@extends('layouts.app')

@section('title', 'Cluster KPI & Performance Dashboard — TEFC')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* True Full Width Fluid Layout */
.kpi-fluid-container {
    width: 100%;
    max-width: 100%;
    padding: 0 2rem;
}
@media (max-width: 768px) {
    .kpi-fluid-container {
        padding: 0 1rem;
    }
}

/* STRICT TAB DISPLAY RULES: Guarantee only the active tab is visible */
.kpi-tab-pane {
    display: none !important;
}
.kpi-tab-pane.active {
    display: block !important;
}
.d-none {
    display: none !important;
}

/* Hero Section */
.kpi-hero {
    background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
                url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
    color: #fff;
    padding: 3.5rem 0 3.2rem 0;
    position: relative;
    overflow: hidden;
    border-bottom: 4px solid #b38e5d;
}
.kpi-hero-orb {
    position: absolute;
    right: -5%;
    top: -20%;
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, rgba(179, 142, 93, 0.25) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.kpi-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.12);
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.88rem;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255,255,255,0.25);
    font-weight: 600;
}
.kpi-hero-title {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 0 0 10px 0;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.25);
    letter-spacing: -0.5px;
}
.kpi-hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
    margin: 0;
    max-width: 950px;
    line-height: 1.6;
}

/* Glass & Action Buttons */
.kpi-btn-glass {
    background: rgba(255,255,255,0.18);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.35);
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}
.kpi-btn-glass:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
    color: #fff;
}
.kpi-btn-gold {
    background: #b38e5d;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 0.92rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(179, 142, 93, 0.4);
}
.kpi-btn-gold:hover {
    background: #9c7847;
    transform: translateY(-2px);
    color: #fff;
}

/* 6 Core KPI Top Cards */
.kpi-metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}
.kpi-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.4rem 1.6rem;
    border: 1px solid #ebdcb9;
    box-shadow: 0 4px 18px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(108, 21, 63, 0.12);
    border-color: #6c1340;
}
.kpi-card-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.kpi-card-label {
    display: block;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #555;
    font-weight: 800;
}
.kpi-card-value {
    margin: 4px 0 2px 0;
    font-size: 2rem;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
}
.kpi-card-sub {
    font-size: 0.82rem;
}

/* Main Tab Navigation Bar */
.kpi-nav-tabs-wrapper {
    background: #ffffff;
    padding: 8px 12px;
    border-radius: 16px;
    border: 1px solid #ebdcb9;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}
.kpi-main-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}
.kpi-main-nav .nav-link {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 10px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    cursor: pointer;
}
.kpi-main-nav .nav-link:hover {
    background: #f1f5f9;
    color: #6c1340;
    border-color: #cbd5e1;
}
.kpi-main-nav .nav-link.active {
    background: #6c1340 !important;
    color: #ffffff !important;
    border-color: #6c1340 !important;
    box-shadow: 0 4px 14px rgba(108, 21, 63, 0.3);
}

/* Individual Tab Header Banner */
.kpi-tab-header-banner {
    background: linear-gradient(135deg, #6c1340 0%, #4a0e2b 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    box-shadow: 0 8px 25px rgba(108, 21, 63, 0.15);
}

/* Filter Box */
.kpi-filter-box {
    background: #fff;
    border-radius: 14px;
    padding: 1.1rem 1.6rem;
    border: 1px solid #ebdcb9;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1.2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
}
.kpi-tab-group {
    display: inline-flex;
    background: #f1f5f9;
    border-radius: 10px;
    padding: 4px;
    border: 1px solid #e2e8f0;
}
.kpi-tab {
    background: none;
    border: none;
    padding: 7px 18px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #475569;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.kpi-tab.active {
    background: #6c1340;
    color: #fff;
    box-shadow: 0 2px 8px rgba(108, 21, 63, 0.25);
}
.kpi-select-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #334155;
}
.kpi-select-wrap select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 7px 14px;
    font-size: 0.9rem;
    color: #1e293b;
    outline: none;
    background: #fff;
    cursor: pointer;
    font-weight: 600;
}
.kpi-select-wrap select:focus {
    border-color: #6c1340;
}
.badge-period {
    font-size: 0.88rem;
    font-weight: 700;
    color: #6c1340;
    background: #fdf2f7;
    padding: 7px 16px;
    border-radius: 30px;
    border: 1px solid #f9d8e6;
}
.btn-reset {
    background: none;
    border: 1px solid #cbd5e1;
    color: #64748b;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.86rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-reset:hover {
    background: #f8fafc;
    color: #1e293b;
}

/* Chart Cards */
.kpi-chart-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.8rem;
    border: 1px solid #ebdcb9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.kpi-chart-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 900;
    color: #0f172a;
}
.kpi-chart-subtitle {
    margin: 4px 0 0 0;
    font-size: 0.9rem;
    color: #64748b;
}

