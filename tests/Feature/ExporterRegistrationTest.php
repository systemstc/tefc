<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\ExporterProfile;
use Tests\TestCase;

class ExporterRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);
    }

    public function test_it_validates_gst_lookup_format()
    {
        // Invalid GST
        $response = $this->getJson('/api/gst-lookup?gst_no=INVALID_GST');
        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);

        // Valid GST (27 Maharashtra, AAAAA1111A, 1, Z, 1)
        $response = $this->getJson('/api/gst-lookup?gst_no=27AAAAA1111A1Z1');
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'company_name',
                'address',
                'city',
                'state',
                'contact_phone',
                'website'
            ]
        ]);
    }

    public function test_it_registers_buyer_successfully()
    {
        $response = $this->post('/register', [
            'name'                 => 'Test Buyer',
            'email'                => 'buyer@test.com',
            'role'                 => 'buyer',
            'company_name'         => 'Global Garments Inc.',
            'contact_phone'        => '+1 212 555 0199',
            'address'              => '123 Main Street, New York, NY 10001',
            'country'              => 'United States',
            'website'              => 'https://www.globalgarments.com',
            'sourcing_requirements'=> 'Cotton fabrics, minimum 5000 metres per order.',
            'password'             => 'password123',
            'password_confirmation'=> 'password123'
        ]);

        $response->assertRedirect('/exporter-portal');
        $this->assertDatabaseHas('users', [
            'name'   => 'Test Buyer',
            'email'  => 'buyer@test.com',
            'status' => 'pending'
        ]);
        $this->assertDatabaseHas('buyer_profiles', [
            'company_name'  => 'Global Garments Inc.',
            'contact_phone' => '+1 212 555 0199',
            'address'       => '123 Main Street, New York, NY 10001',
            'country'       => 'United States',
            'website'       => 'https://www.globalgarments.com',
        ]);
    }

    public function test_it_validates_seller_registration_fields()
    {
        // Missing required seller fields
        $response = $this->post('/register', [
            'name' => 'Test Seller',
            'email' => 'seller@test.com',
            'role' => 'seller',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors([
            'designation', 'gst_no', 'company_name', 'certificate', 'contact_phone',
            'factory_address', 'district', 'state', 'year_of_establishment',
            'textile_segment', 'main_product_hsn', 'installed_capacity',
            'capacity_unit', 'monthly_production', 'major_machineries',
            'annual_turnover', 'is_exporter'
        ]);
    }

    public function test_it_registers_seller_successfully_with_certificate()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('certificate.pdf', 500, 'application/pdf');
        $img1 = UploadedFile::fake()->image('product1.jpg');
        $img2 = UploadedFile::fake()->image('product2.jpg');

        $response = $this->post('/register', [
            'name' => 'Main Contact',
            'designation' => 'Managing Director',
            'email' => 'seller@test.com',
            'role' => 'seller',
            'company_name' => 'Alpha Textiles Ltd',
            'gst_no' => '27AAAAA1111A1Z1',
            'iec_no' => '0123456789',
            'contact_phone' => '+91 9876543210',
            'website' => 'https://alpha.com',
            'certificate' => $file,
            'factory_address' => '123 Textile Lane, Sector 5',
            'district' => 'Kolhapur',
            'state' => 'Maharashtra',
            'year_of_establishment' => 2010,
            'textile_segment' => 'Spinning',
            'main_product_hsn' => '5208',
            'installed_capacity' => '100000',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '80000',
            'major_machineries' => 'High speed automatic weaving looms',
            'annual_turnover' => '5 to 25 Crores',
            'is_exporter' => 'Y',
            'export_turnover' => '3 Crores',
            'major_exporting_countries' => 'USA, UK',
            'certifications' => 'ISO 9001',
            'product_images' => [$img1, $img2],
            'export_potential' => 'High potential in US markets',
            'remarks' => 'N/A',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertRedirect('/login');

        // Check user creation
        $user = User::where('email', 'seller@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pending', $user->status);
        $this->assertTrue($user->hasRole('seller'));

        // Check profile creation
        $profile = ExporterProfile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertEquals('Alpha Textiles Ltd', $profile->company_name);
        $this->assertEquals('27AAAAA1111A1Z1', $profile->gst_no);
        $this->assertEquals('0123456789', $profile->iec_code);
        $this->assertEquals('+91 9876543210', $profile->contact_phone);
        $this->assertEquals('https://alpha.com', $profile->website);
        $this->assertEquals('123 Textile Lane, Sector 5', $profile->factory_address);
        $this->assertEquals('Kolhapur', $profile->district);
        $this->assertEquals('Maharashtra', $profile->state);
        $this->assertEquals(2010, $profile->year_of_establishment);
        $this->assertEquals('Spinning', $profile->textile_segment);
        $this->assertEquals('5208', $profile->main_product_hsn);
        $this->assertEquals('100000', $profile->installed_capacity);
        $this->assertEquals('Meters/Month', $profile->capacity_unit);
        $this->assertEquals('80000', $profile->monthly_production);
        $this->assertEquals('High speed automatic weaving looms', $profile->major_machineries);
        $this->assertEquals('5 to 25 Crores', $profile->annual_turnover);
        $this->assertEquals('Y', $profile->is_exporter);
        $this->assertEquals('3 Crores', $profile->export_turnover);
        $this->assertEquals('USA, UK', $profile->major_exporting_countries);
        $this->assertEquals('ISO 9001', $profile->certifications);
        $this->assertEquals('High potential in US markets', $profile->export_potential);
        $this->assertEquals('N/A', $profile->remarks);

        // Check certificate upload storage
        $this->assertNotNull($profile->certificate_path);
        Storage::disk('public')->assertExists($profile->certificate_path);

        // Check product images upload storage
        $this->assertCount(2, $profile->product_images);
        foreach ($profile->product_images as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_it_registers_exporter_without_iec_no_successfully()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Domestic Manufacturer',
            'email' => 'domestic.' . uniqid() . '@test.com',
            'role' => 'exporter',
            'designation' => 'Managing Partner',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gst_no' => '24AAAAA1111A1Z1',
            // iec_no is omitted
            'company_name' => 'Surat Weaving Unit',
            'contact_phone' => '+91 9876543210',
            'factory_address' => 'GIDC Sachin, Surat',
            'district' => 'Surat',
            'state' => 'Gujarat',
            'year_of_establishment' => 2018,
            'textile_segment' => 'Weaving',
            'main_product_hsn' => '5208',
            'installed_capacity' => '50000',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '40000',
            'major_machineries' => 'Water jet looms',
            'annual_turnover' => '1 to 5 Crores',
            'is_exporter' => 'N',
            'certificate' => UploadedFile::fake()->create('gst_reg.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect('/login');
        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertNull($user->exporterProfile->iec_code);
        $this->assertEquals('N', $user->exporterProfile->is_exporter);
    }

    public function test_it_registers_exporter_with_custom_textile_segment_other()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Specialized Artisan',
            'email' => 'artisan.' . uniqid() . '@test.com',
            'role' => 'exporter',
            'designation' => 'Proprietor',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gst_no' => '09AAAAA1111A1Z1',
            'company_name' => 'Banaras Handloom Heritage',
            'contact_phone' => '+91 9876543210',
            'factory_address' => 'Chowk, Varanasi',
            'district' => 'Varanasi',
            'state' => 'Uttar Pradesh',
            'year_of_establishment' => 2015,
            'textile_segment' => 'Other',
            'textile_segment_other' => 'Zari & Brocade Handloom',
            'main_product_hsn' => '5007',
            'installed_capacity' => '1000',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '800',
            'major_machineries' => 'Traditional Jacquard Pit Looms',
            'annual_turnover' => 'Under 1 Crore',
            'is_exporter' => 'N',
            'certificate' => UploadedFile::fake()->create('gst_reg.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect('/login');
        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals('Zari & Brocade Handloom', $user->exporterProfile->textile_segment);
    }

    public function test_it_automatically_activates_is_exporter_when_iec_is_provided()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Exporting Unit',
            'email' => 'exporter.' . uniqid() . '@test.com',
            'role' => 'exporter',
            'designation' => 'Director',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gst_no' => '33AAAAA1111A1Z1',
            'iec_no' => '0512345678', // User entered IEC
            'company_name' => 'Karur Home Textiles Ltd',
            'contact_phone' => '+91 9876543210',
            'factory_address' => 'Sengunthapuram, Karur',
            'district' => 'Karur',
            'state' => 'Tamil Nadu',
            'year_of_establishment' => 2012,
            'textile_segment' => 'Garmenting',
            'main_product_hsn' => '6302',
            'installed_capacity' => '30000',
            'capacity_unit' => 'Pieces/Month',
            'monthly_production' => '25000',
            'major_machineries' => 'Industrial sewing and quilting machines',
            'annual_turnover' => '5 to 25 Crores',
            'is_exporter' => 'Y',
            'export_turnover' => '10 Crores',
            'major_exporting_countries' => 'USA, Germany',
            'certificate' => UploadedFile::fake()->create('gst_reg.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect('/login');
        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals('0512345678', $user->exporterProfile->iec_code);
        $this->assertEquals('Y', $user->exporterProfile->is_exporter);
        $this->assertEquals('10 Crores', $user->exporterProfile->export_turnover);
    }

    public function test_register_page_renders_with_bhashini_and_accessibility_topbar()
    {
        $response = $this->get('/register?role=exporter');
        $response->assertStatus(200);
        $response->assertSee('Government of India');
        $response->assertSee('Ministry of Textiles');
        $response->assertSee('Bhashini');
        $response->assertSee('btnFontDec');
        $response->assertSee('btnFontInc');
        $response->assertSee('btnContrastToggle');
        $response->assertSee('bhashiniDropdownBtn');
        $response->assertSee('google_translate_element');
        $response->assertSee('iecInput');
        $response->assertSee('otherSegmentGroup');
    }

    public function test_it_normalizes_buyer_website_without_http_scheme()
    {
        $response = $this->post('/register', [
            'name'                 => 'International Buyer',
            'email'                => 'intlbuyer@test.com',
            'role'                 => 'buyer',
            'company_name'         => 'Textile Global Corp',
            'contact_phone'        => '+1 415 555 2671',
            'address'              => '456 Fashion Ave, Suite 300, NY',
            'country'              => 'USA',
            'website'              => 'www.textileglobal.com',
            'password'             => 'password123',
            'password_confirmation'=> 'password123'
        ]);

        $response->assertRedirect('/exporter-portal');
        $this->assertDatabaseHas('buyer_profiles', [
            'company_name' => 'Textile Global Corp',
            'website'      => 'https://www.textileglobal.com',
        ]);
    }

    public function test_buyer_registration_validates_required_fields()
    {
        $response = $this->post('/register', [
            'name'                 => '',
            'email'                => 'not-an-email',
            'role'                 => 'buyer',
            'company_name'         => '',
            'contact_phone'        => '',
            'address'              => '',
            'password'             => 'short',
            'password_confirmation'=> 'different'
        ]);

        $response->assertSessionHasErrors([
            'name', 'email', 'company_name', 'contact_phone', 'address', 'password'
        ]);
    }

    public function test_header_renders_login_dropdown_with_user_and_office_options()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('navLoginDropdownBtn');
        $response->assertSee('User Login');
        $response->assertSee('Office Login');
        $response->assertSee('navLoginMenu');
    }
}
