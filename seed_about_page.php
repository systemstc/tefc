<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'about')->first();
if (!$page) {
    echo "Page not found\n";
    exit;
}

$page->sections()->delete();

$page->sections()->create([
    'title' => 'About TEFC',
    'type' => 'about_hero',
    'order' => 1,
    'content' => [
        'subtitle' => 'The Textiles Export Facilitation Centre (TEFC) is a strategic initiative of the Textiles Committee, Ministry of Textiles, Government of India, created to empower MSMEs and textile exporters to compete in the global market.',
        'side_stats' => [
            ['label' => 'Total MSMEs Empowered', 'value' => '4,500+'],
            ['label' => 'Exporters Supported', 'value' => '1,200+'],
            ['label' => 'Total Trade Facilitated', 'value' => '15,000 Cr'],
            ['label' => 'Founded in Year', 'value' => 'Est. 2022']
        ]
    ]
]);

$page->sections()->create([
    'title' => 'Overview',
    'type' => 'about_overview',
    'order' => 2,
    'content' => [
        'description' => 'The Textiles Export Facilitation Centre (TEFC) is a dedicated support ecosystem established by the Textiles Committee. We serve as a one-stop-shop for Indian textile exporters, providing technical guidance, market intelligence, and logistical support to ensure that the heritage of Indian textiles reaches every corner of the world.',
        'features' => [
            ['icon' => 'fas fa-globe', 'title' => 'Global Market Access', 'text' => 'Connecting local weavers to international buyers through verified trade portals.'],
            ['icon' => 'fas fa-hand-holding-heart', 'title' => 'Handholding Support', 'text' => 'End-to-end guidance from registration to final export documentation.'],
            ['icon' => 'fas fa-chart-line', 'title' => 'Capacity Building', 'text' => 'Regular workshops on quality standards and global market trends.'],
            ['icon' => 'fas fa-file-contract', 'title' => 'Policy Guidance', 'text' => 'Detailed information on FTAs and government scheme benefits.']
        ],
        'pulse_stats' => [
            ['label' => 'Daily Traffic', 'value' => '12,500+', 'trend' => '+15% this month'],
            ['label' => 'Active Countries', 'value' => '45+', 'trend' => 'Recently added: UAE, Japan'],
            ['label' => 'Verified Sellers', 'value' => '1,800+']
        ]
    ]
]);

$page->sections()->create([
    'title' => 'Mission & Vision',
    'type' => 'mission_vision',
    'order' => 3,
    'content' => [
        'mission' => 'To facilitate and strengthen the export ecosystem of India\'s textile industry by providing technical support, market intelligence, quality certification, and policy guidance—enabling growth for MSMEs and established exporters.',
        'vision' => 'To emerge as India\'s premier destination for high-quality, sustainable, and competitive textile exports, serving as the bridge between Indian craftsmanship and global demand.',
        'objectives' => [
            'Empower MSMEs through direct export assistance.',
            'Maintain world-class quality standards through certification.',
            'Facilitate trade through Free Trade Agreements (FTAs).',
            'Foster innovation in textile design and manufacturing.'
        ]
    ]
]);

$page->sections()->create([
    'title' => 'Key People',
    'type' => 'people_grid',
    'order' => 4,
    'content' => [
        'people' => [
            ['name' => 'Shri. Ajit Singh', 'designation' => 'Chairman, Textiles Committee', 'image' => ''],
            ['name' => 'Smt. Roopa Dutta', 'designation' => 'Member Secretary', 'image' => ''],
            ['name' => 'Shri. Kartikeya Mishra', 'designation' => 'Director (Exports)', 'image' => ''],
            ['name' => 'Dr. Meena Rani', 'designation' => 'Nodal Officer, TEFC', 'image' => '']
        ],
        'footer_about' => 'The Textiles Committee (TEFC) was established by the Textiles Committee Act, 1963. We are a statutory body under the Ministry of Textiles, Government of India. Our primary objective is to ensure the quality of textiles and textile machinery both for internal consumption and export.'
    ]
]);

$page->sections()->create([
    'title' => 'Organisation Structure',
    'type' => 'org_structure',
    'order' => 5,
    'content' => []
]);

$page->sections()->create([
    'title' => 'Milestones',
    'type' => 'milestone_timeline',
    'order' => 6,
    'content' => [
        'milestones' => [
            ['year' => '2022', 'title' => 'TEFC Foundation', 'text' => 'The Textiles Committee officially announced the formation of the Export Facilitation Centre to support MSMEs.'],
            ['year' => '2023', 'title' => 'Digital Portal Launch', 'text' => 'The first version of the TEFC digital portal was launched, connecting 500+ sellers.'],
            ['year' => '2024', 'title' => 'Global Expansion', 'text' => 'Strategic MoUs signed with international trade bodies in Europe and the Middle East.'],
            ['year' => '2025', 'title' => 'AI-Powered Insights', 'text' => 'Integrated advanced trade analytics to help exporters predict global demand trends.']
        ]
    ]
]);

echo "About page seeded successfully\n";
