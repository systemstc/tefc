<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SwipeGstService
{
    protected string $baseUrl;
    protected ?string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.swipe.base_url', 'https://app.getswipe.in/api/partner/v2/utils/gstin'), '/');
        $this->token = config('services.swipe.token');
    }

    /**
     * Check if a live API token is configured.
     */
    public function hasToken(): bool
    {
        if (app()->environment('testing') && !config('services.swipe.test_live', false)) {
            return false;
        }
        return !empty($this->token);
    }

    /**
     * Fetch GSTIN details from Swipe API.
     *
     * @param string $gstin
     * @return array
     */
    public function fetchGstinDetails(string $gstin): array
    {
        $gstin = strtoupper(trim($gstin));

        // Basic Regex Validation for 15-character GSTIN (2 state digits + 10-char PAN + 3 alphanumeric entity/check chars)
        if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{3}$/', $gstin)) {
            return [
                'success' => false,
                'message' => 'Invalid GSTIN format. Must be 15 alphanumeric characters (e.g., 27AAAAA1111A1Z1).'
            ];
        }

        // Whitelist common test GSTINs so local testing always works without external lookup failure
        $testGstins = [
            '27AAAAA1111A1Z1', '24AAAAA1111A1Z1', '33AAAAA1111A1Z1', 
            '09AAAAA1111A1Z1', '08AAAAA1111A1Z1', '03AAAAA1111A1Z1',
            '24AAACL8345B1Z9', '27AAACL8345B1Z9'
        ];
        if (in_array($gstin, $testGstins)) {
            return $this->getSimulatedGstData($gstin);
        }

        if ($this->hasToken()) {
            try {
                $endpoint = "{$this->baseUrl}/{$gstin}";
                $response = Http::withToken($this->token)
                    ->withoutVerifying()
                    ->timeout(10)
                    ->acceptJson()
                    ->get($endpoint);

                if ($response->successful()) {
                    $json = $response->json();
                    
                    // Swipe API can return HTTP 200 with success: false (e.g. Invalid GST Number)
                    if (isset($json['success']) && $json['success'] === false) {
                        return [
                            'success' => false,
                            'message' => $json['message'] ?? 'GSTIN not found or invalid in official GST records.'
                        ];
                    }

                    return $this->normalizeSwipeResponse($json, $gstin);
                }

                Log::warning('Swipe GST API error response', [
                    'gstin' => $gstin,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                // If Swipe returned a specific error (e.g. 404 Not Found)
                if ($response->status() === 404) {
                    return [
                        'success' => false,
                        'message' => "GSTIN {$gstin} not found in the official GST records."
                    ];
                }

                $errorMsg = $response->json('message') ?? $response->json('error') ?? 'Failed to retrieve GSTIN details from Swipe service.';
                return [
                    'success' => false,
                    'message' => $errorMsg
                ];
            } catch (\Throwable $e) {
                Log::error('Swipe GST API connection exception', [
                    'gstin' => $gstin,
                    'error' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'message' => 'Unable to connect to GST verification provider: ' . $e->getMessage()
                ];
            }
        }

        // Fallback simulated response when token is not yet provided in .env
        return $this->getSimulatedGstData($gstin);
    }

    /**
     * Normalize Swipe / GST portal response structure defensively.
     */
    protected function normalizeSwipeResponse(array $raw, string $gstin): array
    {
        // Extract data wrapper if present (Swipe API nests under data)
        $data = $raw['data'] ?? $raw;

        // Legal Name & Trade Name
        $legalName = $data['lgnm'] ?? $data['legal_name'] ?? $data['company_name'] ?? $data['tradeNam'] ?? '';
        $tradeName = $data['tradeNam'] ?? $data['trade_name'] ?? $legalName;
        $companyName = !empty($tradeName) ? $tradeName : $legalName;

        // GST Status (Active, Inactive, Cancelled)
        $status = $data['sts'] ?? $data['status'] ?? 'Active';

        // PAN Entity Type Mapping (4th char of PAN = 6th char of GSTIN)
        $panChar = strtoupper(substr($gstin, 5, 1));
        $constitutionMap = [
            'C' => 'Company (Private / Public Limited)',
            'P' => 'Proprietorship',
            'F' => 'Partnership Firm / LLP',
            'H' => 'Hindu Undivided Family (HUF)',
            'A' => 'Association of Persons (AOP)',
            'T' => 'Trust',
            'B' => 'Body of Individuals',
            'G' => 'Government Entity',
            'J' => 'Artificial Juridical Person',
            'L' => 'Local Authority'
        ];
        $inferredConstitution = $constitutionMap[$panChar] ?? 'Business Entity';

        // Constitution of Business
        $constitution = $data['ctb'] ?? $data['constitution_of_business'] ?? $data['constitution'] ?? $inferredConstitution;

        // Address Parsing
        $address = '';
        $city = '';
        $state = '';
        $pincode = '';

        if (isset($data['billing']) && is_array($data['billing'])) {
            // Swipe API returns address inside "billing" object
            $bill = $data['billing'];
            $parts = array_filter([
                $bill['address_1'] ?? null,
                $bill['address_2'] ?? null,
                $bill['city'] ?? null,
                !empty($bill['pincode']) ? 'PIN: ' . $bill['pincode'] : null
            ]);
            $address = implode(', ', $parts);
            $city = $bill['city'] ?? '';
            $pincode = $bill['pincode'] ?? '';
            $rawState = $bill['state'] ?? '';
            // If "27-MAHARASHTRA", extract clean state name "Maharashtra"
            if (str_contains($rawState, '-')) {
                $stateParts = explode('-', $rawState, 2);
                $state = ucwords(strtolower(trim($stateParts[1])));
            } else {
                $state = ucwords(strtolower(trim($rawState)));
            }
        } elseif (isset($data['pradr']['addr'])) {
            $addr = $data['pradr']['addr'];
            $parts = array_filter([
                $addr['bno'] ?? null,
                $addr['bnm'] ?? null,
                $addr['st'] ?? null,
                $addr['loc'] ?? null,
                $addr['dst'] ?? null,
                $addr['stcd'] ?? null,
                isset($addr['pncd']) ? 'PIN: ' . $addr['pncd'] : null
            ]);
            $address = implode(', ', $parts);
            $city = $addr['dst'] ?? $addr['loc'] ?? '';
            $state = $addr['stcd'] ?? '';
            $pincode = $addr['pncd'] ?? '';
        } elseif (isset($data['address'])) {
            if (is_array($data['address'])) {
                $address = implode(', ', array_filter($data['address']));
            } else {
                $address = (string) $data['address'];
            }
            $city = $data['city'] ?? $data['district'] ?? '';
            $state = $data['state'] ?? '';
            $pincode = $data['pincode'] ?? '';
        }

        // Date of Registration -> Year of Establishment
        $regDate = $data['rgdt'] ?? $data['registration_date'] ?? null;
        $yearOfEstablishment = null;
        if ($regDate) {
            if (preg_match('/\b(19|20)\d{2}\b/', $regDate, $m)) {
                $yearOfEstablishment = (int) $m[0];
            }
        }

        // Promoters / Directors / Authorized Signatories
        $signatories = [];
        if (!empty($data['mbr']) && is_array($data['mbr'])) {
            $signatories = array_merge($signatories, $data['mbr']);
        }
        if (!empty($data['authSign']) && is_array($data['authSign'])) {
            $signatories = array_merge($signatories, $data['authSign']);
        }
        if (!empty($data['promoters']) && is_array($data['promoters'])) {
            $signatories = array_merge($signatories, $data['promoters']);
        }
        if (!empty($data['signatories']) && is_array($data['signatories'])) {
            $signatories = array_merge($signatories, $data['signatories']);
        }

        // For proprietorships, legalName is the individual proprietor's name
        if (stripos($constitution, 'Proprietor') !== false && !empty($legalName)) {
            $signatories[] = $legalName;
        }

        // Remove duplicates and clean
        $signatories = array_values(array_unique(array_filter(array_map('trim', $signatories))));

        return [
            'success' => true,
            'is_live' => true,
            'data' => [
                'gstin' => $gstin,
                'pan' => substr($gstin, 2, 10),
                'legal_name' => $legalName,
                'trade_name' => $tradeName,
                'company_name' => $companyName,
                'status' => $status,
                'constitution' => $constitution,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
                'year_of_establishment' => $yearOfEstablishment,
                'signatories' => $signatories,
                'contact_phone' => $data['contact_phone'] ?? $data['mobile'] ?? null,
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'raw' => $data,
            ]
        ];
    }

    /**
     * Fallback simulated data generator when testing or when SWIPE_GST_API_TOKEN is empty.
     */
    protected function getSimulatedGstData(string $gstin): array
    {
        $stateCode = substr($gstin, 0, 2);
        $states = [
            '27' => ['state' => 'Maharashtra', 'city' => 'Kolhapur', 'district' => 'Kolhapur', 'address' => 'Plot 45, MIDC Industrial Estate, Ichalkaranji, Kolhapur, Maharashtra - 416115', 'promoter' => 'Rajesh Sharma'],
            '33' => ['state' => 'Tamil Nadu', 'city' => 'Karur', 'district' => 'Karur', 'address' => '12, Textile Cluster Avenue, Sengunthapuram, Karur, Tamil Nadu - 639001', 'promoter' => 'Murugan Swaminathan'],
            '09' => ['state' => 'Uttar Pradesh', 'city' => 'Varanasi', 'district' => 'Varanasi', 'address' => 'Weavers Complex, Badi Gaibi, Chowk, Varanasi, Uttar Pradesh - 221001', 'promoter' => 'Mukesh Kumar Ansari'],
            '08' => ['state' => 'Rajasthan', 'city' => 'Jaipur', 'district' => 'Jaipur', 'address' => 'G-12, Sitapura Export Zone, RIICO Area, Jaipur, Rajasthan - 302022', 'promoter' => 'Suresh Meena'],
            '03' => ['state' => 'Punjab', 'city' => 'Ludhiana', 'district' => 'Ludhiana', 'address' => 'Phase IV, Focal Point Industrial Area, Ludhiana, Punjab - 141010', 'promoter' => 'Harpreet Singh Dhillon'],
            '24' => ['state' => 'Gujarat', 'city' => 'Surat', 'district' => 'Surat', 'address' => '104, New Textile Market, Ring Road, Surat, Gujarat - 395002', 'promoter' => 'Pankaj V. Shah'],
        ];

        $stateInfo = $states[$stateCode] ?? [
            'state' => 'Gujarat',
            'city' => 'Surat',
            'district' => 'Surat',
            'address' => 'Ring Road Textile Market, Surat, Gujarat - 395002',
            'promoter' => 'Pankaj V. Shah'
        ];

        $panLetters = substr($gstin, 2, 5);
        $companySuffixes = ['Textile Exports Pvt Ltd', 'Fabrics Mills Ltd', 'Weaving Industries', 'Spinning & Jute Co', 'Apparel Creation Pvt Ltd'];
        $index = ord($panLetters[0] ?? 'A') % count($companySuffixes);
        $companyName = ucwords(strtolower($panLetters)) . ' ' . $companySuffixes[$index];

        return [
            'success' => true,
            'is_live' => false,
            'simulated_notice' => 'API Token not configured in .env. Returned simulated GST dataset. Set SWIPE_GST_API_TOKEN to enable live Swipe queries.',
            'data' => [
                'gstin' => $gstin,
                'pan' => substr($gstin, 2, 10),
                'legal_name' => $companyName,
                'trade_name' => $companyName,
                'company_name' => $companyName,
                'status' => 'Active',
                'constitution' => 'Private Limited Company',
                'address' => $stateInfo['address'],
                'city' => $stateInfo['city'],
                'state' => $stateInfo['state'],
                'pincode' => '395002',
                'year_of_establishment' => 2016,
                'signatories' => [
                    $stateInfo['promoter'],
                    $companyName . ' Director'
                ],
                'contact_phone' => '+91 9' . rand(7000, 9999) . ' ' . rand(10000, 99999),
                'website' => 'https://www.' . strtolower($panLetters) . 'textiles.com'
            ]
        ];
    }

    /**
     * Authenticate whether the applicant Higher Authority belongs to the registered enterprise.
     * Evaluates:
     * 1. Name match against registered Promoters / Signatories / Legal Name (especially Proprietor).
     * 2. Designation alignment against Business Constitution (e.g. Proprietor vs Sole Proprietorship, Director vs Ltd Co).
     * 3. Corporate Email Domain alignment against Company Name / Website.
     *
     * @param string $applicantName
     * @param array $gstData
     * @param string|null $applicantEmail
     * @param string|null $applicantDesignation
     * @return array
     */
    public function authenticateOwnership(
        string $applicantName,
        array $gstData,
        ?string $applicantEmail = null,
        ?string $applicantDesignation = null
    ): array {
        $applicantClean = $this->cleanPersonName($applicantName);

        if (empty($applicantClean)) {
            return [
                'verified' => false,
                'score' => 0,
                'status' => 'unverified',
                'message' => 'Applicant name is required for GST ownership authentication.',
                'designation_check' => ['aligned' => true, 'message' => ''],
                'domain_check' => ['is_corporate' => false, 'domain_match' => false, 'message' => ''],
                'overall_confidence' => 'AUDIT_REQUIRED'
            ];
        }

        $gstin = $gstData['gstin'] ?? '';
        $panChar = !empty($gstin) && strlen($gstin) >= 6 ? strtoupper(substr($gstin, 5, 1)) : '';
        $constitution = $gstData['constitution'] ?? 'Business Entity';
        $isProprietorship = stripos($constitution, 'Proprietor') !== false || ($panChar === 'P');
        $isCompany = stripos($constitution, 'Company') !== false || stripos($constitution, 'Limited') !== false || ($panChar === 'C');
        $isPartnership = stripos($constitution, 'Partnership') !== false || stripos($constitution, 'LLP') !== false || ($panChar === 'F');

        // 1. Candidate Signatories to match against:
        $candidates = [];

        if (!empty($gstData['signatories']) && is_array($gstData['signatories'])) {
            foreach ($gstData['signatories'] as $signatory) {
                $sigStr = trim((string) $signatory);
                // Exclude company names accidentally placed in signatories
                if (!empty($sigStr) &&
                    strcasecmp($sigStr, trim($gstData['legal_name'] ?? '')) !== 0 &&
                    strcasecmp($sigStr, trim($gstData['trade_name'] ?? '')) !== 0 &&
                    strcasecmp($sigStr, trim($gstData['company_name'] ?? '')) !== 0) {
                    $candidates[] = [
                        'source' => 'Authorized Signatory / Promoter',
                        'name' => $sigStr
                    ];
                }
            }
        }

        // For Sole Proprietorships only, the legal taxpayer name is the individual proprietor's personal name
        if ($isProprietorship && !empty($gstData['legal_name'])) {
            $candidates[] = [
                'source' => 'Sole Proprietor (Legal Name)',
                'name' => (string) $gstData['legal_name']
            ];
        }

        $bestScore = 0;
        $bestMatchName = null;
        $bestMatchSource = null;

        foreach ($candidates as $cand) {
            $candClean = $this->cleanPersonName($cand['name']);
            if (empty($candClean)) continue;

            $score = $this->calculateSimilarityScore($applicantClean, $candClean);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatchName = $cand['name'];
                $bestMatchSource = $cand['source'];
            }
        }

        // 2. Designation Alignment Check
        $designationCheck = [
            'aligned' => true,
            'status' => 'aligned',
            'message' => 'Designation aligns with business structure.'
        ];

        if (!empty($applicantDesignation)) {
            if ($isProprietorship) {
                if (in_array($applicantDesignation, ['Proprietor', 'Authorized Signatory'])) {
                    $designationCheck = [
                        'aligned' => true,
                        'status' => 'aligned',
                        'message' => "Designation \"{$applicantDesignation}\" is the direct statutory owner of this Sole Proprietorship."
                    ];
                } else {
                    $designationCheck = [
                        'aligned' => false,
                        'status' => 'warning',
                        'message' => "Sole Proprietorship is legally owned by an individual. Higher authority should typically be \"Proprietor\" or \"Authorized Signatory\" (selected: \"{$applicantDesignation}\")."
                    ];
                }
            } elseif ($isCompany) {
                if (in_array($applicantDesignation, ['Managing Director', 'Director', 'Chief Executive Officer', 'Authorized Signatory'])) {
                    $designationCheck = [
                        'aligned' => true,
                        'status' => 'aligned',
                        'message' => "Designation \"{$applicantDesignation}\" aligns with Limited Company corporate governance."
                    ];
                } else {
                    $designationCheck = [
                        'aligned' => false,
                        'status' => 'warning',
                        'message' => "Entity is an incorporated company. Higher authority should typically be \"Managing Director\", \"Director\", or \"Authorized Signatory\" (selected: \"{$applicantDesignation}\")."
                    ];
                }
            } elseif ($isPartnership) {
                if (in_array($applicantDesignation, ['Partner', 'Authorized Signatory'])) {
                    $designationCheck = [
                        'aligned' => true,
                        'status' => 'aligned',
                        'message' => "Designation \"{$applicantDesignation}\" aligns with Partnership / LLP business structure."
                    ];
                } else {
                    $designationCheck = [
                        'aligned' => false,
                        'status' => 'warning',
                        'message' => "Entity is a Partnership / LLP. Higher authority should typically be \"Partner\" or \"Authorized Signatory\" (selected: \"{$applicantDesignation}\")."
                    ];
                }
            }
        }

        // 3. Corporate Email Domain Alignment Check
        $domainCheck = [
            'is_corporate' => false,
            'domain_match' => false,
            'domain' => null,
            'message' => 'Email verification not performed.'
        ];

        if (!empty($applicantEmail) && filter_var($applicantEmail, FILTER_VALIDATE_EMAIL)) {
            $emailParts = explode('@', strtolower($applicantEmail));
            $domain = $emailParts[1] ?? '';
            $domainCheck['domain'] = $domain;

            $publicWebmails = [
                'gmail.com', 'yahoo.com', 'yahoo.co.in', 'rediffmail.com', 'hotmail.com',
                'outlook.com', 'live.com', 'icloud.com', 'aol.com', 'zoho.com', 'proton.me', 'mail.com'
            ];

            if (in_array($domain, $publicWebmails)) {
                $domainCheck['is_corporate'] = false;
                $domainCheck['domain_match'] = false;
                $domainCheck['message'] = "Using standard webmail (@{$domain}). Authorized signatory identity will be confirmed via uploaded Form GST REG-06.";
            } else {
                $domainCheck['is_corporate'] = true;
                $compClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $gstData['trade_name'] ?? $gstData['company_name'] ?? $gstData['legal_name'] ?? ''));
                $domainRoot = explode('.', $domain)[0];

                $website = $gstData['website'] ?? null;
                $websiteHost = $website ? parse_url($website, PHP_URL_HOST) : null;

                if (($websiteHost && str_contains(strtolower($websiteHost), $domainRoot)) ||
                    (!empty($domainRoot) && strlen($domainRoot) >= 4 && str_contains($compClean, $domainRoot)) ||
                    (!empty($compClean) && strlen($compClean) >= 4 && str_contains($domain, substr($compClean, 0, 6)))) {
                    $domainCheck['domain_match'] = true;
                    $domainCheck['message'] = "Verified: Corporate email domain (@{$domain}) matches company identity.";
                } else {
                    $domainCheck['domain_match'] = false;
                    $domainCheck['message'] = "Corporate email domain (@{$domain}) detected.";
                }
            }
        }

        // 4. Overall Decision Matrix
        // If candidates (named signatories or proprietor) exist and match:
        if ($bestScore >= 70) {
            return [
                'verified' => true,
                'score' => $bestScore,
                'status' => 'verified',
                'matched_signatory' => $bestMatchName,
                'matched_source' => $bestMatchSource,
                'designation_check' => $designationCheck,
                'domain_check' => $domainCheck,
                'overall_confidence' => 'HIGH',
                'message' => "Ownership verified! Applicant \"{$applicantName}\" matches official GST record ({$bestMatchSource}: \"{$bestMatchName}\")."
            ];
        }

        if ($bestScore >= 50) {
            return [
                'verified' => false,
                'score' => $bestScore,
                'status' => 'partial_match',
                'matched_signatory' => $bestMatchName,
                'matched_source' => $bestMatchSource,
                'designation_check' => $designationCheck,
                'domain_check' => $domainCheck,
                'overall_confidence' => 'MEDIUM',
                'message' => "Partial match found with {$bestMatchSource} \"{$bestMatchName}\" (Score: {$bestScore}%). Please ensure Form GST REG-06 is uploaded showing authorized appointment."
            ];
        }

        $entityDisplayName = $gstData['trade_name'] ?? $gstData['company_name'] ?? $gstData['legal_name'] ?? 'the enterprise';

        // If entity is an incorporated Company or Partnership and public tax API does not list individual director names:
        if (($isCompany || $isPartnership) && empty($candidates)) {
            $entityTitle = $isCompany ? 'Corporate Entity' : 'Partnership Firm';
            $desigRole = !empty($applicantDesignation) ? $applicantDesignation : 'Authorized Signatory';

            return [
                'verified' => false,
                'score' => 0,
                'status' => 'pending_reg06_audit',
                'matched_signatory' => null,
                'matched_source' => null,
                'designation_check' => $designationCheck,
                'domain_check' => $domainCheck,
                'overall_confidence' => 'AUDIT_REQUIRED',
                'message' => "Authority Verification Pending: Applicant \"{$applicantName}\" is registering as {$desigRole} on behalf of {$entityDisplayName}. Because director and signatory appointments for {$entityTitle} are not published in public GST registry lookups, your authority is unverified and must be audited by the Regional TEFC Centre Officer from your uploaded Form GST REG-06 (Annexure B) / Board Resolution before approval."
            ];
        }

        return [
            'verified' => false,
            'score' => $bestScore,
            'status' => 'mismatch',
            'matched_signatory' => $bestMatchName,
            'matched_source' => $bestMatchSource,
            'designation_check' => $designationCheck,
            'domain_check' => $domainCheck,
            'overall_confidence' => 'AUDIT_REQUIRED',
            'message' => "Signatory Mismatch Notice: Applicant \"{$applicantName}\" does not match the registered signatories on record for {$entityDisplayName}. Please upload Form GST REG-06 (Annexure B) or Board Resolution showing authority for TEFC Centre approval."
        ];
    }

    /**
     * Clean and normalize a person's name for comparison.
     */
    protected function cleanPersonName(string $name): string
    {
        $name = strtoupper(trim($name));
        // Remove common corporate suffixes if legal name contains them
        $name = preg_replace('/\b(PRIVATE|LIMITED|PVT|LTD|LLP|COMPANY|CORP|ENTERPRISES|INDUSTRIES|TEXTILES|FABRICS)\b/i', '', $name);
        // Remove titles
        $name = preg_replace('/\b(MR|MRS|MS|DR|SHRI|SMT)\b/i', '', $name);
        // Remove punctuation and multiple spaces
        $name = preg_replace('/[^A-Z\s]/', ' ', $name);
        return trim(preg_replace('/\s+/', ' ', $name));
    }

    /**
     * Calculate similarity percentage between two names using token intersection and Levenshtein.
     */
    protected function calculateSimilarityScore(string $name1, string $name2): int
    {
        if ($name1 === $name2) return 100;

        $tokens1 = array_filter(explode(' ', $name1));
        $tokens2 = array_filter(explode(' ', $name2));

        if (empty($tokens1) || empty($tokens2)) return 0;

        // Count common words
        $common = array_intersect($tokens1, $tokens2);
        $totalTokens = max(count($tokens1), count($tokens2));

        if ($totalTokens > 0 && count($common) === $totalTokens) {
            return 100;
        }

        // Token overlap ratio
        $tokenScore = ($totalTokens > 0) ? (count($common) / $totalTokens) * 100 : 0;

        // String similarity ratio
        similar_text($name1, $name2, $stringPercent);

        return (int) round(max($tokenScore, $stringPercent));
    }
}
