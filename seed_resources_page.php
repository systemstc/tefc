<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'resources')->first();
if (!$page) {
    echo "Resources page not found, creating it...\n";
    $page = Page::create(['slug' => 'resources', 'title' => 'Resource Center — TEFC']);
}

// Clear old resources sections
$page->sections()->delete();

// 1. Resources Hero Section
$page->sections()->create([
    'title' => 'Resources Hero',
    'type' => 'resources_hero',
    'order' => 1,
    'content' => [
        'title' => 'Resource Center',
        'subtitle' => 'Access essential trade guides, bilateral tariff structures, step-by-step export processes, and market intelligence reports.'
    ]
]);

// 2. Market Research Section
$page->sections()->create([
    'title' => 'Market Research Section',
    'type' => 'resources_market_research',
    'order' => 2,
    'content' => [
        'featured' => [
            'value' => '$100B',
            'value_label' => 'Export Target by 2030',
            'category' => 'FEATURED ADVISORY',
            'title' => 'Vision 2030 Roadmap: Scaling Indian Textile Exports Globally',
            'desc' => 'A comprehensive policy framework detailing capital infusion, cluster modernization under PM-MITRA, and high-value technical textiles expansion to secure global market share.'
        ],
        'items' => [
            [
                'category' => 'MMF Fibres',
                'title' => 'Global Polyester & MMF Trade Report Q1 2026',
                'desc' => 'Analyzing export demand trajectories in the European Union and the impact of the newly introduced anti-dumping duties on synthetic yarns.',
                'date' => 'May 2026',
                'color' => '#6c1340'
            ],
            [
                'category' => 'Cotton Sector',
                'title' => 'Indian Organic Cotton Export Strategy & ESG Compliance',
                'desc' => 'Detailed mapping of European carbon border adjustments and documentation requirements for cotton ginning houses under GOTS 8.0.',
                'date' => 'May 2026',
                'color' => '#1a5d8a'
            ],
            [
                'category' => 'Artisanal Craft',
                'title' => 'Jaipur & Varanasi Artisan Alliances Sourcing Directory',
                'desc' => 'Auditing the trade potential of block print and handloom silk clusters, with standard pricing models for Western lifestyle buyers.',
                'date' => 'April 2026',
                'color' => '#2e7d32'
            ],
            [
                'category' => 'Logistics',
                'title' => 'Bilateral Freight Container Rates & Shipping Index',
                'desc' => 'Weekly freight analysis for key trade routes: Mumbai to Rotterdam, Chennai to Hamburg, and Mundra to New York.',
                'date' => 'April 2026',
                'color' => '#7b1fa2'
            ],
            [
                'category' => 'Global Trends',
                'title' => 'US Apparel Market Trends & Buyer Preferences 2026',
                'desc' => 'Investigating sustainable apparel consumption shifts in North America and how MSME exporters can apply for green certifications.',
                'date' => 'March 2026',
                'color' => '#d84315'
            ],
            [
                'category' => 'State Subsidies',
                'title' => 'PM-MITRA Park Capital Incentives and Financial Aid Guide',
                'desc' => 'Complete breakdown of state-allocated capital subsidies for setting up operations inside greenfield integrated textile parks.',
                'date' => 'March 2026',
                'color' => '#00838f'
            ]
        ]
    ]
]);

