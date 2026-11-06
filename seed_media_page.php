<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'media')->first();
if (!$page) {
    echo "Media page not found, creating it...\n";
    $page = Page::create(['slug' => 'media', 'title' => 'Media Center — TEFC']);
}

// Clear old media sections
$page->sections()->delete();

// 1. Hero Section
$page->sections()->create([
    'title' => 'Media Center',
    'type' => 'media_hero',
    'order' => 1,
    'content' => [
        'subtitle' => 'Access comprehensive video tutorials, cluster documentaries, photos and news updates about the Indian textile export community.',
        'stats' => [
            ['label' => 'Videos in Library', 'value' => '36'],
            ['label' => 'Video Views', 'value' => '1.2 Lakh+'],
            ['label' => 'Total Content', 'value' => '48 Hrs+'],
            ['label' => 'Featured Playlists', 'value' => '6']
        ]
    ]
]);

// 2. Training Videos Section
$page->sections()->create([
    'title' => 'Training Videos',
    'type' => 'media_training',
    'order' => 2,
    'content' => [
        'subtitle' => 'Educational guides, compliance courses and webinars for textile MSMEs.',
        'featured_video' => [
            'title' => 'Export Documentation Checklist - 2025',
            'description' => 'A comprehensive walk-through of the necessary steps and paperwork required for Indian textile exporters starting out in the EU and North American markets under new trade rules.',
            'duration' => '12:45 Mins',
            'published' => 'Jan 2025',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
        ],
        'items' => [
            ['title' => 'Introduction to FTA for Exporters', 'category' => 'Export Promotion', 'published' => 'Jan 2025', 'duration' => '15:20', 'url' => '#'],
            ['title' => 'Quality Standard Certifications for Global Markets', 'category' => 'Quality Standards', 'published' => 'Dec 2024', 'duration' => '18:30', 'url' => '#'],
            ['title' => 'Exploring PLI Scheme benefits', 'category' => 'Documentation', 'published' => 'Oct 2024', 'duration' => '10:15', 'url' => '#'],
            ['title' => 'Finance Essentials for Textile Exporters', 'category' => 'FTA Benefits', 'published' => 'Sep 2024', 'duration' => '22:40', 'url' => '#'],
            ['title' => 'Packaging and Labelling requirements', 'category' => 'MSME Capacity', 'published' => 'Aug 2024', 'duration' => '14:50', 'url' => '#'],
            ['title' => 'Digital Tools for MSME Exporters', 'category' => 'MSME Capacity', 'published' => 'Jul 2024', 'duration' => '11:30', 'url' => '#']
        ]
    ]
]);

// 3. Cluster Videos Section
$page->sections()->create([
    'title' => 'Cluster Videos',
    'type' => 'media_cluster',
    'order' => 3,
    'content' => [
        'subtitle' => 'Documentaries showcasing prominent textile cluster regions, craftsmanship and scale.',
        'featured_video' => [
            'title' => 'Karur Cluster - Home Textiles Global Giant',
            'description' => 'Go inside India\'s home textiles capital - Karur. See how handlooms and modern technology blend to produce export-grade linen, from raw yarn management to finished product.',
            'duration' => '18:15 Mins',
            'published' => 'Jan 2025',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
        ],
        'items' => [
            ['title' => 'Surat - The Synthetic Capital', 'category' => 'Surat', 'published' => 'Dec 2024', 'duration' => '15:40', 'url' => '#'],
            ['title' => 'Ichalkaranji Weaving Sector', 'category' => 'Ichalkaranji', 'published' => 'Nov 2024', 'duration' => '12:50', 'url' => '#'],
            ['title' => 'Jaipur Traditional Block Printing', 'category' => 'Jaipur', 'published' => 'Oct 2024', 'duration' => '14:15', 'url' => '#'],
            ['title' => 'Ludhiana Knitwear Powerhouse', 'category' => 'Ludhiana', 'published' => 'Sep 2024', 'duration' => '17:35', 'url' => '#'],
            ['title' => 'Varanasi - Legacy of Handloom Silk', 'category' => 'Varanasi', 'published' => 'Aug 2024', 'duration' => '19:20', 'url' => '#'],
            ['title' => 'Bhagalpur - Tussar Silk weavers', 'category' => 'Other', 'published' => 'Jul 2024', 'duration' => '11:45', 'url' => '#']
        ]
    ]
]);

// 4. Other Videos Section
$page->sections()->create([
    'title' => 'Other Videos',
    'type' => 'media_other',
    'order' => 4,
    'content' => [
        'subtitle' => 'Recent events, expert talks, trade shows and media coverage.',
        'items' => [
            ['title' => 'IITF 2024 - Textile Pavilion Highlights', 'category' => 'Event', 'published' => 'Nov 2024', 'duration' => '05:25', 'url' => '#'],
            ['title' => 'Budget 2025 - Impact on Textile Sector', 'category' => 'Expert', 'published' => 'Feb 2025', 'duration' => '11:50', 'url' => '#'],
            ['title' => 'Ministry Address at TEFC Launch Ceremony', 'category' => 'Event', 'published' => 'Aug 2024', 'duration' => '08:35', 'url' => '#'],
            ['title' => 'Interviews: Exporters on New Free Trade Agreements', 'category' => 'Expert', 'published' => 'Jan 2025', 'duration' => '14:10', 'url' => '#'],
            ['title' => 'Sustainable Textiles: Future Trend Seminar', 'category' => 'Seminar', 'published' => 'Oct 2024', 'duration' => '21:15', 'url' => '#'],
            ['title' => 'Weaver Handloom Workshop - Varanasi', 'category' => 'Workshop', 'published' => 'Sep 2024', 'duration' => '10:45', 'url' => '#']
        ]
    ]
]);

// 5. Photo Gallery Section
$page->sections()->create([
    'title' => 'Photo Gallery',
    'type' => 'media_photos',
    'order' => 5,
    'content' => [
        'subtitle' => 'Images from standard quality workshops, trade delegations and exhibitions.',
        'items' => [
            ['title' => 'Varanasi Skill Training Workshop', 'category' => 'Capacity Building', 'published' => 'Feb 2025'],
            ['title' => 'Karur Buyer Seller Meet 2024', 'category' => 'Buyer-Seller Meets'],
            ['title' => 'Surat Fabric Quality Forum', 'category' => 'Quality Forums'],
            ['title' => 'Ludhiana Knitwear Expo', 'category' => 'Exhibitions'],
            ['title' => 'Weavers Meet - Bhagalpur', 'category' => 'Capacity Building'],
            ['title' => 'Jaipur Block Print Demonstration', 'category' => 'Training Programmes'],
            ['title' => 'TEFC Delhi Launch Ceremony', 'category' => 'Exhibitions'],
            ['title' => 'Mumbai Apparel Fashion Show', 'category' => 'Buyer-Seller Meets']
        ]
    ]
]);

echo "Media page sections seeded successfully!\n";
