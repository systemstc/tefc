<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KpiAggregationService;

class KpiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'yearly');
        $periodValue = $request->query('quarter', $request->query('month'));

        $dashboardData = KpiAggregationService::getFullDashboardData($period, $periodValue);

        $clusters = $dashboardData['clusters'];
        $clusterStates = $dashboardData['clusterStates'];
        $kpiData = $dashboardData['kpiData'];
        $summary = $dashboardData['summary'];
        $rawStats = $dashboardData['rawStats'];
        $radarDimensions = $dashboardData['radarDimensions'];
        $radarScores = $dashboardData['radarScores'];
        $masterMetrics = $dashboardData['masterMetrics'];
        $barData = $dashboardData['barData'];

        return view('kpi-dashboard', compact('clusters', 'clusterStates', 'kpiData', 'summary', 'rawStats', 'radarDimensions', 'radarScores', 'masterMetrics', 'barData'));
    }
}