// 3. Export Process Section
$page->sections()->create([
    'title' => 'Export Process Timeline',
    'type' => 'resources_export_process',
    'order' => 3,
    'content' => [
        'steps' => [
            [
                'step' => '01',
                'title' => 'Registration & Licensing',
                'desc' => 'Obtain an Import Export Code (IEC) from DGFT, join the appropriate Export Promotion Council (e.g. TEXPROCIL), and register on the TEFC Single Window portal.',
                'status' => 'MANDATORY'
            ],
            [
                'step' => '02',
                'title' => 'Buyer Sourcing & Product Matching',
                'desc' => 'Upload your catalog to the TEFC Product Directory to secure automated matching with verified global buyers, and participate in buyer-seller conclaves.',
                'status' => 'MATCHING'
            ],
            [
                'step' => '03',
                'title' => 'Quality Standards & Certifications',
                'desc' => 'Acquire mandatory export certifications such as OEKO-TEX, GOTS, or ISO depending on target destinations to comply with chemical restriction mandates.',
                'status' => 'COMPLIANCE'
            ],
            [
                'step' => '04',
                'title' => 'Customs Clearance & Single-Window Filing',
                'desc' => 'Submit shipping bills online, generate dynamic QR-backed e-invoices, and complete automated customs clearance via Indian Customs ICEGATE.',
                'status' => 'SHIPPING'
            ],
            [
                'step' => '05',
                'title' => 'Incentives Claims & Subsidy Settlement',
                'desc' => 'File for tax rebates under RoDTEP or RoSCTL directly via your TEFC portal dashboard and receive capital subsidies within 14 business days.',
                'status' => 'BENEFITS'
            ]
        ],
        'useful_links' => [
            ['title' => 'DGFT Single Window Portal', 'url' => '#'],
            ['title' => 'ICEGATE Customs Portal', 'url' => '#'],
            ['title' => 'TEXPROCIL Export Council', 'url' => '#'],
            ['title' => 'Ministry of Textiles India', 'url' => '#']
        ]
    ]
]);

// 4. Bilateral Tariffs Section
$page->sections()->create([
    'title' => 'Bilateral Tariffs & FTAs',
    'type' => 'resources_tariffs',
    'order' => 4,
    'content' => [
        'stats' => [
            ['value' => 'India-UAE CEPA', 'label' => '0% Custom Duty on Textiles'],
            ['value' => 'India-Australia ECTA', 'label' => 'Immediate Duty Elimination'],
            ['value' => 'India-EU FTA', 'label' => '280 HS Codes Agreed']
        ],
        'items' => [
            [
                'title' => 'India-UAE CEPA Trade Guide',
                'status' => 'ACTIVE',
                'status_color' => '#2e7d32',
                'date' => 'Active since May 2022',
                'details' => 'Exporters receive 100% duty-free access for MMF and cotton apparel. TEFC single-window dynamic certificates of origin are approved for quick custom clearances.'
            ],
            [
                'title' => 'India-Australia ECTA Benefits',
                'status' => 'ACTIVE',
                'status_color' => '#2e7d32',
                'date' => 'Active since Dec 2022',
                'details' => 'Immediate elimination of custom duties on all textile products. Handloom and artisanal goods are 100% exempt from tariff caps and origin restrictions.'
            ],
            [
                'title' => 'India-EU FTA Negotiating Status',
                'status' => 'IN NEGOTIATION',
                'status_color' => '#d84315',
                'date' => 'Target: Dec 2026',
                'details' => 'Bilateral trade rounds agreed on 280 textile HS codes. Safeguard mechanisms, organic certification verification, and strict origin checks are being resolved.'
            ],
            [
                'title' => 'Bilateral Tariffs: India-USA',
                'status' => 'STANDARD',
                'status_color' => '#b38e5d',
                'date' => 'Standard Tariff Regime',
                'details' => 'Cotton garments face standard 16.5% duties. Exporters are advised to utilize generalized system of preferences and TEFC verified compliance marks.'
            ]
        ]
    ]
]);

