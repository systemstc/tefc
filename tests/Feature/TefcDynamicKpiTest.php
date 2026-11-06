<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\TefcStaff;
use App\Models\TefcAdvisory;
use App\Models\TefcTraining;
use App\Models\TefcPublication;
use App\Models\TefcQuery;
use App\Models\TefcClusterProfile;
use Database\Seeders\TefcKpiDatabaseSeeder;

class TefcDynamicKpiTest extends TestCase
{
    use RefreshDatabase;

    protected User $ichalkaranjiOfficer;
    protected User $jaipurOfficer;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TefcKpiDatabaseSeeder::class);

        $centerRole = Role::firstOrCreate(['slug' => 'center', 'name' => 'TEFC Centre Officer']);
        $adminRole = Role::firstOrCreate(['slug' => 'admin', 'name' => 'Administrator']);

        $this->ichalkaranjiOfficer = User::create([
            'name' => 'Ichalkaranji Officer',
            'email' => 'officer.ichalkaranji@tefc.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'ichalkaranji',
        ]);
        $this->ichalkaranjiOfficer->roles()->attach($centerRole);

        $this->jaipurOfficer = User::create([
            'name' => 'Jaipur Officer',
            'email' => 'officer.jaipur@tefc.in',
            'password' => bcrypt('password'),
            'status' => 'active',
            'tefc_centre' => 'jaipur',
        ]);
        $this->jaipurOfficer->roles()->attach($centerRole);

        $this->adminUser = User::create([
            'name' => 'HO Admin',
            'email' => 'ho.admin@tefc.in',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->adminUser->roles()->attach($adminRole);
    }

    public function test_centre_officer_can_view_kpi_management_portal(): void
    {
        $response = $this->actingAs($this->ichalkaranjiOfficer)->get('/center/kpis');

        $response->assertStatus(200);
        $response->assertSee('Facilitation Centre Performance Data');
        $response->assertSee('Ichalkaranji');
        $response->assertSee('ManPower (Staff)');
        $response->assertSee('Training Programmes');
    }

    public function test_centre_officer_can_add_staff_member_and_kpi_updates(): void
    {
        $initialCount = TefcStaff::where('centre_code', 'ichalkaranji')->where('status', 'active')->count();

        $response = $this->actingAs($this->ichalkaranjiOfficer)->from('/center/kpis')->post('/center/kpis/staff', [
            'name' => 'Rajesh Sharma',
            'age' => 38,
            'designation' => 'Senior Textile Specialist',
            'education' => 'M.Tech Textile Technology',
            'experience_years' => 12,
            'email' => 'rajesh.sharma@tefc.in',
            'phone' => '+91 9876543210',
            'status' => 'active',
            'joining_date' => '2025-06-01',
        ]);

        $response->assertRedirect('/center/kpis');
        $response->assertSessionHas('success');

        $newCount = TefcStaff::where('centre_code', 'ichalkaranji')->where('status', 'active')->count();
        $this->assertEquals($initialCount + 1, $newCount);

        // Verify public KPI dashboard reflects updated manpower
        $dashResponse = $this->get('/kpi-dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('14'); // Initial total was 13, now 14
    }

    public function test_centre_officer_can_record_advisory_session(): void
    {
        $response = $this->actingAs($this->ichalkaranjiOfficer)->from('/center/kpis')->post('/center/kpis/advisory', [
            'company_name' => 'Deccan Premium Yarns Ltd',
            'iec_status' => 'new_iec',
            'session_date' => '2026-03-15',
            'advisory_topic' => 'DGFT New IEC issuance and European REACH compliance certification',
            'outcome_notes' => 'Applied for new IEC; granted support for test lab documentation.',
        ]);

        $response->assertRedirect('/center/kpis');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tefc_advisories', [
            'centre_code' => 'ichalkaranji',
            'company_name' => 'Deccan Premium Yarns Ltd',
            'iec_status' => 'new_iec',
        ]);
    }

    public function test_centre_officer_can_record_training_programme(): void
    {
        $initialParticipants = (int) TefcTraining::where('centre_code', 'ichalkaranji')->sum('participants_count');

        $response = $this->actingAs($this->ichalkaranjiOfficer)->from('/center/kpis')->post('/center/kpis/training', [
            'title' => 'Advanced Export Packaging for Technical Textiles',
            'type' => 'training_programme',
            'event_date' => '2026-04-10',
            'venue' => 'Ichalkaranji Powerloom Association Convention Hall',
            'participants_count' => 85,
            'topics_covered' => 'Moisture-resistant packaging, container shipment standards, barcode labeling',
        ]);

        $response->assertRedirect('/center/kpis');
        $response->assertSessionHas('success');

        $newParticipants = (int) TefcTraining::where('centre_code', 'ichalkaranji')->sum('participants_count');
        $this->assertEquals($initialParticipants + 85, $newParticipants);
    }

    public function test_centre_officer_can_record_and_resolve_query_with_sla(): void
    {
        $response = $this->actingAs($this->jaipurOfficer)->from('/center/kpis')->post('/center/kpis/query', [
            'company_name' => 'Royal Heritage Hand Block Crafts',
            'contact_person' => 'Mahesh Verma',
            'subject' => 'HSN Code 6204 duty drawback rates under RoDTEP scheme',
            'category' => 'compliance',
            'received_date' => '2026-04-01',
            'status' => 'pending',
        ]);

        $response->assertRedirect('/center/kpis');
        $query = TefcQuery::where('company_name', 'Royal Heritage Hand Block Crafts')->first();
        $this->assertNotNull($query);
        $this->assertEquals('pending', $query->status);

        // Now resolve the query within SLA
        $resolveResponse = $this->actingAs($this->jaipurOfficer)->from('/center/kpis')->post('/center/kpis/query/' . $query->id . '/resolve', [
            'resolved_date' => '2026-04-02',
            'is_within_sla' => '1',
            'resolution_notes' => 'Provided official DGFT RoDTEP schedule and advisory on drawback credit claim process.',
        ]);

        $resolveResponse->assertRedirect('/center/kpis');
        $query->refresh();
        $this->assertEquals('resolved', $query->status);
        $this->assertTrue((bool)$query->is_within_sla);
    }

    public function test_centre_cannot_modify_another_centres_records_unless_ho(): void
    {
        // Jaipur officer tries to add advisory
        // It must automatically assign to authenticated user's centre (jaipur)
        $this->actingAs($this->jaipurOfficer)->post('/center/kpis/advisory', [
            'company_name' => 'Cross Border Textile Mills',
            'iec_status' => 'no_iec',
            'session_date' => '2026-04-05',
            'advisory_topic' => 'Export licensing',
        ]);

        $advisory = TefcAdvisory::where('company_name', 'Cross Border Textile Mills')->first();
        $this->assertEquals('jaipur', $advisory->centre_code);
    }
}
