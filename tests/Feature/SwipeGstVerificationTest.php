<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\TefcCentre;
use App\Models\ExporterProfile;
use App\Services\SwipeGstService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SwipeGstVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure exporter role exists
        Role::firstOrCreate(['slug' => 'exporter'], ['name' => 'Exporter']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Exporter / Seller']);

        // Enable Swipe API pathway for this specific test suite
        config(['services.swipe.test_live' => true]);

        // Mock Swipe API endpoint for deterministic testing
        Http::fake([
            'https://app.getswipe.in/api/partner/v2/utils/gstin/24AAACL8345B1Z9' => Http::response([
                'success' => true,
                'message' => 'Active',
                'data' => [
                    'gstin' => '24AAACL8345B1Z9',
                    'legal_name' => 'Surat Textiles Ltd',
                    'trade_name' => 'Surat Textiles Ltd',
                    'company_name' => 'Surat Textiles Ltd',
                    'status' => 'Active',
                    'constitution' => 'Company (Private / Public Limited)',
                    'signatories' => ['Pankaj V. Shah'],
                    'billing' => [
                        'address_1' => 'Plot 104, Ring Road Textile Market',
                        'city' => 'Surat',
                        'pincode' => '395002',
                        'state' => '24-GUJARAT'
                    ]
                ]
            ], 200),
            'https://app.getswipe.in/api/partner/v2/utils/gstin/27AAHCP2474M1ZD' => Http::response([
                'success' => true,
                'message' => 'Active',
                'data' => [
                    'gstin' => '27AAHCP2474M1ZD',
                    'legal_name' => 'Pacifier Solutions Private Limited',
                    'trade_name' => 'Pacifier Solutions Private Limited',
                    'company_name' => 'Pacifier Solutions Private Limited',
                    'status' => 'Active',
                    'constitution' => 'Private Limited Company',
                    'signatories' => [],
                    'billing' => [
                        'address_1' => 'Office 501, Business Park',
                        'city' => 'Ichalkaranji',
                        'pincode' => '416115',
                        'state' => '27-MAHARASHTRA'
                    ]
                ]
            ], 200),
            'https://app.getswipe.in/api/partner/v2/utils/gstin/*' => Http::response([
                'success' => false,
                'message' => 'Invalid GST Number'
            ], 200)
        ]);
    }

    public function test_gst_lookup_validates_format()
    {
        $response = $this->getJson('/api/gst-lookup?gst_no=INVALID123');
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false
        ]);
    }

    public function test_gst_lookup_fetches_details_and_authenticates_matching_applicant()
    {
        // 24 (Gujarat/Surat) promoter in simulated fallback is 'Pankaj V. Shah'
        $response = $this->getJson('/api/gst-lookup?gst_no=24AAACL8345B1Z9&applicant_name=Pankaj%20Shah');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'gstin' => '24AAACL8345B1Z9',
                'state' => 'Gujarat'
            ],
            'ownership' => [
                'verified' => true,
                'status' => 'verified'
            ]
        ]);
    }

    public function test_gst_lookup_detects_mismatch_for_stranger_applicant()
    {
        $response = $this->getJson('/api/gst-lookup?gst_no=24AAACL8345B1Z9&applicant_name=Johnathan%20Doe');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'ownership' => [
                'verified' => false,
                'status' => 'mismatch'
            ]
        ]);
    }

    public function test_exporter_registration_persists_gst_verification_metadata()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Pankaj V. Shah',
            'email' => 'pankaj.shah.' . uniqid() . '@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'role' => 'exporter',
            'designation' => 'Managing Director',
            'gst_no' => '24AAACL8345B1Z9',
            'iec_no' => '0123456789',
            'company_name' => 'Surat Textiles Ltd',
            'year_of_establishment' => 2018,
            'contact_phone' => '+91 9876543210',
            'factory_address' => 'Plot 104, Ring Road Textile Market, Surat',
            'state' => 'Gujarat',
            'district' => 'Surat',
            'textile_segment' => 'Weaving',
            'main_product_hsn' => '5208',
            'installed_capacity' => '50000',
            'major_machineries' => 'Air jet looms and high-speed sizing',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '45000',
            'annual_turnover' => '5 to 25 Crores',
            'is_exporter' => 'N',
            'certificate' => UploadedFile::fake()->create('gst_reg_06.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'status' => 'pending',
            'tefc_centre' => 'surat',
        ]);

        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user->exporterProfile);
        
        // Assert GST metadata was captured
        $profile = $user->exporterProfile;
        $this->assertEquals('24AAACL8345B1Z9', $profile->gst_no);
        $this->assertNotEmpty($profile->gst_legal_name);
        $this->assertEquals('Active', $profile->gst_status);
        $this->assertTrue($profile->gst_ownership_verified);
        $this->assertEquals('Pankaj V. Shah', $profile->gst_matched_signatory);
    }

    public function test_gst_lookup_evaluates_designation_and_email_domain_alignment()
    {
        $response = $this->getJson('/api/gst-lookup?gst_no=24AAACL8345B1Z9&applicant_name=Pankaj%20Shah&applicant_email=pankaj@aaacltextiles.com&applicant_designation=Managing%20Director');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'ownership' => [
                'verified' => true,
                'status' => 'verified',
                'designation_check' => [
                    'aligned' => true,
                    'status' => 'aligned'
                ],
                'domain_check' => [
                    'is_corporate' => true,
                    'domain_match' => true
                ],
                'overall_confidence' => 'HIGH'
            ]
        ]);
    }

    public function test_gst_lookup_flags_public_webmail_and_designation_notice()
    {
        $response = $this->getJson('/api/gst-lookup?gst_no=24AAACL8345B1Z9&applicant_name=Pankaj%20Shah&applicant_email=pankaj123@gmail.com&applicant_designation=Proprietor');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'ownership' => [
                'verified' => true,
                'designation_check' => [
                    'aligned' => false,
                    'status' => 'warning'
                ],
                'domain_check' => [
                    'is_corporate' => false,
                    'domain_match' => false
                ]
            ]
        ]);
    }

    public function test_corporate_entity_without_public_signatories_requires_reg06_audit_and_is_not_verified()
    {
        // When applicant enters a real company GST (like Pacifier Solutions) but is not in signatories
        $response = $this->getJson('/api/gst-lookup?gst_no=27AAHCP2474M1ZD&applicant_name=Yakub%20Ali%20Shaikh&applicant_designation=Managing%20Director');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'ownership' => [
                'verified' => false,
                'status' => 'pending_reg06_audit',
                'overall_confidence' => 'AUDIT_REQUIRED'
            ]
        ]);
        $this->assertFalse($response->json('ownership.verified'));
    }

    public function test_corporate_registration_saves_ownership_verified_false_when_unauthenticated()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Yakub Ali Shaikh',
            'email' => 'yakub.test.' . uniqid() . '@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'role' => 'exporter',
            'designation' => 'Director',
            'gst_no' => '27AAHCP2474M1ZD',
            'iec_no' => '0123456789',
            'company_name' => 'Pacifier Solutions Private Limited',
            'year_of_establishment' => 2020,
            'contact_phone' => '+91 9123456780',
            'factory_address' => 'Office 501, Business Park, Ichalkaranji',
            'state' => 'Maharashtra',
            'district' => 'Kolhapur',
            'textile_segment' => 'Garmenting',
            'main_product_hsn' => '6205',
            'installed_capacity' => '25000',
            'major_machineries' => 'Juki industrial sewing machines',
            'capacity_unit' => 'Pieces/Month',
            'monthly_production' => '20000',
            'annual_turnover' => '1 to 5 Crores',
            'is_exporter' => 'N',
            'certificate' => UploadedFile::fake()->create('gst_reg_06.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect('/login');
        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user->exporterProfile);
        
        $profile = $user->exporterProfile;
        $this->assertEquals('27AAHCP2474M1ZD', $profile->gst_no);
        $this->assertFalse((bool) $profile->gst_ownership_verified, 'Unproven corporate applicant must NOT be saved as verified!');
        $this->assertNull($profile->gst_matched_signatory);
    }
}
