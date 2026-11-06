<?php

namespace App\Services;

use App\Models\TefcStaff;
use App\Models\TefcAdvisory;
use App\Models\TefcTraining;
use App\Models\TefcPublication;
use App\Models\TefcQuery;
use App\Models\TefcClusterProfile;

class KpiAggregationService
{
    public static function getClusterCodes(): array
    {
        return ['ichalkaranji', 'jaipur', 'karur', 'ludhiana', 'surat', 'varanasi'];
    }

    public static function getClusterNames(): array
    {
        return [
            'ichalkaranji' => 'Ichalkaranji',
            'jaipur' => 'Jaipur',
            'karur' => 'Karur',
            'ludhiana' => 'Ludhiana',
            'surat' => 'Surat',
            'varanasi' => 'Varanasi',
        ];
    }

    public static function getClusterStates(): array
    {
        return [
            'ichalkaranji' => 'Maharashtra',
            'jaipur' => 'Rajasthan',
            'karur' => 'Tamil Nadu',
            'ludhiana' => 'Punjab',
            'surat' => 'Gujarat',
            'varanasi' => 'Uttar Pradesh',
        ];
    }

    /**
     * Compute full dynamic metrics across all 6 centres directly from database records.
     */
    public static function getFullDashboardData(string $period = 'yearly', ?string $periodValue = null): array
    {
        $clusters = self::getClusterNames();
        $clusterStates = self::getClusterStates();
        $clusterCodes = self::getClusterCodes();

        // 1. Collect individual cluster stats
        $rawStats = [];
        foreach ($clusterCodes as $code) {
            $staffCount = TefcStaff::where('centre_code', $code)->where('status', 'active')->count();
            
            // Exporters
            $uniqueAdvised = TefcAdvisory::where('centre_code', $code)->distinct('company_name')->count('company_name');
            $expNoIec = TefcAdvisory::where('centre_code', $code)->where('iec_status', 'no_iec')->count();
            $expIecNoExport = TefcAdvisory::where('centre_code', $code)->where('iec_status', 'iec_no_export')->count();
            $expInactiveIec = TefcAdvisory::where('centre_code', $code)->where('iec_status', 'inactive_iec')->count();
            $expNewIec = TefcAdvisory::where('centre_code', $code)->where('iec_status', 'new_iec')->count();

            // Training
            $trainingsCount = TefcTraining::where('centre_code', $code)->where('type', 'training_programme')->count();
            $bsmCount = TefcTraining::where('centre_code', $code)->where('type', 'buyer_seller_meet')->count();
            $exportersTrained = (int) TefcTraining::where('centre_code', $code)->sum('participants_count');

            // Publications
            $publicationsCount = TefcPublication::where('centre_code', $code)->count();

            // Queries
            $queriesReceived = TefcQuery::where('centre_code', $code)->count();
            $queriesResolved = TefcQuery::where('centre_code', $code)->where('status', 'resolved')->count();
            $queriesWithinSla = TefcQuery::where('centre_code', $code)->where('status', 'resolved')->where('is_within_sla', true)->count();
            
            $slaPercent = $queriesResolved > 0 
                ? round(($queriesWithinSla / $queriesResolved) * 100, 2)
                : 100.00;

            // Cluster Profile & Coverage
            $profile = TefcClusterProfile::where('centre_code', $code)->first();
            $targetMsmes = $profile ? max(1, $profile->total_target_msmes) : 250;
            $coveragePercent = round(($uniqueAdvised / $targetMsmes) * 100, 1);

            $rawStats[$code] = [
                'manpower' => $staffCount,
                'exporters_advised' => $uniqueAdvised,
                'exp_no_iec' => $expNoIec,
                'exp_iec_no_export' => $expIecNoExport,
                'exp_inactive_iec' => $expInactiveIec,
                'exp_new_iec' => $expNewIec,
                'trainings_count' => $trainingsCount,
                'bsm_count' => $bsmCount,
                'exporters_trained' => $exportersTrained,
                'publications_count' => $publicationsCount,
                'queries_received' => $queriesReceived,
                'queries_resolved' => $queriesResolved,
                'queries_sla_resolved' => $queriesWithinSla,
                'sla_percent' => $slaPercent,
                'clusters_covered' => 1,
                'target_msmes' => $targetMsmes,
                'coverage_percent' => $coveragePercent,
            ];
        }

        // 2. Compute Cumulative Totals
        $cumManpower = array_sum(array_column($rawStats, 'manpower'));
        $cumAdvised = array_sum(array_column($rawStats, 'exporters_advised'));
        $cumNoIec = array_sum(array_column($rawStats, 'exp_no_iec'));
        $cumIecNoExport = array_sum(array_column($rawStats, 'exp_iec_no_export'));
        $cumInactiveIec = array_sum(array_column($rawStats, 'exp_inactive_iec'));
        $cumNewIec = array_sum(array_column($rawStats, 'exp_new_iec'));
        $cumTrainings = array_sum(array_column($rawStats, 'trainings_count'));
        $cumBsm = array_sum(array_column($rawStats, 'bsm_count'));
        $cumTrained = array_sum(array_column($rawStats, 'exporters_trained'));
        $cumPublications = array_sum(array_column($rawStats, 'publications_count'));
        $cumQueriesReceived = array_sum(array_column($rawStats, 'queries_received'));
        $cumQueriesResolved = array_sum(array_column($rawStats, 'queries_resolved'));
        $cumQueriesWithinSla = array_sum(array_column($rawStats, 'queries_sla_resolved'));
        $cumSlaPercent = $cumQueriesResolved > 0 
            ? round(($cumQueriesWithinSla / $cumQueriesResolved) * 100, 2)
            : 100.00;
        $cumTargetMsmes = array_sum(array_column($rawStats, 'target_msmes'));
        $cumCoveragePercent = $cumTargetMsmes > 0 
            ? round(($cumAdvised / $cumTargetMsmes) * 100, 1)
            : 19.9;

        // 3. Build Dynamic Master Table Structure
        $kpiData = [
            'cumulative' => [
                'manpower' => [
                    'title' => 'ManPower',
                    'items' => [
                        array_merge([
                            'indicator' => 'Operational TEFC Advisory Personnel Deployed',
                            'cumulative' => $cumManpower,
                        ], array_combine($clusterCodes, array_column($rawStats, 'manpower')))
                    ]
                ],
                'exporter_base' => [
                    'title' => 'Exporter Base',
                    'items' => [
                        array_merge([
                            'indicator' => 'Number of unique exporters receiving advisory support',
                            'cumulative' => $cumAdvised,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exporters_advised'))),
                        array_merge([
                            'indicator' => 'a. Prospective Exporters (Without IEC)',
                            'cumulative' => $cumNoIec,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exp_no_iec'))),
                        array_merge([
                            'indicator' => 'b. IEC Holders with No Export History',
                            'cumulative' => $cumIecNoExport,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exp_iec_no_export'))),
                        array_merge([
                            'indicator' => 'c. Inactive IEC Holders (No Exports in the Last Two Years)',
                            'cumulative' => $cumInactiveIec,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exp_inactive_iec'))),
                        array_merge([
                            'indicator' => 'd. Number of New IEC Generated through TEFC',
                            'cumulative' => $cumNewIec,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exp_new_iec'))),
                    ]
                ],
                'training' => [
                    'title' => 'Training Programmes',
                    'items' => [
                        array_merge([
                            'indicator' => 'Number of training programmes conducted',
                            'cumulative' => $cumTrainings,
                        ], array_combine($clusterCodes, array_column($rawStats, 'trainings_count'))),
                        array_merge([
                            'indicator' => 'Number of buyer-seller meetings/export promotion events facilitated',
                            'cumulative' => $cumBsm,
                        ], array_combine($clusterCodes, array_column($rawStats, 'bsm_count'))),
                        array_merge([
                            'indicator' => 'Number of exporters trained',
                            'cumulative' => $cumTrained,
                        ], array_combine($clusterCodes, array_column($rawStats, 'exporters_trained'))),
                    ]
                ],
                'newsletters' => [
                    'title' => 'Newsletters',
                    'items' => [
                        array_merge([
                            'indicator' => 'Number of newsletters/advisories/trade reports issued',
                            'cumulative' => $cumPublications,
                        ], array_combine($clusterCodes, array_column($rawStats, 'publications_count'))),
                    ]
                ],
                'queries' => [
                    'title' => 'Queries Handled',
                    'items' => [
                        array_merge([
                            'indicator' => 'Number of HSN/compliance/documentation queries resolved',
                            'cumulative' => $cumQueriesResolved,
                        ], array_combine($clusterCodes, array_column($rawStats, 'queries_resolved'))),
                        array_merge([
                            'indicator' => 'Total exporter queries received',
                            'cumulative' => $cumQueriesReceived,
                        ], array_combine($clusterCodes, array_column($rawStats, 'queries_received'))),
                        array_merge([
                            'indicator' => 'Percentage (%) of queries resolved within the prescribed timeline',
                            'is_percentage' => true,
                            'cumulative' => $cumSlaPercent,
                        ], array_combine($clusterCodes, array_column($rawStats, 'sla_percent'))),
                    ]
                ],
                'focus_areas' => [
                    'title' => 'Key focus areas',
                    'items' => [
                        array_merge([
                            'indicator' => 'Number of textile clusters covered',
                            'cumulative' => 6,
                        ], array_combine($clusterCodes, array_fill(0, count($clusterCodes), 1))),
                        array_merge([
                            'indicator' => 'Percentage (%) coverage within targeted textile clusters (Unique exporters supported divided by the total number of registered MSMEs/Exporters in the respective clusters)',
                            'is_percentage' => true,
                            'cumulative' => $cumCoveragePercent,
                        ], array_combine($clusterCodes, array_column($rawStats, 'coverage_percent'))),
                    ]
                ]
            ]
        ];

        // 4. Overall Summary Highlight Badges
        $summary = [
            'total_manpower' => $cumManpower,
            'total_exporters_supported' => $cumAdvised,
            'total_exporters_trained' => $cumTrained,
            'total_queries_resolved' => $cumQueriesResolved,
            'total_queries_received' => $cumQueriesReceived,
            'avg_resolution_sla' => $cumSlaPercent,
            'total_training_programmes' => $cumTrainings,
            'total_publications' => $cumPublications,
            'clusters_covered' => 6,
        ];

        // 5. Radar Dimensions & Normalized Scores (0 to 100)
        $radarDimensions = [
            '1. ManPower',
            '2. Exporter Base',
            '3. Training Programmes',
            '4. Newsletters',
            '5. Queries Handled',
            '6. Key focus areas'
        ];

        // Find maximums for fair relative normalization
        $maxMp = max(3, max(array_column($rawStats, 'manpower')));
        $maxExp = max(138, max(array_column($rawStats, 'exporters_advised')));
        $maxTr = max(310, max(array_column($rawStats, 'exporters_trained')));
        $maxPub = max(31, max(array_column($rawStats, 'publications_count')));
        $maxQr = max(120, max(array_column($rawStats, 'queries_received')));
        $maxCov = max(28.5, max(array_column($rawStats, 'coverage_percent')));

        $clusterColors = [
            'ichalkaranji' => ['bg' => 'rgba(108, 21, 63, 0.22)',  'border' => '#6c1340'],
            'jaipur'       => ['bg' => 'rgba(179, 142, 93, 0.22)', 'border' => '#b38e5d'],
            'karur'        => ['bg' => 'rgba(30, 126, 68, 0.22)',  'border' => '#1e7e44'],
            'ludhiana'     => ['bg' => 'rgba(20, 100, 160, 0.22)', 'border' => '#1464a0'],
            'surat'        => ['bg' => 'rgba(124, 58, 237, 0.22)', 'border' => '#7c3aed'],
            'varanasi'     => ['bg' => 'rgba(217, 119, 6, 0.22)',  'border' => '#d97706'],
        ];

        $radarScores = [];
        foreach ($clusterCodes as $code) {
            $st = $rawStats[$code];
            $scoreMp = round(($st['manpower'] / $maxMp) * 100);
            $scoreExp = round(($st['exporters_advised'] / $maxExp) * 100);
            $scoreTr = round(($st['exporters_trained'] / $maxTr) * 100);
            $scorePub = round(($st['publications_count'] / $maxPub) * 100);
            $scoreQr = round(($st['queries_received'] / $maxQr) * 100);
            $scoreCov = round(($st['coverage_percent'] / $maxCov) * 100);

            $radarScores[$code] = [
                'name' => $clusters[$code],
                'color' => $clusterColors[$code]['bg'],
                'border' => $clusterColors[$code]['border'],
                'scores' => [$scoreMp, $scoreExp, $scoreTr, $scorePub, $scoreQr, $scoreCov],
            ];
        }

        $masterMetrics = [
            'mp_1' => $rawStats['ichalkaranji']['manpower'],
            'mp_2' => $rawStats['jaipur']['manpower'],
            'mp_3' => $rawStats['karur']['manpower'],
            'mp_4' => $rawStats['ludhiana']['manpower'],
            'mp_5' => $rawStats['surat']['manpower'],
            'mp_6' => $rawStats['varanasi']['manpower'],
            'mp_cum' => $cumManpower,

            'exp_unique_1' => $rawStats['ichalkaranji']['exporters_advised'],
            'exp_unique_2' => $rawStats['jaipur']['exporters_advised'],
            'exp_unique_3' => $rawStats['karur']['exporters_advised'],
            'exp_unique_4' => $rawStats['ludhiana']['exporters_advised'],
            'exp_unique_5' => $rawStats['surat']['exporters_advised'],
            'exp_unique_6' => $rawStats['varanasi']['exporters_advised'],
            'exp_unique_cum' => $cumAdvised,

            'exp_a_1' => $rawStats['ichalkaranji']['exp_no_iec'],
            'exp_a_2' => $rawStats['jaipur']['exp_no_iec'],
            'exp_a_3' => $rawStats['karur']['exp_no_iec'],
            'exp_a_4' => $rawStats['ludhiana']['exp_no_iec'],
            'exp_a_5' => $rawStats['surat']['exp_no_iec'],
            'exp_a_6' => $rawStats['varanasi']['exp_no_iec'],
            'exp_a_cum' => $cumNoIec,

            'exp_b_1' => $rawStats['ichalkaranji']['exp_iec_no_export'],
            'exp_b_2' => $rawStats['jaipur']['exp_iec_no_export'],
            'exp_b_3' => $rawStats['karur']['exp_iec_no_export'],
            'exp_b_4' => $rawStats['ludhiana']['exp_iec_no_export'],
            'exp_b_5' => $rawStats['surat']['exp_iec_no_export'],
            'exp_b_6' => $rawStats['varanasi']['exp_iec_no_export'],
            'exp_b_cum' => $cumIecNoExport,

            'exp_c_1' => $rawStats['ichalkaranji']['exp_inactive_iec'],
            'exp_c_2' => $rawStats['jaipur']['exp_inactive_iec'],
            'exp_c_3' => $rawStats['karur']['exp_inactive_iec'],
            'exp_c_4' => $rawStats['ludhiana']['exp_inactive_iec'],
            'exp_c_5' => $rawStats['surat']['exp_inactive_iec'],
            'exp_c_6' => $rawStats['varanasi']['exp_inactive_iec'],
            'exp_c_cum' => $cumInactiveIec,

            'exp_d_1' => $rawStats['ichalkaranji']['exp_new_iec'],
            'exp_d_2' => $rawStats['jaipur']['exp_new_iec'],
            'exp_d_3' => $rawStats['karur']['exp_new_iec'],
            'exp_d_4' => $rawStats['ludhiana']['exp_new_iec'],
            'exp_d_5' => $rawStats['surat']['exp_new_iec'],
            'exp_d_6' => $rawStats['varanasi']['exp_new_iec'],
            'exp_d_cum' => $cumNewIec,

            'tr_prog_1' => $rawStats['ichalkaranji']['trainings_count'],
            'tr_prog_2' => $rawStats['jaipur']['trainings_count'],
            'tr_prog_3' => $rawStats['karur']['trainings_count'],
            'tr_prog_4' => $rawStats['ludhiana']['trainings_count'],
            'tr_prog_5' => $rawStats['surat']['trainings_count'],
            'tr_prog_6' => $rawStats['varanasi']['trainings_count'],
            'tr_prog_cum' => $cumTrainings,

            'tr_bsm_1' => $rawStats['ichalkaranji']['bsm_count'],
            'tr_bsm_2' => $rawStats['jaipur']['bsm_count'],
            'tr_bsm_3' => $rawStats['karur']['bsm_count'],
            'tr_bsm_4' => $rawStats['ludhiana']['bsm_count'],
            'tr_bsm_5' => $rawStats['surat']['bsm_count'],
            'tr_bsm_6' => $rawStats['varanasi']['bsm_count'],
            'tr_bsm_cum' => $cumBsm,

            'tr_trained_1' => $rawStats['ichalkaranji']['exporters_trained'],
            'tr_trained_2' => $rawStats['jaipur']['exporters_trained'],
            'tr_trained_3' => $rawStats['karur']['exporters_trained'],
            'tr_trained_4' => $rawStats['ludhiana']['exporters_trained'],
            'tr_trained_5' => $rawStats['surat']['exporters_trained'],
            'tr_trained_6' => $rawStats['varanasi']['exporters_trained'],
            'tr_trained_cum' => number_format($cumTrained),

            'news_1' => $rawStats['ichalkaranji']['publications_count'],
            'news_2' => $rawStats['jaipur']['publications_count'],
            'news_3' => $rawStats['karur']['publications_count'],
            'news_4' => $rawStats['ludhiana']['publications_count'],
            'news_5' => $rawStats['surat']['publications_count'],
            'news_6' => $rawStats['varanasi']['publications_count'],
            'news_cum' => $cumPublications,

            'qr_res_1' => $rawStats['ichalkaranji']['queries_resolved'],
            'qr_res_2' => $rawStats['jaipur']['queries_resolved'],
            'qr_res_3' => $rawStats['karur']['queries_resolved'],
            'qr_res_4' => $rawStats['ludhiana']['queries_resolved'],
            'qr_res_5' => $rawStats['surat']['queries_resolved'],
            'qr_res_6' => $rawStats['varanasi']['queries_resolved'],
            'qr_res_cum' => $cumQueriesResolved,

            'qr_tot_1' => $rawStats['ichalkaranji']['queries_received'],
            'qr_tot_2' => $rawStats['jaipur']['queries_received'],
            'qr_tot_3' => $rawStats['karur']['queries_received'],
            'qr_tot_4' => $rawStats['ludhiana']['queries_received'],
            'qr_tot_5' => $rawStats['surat']['queries_received'],
            'qr_tot_6' => $rawStats['varanasi']['queries_received'],
            'qr_tot_cum' => $cumQueriesReceived,

            'qr_pct_1' => number_format($rawStats['ichalkaranji']['sla_percent'], 2) . '%',
            'qr_pct_2' => number_format($rawStats['jaipur']['sla_percent'], 2) . '%',
            'qr_pct_3' => number_format($rawStats['karur']['sla_percent'], 2) . '%',
            'qr_pct_4' => number_format($rawStats['ludhiana']['sla_percent'], 2) . '%',
            'qr_pct_5' => number_format($rawStats['surat']['sla_percent'], 2) . '%',
            'qr_pct_6' => number_format($rawStats['varanasi']['sla_percent'], 2) . '%',
            'qr_pct_cum' => number_format($cumSlaPercent, 2) . '%',

            'fa_cov_1' => 1, 'fa_cov_2' => 1, 'fa_cov_3' => 1, 'fa_cov_4' => 1, 'fa_cov_5' => 1, 'fa_cov_6' => 1, 'fa_cov_cum' => 6,

            'fa_pct_1' => number_format($rawStats['ichalkaranji']['coverage_percent'], 1) . '%',
            'fa_pct_2' => number_format($rawStats['jaipur']['coverage_percent'], 1) . '%',
            'fa_pct_3' => number_format($rawStats['karur']['coverage_percent'], 1) . '%',
            'fa_pct_4' => number_format($rawStats['ludhiana']['coverage_percent'], 1) . '%',
            'fa_pct_5' => number_format($rawStats['surat']['coverage_percent'], 1) . '%',
            'fa_pct_6' => number_format($rawStats['varanasi']['coverage_percent'], 1) . '%',
            'fa_pct_cum' => number_format($cumCoveragePercent, 1) . '%',
        ];

        $barData = [
            'advised' => [
                $rawStats['ichalkaranji']['exporters_advised'],
                $rawStats['jaipur']['exporters_advised'],
                $rawStats['karur']['exporters_advised'],
                $rawStats['ludhiana']['exporters_advised'],
                $rawStats['surat']['exporters_advised'],
                $rawStats['varanasi']['exporters_advised'],
            ],
            'trained' => [
                $rawStats['ichalkaranji']['exporters_trained'],
                $rawStats['jaipur']['exporters_trained'],
                $rawStats['karur']['exporters_trained'],
                $rawStats['ludhiana']['exporters_trained'],
                $rawStats['surat']['exporters_trained'],
                $rawStats['varanasi']['exporters_trained'],
            ],
            'queries' => [
                $rawStats['ichalkaranji']['queries_received'],
                $rawStats['jaipur']['queries_received'],
                $rawStats['karur']['queries_received'],
                $rawStats['ludhiana']['queries_received'],
                $rawStats['surat']['queries_received'],
                $rawStats['varanasi']['queries_received'],
            ],
        ];

        return [
            'clusters' => $clusters,
            'clusterStates' => $clusterStates,
            'kpiData' => $kpiData,
            'rawStats' => $rawStats,
            'summary' => $summary,
            'radarDimensions' => $radarDimensions,
            'radarScores' => $radarScores,
            'masterMetrics' => $masterMetrics,
            'barData' => $barData,
        ];
    }
}
