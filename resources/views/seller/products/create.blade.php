@extends('layouts.portal')

@section('title', 'Add New Product — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}" class="active"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
    <li><a href="#"><i class="fas fa-user-circle"></i> Profile Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <h3>Add New Product</h3>
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

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required style="border-radius: 8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Category * (Multiple Select)</label>
                    <select name="category[]" class="form-select" multiple required style="height: 120px; border-radius: 8px;">
                        <option value="Home Textiles" @if(is_array(old('category')) && in_array('Home Textiles', old('category'))) selected @endif>Home Textiles</option>
                        <option value="Technical Textiles" @if(is_array(old('category')) && in_array('Technical Textiles', old('category'))) selected @endif>Technical Textiles</option>
                        <option value="Handloom Products" @if(is_array(old('category')) && in_array('Handloom Products', old('category'))) selected @endif>Handloom Products</option>
                        <option value="Carpets" @if(is_array(old('category')) && in_array('Carpets', old('category'))) selected @endif>Carpets</option>
                        <option value="Woollen Products" @if(is_array(old('category')) && in_array('Woollen Products', old('category'))) selected @endif>Woollen Products</option>
                        <option value="Garments" @if(is_array(old('category')) && in_array('Garments', old('category'))) selected @endif>Garments</option>
                        <option value="Yarn" @if(is_array(old('category')) && in_array('Yarn', old('category'))) selected @endif>Yarn</option>
                        <option value="Fabrics" @if(is_array(old('category')) && in_array('Fabrics', old('category'))) selected @endif>Fabrics</option>
                        <option value="Sustainable Textiles" @if(is_array(old('category')) && in_array('Sustainable Textiles', old('category'))) selected @endif>Sustainable Textiles</option>
                        <option value="Organic Products" @if(is_array(old('category')) && in_array('Organic Products', old('category'))) selected @endif>Organic Products</option>
                        <option value="Other" @if(is_array(old('category')) && in_array('Other', old('category'))) selected @endif>Other</option>
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Cluster</label>
                    <select name="cluster" class="form-select" style="border-radius: 8px;">
                        <option value="">Select Cluster (Optional)</option>
                        @php $clusters = ['Tirupur', 'Surat', 'Panipat', 'Ludhiana', 'Varanasi', 'Bhilwara', 'Kanchipuram', 'Bhagalpur', 'Kolkata', 'Other']; @endphp
                        @foreach($clusters as $c)
                            <option value="{{ $c }}" @if(old('cluster') == $c) selected @endif>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">HS Code</label>
                    <input type="text" name="hs_code" class="form-control" value="{{ old('hs_code') }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Production Capacity</label>
                    <input type="text" name="production_capacity" class="form-control" value="{{ old('production_capacity') }}" style="border-radius: 8px;">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Width/Size</label>
                    <input type="text" name="width_size" class="form-control" value="{{ old('width_size') }}" style="border-radius: 8px;" placeholder="E.g. 44 inches, King Size">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Pattern</label>
                    <input type="text" name="pattern" class="form-control" value="{{ old('pattern') }}" style="border-radius: 8px;" placeholder="E.g. Plain, Printed, Jacquard">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color') }}" style="border-radius: 8px;" placeholder="E.g. Blue, Off-White">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Contact Information (For this product inquiry)</label>
                    <textarea name="contact_information" class="form-control" rows="2" placeholder="Email, Phone, Person Name..." style="border-radius: 8px;">{{ old('contact_information') }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Material Composition</label>
                    <textarea name="material_composition" class="form-control" rows="2" style="border-radius: 8px;">{{ old('material_composition') }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Technical Specifications</label>
                    <textarea name="technical_specifications" class="form-control" rows="3" style="border-radius: 8px;">{{ old('technical_specifications') }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Certifications (List them or describe)</label>
                    <textarea name="certifications" class="form-control" rows="2" style="border-radius: 8px;">{{ old('certifications') }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <h4 class="mb-3 text-primary">Media Uploads</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Product Images (Multiple allowed)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" style="border-radius: 8px;">
                    <small class="text-muted">JPG, PNG up to 5MB.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Product Brochures (Multiple allowed)</label>
                    <input type="file" name="brochures[]" class="form-control" multiple accept=".pdf,.docx,.xlsx" style="border-radius: 8px;">
                    <small class="text-muted">PDF, DOCX up to 5MB.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Product Videos (Multiple allowed)</label>
                    <input type="file" name="videos[]" class="form-control" multiple accept="video/mp4" style="border-radius: 8px;">
                    <small class="text-muted">MP4 up to 10MB.</small>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: var(--primary); border-radius: 8px; font-weight: bold; border: none;">Submit for Moderation</button>
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 py-2 ms-2" style="border-radius: 8px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
