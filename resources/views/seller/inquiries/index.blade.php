@extends('layouts.portal')

@section('title', 'Buyer Leads — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    <li><a href="{{ route('seller.inquiries') }}" class="active"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
    <li><a href="#"><i class="fas fa-user-circle"></i> Profile Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>Incoming Buyer Inquiries</h3>
        <span class="badge bg-primary rounded-pill">{{ $inquiries->total() }} Leads</span>
    </div>
    
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product Interested</th>
                    <th>Buyer Name</th>
                    <th>Contact Information</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inquiry)
                <tr>
                    <td class="small text-muted">{{ $inquiry->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="fw-bold">{{ $inquiry->product->name }}</div>
                        <div class="small text-muted">ID: #PROD-{{ $inquiry->product_id }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $inquiry->buyer_name }}</div>
                    </td>
                    <td>
                        <div class="small"><i class="fas fa-envelope me-1 text-muted"></i> {{ $inquiry->buyer_email }}</div>
                        @if($inquiry->buyer_phone)
                            <div class="small"><i class="fas fa-phone me-1 text-muted"></i> {{ $inquiry->buyer_phone }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-active"><i class="fas fa-envelope-open"></i> {{ $inquiry->status }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="bg-light p-3">
                        <div class="px-4">
                            <div class="row g-3 small mb-2">
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted">Quantity Required:</span> 
                                    <strong class="text-dark d-block mt-1">{{ $inquiry->quantity_required ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted">Sourcing Category:</span> 
                                    <strong class="text-dark d-block mt-1">{{ $inquiry->category ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted">Material Composition:</span> 
                                    <strong class="text-dark d-block mt-1">{{ $inquiry->material ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted">Pattern & Size:</span> 
                                    <strong class="text-dark d-block mt-1">{{ $inquiry->pattern ?? 'N/A' }} | {{ $inquiry->width_size ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="small mt-2 pt-2 border-top border-200">
                                <strong class="text-dark">Buyer Requirements / Message:</strong> 
                                <p class="text-muted mt-1 mb-0" style="font-style: italic;">"{{ $inquiry->additional_requirements ?? $inquiry->message }}"</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No inquiries received yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">
        {{ $inquiries->links() }}
    </div>
</div>
@endsection
