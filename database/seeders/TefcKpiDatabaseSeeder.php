<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TefcStaff;
use App\Models\TefcAdvisory;
use App\Models\TefcTraining;
use App\Models\TefcPublication;
use App\Models\TefcQuery;
use App\Models\TefcClusterProfile;
use Illuminate\Support\Facades\DB;

class TefcKpiDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        TefcStaff::truncate();
        TefcAdvisory::truncate();
        TefcTraining::truncate();
        TefcPublication::truncate();
        TefcQuery::truncate();
        TefcClusterProfile::truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        $clusters = [
            'ichalkaranji' => [
                'name' => 'Ichalkaranji',
                'state' => 'Maharashtra',
                'districts' => 'Revenue district of Kolhapur',
                'specialization' => 'Powerloom Weaving, Yarn Processing & Grey Fabric',
                'target_msmes' => 240,
                'staff' => [
                    ['name' => 'Anand Kulkarni', 'age' => 42, 'designation' => 'TEFC Centre Officer', 'education' => 'B.Tech Textile Tech, MBA', 'experience_years' => 16, 'status' => 'active', 'email' => 'anand.kulkarni@tefc.gov.in', 'phone' => '+91 98231 45671'],
                    ['name' => 'Sanjay Patil', 'age' => 36, 'designation' => 'Textile Facilitator & Technical Advisor', 'education' => 'M.Sc Textile Chemistry', 'experience_years' => 11, 'status' => 'active', 'email' => 'sanjay.patil@tefc.gov.in', 'phone' => '+91 98231 45672'],
                ],
                'advised_count' => 34,
                'iec_breakdown' => ['no_iec' => 8, 'iec_no_export' => 2, 'inactive_iec' => 7, 'new_iec' => 2, 'regular' => 15],
                'trainings' => [
                    ['title' => 'Export Documentation & Customs Clearance for Weaving Units', 'type' => 'training_programme', 'participants' => 95],
                    ['title' => 'Accessing EU & US Markets under Modern FTA Rules', 'type' => 'training_programme', 'participants' => 85],
                    ['title' => 'Digital Export Marketing & B2B International Sourcing', 'type' => 'training_programme', 'participants' => 70],
                    ['title' => 'Zero Defect Quality Standards in Powerloom Exporting', 'type' => 'training_programme', 'participants' => 60],
                ],
                'publications_count' => 7,
                'queries_received' => 28,
                'queries_resolved' => 4,
                'sla_rate' => 91.00,
            ],
            'jaipur' => [
                'name' => 'Jaipur',
                'state' => 'Rajasthan',
                'districts' => 'Revenue district of Jaipur',
                'specialization' => 'Block Printed Textiles, Made-ups, Garments & Quilts',
                'target_msmes' => 484,
                'staff' => [
                    ['name' => 'Vikram Singh', 'age' => 45, 'designation' => 'TEFC Lead Centre Director', 'education' => 'M.Tech Textile Engg, Foreign Trade Diploma', 'experience_years' => 19, 'status' => 'active', 'email' => 'vikram.singh@tefc.gov.in', 'phone' => '+91 94140 12345'],
                    ['name' => 'Pooja Sharma', 'age' => 33, 'designation' => 'Craft & Apparel Export Facilitator', 'education' => 'NIFT Post Graduate (Design & Merchandising)', 'experience_years' => 9, 'status' => 'active', 'email' => 'pooja.sharma@tefc.gov.in', 'phone' => '+91 94140 12346'],
                    ['name' => 'Rajesh Meena', 'age' => 29, 'designation' => 'Documentation & DGFT Advisory Specialist', 'education' => 'MBA International Business', 'experience_years' => 5, 'status' => 'active', 'email' => 'rajesh.meena@tefc.gov.in', 'phone' => '+91 94140 12347'],
                ],
                'advised_count' => 138,
                'iec_breakdown' => ['no_iec' => 2, 'iec_no_export' => 3, 'inactive_iec' => 1, 'new_iec' => 2, 'regular' => 130],
                'trainings' => [
                    ['title' => 'Sanganeri & Bagru Block Print Exporter Readiness Programme', 'type' => 'training_programme', 'participants' => 78],
                    ['title' => 'Navigating E-Commerce Export Hubs (ECEH) for Handicrafts', 'type' => 'training_programme', 'participants' => 55],
                    ['title' => 'Sustainable Dyeing Compliance for European High-End Retailers', 'type' => 'training_programme', 'participants' => 45],
                    ['title' => 'DGFT Export Schemes, Duty Drawback & RoDTEP Optimization', 'type' => 'training_programme', 'participants' => 40],
                ],
                'publications_count' => 31,
                'queries_received' => 69,
                'queries_resolved' => 6,
                'sla_rate' => 95.83,
            ],
            'karur' => [
                'name' => 'Karur',
                'state' => 'Tamil Nadu',
                'districts' => 'Revenue district of Karur',
                'specialization' => 'Home Textiles, Table Linen, Kitchen Textiles & Made-ups',
                'target_msmes' => 89,
                'staff' => [
                    ['name' => 'K. Murugan', 'age' => 48, 'designation' => 'TEFC Centre Officer', 'education' => 'B.Tech Textile Tech, PGD Foreign Trade', 'experience_years' => 22, 'status' => 'active', 'email' => 'k.murugan@tefc.gov.in', 'phone' => '+91 94433 78901'],
                    ['name' => 'S. Balasubramanian', 'age' => 38, 'designation' => 'Home Textile Export Technical Advisor', 'education' => 'M.Sc Textile Technology', 'experience_years' => 14, 'status' => 'active', 'email' => 's.bala@tefc.gov.in', 'phone' => '+91 94433 78902'],
                ],
                'advised_count' => 16,
                'iec_breakdown' => ['no_iec' => 0, 'iec_no_export' => 10, 'inactive_iec' => 3, 'new_iec' => 0, 'regular' => 3],
                'trainings' => [
                    ['title' => 'OEKO-TEX & GOTS Certification Standards for Home Textiles', 'type' => 'training_programme', 'participants' => 52],
                    ['title' => 'Export Pricing Strategies & Forex Hedging for Made-Up Exporters', 'type' => 'training_programme', 'participants' => 42],
                    ['title' => 'Trade with UK & Australia under recent Free Trade Accords', 'type' => 'training_programme', 'participants' => 35],
                ],
                'publications_count' => 18,
                'queries_received' => 120,
                'queries_resolved' => 80,
                'sla_rate' => 95.80,
            ],
            'ludhiana' => [
                'name' => 'Ludhiana',
                'state' => 'Punjab',
                'districts' => 'Revenue district of Ludhiana',
                'specialization' => 'Knitwear, Hosiery, Woollen Garments & Thermal Fabrics',
                'target_msmes' => 353,
                'staff' => [
                    ['name' => 'Harpreet Singh', 'age' => 44, 'designation' => 'TEFC Centre Officer', 'education' => 'B.E. Textile Engineering', 'experience_years' => 18, 'status' => 'active', 'email' => 'harpreet.singh@tefc.gov.in', 'phone' => '+91 98150 67891'],
                    ['name' => 'Gurinder Kaur', 'age' => 34, 'designation' => 'Knitwear Export Technical Advisor', 'education' => 'MBA International Business, NIFT Alum', 'experience_years' => 10, 'status' => 'active', 'email' => 'gurinder.kaur@tefc.gov.in', 'phone' => '+91 98150 67892'],
                ],
                'advised_count' => 79,
                'iec_breakdown' => ['no_iec' => 8, 'iec_no_export' => 1, 'inactive_iec' => 0, 'new_iec' => 1, 'regular' => 69],
                'trainings' => [
                    ['title' => 'Woolmark Quality Assurance & Premium Knitwear Exporting', 'type' => 'training_programme', 'participants' => 45],
                    ['title' => 'Exploring Russian, CIS & Middle East Winter Garment Markets', 'type' => 'training_programme', 'participants' => 38],
                    ['title' => 'Logistics Optimization & Container Freight Management', 'type' => 'training_programme', 'participants' => 30],
                    ['title' => 'Cross-Border B2B E-Commerce for Hosiery Manufacturers', 'type' => 'training_programme', 'participants' => 25],
                    ['title' => 'Technical Textiles: Circular Knitting for Sports & Healthcare', 'type' => 'training_programme', 'participants' => 23],
                ],
                'publications_count' => 18,
                'queries_received' => 49,
                'queries_resolved' => 32,
                'sla_rate' => 100.00,
            ],
            'surat' => [
                'name' => 'Surat',
                'state' => 'Gujarat',
                'districts' => 'Revenue district of Surat and Navsari',
                'specialization' => 'Man-made Fibre (MMF), Synthetic Fabrics, Technical Textiles & Jacquards',
                'target_msmes' => 115,
                'staff' => [
                    ['name' => 'Bhavin Shah', 'age' => 41, 'designation' => 'TEFC Centre Officer', 'education' => 'B.Tech Textile Chemistry, PGD Export Management', 'experience_years' => 15, 'status' => 'active', 'email' => 'bhavin.shah@tefc.gov.in', 'phone' => '+91 98250 11223'],
                    ['name' => 'Dharmesh Desai', 'age' => 35, 'designation' => 'MMF & Technical Textiles Advisor', 'education' => 'M.Sc Polymer & Textile Tech', 'experience_years' => 11, 'status' => 'active', 'email' => 'dharmesh.desai@tefc.gov.in', 'phone' => '+91 98250 11224'],
                ],
                'advised_count' => 19,
                'iec_breakdown' => ['no_iec' => 0, 'iec_no_export' => 2, 'inactive_iec' => 0, 'new_iec' => 0, 'regular' => 17],
                'trainings' => [
                    ['title' => 'Poly-Viscose and Nylon Technical Fabrics for Defense & Automotive Exports', 'type' => 'training_programme', 'participants' => 50],
                    ['title' => 'Export Factoring & Working Capital Facilities for MSME Weavers', 'type' => 'training_programme', 'participants' => 42],
                    ['title' => 'Overcoming Anti-Dumping & Countervailing Measures in MMF Export', 'type' => 'training_programme', 'participants' => 32],
                ],
                'publications_count' => 17,
                'queries_received' => 34,
                'queries_resolved' => 30,
                'sla_rate' => 100.00,
            ],
            'varanasi' => [
                'name' => 'Varanasi',
                'state' => 'Uttar Pradesh',
                'districts' => 'Revenue district of Varanasi and Sant Ravidas Nagar (Bhadohi) & Revenue taluks of Mirzapur and Chunar of Mirzapur district',
                'specialization' => 'Banarasi Silk, Handloom Brocades, Handmade Carpets & Rugs',
                'target_msmes' => 197,
                'staff' => [
                    ['name' => 'Ramesh Chandra Mishra', 'age' => 47, 'designation' => 'TEFC Centre Officer', 'education' => 'Handloom Tech Diploma, M.A., PGD Foreign Trade', 'experience_years' => 21, 'status' => 'active', 'email' => 'ramesh.mishra@tefc.gov.in', 'phone' => '+91 94500 33441'],
                    ['name' => 'Alok Kumar Gupta', 'age' => 32, 'designation' => 'Silk & Carpet Export Facilitator', 'education' => 'B.Tech Textile Tech', 'experience_years' => 8, 'status' => 'active', 'email' => 'alok.gupta@tefc.gov.in', 'phone' => '+91 94500 33442'],
                ],
                'advised_count' => 39,
                'iec_breakdown' => ['no_iec' => 7, 'iec_no_export' => 4, 'inactive_iec' => 5, 'new_iec' => 10, 'regular' => 13],
                'trainings' => [
                    ['title' => 'Geographical Indication (GI) Tagging and Silk Brocade Export Branding', 'type' => 'training_programme', 'participants' => 60],
                ],
                'publications_count' => 18,
                'queries_received' => 32,
                'queries_resolved' => 93,
                'sla_rate' => 100.00,
            ],
        ];

        foreach ($clusters as $code => $data) {
            // 1. Seed Cluster Profile
            TefcClusterProfile::create([
                'centre_code' => $code,
                'cluster_name' => $data['name'],
                'state' => $data['state'],
                'covered_districts' => $data['districts'],
                'total_target_msmes' => $data['target_msmes'],
                'specialization' => $data['specialization'],
            ]);

            // 2. Seed Staff (KPI 1: Manpower)
            foreach ($data['staff'] as $st) {
                TefcStaff::create([
                    'centre_code' => $code,
                    'name' => $st['name'],
                    'age' => $st['age'],
                    'designation' => $st['designation'],
                    'education' => $st['education'],
                    'experience_years' => $st['experience_years'],
                    'email' => $st['email'],
                    'phone' => $st['phone'],
                    'status' => $st['status'],
                    'joining_date' => now()->subMonths(rand(6, 24)),
                ]);
            }

            // 3. Seed Advisories (KPI 2: Exporter Base)
            $totalAdvised = $data['advised_count'];
            $breakdown = $data['iec_breakdown'];
            $counter = 1;

            foreach ($breakdown as $iecType => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $topic = match($iecType) {
                        'no_iec' => 'DGFT Portal Orientation & IEC Application Steps',
                        'iec_no_export' => 'First-Time Exporter Handholding & HS Code Selection',
                        'inactive_iec' => 'IEC Profile Updation & Overseas Buyer Linkage',
                        'new_iec' => 'Fresh IEC Handover & Product Sourcing Strategy',
                        default => 'Customs Compliance & Market Diversification'
                    };

                    TefcAdvisory::create([
                        'centre_code' => $code,
                        'company_name' => "{$data['name']} Textile Enterprise #{$counter}",
                        'iec_status' => $iecType,
                        'session_date' => now()->subDays(rand(1, 180)),
                        'advisory_topic' => $topic,
                        'outcome_notes' => "Assisted unit with export orientation and documentation checklist.",
                    ]);
                    $counter++;
                }
            }

            // 4. Seed Trainings (KPI 3: Training Programmes)
            foreach ($data['trainings'] as $tr) {
                TefcTraining::create([
                    'centre_code' => $code,
                    'title' => $tr['title'],
                    'type' => $tr['type'],
                    'event_date' => now()->subDays(rand(15, 200)),
                    'venue' => "{$data['name']} Chamber of Commerce & Industry Hall",
                    'participants_count' => $tr['participants'],
                    'topics_covered' => 'Export compliance, FTA advantages, international buyer negotiations.',
                ]);
            }

            // 5. Seed Publications (KPI 4: Newsletters)
            for ($p = 1; $p <= $data['publications_count']; $p++) {
                $type = ($p % 4 === 0) ? 'trade_advisory' : (($p % 5 === 0) ? 'market_report' : 'newsletter');
                TefcPublication::create([
                    'centre_code' => $code,
                    'title' => "{$data['name']} Export Trade Bulletin - Edition #{$p}",
                    'type' => $type,
                    'issue_date' => now()->subDays($p * 12),
                    'summary' => "Monthly sectoral intelligence for textile clusters in {$data['state']}.",
                ]);
            }

            // 6. Seed Queries (KPI 5: Queries Handled)
            $qRec = $data['queries_received'];
            $qRes = $data['queries_resolved'];
            $slaPct = $data['sla_rate'];

            // Seed received queries
            for ($q = 1; $q <= $qRec; $q++) {
                $isResolved = ($q <= $qRes);
                $isSla = ($isResolved && ($slaPct >= 95 || rand(1, 100) <= $slaPct));
                $cat = ['hsn_classification', 'compliance', 'documentation', 'incentives', 'fta_origin'][rand(0, 4)];

                TefcQuery::create([
                    'centre_code' => $code,
                    'company_name' => "Cluster Enterprise {$data['name']} Q{$q}",
                    'contact_person' => "Proprietor {$q}",
                    'subject' => "Query regarding textile export compliance and #{cat}",
                    'category' => $cat,
                    'received_date' => now()->subDays(rand(5, 120)),
                    'resolved_date' => $isResolved ? now()->subDays(rand(1, 4)) : null,
                    'status' => $isResolved ? 'resolved' : 'pending',
                    'is_within_sla' => $isSla,
                    'resolution_notes' => $isResolved ? 'Official advisory note and clarification provided to applicant.' : null,
                ]);
            }

            // If Varanasi resolved 93 queries (including historical backlog), seed the extra resolved queries
            if ($qRes > $qRec) {
                $extra = $qRes - $qRec;
                for ($eq = 1; $eq <= $extra; $eq++) {
                    TefcQuery::create([
                        'centre_code' => $code,
                        'company_name' => "Legacy Exporter {$data['name']} B{$eq}",
                        'contact_person' => "Artisan {$eq}",
                        'subject' => "Historical backlog query resolved",
                        'category' => 'compliance',
                        'received_date' => now()->subDays(150 + $eq),
                        'resolved_date' => now()->subDays(rand(10, 50)),
                        'status' => 'resolved',
                        'is_within_sla' => true,
                        'resolution_notes' => 'Resolution completed via special facilitation desk.',
                    ]);
                }
            }
        }
    }
}
