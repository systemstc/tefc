<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'help-support')->first();
if (!$page) {
    echo "Help-support page not found, creating it...\n";
    $page = Page::create(['slug' => 'help-support', 'title' => 'Help & Support — TEFC']);
}

// Clear old help-support sections
$page->sections()->delete();

// 1. Hero Section
$page->sections()->create([
    'title' => 'Help & Support Hero',
    'type' => 'help_support_hero',
    'order' => 1,
    'content' => [
        'title' => 'Help & Support Desk',
        'subtitle' => 'Browse textile quality standards highlights, export regulatory policies, and submit custom queries to our facilitation desks.'
    ]
]);

// 2. Section 1: Standards FAQs
$page->sections()->create([
    'title' => 'Textile Industry & Standards FAQs',
    'type' => 'help_support_standards_faqs',
    'order' => 2,
    'content' => [
        'title' => 'Textile Industry & Standards (50 FAQ Highlights)',
        'items' => [
            [
                'q' => 'What is the Jute Mark India (JMI) scheme?',
                'a' => 'JMI is a certification mark that provides an assurance of quality and origin for jute products manufactured in India.'
            ],
            [
                'q' => 'How do I apply for Handloom Mark?',
                'a' => 'You can apply through the Textiles Committee portal by submitting your loom details and fabric samples for testing.'
            ],
            [
                'q' => 'What are Technical Textiles?',
                'a' => 'These are textiles manufactured primarily for their technical performance and functional properties rather than their aesthetic characteristics (e.g., medical, agro-textiles).'
            ],
            [
                'q' => 'Which lab tests are mandatory for cotton export?',
                'a' => 'Common tests include GSM, Color Fastness, Tensile Strength, and Shrinkage tests as per ISO or AATCC standards.'
            ],
            [
                'q' => 'Is organic certification required for textiles?',
                'a' => 'If you are marketing as "Organic", GOTS (Global Organic Textile Standard) certification is generally required for international markets.'
            ],
            [
                'q' => 'What is the role of TEFC in cluster development?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'How does the EPC (Export Promotion Council) assist new exporters?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Requirements for Silk Mark certification?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Benefits of ZED certification for textile MSMEs?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Difference between Carded and Combed yarn standards?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What are the azo-dye restrictions for EU exports?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'How to access the Textile Testing Laboratory services?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What is the PM MITRA scheme?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Standards for Fire Retardant fabrics in exports?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'How to verify the authenticity of Pashmina?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ]
        ]
    ]
]);

// 3. Section 3: Procedures FAQs
$page->sections()->create([
    'title' => 'Export Procedures & Policies FAQs',
    'type' => 'help_support_procedures_faqs',
    'order' => 3,
    'content' => [
        'title' => 'Export Procedures & Policies (50 FAQ Highlights)',
        'items' => [
            [
                'q' => 'What is the RoDTEP Scheme?',
                'a' => 'Remission of Duties and Taxes on Exported Products provides a rebate on central/state taxes not otherwise refunded.'
            ],
            [
                'q' => 'How to obtain an IEC (Import Export Code)?',
                'a' => 'You must apply online through the DGFT website using your PAN and bank details.'
            ],
            [
                'q' => 'What is a Certificate of Origin (CoO)?',
                'a' => 'A document declaring in which country a commodity was manufactured. Crucial for claiming FTA benefits.'
            ],
            [
                'q' => 'What are FTA benefits for Indian textiles in Australia?',
                'a' => 'Under the ECTA, most Indian textile products enjoy zero-duty access to the Australian market.'
            ],
            [
                'q' => 'What is a Letter of Credit (LC)?',
                'a' => 'A payment mechanism where the buyer\'s bank guarantees payment to the exporter upon submission of valid shipping documents.'
            ],
            [
                'q' => 'Difference between FOB and CIF contracts?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What is the duration of an EPCG scheme obligation?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'How to claim drawback on duty-paid inputs?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Role of ECGC in protecting textile exporters?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What is the ICEGATE portal used for?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Mandatory documents for shipping to USA?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What is the eBRC and why is it important?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'How to handle a customs show-cause notice?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'What is the Status Holder Certificate?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ],
            [
                'q' => 'Benefits of the Advance Authorization Scheme?',
                'a' => 'Contact our facilitation desk for the detailed compliance checklist regarding this specific query.'
            ]
        ]
    ]
]);

// 4. Submit Query Contact Desk
$page->sections()->create([
    'title' => 'Facilitation Desk Details',
    'type' => 'help_support_facilitation',
    'order' => 4,
    'content' => [
        'email' => 'support@tefc.gov.in',
        'toll_free' => '1800-123-4567',
        'address' => 'Textiles Committee, Textiles Committee, Prabhadevi, Mumbai.',
        'hours' => 'Monday to Friday (9 AM - 6 PM)',
        'categories' => [
            'Textile Quality Testing',
            'IEC Registration',
            'CoO Verification',
            'RoDTEP Scheme Claiming',
            'PM-MITRA Allotments',
            'Bilateral FTA Queries'
        ]
    ]
]);

echo "Help-support page seeded successfully with dynamic CMS sections!\n";
