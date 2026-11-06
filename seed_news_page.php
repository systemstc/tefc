<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'news')->first();
if (!$page) {
    echo "News page not found, creating it...\n";
    $page = Page::create(['slug' => 'news', 'title' => 'News & Updates — TEFC']);
}

// Clear old news sections
$page->sections()->delete();

// 1. News Hero Section
$page->sections()->create([
    'title' => 'News & Updates Hero',
    'type' => 'news_hero',
    'order' => 1,
    'content' => [
        'title' => 'News & Updates',
        'subtitle' => 'Stay informed with the latest domestic and international textile industry news, policy updates, trade developments, and export opportunities.',
        'ticker_label' => '🔴 Breaking News',
        'ticker_items' => [
            ['category' => 'Policy', 'text' => 'RoDTEP rates revised upward for MMF garments — effective 1 May 2026'],
            ['category' => 'Trade', 'text' => 'India-EU FTA negotiations enter final round; textiles chapter agreed'],
            ['category' => 'Market', 'text' => 'Bangladesh garment exports slow — opportunity for Indian exporters']
        ]
    ]
]);

// 2. Latest News Directory
$page->sections()->create([
    'title' => 'Latest News Section',
    'type' => 'news_latest',
    'order' => 2,
    'content' => [
        'featured' => [
            'title' => 'Union Budget 2026-27 Allocates Rs.4,200 Crore for Textile Export Incentives Under PLI & RoDTEP',
            'desc' => 'The Ministry of Textiles has announced a massive funding boost for synthetic fiber production and cotton handloom clusters to enhance global competitiveness.',
            'category' => 'Union Budget',
            'date' => 'Published: Jan 2026',
            'duration' => '12 Mins Read',
            'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=800'
        ],
        'items' => [
            [
                'title' => 'India-EU FTA Textile Chapter Clinched; Zero-Duty Access for 280 HS Codes',
                'category' => 'Trade Agreements',
                'date' => '18 May 2026',
                'color' => '#1a5d8a'
            ],
            [
                'title' => 'Surat Textile Clusters Record 22% Export Surge in Q1 2026 Amid New Digital Handshake Initiatives',
                'category' => 'Cluster News',
                'date' => '17 May 2026',
                'color' => '#7b1fa2'
            ],
            [
                'title' => 'GOTS 8.0 Launches: Stringent Compliance Standards Imposed on Organic Cotton Exporters',
                'category' => 'Quality Standards',
                'date' => '16 May 2026',
                'color' => '#2e7d32'
            ],
            [
                'title' => 'US Buyer Group Inks Agreements with 45 Jaipur Artisan Alliances for Sustainable Block Prints',
                'category' => 'USA Market',
                'date' => '15 May 2026',
                'color' => '#d84315'
            ],
            [
                'title' => 'Varanasi Weaver Associations Showcase Silk Mastery at Tokyo International Handloom Fair 2026',
                'category' => 'Global Events',
                'date' => '14 May 2026',
                'color' => '#00838f'
            ]
        ]
    ]
]);

// 3. In Focus Grid
$page->sections()->create([
    'title' => 'In Focus Section',
    'type' => 'news_infocus',
    'order' => 3,
    'content' => [
        'subtitle' => 'Deep dives, special reports, and expert policy analysis on key trade parameters.',
        'items' => [
            [
                'title' => 'India\'s Textile Export Target of $100 Billion by 2030 — In-Depth Progress Report',
                'category' => 'Policy Analysis',
                'desc' => 'An extensive audit of India\'s infrastructure, export incentives, and cluster modernization plans over the next 4 years.',
                'date' => 'May 2026',
                'icon' => 'fa-chart-line',
                'color' => '#6c1340'
            ],
            [
                'title' => 'US-China Trade Tariffs & Shift: Opportunities for Indian Cotton Exporters',
                'category' => 'Trade Shift',
                'desc' => 'How the ongoing synthetic yarn tariff increases are redirecting global apparel buyers to Indian powerlooms.',
                'date' => 'May 2026',
                'icon' => 'fa-star',
                'color' => '#1a5d8a'
            ],
            [
                'title' => 'Technical Textiles: High-Value Growth Engine for Indian MSMEs in Europe',
                'category' => 'Technical Textiles',
                'desc' => 'Analyzing the rise of medical, agro, and geo-textiles and how small-scale manufacturers can secure international certifications.',
                'date' => 'May 2026',
                'icon' => 'fa-graduation-cap',
                'color' => '#7b1fa2'
            ],
            [
                'title' => 'Sustainable Fashion and Zero-Carbon Mandate: Preparing for the EU Green Deal 2026',
                'category' => 'Compliance',
                'desc' => 'A step-by-step documentation checklist for exporters dealing with European luxury brands under carbon taxes.',
                'date' => 'May 2026',
                'icon' => 'fa-leaf',
                'color' => '#2e7d32'
            ],
            [
                'title' => 'Digital Threads: How Artificial Intelligence is Revamping Ludhiana Knitwear Processing Houses',
                'category' => 'Technology',
                'desc' => 'Case study of automated pattern mapping and predictive waste reduction reducing operational costs by 18%.',
                'date' => 'May 2026',
                'icon' => 'fa-desktop',
                'color' => '#d84315'
            ],
            [
                'title' => 'Modernizing Handloom Clusters: Financial Aid and Training Programs under PM-MITRA Scheme',
                'category' => 'Infrastructure',
                'desc' => 'Complete walkthrough of state-funded capital subsidies available for upgrading looms and dyeing yards.',
                'date' => 'May 2026',
                'icon' => 'fa-users',
                'color' => '#00838f'
            ]
        ]
    ]
]);

