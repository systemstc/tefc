<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BhashiniService
{
    protected ?string $userId;
    protected ?string $apiKey;
    protected ?string $inferenceApiKey;
    protected ?string $pipelineId;
    protected string $ulcaBaseUrl;
    protected string $inferenceBaseUrl;

    public function __construct()
    {
        $config = config('services.bhashini', []);
        $this->userId = $config['user_id'] ?? null;
        $this->apiKey = $config['api_key'] ?? null;
        $this->inferenceApiKey = $config['inference_api_key'] ?: $this->apiKey;
        $this->pipelineId = $config['pipeline_id'] ?? null;
        $this->ulcaBaseUrl = $config['ulca_base_url'] ?? 'https://meity-auth.ulcacontrib.org/ulca/apis/v0/model/getModelsPipeline';
        $this->inferenceBaseUrl = $config['inference_base_url'] ?? 'https://dhruva-api.bhashini.gov.in/services/inference/pipeline';
    }

    /**
     * Check if Bhashini API keys are configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) || !empty($this->inferenceApiKey);
    }

    /**
     * Get list of Indian languages supported with special textile cluster badges.
     */
    public function getLanguages(): array
    {
        return [
            [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
                'cluster' => 'Default',
                'is_cluster' => true,
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'native' => 'हिन्दी',
                'cluster' => 'Varanasi, Gorakhpur, Panipat, Jaipur',
                'is_cluster' => true,
            ],
            [
                'code' => 'gu',
                'name' => 'Gujarati',
                'native' => 'ગુજરાતી',
                'cluster' => 'Surat, Ahmedabad, Rajkot',
                'is_cluster' => true,
            ],
            [
                'code' => 'ta',
                'name' => 'Tamil',
                'native' => 'தமிழ்',
                'cluster' => 'Tirupur, Karur, Coimbatore',
                'is_cluster' => true,
            ],
            [
                'code' => 'mr',
                'name' => 'Marathi',
                'native' => 'मराठी',
                'cluster' => 'Ichalkaranji, Solapur, Bhiwandi',
                'is_cluster' => true,
            ],
            [
                'code' => 'pa',
                'name' => 'Punjabi',
                'native' => 'ਪੰਜਾਬੀ',
                'cluster' => 'Ludhiana, Amritsar',
                'is_cluster' => true,
            ],
            [
                'code' => 'bn',
                'name' => 'Bengali',
                'native' => 'বাংলা',
                'cluster' => 'Kolkata, Shantipur',
                'is_cluster' => true,
            ],
            [
                'code' => 'te',
                'name' => 'Telugu',
                'native' => 'తెలుగు',
                'cluster' => 'Warangal, Dharmavaram',
                'is_cluster' => false,
            ],
            [
                'code' => 'kn',
                'name' => 'Kannada',
                'native' => 'ಕನ್ನಡ',
                'cluster' => 'Bangalore, Ilkal',
                'is_cluster' => false,
            ],
            [
                'code' => 'ml',
                'name' => 'Malayalam',
                'native' => 'മലയാളം',
                'cluster' => 'Kannur, Balaramapuram',
                'is_cluster' => false,
            ],
            [
                'code' => 'or',
                'name' => 'Odia',
                'native' => 'ଓଡ଼ିଆ',
                'cluster' => 'Sambalpur, Berhampur',
                'is_cluster' => false,
            ],
            [
                'code' => 'as',
                'name' => 'Assamese',
                'native' => 'অসমীয়া',
                'cluster' => 'Sualkuchi',
                'is_cluster' => false,
            ],
            [
                'code' => 'ur',
                'name' => 'Urdu',
                'native' => 'اردو',
                'cluster' => 'Lucknow, Hyderabad',
                'is_cluster' => false,
            ],
        ];
    }

    /**
     * Translate one or more text strings via Bhashini Dhruva NMT inference.
     *
     * @param string|array $text
     * @param string $targetLang ISO code (e.g. 'hi', 'gu', 'ta')
     * @param string $sourceLang ISO code (default 'en')
     * @return array
     */
    public function translate(string|array $text, string $targetLang, string $sourceLang = 'en'): array
    {
        $isArray = is_array($text);
        $inputs = $isArray ? $text : [$text];

        if ($targetLang === $sourceLang) {
            return [
                'success' => true,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'translations' => $inputs,
                'single_translation' => $isArray ? null : ($inputs[0] ?? ''),
                'provider' => 'noop',
            ];
        }

        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Bhashini API credentials not configured in environment (.env).',
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'translations' => $inputs,
                'single_translation' => $isArray ? null : ($inputs[0] ?? ''),
                'provider' => 'unconfigured',
            ];
        }

        try {
            $formattedInput = array_map(function ($item) {
                return ['source' => (string) $item];
            }, $inputs);

            $payload = [
                'pipelineTasks' => [
                    [
                        'taskType' => 'translation',
                        'config' => [
                            'language' => [
                                'sourceLanguage' => $sourceLang,
                                'targetLanguage' => $targetLang,
                            ],
                        ],
                    ],
                ],
                'inputData' => [
                    'input' => $formattedInput,
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => $this->inferenceApiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(12)->post($this->inferenceBaseUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $outputs = $data['pipelineResponse'][0]['output'] ?? [];
                $translations = [];

                foreach ($outputs as $out) {
                    $translations[] = $out['target'] ?? '';
                }

                return [
                    'success' => true,
                    'source_lang' => $sourceLang,
                    'target_lang' => $targetLang,
                    'translations' => $translations,
                    'single_translation' => $isArray ? null : ($translations[0] ?? ''),
                    'provider' => 'bhashini_dhruva',
                ];
            }

            Log::warning('Bhashini API request returned error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Bhashini service returned status ' . $response->status(),
                'error_detail' => $response->json() ?: $response->body(),
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'translations' => $inputs,
                'single_translation' => $isArray ? null : ($inputs[0] ?? ''),
                'provider' => 'error_fallback',
            ];
        } catch (\Throwable $e) {
            Log::error('Bhashini translation exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Bhashini service connection failed: ' . $e->getMessage(),
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'translations' => $inputs,
                'single_translation' => $isArray ? null : ($inputs[0] ?? ''),
                'provider' => 'exception_fallback',
            ];
        }
    }
}