// 5. News & Advisories Section
$page->sections()->create([
    'title' => 'Trade Advisories & Alerts',
    'type' => 'resources_advisories',
    'order' => 5,
    'content' => [
        'items' => [
            [
                'icon' => 'fa-bullhorn',
                'category' => 'POLICY ALERT',
                'title' => 'RoDTEP Rates Revised for MMF Apparels',
                'desc' => 'Exporters can now claim up to 4.3% rebates on FOB value, effective from 1 May 2026. Submit claims directly via single-window dashboard.',
                'color' => '#6c1340'
            ],
            [
                'icon' => 'fa-exclamation-triangle',
                'category' => 'SHIPPING ALERT',
                'title' => 'Rotterdam Container Congestion Delayed',
                'desc' => 'Average freight clearance times at Netherlands terminals have risen to 6 business days. Consider alternative ports like Antwerp.',
                'color' => '#d84315'
            ],
            [
                'icon' => 'fa-check-circle',
                'category' => 'COMPLIANCE UPDATE',
                'title' => 'EU Green Deal Zero-Carbon Mandate 2026',
                'desc' => 'New labeling requirements require verified organic cotton origins. Standard QR mapping templates are available in our downloads section.',
                'color' => '#2e7d32'
            ]
        ]
    ]
]);

// 6. FAQs Section
$page->sections()->create([
    'title' => 'Resource FAQs',
    'type' => 'resources_faqs',
    'order' => 6,
    'content' => [
        'items' => [
            [
                'q' => 'How do I apply for an Import Export Code (IEC) online?',
                'a' => 'Exporters can register directly on the DGFT single window portal, upload PAN details, verified bank account, and secure an IEC instantly within 24 hours.'
            ],
            [
                'q' => 'How does the RoDTEP incentive scheme benefit small weavers?',
                'a' => 'The RoDTEP scheme reimburses local taxes, electricity duties, and custom charges incurred during weaving. Weavers receive direct fiscal refunds.'
            ],
            [
                'q' => 'What documentation is required to claim the India-UAE CEPA zero-duty benefit?',
                'a' => 'Exporters must submit shipping invoices, packing lists, a valid IEC, and generate a certified TEFC single-window Certificate of Origin (CoO) online.'
            ],
            [
                'q' => 'Can technical textile MSMEs claim state-funded capital subsidies?',
                'a' => 'Yes, under the PM-MITRA and amended technology upgradation schemes, small scale units can claim up to 15% capital subsidies on modern high-speed looms.'
            ]
        ]
    ]
]);

// 7. Downloads Section
$page->sections()->create([
    'title' => 'Official Downloads',
    'type' => 'resources_downloads',
    'order' => 7,
    'content' => [
        'items' => [
            ['title' => 'TEFC Import Export Registration Guide PDF', 'size' => '2.4 MB', 'type' => 'PDF'],
            ['title' => 'India-UAE CEPA Certificate of Origin Form Template', 'size' => '1.1 MB', 'type' => 'XLSX'],
            ['title' => 'RoDTEP Rates Scheme Directory 2026', 'size' => '4.8 MB', 'type' => 'PDF'],
            ['title' => 'GOTS 8.0 Compliance Standard Operating Procedures', 'size' => '3.2 MB', 'type' => 'PDF'],
            ['title' => 'Shipping Bill and single-window Customs customs Declaration', 'size' => '850 KB', 'type' => 'DOCX'],
            ['title' => 'PM-MITRA Park Phase 1 Allotment Application', 'size' => '1.5 MB', 'type' => 'PDF'],
            ['title' => 'Technical Textiles Capital Subsidy Guidelines', 'size' => '2.1 MB', 'type' => 'PDF'],
            ['title' => 'Organic Cotton Weavers Guild Sourcing Directory', 'size' => '5.6 MB', 'type' => 'PDF']
        ]
    ]
]);

// 8. Statistics counter and callout press desk
$page->sections()->create([
    'title' => 'Resources Statistics & Press Support',
    'type' => 'resources_metrics',
    'order' => 8,
    'content' => [
        'stats' => [
            ['value' => '10,000+', 'label' => 'Active Exporters'],
            ['value' => '150+', 'label' => 'Countries Covered'],
            ['value' => '24/7', 'label' => 'Single Window Clearance'],
            ['value' => '₹4,200Cr', 'label' => 'Incentives Settled'],
            ['value' => '0% CoO', 'label' => 'Tariff Processing Fee']
        ]
    ]
]);

echo "Resources page seeded successfully with dynamic CMS sections!\n";
