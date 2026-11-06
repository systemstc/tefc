@extends('layouts.portal')

@section('title', $centre['name'] . ' TEFC Centre — Performance & KPI Management')

@section('sidebar')
    <li><a href="{{ route('center.dashboard') }}"><i class="fas fa-landmark"></i> Centre Overview</a></li>
    <li>
        <a href="{{ route('center.applications') }}">
            <i class="fas fa-user-check"></i> Exporter Applications
        </a>
    </li>
    <li><a href="{{ route('center.kpis') }}" class="active"><i class="fas fa-chart-line"></i> Performance &amp; KPIs</a></li>
    <li><a href="{{ route('center.cluster.edit') }}" class="{{ request()->routeIs('center.cluster.*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Cluster Page Content</a></li>
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
                <h5 style="margin: 0; font-weight: 800; font-size: 1.1rem; letter-spacing: 0.3px;">Head Office (HO) Direct Access Mode</h5>
                <p style="margin: 0; opacity: 0.85; font-size: 0.88rem;">Currently managing <strong>{{ $centre['name'] }} Centre</strong> KPI performance records.</p>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- HEADER BANNER -->
<div class="portal-card mb-4" style="background: linear-gradient(135deg, #6c1340 0%, #4a0e2b 100%); color: #fff;">
    <div style="padding: 2rem 2.2rem;">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge" style="background: #f8b803; color: #1a1a2e; font-weight: 800; font-size: 0.82rem; padding: 4px 10px; border-radius: 20px;">
                        CLUSTER KPI FEED CONTROL ROOM
                    </span>
                    <span style="opacity: 0.8; font-size: 0.9rem;"><i class="fas fa-map-marker-alt me-1"></i> {{ $centre['state'] }} &bull; {{ $centre['districts_label'] }}</span>
                </div>
                <h1 style="color: #fff; font-size: 2rem; font-weight: 900; margin: 0 0 6px 0;">
                    {{ $centre['name'] }} Facilitation Centre Performance Data
                </h1>
                <p style="margin: 0; opacity: 0.9; max-width: 850px; font-size: 0.95rem;">
                    Feed and manage individual operational records across all 6 Key Performance Indicators. All active entries automatically calculate your centre's cumulative volume and normalized radar benchmarks in real-time.
                </p>
            </div>
            <div>
                <a href="{{ url('/kpi-dashboard') }}" target="_blank" class="btn btn-warning fw-bold px-3 py-2" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(248,184,3,0.35);">
                    <i class="fas fa-chart-pie me-1"></i> View Live National Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- LIVE KPI SUMMARY CARDS FOR THIS CENTRE -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #6c1340;">
            <div class="text-muted small fw-bold text-uppercase">1. ManPower</div>
            <div class="h2 fw-bold mb-1" style="color: #6c1340;">{{ $kpiSummary['active_staff'] }}</div>
            <div class="small text-muted">Active Advisors</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #b38e5d;">
            <div class="text-muted small fw-bold text-uppercase">2. Exporter Base</div>
            <div class="h2 fw-bold mb-1" style="color: #b38e5d;">{{ $kpiSummary['unique_advised'] }}</div>
            <div class="small text-muted">Exporters Advised</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #1e7e44;">
            <div class="text-muted small fw-bold text-uppercase">3. Training</div>
            <div class="h2 fw-bold mb-1" style="color: #1e7e44;">{{ $kpiSummary['exporters_trained'] }}</div>
            <div class="small text-muted">{{ $kpiSummary['trainings_count'] }} Sessions</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #d97706;">
            <div class="text-muted small fw-bold text-uppercase">4. Newsletters</div>
            <div class="h2 fw-bold mb-1" style="color: #d97706;">{{ $kpiSummary['publications_count'] }}</div>
            <div class="small text-muted">Trade Advisories</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #1464a0;">
            <div class="text-muted small fw-bold text-uppercase">5. Queries &amp; SLA</div>
            <div class="h2 fw-bold mb-1" style="color: #1464a0;">{{ $kpiSummary['sla_percent'] }}%</div>
            <div class="small text-muted">{{ $kpiSummary['queries_resolved'] }} / {{ $kpiSummary['queries_received'] }} Resolved</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="portal-card p-3 text-center h-100" style="border-top: 4px solid #7c3aed;">
            <div class="text-muted small fw-bold text-uppercase">6. Penetration</div>
            <div class="h2 fw-bold mb-1" style="color: #7c3aed;">{{ $kpiSummary['coverage_percent'] }}%</div>
            <div class="small text-muted">MSME Reach</div>
        </div>
    </div>
