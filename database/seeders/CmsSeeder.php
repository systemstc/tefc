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
        ];

        foreach ($pageData as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], ['title' => $p['title']]);
        }

        // 3. Navigation (Full Restoration)
        $headerMenu = Menu::updateOrCreate(['location' => 'header'], ['name' => 'Main Navigation']);
        MenuItem::where('menu_id', $headerMenu->id)->delete();

        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Home', 'url' => '/', 'page_slug' => 'home', 'order' => 1]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'About', 'url' => '/p/about', 'page_slug' => 'about', 'order' => 2]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Clusters', 'url' => '/p/clusters', 'page_slug' => 'clusters', 'order' => 3]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Resources', 'url' => '/resources', 'page_slug' => null, 'order' => 4]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'FTA Benefits', 'url' => '/p/fta-benefits', 'page_slug' => 'fta-benefits', 'order' => 5]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Online Services', 'url' => '/p/online-services', 'page_slug' => 'online-services', 'order' => 6]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'News', 'url' => '/p/news', 'page_slug' => 'news', 'order' => 7]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Media Center', 'url' => '/p/media', 'page_slug' => 'media', 'order' => 8]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'label' => 'Help & Support', 'url' => '/p/help-support', 'page_slug' => 'help-support', 'order' => 9]);

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
    }
}