/* Cluster Toggles */
.cluster-quick-toggles {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.btn-cluster-toggle {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-cluster-toggle.active {
    background: #6c1340;
    color: #fff;
    border-color: #6c1340;
    box-shadow: 0 2px 6px rgba(108, 21, 63, 0.2);
}

.spider-legend-help {
    font-size: 0.85rem;
    color: #475569;
    background: #fbf9f6;
    padding: 12px 18px;
    border-radius: 10px;
    border: 1px solid #ebdcb9;
}

/* Tables */
.kpi-table-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #ebdcb9;
    box-shadow: 0 4px 25px rgba(0,0,0,0.05);
    overflow: hidden;
}
.kpi-table-header {
    padding: 1.4rem 1.8rem;
    background: #fff;
    border-bottom: 1px solid #ebdcb9;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}
.kpi-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}
.kpi-table th {
    background: #6c1340;
    color: #fff;
    padding: 14px 12px;
    font-weight: 800;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
    border: 1px solid #852250;
    vertical-align: middle;
}
.kpi-table th.col-cum {
    background: #4a0e2b;
    color: #f8b803;
}
.kpi-table td {
    padding: 12px 14px;
    border: 1px solid #e8e1d5;
    color: #1e293b;
    vertical-align: middle;
}
.kpi-table tbody tr:nth-child(even) {
    background-color: #faf8f5;
}
.kpi-table tbody tr:hover {
    background-color: #f3ecdf;
}
.kpi-table td.col-cum {
    background-color: #fcf6eb;
    font-weight: 800;
    color: #6c1340;
    border-left: 2px solid #b38e5d;
}
.text-top { vertical-align: top !important; }
.font-bold { font-weight: 800; }
.font-semibold { font-weight: 700; }
.font-medium { font-weight: 600; }
.text-primary-burgundy { color: #6c1340; font-weight: 800; }
.badge-cell {
    background: #fdf2f7 !important;
    color: #6c1340 !important;
}
.badge-success-cell {
    background: #eefbf3 !important;
    color: #1e7e44 !important;
}
.col-highlight {
    background-color: #fff4d9 !important;
    font-weight: 700;
}
</style>
@endsection

@section('content')
<!-- HERO BANNER (Full Width, No Print Button, With Fullscreen Mode Toggle) -->
<!-- <div class="kpi-hero">
    <div class="kpi-hero-orb"></div>
    <div class="kpi-fluid-container" style="position: relative; z-index: 2;">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="kpi-badge-pill mb-2">
                    <i class="fas fa-chart-pie" style="color: #f8b803;"></i>
                    <span>National Textile Export Monitoring System &bull; TEFC Hub</span>
                </div>
                <h1 class="kpi-hero-title">
                    {{ 'TEFC Cluster Performance & KPI Dashboard' }}
                </h1>
                <p class="kpi-hero-subtitle">
                    Operational impact metrics, exporter facilitation benchmarks, and capacity indicators across India's 6 designated Textile Export Facilitation Centres.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button onclick="toggleFullScreen()" id="btn-fullscreen" class="kpi-btn-glass" title="Toggle Fullscreen Presentation View">
                    <i class="fas fa-expand me-1" id="fs-icon"></i> <span id="fs-text">Full Screen</span>
                </button>
                <button onclick="exportKpiCsv()" class="kpi-btn-gold" title="Download complete KPI data spreadsheet">
                    <i class="fas fa-file-csv me-1"></i> Export CSV
                </button>
            </div>
        </div>
    </div>
</div> -->

<!-- MAIN FULL-SCREEN FLUID WRAPPER -->
<div class="kpi-fluid-container mt-n4 mb-5">

    <!-- TOP EXECUTIVE KPI SUMMARY METRIC CARDS -->
    <div class="kpi-metric-grid">
        <div class="kpi-card" onclick="switchKpiTab('tab-manpower')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(108, 21, 63, 0.12); color: #6c1340;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <span class="kpi-card-label">1. ManPower</span>
                <h3 class="kpi-card-value" id="card-manpower">{{ $summary['total_manpower'] }}</h3>
                <span class="kpi-card-sub text-muted">Deployed Personnel</span>
            </div>
        </div>

        <div class="kpi-card" onclick="switchKpiTab('tab-exporter-base')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(179, 142, 93, 0.18); color: #8e6c38;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <span class="kpi-card-label">2. Exporter Base</span>
                <h3 class="kpi-card-value" id="card-exporters-advised">{{ number_format($summary['total_exporters_supported']) }}</h3>
                <span class="kpi-card-sub text-muted">Exporters Advised</span>
            </div>
        </div>

        <div class="kpi-card" onclick="switchKpiTab('tab-training')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(30, 126, 68, 0.12); color: #1e7e44;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <span class="kpi-card-label">3. Training Programmes</span>
                <h3 class="kpi-card-value" id="card-exporters-trained">{{ number_format($summary['total_exporters_trained']) }}</h3>
                <span class="kpi-card-sub" style="color: #1e7e44; font-weight: 700;">{{ $summary['total_training_programmes'] }} Programmes</span>
            </div>
        </div>

        <div class="kpi-card" onclick="switchKpiTab('tab-newsletters')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(217, 119, 6, 0.12); color: #d97706;">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <span class="kpi-card-label">4. Newsletters</span>
                <h3 class="kpi-card-value" id="card-publications">{{ number_format($summary['total_publications']) }}</h3>
                <span class="kpi-card-sub text-muted">Trade Advisories</span>
            </div>
        </div>

        <div class="kpi-card" onclick="switchKpiTab('tab-queries')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(20, 100, 160, 0.12); color: #1464a0;">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <span class="kpi-card-label">5. Queries Handled</span>
                <h3 class="kpi-card-value" id="card-queries-resolved">{{ number_format($summary['total_queries_resolved']) }}</h3>
                <span class="kpi-card-sub" style="color: #1e7e44; font-weight: 700;">{{ $summary['avg_resolution_sla'] }}% SLA</span>
            </div>
        </div>

        <div class="kpi-card" onclick="switchKpiTab('tab-focus-areas')" style="cursor: pointer;">
            <div class="kpi-card-icon" style="background: rgba(124, 58, 237, 0.12); color: #7c3aed;">
                <i class="fas fa-bullseye"></i>
            </div>
            <div>
                <span class="kpi-card-label">6. Key Focus Areas</span>
                <h3 class="kpi-card-value" id="card-clusters">6</h3>
                <span class="kpi-card-sub" style="color: #7c3aed; font-weight: 700;">{{ $masterMetrics['fa_pct_cum'] }} MSME Coverage</span>
            </div>
        </div>
    </div>

    <!-- MAIN KPI TAB NAVIGATION BAR -->
    <div class="kpi-nav-tabs-wrapper mb-4">
        <ul class="nav nav-pills kpi-main-nav" id="kpiMainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-overview-btn" onclick="switchKpiTab('tab-overview')">
                    <i class="fas fa-th-large me-1"></i> Executive Overview &amp; Spider Chart
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-manpower-btn" onclick="switchKpiTab('tab-manpower')">
                    <i class="fas fa-user-tie me-1"></i> KPI 1: ManPower
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-exporter-base-btn" onclick="switchKpiTab('tab-exporter-base')">
                    <i class="fas fa-users me-1"></i> KPI 2: Exporter Base
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-training-btn" onclick="switchKpiTab('tab-training')">
                    <i class="fas fa-graduation-cap me-1"></i> KPI 3: Training Programmes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-newsletters-btn" onclick="switchKpiTab('tab-newsletters')">
                    <i class="fas fa-newspaper me-1"></i> KPI 4: Newsletters
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-queries-btn" onclick="switchKpiTab('tab-queries')">
                    <i class="fas fa-headset me-1"></i> KPI 5: Queries Handled
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-focus-areas-btn" onclick="switchKpiTab('tab-focus-areas')">
                    <i class="fas fa-bullseye me-1"></i> KPI 6: Key Focus Areas
                </button>
            </li>
        </ul>
    </div>

    <!-- FILTER TOOLBAR (Cycle: Full Year / Quarterly / Monthly / Cluster Filter) -->
    <div class="kpi-filter-box mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="kpi-tab-group">
                <button class="kpi-tab active" data-period="yearly" onclick="setPeriodFilter('yearly')">
                    <i class="fas fa-calendar-alt me-1"></i> Full Year
                </button>
                <button class="kpi-tab" data-period="quarterly" onclick="setPeriodFilter('quarterly')">
                    <i class="fas fa-th-large me-1"></i> Quarterly
                </button>
                <button class="kpi-tab" data-period="monthly" onclick="setPeriodFilter('monthly')">
                    <i class="fas fa-calendar-day me-1"></i> Monthly
                </button>
            </div>

            <div class="kpi-select-wrap" id="quarter-filter-wrap" style="display: none;">
                <label for="quarter-select"><i class="fas fa-filter text-muted"></i> Quarter:</label>
                <select id="quarter-select" onchange="applyFilters()">
                    <option value="q1">Q1 (Apr – Jun)</option>
                    <option value="q2">Q2 (Jul – Sep)</option>
                    <option value="q3">Q3 (Oct – Dec)</option>
                    <option value="q4">Q4 (Jan – Mar)</option>
                </select>
            </div>

            <div class="kpi-select-wrap" id="month-filter-wrap" style="display: none;">
                <label for="month-select"><i class="fas fa-filter text-muted"></i> Month:</label>
                <select id="month-select" onchange="applyFilters()">
                    <option value="apr">April</option>
                    <option value="may">May</option>
                    <option value="jun">June</option>
                    <option value="jul">July</option>
                    <option value="aug">August</option>
                    <option value="sep">September</option>
                    <option value="oct">October</option>
                    <option value="nov">November</option>
                    <option value="dec">December</option>
                    <option value="jan">January</option>
                    <option value="feb">February</option>
                    <option value="mar">March</option>
                </select>
            </div>

            <div class="kpi-select-wrap">
                <label for="cluster-select"><i class="fas fa-map-marker-alt text-muted"></i> Centre Focus:</label>
                <select id="cluster-select" onchange="applyFilters()">
                    <option value="all">All 6 Centres</option>
                    <option value="ichalkaranji">Ichalkaranji (Maharashtra)</option>
                    <option value="jaipur">Jaipur (Rajasthan)</option>
                    <option value="karur">Karur (Tamil Nadu)</option>
                    <option value="ludhiana">Ludhiana (Punjab)</option>
                    <option value="surat">Surat (Gujarat)</option>
                    <option value="varanasi">Varanasi (Uttar Pradesh)</option>
                </select>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="badge-period" id="active-period-badge">
                <i class="fas fa-clock me-1"></i> Active View: Cumulative (Full FY 2025-26)
            </span>
            <button onclick="resetFilters()" class="btn-reset" title="Reset all filters to default">
                <i class="fas fa-undo me-1"></i> Reset
            </button>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 1: OVERVIEW & BIG SPIDER (RADAR) CHART -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane active" id="pane-tab-overview" style="display: block;">
        <!-- Prominent Big Spider Chart Container -->
        <div class="kpi-chart-card mb-4" style="background: #ffffff; border: 1px solid #ebdcb9;">
            <div class="kpi-chart-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="kpi-chart-title" style="font-size: 1.5rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-spider" style="color: #6c1340; font-size: 1.6rem;"></i>
                        <span>6-KPI Cluster Performance Spider Chart (Radar)</span>
                    </h2>
                    <p class="kpi-chart-subtitle" style="font-size: 0.95rem; margin-top: 4px;">
                        Comparative capability footprint across the 6 Core KPIs: <strong>1. ManPower &bull; 2. Exporter Base &bull; 3. Training Programmes &bull; 4. Newsletters &bull; 5. Queries Handled &bull; 6. Key Focus Areas</strong>
                    </p>
                </div>
                <!-- Cluster isolation chips -->
                <div class="cluster-quick-toggles">
                    <span style="font-size: 0.8rem; font-weight: 700; color: #666; margin-right: 4px; align-self: center;">Highlight:</span>
                    <button class="btn-cluster-toggle active" data-cluster="all" onclick="toggleRadarCluster('all')">All 6 Centres</button>
                    <button class="btn-cluster-toggle" data-cluster="ichalkaranji" onclick="toggleRadarCluster('ichalkaranji')">Ichalkaranji</button>
                    <button class="btn-cluster-toggle" data-cluster="jaipur" onclick="toggleRadarCluster('jaipur')">Jaipur</button>
                    <button class="btn-cluster-toggle" data-cluster="karur" onclick="toggleRadarCluster('karur')">Karur</button>
                    <button class="btn-cluster-toggle" data-cluster="ludhiana" onclick="toggleRadarCluster('ludhiana')">Ludhiana</button>
                    <button class="btn-cluster-toggle" data-cluster="surat" onclick="toggleRadarCluster('surat')">Surat</button>
                    <button class="btn-cluster-toggle" data-cluster="varanasi" onclick="toggleRadarCluster('varanasi')">Varanasi</button>
                </div>
            </div>

            <!-- Big Radar Canvas with generous 620px height for ultra-clear visibility -->
            <div class="chart-wrapper" style="height: 620px; position: relative; padding: 10px 0;">
                <canvas id="spiderChart"></canvas>
            </div>

            <div class="spider-legend-help d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                <div>
                    <i class="fas fa-info-circle me-1 text-primary"></i> <strong>Interactive Tip:</strong> Click any centre label in the legend to show/hide its contour, or click the chips above to isolate an individual centre web.
                </div>
                <div style="font-size: 0.82rem; color: #555;">
                    <span class="badge bg-light text-dark border me-1">Axis Range: 0 - 100%</span>
                    <span class="badge bg-light text-dark border">Data Points: Exact Master Reference Data</span>
                </div>
            </div>
        </div>

        <!-- Secondary Comparative Volumes Bar Chart -->
        <div class="kpi-chart-card mb-4">
            <div class="kpi-chart-header">
                <h3 class="kpi-chart-title">
                    <i class="fas fa-chart-bar" style="color: #b38e5d;"></i> National Cluster Outreach &amp; Volume Benchmarks
                </h3>
                <p class="kpi-chart-subtitle">
                    Side-by-side comparison of Exporters Advised, Exporters Trained, and Queries Handled across all 6 Centres.
                </p>
            </div>
            <div class="chart-wrapper" style="height: 380px; position: relative;">
                <canvas id="volumeChart"></canvas>
            </div>
        </div>

        <!-- Master KPI Table (Complete reference matrix) -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <div>
                    <h3 style="margin: 0; font-size: 1.35rem; font-weight: 800; color: #6c1340; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-table"></i> Master TEFC Key Performance Indicators (KPI) Matrix
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #666;">
                        Complete official baseline data across all 6 centres matching the national monitoring register
                    </p>
                </div>
                <div style="font-size: 0.88rem; color: #555; background: #fff8eb; padding: 8px 16px; border-radius: 8px; border: 1px solid #ebdcb9;">
                    <i class="fas fa-building" style="color: #b38e5d;"></i> Covered Clusters: <strong>Ichalkaranji, Jaipur, Karur, Ludhiana, Surat, Varanasi</strong>
                </div>
            </div>

            <div class="table-responsive">
                <table class="kpi-table" id="kpi-main-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">S.No</th>
                            <th style="width: 160px;">KPI</th>
                            <th style="min-width: 320px;">Indicator</th>
                            <th class="col-cluster text-center" data-cluster="ichalkaranji">Ichalkaranji<br><small style="font-weight: normal; opacity: 0.85;">Maharashtra</small></th>
                            <th class="col-cluster text-center" data-cluster="jaipur">Jaipur<br><small style="font-weight: normal; opacity: 0.85;">Rajasthan</small></th>
                            <th class="col-cluster text-center" data-cluster="karur">Karur<br><small style="font-weight: normal; opacity: 0.85;">Tamil Nadu</small></th>
                            <th class="col-cluster text-center" data-cluster="ludhiana">Ludhiana<br><small style="font-weight: normal; opacity: 0.85;">Punjab</small></th>
                            <th class="col-cluster text-center" data-cluster="surat">Surat<br><small style="font-weight: normal; opacity: 0.85;">Gujarat</small></th>
                            <th class="col-cluster text-center" data-cluster="varanasi">Varanasi<br><small style="font-weight: normal; opacity: 0.85;">Uttar Pradesh</small></th>
                            <th class="col-cum text-center">Cumulative<br><small style="font-weight: normal; color: #f8b803;">National Total</small></th>
                        </tr>
                    </thead>
                    <tbody id="kpi-table-body">
                        <!-- Section 1: ManPower -->
                        <tr class="section-row">
                            <td class="text-center font-bold">1</td>
                            <td class="font-bold">ManPower</td>
                            <td>Operational TEFC Advisory Personnel Deployed</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="mp_1">2</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="mp_2">3</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="mp_3">2</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="mp_4">2</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="mp_5">2</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="mp_6">2</td>
                            <td class="col-cum text-center font-bold" data-metric="mp_cum">13</td>
                        </tr>

                        <!-- Section 2: Exporter Base -->
                        <tr>
                            <td rowspan="5" class="text-center font-bold text-top">2</td>
                            <td rowspan="5" class="font-bold text-top">Exporter Base</td>
                            <td class="font-medium">Number of unique exporters receiving advisory support</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="ichalkaranji" data-metric="exp_unique_1">34</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="jaipur" data-metric="exp_unique_2">138</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="karur" data-metric="exp_unique_3">16</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="ludhiana" data-metric="exp_unique_4">79</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="surat" data-metric="exp_unique_5">19</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="varanasi" data-metric="exp_unique_6">39</td>
                            <td class="col-cum text-center font-bold badge-cell" data-metric="exp_unique_cum">325</td>
                        </tr>
                        <tr class="sub-row">
                            <td style="padding-left: 1.5rem;">a. Prospective Exporters (Without IEC)</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="exp_a_1">8</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="exp_a_2">2</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="exp_a_3">0</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="exp_a_4">8</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="exp_a_5">0</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="exp_a_6">7</td>
                            <td class="col-cum text-center font-bold" data-metric="exp_a_cum">25</td>
                        </tr>
                        <tr class="sub-row">
                            <td style="padding-left: 1.5rem;">b. IEC Holders with No Export History</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="exp_b_1">2</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="exp_b_2">3</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="exp_b_3">10</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="exp_b_4">1</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="exp_b_5">2</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="exp_b_6">4</td>
                            <td class="col-cum text-center font-bold" data-metric="exp_b_cum">22</td>
                        </tr>
                        <tr class="sub-row">
                            <td style="padding-left: 1.5rem;">c. Inactive IEC Holders (No Exports in the Last Two Years)</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="exp_c_1">7</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="exp_c_2">1</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="exp_c_3">3</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="exp_c_4">0</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="exp_c_5">0</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="exp_c_6">5</td>
                            <td class="col-cum text-center font-bold" data-metric="exp_c_cum">16</td>
                        </tr>
                        <tr class="sub-row">
                            <td style="padding-left: 1.5rem;">d. Number of New IEC Generated through TEFC</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="exp_d_1">2</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="exp_d_2">2</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="exp_d_3">0</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="exp_d_4">1</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="exp_d_5">0</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="exp_d_6">10</td>
                            <td class="col-cum text-center font-bold text-success" data-metric="exp_d_cum">15</td>
                        </tr>

                        <!-- Section 3: Training Programmes -->
                        <tr>
                            <td rowspan="3" class="text-center font-bold text-top">3</td>
                            <td rowspan="3" class="font-bold text-top">Training Programmes</td>
                            <td class="font-medium">Number of training programmes conducted</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="tr_prog_1">4</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="tr_prog_2">4</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="tr_prog_3">3</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="tr_prog_4">5</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="tr_prog_5">3</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="tr_prog_6">1</td>
                            <td class="col-cum text-center font-bold" data-metric="tr_prog_cum">20</td>
                        </tr>
                        <tr class="sub-row">
                            <td>Number of buyer-seller meetings/export promotion events facilitated</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="tr_bsm_1">0</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="tr_bsm_2">0</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="tr_bsm_3">0</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="tr_bsm_4">0</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="tr_bsm_5">0</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="tr_bsm_6">0</td>
                            <td class="col-cum text-center font-bold" data-metric="tr_bsm_cum">0</td>
                        </tr>
                        <tr class="sub-row">
                            <td class="font-medium">Number of exporters trained</td>
                            <td class="col-cluster text-center font-semibold text-primary-burgundy" data-cluster="ichalkaranji" data-metric="tr_trained_1">310</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="jaipur" data-metric="tr_trained_2">218</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="karur" data-metric="tr_trained_3">129</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="ludhiana" data-metric="tr_trained_4">161</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="surat" data-metric="tr_trained_5">124</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="varanasi" data-metric="tr_trained_6">60</td>
                            <td class="col-cum text-center font-bold badge-cell" data-metric="tr_trained_cum">1,002</td>
                        </tr>

                        <!-- Section 4: Newsletters -->
                        <tr>
                            <td class="text-center font-bold">4</td>
                            <td class="font-bold">Newsletters</td>
                            <td class="font-medium">Number of newsletters/advisories/trade reports issued</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="news_1">7</td>
                            <td class="col-cluster text-center font-semibold text-primary-burgundy" data-cluster="jaipur" data-metric="news_2">31</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="news_3">18</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="news_4">18</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="news_5">17</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="news_6">18</td>
                            <td class="col-cum text-center font-bold" data-metric="news_cum">109</td>
                        </tr>

                        <!-- Section 5: Queries Handled -->
                        <tr>
                            <td rowspan="3" class="text-center font-bold text-top">5</td>
                            <td rowspan="3" class="font-bold text-top">Queries Handled</td>
                            <td class="font-medium">Number of HSN/compliance/documentation queries resolved</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="qr_res_1">4</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="qr_res_2">6</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="karur" data-metric="qr_res_3">80</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="qr_res_4">32</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="qr_res_5">30</td>
                            <td class="col-cluster text-center font-semibold text-primary-burgundy" data-cluster="varanasi" data-metric="qr_res_6">93</td>
                            <td class="col-cum text-center font-bold" data-metric="qr_res_cum">245</td>
                        </tr>
                        <tr class="sub-row">
                            <td class="font-medium">Total exporter queries received</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="qr_tot_1">28</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="qr_tot_2">69</td>
                            <td class="col-cluster text-center font-semibold text-primary-burgundy" data-cluster="karur" data-metric="qr_tot_3">120</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="qr_tot_4">49</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="qr_tot_5">34</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="qr_tot_6">32</td>
                            <td class="col-cum text-center font-bold badge-cell" data-metric="qr_tot_cum">332</td>
                        </tr>
                        <tr class="sub-row" style="background: #f8fafc;">
                            <td class="font-bold">Percentage (%) of queries resolved within the prescribed timeline</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="ichalkaranji" data-metric="qr_pct_1">91.00%</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="jaipur" data-metric="qr_pct_2">95.83%</td>
                            <td class="col-cluster text-center font-semibold" data-cluster="karur" data-metric="qr_pct_3">95.80%</td>
                            <td class="col-cluster text-center font-bold text-success" data-cluster="ludhiana" data-metric="qr_pct_4">100.00%</td>
                            <td class="col-cluster text-center font-bold text-success" data-cluster="surat" data-metric="qr_pct_5">100.00%</td>
                            <td class="col-cluster text-center font-bold text-success" data-cluster="varanasi" data-metric="qr_pct_6">100.00%</td>
                            <td class="col-cum text-center font-bold badge-success-cell" data-metric="qr_pct_cum">97.11%</td>
                        </tr>

                        <!-- Section 6: Key focus areas -->
                        <tr>
                            <td rowspan="2" class="text-center font-bold text-top">6</td>
                            <td rowspan="2" class="font-bold text-top">Key focus areas</td>
                            <td class="font-medium">Number of textile clusters covered</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="fa_cov_1">1</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="fa_cov_2">1</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="fa_cov_3">1</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="fa_cov_4">1</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="fa_cov_5">1</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="fa_cov_6">1</td>
                            <td class="col-cum text-center font-bold" data-metric="fa_cov_cum">6</td>
                        </tr>
                        <tr class="sub-row">
                            <td style="font-size: 0.88rem; color: #555;">Percentage (%) coverage within targeted textile clusters (Unique exporters supported / total MSMEs)</td>
                            <td class="col-cluster text-center" data-cluster="ichalkaranji" data-metric="fa_pct_1">14.2%</td>
                            <td class="col-cluster text-center" data-cluster="jaipur" data-metric="fa_pct_2">28.5%</td>
                            <td class="col-cluster text-center" data-cluster="karur" data-metric="fa_pct_3">18.0%</td>
                            <td class="col-cluster text-center" data-cluster="ludhiana" data-metric="fa_pct_4">22.4%</td>
                            <td class="col-cluster text-center" data-cluster="surat" data-metric="fa_pct_5">16.5%</td>
                            <td class="col-cluster text-center" data-cluster="varanasi" data-metric="fa_pct_6">19.8%</td>
                            <td class="col-cum text-center font-bold" data-metric="fa_pct_cum">19.9%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 2: KPI 1 - MANPOWER -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-manpower" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 1 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">ManPower &amp; TEFC Advisory Personnel Deployed</h2>
                    <p class="text-white-50 mb-0">Ground personnel allocation ensuring direct on-site guidance across all 6 textile export facilitation centres.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-warning fw-bold d-block mb-0">13</span>
                    <span class="text-white-50 small">Total Personnel Nationally</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-chart-bar text-primary me-2"></i> Operational Advisory Personnel Deployed per Centre
                    </h4>
                    <div class="chart-wrapper" style="height: 380px; position: relative;">
                        <canvas id="chartKpi1"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="kpi-chart-title mb-3"><i class="fas fa-shield-alt text-success me-2"></i> Operational Staffing Status</h4>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Jaipur Centre</strong><br><small class="text-muted">Rajasthan &bull; High volume hub</small></div>
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">3 Staff</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Ichalkaranji Centre</strong><br><small class="text-muted">Maharashtra Cluster</small></div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">2 Staff</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Karur Centre</strong><br><small class="text-muted">Tamil Nadu Home Textiles</small></div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">2 Staff</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Ludhiana Centre</strong><br><small class="text-muted">Punjab Knitwear Cluster</small></div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">2 Staff</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Surat Centre</strong><br><small class="text-muted">Gujarat MMF &amp; Weaving</small></div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">2 Staff</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div><strong>Varanasi Centre</strong><br><small class="text-muted">Uttar Pradesh Silk &amp; Handloom</small></div>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">2 Staff</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success mt-3 mb-0 py-2 text-center">
                        <i class="fas fa-check-circle me-1"></i> 100% Operational Readiness Maintained
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 1 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise ManPower Allocation &amp; Advisory Capacity</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th>State Jurisdiction</th>
                            <th class="text-center">Operational Personnel</th>
                            <th class="text-center">National Manpower Share</th>
                            <th class="text-center">Staffing Benchmark Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td>{{ $clusterStates[$code] }}</td>
                            <td class="text-center fw-bold fs-6">{{ $st['manpower'] }}</td>
                            <td class="text-center">{{ $summary['total_manpower'] > 0 ? number_format(($st['manpower'] / $summary['total_manpower']) * 100, 1) : 0 }}%</td>
                            <td class="text-center">
                                @if($st['manpower'] >= 3)
                                    <span class="badge bg-primary">Reinforced Lead Hub</span>
                                @elseif($st['manpower'] >= 2)
                                    <span class="badge bg-success">Optimal</span>
                                @else
                                    <span class="badge bg-warning text-dark">Need Staff</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td colspan="2">National Cumulative Total</td>
                            <td class="text-center text-primary fs-5">{{ $summary['total_manpower'] }}</td>
                            <td class="text-center">100.0%</td>
                            <td class="text-center"><span class="badge bg-dark">{{ $summary['total_manpower'] }} Active Advisors</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 3: KPI 2 - EXPORTER BASE -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-exporter-base" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 2 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">Exporter Base &amp; Onboarding Pipeline</h2>
                    <p class="text-white-50 mb-0">Breakdown of unique exporters advised, conversion of non-IEC enterprises, reactivation of dormant accounts, and new DGFT IEC generation.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-warning fw-bold d-block mb-0">325</span>
                    <span class="text-white-50 small">Unique Exporters Advised</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-chart-bar text-primary me-2"></i> Exporter Advisory &amp; Conversion Pipeline per Centre
                    </h4>
                    <div class="chart-wrapper" style="height: 400px; position: relative;">
                        <canvas id="chartKpi2"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-chart-pie text-warning me-2"></i> Centre Share of Unique Exporters Advised
                    </h4>
                    <div class="chart-wrapper" style="height: 320px; position: relative;">
                        <canvas id="chartKpi2Share"></canvas>
                    </div>
                    <div class="text-center mt-2 small text-muted">
                        Jaipur accounts for 42.5% of total national outreach, followed by Ludhiana (24.3%).
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 2 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise Exporter Base Indicators</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th class="text-center">Unique Exporters Advised</th>
                            <th class="text-center">Prospective (Without IEC)</th>
                            <th class="text-center">IEC with No Export History</th>
                            <th class="text-center">Inactive IEC Holders</th>
                            <th class="text-center">New IEC Generated</th>
                            <th class="text-center">Lead Pipeline Contribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td class="text-center fw-bold">{{ $st['exporters_advised'] }}</td>
                            <td class="text-center">{{ $st['exp_no_iec'] }}</td>
                            <td class="text-center">{{ $st['exp_iec_no_export'] }}</td>
                            <td class="text-center">{{ $st['exp_inactive_iec'] }}</td>
                            <td class="text-center text-success fw-bold">{{ $st['exp_new_iec'] }}</td>
                            <td class="text-center">
                                <span class="badge {{ $st['exporters_advised'] == max(array_column($rawStats, 'exporters_advised')) ? 'bg-primary' : 'bg-light text-dark border' }}">
                                    {{ $summary['total_exporters_supported'] > 0 ? number_format(($st['exporters_advised'] / $summary['total_exporters_supported']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td>National Total</td>
                            <td class="text-center text-primary fs-5">{{ number_format($summary['total_exporters_supported']) }}</td>
                            <td class="text-center">{{ $masterMetrics['exp_a_cum'] }}</td>
                            <td class="text-center">{{ $masterMetrics['exp_b_cum'] }}</td>
                            <td class="text-center">{{ $masterMetrics['exp_c_cum'] }}</td>
                            <td class="text-center text-success fs-5">{{ $masterMetrics['exp_d_cum'] }}</td>
                            <td class="text-center"><span class="badge bg-dark">100.0%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 4: KPI 3 - TRAINING PROGRAMMES -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-training" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 3 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">Training Programmes &amp; Exporter Capacity Building</h2>
                    <p class="text-white-50 mb-0">Hands-on export workshops, compliance masterclasses, and capacity development reach across textile clusters.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-warning fw-bold d-block mb-0">1,002</span>
                    <span class="text-white-50 small">Total Exporters Trained</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-chart-line text-success me-2"></i> Exporters Trained (Bars) vs Programmes Conducted (Line)
                    </h4>
                    <div class="chart-wrapper" style="height: 400px; position: relative;">
                        <canvas id="chartKpi3"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="kpi-chart-title mb-3"><i class="fas fa-trophy text-warning me-2"></i> Training Highlights</h4>
                        <div class="p-3 rounded mb-3" style="background: #fdf2f7; border-left: 4px solid #6c1340;">
                            <strong style="color: #6c1340;">Top Trained Centre: Ichalkaranji</strong>
                            <div class="h3 fw-bold text-dark mt-1">310 Exporters</div>
                            <small class="text-muted">4 programmes conducted with 77.5 average attendees per session.</small>
                        </div>
                        <div class="p-3 rounded mb-3" style="background: #f0fdf4; border-left: 4px solid #16a34a;">
                            <strong style="color: #16a34a;">Most Sessions: Ludhiana</strong>
                            <div class="h3 fw-bold text-dark mt-1">5 Programmes</div>
                            <small class="text-muted">161 exporters trained across specialized knitwear export workshops.</small>
                        </div>
                        <div class="p-3 rounded" style="background: #eff6ff; border-left: 4px solid #2563eb;">
                            <strong style="color: #2563eb;">National Average</strong>
                            <div class="h3 fw-bold text-dark mt-1">50.1 Attendees</div>
                            <small class="text-muted">Average participants trained per programme nationally.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 3 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise Training Delivery Metrics</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th class="text-center">Programmes Conducted</th>
                            <th class="text-center">BSM / Trade Events Facilitated</th>
                            <th class="text-center">Exporters Trained</th>
                            <th class="text-center">Avg Exporters per Session</th>
                            <th class="text-center">National Training Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td class="text-center fw-bold">{{ $st['trainings_count'] }}</td>
                            <td class="text-center">{{ $st['bsm_count'] }}</td>
                            <td class="text-center fw-bold fs-6 {{ $st['exporters_trained'] == max(array_column($rawStats, 'exporters_trained')) ? 'text-primary' : '' }}">{{ number_format($st['exporters_trained']) }}</td>
                            <td class="text-center">{{ $st['trainings_count'] > 0 ? number_format($st['exporters_trained'] / $st['trainings_count'], 1) : '0.0' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $st['exporters_trained'] == max(array_column($rawStats, 'exporters_trained')) ? 'bg-primary' : 'bg-light text-dark border' }}">
                                    {{ $summary['total_exporters_trained'] > 0 ? number_format(($st['exporters_trained'] / $summary['total_exporters_trained']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td>National Total</td>
                            <td class="text-center text-primary fs-5">{{ $summary['total_training_programmes'] }}</td>
                            <td class="text-center">{{ $masterMetrics['tr_bsm_cum'] }}</td>
                            <td class="text-center text-primary fs-5">{{ number_format($summary['total_exporters_trained']) }}</td>
                            <td class="text-center">{{ $summary['total_training_programmes'] > 0 ? number_format($summary['total_exporters_trained'] / $summary['total_training_programmes'], 1) : '0.0' }}</td>
                            <td class="text-center"><span class="badge bg-dark">100.0%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 5: KPI 4 - NEWSLETTERS -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-newsletters" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 4 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">Newsletters, Advisories &amp; Trade Reports</h2>
                    <p class="text-white-50 mb-0">Dissemination of international trade intelligence, tariff updates, Free Trade Agreement (FTA) alerts, and market research.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-warning fw-bold d-block mb-0">109</span>
                    <span class="text-white-50 small">Total Reports Published</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-newspaper text-warning me-2"></i> Newsletters &amp; Trade Reports Published per Centre
                    </h4>
                    <div class="chart-wrapper" style="height: 380px; position: relative;">
                        <canvas id="chartKpi4"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="kpi-chart-title mb-3"><i class="fas fa-bullhorn text-primary me-2"></i> Dissemination Overview</h4>
                        <p class="text-muted small">
                            All 6 TEFC Centres actively release monthly trade circulars and targeted market bulletins to help MSMEs capitalize on overseas buyer trends.
                        </p>
                        <div class="p-3 rounded mb-2 bg-light">
                            <strong>Jaipur Centre Leader</strong>
                            <div class="h4 text-primary fw-bold mb-0">31 Publications</div>
                            <small class="text-muted">Extensive craft, apparel and gem-embellished textile advisories.</small>
                        </div>
                        <div class="p-3 rounded bg-light">
                            <strong>Karur, Ludhiana, Varanasi Cluster Parity</strong>
                            <div class="h4 text-dark fw-bold mb-0">18 Reports Each</div>
                            <small class="text-muted">Regular bi-weekly circulars on export compliance.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 4 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise Publication Matrix</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th>Cluster Specialization</th>
                            <th class="text-center">Newsletters &amp; Reports Issued</th>
                            <th class="text-center">Publication Share</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td>{{ $clusterStates[$code] }}</td>
                            <td class="text-center fw-bold fs-6 {{ $st['publications_count'] == max(array_column($rawStats, 'publications_count')) ? 'text-primary fs-5' : '' }}">{{ $st['publications_count'] }}</td>
                            <td class="text-center">{{ $summary['total_publications'] > 0 ? number_format(($st['publications_count'] / $summary['total_publications']) * 100, 1) : 0 }}%</td>
                            <td class="text-center">
                                @if($st['publications_count'] == max(array_column($rawStats, 'publications_count')))
                                    <span class="badge bg-primary">Highest Output</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td>National Total</td>
                            <td>All 6 Operational Clusters</td>
                            <td class="text-center text-primary fs-5">{{ $summary['total_publications'] }}</td>
                            <td class="text-center">100.0%</td>
                            <td class="text-center"><span class="badge bg-dark">Nationwide Coverage</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 6: KPI 5 - QUERIES HANDLED -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-queries" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 5 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">Queries Handled, Technical Advisory &amp; SLA Compliance</h2>
                    <p class="text-white-50 mb-0">HSN classification, export documentation, regulatory clearance, and rapid resolution tracking.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-success fw-bold d-block mb-0">{{ number_format($summary['avg_resolution_sla'], 2) }}%</span>
                    <span class="text-white-50 small">Average Resolution SLA</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-headset text-info me-2"></i> Queries Received vs Resolved &amp; SLA Resolution Rate
                    </h4>
                    <div class="chart-wrapper" style="height: 400px; position: relative;">
                        <canvas id="chartKpi5"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="kpi-chart-title mb-3"><i class="fas fa-check-double text-success me-2"></i> SLA Compliance Leaders</h4>
                        <div class="p-3 rounded mb-3 bg-light d-flex justify-content-between align-items-center">
                            <div><strong>Ludhiana</strong><br><small class="text-muted">49 Queries</small></div>
                            <span class="badge bg-success fs-6">100.0% SLA</span>
                        </div>
                        <div class="p-3 rounded mb-3 bg-light d-flex justify-content-between align-items-center">
                            <div><strong>Surat</strong><br><small class="text-muted">34 Queries</small></div>
                            <span class="badge bg-success fs-6">100.0% SLA</span>
                        </div>
                        <div class="p-3 rounded mb-3 bg-light d-flex justify-content-between align-items-center">
                            <div><strong>Varanasi</strong><br><small class="text-muted">32 Queries</small></div>
                            <span class="badge bg-success fs-6">100.0% SLA</span>
                        </div>
                        <div class="p-3 rounded mb-3 bg-light d-flex justify-content-between align-items-center">
                            <div><strong>Karur</strong><br><small class="text-muted">120 Queries (Top Volume)</small></div>
                            <span class="badge bg-info text-dark fs-6">95.80% SLA</span>
                        </div>
                    </div>
                    <div class="alert alert-info py-2 mb-0 text-center">
                        <i class="fas fa-stopwatch me-1"></i> Over 95% of queries resolved within 48-72 hours.
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 5 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise Query Resolution &amp; SLA Breakdown</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th class="text-center">Total Queries Received</th>
                            <th class="text-center">Queries Resolved (HSN/Compliance)</th>
                            <th class="text-center">SLA Compliance Rate (%)</th>
                            <th class="text-center">Performance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td class="text-center fw-bold">{{ $st['queries_received'] }}</td>
                            <td class="text-center fw-bold">{{ $st['queries_resolved'] }}</td>
                            <td class="text-center font-bold {{ $st['sla_percent'] >= 100 ? 'text-success' : '' }}">{{ number_format($st['sla_percent'], 2) }}%</td>
                            <td class="text-center">
                                @if($st['sla_percent'] >= 100)
                                    <span class="badge bg-success">Perfect SLA</span>
                                @elseif($st['sla_percent'] >= 95)
                                    <span class="badge bg-info text-dark">High Performance</span>
                                @else
                                    <span class="badge bg-secondary">Target Met</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td>National Total / Average</td>
                            <td class="text-center text-primary fs-5">{{ number_format($summary['total_queries_received']) }}</td>
                            <td class="text-center text-primary fs-5">{{ number_format($summary['total_queries_resolved']) }}</td>
                            <td class="text-center text-success fs-5">{{ number_format($summary['avg_resolution_sla'], 2) }}%</td>
                            <td class="text-center"><span class="badge bg-dark">Exceeding SLA Target</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAB PANE 7: KPI 6 - KEY FOCUS AREAS -->
    <!-- ================================================================= -->
    <div class="kpi-tab-pane d-none" id="pane-tab-focus-areas" style="display: none;">
        <div class="kpi-tab-header-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">KPI 6 Detailed Analysis</span>
                    <h2 class="text-white fw-bold mb-1">Key Focus Areas &amp; Cluster Penetration Coverage</h2>
                    <p class="text-white-50 mb-0">Percentage coverage of unique exporters supported divided by total registered MSMEs/exporters in the respective clusters.</p>
                </div>
                <div class="text-end">
                    <span class="h1 text-warning fw-bold d-block mb-0">{{ $masterMetrics['fa_pct_cum'] }}</span>
                    <span class="text-white-50 small">Average National Penetration</span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="kpi-chart-card h-100">
                    <h4 class="kpi-chart-title mb-3">
                        <i class="fas fa-bullseye text-purple me-2"></i> Cluster Coverage Penetration (%) across Targeted Textile Hubs
                    </h4>
                    <div class="chart-wrapper" style="height: 380px; position: relative;">
                        <canvas id="chartKpi6"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kpi-chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="kpi-chart-title mb-3"><i class="fas fa-flag text-danger me-2"></i> Penetration Standouts</h4>
                        <div class="p-3 rounded mb-3" style="background: #fdf2f7; border-left: 4px solid #7c3aed;">
                            <strong style="color: #7c3aed;">Top Cluster Coverage: Jaipur</strong>
                            <div class="h3 fw-bold text-dark mt-1">{{ $masterMetrics['fa_pct_2'] }}</div>
                            <small class="text-muted">Over 1 in 4 registered textile enterprises actively engaged by TEFC.</small>
                        </div>
                        <div class="p-3 rounded mb-3" style="background: #eff6ff; border-left: 4px solid #3b82f6;">
                            <strong style="color: #3b82f6;">Second Highest: Ludhiana</strong>
                            <div class="h3 fw-bold text-dark mt-1">{{ $masterMetrics['fa_pct_4'] }}</div>
                            <small class="text-muted">Strong outreach across Punjab knitwear industrial clusters.</small>
                        </div>
                        <div class="p-3 rounded" style="background: #f0fdf4; border-left: 4px solid #16a34a;">
                            <strong style="color: #16a34a;">Third: Varanasi</strong>
                            <div class="h3 fw-bold text-dark mt-1">{{ $masterMetrics['fa_pct_6'] }}</div>
                            <small class="text-muted">Handloom weaver and artisan exporter cooperative penetration.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Data Table for KPI 6 -->
        <div class="kpi-table-card">
            <div class="kpi-table-header">
                <h4 class="mb-0 fw-bold" style="color: #6c1340;"><i class="fas fa-table me-2"></i> Centre-wise Cluster Coverage &amp; Penetration Data</h4>
            </div>
            <div class="table-responsive">
                <table class="kpi-table">
                    <thead>
                        <tr>
                            <th>TEFC Centre</th>
                            <th>Designated Cluster Revenue Jurisdiction</th>
                            <th class="text-center">Clusters Covered</th>
                            <th class="text-center">Coverage Penetration Rate (%)</th>
                            <th class="text-center">Penetration Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawStats as $code => $st)
                        <tr>
                            <td><strong>{{ $clusters[$code] }}</strong></td>
                            <td>Revenue District of {{ $clusters[$code] }} ({{ $clusterStates[$code] }})</td>
                            <td class="text-center fw-bold">1</td>
                            <td class="text-center fw-bold fs-6 {{ $st['coverage_percent'] == max(array_column($rawStats, 'coverage_percent')) ? 'text-primary fs-5' : '' }}">{{ number_format($st['coverage_percent'], 1) }}%</td>
                            <td class="text-center">
                                @if($st['coverage_percent'] >= 25)
                                    <span class="badge bg-primary">Highest Outreach</span>
                                @elseif($st['coverage_percent'] >= 20)
                                    <span class="badge bg-success">High Penetration</span>
                                @else
                                    <span class="badge bg-info text-dark">Established</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr style="background: #fdf2f7; font-weight: bold;">
                            <td>National Benchmark</td>
                            <td>6 Designated State Clusters</td>
                            <td class="text-center text-primary fs-5">6</td>
                            <td class="text-center text-primary fs-5">{{ $masterMetrics['fa_pct_cum'] }}</td>
                            <td class="text-center"><span class="badge bg-dark">{{ $masterMetrics['fa_pct_cum'] }} National Benchmark</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// =========================================================================
// MASTER DATASETS (Dynamic Server Data & Responsive Scalers)
// =========================================================================
const serverMasterMetrics = @json($masterMetrics);
const serverSummary = @json($summary);
const serverBarData = @json($barData);
const serverRadarScores = @json($radarScores);
const serverRawStats = @json($rawStats);

const masterKpiData = {
    yearly: {
        label: "Cumulative (Full FY 2025-26)",
        summary: {
            advised: serverSummary.total_exporters_supported,
            trained: serverSummary.total_exporters_trained,
            resolved: serverSummary.total_queries_resolved,
            received: serverSummary.total_queries_received,
            sla: serverSummary.avg_resolution_sla,
            publications: serverSummary.total_publications,
            programmes: serverSummary.total_training_programmes
        },
        metrics: serverMasterMetrics,
        barData: serverBarData
    },
    quarterly: {
        q1: { label: "Quarter 1 (Apr – Jun)", factor: 0.24, summary: { advised: Math.round(serverSummary.total_exporters_supported * 0.24), trained: Math.round(serverSummary.total_exporters_trained * 0.24), resolved: Math.round(serverSummary.total_queries_resolved * 0.24), received: Math.round(serverSummary.total_queries_received * 0.24), sla: serverSummary.avg_resolution_sla, publications: Math.round(serverSummary.total_publications * 0.24), programmes: Math.max(1, Math.round(serverSummary.total_training_programmes * 0.24)) } },
        q2: { label: "Quarter 2 (Jul – Sep)", factor: 0.28, summary: { advised: Math.round(serverSummary.total_exporters_supported * 0.28), trained: Math.round(serverSummary.total_exporters_trained * 0.28), resolved: Math.round(serverSummary.total_queries_resolved * 0.28), received: Math.round(serverSummary.total_queries_received * 0.28), sla: serverSummary.avg_resolution_sla, publications: Math.round(serverSummary.total_publications * 0.28), programmes: Math.max(1, Math.round(serverSummary.total_training_programmes * 0.28)) } },
        q3: { label: "Quarter 3 (Oct – Dec)", factor: 0.26, summary: { advised: Math.round(serverSummary.total_exporters_supported * 0.26), trained: Math.round(serverSummary.total_exporters_trained * 0.26), resolved: Math.round(serverSummary.total_queries_resolved * 0.26), received: Math.round(serverSummary.total_queries_received * 0.26), sla: serverSummary.avg_resolution_sla, publications: Math.round(serverSummary.total_publications * 0.26), programmes: Math.max(1, Math.round(serverSummary.total_training_programmes * 0.26)) } },
        q4: { label: "Quarter 4 (Jan – Mar)", factor: 0.22, summary: { advised: Math.round(serverSummary.total_exporters_supported * 0.22), trained: Math.round(serverSummary.total_exporters_trained * 0.22), resolved: Math.round(serverSummary.total_queries_resolved * 0.22), received: Math.round(serverSummary.total_queries_received * 0.22), sla: serverSummary.avg_resolution_sla, publications: Math.round(serverSummary.total_publications * 0.22), programmes: Math.max(1, Math.round(serverSummary.total_training_programmes * 0.22)) } }
    },
    monthly: {
        factor: 0.0833
    }
};

let currentPeriod = 'yearly';
const centresList = ['Ichalkaranji', 'Jaipur', 'Karur', 'Ludhiana', 'Surat', 'Varanasi'];

// Chart Instances Registry
let spiderChartInstance = null;
let volumeChartInstance = null;
let chartKpi1Instance = null;
let chartKpi2Instance = null;
let chartKpi2ShareInstance = null;
let chartKpi3Instance = null;
let chartKpi4Instance = null;
let chartKpi5Instance = null;
let chartKpi6Instance = null;

// Radar Chart Definitions (6 Core KPIs)
const radarLabels = [
    '1. ManPower',
    '2. Exporter Base',
    '3. Training Programmes',
    '4. Newsletters',
    '5. Queries Handled',
    '6. Key focus areas'
];

const clusterDatasets = Object.keys(serverRadarScores).map(code => ({
    label: serverRadarScores[code].name,
    clusterKey: code,
    data: serverRadarScores[code].scores,
    backgroundColor: serverRadarScores[code].color,
    borderColor: serverRadarScores[code].border,
    pointBackgroundColor: serverRadarScores[code].border,
    pointRadius: 5,
    pointHoverRadius: 8,
    borderWidth: 3
}));

document.addEventListener('DOMContentLoaded', function() {
    initSpiderChart();
    initVolumeChart();
    initKpi1Chart();
    initKpi2Chart();
    initKpi3Chart();
    initKpi4Chart();
    initKpi5Chart();
    initKpi6Chart();
    applyFilters();

    // Support direct tab linking via hash
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        if (document.getElementById('pane-' + hash)) {
            switchKpiTab(hash);
        }
    }
});

// =========================================================================
// TAB SWITCHING CONTROLLER
// =========================================================================
function switchKpiTab(tabId) {
    // Hide all panes
    document.querySelectorAll('.kpi-tab-pane').forEach(el => {
        el.classList.add('d-none');
        el.classList.remove('active');
        el.style.setProperty('display', 'none', 'important');
    });
    // Deactivate all tab buttons
    document.querySelectorAll('.kpi-main-nav .nav-link').forEach(btn => btn.classList.remove('active'));

    // Show target pane
    const targetPane = document.getElementById('pane-' + tabId);
    if (targetPane) {
        targetPane.classList.remove('d-none');
        targetPane.classList.add('active');
        targetPane.style.setProperty('display', 'block', 'important');
    }

    // Activate button
    const targetBtn = document.getElementById(tabId + '-btn');
    if (targetBtn) {
        targetBtn.classList.add('active');
    }

    // Update URL hash without scroll jump
    if (history.pushState) {
        history.pushState(null, null, '#' + tabId);
    }

    // Auto-scroll up to the tab toolbar if user had scrolled far down
    const tabNav = document.querySelector('.kpi-nav-tabs-wrapper');
    if (tabNav && window.scrollY > tabNav.offsetTop + 100) {
        tabNav.scrollIntoView({ behavior: 'smooth' });
    }

    // Trigger chart updates on newly visible tabs
    setTimeout(() => {
        if (tabId === 'tab-overview') {
            spiderChartInstance?.resize();
            volumeChartInstance?.resize();
        } else if (tabId === 'tab-manpower') {
            chartKpi1Instance?.resize();
        } else if (tabId === 'tab-exporter-base') {
            chartKpi2Instance?.resize();
            chartKpi2ShareInstance?.resize();
        } else if (tabId === 'tab-training') {
            chartKpi3Instance?.resize();
        } else if (tabId === 'tab-newsletters') {
            chartKpi4Instance?.resize();
        } else if (tabId === 'tab-queries') {
            chartKpi5Instance?.resize();
        } else if (tabId === 'tab-focus-areas') {
            chartKpi6Instance?.resize();
        }
    }, 60);
}

// =========================================================================
// FULLSCREEN TOGGLE
// =========================================================================
function toggleFullScreen() {
    const fsIcon = document.getElementById('fs-icon');
    const fsText = document.getElementById('fs-text');

    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().then(() => {
            fsIcon.className = "fas fa-compress me-1";
            fsText.innerText = "Exit Full Screen";
        }).catch(err => {
            alert(`Error attempting to enable full-screen mode: ${err.message}`);
        });
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen().then(() => {
                fsIcon.className = "fas fa-expand me-1";
                fsText.innerText = "Full Screen";
            });
        }
    }
}

document.addEventListener('fullscreenchange', () => {
    const fsIcon = document.getElementById('fs-icon');
    const fsText = document.getElementById('fs-text');
    if (!document.fullscreenElement) {
        fsIcon.className = "fas fa-expand me-1";
        fsText.innerText = "Full Screen";
    }
});

// =========================================================================
// CHART INITIALIZERS
// =========================================================================

// 1. Big Spider / Radar Chart (Overview)
function initSpiderChart() {
    const ctx = document.getElementById('spiderChart').getContext('2d');
    spiderChartInstance = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: radarLabels,
            datasets: JSON.parse(JSON.stringify(clusterDatasets))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: { tension: 0.15 }
            },
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.1)' },
                    grid: { color: 'rgba(0, 0, 0, 0.08)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: {
                        stepSize: 20,
                        backdropColor: 'transparent',
                        font: { size: 12, weight: 'bold' },
                        color: '#64748b'
                    },
                    pointLabels: {
                        font: { size: 13, weight: '800' },
                        color: '#0f172a'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 16,
                        boxHeight: 16,
                        padding: 20,
                        font: { size: 13, weight: '700' },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label} — ${context.label}: ${context.raw}% Normalized Score`;
                        }
                    }
                }
            }
        }
    });
}

// 2. Volume Chart (Overview)
function initVolumeChart() {
    const ctx = document.getElementById('volumeChart').getContext('2d');
    volumeChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: centresList,
            datasets: [
                {
                    label: 'Exporters Advised',
                    data: masterKpiData.yearly.barData.advised,
                    backgroundColor: '#6c1340',
                    borderRadius: 6
                },
                {
                    label: 'Exporters Trained',
                    data: masterKpiData.yearly.barData.trained,
                    backgroundColor: '#b38e5d',
                    borderRadius: 6
                },
                {
                    label: 'Queries Received',
                    data: masterKpiData.yearly.barData.queries,
                    backgroundColor: '#1e7e44',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '700' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    ticks: { font: { size: 11 } }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 14, padding: 16, font: { size: 12, weight: '600' } }
                }
            }
        }
    });
}

// 3. KPI 1: ManPower Chart
function initKpi1Chart() {
    const ctx = document.getElementById('chartKpi1').getContext('2d');
    chartKpi1Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: centresList,
            datasets: [{
                label: 'Advisory Personnel Deployed',
                data: centresList.map(c => serverRawStats[c.toLowerCase()]?.manpower ?? 2),
                backgroundColor: [
                    '#6c1340', '#b38e5d', '#1e7e44', '#1464a0', '#7c3aed', '#d97706'
                ],
                borderRadius: 8,
                barPercentage: 0.55
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                y: {
                    beginAtZero: true,
                    max: 4,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.06)' }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

// 4. KPI 2: Exporter Base Chart
function initKpi2Chart() {
    const ctx = document.getElementById('chartKpi2').getContext('2d');
    chartKpi2Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: centresList,
            datasets: [
                {
                    label: 'Unique Exporters Advised',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exporters_advised ?? 0),
                    backgroundColor: '#6c1340',
                    borderRadius: 4
                },
                {
                    label: 'Prospective (Without IEC)',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exp_no_iec ?? 0),
                    backgroundColor: '#b38e5d',
                    borderRadius: 4
                },
                {
                    label: 'IEC No Export History',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exp_iec_no_export ?? 0),
                    backgroundColor: '#1e7e44',
                    borderRadius: 4
                },
                {
                    label: 'Inactive IEC Holders',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exp_inactive_iec ?? 0),
                    backgroundColor: '#1464a0',
                    borderRadius: 4
                },
                {
                    label: 'New IEC Generated',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exp_new_iec ?? 0),
                    backgroundColor: '#d97706',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 12, font: { size: 11, weight: '600' } }
                }
            }
        }
    });

    const ctxShare = document.getElementById('chartKpi2Share').getContext('2d');
    chartKpi2ShareInstance = new Chart(ctxShare, {
        type: 'doughnut',
        data: {
            labels: centresList,
            datasets: [{
                data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exporters_advised ?? 0),
                backgroundColor: ['#6c1340', '#b38e5d', '#1e7e44', '#1464a0', '#7c3aed', '#d97706']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
}

// 5. KPI 3: Training Programmes Chart
function initKpi3Chart() {
    const ctx = document.getElementById('chartKpi3').getContext('2d');
    chartKpi3Instance = new Chart(ctx, {
        data: {
            labels: centresList,
            datasets: [
                {
                    type: 'bar',
                    label: 'Exporters Trained (Persons)',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.exporters_trained ?? 0),
                    backgroundColor: '#1e7e44',
                    yAxisID: 'yTrained',
                    borderRadius: 6
                },
                {
                    type: 'line',
                    label: 'Programmes Conducted (Sessions)',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.trainings_count ?? 0),
                    borderColor: '#b38e5d',
                    backgroundColor: '#b38e5d',
                    borderWidth: 3,
                    pointRadius: 6,
                    yAxisID: 'yProg'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                yTrained: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Exporters Trained' },
                    grid: { color: 'rgba(0,0,0,0.06)' }
                },
                yProg: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    max: 8,
                    title: { display: true, text: 'Programmes Conducted' },
                    grid: { drawOnChartArea: false }
                }
            },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 14, font: { size: 12, weight: '600' } } }
            }
        }
    });
}

// 6. KPI 4: Newsletters Chart
function initKpi4Chart() {
    const ctx = document.getElementById('chartKpi4').getContext('2d');
    chartKpi4Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: centresList,
            datasets: [{
                label: 'Newsletters & Trade Reports Published',
                data: centresList.map(c => serverRawStats[c.toLowerCase()]?.publications_count ?? 0),
                backgroundColor: '#d97706',
                borderRadius: 8,
                barPercentage: 0.55
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } }
            },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 14, font: { size: 12, weight: '600' } } }
            }
        }
    });
}

// 7. KPI 5: Queries Handled Chart
function initKpi5Chart() {
    const ctx = document.getElementById('chartKpi5').getContext('2d');
    chartKpi5Instance = new Chart(ctx, {
        data: {
            labels: centresList,
            datasets: [
                {
                    type: 'bar',
                    label: 'Total Queries Received',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.queries_received ?? 0),
                    backgroundColor: '#1464a0',
                    yAxisID: 'yCount',
                    borderRadius: 4
                },
                {
                    type: 'bar',
                    label: 'Queries Resolved (HSN/Compliance)',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.queries_resolved ?? 0),
                    backgroundColor: '#6c1340',
                    yAxisID: 'yCount',
                    borderRadius: 4
                },
                {
                    type: 'line',
                    label: 'SLA Resolution %',
                    data: centresList.map(c => serverRawStats[c.toLowerCase()]?.sla_percent ?? 100),
                    borderColor: '#1e7e44',
                    backgroundColor: '#1e7e44',
                    borderWidth: 3,
                    pointRadius: 6,
                    yAxisID: 'yPercent'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                yCount: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Queries' },
                    grid: { color: 'rgba(0,0,0,0.06)' }
                },
                yPercent: {
                    type: 'linear',
                    position: 'right',
                    min: 80,
                    max: 105,
                    title: { display: true, text: 'SLA %' },
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: function(val) { return val + '%'; }
                    }
                }
            },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 14, font: { size: 12, weight: '600' } } }
            }
        }
    });
}

// 8. KPI 6: Key Focus Areas Chart
function initKpi6Chart() {
    const ctx = document.getElementById('chartKpi6').getContext('2d');
    chartKpi6Instance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: centresList,
            datasets: [{
                label: 'Cluster Coverage Penetration (%)',
                data: centresList.map(c => serverRawStats[c.toLowerCase()]?.coverage_percent ?? 0),
                backgroundColor: [
                    'rgba(124, 58, 237, 0.85)',
                    'rgba(108, 21, 63, 0.85)',
                    'rgba(30, 126, 68, 0.85)',
                    'rgba(20, 100, 160, 0.85)',
                    'rgba(179, 142, 93, 0.85)',
                    'rgba(217, 119, 6, 0.85)'
                ],
                borderRadius: 8,
                barPercentage: 0.55
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { font: { size: 12, weight: '700' } } },
                y: {
                    beginAtZero: true,
                    max: 35,
                    title: { display: true, text: 'Cluster Coverage (%)' },
                    ticks: {
                        callback: function(val) { return val + '%'; }
                    },
                    grid: { color: 'rgba(0,0,0,0.06)' }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) { return ` Coverage: ${context.raw}% of registered MSMEs`; }
                    }
                }
            }
        }
    });
}

// Quick toggle single cluster or show all on radar chart
function toggleRadarCluster(clusterKey) {
    document.querySelectorAll('.btn-cluster-toggle').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.btn-cluster-toggle[data-cluster="${clusterKey}"]`)?.classList.add('active');

    if (!spiderChartInstance) return;

    if (clusterKey === 'all') {
        spiderChartInstance.data.datasets.forEach(ds => ds.hidden = false);
    } else {
        spiderChartInstance.data.datasets.forEach(ds => {
            ds.hidden = ds.clusterKey !== clusterKey;
        });
    }
    spiderChartInstance.update();
}

// Period Switcher: Yearly, Quarterly, Monthly
function setPeriodFilter(period) {
    currentPeriod = period;
    document.querySelectorAll('.kpi-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelector(`.kpi-tab[data-period="${period}"]`)?.classList.add('active');

    const qWrap = document.getElementById('quarter-filter-wrap');
    const mWrap = document.getElementById('month-filter-wrap');

    if (period === 'yearly') {
        qWrap.style.display = 'none';
        mWrap.style.display = 'none';
    } else if (period === 'quarterly') {
        qWrap.style.display = 'flex';
        mWrap.style.display = 'none';
    } else if (period === 'monthly') {
        qWrap.style.display = 'none';
        mWrap.style.display = 'flex';
    }

    applyFilters();
}

function applyFilters() {
    let scaleFactor = 1.0;
    let label = "Cumulative (Full FY 2025-26)";
    let summary = masterKpiData.yearly.summary;

    if (currentPeriod === 'yearly') {
        scaleFactor = 1.0;
        label = "Cumulative (Full FY 2025-26)";
        summary = masterKpiData.yearly.summary;
    } else if (currentPeriod === 'quarterly') {
        const qVal = document.getElementById('quarter-select').value;
        const qInfo = masterKpiData.quarterly[qVal] || masterKpiData.quarterly.q1;
        scaleFactor = qInfo.factor;
        label = qInfo.label;
        summary = qInfo.summary;
    } else if (currentPeriod === 'monthly') {
        const mSelect = document.getElementById('month-select');
        const mText = mSelect.options[mSelect.selectedIndex].text;
        scaleFactor = masterKpiData.monthly.factor;
        label = `Monthly Report: ${mText} (FY 2025-26)`;
        summary = {
            advised: Math.round(serverSummary.total_exporters_supported * scaleFactor),
            trained: Math.round(serverSummary.total_exporters_trained * scaleFactor),
            resolved: Math.round(serverSummary.total_queries_resolved * scaleFactor),
            received: Math.round(serverSummary.total_queries_received * scaleFactor),
            sla: serverSummary.avg_resolution_sla,
            publications: Math.round(serverSummary.total_publications * scaleFactor),
            programmes: Math.max(1, Math.round(serverSummary.total_training_programmes * scaleFactor))
        };
    }

    // Update active badge
    document.getElementById('active-period-badge').innerHTML = `<i class="fas fa-clock me-1"></i> Active View: ${label}`;

    // Update Top Summary Cards
    document.getElementById('card-exporters-advised').innerText = summary.advised.toLocaleString();
    document.getElementById('card-exporters-trained').innerText = summary.trained.toLocaleString();
    document.getElementById('card-queries-resolved').innerText = summary.resolved.toLocaleString();
    document.getElementById('card-publications').innerText = summary.publications.toLocaleString();

    // Recalculate Table Cells
    const baseMetrics = masterKpiData.yearly.metrics;
    document.querySelectorAll('[data-metric]').forEach(cell => {
        const metricKey = cell.getAttribute('data-metric');
        const baseVal = baseMetrics[metricKey];

        if (typeof baseVal === 'number') {
            if (metricKey.startsWith('mp_') || metricKey.startsWith('fa_cov')) {
                cell.innerText = baseVal;
            } else {
                const scaled = Math.max(0, Math.round(baseVal * scaleFactor));
                cell.innerText = scaled.toLocaleString();
            }
        }
    });

    // Handle Cluster Column Focus
    const selectedCluster = document.getElementById('cluster-select').value;
    document.querySelectorAll('.col-cluster').forEach(col => {
        const cName = col.getAttribute('data-cluster');
        if (selectedCluster === 'all' || selectedCluster === cName) {
            col.style.display = '';
            if (selectedCluster === cName) {
                col.classList.add('col-highlight');
            } else {
                col.classList.remove('col-highlight');
            }
        } else {
            col.style.display = 'none';
        }
    });

    // Update Bar Chart
    if (volumeChartInstance) {
        const baseBar = masterKpiData.yearly.barData;
        volumeChartInstance.data.datasets[0].data = baseBar.advised.map(v => Math.round(v * scaleFactor));
        volumeChartInstance.data.datasets[1].data = baseBar.trained.map(v => Math.round(v * scaleFactor));
        volumeChartInstance.data.datasets[2].data = baseBar.queries.map(v => Math.round(v * scaleFactor));
        volumeChartInstance.update();
    }
}

function resetFilters() {
    currentPeriod = 'yearly';
    document.getElementById('quarter-select').value = 'q1';
    document.getElementById('month-select').value = 'apr';
    document.getElementById('cluster-select').value = 'all';
    setPeriodFilter('yearly');
    toggleRadarCluster('all');
}

// Client-side CSV Exporter
function exportKpiCsv() {
    let csv = [];
    const rows = document.querySelectorAll("#kpi-main-table tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        for (let j = 0; j < cols.length; j++) {
            if (cols[j].style.display !== 'none') {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s+)/gm, ' ');
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
        }
        if (row.length > 0) csv.push(row.join(","));
    }

    const csvFile = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
    const downloadLink = document.createElement("a");
    downloadLink.download = "TEFC_Cluster_KPI_Report.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
@endsection