</div>

<!-- KPI DATA FEED NAVIGATION TABS -->
<div class="portal-card mb-4">
    <div class="p-3 border-bottom bg-light">
        <ul class="nav nav-pills" id="kpiManagerTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold" id="tab-staff-link" data-bs-toggle="pill" data-bs-target="#tab-staff" type="button">
                    <i class="fas fa-user-tie me-1"></i> 1. ManPower (Staff)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="tab-advisories-link" data-bs-toggle="pill" data-bs-target="#tab-advisories" type="button">
                    <i class="fas fa-users me-1"></i> 2. Exporter Base &amp; Advisories
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="tab-trainings-link" data-bs-toggle="pill" data-bs-target="#tab-trainings" type="button">
                    <i class="fas fa-graduation-cap me-1"></i> 3. Training Programmes
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="tab-publications-link" data-bs-toggle="pill" data-bs-target="#tab-publications" type="button">
                    <i class="fas fa-newspaper me-1"></i> 4. Newsletters &amp; Advisories
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="tab-queries-link" data-bs-toggle="pill" data-bs-target="#tab-queries" type="button">
                    <i class="fas fa-headset me-1"></i> 5. Queries &amp; SLA
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="tab-profile-link" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button">
                    <i class="fas fa-bullseye me-1"></i> 6. Focus Area &amp; Target Base
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content p-4" id="kpiManagerTabContent">

        <!-- ============================================================= -->
        <!-- TAB 1: MANPOWER & STAFF -->
        <!-- ============================================================= -->
        <div class="tab-pane fade show active" id="tab-staff" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-user-tie me-2"></i> TEFC Advisory Personnel &amp; Staff</h4>
                    <p class="text-muted small mb-0">Record all operational officers, facilitators, and technical trade advisors deployed at this centre.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddStaff">
                    <i class="fas fa-plus me-1"></i> Add Staff Member
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Age</th>
                            <th>Designation</th>
                            <th>Education</th>
                            <th>Experience</th>
                            <th>Contact</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                    @if($member->joining_date)
                                        <div class="small text-muted">Joined: {{ $member->joining_date->format('M Y') }}</div>
                                    @endif
                                </td>
                                <td>{{ $member->age ?? '—' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $member->designation }}</span></td>
                                <td>{{ $member->education ?? '—' }}</td>
                                <td>{{ $member->experience_years ? $member->experience_years . ' yrs' : '—' }}</td>
                                <td>
                                    <div class="small">{{ $member->email ?? '—' }}</div>
                                    <div class="small text-muted">{{ $member->phone ?? '' }}</div>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('center.kpis.staff.toggle', $member->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $member->status === 'active' ? 'btn-success' : 'btn-secondary' }}" title="Click to toggle Active/Inactive">
                                            {{ ucfirst($member->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('center.kpis.staff.delete', $member->id) }}" method="POST" onsubmit="return confirm('Remove this staff record?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No staff members added yet. Click <strong>Add Staff Member</strong> above to feed manpower data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- TAB 2: EXPORTER BASE & ADVISORIES -->
        <!-- ============================================================= -->
        <div class="tab-pane fade" id="tab-advisories" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-users me-2"></i> Exporter Advisory Logs &amp; Pipeline</h4>
                    <p class="text-muted small mb-0">Track advisory handholding provided to prospective exporters, dormant IEC holders, and active enterprises.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddAdvisory">
                    <i class="fas fa-plus me-1"></i> Log Advisory Session
                </button>
            </div>

            <!-- Auto-detected Exporter Pipeline Stats from Registration -->
            <div class="p-3 rounded mb-4" style="background: #fdf2f7; border: 1px solid #ebdcb9;">
                <div class="row text-center">
                    <div class="col-md-3 border-end">
                        <div class="small text-muted">Assigned Registered Exporters</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ $assignedExporters->count() }} Units</div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="small text-muted">Without IEC (Prospective)</div>
                        <div class="h4 fw-bold mb-0" style="color: #6c1340;">{{ $advisories->where('iec_status', 'no_iec')->count() }}</div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="small text-muted">Dormant / Inactive IEC</div>
                        <div class="h4 fw-bold mb-0 text-warning">{{ $advisories->where('iec_status', 'inactive_iec')->count() }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">New IEC Generated via TEFC</div>
                        <div class="h4 fw-bold mb-0 text-success">{{ $advisories->where('iec_status', 'new_iec')->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Company / Enterprise</th>
                            <th>IEC Category</th>
                            <th>Session Date</th>
                            <th>Advisory Topic</th>
                            <th>Outcome / Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advisories as $adv)
                            <tr>
                                <td><strong>{{ $adv->company_name }}</strong></td>
                                <td>
                                    @if($adv->iec_status === 'no_iec')
                                        <span class="badge bg-secondary">a. No IEC (Prospective)</span>
                                    @elseif($adv->iec_status === 'iec_no_export')
                                        <span class="badge bg-info text-dark">b. IEC No Export</span>
                                    @elseif($adv->iec_status === 'inactive_iec')
                                        <span class="badge bg-warning text-dark">c. Inactive IEC</span>
                                    @elseif($adv->iec_status === 'new_iec')
                                        <span class="badge bg-success">d. New IEC Generated</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Regular Exporter</span>
                                    @endif
                                </td>
                                <td>{{ $adv->session_date ? $adv->session_date->format('d M Y') : '—' }}</td>
                                <td>{{ $adv->advisory_topic }}</td>
                                <td class="small text-muted">{{ Str::limit($adv->outcome_notes, 60) }}</td>
                                <td class="text-end">
                                    <form action="{{ route('center.kpis.advisory.delete', $adv->id) }}" method="POST" onsubmit="return confirm('Delete this advisory log?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No advisory sessions logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $advisories->links() }}
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- TAB 3: TRAINING PROGRAMMES -->
        <!-- ============================================================= -->
        <div class="tab-pane fade" id="tab-trainings" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-graduation-cap me-2"></i> Training Programmes &amp; Buyer-Seller Meets</h4>
                    <p class="text-muted small mb-0">Record all export workshops, FTA masterclasses, compliance seminars, and BSM events.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddTraining">
                    <i class="fas fa-plus me-1"></i> Record Training / BSM
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Programme Title</th>
                            <th>Event Type</th>
                            <th>Date</th>
                            <th>Venue / Mode</th>
                            <th class="text-center">Exporters Trained</th>
                            <th>Key Topics Covered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainings as $tr)
                            <tr>
                                <td><strong>{{ $tr->title }}</strong></td>
                                <td>
                                    <span class="badge {{ $tr->type === 'buyer_seller_meet' ? 'bg-primary' : 'bg-success' }}">
                                        {{ $tr->type === 'buyer_seller_meet' ? 'Buyer-Seller Meet' : 'Training Programme' }}
                                    </span>
                                </td>
                                <td>{{ $tr->event_date ? $tr->event_date->format('d M Y') : '—' }}</td>
                                <td>{{ $tr->venue ?? 'Online / Hybrid' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark fs-6 border">{{ $tr->participants_count }}</span>
                                </td>
                                <td class="small text-muted">{{ Str::limit($tr->topics_covered, 50) }}</td>
                                <td class="text-end">
                                    <form action="{{ route('center.kpis.training.delete', $tr->id) }}" method="POST" onsubmit="return confirm('Delete this training record?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No training programmes recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- TAB 4: NEWSLETTERS & PUBLICATIONS -->
        <!-- ============================================================= -->
        <div class="tab-pane fade" id="tab-publications" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-newspaper me-2"></i> Newsletters, Advisories &amp; Trade Reports</h4>
                    <p class="text-muted small mb-0">Record all monthly trade intelligence bulletins, tariff alerts, and research notes published by this centre.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddPublication">
                    <i class="fas fa-plus me-1"></i> Add Publication
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Publication Title</th>
                            <th>Type</th>
                            <th>Issue Date</th>
                            <th>Sectoral Summary</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($publications as $pub)
                            <tr>
                                <td><strong>{{ $pub->title }}</strong></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucwords(str_replace('_', ' ', $pub->type)) }}
                                    </span>
                                </td>
                                <td>{{ $pub->issue_date ? $pub->issue_date->format('d M Y') : '—' }}</td>
                                <td class="small text-muted">{{ Str::limit($pub->summary, 80) }}</td>
                                <td class="text-end">
                                    <form action="{{ route('center.kpis.publication.delete', $pub->id) }}" method="POST" onsubmit="return confirm('Delete this publication?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No publications added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- TAB 5: QUERIES HANDLED & SLA -->
        <!-- ============================================================= -->
        <div class="tab-pane fade" id="tab-queries" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-headset me-2"></i> Exporter Queries &amp; SLA Compliance</h4>
                    <p class="text-muted small mb-0">Log trade queries received from cluster units and track SLA compliance (resolved within 48-72h).</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddQuery">
                    <i class="fas fa-plus me-1"></i> Log Exporter Query
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th>Company / Applicant</th>
                            <th>Subject / Inquiry</th>
                            <th>Category</th>
                            <th>Received Date</th>
                            <th>Resolved Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">SLA Compliant</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $qr)
                            <tr>
                                <td>
                                    <strong>{{ $qr->company_name }}</strong>
                                    @if($qr->contact_person)
                                        <div class="small text-muted">{{ $qr->contact_person }}</div>
                                    @endif
                                </td>
                                <td>{{ $qr->subject }}</td>
                                <td><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_', ' ', $qr->category)) }}</span></td>
                                <td>{{ $qr->received_date ? $qr->received_date->format('d M Y') : '—' }}</td>
                                <td>{{ $qr->resolved_date ? $qr->resolved_date->format('d M Y') : 'Pending' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $qr->status === 'resolved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($qr->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($qr->is_within_sla)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Overdue</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($qr->status === 'pending')
                                        <button class="btn btn-sm btn-outline-success me-1" onclick="openResolveModal({{ $qr->id }})">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                    @endif
                                    <form action="{{ route('center.kpis.query.delete', $qr->id) }}" method="POST" onsubmit="return confirm('Delete this query?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No queries logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $queries->links() }}
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- TAB 6: CLUSTER PROFILE & TARGET COVERAGE -->
        <!-- ============================================================= -->
        <div class="tab-pane fade" id="tab-profile" role="tabpanel">
            <div class="mb-3">
                <h4 class="fw-bold mb-1" style="color: #6c1340;"><i class="fas fa-bullseye me-2"></i> Cluster Target Profile &amp; MSME Base</h4>
                <p class="text-muted small mb-0">Define the registered MSME textile base in this cluster. This is used to compute KPI 6 (Penetration % = Exporters Advised / Target MSMEs).</p>
            </div>

            <form action="{{ route('center.kpis.profile.update') }}" method="POST" class="portal-card p-4" style="max-width: 700px;">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Designated Cluster Name</label>
                    <input type="text" class="form-control" value="{{ $profile->cluster_name }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">State Jurisdiction</label>
                    <input type="text" class="form-control" value="{{ $profile->state }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Covered Revenue Districts / Taluks</label>
                    <input type="text" class="form-control" value="{{ $profile->covered_districts }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Estimated Registered Textile MSMEs in Cluster</label>
                    <input type="number" name="total_target_msmes" class="form-control" value="{{ old('total_target_msmes', $profile->total_target_msmes) }}" required min="1">
                    <div class="form-text">Used as the benchmark denominator for cluster penetration coverage.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Cluster Sectoral Specialization</label>
                    <textarea name="specialization" class="form-control" rows="3">{{ old('specialization', $profile->specialization) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="fas fa-save me-1"></i> Update Cluster Profile
                </button>
            </form>
        </div>

    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: ADD STAFF -->
<!-- ================================================================= -->
<div class="modal fade" id="modalAddStaff" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('center.kpis.staff.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #6c1340; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Add TEFC Staff Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Ramesh Patel">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Age</label>
                        <input type="number" name="age" class="form-control" min="18" max="80" placeholder="e.g. 35">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control" min="0" max="60" placeholder="e.g. 8">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Designation *</label>
                    <input type="text" name="designation" class="form-control" required placeholder="e.g. Textile Facilitator &amp; Technical Advisor">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Education Qualification</label>
                    <input type="text" name="education" class="form-control" placeholder="e.g. B.Tech Textile Tech / MBA Foreign Trade">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="name@tefc.gov.in">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210">
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fw-bold">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active (Deployed)</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold">Save Staff Record</button>
            </div>
        </form>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: ADD ADVISORY SESSION -->
<!-- ================================================================= -->
<div class="modal fade" id="modalAddAdvisory" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('center.kpis.advisory.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #6c1340; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-handshake me-2"></i> Log Exporter Advisory Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Enterprise / Company Name *</label>
                    <input type="text" name="company_name" class="form-control" required placeholder="e.g. Surat Synthetic Silk Mills">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">IEC Category *</label>
                    <select name="iec_status" class="form-select" required>
                        <option value="no_iec">a. Prospective Exporter (Without IEC)</option>
                        <option value="iec_no_export">b. IEC Holder with No Export History</option>
                        <option value="inactive_iec">c. Inactive IEC Holder (No export in 2 yrs)</option>
                        <option value="new_iec">d. New IEC Generated through TEFC</option>
                        <option value="regular">Regular Active Exporter</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Session Date *</label>
                    <input type="date" name="session_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Advisory Topic / Scope *</label>
                    <input type="text" name="advisory_topic" class="form-control" required placeholder="e.g. DGFT Application, HS Code 5407, FTA Duty Benefits">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Session Outcome / Notes</label>
                    <textarea name="outcome_notes" class="form-control" rows="2" placeholder="Provided checklist of export documents and guided on customs filing."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold">Log Advisory</button>
            </div>
        </form>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: ADD TRAINING -->
<!-- ================================================================= -->
<div class="modal fade" id="modalAddTraining" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('center.kpis.training.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #6c1340; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-chalkboard-teacher me-2"></i> Record Training Session / BSM</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Programme Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Export Packaging &amp; Labeling Compliance Workshop">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Event Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="training_programme">Training Programme</option>
                            <option value="buyer_seller_meet">Buyer-Seller Meet (BSM)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Event Date *</label>
                        <input type="date" name="event_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Exporters Trained *</label>
                        <input type="number" name="participants_count" class="form-control" required min="1" placeholder="e.g. 45">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Venue</label>
                        <input type="text" name="venue" class="form-control" placeholder="e.g. TEFC Conference Hall">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Topics Covered</label>
                    <textarea name="topics_covered" class="form-control" rows="2" placeholder="Customs documentation, zero-defect quality, currency risk hedging..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold">Save Programme</button>
            </div>
        </form>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: ADD PUBLICATION -->
<!-- ================================================================= -->
<div class="modal fade" id="modalAddPublication" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('center.kpis.publication.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #6c1340; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-alt me-2"></i> Add Newsletter / Trade Advisory</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Publication Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Monthly Textile Trade Digest - Edition #12">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Publication Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="newsletter">Newsletter</option>
                            <option value="trade_advisory">Trade Advisory</option>
                            <option value="market_report">Market Report</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Issue Date *</label>
                        <input type="date" name="issue_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Summary / Key Themes</label>
                    <textarea name="summary" class="form-control" rows="2" placeholder="Highlights on EU ESG directives and Australia CECA concessions..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold">Save Publication</button>
            </div>
        </form>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: ADD QUERY -->
<!-- ================================================================= -->
<div class="modal fade" id="modalAddQuery" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('center.kpis.query.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #6c1340; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-question-circle me-2"></i> Log Exporter Query</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Company / Unit Name *</label>
                    <input type="text" name="company_name" class="form-control" required placeholder="e.g. Karur Home Tex Pvt Ltd">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="e.g. S. Kumar">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Category *</label>
                        <select name="category" class="form-select" required>
                            <option value="hsn_classification">HSN Classification</option>
                            <option value="compliance">Compliance &amp; Quality</option>
                            <option value="documentation">Export Documentation</option>
                            <option value="incentives">Duty Drawback / RoDTEP</option>
                            <option value="other">Other Trade Advisory</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject / Query Query *</label>
                    <input type="text" name="subject" class="form-control" required placeholder="e.g. HS Code clarification for dyed poly-cotton blend">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Received Date *</label>
                        <input type="date" name="received_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Initial Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="resolved">Resolved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_within_sla" id="chkSla" value="1" checked>
                    <label class="form-check-label fw-bold" for="chkSla">
                        Resolved within SLA (Prescribed timeline / 48-72h)
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Resolution Notes</label>
                    <textarea name="resolution_notes" class="form-control" rows="2" placeholder="Provided clarification and DGFT public notice link..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-bold">Save Query</button>
            </div>
        </form>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL: RESOLVE QUERY (QUICK ACTION) -->
<!-- ================================================================= -->
<div class="modal fade" id="modalResolveQuery" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formResolveQuery" method="POST" class="modal-content">
            @csrf
            <div class="modal-header" style="background: #1e7e44; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i> Resolve Query</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Resolution Date *</label>
                    <input type="date" name="resolved_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_within_sla" id="chkResolveSla" value="1" checked>
                    <label class="form-check-label fw-bold" for="chkResolveSla">
                        Resolved within SLA (Prescribed timeline / 48-72h)
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Resolution Clarification / Notes</label>
                    <textarea name="resolution_notes" class="form-control" rows="3" required placeholder="Describe advisory steps given to the exporter..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success fw-bold">Complete Resolution</button>
            </div>
        </form>
    </div>
</div>

<script>
function openResolveModal(queryId) {
    const form = document.getElementById('formResolveQuery');
    form.action = "{{ url('/center/kpis/query') }}/" + queryId + "/resolve";
    const modal = new bootstrap.Modal(document.getElementById('modalResolveQuery'));
    modal.show();
}
</script>
@endsection
