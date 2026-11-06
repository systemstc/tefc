@extends('layouts.portal')

@section('title', 'My Catalogue — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}" class="active"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    @if(Auth::user()->isMainExporter())
        <li><a href="{{ route('exporter.operators.index') }}"><i class="fas fa-users-cog"></i> Data Entry Operators</a></li>
    @endif
    <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--primary);">Product Catalogue Management</h2>
        <p class="text-muted mb-0">
            @if(Auth::user()->isOperator())
                Logged in as <strong>Data Entry Operator</strong>. Products you enter will be verified and acknowledged by your Exporter Higher Authority before publication.
            @else
                Logged in as <strong>Exporter Higher Authority</strong> ({{ Auth::user()->designation ?: 'Authorized Signatory' }}). You have full authority to acknowledge and publish products directly to the public catalogue without HO admin verification.
            @endif
        </p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary px-3 py-2 fw-bold">
        <i class="fas fa-plus me-2"></i> Add New Product
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2">
        <i class="fas fa-check-circle fs-5"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2">
        <i class="fas fa-info-circle fs-5"></i>
        <span>{{ session('info') }}</span>
    </div>
@endif

<!-- Pending Acknowledgment Alert Banner -->
@if(isset($pendingAcknowledgmentCount) && $pendingAcknowledgmentCount > 0)
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="fs-4">⚡</span>
            <div>
                @if(Auth::user()->isMainExporter())
                    <strong class="d-block text-dark">{{ $pendingAcknowledgmentCount }} Product(s) Awaiting Your Higher Authority Acknowledgment</strong>
                    <small class="text-muted">Click <strong>"Acknowledge & Publish"</strong> on any pending item to verify its technical specifications and publish it directly to the TEFC Public Catalogue. No HO approval is needed.</small>
                @else
                    <strong class="d-block text-dark">{{ $pendingAcknowledgmentCount }} Product(s) Awaiting Exporter Acknowledgment</strong>
                    <small class="text-muted">Your product submission has been recorded and is currently in review by your Exporter Higher Authority.</small>
                @endif
            </div>
        </div>
    </div>
@endif

<div class="portal-card">
    <div class="portal-card-header d-flex justify-content-between align-items-center">
        <h3>Company Products ({{ $products->count() }})</h3>
        <div class="d-flex gap-2">
            <span class="badge bg-success-subtle text-success border"><i class="fas fa-check-circle me-1"></i> {{ $products->where('status', 'Approved')->count() }} Published</span>
            <span class="badge bg-warning-subtle text-warning border"><i class="fas fa-hourglass-half me-1"></i> {{ $pendingAcknowledgmentCount ?? 0 }} Awaiting Review</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th>Entered By</th>
                    <th>Status</th>
                    <th>Updated On</th>
                    <th class="text-end">Actions & Verification</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded p-2 text-center" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($product->media->where('media_type', 'image')->first())
                                    <img src="{{ asset('storage/' . $product->media->where('media_type', 'image')->first()->file_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                @else
                                    <i class="fas fa-box text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <div class="small text-muted">
                                    HS: {{ $product->hs_code ?: 'N/A' }} | {{ $product->material_composition ?: 'Specs provided' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ is_array($product->category) ? implode(', ', $product->category) : $product->category }}</span>
                    </td>
                    <td>
                        @if($product->creator)
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas {{ $product->creator->isOperator() ? 'fa-keyboard text-info' : 'fa-user-tie text-primary' }}"></i>
                                <div>
                                    <div class="small fw-bold text-dark">{{ $product->creator->name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--muted);">{{ $product->creator->designation ?: ($product->creator->isOperator() ? 'Data Entry Operator' : 'Higher Authority') }}</div>
                                </div>
                            </div>
                        @else
                            <span class="small text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($product->status == 'Approved')
                            <span class="status-badge status-active" title="Visible on Public Catalogue">
                                <i class="fas fa-check-double"></i> Published Live
                            </span>
                            @if($product->acknowledged_at)
                                <div style="font-size: 0.7rem; color: #2e7d32;" class="mt-1">
                                    Verified & Acknowledged
                                </div>
                            @endif
                        @elseif($product->status == 'Pending Exporter Review' || $product->status == 'Pending')
                            <span class="status-badge status-pending" title="Awaiting Higher Authority Verification">
                                <i class="fas fa-hourglass-half"></i> Pending Exporter Review
                            </span>
                            <div style="font-size: 0.7rem; color: #b78103;" class="mt-1">
                                Needs Exporter Acknowledgment
                            </div>
                        @elseif($product->status == 'Revision Requested')
                            <span class="status-badge status-rejected" title="{{ $product->moderation_notes }}">
                                <i class="fas fa-undo"></i> Revision Needed
                            </span>
                            @if($product->moderation_notes)
                                <div style="font-size: 0.7rem; color: #c62828;" class="mt-1">
                                    {{ Str::limit($product->moderation_notes, 30) }}
                                </div>
                            @endif
                        @else
                            <span class="status-badge status-pending">{{ $product->status }}</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $product->updated_at->format('d M, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <!-- Higher Authority Direct Acknowledgment Action -->
                            @if(Auth::user()->isMainExporter() && $product->status !== 'Approved')
                                <form action="{{ route('seller.products.acknowledge', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm px-2 py-1 fw-bold" title="Acknowledge specifications and publish to public catalogue directly (no HO verification needed)">
                                        <i class="fas fa-check me-1"></i> Acknowledge & Publish
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-warning btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#revisionModal{{ $product->id }}" title="Request revision from operator">
                                    <i class="fas fa-reply"></i>
                                </button>
                            @endif

                            @if($product->status === 'Approved')
                                <a href="{{ route('catalogue.show', $product) }}" target="_blank" class="action-btn" title="View on Public Catalogue">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                </a>
                            @endif

                            <a href="{{ route('seller.products.edit', $product) }}" class="action-btn" title="Edit Product"><i class="fas fa-edit"></i></a>

                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product from company catalogue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" title="Delete"><i class="fas fa-trash-alt text-danger"></i></button>
                            </form>
                        </div>

                        <!-- Revision Modal for this product -->
                        @if(Auth::user()->isMainExporter() && $product->status !== 'Approved')
                        <div class="modal fade" id="revisionModal{{ $product->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-bottom p-4">
                                        <h5 class="modal-title fw-bold" style="color: var(--primary);">Request Revision: {{ $product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('seller.products.request-revision', $product) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <label class="form-label small fw-bold text-uppercase text-muted">Feedback / Revision Instructions for Operator</label>
                                            <textarea name="moderation_notes" class="form-control" rows="3" required placeholder="Specify any corrections in material composition, HS code, images or capacity..."></textarea>
                                        </div>
                                        <div class="modal-footer border-top p-3">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning fw-bold px-3">Send Revision Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted mb-3">No products found in your catalogue.</div>
                        <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i> Upload First Product
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
