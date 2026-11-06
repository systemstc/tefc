@extends('layouts.portal')

@section('title', $centre['name'] . ' TEFC Centre — Exporter Applications')

@section('sidebar')
    <li><a href="{{ route('center.dashboard') }}"><i class="fas fa-landmark"></i> Centre Overview</a></li>
    <li><a href="{{ route('center.applications') }}" class="active"><i class="fas fa-user-check"></i> Exporter Applications</a></li>
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
    <div style="background: linear-gradient(135deg, #6c1340 0%, #3f0923 100%); color: #fff; border-radius: 14px; padding: 1rem 1.6rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 1.4rem;">🏛️</span>
            <div>
                <strong style="font-size: 0.95rem;">Head Office Mode: Managing {{ $centre['name'] }} Centre Applications</strong>
                <div style="font-size: 0.8rem; opacity: 0.85;">Actions taken here are recorded as {{ $centre['name'] }} TEFC Facilitation Centre validations.</div>
            </div>
        </div>
        <a href="{{ route('admin.centre.exit') }}" class="btn btn-sm btn-light" style="font-weight: 700; color: var(--primary); border-radius: 8px;">
            Exit Centre View
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
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="portal-card mb-4">
    <div class="portal-card-header">
        <div>
            <h3>{{ $centre['name'] }} Exporter Applications</h3>
            <p class="text-muted small mb-0">Jurisdiction: {{ $centre['districts_label'] }} ({{ $centre['state'] }})</p>
        </div>
        <div>
            <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size: 0.85rem;">
                {{ $applications->total() }} Total Applications
            </span>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div style="padding: 1.2rem 2rem; background: #fafbfc; border-bottom: 1px solid var(--sky);">
        <form method="GET" action="{{ route('center.applications') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search company, contact, GSTIN, IEC, district..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('center.applications', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" 
                       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-weight: 600;">
                        All
                    </a>
                    <a href="{{ route('center.applications', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}" style="font-weight: 600;">
                        Pending
                    </a>
                    <a href="{{ route('center.applications', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}" 
                       class="btn btn-sm {{ request('status') === 'active' ? 'btn-success' : 'btn-outline-secondary' }}" style="font-weight: 600;">
                        Approved
                    </a>
                    <a href="{{ route('center.applications', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}" 
                       class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}" style="font-weight: 600;">
                        Rejected
                    </a>
                </div>
            </div>

            <div class="col-md-3 text-md-end">
                <button type="submit" class="btn btn-outline-primary btn-sm px-3" style="font-weight: 700; border-radius: 8px;">
                    Apply Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('center.applications') }}" class="btn btn-link btn-sm text-muted text-decoration-none">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Enterprise Details</th>
                    <th>Statutory Identifiers</th>
                    <th>Cluster & Segment</th>
                    <th>Turnover / Machinery</th>
                    <th>Status</th>
                    <th class="text-end">Validation Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $user)
                    @php
                        $profile = $user->exporterProfile;
                    @endphp
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background: var(--sky2); color: var(--primary); font-weight: 800;">
                                    {{ substr($profile->company_name ?? $user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $profile->company_name ?? 'N/A' }}</div>
                                    <div class="text-muted small">
                                        {{ $user->name }} &bull; {{ $user->email }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        <i class="fas fa-phone me-1"></i> {{ $profile->contact_phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-bold" style="font-family: monospace; font-size: 0.85rem; color: #1a202c;">
                                GSTIN: {{ $profile->gst_no ?? 'N/A' }}
                            </div>
                            <div class="small text-muted" style="font-family: monospace;">
                                IEC: {{ $profile->iec_code ?? 'N/A' }}
                            </div>
                            @if(!empty($profile->gst_legal_name))
                                <div class="mt-1" style="font-size: 0.73rem; color: #475569;" title="GST Legal Entity: {{ $profile->gst_legal_name }}">
                                    <i class="fas fa-landmark text-secondary me-1"></i> {{ Str::limit($profile->gst_legal_name, 22) }}
                                </div>
                            @endif
                            <div class="mt-1">
                                @if($profile->gst_ownership_verified)
                                    <span class="badge bg-success" style="font-size: 0.68rem;" title="Applicant matches GST Promoters / Signatories">
                                        <i class="fas fa-shield-alt me-1"></i> Signatory Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 0.68rem;" title="Verify applicant authority in uploaded REG-06">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Verify REG-06
                                    </span>
                                @endif
                            </div>
                            @if(!empty($profile->certificate_path))
                                <a href="{{ asset('storage/' . $profile->certificate_path) }}" target="_blank" class="badge bg-light text-primary border mt-1 text-decoration-none" style="font-size: 0.75rem;">
                                    <i class="fas fa-file-pdf me-1"></i> View Business Proof / REG-06
                                </a>
                            @else
                                <span class="text-muted small" style="font-size: 0.75rem;">No file uploaded</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge" style="background: #edf2f7; color: #2d3748; font-weight: 700; font-size: 0.78rem;">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $profile->district ?? 'N/A' }}, {{ $profile->state ?? '' }}
                            </span>
                            <div class="small fw-semibold mt-1" style="color: var(--primary);">
                                {{ $profile->textile_segment ?? 'N/A' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                HSN: {{ $profile->main_product_hsn ?? 'N/A' }}
                            </div>
                        </td>

                        <td>
                            <div class="small fw-semibold text-dark">
                                Turnover: {{ $profile->annual_turnover ?? 'N/A' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                Cap: {{ $profile->installed_capacity ?? 'N/A' }} {{ $profile->capacity_unit ?? '' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                Applied: {{ $user->created_at->format('d M Y') }}
                            </div>
                        </td>

                        <td>
                            @if($user->status === 'pending')
                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($user->status === 'active')
                                <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Approved</span>
                            @else
                                <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                                @if(!empty($profile->rejection_reason))
                                    <div class="text-danger mt-1" style="font-size: 0.72rem; max-width: 140px; line-height: 1.2;" title="{{ $profile->rejection_reason }}">
                                        <em>"{{ Str::limit($profile->rejection_reason, 40) }}"</em>
                                    </div>
                                @endif
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <!-- Details Modal Trigger -->
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $user->id }}" title="View Full Enterprise Dossier" style="border-radius: 8px; font-weight: 600; font-size: 0.78rem;">
                                    <i class="fas fa-eye"></i> Details
                                </button>

                                <!-- Accept (Approve) Button -->
                                @if($user->status !== 'active')
                                    <form action="{{ route('center.applications.status', $user->id) }}" method="POST" onsubmit="return confirm('Confirm approval for {{ addslashes($profile->company_name ?? $user->name) }}?');" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 700; font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                                            <i class="fas fa-check me-1"></i> Accept
                                        </button>
                                    </form>
                                @endif

                                <!-- Reject Button (Opens Modal) -->
                                @if($user->status !== 'rejected')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $user->id }}" style="border-radius: 8px; font-weight: 700; font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                @endif
                            </div>

                            <!-- Enterprise Details Modal -->
                            <div class="modal fade text-start" id="detailModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 20px; border: 1px solid var(--border); overflow: hidden;">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #fdfbfd 0%, var(--sky2) 100%); border-bottom: 1px solid var(--sky);">
                                            <div>
                                                <h5 class="modal-title fw-bold" style="color: var(--primary);">{{ $profile->company_name ?? $user->name }}</h5>
                                                <span class="text-muted small">Application ID #{{ $user->id }} &bull; TEFC Centre: {{ $centre['name'] }}</span>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Higher Authority / Applicant</label>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <div class="text-primary small fw-semibold"><i class="fas fa-id-badge me-1"></i> {{ $user->designation ?? 'Authorized Signatory' }}</div>
                                                    <div class="small text-muted">{{ $user->email }} &bull; {{ $profile->contact_phone ?? 'N/A' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Registration Status</label>
                                                    <div>
                                                        @if($user->status === 'pending')
                                                            <span class="status-badge status-pending">Pending Review</span>
                                                        @elseif($user->status === 'active')
                                                            <span class="status-badge status-active">Approved & Active</span>
                                                        @else
                                                            <span class="status-badge status-rejected">Rejected</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12"><hr class="my-2"></div>

                                                <div class="col-md-4">
                                                    <label class="text-muted small fw-bold text-uppercase">GSTIN</label>
                                                    <div class="fw-bold font-monospace">{{ $profile->gst_no ?? 'N/A' }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-muted small fw-bold text-uppercase">IEC Code</label>
                                                    <div class="fw-bold font-monospace">{{ $profile->iec_code ?? 'N/A' }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-muted small fw-bold text-uppercase">Year Established</label>
                                                    <div class="fw-bold">{{ $profile->year_of_establishment ?? 'N/A' }}</div>
                                                </div>

                                                <!-- GSTIN Verification & Ownership Audit Dossier -->
                                                <div class="col-12">
                                                    <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 1.1rem 1.3rem;">
                                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span style="font-size: 1.1rem;">🏛️</span>
                                                                <strong style="color: #0f172a; font-size: 0.9rem;">Official GSTIN Compliance &amp; Authority Cross-Check</strong>
                                                            </div>
                                                            <div>
                                                                @if($profile->gst_ownership_verified)
                                                                    <span class="badge bg-success"><i class="fas fa-shield-alt me-1"></i> Signatory Verified</span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Audit REG-06 Required</span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row g-2 small">
                                                            <div class="col-md-6">
                                                                <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">GST Registered Legal Name:</span>
                                                                <strong class="text-dark">{{ $profile->gst_legal_name ?? $profile->company_name ?? 'N/A' }}</strong>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Business Constitution:</span>
                                                                <span>{{ $profile->gst_constitution ?? 'N/A' }} (Status: <strong class="text-success">{{ $profile->gst_status ?? 'Active' }}</strong>)</span>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Promoters / Signatories on Record:</span>
                                                                <span class="text-dark fw-semibold">
                                                                    @if(!empty($profile->gst_signatories) && is_array($profile->gst_signatories))
                                                                        {{ implode(', ', $profile->gst_signatories) }}
                                                                    @else
                                                                        {{ $profile->gst_legal_name ?? 'Sole Proprietor / Director' }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="col-12 mt-2 pt-2 border-top">
                                                                @if($profile->gst_ownership_verified)
                                                                    <div class="p-2 rounded bg-success-subtle text-success border border-success-subtle">
                                                                        <i class="fas fa-check-circle me-1"></i>
                                                                        <strong>Applicant Validated:</strong> Applicant "{{ $user->name }}" ({{ $user->designation ?? 'Higher Authority' }}) matched registered signatory/promoter record: <em>"{{ $profile->gst_matched_signatory }}"</em>. Prefilled GST records were locked from official taxpayer database.
                                                                    </div>
                                                                @else
                                                                    <div class="p-2 rounded bg-warning-subtle text-dark border border-warning-subtle">
                                                                        <i class="fas fa-info-circle text-warning me-1"></i>
                                                                        <strong>Officer Audit Guidance:</strong> Applicant "{{ $user->name }}" does not directly match the promoter list above. Please inspect the attached Form GST REG-06 / Board Resolution to confirm authorization before clicking <strong>Accept</strong>.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-12 mt-1">
                                                                <div class="small text-muted">
                                                                    <i class="fas fa-balance-scale text-primary me-1"></i> <strong>Statutory Undertaking:</strong> Applicant has affirmed corporate representation under penalty of law.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="text-muted small fw-bold text-uppercase">Factory / Plant Address</label>
                                                    <div class="p-2 rounded bg-light" style="font-size: 0.9rem;">{{ $profile->factory_address ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Textile Segment & HSN</label>
                                                    <div class="fw-bold">{{ $profile->textile_segment ?? 'N/A' }} (HSN: {{ $profile->main_product_hsn ?? 'N/A' }})</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Installed Capacity & Production</label>
                                                    <div class="fw-bold">{{ $profile->installed_capacity ?? 'N/A' }} {{ $profile->capacity_unit ?? '' }} (Monthly: {{ $profile->monthly_production ?? 'N/A' }})</div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="text-muted small fw-bold text-uppercase">Machinery & Technology</label>
                                                    <div class="p-2 rounded bg-light small">{{ $profile->major_machineries ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Annual Turnover</label>
                                                    <div class="fw-bold">{{ $profile->annual_turnover ?? 'N/A' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-muted small fw-bold text-uppercase">Export Experience</label>
                                                    <div class="fw-bold">
                                                        {{ ($profile->is_exporter ?? 'N') === 'Y' ? 'Active Exporter' : 'Domestic / Aspiring Exporter' }}
                                                        @if(!empty($profile->export_turnover))
                                                            <span class="text-muted small">({{ $profile->export_turnover }})</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if(!empty($profile->certificate_path))
                                                    <div class="col-12 mt-2">
                                                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Uploaded Business Proof / Certificate</label>
                                                        <a href="{{ asset('storage/' . $profile->certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-weight: 700; border-radius: 8px;">
                                                            <i class="fas fa-file-pdf me-1"></i> Open Certificate Document in New Window
                                                        </a>
                                                    </div>
                                                @endif

                                                @if($user->status === 'rejected' && !empty($profile->rejection_reason))
                                                    <div class="col-12 mt-2">
                                                        <div class="alert alert-danger mb-0" style="border-radius: 10px;">
                                                            <strong>Rejection Remarks by Centre Officer:</strong>
                                                            <p class="mb-0 mt-1" style="font-size: 0.88rem;">{{ $profile->rejection_reason }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="background: #f8f9fa;">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Reason Modal -->
                            <div class="modal fade text-start" id="rejectModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 20px; border: 1px solid var(--border);">
                                        <form action="{{ route('center.applications.status', $user->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="fas fa-exclamation-triangle me-2"></i> Reject Application
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <p style="font-size: 0.9rem; color: #4a5568;">
                                                    You are rejecting the exporter onboarding application for:
                                                    <strong class="d-block text-dark">{{ $profile->company_name ?? $user->name }}</strong>
                                                </p>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-uppercase">Reason for Rejection / Remarks <span class="text-danger">*</span></label>
                                                    <textarea name="rejection_reason" class="form-control" rows="4" placeholder="State specific reason for rejection (e.g. Invalid GST document, location discrepancy, missing factory verification, etc.)..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                                    Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No applications match your filter criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
