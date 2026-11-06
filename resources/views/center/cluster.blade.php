@extends('layouts.portal')

@section('title', $centre['name'] . ' Cluster Page — Content & Section Management')

@section('sidebar')
    <li><a href="{{ route('center.dashboard') }}"><i class="fas fa-landmark"></i> Centre Overview</a></li>
    <li>
        <a href="{{ route('center.applications') }}">
            <i class="fas fa-user-check"></i> Exporter Applications
        </a>
    </li>
    <li><a href="{{ route('center.kpis') }}"><i class="fas fa-chart-line"></i> Performance &amp; KPIs</a></li>
    <li><a href="{{ route('center.cluster.edit') }}" class="active"><i class="fas fa-layer-group"></i> Cluster Page Content</a></li>
    <li>
        <a href="{{ url('/kpi-dashboard') }}" target="_blank" style="color: #6c1340;">
            <i class="fas fa-external-link-alt"></i> Live KPI Dashboard
        </a>
    </li>
    @if(Auth::user()->hasRole('admin'))
        <li style="margin-top: 2rem; border-top: 1px dashed var(--border); padding-top: 1rem;">
            <a href="{{ route('dashboard') }}" style="color: var(--primary); font-weight: 700;">
                <i class="fas fa-arrow-left"></i> Return to HO Master
            </a>
        </li>
    @endif
@endsection

@section('content')
@if($isHoMode)
    <div style="background: linear-gradient(135deg, #6c1340 0%, #3f0923 100%); color: #fff; border-radius: 14px; padding: 1.2rem 1.8rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(108, 21, 63, 0.2);">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="font-size: 1.8rem; background: rgba(255,255,255,0.15); width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">🏛️</div>
            <div>
                <h5 style="margin: 0; font-weight: 800; font-size: 1.1rem;">HO Centre Direct Access Mode</h5>
                <p style="margin: 0; opacity: 0.85; font-size: 0.88rem;">Managing <strong>{{ $centre['name'] }} Cluster</strong> portal content and section statuses.</p>
            </div>
        </div>
        <a href="{{ route('admin.centre.exit') }}" class="btn btn-sm btn-light" style="font-weight: 700; color: var(--primary); border-radius: 8px; padding: 0.5rem 1.2rem;">
            <i class="fas fa-sign-out-alt me-1"></i> Exit Centre View
        </a>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Header Banner -->
