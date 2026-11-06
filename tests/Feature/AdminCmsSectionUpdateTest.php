<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Page;
use App\Models\Section;
use Database\Seeders\CmsSeeder;
use Tests\TestCase;

class AdminCmsSectionUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['slug' => 'admin', 'name' => 'Admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->attach($adminRole);

        $this->seed(CmsSeeder::class);
    }

    public function test_updating_section_with_array_and_slider_images_does_not_throw_type_error()
    {
        $section = Section::first();
        $this->assertNotNull($section);

        $payload = [
            'title' => 'Updated Section Title',
            'slider_images' => [], // Empty array submitted by multiple file input
            'json_items' => json_encode([
                ['title' => 'Item 1', 'desc' => 'Description 1'],
                ['title' => 'Item 2', 'desc' => 'Description 2']
            ]),
            'custom_tags' => ['tag1', 'tag2'], // Array input that previously caused TypeError in json_decode()
            'plain_text_field' => 'Simple string value',
        ];

        $response = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$section->id}/update", $payload);

        $response->assertRedirect(route('admin.cms'));
        $response->assertSessionHas('success', 'Section updated successfully');

        $section->refresh();
        $this->assertEquals('Updated Section Title', $section->title);
        $this->assertIsArray($section->content['items']);
        $this->assertCount(2, $section->content['items']);
        $this->assertEquals(['tag1', 'tag2'], $section->content['custom_tags']);
        $this->assertEquals('Simple string value', $section->content['plain_text_field']);
    }

    public function test_updating_hero_section_without_title_or_buttons_enables_pure_banner_mode()
    {
        $heroSection = Section::where('type', 'hero')->first();
        $this->assertNotNull($heroSection);

        // 1. Verify Edit Page displays recommended banner dimensions & height setting
        $editResponse = $this->actingAs($this->adminUser)->get("/admin/cms/sections/{$heroSection->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Recommended Hero Banner Dimensions', false);
        $editResponse->assertSee('1920 px (Width) &times; 500 px (Height)', false);
        $editResponse->assertSee('Hero Section Display Height (Desktop)', false);

        $payload = [
            'title' => '', // Left empty by user
            'subtitle' => '', // Left empty by user
            'primary_btn_text' => '', // Left empty by user
            'primary_btn_url' => '',
            'secondary_btn_text' => '', // Left empty by user
            'secondary_btn_url' => '',
            'banner_height' => '450',
            'existing_slider_images' => ['images/slider/banner1.jpg', 'images/slider/banner2.jpg'],
        ];

        $response = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$heroSection->id}/update", $payload);

        $response->assertRedirect(route('admin.cms'));
        $response->assertSessionHas('success', 'Section updated successfully');

        $heroSection->refresh();
        $this->assertNull($heroSection->title);
        $this->assertEquals(['images/slider/banner1.jpg', 'images/slider/banner2.jpg'], $heroSection->content['images']);
        $this->assertEquals('450', $heroSection->content['banner_height']);

        // Check that homepage loads and renders compact height & pure banner mode without broken tags
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('hero-slider-wrapper');
        $homeResponse->assertSee('--hero-desktop-height: 450px');
        $homeResponse->assertSee('images/slider/banner1.jpg');
        $homeResponse->assertDontSee('btn-premium');
        $homeResponse->assertDontSee('btn-glass');
    }

    public function test_updating_clusters_section_with_district_counts_and_animated_map()
    {
        $clustersSection = Section::where('type', 'clusters_grid')->first();
        $this->assertNotNull($clustersSection);

        // 1. Test Admin Edit Page renders dedicated visual editor for clusters & districts
        $editResponse = $this->actingAs($this->adminUser)->get("/admin/cms/sections/{$clustersSection->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Interactive Animated Map');
        $editResponse->assertSee('Auto-Fill Official 6 TEFC Centres');
        $editResponse->assertSee('btnAddCluster');

        // 2. Test updating clusters with district units count
        $items = [
            [
                'code' => 'surat',
                'name' => 'Surat',
                'state' => 'Gujarat',
                'districts' => 'Surat, Navsari',
                'units_count' => 115,
                'specialization' => 'Man-made Fibre (MMF) & Technical Textiles',
                'description' => 'Synthetic textile manufacturing hub.',
                'image' => ''
            ],
            [
                'code' => 'karur',
                'name' => 'Karur',
                'state' => 'Tamil Nadu',
                'districts' => 'Karur',
                'units_count' => 89,
                'specialization' => 'Home Textiles & Bed Linens',
                'description' => 'Home textiles export centre.',
                'image' => ''
            ]
        ];

        $payload = [
            'title' => 'Major Textile Clusters',
            'description' => 'Interactive Facilitation Grid across India.',
            'show_map_card_submitted' => '1',
            'show_map_card' => '1',
            'json_items' => json_encode($items),
        ];

        $updateResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$clustersSection->id}/update", $payload);
        $updateResponse->assertRedirect(route('admin.cms'));
        $updateResponse->assertSessionHas('success', 'Section updated successfully');

        $clustersSection->refresh();
        $this->assertEquals('Major Textile Clusters', $clustersSection->title);
        $this->assertCount(2, $clustersSection->content['items']);
        $this->assertEquals(115, $clustersSection->content['items'][0]['units_count']);
        $this->assertEquals('Surat, Navsari', $clustersSection->content['items'][0]['districts']);

        // 3. Test homepage renders the animated India SVG map, district nodes, and radar cards
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('tefc-india-svg');
        $homeResponse->assertSee('node-surat');
        $homeResponse->assertSee('node-karur');
        $homeResponse->assertSee('115 Units');
        $homeResponse->assertSee('Surat, Navsari');
        $homeResponse->assertSee('cluster-district-header');

        // Reset show_map_card to false to respect user's inactive preference for the map card
        $content = $clustersSection->content;
        $content['show_map_card'] = false;
        $clustersSection->update(['content' => $content]);
    }

    public function test_updating_about_intro_section_with_individual_leadership_people_cards()
    {
        $aboutSection = Section::where('type', 'about_intro')->first();
        $this->assertNotNull($aboutSection);

        // 1. Verify Edit Page displays dedicated visual editor for Leadership Cards
        $editResponse = $this->actingAs($this->adminUser)->get("/admin/cms/sections/{$aboutSection->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Key People & Leadership Team', false);
        $editResponse->assertSee('people_cards_list');
        $editResponse->assertSee('addPersonCard');

        // 2. Submit form with individual leadership cards & uploaded portrait
        \Illuminate\Support\Facades\Storage::fake('public');
        $fakePhoto = \Illuminate\Http\UploadedFile::fake()->image('officer.png', 300, 300);

        $payload = [
            'title' => 'About TEFC Leadership',
            'text_1' => 'Supporting Indian MSMEs and textile exporters.',
            'text_2' => 'Connecting clusters worldwide.',
            'btn_text' => 'Read Our Full Story',
            'btn_url' => '/p/about',
            'people_name' => ['NEELAM SHAMI RAO', 'Vrunda Manohar Desai', 'Kartikay Dhanda'],
            'people_designation' => ["Chairperson's Message", "Vice-Chairperson's Message", 'CEO Message'],
            'people_existing_image' => ['images/people/neelam_shami_rao.png', 'images/people/vrunda_desai.png', 'images/people/kartikay_dhanda.png'],
            'people_link' => ['/p/about#chairperson', '/p/about#vice-chair', '/p/about#ceo'],
            'people_image_0' => $fakePhoto,
        ];

        $updateResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$aboutSection->id}/update", $payload);
        $updateResponse->assertRedirect(route('admin.cms'));
        $updateResponse->assertSessionHas('success', 'Section updated successfully');

        $aboutSection->refresh();
        $this->assertEquals('About TEFC Leadership', $aboutSection->title);
        $this->assertCount(3, $aboutSection->content['people']);
        $this->assertEquals('NEELAM SHAMI RAO', $aboutSection->content['people'][0]['name']);
        $this->assertEquals("Chairperson's Message", $aboutSection->content['people'][0]['designation']);
        $this->assertStringContainsString('person_', $aboutSection->content['people'][0]['image']);
        $this->assertEquals('/p/about#chairperson', $aboutSection->content['people'][0]['link']);

        $this->assertEquals('Vrunda Manohar Desai', $aboutSection->content['people'][1]['name']);
        $this->assertEquals('images/people/vrunda_desai.png', $aboutSection->content['people'][1]['image']);

        // 3. Verify Homepage displays the 3 leadership cards matching the design
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('leadership-cards-wrapper');
        $homeResponse->assertSee('NEELAM SHAMI RAO');
        $homeResponse->assertSee("Chairperson&#039;s Message", false);
        $homeResponse->assertSee('Vrunda Manohar Desai');
        $homeResponse->assertSee('Kartikay Dhanda');
        $homeResponse->assertSee('CEO Message');
    }

    public function test_registration_modal_section_is_editable_via_cms()
    {
        $regModalSection = Section::where('type', 'registration_modal')->first();
        $this->assertNotNull($regModalSection);

        $editResponse = $this->actingAs($this->adminUser)->get("/admin/cms/sections/{$regModalSection->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('First-Visit Exporter Registration Popup Modal');

        $payload = [
            'title' => 'Custom Exporter Onboarding',
            'subtitle' => 'Join 1000+ verified Indian textile manufacturers.',
            'btn_text' => 'Start Registration Now',
            'btn_url' => '/register?role=exporter',
            'secondary_text' => 'Skip to Public Directory',
        ];

        $updateResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$regModalSection->id}/update", $payload);
        $updateResponse->assertRedirect(route('admin.cms'));
        $updateResponse->assertSessionHas('success', 'Section updated successfully');

        $regModalSection->refresh();
        $this->assertEquals('Custom Exporter Onboarding', $regModalSection->title);
        $this->assertEquals('Join 1000+ verified Indian textile manufacturers.', $regModalSection->content['subtitle']);
        $this->assertEquals('Start Registration Now', $regModalSection->content['btn_text']);
        $this->assertEquals('Skip to Public Directory', $regModalSection->content['secondary_text']);
    }

    public function test_header_renders_skip_link_accessibility_drawer_and_bhashini_slot()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Skip to main content
        $response->assertSee('Skip to Main Content');
        $response->assertSee('id="skipToContentLink"', false);
        $response->assertSee('href="#main-content"', false);

        // Accessibility trigger & left drawer
        $response->assertSee('id="btnOpenAccessibility"', false);
        $response->assertSee('id="accessibilityDrawer"', false);
        $response->assertSee('Accessibility Options');
        $response->assertSee('id="accessibilityBackdrop"', false);

        // Bhashini API integration space
        $response->assertSee('id="bhashiniPluginSlot"', false);
        $response->assertSee('id="bhashini-plugin-container"', false);
        $response->assertSee('bhashini-api-mount');
        $response->assertSee('id="bhashiniDropdownBtn"', false);

        // One-time popup check script logic present
        $response->assertSee('tefc_exporter_popup_seen');
    }

    public function test_section_active_inactive_toggle_and_frontend_filtering()
    {
        $mapSection = Section::where('type', 'clusters_grid')->first();
        $this->assertNotNull($mapSection);

        // Initially active with map card enabled for test
        $content = $mapSection->content;
        $content['show_map_card'] = true;
        $mapSection->update(['is_active' => true, 'content' => $content]);

        $responseActive = $this->get('/');
        $responseActive->assertStatus(200);
        $responseActive->assertSee('District Export Presence');

        // Toggle inactive via admin endpoint
        $toggleResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$mapSection->id}/toggle-status");
        $toggleResponse->assertRedirect();
        
        $mapSection->refresh();
        $this->assertFalse($mapSection->is_active);

        // When inactive, the entire section should NOT be rendered on homepage
        $responseInactive = $this->get('/');
        $responseInactive->assertStatus(200);
        $responseInactive->assertDontSee('District Export Presence');

        // Can also be toggled or updated via updateSection form
        $updateResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$mapSection->id}/update", [
            'title' => $mapSection->title,
            'is_active_submitted' => '1',
            'is_active' => '1',
            'show_map_card_submitted' => '1',
            'show_map_card' => '0'
        ]);
        $updateResponse->assertRedirect(route('admin.cms'));

        $mapSection->refresh();
        $this->assertTrue($mapSection->is_active);
        $this->assertFalse((bool)($mapSection->content['show_map_card'] ?? false));
    }

    public function test_toggle_map_card_status_endpoint()
    {
        $clustersSection = Section::where('type', 'clusters_grid')->first();
        $this->assertNotNull($clustersSection);

        // 1. Initially set to false
        $content = $clustersSection->content;
        $content['show_map_card'] = false;
        $clustersSection->update(['is_active' => true, 'content' => $content]);

        $homeOff = $this->get('/');
        $homeOff->assertStatus(200);
        $homeOff->assertDontSee('tefc-map-showcase');

        // 2. Toggle to true via AJAX endpoint
        $ajaxResponse = $this->actingAs($this->adminUser)->postJson("/admin/cms/sections/{$clustersSection->id}/toggle-map-card", [
            'state' => true
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson(['success' => true, 'show_map_card' => true]);

        $clustersSection->refresh();
        $this->assertTrue((bool)$clustersSection->content['show_map_card']);

        // 3. Toggle back to false via endpoint
        $toggleBack = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$clustersSection->id}/toggle-map-card");
        $toggleBack->assertRedirect();

        $clustersSection->refresh();
        $this->assertFalse((bool)$clustersSection->content['show_map_card']);

        $homeOffAgain = $this->get('/');
        $homeOffAgain->assertStatus(200);
        $homeOffAgain->assertDontSee('tefc-map-showcase');
    }

    public function test_fta_benefits_items_without_url_and_with_description()
    {
        $ftaSection = Section::where('type', 'fta_benefits')->first();
        $this->assertNotNull($ftaSection);

        $payload = [
            'title' => 'FTA Strategic Benefits',
            'is_active_submitted' => '1',
            'is_active' => '1',
            'json_items' => json_encode([
                [
                    'name' => 'Zero Customs Duty',
                    'description' => 'Zero duty access for Indian textile exporters to participating FTA markets.'
                ],
                [
                    'name' => 'Fast Customs Green Channel',
                    'description' => 'Priority border clearance and digital verification of origin.'
                ]
            ])
        ];

        $updateResponse = $this->actingAs($this->adminUser)->post("/admin/cms/sections/{$ftaSection->id}/update", $payload);
        $updateResponse->assertRedirect(route('admin.cms'));

        $ftaSection->refresh();
        $this->assertEquals('Zero Customs Duty', $ftaSection->content['items'][0]['name']);
        $this->assertEquals('Zero duty access for Indian textile exporters to participating FTA markets.', $ftaSection->content['items'][0]['description']);
        $this->assertArrayNotHasKey('url', $ftaSection->content['items'][0]);

        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Zero Customs Duty');
        $homeResponse->assertSee('Zero duty access for Indian textile exporters to participating FTA markets.');
    }

    public function test_sitemap_page_and_streamlined_footer()
    {
        // Sitemap route loads successfully
        $sitemapResponse = $this->get('/sitemap');
        $sitemapResponse->assertStatus(200);
        $sitemapResponse->assertSee('TEFC Portal Sitemap');
        $sitemapResponse->assertSee('Certificate of Origin');

        // Streamlined footer checks on homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Portal Navigation');
        $homeResponse->assertSee('href="http://localhost/sitemap"', false);
        $homeResponse->assertSee('Product Catalogue');
        $homeResponse->assertSee('Dashboard');
    }

    public function test_hero_section_height_of_600px_and_announcement_section_after_hero()
    {
        $homePage = Page::where('slug', 'home')->first();
        $this->assertNotNull($homePage);

        $heroSection = Section::where('page_id', $homePage->id)->where('type', 'hero')->first();
        $this->assertNotNull($heroSection);

        // Update banner_height to 600
        $content = $heroSection->content;
        $content['banner_height'] = 600;
        $heroSection->update(['content' => $content]);

        $announcementSection = Section::where('page_id', $homePage->id)->where('type', 'announcement')->first();
        $this->assertNotNull($announcementSection);
        $this->assertTrue($announcementSection->order > $heroSection->order);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('--hero-desktop-height: 600px');
        $response->assertSee('tefc-announcement-strip');
        $response->assertSee('Latest Announcements');
        $response->assertSee('announcement-ticker-track');
    }
}