// 4. Domestic vs International News lists
$page->sections()->create([
    'title' => 'Domestic vs International News',
    'type' => 'news_lists',
    'order' => 4,
    'content' => [
        'domestic' => [
            ['title' => 'Tirupur Knitwear Manufacturers Seek Fast-Track GST Refund System', 'badge' => 'GST Refund', 'date' => '18 May 2026'],
            ['title' => 'Cotton Seed Prices Stabilize Across Punjab and Haryana Belts', 'badge' => 'Cotton Prices', 'date' => '17 May 2026'],
            ['title' => 'Union Minister Approves ₹85 Crore for Surat Dyeing Yard Eco-Upgrades', 'badge' => 'State Subsidy', 'date' => '15 May 2026'],
            ['title' => 'Varanasi Handloom silk Guild Demands Special Power Subsidy Restoration', 'badge' => 'Power Supply', 'date' => '14 May 2026'],
            ['title' => 'Jaipur block printers association hosts three-day natural dye workshop', 'badge' => 'Workshop', 'date' => '12 May 2026'],
            ['title' => 'National Textile Council Appoints New Board of Export Directors', 'badge' => 'Governance', 'date' => '10 May 2026'],
            ['title' => 'Karur Home Textiles Guild Reports 85% Attendance at ESG Compliance Seminar', 'badge' => 'Compliance', 'date' => '08 May 2026']
        ],
        'international' => [
            ['title' => 'US Department of Commerce Imposes 35% Countervailing Duty on MMF Imports', 'badge' => 'US Tariff', 'date' => '18 May 2026'],
            ['title' => 'EU Green Deal Regulations to Take Effect from September 2026', 'badge' => 'EU Regulations', 'date' => '16 May 2026'],
            ['title' => 'Global Freight Container Index Drops to 18-Month Low, easing export overheads', 'badge' => 'Logistics', 'date' => '15 May 2026'],
            ['title' => 'Tokyo Fashion League to Feature Dedicated Indian Handloom Pavilion', 'badge' => 'Tokyo Fair', 'date' => '12 May 2026'],
            ['title' => 'Germany\'s Supply Chain Due Diligence Act (LkSG) Curbs Synthetic Yarn Imports', 'badge' => 'Compliance', 'date' => '10 May 2026'],
            ['title' => 'UAE Retail Giants Sign Multi-Million Dollar Procurement Pact with TEFC Sellers', 'badge' => 'UAE Procurement', 'date' => '08 May 2026'],
            ['title' => 'Global Cotton Advisory Committee Projects Steady Global Demand for 2026-27', 'badge' => 'Cotton Demand', 'date' => '05 May 2026']
        ]
    ]
]);

