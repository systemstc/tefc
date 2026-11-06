<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\ExporterProfile;
use App\Models\TefcCentre;
use Tests\TestCase;

class TefcCentreWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected Role $adminRole;
    protected Role $centerRole;
    protected Role $exporterRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['slug' => 'admin', 'name' => 'Administrator']);
        $this->centerRole = Role::firstOrCreate(['slug' => 'center', 'name' => 'TEFC Centre Officer']);
        $this->exporterRole = Role::firstOrCreate(['slug' => 'exporter', 'name' => 'Exporter']);
        Role::firstOrCreate(['slug' => 'seller', 'name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'buyer', 'name' => 'Buyer']);
    }

    public function test_exporter_registration_with_eligible_cluster_district_auto_assigns_tefc_centre()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('proof.pdf', 300, 'application/pdf');

        $response = $this->post('/register', [
            'name' => 'Surat Weave Master',
            'designation' => 'Managing Director',
            'email' => 'weave@suratexports.com',
            'role' => 'exporter',
            'company_name' => 'Surat Silk Prints Ltd',
            'gst_no' => '24ABCDE1234F1Z5',
            'iec_no' => '0512345678',
            'contact_phone' => '+91 98250 12345',
            'website' => 'https://suratexports.com',
            'certificate' => $file,
            'factory_address' => 'Plot 88, GIDC Industrial Estate, Sachin, Surat',
            'state' => 'Gujarat',
            'district' => 'Surat',
            'year_of_establishment' => 2012,
            'textile_segment' => 'Weaving',
            'main_product_hsn' => '5407',
            'installed_capacity' => '50000',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '42000',
            'major_machineries' => 'Water jet looms and rapier machinery',
            'annual_turnover' => '5 to 25 Crores',
            'is_exporter' => 'Y',
            'export_turnover' => '8 Crores',
            'major_exporting_countries' => 'UAE, Saudi Arabia',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertRedirect('/login');

        $user = User::where('email', 'weave@suratexports.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pending', $user->status);
        $this->assertEquals('surat', $user->tefc_centre);
        $this->assertTrue($user->hasRole('exporter'));

        $profile = ExporterProfile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertEquals('surat', $profile->tefc_centre);
        $this->assertEquals('Surat Silk Prints Ltd', $profile->company_name);
    }

    public function test_exporter_registration_outside_cluster_district_is_rejected()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('proof.pdf', 300, 'application/pdf');

        $response = $this->from('/register')->post('/register', [
            'name' => 'Pune Textile Trader',
            'designation' => 'Managing Director',
            'email' => 'trader@pune.com',
            'role' => 'exporter',
            'company_name' => 'Pune Fab',
            'gst_no' => '27ABCDE1234F1Z5',
            'iec_no' => '0512345678',
            'contact_phone' => '+91 98250 12345',
            'certificate' => $file,
            'factory_address' => 'Pune City Area',
            'state' => 'Maharashtra',
            'district' => 'Pune', // Ineligible district (Kolhapur is the eligible cluster for Maharashtra)
            'year_of_establishment' => 2012,
            'textile_segment' => 'Weaving',
            'main_product_hsn' => '5407',
            'installed_capacity' => '50000',
            'capacity_unit' => 'Meters/Month',
            'monthly_production' => '42000',
            'major_machineries' => 'Looms',
            'annual_turnover' => '5 to 25 Crores',
            'is_exporter' => 'N',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['district']);
        $this->assertDatabaseMissing('users', ['email' => 'trader@pune.com']);
    }

    public function test_centre_officer_can_view_cluster_dashboard_and_applications()
    {
        $centreUser = User::create([
            'name' => 'Surat Centre Officer',
            'email' => 'center.surat@tefc.gov.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'surat',
        ]);
        $centreUser->roles()->attach($this->centerRole);

        $response = $this->actingAs($centreUser)->get('/center/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Surat Facilitation Centre');
        $response->assertSee('Gujarat');

        $appResponse = $this->actingAs($centreUser)->get('/center/applications');
        $appResponse->assertStatus(200);
        $appResponse->assertSee('Surat Exporter Applications');
    }

    public function test_centre_officer_can_accept_and_approve_application()
    {
        $centreUser = User::create([
            'name' => 'Surat Officer',
            'email' => 'officer.surat@tefc.gov.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'surat',
        ]);
        $centreUser->roles()->attach($this->centerRole);

        $applicant = User::create([
            'name' => 'Applicant One',
            'email' => 'applicant1@surat.com',
            'password' => bcrypt('password'),
            'status' => 'pending',
            'tefc_centre' => 'surat',
        ]);
        $applicant->roles()->attach($this->exporterRole);

        ExporterProfile::create([
            'user_id' => $applicant->id,
            'tefc_centre' => 'surat',
            'company_name' => 'Surat Textiles Alpha',
            'district' => 'Surat',
            'state' => 'Gujarat',
        ]);

        $response = $this->actingAs($centreUser)->post("/center/applications/{$applicant->id}/status", [
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertEquals('active', $applicant->fresh()->status);
    }

    public function test_centre_officer_can_reject_application_with_reason()
    {
        $centreUser = User::create([
            'name' => 'Surat Officer',
            'email' => 'officer.surat2@tefc.gov.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'surat',
        ]);
        $centreUser->roles()->attach($this->centerRole);

        $applicant = User::create([
            'name' => 'Applicant Two',
            'email' => 'applicant2@surat.com',
            'password' => bcrypt('password'),
            'status' => 'pending',
            'tefc_centre' => 'surat',
        ]);
        $applicant->roles()->attach($this->exporterRole);

        $profile = ExporterProfile::create([
            'user_id' => $applicant->id,
            'tefc_centre' => 'surat',
            'company_name' => 'Invalid Docs Corp',
            'district' => 'Surat',
            'state' => 'Gujarat',
        ]);

        $response = $this->actingAs($centreUser)->post("/center/applications/{$applicant->id}/status", [
            'status' => 'rejected',
            'rejection_reason' => 'Factory address not physically verifiable within Surat jurisdiction.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('rejected', $applicant->fresh()->status);
        $this->assertEquals(
            'Factory address not physically verifiable within Surat jurisdiction.',
            $profile->fresh()->rejection_reason
        );
    }

    public function test_centre_officer_cannot_moderate_applicant_from_different_centre()
    {
        // Karur officer
        $karurOfficer = User::create([
            'name' => 'Karur Officer',
            'email' => 'officer.karur@tefc.gov.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'karur',
        ]);
        $karurOfficer->roles()->attach($this->centerRole);

        // Surat applicant
        $suratApplicant = User::create([
            'name' => 'Surat Applicant',
            'email' => 'applicant@surat.com',
            'password' => bcrypt('password'),
            'status' => 'pending',
            'tefc_centre' => 'surat',
        ]);
        $suratApplicant->roles()->attach($this->exporterRole);

        $response = $this->actingAs($karurOfficer)->post("/center/applications/{$suratApplicant->id}/status", [
            'status' => 'active',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('pending', $suratApplicant->fresh()->status);
    }

    public function test_head_office_can_view_master_dashboard_and_switch_into_centre_view()
    {
        $hoAdmin = User::create([
            'name' => 'HO Administrator',
            'email' => 'ho.admin@tefc.gov.in',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $hoAdmin->roles()->attach($this->adminRole);

        // 1. Visit HO Master Dashboard
        $response = $this->actingAs($hoAdmin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('TEFC Regional Centres');
        $response->assertSee('Surat');
        $response->assertSee('Karur');
        $response->assertSee('Ichalkaranji');
        $response->assertSee('Jaipur');
        $response->assertSee('Varanasi');
        $response->assertSee('Ludhiana');

        // 2. Direct Switch into Surat Centre View
        $switchResp = $this->actingAs($hoAdmin)->get('/admin/centre/switch/surat');
        $switchResp->assertRedirect('/center/dashboard');
        $this->assertEquals('surat', session('viewing_centre'));

        // 3. Follow redirect to Centre Dashboard in HO Mode
        $centreViewResp = $this->actingAs($hoAdmin)->get('/center/dashboard');
        $centreViewResp->assertStatus(200);
        $centreViewResp->assertSee('Head Office (HO) Centre Direct Access Mode');
        $centreViewResp->assertSee('Surat Facilitation Centre');

        // 4. Exit Centre View back to HO Master Dashboard
        $exitResp = $this->actingAs($hoAdmin)->get('/admin/centre/exit');
        $exitResp->assertRedirect('/dashboard');
        $this->assertFalse(session()->has('viewing_centre'));
    }
}
