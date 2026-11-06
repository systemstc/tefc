@extends('layouts.portal')

@section('title', 'Admin Dashboard — TEFC')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="portal-card p-4 text-center">
            <div class="text-muted small text-uppercase fw-bold mb-2">Total Users</div>
            <div class="h2 fw-bold text-primary">{{ $stats['users'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="portal-card p-4 text-center">
            <div class="text-muted small text-uppercase fw-bold mb-2">Approved Products</div>
            <div class="h2 fw-bold text-success">{{ $stats['products'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="portal-card p-4 text-center">
            <div class="text-muted small text-uppercase fw-bold mb-2">Buyer Inquiries</div>
            <div class="h2 fw-bold text-info">{{ $stats['inquiries'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="portal-card p-4 text-center">
            <div class="text-muted small text-uppercase fw-bold mb-2">Platform Traffic</div>
            <div class="h2 fw-bold text-danger">{{ $stats['total_views'] ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- TEFC Centres Cluster Matrix & Direct Access -->
<div class="portal-card mb-5">
    <div class="portal-card-header">
        <div>
            <h3 style="color: var(--primary); font-weight: 800;">🏛️ TEFC Regional Centres — Cluster Overview & Direct Login</h3>
            <p class="text-muted small mb-0">Head Office direct access to individual facilitation centres across designated state clusters</p>
        </div>
        <span class="badge bg-primary px-3 py-2" style="font-size: 0.8rem; border-radius: 8px;">6 Operational Centres</span>
    </div>
    
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>TEFC Centre</th>
                    <th>State Jurisdiction</th>
                    <th>Covered Revenue Districts / Taluks</th>
                    <th class="text-center">Total Apps</th>
                    <th class="text-center">Pending</th>
                    <th class="text-center">Approved</th>
                    <th class="text-center">Products</th>
                    <th class="text-end">HO Direct Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($centreStats))
                    @foreach($centreStats as $code => $cData)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 36px; height: 36px; font-size: 0.85rem; background: var(--sky); color: var(--primary); font-weight: 800;">
                                        {{ substr($cData['centre']['name'], 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $cData['centre']['name'] }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $cData['centre']['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $cData['centre']['state'] }}</span>
                            </td>
                            <td>
                                <span class="small text-muted" style="max-width: 280px; display: inline-block; line-height: 1.3;">
                                    {{ $cData['centre']['districts_label'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $cData['total_applications'] }}</span>
                            </td>
                            <td class="text-center">
                                @if($cData['pending'] > 0)
                                    <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.78rem;">{{ $cData['pending'] }}</span>
                                @else
                                    <span class="text-muted small">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cData['approved'] > 0)
                                    <span class="badge bg-success px-2 py-1" style="font-size: 0.78rem;">{{ $cData['approved'] }}</span>
                                @else
                                    <span class="text-muted small">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $cData['products'] }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.centre.switch', $code) }}" class="btn btn-sm btn-primary" style="font-weight: 700; border-radius: 8px; font-size: 0.8rem; padding: 0.4rem 0.9rem; box-shadow: 0 2px 8px rgba(108, 21, 63, 0.15);">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login to Centre
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="portal-card mb-5">
    <div class="portal-card-header">
        <h3>Catalogue Traffic Analysis</h3>
        <span class="text-muted small">Last 7 Days</span>
    </div>
    <div class="p-4">
        <canvas id="adminChart" height="100"></canvas>
    </div>
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <h3>Quick Actions</h3>
    </div>
    <div class="p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.users') }}" class="portal-card p-4 text-center text-decoration-none d-block hover-lift">
                    <i class="fas fa-user-check fa-2x mb-3 text-primary"></i>
                    <div class="fw-bold text-dark">Approve Users</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.products.index') }}" class="portal-card p-4 text-center text-decoration-none d-block hover-lift">
                    <i class="fas fa-clipboard-check fa-2x mb-3 text-success"></i>
                    <div class="fw-bold text-dark">Moderate Products</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.cms') }}" class="portal-card p-4 text-center text-decoration-none d-block hover-lift">
                    <i class="fas fa-layer-group fa-2x mb-3 text-warning"></i>
                    <div class="fw-bold text-dark">Manage CMS</div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift { transition: transform 0.2s; }
.hover-lift:hover { transform: translateY(-5px); border-color: var(--primary); }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('adminChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Catalogue Views',
                    data: {!! json_encode($chartData['views'] ?? []) !!},
                    backgroundColor: '#6c1340',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
