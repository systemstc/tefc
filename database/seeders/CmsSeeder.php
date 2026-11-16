<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Section;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Global Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'TEFC Portal', 'type' => 'text'],
            ['key' => 'logo_text_rich', 'value' => '<span style="color:#6c1340; font-weight:900; font-size:2.3rem;">T</span>extile <span style="color:#6c1340; font-weight:900; font-size:2.3rem;">E</span>xport <span style="color:#6c1340; font-weight:900; font-size:2.3rem;">F</span>acilitation <span style="color:#6c1340; font-weight:900; font-size:2.3rem;">C</span>entre', 'type' => 'text'],
            ['key' => 'logo_subtitle', 'value' => 'An initiative of Textiles Committee, Min. of Textiles, Govt of India', 'type' => 'text'],
            ['key' => 'footer_about_text', 'value' => 'The Textiles Export Facilitation Centre (TEFC) is a one-stop strategic support hub for MSMEs and exporters, bridging the gap between local craft and global demand.', 'type' => 'text'],
            ['key' => 'footer_copyright', 'value' => '© 2026 Textile Export Facilitation Centre. An ISO 9001:2015 Certified Organization.', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'support@tefc.gov.in', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+91 11 2345 6789', 'type' => 'text'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        // 2. Create All Required Pages
        $pageData = [
            ['slug' => 'home', 'title' => 'Home — TEFC'],
            ['slug' => 'about', 'title' => 'About Us — TEFC'],
            ['slug' => 'clusters', 'title' => 'Major Textile Clusters — TEFC'],
            ['slug' => 'fta-benefits', 'title' => 'FTA Strategic Benefits — TEFC'],
            ['slug' => 'online-services', 'title' => 'Online Services — TEFC'],
            ['slug' => 'news', 'title' => 'Latest News — TEFC'],
            ['slug' => 'media', 'title' => 'Media Center — TEFC'],
            ['slug' => 'help-support', 'title' => 'Help & Support — TEFC'],
            ['slug' => 'coo', 'title' => 'Certificate of Origin (COO) — TEFC'],
            ['slug' => 'ecgc', 'title' => 'ECGC Services — TEFC'],
            ['slug' => 'hs-code', 'title' => 'HS Code Search — TEFC'],
            ['slug' => 'iec', 'title' => 'Import Export Code (IEC) — TEFC'],
            ['slug' => 'letter-of-credit', 'title' => 'Letter of Credit — TEFC'],
            ['slug' => 'resources', 'title' => 'Resources & Trade Guides — TEFC'],
        ];

        foreach ($pageData as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], ['title' => $p['title']]);
        }

        // 3. Navigation (Full Restoration)
        $headerMenu = Menu::updateOrCreate(['location' => 'header'], ['name' => 'Main Navigation']);
        MenuItem::where('menu_id', $headerMenu->id)->delete();

        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Home', 'url' => '/', 'page_slug' => 'home', 'order' => 1]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'About', 'url' => '/p/about', 'page_slug' => 'about', 'order' => 2]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'About TC', 'url' => 'https://textilescommittee.gov.in', 'page_slug' => null, 'order' => 3]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Clusters', 'url' => '/p/clusters', 'page_slug' => 'clusters', 'order' => 4]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Resources', 'url' => '/resources', 'page_slug' => null, 'order' => 5]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'FTA Benefits', 'url' => '/p/fta-benefits', 'page_slug' => 'fta-benefits', 'order' => 6]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Online Services', 'url' => '/p/online-services', 'page_slug' => 'online-services', 'order' => 7]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'News', 'url' => '/p/news', 'page_slug' => 'news', 'order' => 8]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Media Center', 'url' => '/p/media', 'page_slug' => 'media', 'order' => 9]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Help & Support', 'url' => '/p/help-support', 'page_slug' => 'help-support', 'order' => 10]);

        // Clear existing sections
        Section::truncate();

        // 4. Home Page Content
        $homePage = Page::where('slug', 'home')->first();
        Section::create(['page_id' => $homePage->id, 'type' => 'registration_modal', 'order' => 0, 'title' => 'Exporter Registration', 'content' => ['subtitle' => 'Join India\'s premier textile facilitation network and unlock global trade opportunities.']]);
        Section::create(['page_id' => $homePage->id, 'type' => 'hero', 'order' => 1, 'title' => 'Empowering Textile Exports through TEFC', 'content' => ['banner_height' => 600, 'background_image' => 'images/hero_map.jpg', 'subtitle' => 'Providing strategic intelligence, trade support, and quality assurance to empower India\'s textile renaissance.', 'primary_btn_text' => 'Explore Services', 'primary_btn_url' => '/p/online-services', 'secondary_btn_text' => 'View Clusters', 'secondary_btn_url' => '/p/clusters', 'stats' => [['label' => 'Textile Clusters', 'value' => '6+'], ['label' => 'Exporters Supported', 'value' => '1000+'], ['label' => 'Export Value', 'value' => '₹5000 Cr'],]]]);
        Section::create([
            'page_id' => $homePage->id,
            'type' => 'announcement',
            'order' => 2,
            'title' => 'Latest Announcements & Trade Alerts',
            'content' => [
                'badge' => 'Latest Announcements',
                'items' => [
                    ['category' => 'RoDTEP', 'text' => 'Enhanced RoDTEP & RoSCTL rates notified for textile exporters — single-window advisory active across all 6 TEFC clusters.', 'url' => '/p/fta-benefits', 'is_new' => true],
                    ['category' => 'Registration', 'text' => 'Single-Window Exporter Registration is open for textile MSMEs to access dedicated nodal officer support and export facilitation.', 'url' => '/register', 'is_new' => true],
                    ['category' => 'FTA Alert', 'text' => 'India-EFTA and UAE-CEPA preferential duty guidelines published — access zero-duty tariff schedules for spun yarns and technical fabrics.', 'url' => '/p/fta-benefits', 'is_new' => false],
                    ['category' => 'Outreach', 'text' => 'Special export facilitation camps ongoing across Surat, Karur, Ichalkaranji, Jaipur, Varanasi & Ludhiana centres.', 'url' => '/p/clusters', 'is_new' => false],
                ],
                'view_all_url' => '/p/news',
                'view_all_text' => 'All Updates',
            ]
        ]);
        Section::create([
            'page_id' => $homePage->id,
            'type' => 'about_intro',
            'order' => 3,
            'title' => 'About TEFC',
            'content' => [
                'image' => 'images/Key-People-in-organisation.png',
                'people' => [
                    ['name' => 'NEELAM SHAMI RAO', 'designation' => "Chairperson's Message", 'image' => 'images/people/neelam_shami_rao.png', 'link' => '/p/about'],
                    ['name' => 'Vrunda Manohar Desai', 'designation' => "Vice-Chairperson's Message", 'image' => 'images/people/vrunda_desai.png', 'link' => '/p/about'],
                    ['name' => 'Kartikay Dhanda', 'designation' => 'CEO Message', 'image' => 'images/people/kartikay_dhanda.png', 'link' => '/p/about'],
                ],
                'text_1' => 'A strategic initiative by the Textiles Committee, TEFC serves as a comprehensive support ecosystem for Indian MSMEs and textile exporters.',
                'text_2' => 'We bridge the gap between local craftsmanship and international demand.',
                'btn_text' => 'Read Our Full Story',
                'btn_url' => '/p/about'
            ]
        ]);
        Section::create(['page_id' => $homePage->id, 'type' => 'clusters_grid', 'order' => 4, 'title' => 'Major Textile Clusters', 'content' => ['items' => [['name' => 'Karur', 'state' => 'Tamil Nadu', 'image' => 'images/karur.png'], ['name' => 'Surat', 'state' => 'Gujarat', 'image' => 'images/karur.png'], ['name' => 'Ichalkaranji', 'state' => 'Maharashtra', 'image' => 'images/karur.png'],]]]);
        Section::create(['page_id' => $homePage->id, 'type' => 'fta_benefits', 'order' => 5, 'title' => 'FTA Benefits', 'content' => ['items' => [['name' => 'RoDTEP Scheme', 'url' => '#'], ['name' => 'PLI Scheme', 'url' => '#'], ['name' => 'Trade Schemes', 'url' => '#'],]]]);
        Section::create(['page_id' => $homePage->id, 'type' => 'online_services_grid', 'order' => 6, 'title' => 'Online Services', 'content' => ['items' => [['label' => 'Trade Connect', 'icon' => '🖥️'], ['label' => 'Applications', 'icon' => '📋'], ['label' => 'Training', 'icon' => '🎓'], ['label' => 'Certification', 'icon' => '🏅'],]]]);
        Section::create(['page_id' => $homePage->id, 'type' => 'impact_stats', 'order' => 7, 'title' => 'Our Impact', 'content' => ['stats' => [['label' => 'Clusters', 'value' => '6+'], ['label' => 'Exporters Supported', 'value' => '1000+'], ['label' => 'Export Value', 'value' => '₹5000 Cr'], ['label' => 'Production Value', 'value' => '₹7000 Cr'],]]]);
        Section::create(['page_id' => $homePage->id, 'type' => 'impact_charts', 'order' => 8, 'title' => 'Impact Analytics', 'content' => ['bar_items' => [['label' => 'Karur', 'value' => '33%', 'color' => '#6c1340'], ['label' => 'Ludhiana', 'value' => '27%', 'color' => '#8a1e50'],], 'donut_gradient' => 'conic-gradient(#6c1340 0% 33%, #8a1e50 33% 60%, #a0245e 60% 78%, #c26090 78% 91%, #e0a0c0 91% 100%)', 'donut_items' => [['label' => 'Karur', 'value' => '33%', 'color' => '#6c1340'], ['label' => 'Ludhiana', 'value' => '27%', 'color' => '#8a1e50'],]]]);
        Section::create([
            'page_id' => $homePage->id,
            'type' => 'gallery',
            'order' => 9,
            'title' => 'Our Gallery',
            'content' => [
                'subtitle' => 'A glimpse into our work, events, and the people behind TEFC.',
                'items' => [
                    ['image' => 'images/gallery/gallery_1.jpg', 'title' => 'Textiles Committee 1944-2026 Milestone Review', 'caption' => 'A Journey of India\'s Trust & Quality Assurance across global textile markets.'],
                    ['image' => 'images/gallery/gallery_2.jpg', 'title' => 'Stakeholders & Industry Exhibition Stall', 'caption' => 'Official delegation reviewing regional handloom and powerloom products.'],
                    ['image' => 'images/gallery/gallery_3.jpg', 'title' => 'International Fabric & Apparel Display', 'caption' => 'Weavers and manufacturers showcasing premium export-grade textiles.'],
                    ['image' => 'images/gallery/gallery_4.jpg', 'title' => 'Official Delegation at National Conclave', 'caption' => 'Leadership gathering for export facilitation and trade promotion.'],
                    ['image' => 'images/gallery/gallery_5.jpg', 'title' => 'Global Logistics & Container Cargo Port', 'caption' => 'Facilitating efficient multimodal freight and expedited port clearances.'],
                    ['image' => 'images/gallery/gallery_6.jpg', 'title' => 'Foundation Day & Welcome Ceremony', 'caption' => 'Textiles Committee executive leadership and regional nodal officers.'],
                    ['image' => 'images/gallery/gallery_7.jpg', 'title' => 'Institutional Felicitation & Award Ceremony', 'caption' => 'Recognizing outstanding achievements in textile innovation and export growth.'],
                    ['image' => 'images/gallery/gallery_8.jpg', 'title' => 'Inaugural Lamp Lighting Ceremony', 'caption' => 'Formal opening ceremony of the National Textile Export Facilitation Conclave.'],
                ]
            ]
        ]);
        Section::create(['page_id' => $homePage->id, 'type' => 'assistance_banner', 'order' => 10, 'title' => 'Need Assistance? We\'re here to help.', 'content' => ['btn_text' => 'Contact Support', 'btn_url' => '/p/help-support']]);

        // 5. About Us Page Content
        $aboutPage = Page::where('slug', 'about')->first();
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'about_hero', 'order' => 1, 'title' => 'About TEFC',
            'content' => [
                'subtitle' => 'The Textiles Export Facilitation Centre (TEFC) is a strategic initiative of the Textiles Committee, Ministry of Textiles, Government of India, created to empower MSMEs and textile exporters to compete in the global market.',
                'side_stats' => [
                    ['label' => 'Total MSMEs Empowered', 'value' => '4,500+'],
                    ['label' => 'Exporters Supported', 'value' => '1,200+'],
                    ['label' => 'Total Trade Facilitated', 'value' => '15,000 Cr'],
                    ['label' => 'Founded in Year', 'value' => 'Est. 2022'],
                ]
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'about_overview', 'order' => 2, 'title' => 'Overview',
            'content' => [
                'tagline' => "Empowering India's textile heartlands for global reach.",
                'heading' => 'Facilitating Excellence',
                'description' => 'The Textiles Export Facilitation Centre (TEFC) is a dedicated support ecosystem established by the Textiles Committee. We serve as a one-stop-shop for Indian textile exporters, providing technical guidance, market intelligence, and logistical support to ensure that the heritage of Indian textiles reaches every corner of the world.',
                'features' => [
                    ['icon' => 'fas fa-globe', 'title' => 'Global Market Access', 'text' => 'Connecting local weavers to international buyers through verified trade portals.'],
                    ['icon' => 'fas fa-hand-holding-heart', 'title' => 'Handholding Support', 'text' => 'End-to-end guidance from registration to final export documentation.'],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Capacity Building', 'text' => 'Regular workshops on quality standards and global market trends.'],
                    ['icon' => 'fas fa-file-contract', 'title' => 'Policy Guidance', 'text' => 'Detailed information on FTAs and government scheme benefits.'],
                ],
                'pulse_stats' => [
                    ['label' => 'Daily Traffic', 'value' => '12,500+', 'trend' => '+15% this month'],
                    ['label' => 'Active Countries', 'value' => '45+', 'trend' => 'Recently added: UAE, Japan'],
                    ['label' => 'Verified Sellers', 'value' => '1,800+', 'trend' => 'Verified by Textiles Committee'],
                ]
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'mission_vision', 'order' => 3, 'title' => 'Mission, Vision & Objectives',
            'content' => [
                'tagline' => "Our roadmap for India's textile export future.",
                'mission' => "To facilitate and strengthen the export ecosystem of India's textile industry by providing technical support, market intelligence, quality certification, and policy guidance—enabling growth for MSMEs and established exporters.",
                'vision' => "To emerge as India's premier destination for high-quality, sustainable, and competitive textile exports, serving as the bridge between Indian craftsmanship and global demand.",
                'objectives' => [
                    'Empower MSMEs through direct export assistance.',
                    'Maintain world-class quality standards through certification.',
                    'Facilitate trade through Free Trade Agreements (FTAs).',
                    'Foster innovation in textile design and manufacturing.',
                ]
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'people_grid', 'order' => 4, 'title' => 'Key People in Organisation',
            'content' => [
                'tagline' => 'Guiding the strategic export initiatives of India.',
                'people' => [
                    ['name' => 'Shri. Ajit Singh', 'designation' => 'Chairman, Textiles Committee', 'image' => 'images/MOT.jpeg'],
                    ['name' => 'Smt. Roopa Dutta', 'designation' => 'Member Secretary', 'image' => 'images/Vrunda-Manohar-Desai.png'],
                    ['name' => 'Shri. Kartikeya Mishra', 'designation' => 'Director (Exports)', 'image' => 'images/Secretary-textiles-Png-Image.png'],
                    ['name' => 'Dr. Meena Rani', 'designation' => 'Nodal Officer, TEFC', 'image' => 'images/CEO_TC.png'],
                ],
                'statutory_title' => 'Statutory Oversight',
                'footer_about' => 'The Textiles Committee (TEFC) was established by the Textiles Committee Act, 1963. We are a statutory body under the Ministry of Textiles, Government of India. Our primary objective is to ensure the quality of textiles and textile machinery both for internal consumption and export.'
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'org_structure', 'order' => 5, 'title' => 'Organisational Structure',
            'content' => [
                'tagline' => 'Our administrative and operational framework.',
                'level_1' => 'Ministry of Textiles, Govt. of India',
                'level_2' => 'Textiles Committee',
                'level_3' => 'TEFC • Strategic Secretariat',
                'branches' => ['TEFC Core', 'TEFC East', 'TEFC North-east', 'TEFC West', 'TEFC Central', 'TEFC Admin'],
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'milestone_timeline', 'order' => 6, 'title' => 'Key Milestones',
            'content' => [
                'tagline' => 'A journey of textile excellence and global facilitation.',
                'milestones' => [
                    ['year' => '2022', 'title' => 'TEFC Foundation', 'text' => 'The Textiles Committee officially announced the formation of the Export Facilitation Centre to support MSMEs.'],
                    ['year' => '2023', 'title' => 'Digital Portal Launch', 'text' => 'The first version of the TEFC digital portal was launched, connecting 500+ sellers.'],
                    ['year' => '2024', 'title' => 'Global Expansion', 'text' => 'Strategic MoUs signed with international trade bodies in Europe and the Middle East.'],
                    ['year' => '2025', 'title' => 'AI-Powered Insights', 'text' => 'Integrated advanced trade analytics to help exporters predict global demand trends.'],
                ]
            ]
        ]);
        Section::create([
            'page_id' => $aboutPage->id, 'type' => 'assistance_banner', 'order' => 7, 'title' => 'Want to know more about TEFC? Speak to our team directly.',
            'content' => ['btn_text' => 'Contact Us', 'btn_url' => '/p/help-support']
        ]);

        // 6. News Page Content
        $newsPage = Page::where('slug', 'news')->first();
        Section::create([
            'page_id' => $newsPage->id, 'type' => 'news_list', 'order' => 1, 'title' => 'Latest News & Updates',
            'content' => [
                'description' => 'Stay informed with the latest developments in the Indian textile export sector.',
                'items' => [
                    ['day' => '15', 'month' => 'MAY', 'title' => 'New PLI Scheme Guidelines Released', 'summary' => 'The Ministry of Textiles has released updated guidelines for the Production Linked Incentive scheme aiming to boost MMF apparel.', 'url' => '#'],
                    ['day' => '02', 'month' => 'MAY', 'title' => 'TEFC Opens New Chapter in Surat', 'summary' => 'A new regional facilitation center has been inaugurated in Surat to support local synthetic fabric manufacturers.', 'url' => '#'],
                    ['day' => '28', 'month' => 'APR', 'title' => 'Export Target Reaches Record High', 'summary' => 'Indian textile exports have hit a record high this quarter, driven by strong demand in European markets.', 'url' => '#'],
                ]
            ]
        ]);
        Section::create(['page_id' => $newsPage->id, 'type' => 'assistance_banner', 'order' => 2, 'title' => 'Have a Press Inquiry?', 'content' => ['btn_text' => 'Contact Media Team', 'btn_url' => '/p/help-support']]);

        // 7. Help & Support Page Content
        $helpPage = Page::where('slug', 'help-support')->first();
        Section::create([
            'page_id' => $helpPage->id, 'type' => 'help_faq', 'order' => 1, 'title' => 'Help & Support',
            'content' => [
                'description' => 'Find answers to common questions or reach out to our dedicated support team.',
                'support_email' => 'support@tefc.gov.in',
                'items' => [
                    ['question' => 'How do I register as an exporter?', 'answer' => 'You can register by clicking the Exporter Registration button on the home page and submitting your IEC code and company details.'],
                    ['question' => 'What are the benefits of the RoDTEP scheme?', 'answer' => 'The RoDTEP scheme provides rebates on Central, State, and Local duties/taxes that are not refunded under any other scheme.'],
                    ['question' => 'How can I apply for the Quality Certification mark?', 'answer' => 'Navigate to the Online Services section and select Certification. You will be guided through the document submission process.'],
                ]
            ]
        ]);

        // 8. Media Center Page Content
        $mediaPage = Page::where('slug', 'media')->first();
        Section::create([
            'page_id' => $mediaPage->id, 'type' => 'media_center', 'order' => 1, 'title' => 'Media & Video Gallery',
            'content' => [
                'items' => [
                    ['label' => 'Karur Cluster Overview', 'image' => 'images/karur.png'],
                    ['label' => 'Surat Capacity Building', 'image' => 'images/karur.png'],
                    ['label' => 'Quality Assurance Workshop', 'image' => 'images/karur.png'],
                    ['label' => 'Global Trade Fair Highlights', 'image' => 'images/karur.png'],
                ]
            ]
        ]);

        // 9. Resource Center Page Content
        $resourcesPage = Page::where('slug', 'resources')->first();
        if ($resourcesPage) {
            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_hero', 'order' => 1, 'title' => 'Resource Center Banner',
                'content' => [
                    'title' => 'Resource Center',
                    'subtitle' => 'Access essential export guides, market intelligence reports, government schemes, and step-by-step export processes to accelerate your global textile trade.',
                    'background_image' => 'images/page-hero-bg.jpg',
                ]
            ]);

            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_export_markets', 'order' => 2, 'title' => 'Explore Export Markets',
                'content' => [
                    'kicker' => 'Market intelligence · International trade',
                    'heading' => 'Explore your next export market.',
                    'intro' => 'Start with a featured destination, then continue to Trade Connect for the official country guide and detailed market information.',
                    'view_all_text' => 'Explore all country guides',
                    'view_all_url' => 'https://www.trade.gov.in/pages/country-guide',
                    'cards' => [
                        [
                            'code' => 'US', 'name' => 'United States', 'region' => 'North America',
                            'description' => 'High demand for apparel, home textiles, and technical textiles. Direct access to major retail chains.',
                            'url' => 'https://www.trade.gov.in/pages/country-guide',
                            'label' => 'DESTINATION', 'index' => '01', 'style_class' => 'us', 'button_text' => 'Know more',
                        ],
                        [
                            'code' => 'DE', 'name' => 'Germany', 'region' => 'Europe',
                            'description' => 'Key gateway for sustainable, organic, and circular textiles in the EU market.',
                            'url' => 'https://www.trade.gov.in/pages/country-guide',
                            'label' => 'DESTINATION', 'index' => '02', 'style_class' => 'de', 'button_text' => 'Know more',
                        ],
                        [
                            'code' => 'JP', 'name' => 'Japan', 'region' => 'East Asia',
                            'description' => 'Premium market for high-quality cotton fabrics, silks, and specialized finishes.',
                            'url' => 'https://www.trade.gov.in/pages/country-guide',
                            'label' => 'DESTINATION', 'index' => '03', 'style_class' => 'jp', 'button_text' => 'Know more',
                        ],
                        [
                            'code' => 'AE', 'name' => 'United Arab Emirates', 'region' => 'Middle East',
                            'description' => 'Strategic hub for re-exports and growing direct retail demand under CEPA.',
                            'url' => 'https://www.trade.gov.in/pages/country-guide',
                            'label' => 'DESTINATION', 'index' => '04', 'style_class' => 'ae', 'button_text' => 'Know more',
                        ],
                    ],
                    'bottom_note' => 'TEFC and Trade Connect are collaborative initiatives. Country guidance is curated by the Directorate General of Foreign Trade (DGFT) and the Ministry of Commerce & Industry.',
                ]
            ]);

            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_export_process', 'order' => 3, 'title' => 'Textile Export Process (12-Step Guide)',
                'content' => [
                    'eyebrow' => 'Exporter learning guide',
                    'heading' => 'Textile Export Process',
                    'intro' => 'A practical guide to exporting textile products from India — from selecting a product and finding a buyer to customs clearance, shipment and receipt of export payment.',
                    'chips' => ['12 practical steps', 'Document checklists', 'Official resources', 'Indicative timelines'],
                    'notice' => 'Important: Timelines below are indicative planning estimates, not government-guaranteed service levels. Requirements vary by product/HS code, destination, buyer contract, transport mode and current notifications. Confirm current requirements with DGFT, Customs, your bank, the relevant textile Export Promotion Council and the buyer before shipment.',
                    'sidebar_heading' => 'Your export journey',
                    'steps_heading' => '12 steps from order to payment',
                    'steps_intro' => 'Open each step for documents, actions and source links.',
                    'steps' => [
                        [
                            'num' => '01', 'title' => 'Choose your textile product and target market',
                            'sub' => 'Define the product and check demand, buyer requirements and destination-market rules.', 'time' => '1–3 weeks*',
                            'description' => 'Choose a specific product such as cotton bed linen, woven fabric, knitted apparel, towels, carpets, scarves or technical textiles. Research target-market demand, competitors, landed cost and import requirements.',
                            'checks' => ['Fibre composition and construction (woven, knitted or non-woven).', 'GSM, width, dimensions, colourfastness, shrinkage and finish as relevant.', 'Destination rules for fibre-content/care labels, chemicals, flammability and product safety where applicable.', 'Buyer test protocols and sample approval requirements.'],
                            'records' => ['Product specification sheet.', 'Market research and landed-cost estimate.', 'Destination compliance matrix.', 'Buyer requirement list.'],
                            'actions' => ['Shortlist target countries and buyer types.', 'Check tariffs and whether a trade-agreement preference could apply.', 'Ask the importer to confirm standards, labelling language and tests in writing.'],
                            'links' => [['title' => 'India Trade Portal ↗', 'url' => 'https://www.india.tradeportal.in/'], ['title' => 'Department of Commerce ↗', 'url' => 'https://www.commerce.gov.in/']],
                            'footnote' => '*Initial research estimate; complexity varies.'
                        ],
                        [
                            'num' => '02', 'title' => 'Identify HS / ITC(HS) classification',
                            'sub' => 'Classification affects export policy, tariff, origin rules and customs declarations.', 'time' => '1–5 working days*',
                            'description' => 'Determine the Indian ITC(HS) export code from the product\'s material, construction, use and processing. Textile goods span multiple chapters; do not classify solely by marketing name.',
                            'checks' => ['Fibre percentages, construction, intended use and finish/coating.', 'Technical description, photos or sample if classification is uncertain.', 'Classification rationale and destination tariff code confirmed with the importer.'],
                            'records' => ['Whether export is free, restricted, prohibited or subject to a specific condition.', 'Applicable product-specific Rules of Origin if claiming FTA preference.', 'Whether destination-market import permits or product rules apply.'],
                            'actions' => ['Cross-verify 8-digit ITC(HS) code with current DGFT schedule.', 'Confirm destination tariff line with overseas buyer.', 'Check relevant export policy restrictions and duty drawback/remission tables.'],
                            'links' => [['title' => 'DGFT portal ↗', 'url' => 'https://www.dgft.gov.in/'], ['title' => 'ICEGATE / Customs ↗', 'url' => 'https://www.icegate.gov.in/'], ['title' => 'India Trade Portal ↗', 'url' => 'https://www.india.tradeportal.in/']],
                            'footnote' => '*Straightforward products; classification uncertainty can extend this.'
                        ],
                        [
                            'num' => '03', 'title' => 'Set up the exporter business and banking',
                            'sub' => 'Complete entity basics and arrange a bank relationship for export receipts.', 'time' => '2–10 working days*',
                            'description' => 'Choose a suitable business form and complete applicable PAN, business and bank formalities. Open a current account with a bank authorised to handle foreign exchange transactions. Discuss export receipts, LC handling, documentary collection and remittance evidence with the bank.',
                            'checks' => ['PAN and entity constitution documents (as applicable).', 'Business address proof and bank account evidence requested by the relevant service.', 'Authorised signatory details and digital-signature arrangements where needed.', 'Buyer due-diligence records.'],
                            'records' => ['Entity incorporation / registration certificate.', 'AD Code letter from authorised dealer bank branch.', 'Authorized signatory KYC and resolution.'],
                            'actions' => ['Obtain AD Code letter on bank letterhead for ICEGATE registration.', 'Activate foreign inward remittance tracking facilities.'],
                            'links' => [['title' => 'DGFT ↗', 'url' => 'https://www.dgft.gov.in/'], ['title' => 'Reserve Bank of India ↗', 'url' => 'https://www.rbi.org.in/']],
                            'footnote' => '*Assumes basic records are available; entity/bank setup varies.'
                        ],
                        [
                            'num' => '04', 'title' => 'Obtain IEC and complete applicable registrations',
                            'sub' => 'Check IEC, GST/LUT treatment and whether RCMC is required for your purpose.', 'time' => 'IEC planning allowance: 1–3 working days*',
                            'description' => 'IEC: Obtain an Importer-Exporter Code from DGFT unless a specific exemption applies. Keep IEC details accurate and comply with current update/confirmation requirements. GST: Confirm GST registration applicability. Eligible exporters may assess exporting under LUT without payment of IGST. RCMC: If required for a Foreign Trade Policy authorisation or benefit, apply to the appropriate Export Promotion Council.',
                            'checks' => ['PAN, business details, bank and address/contact details requested by DGFT.', 'Authentication/DSC as applicable to the current online process.', 'GST details and LUT acknowledgement, where applicable.', 'Product/category details and supporting documents for RCMC, if needed.'],
                            'records' => ['DGFT Importer Exporter Code (IEC) electronic certificate.', 'GST registration certificate & Letter of Undertaking (LUT) acknowledgement.', 'Registration Cum Membership Certificate (RCMC) from AEPC / TEXPROCIL.'],
                            'actions' => ['File online IEC application at dgft.gov.in with bank proof.', 'Submit annual online LUT on gst.gov.in for zero-rated supplies.'],
                            'links' => [['title' => 'DGFT — IEC ↗', 'url' => 'https://www.dgft.gov.in/'], ['title' => 'GST portal ↗', 'url' => 'https://www.gst.gov.in/'], ['title' => 'AEPC — apparel ↗', 'url' => 'https://www.aepcindia.com/'], ['title' => 'TEXPROCIL — cotton textiles ↗', 'url' => 'https://texprocil.org/']],
                            'footnote' => '*Planning estimate, not a DGFT service guarantee.'
                        ],
                        [
                            'num' => '05', 'title' => 'Find buyers, qualify them and approve samples',
                            'sub' => 'Confirm buyer credibility, textile specifications and acceptance criteria before production.', 'time' => '2–8 weeks*',
                            'description' => 'Find buyers through trade fairs, buyer-seller meets, recognised export bodies, trade directories and direct outreach. Verify buyer identity, import capability, references and credit risk before accepting open-credit exposure.',
                            'checks' => ['Lab dip, shade swatch, fabric hanger, strike-off, garment prototype or finished sample as requested.', 'Approved tech pack/specification: composition, GSM, width, dimensions, tolerances, finish, stitching and packing.', 'Test reports from a competent laboratory where required by law or contract.', 'Written sample approval, sealed sample record and revision history.', 'Purchase order or order confirmation.'],
                            'records' => ['Counter-samples and physical lab dips signed off by buyer.', 'ECGC buyer credit check report / insurance sanction letter.'],
                            'actions' => ['Submit lab dip swatches under specified light source standards.', 'Obtain written tech pack sign-off before commencing bulk fabric procurement.'],
                            'links' => [['title' => 'AEPC ↗', 'url' => 'https://www.aepcindia.com/'], ['title' => 'TEXPROCIL ↗', 'url' => 'https://texprocil.org/'], ['title' => 'India Trade Portal ↗', 'url' => 'https://www.india.tradeportal.in/']],
                            'footnote' => '*Buyer acquisition and approvals vary significantly.'
                        ],
                        [
                            'num' => '06', 'title' => 'Send quotation and finalise the export contract',
                            'sub' => 'Agree price, Incoterms, payment security, delivery dates and the exact document set.', 'time' => '2–10 working days*',
                            'description' => 'Cost product, packaging, inland transport, customs/port charges, freight, insurance where applicable, finance cost and margin. State the Incoterms® rule and named place/port precisely. Agree who arranges carriage, insurance, export clearance and import clearance.',
                            'checks' => ['Pro forma invoice or formal quotation.', 'Purchase order/sales contract and final product specifications.', 'Currency, payment terms, shipment window and agreed Incoterms® rule.', 'LC, if used, reviewed by the bank before production; otherwise documented advance, collection or open-account terms.', 'Agreed document list, including any CoO, insurance or inspection document required by the buyer/LC.'],
                            'records' => ['Proforma Invoice countersigned by buyer.', 'Irrevocable Letter of Credit (LC) copy verified by advising bank.'],
                            'actions' => ['Verify all LC terms against production capacity and export policy.', 'Lock exchange rate via forward cover with bank if warranted.'],
                            'links' => [['title' => 'DGFT ↗', 'url' => 'https://www.dgft.gov.in/'], ['title' => 'RBI ↗', 'url' => 'https://www.rbi.org.in/']],
                            'footnote' => '*Commercial negotiation estimate; LC amendments can take longer.'
                        ],
                        [
                            'num' => '07', 'title' => 'Manufacture, inspect and release the goods',
                            'sub' => 'Produce to the approved specification and retain traceable quality evidence.', 'time' => '2–12+ weeks*',
                            'description' => 'Plan procurement, production, dyeing/processing, finishing, stitching, testing and dispatch. Lead time varies widely between yarn, fabric, apparel, made-ups, carpets and technical textiles.',
                            'checks' => ['Approved purchase order, technical pack and master specification.', 'Fibre/material records, shade approvals and batch/lot traceability where relevant.', 'In-process and final inspection reports, quantity and defect records.', 'Tests required by buyer contract or destination law, such as colourfastness, dimensional change or restricted-substance testing where relevant.', 'Buyer inspection release/certificate only where required.'],
                            'records' => ['Mill fabric test certificate & yarn composition certificate.', 'Final Quality Audit Inspection Certificate (AQL standard).'],
                            'actions' => ['Conduct 4-point fabric inspection system for yardage.', 'Schedule buyer/third-party inspector prior to carton sealing.'],
                            'links' => [['title' => 'Textiles Committee Testing Labs ↗', 'url' => 'https://textilescommittee.gov.in/']],
                            'footnote' => '*Production is often the largest variable; estimate from factory capacity and approved lead time.'
                        ],
                        [
                            'num' => '08', 'title' => 'Pack, label and prepare commercial documents',
                            'sub' => 'Ensure cargo is protected and all descriptions, quantities, values and marks agree.', 'time' => '1–4 working days*',
                            'description' => 'Pack to protect goods from moisture, contamination, abrasion and handling damage. Confirm packaging with the buyer and carrier. If wooden packaging is used, check whether ISPM 15 requirements apply.',
                            'checks' => ['Commercial invoice: seller/buyer, number/date, currency, product description, quantity, price, value and agreed delivery terms.', 'Packing list: carton/package numbers, marks, contents, quantities, net/gross weight and dimensions as relevant.', 'Purchase order/contract and buyer packing instructions.', 'Destination-required product labels, such as fibre composition and care information where applicable.'],
                            'records' => ['Commercial Invoice with LUT statement and export declaration.', 'Carton-wise Packing List with gross/net weights and CBM volume.'],
                            'actions' => ['Verify barcodes, hangtags, and care labels match destination regulations.', 'Ensure ISPM 15 heat treatment stamps on all wooden pallets/crates.'],
                            'links' => [['title' => 'Foreign Trade Policy 2023 (PDF) ↗', 'url' => 'https://content.dgft.gov.in/Website/dgftprod/61d61bc2-272e-4880-b96c-c8f685a3b244/Foreign%20Trade%20Policy%202023.pdf'], ['title' => 'ICEGATE eSANCHIT guidance ↗', 'url' => 'https://www.icegate.gov.in/guidelines/electronic-filing/esanchit-advisory']],
                            'footnote' => '*After goods and final shipping details are ready.'
                        ],
                        [
                            'num' => '09', 'title' => 'Obtain Certificate of Origin, if required',
                            'sub' => 'Determine whether preferential or non-preferential origin documentation is needed.', 'time' => 'Allow 1–3 working days*',
                            'description' => 'A Certificate of Origin (CoO) evidences declared origin. A preferential CoO is relevant to an agreement-based tariff preference only if the product meets that agreement\'s Rules of Origin. A non-preferential CoO may be requested by a buyer or importing authority without a tariff preference claim.',
                            'checks' => ['IEC and registration on the applicable Common Digital Platform/eCoO 2.0 process.', 'Commercial invoice and shipment details.', 'Product description, HS code, origin criterion and manufacturing/material records.', 'For preferential origin, supporting evidence required by the specific agreement\'s product rule.', 'Authorised signatory/digital signature and fee details as required.'],
                            'records' => ['Draft CoO with origin criterion (e.g., CTH, PSR, Wholly Obtained).', 'Cost accountant certificate / value addition worksheet where required.'],
                            'actions' => ['Apply on DGFT eCoO platform (coo.dgft.gov.in) with digital signature.', 'Verify designated agency issuance (Textiles Committee / EPC).'],
                            'links' => [['title' => 'Trade Connect / eCoO 2.0 ↗', 'url' => 'https://www.trade.gov.in/'], ['title' => 'DGFT CoO notices ↗', 'url' => 'https://coo.dgft.gov.in/'], ['title' => 'TEXPROCIL CoO information ↗', 'url' => 'https://texprocil.org/certificateoforigin.htm']],
                            'footnote' => '*Origin evidence and issuing agency determine actual processing time.'
                        ],
                        [
                            'num' => '10', 'title' => 'File Shipping Bill and complete customs clearance',
                            'sub' => 'Submit the declaration, respond to queries and obtain Let Export Order.', 'time' => '1–3 working days after cargo/doc readiness*',
                            'description' => 'The exporter or authorised Customs Broker files the Shipping Bill through the prescribed electronic customs process. The declaration covers exporter/consignee, goods description, classification, quantity, value, port and applicable declarations/claims.',
                            'checks' => ['IEC and ICEGATE/customs setup; applicable AD Code/bank-account registration and port mapping as required.', 'Commercial invoice and packing list.', 'Purchase order/contract, permits, licences or additional certificates where applicable.', 'eSANCHIT supporting-document references/uploads where applicable.', 'Correct HS code, description, quantity, value, packages, weights and any eligible scheme claim.'],
                            'records' => ['Checklist copy of Shipping Bill signed by customs broker.', 'Customs Let Export Order (LEO) copy.'],
                            'actions' => ['Upload digitized documents to eSANCHIT and obtain IRN numbers.', 'Present cargo at CFS / ICD for customs physical examination / scanner.'],
                            'links' => [['title' => 'ICEGATE ↗', 'url' => 'https://www.icegate.gov.in/'], ['title' => 'Customs export procedure example ↗', 'url' => 'https://mumbaicustomszone3.gov.in/export-commissionerate-export-procedure'], ['title' => 'eSANCHIT guidance ↗', 'url' => 'https://www.icegate.gov.in/guidelines/electronic-filing/esanchit-advisory']],
                            'footnote' => '*Not a Customs SLA; examination, queries and port congestion can extend time.'
                        ],
                        [
                            'num' => '11', 'title' => 'Hand over cargo, ship goods and present documents',
                            'sub' => 'Obtain the transport document and submit the agreed set to bank/buyer.', 'time' => 'Transit is route-dependent',
                            'description' => 'Book freight under the agreed Incoterms® rule and meet carrier cut-offs. After loading/departure, obtain the applicable transport document: Bill of Lading for sea freight, Air Waybill for air freight, or the relevant road/rail/postal document. Check the details against the LC and contract.',
                            'checks' => ['Final commercial invoice and packing list.', 'Final Shipping Bill and LEO/customs export evidence.', 'Bill of Lading, Air Waybill or other transport document.', 'CoO, insurance, inspection/test or other certificates only where applicable/required.', 'Bill of Exchange and LC/collection documents where relevant.'],
                            'records' => ['Original Clean on Board Bill of Lading / Master Air Waybill.', 'Marine cargo insurance certificate / policy.'],
                            'actions' => ['Lodge complete negotiable document set with authorised dealer bank.', 'Dispatch electronic tracking and non-negotiable copies to importer.'],
                            'links' => [['title' => 'ICEGATE ↗', 'url' => 'https://www.icegate.gov.in/'], ['title' => 'DGFT export procedure guide (PDF) ↗', 'url' => 'https://content.dgft.gov.in/Website/CIEP.pdf']],
                            'footnote' => '*Transit depends on carrier schedule, route, port and destination.'
                        ],
                        [
                            'num' => '12', 'title' => 'Realise export payment and complete post-shipment compliance',
                            'sub' => 'Track payment through your bank and reconcile shipment, tax and export records.', 'time' => 'Contract / current RBI rules',
                            'description' => 'Track payment against the invoice through your authorised dealer bank. Follow up on overdue amounts, document discrepancies, short payments, claims or credit notes. Export proceeds must be realised and repatriated within the period prescribed by current RBI directions.',
                            'checks' => ['Bank advice/inward remittance evidence and invoice-wise reconciliation.', 'e-BRC or other export-realisation evidence available through current DGFT/banking systems, where applicable.', 'Final invoice, Shipping Bill, LEO, transport document and buyer correspondence.', 'GST/LUT records and refund documents, where applicable.', 'Benefit/remission documents only if the exporter and shipment qualify under current notifications.'],
                            'records' => ['Foreign Inward Remittance Certificate (FIRC) / bank realization statement.', 'DGFT electronic Bank Realisation Certificate (e-BRC).'],
                            'actions' => ['Generate self-certification e-BRC on DGFT portal linking Shipping Bill.', 'File monthly/quarterly GST refund claims on inverted duty / unutilized ITC.'],
                            'links' => [['title' => 'DGFT / e-BRC services ↗', 'url' => 'https://www.dgft.gov.in/'], ['title' => 'RBI directions ↗', 'url' => 'https://www.rbi.org.in/'], ['title' => 'GST portal ↗', 'url' => 'https://www.gst.gov.in/']],
                            'footnote' => '*Payment timing follows contract/payment instrument and current regulatory framework.'
                        ]
                    ],
                    'checklist_heading' => 'Export readiness checklist',
                    'checklist_sub' => 'This checklist is for your own tracking. Checking a box does not submit a form to a government department.',
                    'checklist_items' => [
                        'Product, market and buyer requirements confirmed',
                        'HS/ITC(HS) and export policy checked',
                        'Business, IEC and applicable tax registrations ready',
                        'Buyer verified; sample/specification approved',
                        'Price, Incoterms and payment terms agreed',
                        'Goods inspected and quality records retained',
                        'Invoice, packing list and labels checked',
                        'CoO/permits/additional documents assessed',
                        'Shipping Bill filed and LEO obtained',
                        'Transport document and bank document set collected',
                        'Documents presented as required',
                        'Payment tracked and records reconciled'
                    ],
                    'sources_heading' => 'Official portals and textile resources',
                    'sources_sub' => 'Use these as starting points and check the latest notification, product-specific rule and portal instructions. A general information page is not a substitute for current law.',
                    'sources' => [
                        ['title' => 'DGFT — IEC, FTP, ITC(HS)', 'desc' => 'Directorate General of Foreign Trade', 'url' => 'https://www.dgft.gov.in/'],
                        ['title' => 'Foreign Trade Policy 2023 (PDF)', 'desc' => 'Export policy and core documents', 'url' => 'https://content.dgft.gov.in/Website/dgftprod/61d61bc2-272e-4880-b96c-c8f685a3b244/Foreign%20Trade%20Policy%202023.pdf'],
                        ['title' => 'ICEGATE', 'desc' => 'Indian Customs electronic gateway', 'url' => 'https://www.icegate.gov.in/'],
                        ['title' => 'eSANCHIT guidance', 'desc' => 'Electronic supporting-document filing', 'url' => 'https://www.icegate.gov.in/guidelines/electronic-filing/esanchit-advisory'],
                        ['title' => 'Trade Connect / eCoO 2.0', 'desc' => 'Trade information and origin certificate pathway', 'url' => 'https://www.trade.gov.in/'],
                        ['title' => 'DGFT CoO information', 'desc' => 'Notices, manuals and help', 'url' => 'https://coo.dgft.gov.in/'],
                        ['title' => 'GST portal', 'desc' => 'GST services and applicable LUT/returns', 'url' => 'https://www.gst.gov.in/'],
                        ['title' => 'Reserve Bank of India', 'desc' => 'Current export proceeds/foreign exchange directions', 'url' => 'https://www.rbi.org.in/'],
                        ['title' => 'AEPC', 'desc' => 'Apparel Export Promotion Council', 'url' => 'https://www.aepcindia.com/'],
                        ['title' => 'TEXPROCIL', 'desc' => 'Cotton Textiles Export Promotion Council', 'url' => 'https://texprocil.org/'],
                        ['title' => 'India Trade Portal', 'desc' => 'Market and trade information', 'url' => 'https://www.india.tradeportal.in/'],
                        ['title' => 'Customs export procedure', 'desc' => 'Official example of Shipping Bill, examination and LEO', 'url' => 'https://mumbaicustomszone3.gov.in/export-commissionerate-export-procedure']
                    ],
                    'disclaimer' => 'This educational guide is not legal, tax, customs-broker, laboratory or banking advice. TEFC is an information and facilitation initiative; it does not issue export licences, certify textiles or conduct product testing.'
                ]
            ]);

            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_launched_services', 'order' => 4, 'title' => 'Recently Launched Services',
                'content' => [
                    'eyebrow' => 'TEFC digital initiatives',
                    'heading' => 'Recently Launched Services',
                    'intro' => 'Discover digital tools designed to help textile exporters find relevant trade information and navigate export-related queries.',
                    'services' => [
                        [
                            'title' => 'HS Code Intelligence Tool',
                            'badge' => 'New service',
                            'description' => 'Enter a product description to explore suggested HS codes, applicable FTA duty information and potentially relevant export schemes in one search.',
                            'action_text' => 'Try the tool',
                            'action_url' => '/p/hs-code',
                            'meta' => 'Product classification support',
                            'color' => 'blue',
                            'icon' => 'hs_code'
                        ],
                        [
                            'title' => 'AI Export Query Assistant',
                            'badge' => 'New service',
                            'description' => 'Ask export-related questions about documentation, regulations, duties and market entry, and get quick guidance from TEFC’s knowledge resources.',
                            'action_text' => 'Ask a question',
                            'action_url' => '#chatModal',
                            'meta' => 'Export information & guidance',
                            'color' => 'gold',
                            'icon' => 'ai_assistant'
                        ]
                    ],
                    'footer_note' => 'These services provide information and facilitation support. HS code suggestions, duty details and AI-generated responses should be independently verified against the relevant official sources before making export or compliance decisions.'
                ]
            ]);

            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_govt_schemes', 'order' => 5, 'title' => 'Government Schemes',
                'content' => [
                    'heading' => 'Government Schemes',
                    'intro' => 'Explore Government of India schemes and programmes that support textile manufacturing, export competitiveness, market access and export-enabling infrastructure.',
                    'schemes' => [
                        [
                            'eyebrow' => 'Export Promotion',
                            'title' => 'Export Promotion Mission (EPM)',
                            'description' => 'A unified, digitally driven framework for export promotion, with dedicated support for trade finance and export-enabling measures.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://www.commerce.gov.in/',
                            'icon' => 'epm'
                        ],
                        [
                            'eyebrow' => 'Export Remission',
                            'title' => 'Remission of Duties and Taxes on Exported Products (RoDTEP)',
                            'description' => 'A remission mechanism for eligible duties, taxes and levies borne on exported products and not otherwise refunded or remitted.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://www.dgft.gov.in/',
                            'icon' => 'rodtep'
                        ],
                        [
                            'eyebrow' => 'Apparel & Made-ups',
                            'title' => 'Rebate of State and Central Taxes and Levies (RoSCTL)',
                            'description' => 'A remission mechanism for eligible embedded State and Central taxes and levies on exports of apparel/garments and made-ups.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://texmin.nic.in/',
                            'icon' => 'rosctl'
                        ],
                        [
                            'eyebrow' => 'Textile Manufacturing',
                            'title' => 'Production Linked Incentive (PLI) Scheme for Textiles',
                            'description' => 'Promotes production of MMF apparel, MMF fabrics and specified technical textile products to build scale and global competitiveness.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://texmin.nic.in/',
                            'icon' => 'pli'
                        ],
                        [
                            'eyebrow' => 'Integrated Textile Infrastructure',
                            'title' => 'PM Mega Integrated Textile Regions and Apparel (PM MITRA) Parks',
                            'description' => 'Develops integrated textile parks intended to strengthen the complete textile value chain, reduce logistics costs and augment export potential.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://texmin.nic.in/',
                            'icon' => 'pm_mitra'
                        ],
                        [
                            'eyebrow' => 'Export Infrastructure',
                            'title' => 'Trade Infrastructure for Export Scheme (TIES)',
                            'description' => 'Supports export-linked infrastructure that helps address logistics, testing, warehousing, packaging and connectivity gaps.',
                            'link_text' => 'Know More',
                            'link_url' => 'https://www.commerce.gov.in/',
                            'icon' => 'ties'
                        ]
                    ],
                    'disclaimer' => 'TEFC role: TEFC provides information and facilitation support. Scheme approval, eligibility determination, sanction and disbursement remain with the competent Government authority and implementing agency.'
                ]
            ]);

            Section::create([
                'page_id' => $resourcesPage->id, 'type' => 'resources_downloads', 'order' => 6, 'title' => 'Downloads & Publications',
                'content' => [
                    'heading' => 'Downloads & Official Documents',
                    'intro' => 'Download official policy documents, export compliance guidelines, circulars, and standard operating procedures directly.',
                ]
            ]);
        }
    }
}