// 5. Popular news and events
$page->sections()->create([
    'title' => 'Most Popular & Events Sidebar',
    'type' => 'news_popular',
    'order' => 5,
    'content' => [
        'popular' => [
            ['title' => 'Step-by-Step Guide on RoDTEP Benefits and Online Application Portal for Indian Exporters', 'badge' => 'EXPORTS', 'color' => '#6c1340'],
            ['title' => 'Revised HS Code Directory for Home Textiles and Apparels under India-UAE CEPA', 'badge' => 'CEPA DIRECTORY', 'color' => '#1a5d8a'],
            ['title' => 'OEKO-TEX Certification Requirements Checklist 2026: Costs, Audits & Local Labs', 'badge' => 'CERTIFICATIONS', 'color' => '#2e7d32'],
            ['title' => 'PM-MITRA Park Ludhiana Layout Approved; Phase 1 Allotments to Commence in July', 'badge' => 'PARK LAYOUT', 'color' => '#d84315'],
            ['title' => 'PLI Scheme for Apparel: How MSMEs Can Apply for 12% Fiscal Subsidies', 'badge' => 'PLI SUBSTANCE', 'color' => '#00838f']
        ],
        'events' => [
            ['title' => 'TEFC Global Buyer-Seller Conclave 2026', 'date' => '24-26 May 2026', 'location' => 'Pragati Maidan, New Delhi'],
            ['title' => 'Sustainable Textile Certifications Summit', 'date' => '05 June 2026', 'location' => 'Karur Textile Club'],
            ['title' => 'Varanasi Handloom Silk Expo 2026', 'date' => '12-15 June 2026', 'location' => 'Ganga Convention Hall']
        ]
    ]
]);

// 6. Trending Articles
$page->sections()->create([
    'title' => 'Trending Articles Section',
    'type' => 'news_trending',
    'order' => 6,
    'content' => [
        'items' => [
            ['title' => 'The Rise of Hemp Textiles in Indian Export Hubs', 'category' => 'FIBRES', 'date' => '18 May 2026', 'image' => 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?auto=format&fit=crop&q=80&w=300', 'color' => '#6c1340'],
            ['title' => 'Dabu Printing: Rajasthan\'s Mud-Resist Magic in Paris Boutiques', 'category' => 'CRAFT', 'date' => '17 May 2026', 'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=300', 'color' => '#1a5d8a'],
            ['title' => 'Smart Wearables: Tech-Infused Sportswear from Ludhiana Mills', 'category' => 'TECH', 'date' => '15 May 2026', 'image' => 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?auto=format&fit=crop&q=80&w=300', 'color' => '#7b1fa2'],
            ['title' => 'Sourcing Organic Cotton: List of Certified GOTS Ginning Mills', 'category' => 'COTTON', 'date' => '14 May 2026', 'image' => 'https://images.unsplash.com/photo-1533158326339-7f3cf2404354?auto=format&fit=crop&q=80&w=300', 'color' => '#2e7d32'],
            ['title' => 'Surat\'s Waterless Dyeing Revolution: Reducing Waste by 90%', 'category' => 'ECO', 'date' => '12 May 2026', 'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&q=80&w=300', 'color' => '#d84315'],
            ['title' => 'E-Commerce Branding Guide for Handloom Weavers and Self-Help Groups', 'category' => 'DIGITAL', 'date' => '10 May 2026', 'image' => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?auto=format&fit=crop&q=80&w=300', 'color' => '#00838f'],
            ['title' => 'India-Australia ECTA: Explaining the 5% Duty Exemption for Khadi Fabrics', 'category' => 'POLICY', 'date' => '08 May 2026', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=300', 'color' => '#6c1340'],
            ['title' => 'Recycled Polyester (rPET) Yarn: Compliance Checklist for US Buyers', 'category' => 'COMPLIANCE', 'date' => '05 May 2026', 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=300', 'color' => '#b38e5d']
        ]
    ]
]);

// 7. News Press Footer & Stats Banner
$page->sections()->create([
    'title' => 'News Metrics & Press Support Banner',
    'type' => 'news_metrics',
    'order' => 7,
    'content' => [
        'stats' => [
            ['value' => '1,240+', 'label' => 'Articles Published'],
            ['value' => '86K+', 'label' => 'Monthly Readers'],
            ['value' => '42', 'label' => 'Countries Covered'],
            ['value' => '120+', 'label' => 'Articles Per Month'],
            ['value' => '18K+', 'label' => 'Active Subscribers']
        ],
        'footer_title' => 'Have a news tip or press release for the Indian textile export industry?',
        'button_text' => 'Submit to News Desk'
    ]
]);

echo "News page seeded successfully with high-fidelity dynamic CMS items!\n";
