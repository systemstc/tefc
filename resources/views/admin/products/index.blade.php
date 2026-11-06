@extends('layouts.portal')

@section('title', 'Product Moderation — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}" class="active"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>Product Verification Queue</h3>
        <span class="badge bg-warning text-dark rounded-pill">{{ $products->total() }} Pending Review</span>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success m-4">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Exporter</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="fw-bold">{{ $product->name }}</td>
                    <td>
                        <div class="small fw-bold text-dark">{{ $product->user->name ?? 'N/A' }}</div>
                        <div class="text-muted small">Exporter ID: #EXP-{{ $product->user_id }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ is_array($product->category) ? implode(', ', $product->category) : $product->category }}</span>
                    </td>
                    <td>
                        @if($product->status == 'Approved')
                            <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Approved</span>
                        @elseif($product->status == 'Rejected')
                            <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                        @else
                            <span class="status-badge status-pending"><i class="fas fa-search"></i> Verification</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $product->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-primary">Review Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No products in moderation queue.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">
        {{ $products->links() }}
    </div>
</div>
@endsection
