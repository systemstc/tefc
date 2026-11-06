@extends('layouts.portal')

@section('title', 'Edit Product — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}" class="active"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
    <li><a href="#"><i class="fas fa-user-circle"></i> Profile Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>Edit Product</h3>
        <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left"></i> Cancel</a>
    </div>
    
    <div class="p-4">
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Category * (Multiple Select)</label>
                    <select name="category[]" class="form-select" multiple required style="height: 120px; border-radius: 8px;">
                        @php 
                            $categories = ['Home Textiles', 'Technical Textiles', 'Handloom Products', 'Carpets', 'Woollen Products', 'Garments', 'Yarn', 'Fabrics', 'Sustainable Textiles', 'Organic Products', 'Other']; 
                            $selected_cats = is_array(old('category', $product->category)) ? old('category', $product->category) : [];
                        @endphp
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @if(in_array($cat, $selected_cats)) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Cluster</label>
                    <select name="cluster" class="form-select" style="border-radius: 8px;">
                        <option value="">Select Cluster (Optional)</option>
                        @php $clusters = ['Tirupur', 'Surat', 'Panipat', 'Ludhiana', 'Varanasi', 'Bhilwara', 'Kanchipuram', 'Bhagalpur', 'Kolkata', 'Other']; @endphp
                        @foreach($clusters as $c)
                            <option value="{{ $c }}" @if(old('cluster', $product->cluster) == $c) selected @endif>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">HS Code</label>
                    <input type="text" name="hs_code" class="form-control" value="{{ old('hs_code', $product->hs_code) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Production Capacity</label>
                    <input type="text" name="production_capacity" class="form-control" value="{{ old('production_capacity', $product->production_capacity) }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Width/Size</label>
                    <input type="text" name="width_size" class="form-control" value="{{ old('width_size', $product->width_size) }}" style="border-radius: 8px;" placeholder="E.g. 44 inches, King Size">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Pattern</label>
                    <input type="text" name="pattern" class="form-control" value="{{ old('pattern', $product->pattern) }}" style="border-radius: 8px;" placeholder="E.g. Plain, Printed, Jacquard">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}" style="border-radius: 8px;" placeholder="E.g. Blue, Off-White">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Contact Information</label>
                    <textarea name="contact_information" class="form-control" rows="2" placeholder="Email, Phone, Person Name..." style="border-radius: 8px;">{{ old('contact_information', $product->contact_information) }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Material Composition</label>
                    <textarea name="material_composition" class="form-control" rows="2" style="border-radius: 8px;">{{ old('material_composition', $product->material_composition) }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Technical Specifications</label>
                    <textarea name="technical_specifications" class="form-control" rows="3" style="border-radius: 8px;">{{ old('technical_specifications', $product->technical_specifications) }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Certifications</label>
                    <textarea name="certifications" class="form-control" rows="2" style="border-radius: 8px;">{{ old('certifications', $product->certifications) }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <h4 class="mb-3 text-primary">Add New Media</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Additional Images</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" style="border-radius: 8px;">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Additional Brochures</label>
                    <input type="file" name="brochures[]" class="form-control" multiple accept=".pdf,.docx,.xlsx" style="border-radius: 8px;">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Additional Videos</label>
                    <input type="file" name="videos[]" class="form-control" multiple accept="video/mp4" style="border-radius: 8px;">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: var(--primary); border-radius: 8px; font-weight: bold; border: none;">Update Product</button>
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 py-2 ms-2" style="border-radius: 8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
