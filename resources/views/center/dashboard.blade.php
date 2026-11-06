@extends('layouts.portal')

@section('title', $centre['name'] . ' TEFC Centre — Cluster Dashboard')

@section('sidebar')
    <li><a href="{{ route('center.dashboard') }}" class="active"><i class="fas fa-landmark"></i> Centre Overview</a></li>
    <li>
        <a href="{{ route('center.applications') }}">
            <i class="fas fa-user-check"></i> Exporter Applications
            @if(($stats['pending_applications'] ?? 0) > 0)
                <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.72rem; border-radius: 12px; padding: 2px 8px;">{{ $stats['pending_applications'] }}</span>
            @endif
        </a>
    </li>
    <li><a href="{{ route('center.kpis') }}"><i class="fas fa-chart-line"></i> Performance &amp; KPIs</a></li>
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
                <h5 style="margin: 0; font-weight: 800; font-size: 1.1rem; letter-spacing: 0.3px;">Head Office (HO) Centre Direct Access Mode</h5>
                <p style="margin: 0; opacity: 0.85; font-size: 0.88rem;">Currently managing <strong>{{ $centre['name'] }} Centre</strong> cluster with full officer administrative authority.</p>
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

<!-- Centre Jurisdiction Header Card -->
<div class="portal-card mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #fbf5f8 100%); border-left: 5px solid var(--primary);">
    <div style="padding: 1.8rem 2rem;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="badge" style="background: var(--primary); color: #fff; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 0.35rem 0.8rem; border-radius: 8px;">
                        Regional Centre
                    </span>
                    <span class="text-muted small"><i class="fas fa-envelope me-1"></i> {{ $centre['email'] }}</span>
                </div>
                <h2 style="color: var(--primary); font-weight: 900; margin: 0 0 0.5rem 0; font-size: 1.85rem;">
                    {{ $centre['name'] }} Facilitation Centre
                </h2>
                <div style="font-size: 0.95rem; color: #4a5568;">
                    <strong>State Jurisdiction:</strong> {{ $centre['state'] }} &nbsp;|&nbsp;
                    <strong>Covered Districts & Taluks:</strong> <span style="color: var(--primary-dark); font-weight: 600;">{{ $centre['districts_label'] }}</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('center.applications') }}" class="btn" style="background: var(--primary); color: #fff; font-weight: 700; border-radius: 10px; padding: 0.75rem 1.4rem; box-shadow: 0 4px 14px rgba(108, 21, 63, 0.2);">
                    <i class="fas fa-tasks me-2"></i> Review Applications
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Key Performance Indicators -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="portal-card" style="padding: 1.5rem; border-top: 4px solid #3182ce;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #718096; letter-spacing: 0.5px;">Total Applications</div>
                    <h3 style="font-size: 2.2rem; font-weight: 900; color: #2d3748; margin: 0.4rem 0 0 0;">{{ $stats['total_applications'] }}</h3>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #ebf8ff; color: #3182ce; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Registered in this cluster</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="portal-card" style="padding: 1.5rem; border-top: 4px solid #dd6b20;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #718096; letter-spacing: 0.5px;">Pending Validation</div>
                    <h3 style="font-size: 2.2rem; font-weight: 900; color: #c05621; margin: 0.4rem 0 0 0;">{{ $stats['pending_applications'] }}</h3>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #feebc8; color: #dd6b20; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Awaiting Centre officer action</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="portal-card" style="padding: 1.5rem; border-top: 4px solid #38a169;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #718096; letter-spacing: 0.5px;">Approved Exporters</div>
                    <h3 style="font-size: 2.2rem; font-weight: 900; color: #276749; margin: 0.4rem 0 0 0;">{{ $stats['approved_applications'] }}</h3>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #c6f6d5; color: #38a169; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Validated & portal active</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="portal-card" style="padding: 1.5rem; border-top: 4px solid #805ad5;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #718096; letter-spacing: 0.5px;">Cluster Products</div>
                    <h3 style="font-size: 2.2rem; font-weight: 900; color: #553c9e; margin: 0.4rem 0 0 0;">{{ $stats['total_products'] }}</h3>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #faf5ff; color: #805ad5; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Products in online catalogue</div>
        </div>
    </div>
</div>

<!-- Recent Applications Table -->
<div class="portal-card">
    <div class="portal-card-header">
        <div>
            <h3>Recent Exporter Registrations</h3>
            <p class="text-muted small mb-0">Latest applications received within {{ $centre['name'] }} jurisdiction</p>
        </div>
        <a href="{{ route('center.applications') }}" class="btn btn-sm btn-outline-secondary" style="font-weight: 700; border-radius: 8px;">
            View All Applications ({{ $stats['total_applications'] }}) &rarr;
        </a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Company / Contact Person</th>
                    <th>GSTIN / IEC</th>
                    <th>District / Segment</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th class="text-end">Validation Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentApplications as $applicant)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="background: var(--sky2); color: var(--primary); font-weight: 800;">
                                    {{ substr($applicant->exporterProfile->company_name ?? $applicant->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $applicant->exporterProfile->company_name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $applicant->name }} &bull; {{ $applicant->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold" style="font-family: monospace; font-size: 0.85rem; color: #2d3748;">
                                {{ $applicant->exporterProfile->gst_no ?? 'N/A' }}
                            </div>
                            <div class="text-muted small">IEC: {{ $applicant->exporterProfile->iec_code ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="font-size: 0.78rem;">
                                {{ $applicant->exporterProfile->district ?? 'N/A' }}
                            </span>
                            <div class="small text-muted mt-1">{{ $applicant->exporterProfile->textile_segment ?? 'General' }}</div>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $applicant->created_at->format('d M, Y') }}</span>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $applicant->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            @if($applicant->status === 'pending')
                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending Validation</span>
                            @elseif($applicant->status === 'active')
                                <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Approved</span>
                            @else
                                <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('center.applications', ['search' => $applicant->email]) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 700; font-size: 0.8rem; padding: 0.35rem 0.8rem;">
                                <i class="fas fa-clipboard-check me-1"></i> Review & Action
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No exporter applications received for {{ $centre['name'] }} Centre yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
