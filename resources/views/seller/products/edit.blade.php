@extends('layouts.portal')

@section('title', 'Edit Textile Product — TEFC Portal')

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('seller.products.index') }}" class="active"><i class="fas fa-boxes"></i> My Catalogue</a></li>
    @if(Auth::user()->isMainExporter())
        <li><a href="{{ route('exporter.operators.index') }}"><i class="fas fa-users-cog"></i> Data Entry Operators</a></li>
    @endif
    <li><a href="{{ route('seller.inquiries') }}"><i class="fas fa-envelope-open-text"></i> Buyer Leads</a></li>
@endsection

@section('styles')
<style>
    .classification-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        max-height: 380px;
        overflow-y: auto;
        z-index: 1050;
        margin-top: 6px;
    }
    .classification-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f5;
        cursor: pointer;
        transition: background 0.15s;
    }
    .classification-item:hover {
        background: #fbf4f8;
    }
    .attr-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .badge-category {
        background: #6c1340;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .badge-subcat {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .media-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
    }
    .media-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .media-card .primary-pill {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #2e7d32;
        color: #fff;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .media-card .actions-bar {
        padding: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border-top: 1px solid #dee2e6;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--primary);">Edit Product: {{ $product->name }}</h2>
        <p class="text-muted mb-0">Update classification taxonomy, technical parameters, and media assets.</p>
    </div>
    <div class="d-flex gap-2">
        @if($product->status === 'Approved')
            <a href="{{ route('catalogue.show', $product) }}" target="_blank" class="btn btn-outline-primary px-3 py-2">
                <i class="fas fa-external-link-alt me-1"></i> View Live
            </a>
        @endif
        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-3 py-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Catalogue
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4">
        <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Please correct the following errors:</strong>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($product->status === 'Revision Requested' && $product->moderation_notes)
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle fs-5 text-warning"></i>
            <div>
                <strong class="text-dark">Revision Instructions from Exporter Higher Authority:</strong>
                <p class="mb-0 text-muted">{{ $product->moderation_notes }}</p>
            </div>
        </div>
    </div>
@endif

<form id="productEditForm" action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Hidden input for selected classification -->
    <input type="hidden" name="textile_product_id" id="textile_product_id" value="{{ old('textile_product_id', $product->textile_product_id) }}">

    <!-- SECTION 1: CLASSIFICATION -->
    <div class="portal-card mb-4">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-sitemap me-2"></i>1. Textile Classification
            </h4>
            <span class="badge bg-light text-muted border">Taxonomy Master</span>
        </div>
        <div class="p-4">
            <!-- SELECTED CLASSIFICATION CONFIRMATION CARD -->
            <div id="selectedClassificationCard" class="card border-success bg-light rounded-4 p-4 mb-3 {{ $product->textileProduct ? '' : 'd-none' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-success mb-2"><i class="fas fa-check-circle me-1"></i> Active Classification</span>
                        <h4 class="fw-bold text-dark mb-1" id="selectedProductName">{{ $product->textileProduct?->name ?? '—' }}</h4>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <span class="badge-category" id="selectedCategoryName">{{ $product->textileProduct?->subcategory?->category?->name ?? '—' }}</span>
                            <span class="text-muted"><i class="fas fa-chevron-right fs-6"></i></span>
                            <span class="badge-subcat" id="selectedSubcategoryName">{{ $product->textileProduct?->subcategory?->name ?? '—' }}</span>
                            <span class="badge bg-secondary-subtle text-secondary border" id="selectedProductCode">Code: {{ $product->textileProduct?->code ?? '—' }}</span>
                            <span class="badge bg-info-subtle text-info border" id="selectedHsCode">HS: {{ $product->textileProduct?->default_hs_code ?? '—' }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="toggleClassificationPicker()">
                        <i class="fas fa-sync-alt me-1"></i> Change Classification
                    </button>
                </div>
            </div>

            <!-- SEARCH DROPDOWN FOR CHANGING CLASSIFICATION -->
            <div id="classificationPickerContainer" class="{{ $product->textileProduct ? 'd-none' : '' }}">
                <div class="position-relative mb-3">
                    <label class="form-label fw-bold">Search New Classification</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="classificationSearchInput" class="form-control border-start-0" 
                               placeholder="Type keyword, e.g. 'Cotton', 'Denim', 'Bedsheet', 'Shirting', 'Silk'..." autocomplete="off">
                    </div>
                    <div id="classificationSearchResults" class="classification-search-results d-none"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: BASIC PRODUCT INFORMATION -->
    <div class="portal-card mb-4">
        <div class="portal-card-header">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-info-circle me-2"></i>2. Basic Product Information
            </h4>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Product Trade Name *</label>
                    <input type="text" name="name" id="productNameInput" class="form-control form-control-lg" 
                           value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">HS Code</label>
                    <input type="text" name="hs_code" id="hsCodeInput" class="form-control form-control-lg" 
                           value="{{ old('hs_code', $product->hs_code) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Textile Cluster / Region</label>
                    <select name="cluster" class="form-select form-select-lg">
                        <option value="">Select Indian Cluster (Optional)</option>
                        @php 
                            $clusters = [
                                'Tirupur (Tamil Nadu)', 'Surat (Gujarat)', 'Panipat (Haryana)', 'Ludhiana (Punjab)', 
                                'Varanasi (Uttar Pradesh)', 'Bhilwara (Rajasthan)', 'Kanchipuram (Tamil Nadu)', 
                                'Bhagalpur (Bihar)', 'Kolkata (West Bengal)', 'Karur (Tamil Nadu)', 
                                'Ichalkaranji (Maharashtra)', 'Jaipur (Rajasthan)', 'Navsari (Gujarat)', 
                                'Ahmedabad (Gujarat)', 'Coimbatore (Tamil Nadu)', 'Other'
                            ]; 
                            $currCluster = old('cluster', $product->cluster);
                        @endphp
                        @foreach($clusters as $c)
                            <option value="{{ $c }}" @if($currCluster == $c || Str::startsWith($currCluster, explode(' ', $c)[0])) selected @endif>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Minimum Order Quantity (MOQ)</label>
                    <input type="text" name="moq" class="form-control form-control-lg" 
                           value="{{ old('moq', $product->moq) }}" placeholder="E.g. 500, 1000">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Unit of Measurement</label>
                    @php $uom = old('unit_of_measurement', $product->unit_of_measurement); @endphp
                    <select name="unit_of_measurement" class="form-select form-select-lg">
                        <option value="Meters" @if($uom == 'Meters') selected @endif>Meters (m)</option>
                        <option value="Pieces" @if($uom == 'Pieces') selected @endif>Pieces (pcs)</option>
                        <option value="Kilograms" @if($uom == 'Kilograms') selected @endif>Kilograms (kg)</option>
                        <option value="Yards" @if($uom == 'Yards') selected @endif>Yards (yd)</option>
                        <option value="Bales" @if($uom == 'Bales') selected @endif>Bales</option>
                        <option value="Tonnes" @if($uom == 'Tonnes') selected @endif>Tonnes (MT)</option>
                        <option value="Sets" @if($uom == 'Sets') selected @endif>Sets</option>
                        <option value="Pairs" @if($uom == 'Pairs') selected @endif>Pairs</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Monthly Production / Supply Capacity</label>
                    <input type="text" name="production_capacity" class="form-control" 
                           value="{{ old('production_capacity', $product->production_capacity) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Search Keywords & Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           value="{{ old('tags', $product->tags) }}" placeholder="Comma separated, e.g. combed, oxford, organic">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Direct Contact Details for Inquiries</label>
                    <textarea name="contact_information" class="form-control" rows="2">{{ old('contact_information', $product->contact_information) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: TECHNICAL SPECIFICATIONS & ATTRIBUTES -->
    <div class="portal-card mb-4">
        <div class="portal-card-header">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-sliders-h me-2"></i>3. Technical Specifications & Fibre Composition
            </h4>
        </div>
        <div class="p-4">
            <!-- FIBRE COMPOSITION -->
            <div class="attr-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-seedling text-success me-2"></i> Fibre Composition
                    </h5>
                    <span class="badge bg-light text-dark border" id="fibrePercentCounter">Total: 0%</span>
                </div>

                <div id="fibreRowsContainer">
                    @forelse($product->fibres as $index => $fibre)
                        <div class="row g-2 mb-2 fibre-row align-items-center">
                            <div class="col-md-6">
                                <select name="fibres[{{ $index }}][master_id]" class="form-select fibre-select" onchange="onFibreSelectChange(this)">
                                    <option value="{{ $fibre->master_id }}" selected>{{ $fibre->attribute_name }}</option>
                                </select>
                                <input type="hidden" name="fibres[{{ $index }}][name]" class="fibre-name-input" value="{{ $fibre->attribute_name }}">
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="number" step="0.5" min="1" max="100" name="fibres[{{ $index }}][percentage]" class="form-control fibre-percent-input" value="{{ $fibre->percentage }}" oninput="calculateFibreTotal()">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeFibreRow(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row g-2 mb-2 fibre-row align-items-center">
                            <div class="col-md-6">
                                <select name="fibres[0][master_id]" class="form-select fibre-select" onchange="onFibreSelectChange(this)">
                                    <option value="">-- Select Fibre Master --</option>
                                </select>
                                <input type="hidden" name="fibres[0][name]" class="fibre-name-input" value="">
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="number" step="0.5" min="1" max="100" name="fibres[0][percentage]" class="form-control fibre-percent-input" placeholder="Percentage" oninput="calculateFibreTotal()">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeFibreRow(this)" disabled>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFibreRow()">
                    <i class="fas fa-plus me-1"></i> Add Another Fibre Blend
                </button>
            </div>

            <!-- YARN SPECIFICATIONS -->
            @php $yarn = $product->yarns->first(); @endphp
            <div class="attr-card">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-ring text-warning me-2"></i> Yarn Specifications
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Yarn Type / Spinning Method</label>
                        <select name="yarns[0][master_id]" id="yarnMasterSelect" class="form-select" onchange="document.getElementById('yarnNameInput').value = this.options[this.selectedIndex].text;">
                            @if($yarn)
                                <option value="{{ $yarn->master_id }}" selected>{{ $yarn->attribute_name }}</option>
                            @else
                                <option value="">-- Select Yarn Type (Optional) --</option>
                            @endif
                        </select>
                        <input type="hidden" name="yarns[0][name]" id="yarnNameInput" value="{{ $yarn?->attribute_name ?? 'Yarn' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Yarn Count / Linear Density</label>
                        <input type="text" name="yarns[0][value]" class="form-control" value="{{ $yarn?->attribute_value }}" placeholder="E.g. 40s Ne, 2/40s, 150D">
                    </div>
                </div>
            </div>

            <!-- FABRIC SPECIFICATIONS -->
            @php $fabric = $product->fabrics->first(); @endphp
            <div class="attr-card">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-th text-info me-2"></i> Fabric Construction & Dimensions
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fabric Weave / Knit Structure</label>
                        <select name="fabrics[0][master_id]" id="fabricMasterSelect" class="form-select" onchange="document.getElementById('fabricNameInput').value = this.options[this.selectedIndex].text;">
                            @if($fabric)
                                <option value="{{ $fabric->master_id }}" selected>{{ $fabric->attribute_name }}</option>
                            @else
                                <option value="">-- Select Construction --</option>
                            @endif
                        </select>
                        <input type="hidden" name="fabrics[0][name]" id="fabricNameInput" value="{{ $fabric?->attribute_name ?? 'Fabric Construction' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">GSM (Grams per Square Meter)</label>
                        <input type="text" name="fabrics[0][value]" class="form-control" value="{{ $fabric?->attribute_value }}" placeholder="E.g. 180 GSM">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Width / Dimensions</label>
                        <input type="text" name="width_size" class="form-control" value="{{ old('width_size', $product->width_size) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Pattern / Design</label>
                        <input type="text" name="pattern" class="form-control" value="{{ old('pattern', $product->pattern) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Color / Shades</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
                    </div>
                </div>
            </div>

            <!-- PROCESSES & FINISHES -->
            @php $proc = $product->processes->first(); @endphp
            <div class="attr-card">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-flask text-danger me-2"></i> Wet Processing & Special Finishes
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Primary Applied Process</label>
                        <select name="processes[0][master_id]" id="processMasterSelect" class="form-select" onchange="document.getElementById('processNameInput').value = this.options[this.selectedIndex].text;">
                            @if($proc)
                                <option value="{{ $proc->master_id }}" selected>{{ $proc->attribute_name }}</option>
                            @else
                                <option value="">-- Select Applied Process --</option>
                            @endif
                        </select>
                        <input type="hidden" name="processes[0][name]" id="processNameInput" value="{{ $proc?->attribute_name ?? 'Primary Finish' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Finishing Notes & Treatments</label>
                        <input type="text" name="processes[0][value]" class="form-control" value="{{ $proc?->attribute_value }}" placeholder="E.g. Mercerized, Bio-polished, Water Repellent">
                    </div>
                </div>
            </div>

            <!-- GENERAL SPECS -->
            <div class="attr-card">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-certificate text-primary me-2"></i> Standards & Additional Details
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Certifications</label>
                        <textarea name="certifications" class="form-control" rows="3">{{ old('certifications', $product->certifications) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Technical Specifications</label>
                        <textarea name="technical_specifications" class="form-control" rows="3">{{ old('technical_specifications', $product->technical_specifications) }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Material Composition (Summary)</label>
                        <input type="text" name="material_composition" id="materialCompositionSummary" class="form-control" 
                               value="{{ old('material_composition', $product->material_composition) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: EXISTING MEDIA & UPLOADS -->
    <div class="portal-card mb-4">
        <div class="portal-card-header">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-images me-2"></i>4. Media Assets & Documents
            </h4>
        </div>
        <div class="p-4">
            <!-- CURRENT MEDIA GALLERY -->
            @php $currentImages = $product->media->where('media_type', 'image'); @endphp
            @if($currentImages->count() > 0)
                <h5 class="fw-bold text-dark mb-3">Current Images ({{ $currentImages->count() }})</h5>
                <div class="row g-3 mb-4">
                    @foreach($currentImages as $media)
                        <div class="col-md-3 col-6">
                            <div class="media-card">
                                @if($media->is_primary)
                                    <span class="primary-pill"><i class="fas fa-star me-1"></i> Primary</span>
                                @endif
                                <img src="{{ asset('storage/' . $media->file_path) }}" alt="Image">
                                <div class="actions-bar">
                                    @if(!$media->is_primary)
                                        <button type="submit" form="setPrimaryForm{{ $media->id }}" class="btn btn-xs btn-outline-success" title="Set as primary image">
                                            <i class="fas fa-star me-1"></i> Set Primary
                                        </button>
                                    @else
                                        <span class="small text-success fw-bold"><i class="fas fa-check me-1"></i> Main Photo</span>
                                    @endif
                                    <button type="submit" form="deleteMediaForm{{ $media->id }}" class="btn btn-xs btn-outline-danger" title="Delete image" onclick="return confirm('Delete this image?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- UPLOAD NEW MEDIA -->
            <h5 class="fw-bold text-dark mb-3">Upload Additional Media</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Add More Images</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">JPG, PNG, WebP up to 10MB</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Add Brochures / Spec Sheets</label>
                    <input type="file" name="brochures[]" class="form-control" multiple accept=".pdf,.docx,.xlsx">
                    <small class="text-muted">PDF, DOCX up to 15MB</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Add Videos</label>
                    <input type="file" name="videos[]" class="form-control" multiple accept="video/mp4">
                    <small class="text-muted">MP4 up to 10MB</small>
                </div>
            </div>
        </div>
    </div>

    <!-- PUBLISH SETTING & ACTIONS -->
    <div class="portal-card p-4">
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublishedCheck" @if(old('is_published', $product->is_published)) checked @endif>
            <label class="form-check-label fw-bold" for="isPublishedCheck">
                Keep product published on the TEFC Public Catalogue
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 py-2">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold fs-5 shadow">
                <i class="fas fa-save me-2"></i> Save & Update Product
            </button>
        </div>
    </div>
</form>

<!-- Dedicated Media forms outside the main form -->
@foreach($product->media as $media)
    <form id="setPrimaryForm{{ $media->id }}" action="{{ route('seller.products.media.primary', [$product, $media]) }}" method="POST" class="d-none">
        @csrf
    </form>
    <form id="deleteMediaForm{{ $media->id }}" action="{{ route('seller.products.media.destroy', [$product, $media]) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@endsection

@section('scripts')
<script>
    let searchDebounceTimer = null;
    let masterDataCache = { fibres: [], yarns: [], fabrics: [], processes: [] };

    function toggleClassificationPicker() {
        const container = document.getElementById('classificationPickerContainer');
        container.classList.toggle('d-none');
        if (!container.classList.contains('d-none')) {
            document.getElementById('classificationSearchInput').focus();
        }
    }

    const searchInput = document.getElementById('classificationSearchInput');
    const searchResults = document.getElementById('classificationSearchResults');

    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(searchDebounceTimer);

        if (q.length < 2) {
            searchResults.innerHTML = '';
            searchResults.classList.add('d-none');
            return;
        }

        searchDebounceTimer = setTimeout(() => {
            searchResults.innerHTML = '<div class="p-3 text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Searching...</div>';
            searchResults.classList.remove('d-none');

            fetch(`{{ route('api.classifications.search') }}?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(items => {
                    if (!items || items.length === 0) {
                        searchResults.innerHTML = `<div class="p-3 text-muted text-center">No classifications found matching "<strong>${escapeHtml(q)}</strong>".</div>`;
                        return;
                    }

                    let html = '';
                    items.forEach(item => {
                        html += `
                            <div class="classification-item" onclick='selectClassification(${JSON.stringify(item)})'>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-dark">${escapeHtml(item.name)}</div>
                                        <div class="small text-muted mt-1">
                                            <span class="badge bg-light text-dark border me-1">${escapeHtml(item.category_name)}</span>
                                            <i class="fas fa-chevron-right text-muted mx-1" style="font-size:0.65rem;"></i>
                                            <span class="badge bg-light text-secondary border me-1">${escapeHtml(item.subcategory_name)}</span>
                                            ${item.default_hs_code ? `<span class="badge bg-info-subtle text-info border">HS: ${escapeHtml(item.default_hs_code)}</span>` : ''}
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary border">${escapeHtml(item.code)}</span>
                                </div>
                            </div>
                        `;
                    });
                    searchResults.innerHTML = html;
                });
        }, 250);
    });

    function selectClassification(item) {
        document.getElementById('textile_product_id').value = item.id;
        document.getElementById('selectedProductName').innerText = item.name;
        document.getElementById('selectedCategoryName').innerText = item.category_name;
        document.getElementById('selectedSubcategoryName').innerText = item.subcategory_name;
        document.getElementById('selectedProductCode').innerText = 'Code: ' + item.code;
        document.getElementById('selectedHsCode').innerText = item.default_hs_code ? ('HS: ' + item.default_hs_code) : 'HS: N/A';

        const hsInput = document.getElementById('hsCodeInput');
        if (!hsInput.value && item.default_hs_code) {
            hsInput.value = item.default_hs_code;
        }

        document.getElementById('selectedClassificationCard').classList.remove('d-none');
        document.getElementById('classificationPickerContainer').classList.add('d-none');
        searchResults.classList.add('d-none');

        loadCategoryAttributes(item.category_id);
    }

    function loadCategoryAttributes(categoryId) {
        fetch(`{{ route('api.classifications.category-attributes') }}?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                masterDataCache = data;
                populateMasterDropdowns();
            });
    }

    function populateMasterDropdowns() {
        document.querySelectorAll('.fibre-select').forEach(sel => {
            const curVal = sel.value;
            let html = '<option value="">-- Select Fibre Master --</option>';
            masterDataCache.fibres.forEach(f => {
                html += `<option value="${f.id}" data-name="${escapeHtml(f.name)}" ${curVal == f.id ? 'selected' : ''}>${escapeHtml(f.name)} (${f.group_name})</option>`;
            });
            sel.innerHTML = html;
        });

        const yarnSelect = document.getElementById('yarnMasterSelect');
        if (yarnSelect) {
            const cur = yarnSelect.value;
            let html = '<option value="">-- Select Yarn Type (Optional) --</option>';
            masterDataCache.yarns.forEach(y => {
                html += `<option value="${y.id}" ${cur == y.id ? 'selected' : ''}>${escapeHtml(y.name)} (${y.group_name})</option>`;
            });
            yarnSelect.innerHTML = html;
        }

        const fabricSelect = document.getElementById('fabricMasterSelect');
        if (fabricSelect) {
            const cur = fabricSelect.value;
            let html = '<option value="">-- Select Construction (Optional) --</option>';
            masterDataCache.fabrics.forEach(fb => {
                html += `<option value="${fb.id}" ${cur == fb.id ? 'selected' : ''}>${escapeHtml(fb.name)} (${fb.group_name})</option>`;
            });
            fabricSelect.innerHTML = html;
        }

        const procSelect = document.getElementById('processMasterSelect');
        if (procSelect) {
            const cur = procSelect.value;
            let html = '<option value="">-- Select Applied Process (Optional) --</option>';
            masterDataCache.processes.forEach(p => {
                html += `<option value="${p.id}" ${cur == p.id ? 'selected' : ''}>${escapeHtml(p.name)} (${p.group_name})</option>`;
            });
            procSelect.innerHTML = html;
        }
    }

    let fibreRowCounter = {{ count($product->fibres) > 0 ? count($product->fibres) : 1 }};

    function addFibreRow() {
        const container = document.getElementById('fibreRowsContainer');
        const idx = fibreRowCounter++;
        let optHtml = '<option value="">-- Select Fibre Master --</option>';
        masterDataCache.fibres.forEach(f => {
            optHtml += `<option value="${f.id}" data-name="${escapeHtml(f.name)}">${escapeHtml(f.name)} (${f.group_name})</option>`;
        });

        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 fibre-row align-items-center';
        row.innerHTML = `
            <div class="col-md-6">
                <select name="fibres[${idx}][master_id]" class="form-select fibre-select" onchange="onFibreSelectChange(this)">
                    ${optHtml}
                </select>
                <input type="hidden" name="fibres[${idx}][name]" class="fibre-name-input" value="">
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" step="0.5" min="1" max="100" name="fibres[${idx}][percentage]" class="form-control fibre-percent-input" placeholder="Percentage" oninput="calculateFibreTotal()">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeFibreRow(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    function removeFibreRow(btn) {
        const row = btn.closest('.fibre-row');
        row.remove();
        calculateFibreTotal();
    }

    function onFibreSelectChange(sel) {
        const row = sel.closest('.fibre-row');
        const nameInput = row.querySelector('.fibre-name-input');
        if (sel.selectedIndex > 0) {
            const opt = sel.options[sel.selectedIndex];
            nameInput.value = opt.getAttribute('data-name') || opt.text;
        } else {
            nameInput.value = '';
        }
        updateMaterialSummary();
    }

    function calculateFibreTotal() {
        let total = 0;
        document.querySelectorAll('.fibre-percent-input').forEach(inp => {
            const val = parseFloat(inp.value) || 0;
            total += val;
        });

        const counter = document.getElementById('fibrePercentCounter');
        counter.innerText = `Total: ${total}%`;
        if (total === 100) {
            counter.className = 'badge bg-success-subtle text-success border';
        } else if (total > 100) {
            counter.className = 'badge bg-danger-subtle text-danger border';
        } else {
            counter.className = 'badge bg-warning-subtle text-warning border';
        }
        updateMaterialSummary();
    }

    function updateMaterialSummary() {
        const parts = [];
        document.querySelectorAll('.fibre-row').forEach(row => {
            const sel = row.querySelector('.fibre-select');
            const percentInp = row.querySelector('.fibre-percent-input');
            if (sel && sel.selectedIndex > 0 && percentInp && percentInp.value) {
                const opt = sel.options[sel.selectedIndex];
                const name = opt.getAttribute('data-name') || opt.text;
                parts.push(`${percentInp.value}% ${name}`);
            }
        });
        const summaryInput = document.getElementById('materialCompositionSummary');
        if (parts.length > 0) {
            summaryInput.value = parts.join(' / ');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateFibreTotal();
        @if($product->textile_category_id)
            loadCategoryAttributes({{ $product->textile_category_id }});
        @elseif($product->textileProduct?->subcategory?->category_id)
            loadCategoryAttributes({{ $product->textileProduct->subcategory->category_id }});
        @else
            loadCategoryAttributes(1);
        @endif
    });
</script>
@endsection
