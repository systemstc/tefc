@extends('layouts.portal')

@section('title', 'Data Entry Operators — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    @if(Auth::user()->isMainExporter())
        <li><a href="{{ route('exporter.operators.index') }}" class="active"><i class="fas fa-users-cog"></i> Data Entry Operators</a></li>
    @endif
    <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--primary);">Data Entry Operators (DEO)</h2>
        <p class="text-muted mb-0">Authorize subordinate staff to enter products & catalogue specifications. Entries require your acknowledgment before publishing.</p>
    </div>
    <button class="btn btn-primary px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#addOperatorModal">
        <i class="fas fa-user-plus me-2"></i> Add New Operator
    </button>
</div>

<!-- Alert for Pending Reviews -->
@if(isset($pendingReviewsCount) && $pendingReviewsCount > 0)
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span class="fs-4">⚠️</span>
            <div>
                <strong class="d-block text-dark">Action Required: {{ $pendingReviewsCount }} Product(s) Awaiting Your Acknowledgment</strong>
                <small class="text-muted">Data Entry Operators have submitted new or modified products. Acknowledge them to publish live immediately without HO admin approval.</small>
            </div>
        </div>
        <a href="{{ route('seller.products.index') }}" class="btn btn-warning btn-sm fw-bold px-3">
            Review Products <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2">
        <i class="fas fa-check-circle fs-5"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="portal-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3" style="background: rgba(108, 21, 63, 0.1); color: var(--primary);">
                <i class="fas fa-users fs-4"></i>
            </div>
            <div>
                <div class="text-muted small text-uppercase fw-bold">Total Operators</div>
                <div class="h3 fw-bold mb-0 text-dark">{{ $operators->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="portal-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3" style="background: rgba(46, 125, 50, 0.1); color: #2e7d32;">
                <i class="fas fa-user-check fs-4"></i>
            </div>
            <div>
                <div class="text-muted small text-uppercase fw-bold">Active Operators</div>
                <div class="h3 fw-bold mb-0 text-success">{{ $operators->where('status', 'active')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="portal-card p-3 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3" style="background: rgba(248, 184, 3, 0.15); color: #b78103;">
                <i class="fas fa-clipboard-check fs-4"></i>
            </div>
            <div>
                <div class="text-muted small text-uppercase fw-bold">Pending Acknowledgment</div>
                <div class="h3 fw-bold mb-0 text-warning">{{ $pendingReviewsCount ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Operators Table -->
<div class="portal-card">
    <div class="portal-card-header d-flex justify-content-between align-items-center">
        <h3>Assigned Operators for {{ Auth::user()->effective_exporter_profile?->company_name ?? Auth::user()->name }}</h3>
        <span class="badge bg-light text-dark border">Higher Authority: {{ Auth::user()->name }} ({{ Auth::user()->designation ?: 'Authorized Signatory' }})</span>
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Operator Details</th>
                    <th>Role & Designation</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Products Added</th>
                    <th>Created On</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operators as $operator)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                {{ strtoupper(substr($operator->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $operator->name }}</div>
                                <div class="small text-muted">{{ $operator->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary border">
                            <i class="fas fa-keyboard me-1"></i> {{ $operator->designation ?: 'Data Entry Operator' }}
                        </span>
                    </td>
                    <td class="small">{{ $operator->phone ?: '—' }}</td>
                    <td>
                        @if($operator->status === 'active')
                            <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="status-badge status-rejected"><i class="fas fa-ban"></i> Inactive</span>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold">{{ $operator->created_products_count }}</span>
                    </td>
                    <td class="small text-muted">{{ $operator->created_at->format('d M, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('exporter.operators.toggle', $operator) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="action-btn" title="{{ $operator->status === 'active' ? 'Deactivate Operator' : 'Activate Operator' }}">
                                    @if($operator->status === 'active')
                                        <i class="fas fa-user-slash text-warning"></i>
                                    @else
                                        <i class="fas fa-user-check text-success"></i>
                                    @endif
                                </button>
                            </form>
                            <form action="{{ route('exporter.operators.destroy', $operator) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove operator \'{{ $operator->name }}\'? They will no longer be able to log in.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" title="Delete Operator">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted mb-3">No Data Entry Operators created yet.</div>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addOperatorModal">
                            <i class="fas fa-user-plus me-1"></i> Add First Operator
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Operator Modal -->
<div class="modal fade" id="addOperatorModal" tabindex="-1" aria-labelledby="addOperatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold" id="addOperatorModalLabel" style="color: var(--primary);">
                    <i class="fas fa-user-plus me-2"></i> Add Data Entry Operator
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('exporter.operators.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4">
                        This creates a subordinate operator user for <strong>{{ Auth::user()->effective_exporter_profile?->company_name ?? Auth::user()->name }}</strong>. They will be able to input catalogue products, which you will then review and acknowledge to publish.
                    </p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Operator Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Ramesh Patel" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Login Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="operator@company.com" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Contact Phone (Optional)</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91 98765 00000" value="{{ old('phone') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Designation</label>
                        <input type="text" name="designation" class="form-control" value="Data Entry Operator" placeholder="Data Entry Operator">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Create Operator Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
