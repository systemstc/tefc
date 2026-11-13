@extends('layouts.portal')

@section('title', 'Review Product — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}" class="active"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary fw-bold mb-0">Review Product: {{ $product->name }}</h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

    <div class="row">
        <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Product Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr><th style="width: 30%;">Exporter</th><td>{{ optional($product->user->exporterProfile)->company_name ?? $product->user->name ?? 'N/A' }} ({{ $product->user->email ?? 'N/A' }})</td></tr>
                                <tr><th>Textile Classification</th><td>
                                    @if($product->textileCategory)
                                        <span class="badge bg-primary">{{ $product->textileCategory->name }}</span>
                                        @if($product->textileSubcategory)
                                            <span class="badge bg-light text-dark border">{{ $product->textileSubcategory->name }}</span>
                                        @endif
                                        @if($product->textileProduct)
                                            <span class="badge bg-secondary-subtle text-secondary border">[{{ $product->textileProduct->code }}] {{ $product->textileProduct->name }}</span>
                                        @endif
                                    @else
                                        {{ is_array($product->category) ? implode(', ', $product->category) : $product->category }}
                                    @endif
                                </td></tr>
                                <tr><th>Cluster / Region</th><td>{{ $product->cluster ?: 'N/A' }}</td></tr>
                                <tr><th>HS Tariff Code</th><td>{{ $product->hs_code ?: 'N/A' }}</td></tr>
                                <tr><th>Minimum Order (MOQ)</th><td>{{ $product->moq ? ($product->moq . ' ' . ($product->unit_of_measurement ?? '')) : 'N/A' }}</td></tr>
                                <tr><th>Production Capacity</th><td>{{ $product->production_capacity ?: 'N/A' }}</td></tr>
                                <tr><th>Width / Size</th><td>{{ $product->width_size ?: 'N/A' }}</td></tr>
                                <tr><th>Pattern</th><td>{{ $product->pattern ?: 'N/A' }}</td></tr>
                                <tr><th>Color</th><td>{{ $product->color ?: 'N/A' }}</td></tr>
                                <tr><th>Fibre Composition</th><td>
                                    @if($product->fibres->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($product->fibres as $f)
                                                <span class="badge bg-white text-dark border">{{ $f->percentage ? ($f->percentage . '% ') : '' }}{{ $f->attribute_name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ $product->material_composition ?: 'N/A' }}
                                    @endif
                                </td></tr>
                                @if($product->yarns->count() > 0)
                                <tr><th>Yarn Specifications</th><td>
                                    @foreach($product->yarns as $y)
                                        <div><strong>{{ $y->attribute_name }}:</strong> {{ $y->attribute_value }}</div>
                                    @endforeach
                                </td></tr>
                                @endif
                                @if($product->fabrics->count() > 0)
                                <tr><th>Fabric Construction</th><td>
                                    @foreach($product->fabrics as $fb)
                                        <div><strong>{{ $fb->attribute_name }}:</strong> {{ $fb->attribute_value }}</div>
                                    @endforeach
                                </td></tr>
                                @endif
                                @if($product->processes->count() > 0)
                                <tr><th>Applied Processes / Finishes</th><td>
                                    @foreach($product->processes as $pr)
                                        <div><strong>{{ $pr->attribute_name }}:</strong> {{ $pr->attribute_value }}</div>
                                    @endforeach
                                </td></tr>
                                @endif
                                <tr><th>Technical Specifications</th><td>{{ $product->technical_specifications ?: 'N/A' }}</td></tr>
                                <tr><th>Certifications</th><td>{{ $product->certifications ?: 'N/A' }}</td></tr>
                                <tr><th>Contact Information</th><td>{{ $product->contact_information ?: 'N/A' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Media Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($product->media as $media)
                                    <div class="col-md-4 mb-3">
                                        @if($media->media_type == 'image')
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid border rounded" alt="Product Image">
                                        @elseif($media->media_type == 'video')
                                            <video width="100%" controls>
                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="btn btn-outline-secondary w-100">
                                                View {{ ucfirst($media->media_type) }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Moderation Action</h5>
                            <p>Current Status: <strong>{{ $product->status }}</strong></p>
                            
                            <form action="{{ route('admin.products.status', $product) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Update Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Pending" @if($product->status == 'Pending') selected @endif>Pending</option>
                                        <option value="Under Verification" @if($product->status == 'Under Verification') selected @endif>Under Verification</option>
                                        <option value="Approved" @if($product->status == 'Approved') selected @endif>Approve (Publish)</option>
                                        <option value="Rejected" @if($product->status == 'Rejected') selected @endif>Reject</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Moderation Notes (Sent to exporter if rejected)</label>
                                    <textarea name="moderation_notes" class="form-control" rows="4">{{ $product->moderation_notes }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Save Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endsection
