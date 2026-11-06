@extends('layouts.portal')

@section('title', 'My Sourcing Requests — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}" class="{{ request()->routeIs('exporter-portal') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('buyer.sellers') }}" class="{{ request()->routeIs('buyer.sellers') ? 'active' : '' }}"><i class="fas fa-store"></i> Seller Directory</a></li>
    <li><a href="{{ route('buyer.saved-sellers') }}" class="{{ request()->routeIs('buyer.saved-sellers') ? 'active' : '' }}"><i class="fas fa-heart"></i> Saved Sellers</a></li>
    <li><a href="{{ route('buyer.inquiries') }}" class="{{ request()->routeIs('buyer.inquiries') ? 'active' : '' }}"><i class="fas fa-paper-plane"></i> Sent Requests</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>My Sourcing Requests</h3>
        <span class="text-muted small">Review the products you have expressed interest in.</span>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success m-4">{{ session('success') }}</div>
    @endif

    @if($inquiries->count() > 0)
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product / Sourcing Requirement</th>
                        <th>Exporter</th>
                        <th>My Sourcing Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inquiry)
                        <tr>
                            <td class="small text-muted">{{ $inquiry->created_at->format('d M, Y') }}</td>
                            <td>
                                @if($inquiry->product)
                                    <strong><a href="{{ route('catalogue.show', $inquiry->product) }}" style="color: var(--primary); text-decoration: none;">{{ $inquiry->product->name }}</a></strong>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 0.75rem;">General Enquiry</span>
                                    <div class="small text-muted mt-1">{{ $inquiry->category }} ({{ $inquiry->material }})</div>
                                @endif
                            </td>
                            <td>
                                @if($inquiry->product)
                                    {{ $inquiry->product->user->exporterProfile->company_name ?? $inquiry->product->user->name }}
                                @else
                                    <span class="text-muted small">Global Broadcast</span>
                                @endif
                            </td>
                            <td>
                                <div class="small" style="max-width: 350px; line-height: 1.5;">
                                    @if($inquiry->product)
                                        {{ $inquiry->message }}
                                    @else
                                        <strong>Qty Required:</strong> {{ $inquiry->quantity_required }}<br>
                                        <strong>Pattern:</strong> {{ $inquiry->pattern }} | <strong>Size:</strong> {{ $inquiry->width_size }}<br>
                                        <strong>Additional Reqs:</strong> {{ $inquiry->additional_requirements }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($inquiry->status === 'New' || $inquiry->status === 'Pending')
                                    <span class="status-badge status-pending"><i class="fas fa-clock"></i> {{ $inquiry->status }}</span>
                                @elseif($inquiry->status === 'Approved' || $inquiry->status === 'Processed')
                                    <span class="status-badge status-active"><i class="fas fa-check-circle"></i> {{ $inquiry->status }}</span>
                                @else
                                    <span class="status-badge status-rejected"><i class="fas fa-info-circle"></i> {{ $inquiry->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $inquiries->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-paper-plane fa-3x text-muted mb-3 opacity-30"></i>
            <p class="text-muted">You haven't sent any inquiries yet.</p>
            <a href="{{ route('catalogue.index') }}" class="btn btn-sm btn-outline-primary px-4 py-2 mt-2" style="border-radius: 8px; font-weight: bold;">Browse Catalogue</a>
        </div>
    @endif
</div>
@endsection