<div class="portal-card mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #fcf6f9 100%); border-left: 5px solid var(--primary);">
    <div style="padding: 1.8rem 2rem;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge" style="background: var(--primary); color: #fff; font-size: 0.78rem; text-transform: uppercase; padding: 0.35rem 0.8rem; border-radius: 8px;">
                        Cluster Content Management
                    </span>
                    <span class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-1"></i> {{ $centre['state'] }}</span>
                </div>
                <h2 style="color: var(--primary); font-weight: 900; margin: 0 0 0.5rem 0; font-size: 1.8rem;">
                    {{ $clusterData['name'] ?? $centre['name'] }} Textile Cluster Page
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.92rem;">
                    Update your cluster's public overview, metrics, product highlights, exporters directory, scheduled events, and turn individual sections active or inactive on the live portal.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ url('/p/clusters') }}" target="_blank" class="btn btn-outline-primary fw-bold" style="border-radius: 10px; padding: 0.7rem 1.3rem;">
                    <i class="fas fa-external-link-alt me-1"></i> View Live Public Page
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('center.cluster.update') }}" method="POST" id="clusterForm">
    @csrf

    <!-- SECTION 1: SECTION ACTIVE / INACTIVE SWITCHBOARD -->
    @php
        $sec = $clusterData['sections_active'] ?? [];
    @endphp
    <div class="portal-card mb-4">
        <div class="portal-card-header" style="background: #faf7f9;">
            <div>
                <h3 style="font-size: 1.15rem; color: var(--primary); margin: 0;">
                    <i class="fas fa-toggle-on me-2"></i> Section Display Controls (Active / Inactive)
                </h3>
                <span class="small text-muted">Control which sections are publicly visible on the {{ $clusterData['name'] ?? $centre['name'] }} Cluster detail page. Turn OFF to hide any section.</span>
            </div>
        </div>
        <div style="padding: 1.8rem 2rem;">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-chart-pie text-primary"></i>
                                <strong class="small">Stats Strip</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Units, Export Value, Workforce, Destinations bar</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_stats" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_stats" value="1" id="sec_stats" {{ ($sec['stats'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_stats">{{ ($sec['stats'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-info-circle text-primary"></i>
                                <strong class="small">Overview & Facts</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Cluster narrative history & Key Facts card</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_overview" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_overview" value="1" id="sec_overview" {{ ($sec['overview'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_overview">{{ ($sec['overview'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-cubes text-primary"></i>
                                <strong class="small">Key Highlights</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Primary product specialization badges</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_highlights" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_highlights" value="1" id="sec_highlights" {{ ($sec['highlights'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_highlights">{{ ($sec['highlights'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-chart-line text-primary"></i>
                                <strong class="small">Export Flow Chart</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Monthly export volume graph</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_chart" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_chart" value="1" id="sec_chart" {{ ($sec['chart'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_chart">{{ ($sec['chart'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-building text-primary"></i>
                                <strong class="small">Exporters & Traders</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Directory of verified exporters in this cluster</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_exporters" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_exporters" value="1" id="sec_exporters" {{ ($sec['exporters'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_exporters">{{ ($sec['exporters'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-flask text-primary"></i>
                                <strong class="small">Facilities & Support</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Testing labs, training, and certification support</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_facilities" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_facilities" value="1" id="sec_facilities" {{ ($sec['facilities'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_facilities">{{ ($sec['facilities'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between" style="border: 1px solid #b38e5d !important;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-calendar-alt text-warning"></i>
                                <strong class="small" style="color: #6c1340;">Events & Workshops</strong>
                                <span class="badge bg-warning text-dark" style="font-size:0.6rem;">NEW</span>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Buyer meets, conclaves & technical workshops</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_events" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_events" value="1" id="sec_events" {{ ($sec['events'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_events">{{ ($sec['events'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="p-3 border rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fas fa-map-marked-alt text-primary"></i>
                                <strong class="small">Cluster Office & Map</strong>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem; display:block; margin-bottom: 0.8rem;">Helpline, address & Google Maps integration</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="sec_office" value="0">
                            <input class="form-check-input" type="checkbox" name="sec_office" value="1" id="sec_office" {{ ($sec['office'] ?? true) ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            <label class="form-check-label small fw-bold ms-1" for="sec_office">{{ ($sec['office'] ?? true) ? 'Active' : 'Hidden' }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS NAVIGATION -->
    <ul class="nav nav-pills mb-4 gap-2" id="clusterTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold" id="tab-general-btn" data-bs-toggle="pill" data-bs-target="#tab-general" type="button">
                <i class="fas fa-sliders-h me-1"></i> General & Metrics
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-overview-btn" data-bs-toggle="pill" data-bs-target="#tab-overview" type="button">
                <i class="fas fa-file-alt me-1"></i> Overview & Highlights
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-events-btn" data-bs-toggle="pill" data-bs-target="#tab-events" type="button">
                <i class="fas fa-calendar-check text-warning me-1"></i> Events & Workshops (<span id="events-count">{{ count($clusterData['events'] ?? []) }}</span>)
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-exporters-btn" data-bs-toggle="pill" data-bs-target="#tab-exporters" type="button">
                <i class="fas fa-store me-1"></i> Exporters (<span id="exporters-count">{{ count($clusterData['exporters'] ?? []) }}</span>)
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-chart-btn" data-bs-toggle="pill" data-bs-target="#tab-chart" type="button">
                <i class="fas fa-chart-line me-1"></i> Export Volume Graph
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-contact-btn" data-bs-toggle="pill" data-bs-target="#tab-contact" type="button">
                <i class="fas fa-map-marker-alt me-1"></i> Office & Contact
            </button>
        </li>
    </ul>

    <div class="tab-content" id="clusterTabContent">
        <!-- TAB 1: GENERAL & METRICS -->
        <div class="tab-pane fade show active" id="tab-general">
            <div class="portal-card p-4 mb-4">
                <h4 class="text-primary fw-bold mb-3" style="font-size:1.15rem;">Cluster Identification & Metrics</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Cluster Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $clusterData['name'] ?? $centre['name'] }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">State Jurisdiction</label>
                        <input type="text" name="state" class="form-control" value="{{ $clusterData['state'] ?? $centre['state'] }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Primary Specialization</label>
                        <input type="text" name="spec" class="form-control" value="{{ $clusterData['spec'] ?? 'Home Textiles' }}" placeholder="e.g. Woven Fabrics, Synthetic Textiles" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Heritage / Established Era</label>
                        <input type="text" name="era" class="form-control" value="{{ $clusterData['era'] ?? 'Early 20th Century' }}" placeholder="e.g. Late 19th Century">
                    </div>

                    <div class="col-12 mt-4">
                        <div class="p-3 rounded-3 d-flex justify-content-between align-items-center mb-2" style="background: #fdf6f9; border: 1px solid rgba(108,19,64,0.18);">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-chart-line me-1" style="color:#6c1340;"></i> Hero Stats Strip Metrics</h5>
                                <small class="text-muted">Display the 4 key metrics on the live cluster page</small>
                            </div>
                            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" id="sec_stats_inline" {{ ($sec['stats'] ?? true) ? 'checked' : '' }} style="width: 2.6em; height: 1.3em; cursor: pointer;">
                                <label class="form-check-label small fw-bold" for="sec_stats_inline" id="lbl_sec_stats_inline">{{ ($sec['stats'] ?? true) ? 'Active' : 'Hidden' }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fw-bold">Manufacturing Units</label>
                        <input type="text" name="units" class="form-control" value="{{ $clusterData['units'] ?? '20,000+' }}" placeholder="e.g. 20,000+">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fw-bold">Annual Export Value</label>
                        <input type="text" name="exportVal" class="form-control" value="{{ $clusterData['exportVal'] ?? 'Rs.2,500 Cr+' }}" placeholder="e.g. Rs.2,500 Cr+">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fw-bold">Workforce Employed</label>
                        <input type="text" name="workforce" class="form-control" value="{{ $clusterData['workforce'] ?? '1 Lakh+' }}" placeholder="e.g. 1 Lakh+">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fw-bold">Export Destinations</label>
                        <input type="text" name="dests" class="form-control" value="{{ $clusterData['dests'] ?? '40+' }}" placeholder="e.g. 40+">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: OVERVIEW & HIGHLIGHTS -->
        <div class="tab-pane fade" id="tab-overview">
            <div class="portal-card p-4 mb-4">
                <h4 class="text-primary fw-bold mb-3" style="font-size:1.15rem;">Cluster Overview Description</h4>
                <div class="mb-4">
                    <label class="form-label fw-bold">Overview Text</label>
                    <textarea name="desc" class="form-control" rows="5" placeholder="Detailed historical and industrial profile of the cluster">{{ $clusterData['desc'] ?? '' }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-0">Key Highlights (Product Specializations)</h5>
                    <span class="text-muted small" style="font-size:0.75rem;"><i class="fas fa-info-circle me-1"></i>You can add product titles and upload product images</span>
                </div>
                <p class="small text-muted mb-3">Add key product categories displayed in the "Key Highlights" grid on the public cluster page.</p>
                <div id="highlights-container" class="d-flex flex-wrap gap-2 mb-3">
                    <!-- Populated via JS -->
                </div>
                <div class="card p-3 bg-light border rounded-3" style="max-width: 600px;">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold mb-1">Product Title <span class="text-danger">*</span></label>
                            <input type="text" id="new-highlight-input" class="form-control form-control-sm" placeholder="e.g. Cotton Yarn">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Product Image <small class="text-muted">(Optional)</small></label>
                            <input type="file" id="new-highlight-file" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-primary w-100 fw-bold" onclick="addHighlight()">
                                <i class="fas fa-plus me-1"></i> Add Product
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="json_highlights" id="json_highlights">
            </div>
        </div>

        <!-- TAB 3: EVENTS & WORKSHOPS (NEW!) -->
        <div class="tab-pane fade" id="tab-events">
            <div class="portal-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="text-primary fw-bold mb-1" style="font-size:1.15rem;">
                            <i class="fas fa-calendar-alt text-warning me-2"></i> Cluster Events, Seminars &amp; Buyer Meets
                        </h4>
                        <span class="small text-muted">Manage scheduled events displayed in the new Events section directly below Facilities & Support.</span>
                    </div>
                    <button type="button" class="btn btn-primary fw-bold" onclick="addEventRow()">
                        <i class="fas fa-plus me-1"></i> Add New Event
                    </button>
                </div>

                <div id="events-list" class="d-flex flex-column gap-3">
                    <!-- Populated dynamically via JS -->
                </div>
                <input type="hidden" name="json_events" id="json_events">
            </div>
        </div>

        <!-- TAB 4: EXPORTERS & TRADERS -->
        <div class="tab-pane fade" id="tab-exporters">
            <div class="portal-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="text-primary fw-bold mb-1" style="font-size:1.15rem;">
                            <i class="fas fa-building me-2"></i> Exporters &amp; Manufacturers Directory
                        </h4>
                        <span class="small text-muted">Feature active exporter units under this cluster on the public detail page.</span>
                    </div>
                    <button type="button" class="btn btn-primary fw-bold" onclick="addExporterRow()">
                        <i class="fas fa-plus me-1"></i> Add Exporter
                    </button>
                </div>

                <div id="exporters-list" class="d-flex flex-column gap-3">
                    <!-- Populated dynamically via JS -->
                </div>
                <input type="hidden" name="json_exporters" id="json_exporters">
            </div>
        </div>

        <!-- TAB 5: EXPORT VOLUME GRAPH -->
        <div class="tab-pane fade" id="tab-chart">
            <div class="portal-card p-4 mb-4">
                <h4 class="text-primary fw-bold mb-2" style="font-size:1.15rem;">Volume of Export Flow (Monthly Trend)</h4>
                <p class="small text-muted mb-4">Input monthly export figures in Rs. Crores to automatically render the crisp interactive trend graph.</p>

                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $curr = $clusterData['chartCurr'] ?? [25, 28, 30, 29, 35, 34, 38, 40, 42, 38, 45, 48];
                    $prev = $clusterData['chartPrev'] ?? [20, 22, 25, 24, 28, 27, 30, 32, 35, 30, 36, 40];
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:160px;">Timeline</th>
                                @foreach($months as $m)
                                    <th>{{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-primary text-start">Current Year (Rs. Cr)</td>
                                @foreach($months as $i => $m)
                                    <td>
                                        <input type="number" step="any" class="form-control form-control-sm text-center chart-curr-input" data-index="{{ $i }}" value="{{ $curr[$i] ?? 0 }}" style="min-width:55px;">
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted text-start">Previous Year (Rs. Cr)</td>
                                @foreach($months as $i => $m)
                                    <td>
                                        <input type="number" step="any" class="form-control form-control-sm text-center chart-prev-input" data-index="{{ $i }}" value="{{ $prev[$i] ?? 0 }}" style="min-width:55px;">
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="json_chart_curr" id="json_chart_curr">
                <input type="hidden" name="json_chart_prev" id="json_chart_prev">
            </div>
        </div>

        <!-- TAB 6: OFFICE & CONTACT -->
        <div class="tab-pane fade" id="tab-contact">
            <div class="portal-card p-4 mb-4">
                <h4 class="text-primary fw-bold mb-3" style="font-size:1.15rem;">TEFC Cluster Office &amp; Location Details</h4>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Office Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $clusterData['address'] ?? '' }}" placeholder="TEFC Office, Main Road...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Helpline / Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $clusterData['phone'] ?? '' }}" placeholder="+91 000 0000000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Official Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $clusterData['email'] ?? $centre['email'] }}" placeholder="center@tefc.gov.in">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Google Maps Direction URL</label>
                        <input type="url" name="mapUrl" class="form-control" value="{{ $clusterData['mapUrl'] ?? '' }}" placeholder="https://maps.google.com/?q=...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden complete cluster payload input for fail-safe submission -->
    <input type="hidden" name="json_cluster" id="json_cluster_payload">

    <!-- Bottom Action Bar -->
    <div class="p-3 bg-white rounded-3 border d-flex justify-content-between align-items-center shadow-sm">
        <a href="{{ route('center.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
        <button type="submit" class="btn btn-primary btn-lg px-4 fw-bold" onclick="prepareClusterPayload()">
            <i class="fas fa-save me-1"></i> Save &amp; Publish Cluster Content
        </button>
    </div>
</form>

<style>
.nav-pills .nav-link {
    color: #4a5568;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    transition: all 0.2s;
}
.nav-pills .nav-link.active {
    background: var(--primary) !important;
    color: #fff !important;
    border-color: var(--primary) !important;
}
.event-row-card, .exporter-row-card {
    background: #fbf9fb;
    border: 1px solid #ebd8e4;
    border-radius: 12px;
    padding: 1.2rem;
    position: relative;
    transition: box-shadow 0.2s;
}
.event-row-card:hover, .exporter-row-card:hover {
    box-shadow: 0 4px 14px rgba(108, 19, 64, 0.08);
}
</style>

<script>
@php
    $chartCurrData = $clusterData['chartCurr'] ?? [25, 28, 30, 29, 35, 34, 38, 40, 42, 38, 45, 48];
    $chartPrevData = $clusterData['chartPrev'] ?? [20, 22, 25, 24, 28, 27, 30, 32, 35, 30, 36, 40];
@endphp
let currentHighlights = @json($clusterData['highlights'] ?? []);
let currentEvents = @json($clusterData['events'] ?? []);
let currentExporters = @json($clusterData['exporters'] ?? []);
let currentChartCurr = {!! json_encode($chartCurrData) !!};
let currentChartPrev = {!! json_encode($chartPrevData) !!};

document.addEventListener('DOMContentLoaded', function () {
    renderHighlights();
    renderEvents();
    renderExporters();

    // Chart inputs listener
    document.querySelectorAll('.chart-curr-input').forEach(inp => {
        inp.addEventListener('input', function() {
            const idx = parseInt(this.getAttribute('data-index'));
            currentChartCurr[idx] = parseFloat(this.value) || 0;
        });
    });
    document.querySelectorAll('.chart-prev-input').forEach(inp => {
        inp.addEventListener('input', function() {
            const idx = parseInt(this.getAttribute('data-index'));
            currentChartPrev[idx] = parseFloat(this.value) || 0;
        });
    });

    // Sync inline stats strip switch with main switch
    const mainStatsSw = document.getElementById('sec_stats');
    const inlineStatsSw = document.getElementById('sec_stats_inline');
    const inlineStatsLbl = document.getElementById('lbl_sec_stats_inline');
    if (inlineStatsSw && mainStatsSw) {
        inlineStatsSw.addEventListener('change', function() {
            mainStatsSw.checked = this.checked;
            const topLbl = mainStatsSw.parentElement.querySelector('.form-check-label');
            if (topLbl) topLbl.textContent = this.checked ? 'Active' : 'Hidden';
            if (inlineStatsLbl) inlineStatsLbl.textContent = this.checked ? 'Active' : 'Hidden';
        });
        mainStatsSw.addEventListener('change', function() {
            inlineStatsSw.checked = this.checked;
            if (inlineStatsLbl) inlineStatsLbl.textContent = this.checked ? 'Active' : 'Hidden';
        });
    }

    // Switch toggles status label updates
    document.querySelectorAll('.form-check-input').forEach(sw => {
        sw.addEventListener('change', function() {
            const label = this.parentElement.querySelector('.form-check-label');
            if (label) {
                label.textContent = this.checked ? 'Active' : 'Hidden';
            }
        });
    });
});

// ASSET BASE HELPER
const ASSET_BASE = @json(asset('/'));
function formatAssetUrl(path) {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    const cleanPath = path.replace(/^\/+/, '');
    const cleanBase = ASSET_BASE.replace(/\/+$/, '');
    return cleanBase + '/' + cleanPath;
}

// HIGHLIGHTS FUNCTIONS
function renderHighlights() {
    const container = document.getElementById('highlights-container');
    container.innerHTML = '';
    if (!currentHighlights || currentHighlights.length === 0) {
        container.innerHTML = '<span class="text-muted small">No product highlights added yet. Use form below to add.</span>';
        document.getElementById('json_highlights').value = '[]';
        return;
    }

    currentHighlights.forEach((h, i) => {
        const name = typeof h === 'object' ? (h.name || '') : h;
        const img = (typeof h === 'object' && h.image) ? h.image : '';
        const card = document.createElement('div');
        card.className = 'border rounded-3 p-2 bg-white shadow-sm d-flex align-items-center gap-2';
        card.style.minWidth = '220px';
        const imgPreview = img ? formatAssetUrl(img) : '';

        card.innerHTML = `
            <div style="width:44px; height:44px; border-radius:6px; background:#f8f9fa; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center; border:1px solid #dee2e6;">
                ${imgPreview ? `<img src="${imgPreview}" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-image text-muted\\'></i>';" style="width:100%; height:100%; object-fit:cover;">` : `<i class="fas fa-image text-muted"></i>`}
            </div>
            <div class="flex-grow-1" style="min-width:0;">
                <div class="fw-bold small text-truncate" title="${name}">${name}</div>
                <label class="btn btn-link p-0 text-decoration-none text-primary" style="font-size:0.72rem; cursor:pointer;">
                    <i class="fas fa-camera me-1"></i>${img ? 'Change Image' : 'Add Image'}
                    <input type="file" accept="image/*" style="display:none;" onchange="uploadCenterHighlightImg(${i}, this)">
                </label>
            </div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="removeHighlight(${i})" title="Remove"><i class="fas fa-times"></i></button>
        `;
        container.appendChild(card);
    });
    document.getElementById('json_highlights').value = JSON.stringify(currentHighlights);
}

async function uploadCenterHighlightImg(idx, fileInput) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

    const card = fileInput.closest('.border');
    const label = card ? card.querySelector('label') : null;
    const origText = label ? label.innerHTML : '';
    if (label) label.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

    try {
        fileInput.disabled = true;
        const res = await fetch("{{ route('cms.upload-cluster-image') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            const current = currentHighlights[idx];
            const name = typeof current === 'object' ? current.name : current;
            currentHighlights[idx] = { name: name, image: data.path || data.url };
            renderHighlights();
        } else {
            alert(data.message || 'Image upload failed.');
            if (label) label.innerHTML = origText;
        }
    } catch(err) {
        console.error(err);
        alert('An error occurred while uploading image.');
        if (label) label.innerHTML = origText;
    } finally {
        fileInput.disabled = false;
    }
}

async function addHighlight() {
    const input = document.getElementById('new-highlight-input');
    const fileInp = document.getElementById('new-highlight-file');
    const val = input.value.trim();
    if (!val) {
        alert('Please enter a product title.');
        return;
    }

    let imgPath = '';
    if (fileInp && fileInp.files && fileInp.files[0]) {
        const formData = new FormData();
        formData.append('image', fileInp.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        
        const addBtn = fileInp.closest('.card')?.querySelector('button');
        const origBtnText = addBtn ? addBtn.innerHTML : '';
        if (addBtn) { addBtn.disabled = true; addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...'; }

        try {
            const res = await fetch("{{ route('cms.upload-cluster-image') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                imgPath = data.path || data.url;
            } else {
                alert(data.message || 'Image upload failed.');
            }
        } catch(e) {
            console.error(e);
            alert('An error occurred while uploading product image.');
        } finally {
            if (addBtn) { addBtn.disabled = false; addBtn.innerHTML = origBtnText; }
        }
    }

    currentHighlights.push({ name: val, image: imgPath });
    input.value = '';
    if (fileInp) fileInp.value = '';
    renderHighlights();
}

function removeHighlight(i) {
    currentHighlights.splice(i, 1);
    renderHighlights();
}

// EVENTS FUNCTIONS
function renderEvents() {
    const container = document.getElementById('events-list');
    container.innerHTML = '';
    document.getElementById('events-count').textContent = currentEvents.length;

    if (currentEvents.length === 0) {
        container.innerHTML = `
            <div class="text-center p-4 border rounded-3 bg-light text-muted">
                <i class="fas fa-calendar-times fa-2x mb-2 text-secondary"></i>
                <p class="mb-2 fw-bold">No Events Currently Configured</p>
                <button type="button" class="btn btn-sm btn-primary" onclick="addEventRow()">+ Add Your First Event</button>
            </div>
        `;
        document.getElementById('json_events').value = JSON.stringify([]);
        return;
    }

    currentEvents.forEach((evt, i) => {
        const card = document.createElement('div');
        card.className = 'event-row-card';
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-warning text-dark fw-bold">Event #${i + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeEvent(${i})" title="Remove Event">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-7">
                    <label class="small text-muted fw-bold">Event Title</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.title || ''}" onchange="updateEvent(${i}, 'title', this.value)" placeholder="e.g. Cotton Yarn Buyer-Seller Meet" required>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">Event Type</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.type || ''}" onchange="updateEvent(${i}, 'type', this.value)" placeholder="e.g. Buyer Meet, Workshop">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted fw-bold">Status</label>
                    <select class="form-select form-select-sm" onchange="updateEvent(${i}, 'status', this.value)">
                        <option value="Upcoming" ${evt.status === 'Upcoming' ? 'selected' : ''}>Upcoming</option>
                        <option value="Registration Open" ${evt.status === 'Registration Open' ? 'selected' : ''}>Registration Open</option>
                        <option value="Scheduled" ${evt.status === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
                        <option value="Completed" ${evt.status === 'Completed' ? 'selected' : ''}>Completed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Date</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.date || ''}" onchange="updateEvent(${i}, 'date', this.value)" placeholder="e.g. Dec 15, 2026">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Time</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.time || ''}" onchange="updateEvent(${i}, 'time', this.value)" placeholder="e.g. 10:00 AM – 4:00 PM IST">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Badge / Tag</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.badge || ''}" onchange="updateEvent(${i}, 'badge', this.value)" placeholder="e.g. Free Registration">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">Venue / Platform</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.venue || ''}" onchange="updateEvent(${i}, 'venue', this.value)" placeholder="e.g. TEFC Center Hall, Main Road">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">Registration / Details URL</label>
                    <input type="text" class="form-control form-control-sm" value="${evt.reg_url || ''}" onchange="updateEvent(${i}, 'reg_url', this.value)" placeholder="e.g. #register or https://...">
                </div>
                <div class="col-12">
                    <label class="small text-muted fw-bold">Short Description</label>
                    <textarea class="form-control form-control-sm" rows="2" onchange="updateEvent(${i}, 'desc', this.value)" placeholder="Brief agenda or target participants">${evt.desc || ''}</textarea>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    document.getElementById('json_events').value = JSON.stringify(currentEvents);
}

function addEventRow() {
    currentEvents.push({
        title: 'New Textile Workshop',
        type: 'Workshop',
        status: 'Registration Open',
        date: 'Dec 20, 2026',
        time: '10:00 AM – 3:30 PM IST',
        badge: 'Free Registration',
        venue: 'TEFC Cluster Facilitation Centre',
        reg_url: '#register',
        desc: 'Capacity building and export market facilitation session.'
    });
    renderEvents();
}

function updateEvent(i, field, val) {
    if (currentEvents[i]) {
        currentEvents[i][field] = val;
        document.getElementById('json_events').value = JSON.stringify(currentEvents);
    }
}

function removeEvent(i) {
    if (confirm('Delete this event?')) {
        currentEvents.splice(i, 1);
        renderEvents();
    }
}

// EXPORTERS FUNCTIONS
function renderExporters() {
    const container = document.getElementById('exporters-list');
    container.innerHTML = '';
    document.getElementById('exporters-count').textContent = currentExporters.length;

    if (currentExporters.length === 0) {
        container.innerHTML = `
            <div class="text-center p-4 border rounded-3 bg-light text-muted">
                <i class="fas fa-store-slash fa-2x mb-2 text-secondary"></i>
                <p class="mb-2 fw-bold">No Exporters Listed Yet</p>
                <button type="button" class="btn btn-sm btn-primary" onclick="addExporterRow()">+ Add Exporter</button>
            </div>
        `;
        document.getElementById('json_exporters').value = JSON.stringify([]);
        return;
    }

    currentExporters.forEach((exp, i) => {
        const card = document.createElement('div');
        card.className = 'exporter-row-card';
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary text-white fw-bold">Exporter #${i + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeExporter(${i})" title="Remove Exporter">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="small text-muted fw-bold">Company / Society Name</label>
                    <input type="text" class="form-control form-control-sm" value="${exp.name || ''}" onchange="updateExporter(${i}, 'name', this.value)" placeholder="e.g. Deccan Spinners Cooperative" required>
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Proprietor / Contact Person</label>
                    <input type="text" class="form-control form-control-sm" value="${exp.owner || ''}" onchange="updateExporter(${i}, 'owner', this.value)" placeholder="e.g. Sangram Patil">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted fw-bold">Est. Year</label>
                    <input type="text" class="form-control form-control-sm" value="${exp.est || ''}" onchange="updateExporter(${i}, 'est', this.value)" placeholder="e.g. 1998">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Export Products (comma-separated)</label>
                    <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.prods) ? exp.prods.join(', ') : (exp.prods || '')}" onchange="updateExporterArray(${i}, 'prods', this.value)" placeholder="Cotton Yarn, Grey Fabric">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Export Destinations (comma-separated)</label>
                    <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.exports) ? exp.exports.join(', ') : (exp.exports || '')}" onchange="updateExporterArray(${i}, 'exports', this.value)" placeholder="China, Bangladesh, UAE">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">Certifications (comma-separated)</label>
                    <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.certs) ? exp.certs.join(', ') : (exp.certs || '')}" onchange="updateExporterArray(${i}, 'certs', this.value)" placeholder="ISO 9001, OEKO-TEX">
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    document.getElementById('json_exporters').value = JSON.stringify(currentExporters);
}

function addExporterRow() {
    currentExporters.push({
        name: 'New Textile Exporter',
        owner: 'Promoter Name',
        est: '2010',
        prods: ['Fabrics', 'Yarn'],
        exports: ['USA', 'Europe'],
        certs: ['ISO 9001']
    });
    renderExporters();
}

function updateExporter(i, field, val) {
    if (currentExporters[i]) {
        currentExporters[i][field] = val;
        document.getElementById('json_exporters').value = JSON.stringify(currentExporters);
    }
}

function updateExporterArray(i, field, rawStr) {
    if (currentExporters[i]) {
        const items = rawStr.split(',').map(s => s.trim()).filter(Boolean);
        currentExporters[i][field] = items;
        document.getElementById('json_exporters').value = JSON.stringify(currentExporters);
    }
}

function removeExporter(i) {
    if (confirm('Delete this exporter?')) {
        currentExporters.splice(i, 1);
        renderExporters();
    }
}

// COMPLETE PAYLOAD SERIALIZATION BEFORE SUBMIT
function prepareClusterPayload() {
    document.getElementById('json_highlights').value = JSON.stringify(currentHighlights);
    document.getElementById('json_events').value = JSON.stringify(currentEvents);
    document.getElementById('json_exporters').value = JSON.stringify(currentExporters);
    document.getElementById('json_chart_curr').value = JSON.stringify(currentChartCurr);
    document.getElementById('json_chart_prev').value = JSON.stringify(currentChartPrev);

    const payload = {
        name: document.querySelector('input[name="name"]').value,
        state: document.querySelector('input[name="state"]').value,
        spec: document.querySelector('input[name="spec"]').value,
        era: document.querySelector('input[name="era"]').value,
        units: document.querySelector('input[name="units"]').value,
        exportVal: document.querySelector('input[name="exportVal"]').value,
        workforce: document.querySelector('input[name="workforce"]').value,
        dests: document.querySelector('input[name="dests"]').value,
        desc: document.querySelector('textarea[name="desc"]').value,
        address: document.querySelector('input[name="address"]').value,
        phone: document.querySelector('input[name="phone"]').value,
        email: document.querySelector('input[name="email"]').value,
        mapUrl: document.querySelector('input[name="mapUrl"]').value,
        sections_active: {
            stats: document.getElementById('sec_stats').checked,
            overview: document.getElementById('sec_overview').checked,
            highlights: document.getElementById('sec_highlights').checked,
            chart: document.getElementById('sec_chart').checked,
            exporters: document.getElementById('sec_exporters').checked,
            facilities: document.getElementById('sec_facilities').checked,
            events: document.getElementById('sec_events').checked,
            office: document.getElementById('sec_office').checked
        },
        highlights: currentHighlights,
        events: currentEvents,
        exporters: currentExporters,
        chartCurr: currentChartCurr,
        chartPrev: currentChartPrev
    };

    document.getElementById('json_cluster_payload').value = JSON.stringify(payload);
}
</script>
@endsection
