@extends('layouts.portal')

@section('title', 'Add New Textile Product — TEFC Portal')

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
    .wizard-step {
        display: none;
    }
    .wizard-step.active {
        display: block;
    }
    .step-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background: #f1f3f5;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s;
    }
    .step-pill.active {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(108, 19, 64, 0.25);
    }
    .step-pill.completed {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .step-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        margin-right: 8px;
        background: rgba(0,0,0,0.08);
    }
    .step-pill.active .step-num {
        background: rgba(255,255,255,0.25);
    }
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
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--primary);">Add New Textile Product</h2>
        <p class="text-muted mb-0">Classify your product with standard textile taxonomy and specify technical attributes.</p>
    </div>
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-3 py-2">
        <i class="fas fa-arrow-left me-1"></i> Back to Catalogue
    </a>
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

<form id="productForm" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Hidden input for selected classification -->
    <input type="hidden" name="textile_product_id" id="textile_product_id" value="{{ old('textile_product_id') }}">

    <!-- STEPPER HEADER -->
    <div class="portal-card p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between">
            <div class="step-pill active" data-step="1" onclick="goToStep(1)">
                <span class="step-num">1</span> Classification & Taxonomy
            </div>
            <div class="step-pill" data-step="2" onclick="goToStep(2)">
                <span class="step-num">2</span> Basic Information
            </div>
            <div class="step-pill" data-step="3" onclick="goToStep(3)">
                <span class="step-num">3</span> Technical Specifications
            </div>
            <div class="step-pill" data-step="4" onclick="goToStep(4)">
                <span class="step-num">4</span> Media & Documents
            </div>
            <div class="step-pill" data-step="5" onclick="goToStep(5)">
                <span class="step-num">5</span> Review & Submit
            </div>
        </div>
    </div>

    <!-- STEP 1: CLASSIFICATION SELECTOR -->
    <div class="portal-card wizard-step active" id="step-1">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-sitemap me-2"></i>Step 1: Textile Classification
            </h4>
            <span class="badge bg-light text-muted border">Source: TEFC Master Taxonomy</span>
        </div>
        <div class="p-4">
            <p class="text-muted">
                Search our comprehensive database of over 650 verified textile products or navigate through categories. Selecting a classification automatically configures appropriate technical attributes for your product.
            </p>

            <!-- Search Mode vs Hierarchy Mode tabs -->
            <ul class="nav nav-pills mb-4" id="classificationTabs">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="search-tab-btn" type="button" onclick="toggleClassificationMode('search')">
                        <i class="fas fa-search me-1"></i> Quick Search & Autocomplete
                    </button>
                </li>
                <li class="nav-item ms-2">
                    <button class="nav-link fw-bold text-secondary" id="browse-tab-btn" type="button" onclick="toggleClassificationMode('browse')">
                        <i class="fas fa-folder-tree me-1"></i> Browse Categories & Subcategories
                    </button>
                </li>
            </ul>

            <!-- SEARCH AUTOCOMPLETE MODE -->
            <div id="classification-search-mode">
                <div class="position-relative mb-4">
                    <label class="form-label fw-bold">Search Product or Keyword</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="classificationSearchInput" class="form-control border-start-0" 
                               placeholder="Type product name, e.g. 'Cotton', 'Denim', 'Bedsheet', 'Shirting', 'Silk', 'Geotextile'..." autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" onclick="clearClassificationSearch()" style="display:none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="classificationSearchResults" class="classification-search-results d-none"></div>
                </div>
            </div>

            <!-- BROWSE HIERARCHY MODE -->
            <div id="classification-browse-mode" style="display: none;">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">1. Category</label>
                        <select id="browseCategorySelect" class="form-select form-select-lg" onchange="loadSubcategories(this.value)">
                            <option value="">-- Select Category --</option>
                            @foreach($textileCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">2. Subcategory</label>
                        <select id="browseSubcategorySelect" class="form-select form-select-lg" onchange="loadProducts(this.value)" disabled>
                            <option value="">-- First Select Category --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">3. Product</label>
                        <select id="browseProductSelect" class="form-select form-select-lg" onchange="selectFromDropdown(this.value)" disabled>
                            <option value="">-- First Select Subcategory --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SELECTED CLASSIFICATION CONFIRMATION CARD -->
            <div id="selectedClassificationCard" class="card border-success bg-light rounded-4 p-4 mt-3 {{ old('textile_product_id') ? '' : 'd-none' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-success mb-2"><i class="fas fa-check-circle me-1"></i> Classification Confirmed</span>
                        <h4 class="fw-bold text-dark mb-1" id="selectedProductName">—</h4>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <span class="badge-category" id="selectedCategoryName">—</span>
                            <span class="text-muted"><i class="fas fa-chevron-right fs-6"></i></span>
                            <span class="badge-subcat" id="selectedSubcategoryName">—</span>
                            <span class="badge bg-secondary-subtle text-secondary border" id="selectedProductCode">Code: —</span>
                            <span class="badge bg-info-subtle text-info border" id="selectedHsCode">HS: —</span>
                        </div>
                        <p class="text-muted small mt-2 mb-0" id="selectedProductDescription"></p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetClassification()">
                        <i class="fas fa-sync-alt me-1"></i> Change
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <span class="text-muted small"><i class="fas fa-info-circle me-1"></i> Classification determines applicable technical specifications.</span>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold" onclick="validateAndGoStep(2)">
                    Proceed to Basic Info <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 2: BASIC INFORMATION -->
    <div class="portal-card wizard-step" id="step-2">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-info-circle me-2"></i>Step 2: Basic Product Information
            </h4>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Product Trade Name *</label>
                    <input type="text" name="name" id="productNameInput" class="form-control form-control-lg" 
                           value="{{ old('name') }}" placeholder="E.g. Premium 100% Combed Cotton Oxford Shirting" required>
                    <small class="text-muted">Descriptive commercial name as recognized by international buyers.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">HS Code</label>
                    <input type="text" name="hs_code" id="hsCodeInput" class="form-control form-control-lg" 
                           value="{{ old('hs_code') }}" placeholder="E.g. 5208.32">
                    <small class="text-muted">Harmonized System 6 to 8-digit tariff code.</small>
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
                        @endphp
                        @foreach($clusters as $c)
                            <option value="{{ $c }}" @if(old('cluster') == $c) selected @endif>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Minimum Order Quantity (MOQ)</label>
                    <input type="text" name="moq" class="form-control form-control-lg" 
                           value="{{ old('moq') }}" placeholder="E.g. 500, 1000, 5000">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Unit of Measurement</label>
                    <select name="unit_of_measurement" class="form-select form-select-lg">
                        <option value="Meters" @if(old('unit_of_measurement') == 'Meters') selected @endif>Meters (m)</option>
                        <option value="Pieces" @if(old('unit_of_measurement') == 'Pieces') selected @endif>Pieces (pcs)</option>
                        <option value="Kilograms" @if(old('unit_of_measurement') == 'Kilograms') selected @endif>Kilograms (kg)</option>
                        <option value="Yards" @if(old('unit_of_measurement') == 'Yards') selected @endif>Yards (yd)</option>
                        <option value="Bales" @if(old('unit_of_measurement') == 'Bales') selected @endif>Bales</option>
                        <option value="Tonnes" @if(old('unit_of_measurement') == 'Tonnes') selected @endif>Tonnes (MT)</option>
                        <option value="Sets" @if(old('unit_of_measurement') == 'Sets') selected @endif>Sets</option>
                        <option value="Pairs" @if(old('unit_of_measurement') == 'Pairs') selected @endif>Pairs</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Monthly Production / Supply Capacity</label>
                    <input type="text" name="production_capacity" class="form-control" 
                           value="{{ old('production_capacity') }}" placeholder="E.g. 50,000 meters / month">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Search Keywords & Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           value="{{ old('tags') }}" placeholder="Comma separated, e.g. combed, oxford, yarn-dyed, organic, bci">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Direct Contact Details for Inquiries</label>
                    <textarea name="contact_information" class="form-control" rows="2" 
                              placeholder="Designated sales contact person, direct email or phone number...">{{ old('contact_information') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep(1)">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold" onclick="validateAndGoStep(3)">
                    Proceed to Technical Specifications <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 3: DYNAMIC TECHNICAL SPECIFICATIONS -->
    <div class="portal-card wizard-step" id="step-3">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-sliders-h me-2"></i>Step 3: Dynamic Technical Specifications
            </h4>
            <span class="badge bg-light text-muted border" id="categoryRuleBadge">Auto-configured</span>
        </div>
        <div class="p-4">
            <p class="text-muted">
                These specifications are dynamically calibrated according to the selected textile category. Complete the applicable technical parameters to facilitate precise buyer matchmaking.
            </p>

            <!-- FIBRE COMPOSITION SECTION -->
            <div class="attr-card" id="section-fibre">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-seedling text-success me-2"></i> Fibre Composition
                    </h5>
                    <span class="badge bg-light text-dark border" id="fibrePercentCounter">Total: 0%</span>
                </div>
                <p class="text-muted small">Specify all fibres and their percentage breakdown (e.g. 100% Cotton, or 65% Polyester / 35% Cotton).</p>

                <div id="fibreRowsContainer">
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
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFibreRow()">
                    <i class="fas fa-plus me-1"></i> Add Another Fibre Blend
                </button>
            </div>

            <!-- YARN SPECIFICATIONS SECTION -->
            <div class="attr-card" id="section-yarn">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-ring text-warning me-2"></i> Yarn Specifications
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Yarn Type / Spinning Method</label>
                        <select name="yarns[0][master_id]" id="yarnMasterSelect" class="form-select" onchange="document.getElementById('yarnNameInput').value = this.options[this.selectedIndex].text;">
                            <option value="">-- Select Yarn Type (Optional) --</option>
                        </select>
                        <input type="hidden" name="yarns[0][name]" id="yarnNameInput" value="Yarn">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Yarn Count / Linear Density</label>
                        <input type="text" name="yarns[0][value]" class="form-control" placeholder="E.g. 40s Ne, 2/40s, 30/1, 150D, 75D/36F">
                    </div>
                </div>
            </div>

            <!-- FABRIC SPECIFICATIONS SECTION -->
            <div class="attr-card" id="section-fabric">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-th text-info me-2"></i> Fabric Construction & Dimensions
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fabric Weave / Knit Structure</label>
                        <select name="fabrics[0][master_id]" id="fabricMasterSelect" class="form-select" onchange="document.getElementById('fabricNameInput').value = this.options[this.selectedIndex].text;">
                            <option value="">-- Select Construction --</option>
                        </select>
                        <input type="hidden" name="fabrics[0][name]" id="fabricNameInput" value="Fabric Construction">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">GSM (Grams per Square Meter)</label>
                        <input type="text" name="fabrics[0][value]" class="form-control" placeholder="E.g. 180 GSM">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Width / Dimensions</label>
                        <input type="text" name="width_size" class="form-control" value="{{ old('width_size') }}" placeholder="E.g. 58 inches / 147 cm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Pattern / Design</label>
                        <input type="text" name="pattern" class="form-control" value="{{ old('pattern') }}" placeholder="E.g. Plain, Twill, Jacquard, Printed, Stripe">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Color / Shades Available</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="E.g. Bleached White, Indigo, Pantone 19-4052">
                    </div>
                </div>
            </div>

            <!-- PROCESSES & FINISHES SECTION -->
            <div class="attr-card" id="section-process">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-flask text-danger me-2"></i> Wet Processing & Special Finishes
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Primary Dyeing / Finishing Process</label>
                        <select name="processes[0][master_id]" id="processMasterSelect" class="form-select" onchange="document.getElementById('processNameInput').value = this.options[this.selectedIndex].text;">
                            <option value="">-- Select Applied Process --</option>
                        </select>
                        <input type="hidden" name="processes[0][name]" id="processNameInput" value="Primary Finish">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Finishing Notes & Treatments</label>
                        <input type="text" name="processes[0][value]" class="form-control" placeholder="E.g. Bio-polished, Mercerized, Anti-Microbial, Water Repellent">
                    </div>
                </div>
            </div>

            <!-- ADDITIONAL GENERAL SPECS & CERTIFICATIONS -->
            <div class="attr-card">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="fas fa-certificate text-primary me-2"></i> Standards & Additional Details
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Testing & Sustainability Certifications</label>
                        <textarea name="certifications" class="form-control" rows="3" 
                                  placeholder="E.g. OEKO-TEX Standard 100, GOTS Certified, BCI Cotton, ISO 9001, Fair Trade...">{{ old('certifications') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Additional Technical Specifications / Test Data</label>
                        <textarea name="technical_specifications" class="form-control" rows="3" 
                                  placeholder="E.g. Color fastness to washing: Grade 4-5; Shrinkage: < 2%; Tear strength: 25N...">{{ old('technical_specifications') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Material Composition (Free Text Summary)</label>
                        <input type="text" name="material_composition" id="materialCompositionSummary" class="form-control" 
                               value="{{ old('material_composition') }}" placeholder="Auto-generated or custom summary, e.g. 100% Cotton, Combed">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep(2)">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold" onclick="goToStep(4)">
                    Proceed to Media & Documents <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 4: MEDIA & DOCUMENTS -->
    <div class="portal-card wizard-step" id="step-4">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-images me-2"></i>Step 4: Media Uploads & Brochures
            </h4>
        </div>
        <div class="p-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-dashed p-3 text-center rounded-4">
                        <div class="mb-3">
                            <i class="fas fa-camera fa-3x text-primary"></i>
                        </div>
                        <h6 class="fw-bold">Product Images *</h6>
                        <p class="text-muted small">Upload high-resolution images. The first image will be set as primary.</p>
                        <input type="file" name="images[]" class="form-control mt-auto" multiple accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-2">Max 10MB each (JPG, PNG, WebP)</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-dashed p-3 text-center rounded-4">
                        <div class="mb-3">
                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                        </div>
                        <h6 class="fw-bold">Brochures & Spec Sheets</h6>
                        <p class="text-muted small">PDF catalog, technical data sheets, lab test reports.</p>
                        <input type="file" name="brochures[]" class="form-control mt-auto" multiple accept=".pdf,.docx,.xlsx">
                        <small class="text-muted d-block mt-2">Max 15MB each (PDF, DOCX)</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-dashed p-3 text-center rounded-4">
                        <div class="mb-3">
                            <i class="fas fa-video fa-3x text-info"></i>
                        </div>
                        <h6 class="fw-bold">Product Videos</h6>
                        <p class="text-muted small">Short fabric drape video, manufacturing showcase, showroom tour.</p>
                        <input type="file" name="videos[]" class="form-control mt-auto" multiple accept="video/mp4">
                        <small class="text-muted d-block mt-2">Max 10MB (MP4)</small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep(3)">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold" onclick="prepareReviewAndGo(5)">
                    Review & Finalize <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 5: REVIEW & SUBMIT -->
    <div class="portal-card wizard-step" id="step-5">
        <div class="portal-card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="fas fa-clipboard-check me-2"></i>Step 5: Review & Publish
            </h4>
        </div>
        <div class="p-4">
            <div class="alert alert-info border-0 rounded-4 shadow-sm p-3 mb-4">
                <i class="fas fa-info-circle me-1"></i>
                @if(Auth::user()->isOperator())
                    You are logged in as a <strong>Data Entry Operator</strong>. This product will be saved with status <strong>"Pending Exporter Review"</strong> for your company's authorized signatory to verify and acknowledge.
                @else
                    You are logged in as <strong>Exporter Higher Authority</strong>. Acknowledged products are published <strong>directly live to the TEFC Public Catalogue</strong>.
                @endif
            </div>

            <!-- REVIEW SUMMARY TABLE -->
            <div class="card border rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="fw-bold mb-0 text-dark">Product Summary Overview</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 25%;" class="bg-light">Product Name</th>
                                <td id="reviewProductName" class="fw-bold text-dark">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Textile Classification</th>
                                <td id="reviewClassification">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">HS Tariff Code</th>
                                <td id="reviewHsCode">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Cluster / Region</th>
                                <td id="reviewCluster">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Commercial Terms</th>
                                <td id="reviewCommercial">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Fibre Composition</th>
                                <td id="reviewFibre">—</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Key Specifications</th>
                                <td id="reviewSpecs">—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublishedCheck" checked>
                <label class="form-check-label fw-bold" for="isPublishedCheck">
                    Publish this product to the TEFC Public Catalogue upon verification
                </label>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep(4)">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </button>
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold fs-5 shadow">
                    <i class="fas fa-check-circle me-2"></i> Complete & Submit Product
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let searchDebounceTimer = null;
    let masterDataCache = {
        fibres: [],
        yarns: [],
        fabrics: [],
        processes: [],
        rules: {}
    };

    // Step navigation
    function goToStep(step) {
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');

        document.querySelectorAll('.step-pill').forEach(el => {
            const s = parseInt(el.getAttribute('data-step'));
            el.classList.remove('active');
            if (s === step) el.classList.add('active');
            if (s < step) el.classList.add('completed');
            else el.classList.remove('completed');
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateAndGoStep(step) {
        if (step === 2) {
            const prodId = document.getElementById('textile_product_id').value;
            if (!prodId) {
                alert('Please search and select a Textile Classification before proceeding.');
                return;
            }
        }
        if (step === 3) {
            const name = document.getElementById('productNameInput').value.trim();
            if (!name) {
                alert('Please enter a Product Trade Name.');
                document.getElementById('productNameInput').focus();
                return;
            }
        }
        goToStep(step);
    }

    function toggleClassificationMode(mode) {
        if (mode === 'search') {
            document.getElementById('classification-search-mode').style.display = 'block';
            document.getElementById('classification-browse-mode').style.display = 'none';
            document.getElementById('search-tab-btn').classList.add('active');
            document.getElementById('search-tab-btn').classList.remove('text-secondary');
            document.getElementById('browse-tab-btn').classList.remove('active');
            document.getElementById('browse-tab-btn').classList.add('text-secondary');
        } else {
            document.getElementById('classification-search-mode').style.display = 'none';
            document.getElementById('classification-browse-mode').style.display = 'block';
            document.getElementById('browse-tab-btn').classList.add('active');
            document.getElementById('browse-tab-btn').classList.remove('text-secondary');
            document.getElementById('search-tab-btn').classList.remove('active');
            document.getElementById('search-tab-btn').classList.add('text-secondary');
        }
    }

    // Classification Search Autocomplete
    const searchInput = document.getElementById('classificationSearchInput');
    const searchResults = document.getElementById('classificationSearchResults');
    const clearBtn = document.getElementById('clearSearchBtn');

    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(searchDebounceTimer);

        if (q.length > 0) clearBtn.style.display = 'block';
        else clearBtn.style.display = 'none';

        if (q.length < 2) {
            searchResults.innerHTML = '';
            searchResults.classList.add('d-none');
            return;
        }

        searchDebounceTimer = setTimeout(() => {
            searchResults.innerHTML = '<div class="p-3 text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Searching classifications...</div>';
            searchResults.classList.remove('d-none');

            fetch(`{{ route('api.classifications.search') }}?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(items => {
                    if (!items || items.length === 0) {
                        searchResults.innerHTML = `<div class="p-3 text-muted text-center">No classifications found matching "<strong>${escapeHtml(q)}</strong>". Try standard terms like "Cotton", "Silk", "Fabric", "Shirting"...</div>`;
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
                })
                .catch(err => {
                    searchResults.innerHTML = '<div class="p-3 text-danger text-center">Failed to load classifications.</div>';
                });
        }, 250);
    });

    function clearClassificationSearch() {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        searchResults.innerHTML = '';
        searchResults.classList.add('d-none');
        searchInput.focus();
    }

    function selectClassification(item) {
        document.getElementById('textile_product_id').value = item.id;
        document.getElementById('selectedProductName').innerText = item.name;
        document.getElementById('selectedCategoryName').innerText = item.category_name;
        document.getElementById('selectedSubcategoryName').innerText = item.subcategory_name;
        document.getElementById('selectedProductCode').innerText = 'Code: ' + item.code;
        document.getElementById('selectedHsCode').innerText = item.default_hs_code ? ('HS: ' + item.default_hs_code) : 'HS: N/A';
        document.getElementById('selectedProductDescription').innerText = item.description || '';

        // If product trade name is empty, suggest the classification name
        const nameInput = document.getElementById('productNameInput');
        if (!nameInput.value) {
            nameInput.value = item.name;
        }

        // Auto-fill HS Code if empty
        const hsInput = document.getElementById('hsCodeInput');
        if (!hsInput.value && item.default_hs_code) {
            hsInput.value = item.default_hs_code;
        }

        document.getElementById('selectedClassificationCard').classList.remove('d-none');
        searchResults.classList.add('d-none');
        searchInput.value = item.name;

        // Fetch dynamic attribute masters and rules for this category
        loadCategoryAttributes(item.category_id);
    }

    function resetClassification() {
        document.getElementById('textile_product_id').value = '';
        document.getElementById('selectedClassificationCard').classList.add('d-none');
        searchInput.value = '';
        clearBtn.style.display = 'none';
        searchInput.focus();
    }

    // Cascading Dropdown loaders
    function loadSubcategories(categoryId) {
        const subSelect = document.getElementById('browseSubcategorySelect');
        const prodSelect = document.getElementById('browseProductSelect');
        subSelect.innerHTML = '<option value="">-- Loading... --</option>';
        subSelect.disabled = true;
        prodSelect.innerHTML = '<option value="">-- First Select Subcategory --</option>';
        prodSelect.disabled = true;

        if (!categoryId) return;

        fetch(`{{ route('api.classifications.subcategories') }}?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">-- Select Subcategory --</option>';
                data.forEach(sub => {
                    html += `<option value="${sub.id}">${sub.name} (${sub.code})</option>`;
                });
                subSelect.innerHTML = html;
                subSelect.disabled = false;
            });
    }

    function loadProducts(subcategoryId) {
        const prodSelect = document.getElementById('browseProductSelect');
        prodSelect.innerHTML = '<option value="">-- Loading... --</option>';
        prodSelect.disabled = true;

        if (!subcategoryId) return;

        fetch(`{{ route('api.classifications.products') }}?subcategory_id=${subcategoryId}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">-- Select Product --</option>';
                data.forEach(p => {
                    html += `<option value="${p.id}" data-obj='${JSON.stringify(p)}'>${p.name} (${p.code})</option>`;
                });
                prodSelect.innerHTML = html;
                prodSelect.disabled = false;
            });
    }

    function selectFromDropdown(productId) {
        if (!productId) return;
        const prodSelect = document.getElementById('browseProductSelect');
        const opt = prodSelect.options[prodSelect.selectedIndex];
        const raw = opt.getAttribute('data-obj');
        if (!raw) return;
        const p = JSON.parse(raw);
        
        const catSelect = document.getElementById('browseCategorySelect');
        const subSelect = document.getElementById('browseSubcategorySelect');

        selectClassification({
            id: p.id,
            name: p.name,
            code: p.code,
            default_hs_code: p.default_hs_code,
            description: p.description,
            category_id: catSelect.value,
            category_name: catSelect.options[catSelect.selectedIndex].text,
            subcategory_id: subSelect.value,
            subcategory_name: subSelect.options[subSelect.selectedIndex].text
        });
    }

    // Dynamic Attribute loading
    function loadCategoryAttributes(categoryId) {
        fetch(`{{ route('api.classifications.category-attributes') }}?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                masterDataCache = data;
                populateMasterDropdowns();
            });
    }

    function populateMasterDropdowns() {
        // Fibres
        const fibreSelects = document.querySelectorAll('.fibre-select');
        fibreSelects.forEach(sel => {
            const curVal = sel.value;
            let html = '<option value="">-- Select Fibre Master --</option>';
            masterDataCache.fibres.forEach(f => {
                html += `<option value="${f.id}" data-name="${escapeHtml(f.name)}">${escapeHtml(f.name)} (${f.group_name})</option>`;
            });
            sel.innerHTML = html;
            if (curVal) sel.value = curVal;
        });

        // Yarns
        const yarnSelect = document.getElementById('yarnMasterSelect');
        if (yarnSelect) {
            let html = '<option value="">-- Select Yarn Type (Optional) --</option>';
            masterDataCache.yarns.forEach(y => {
                html += `<option value="${y.id}">${escapeHtml(y.name)} (${y.group_name})</option>`;
            });
            yarnSelect.innerHTML = html;
        }

        // Fabrics
        const fabricSelect = document.getElementById('fabricMasterSelect');
        if (fabricSelect) {
            let html = '<option value="">-- Select Construction (Optional) --</option>';
            masterDataCache.fabrics.forEach(fb => {
                html += `<option value="${fb.id}">${escapeHtml(fb.name)} (${fb.group_name})</option>`;
            });
            fabricSelect.innerHTML = html;
        }

        // Processes
        const procSelect = document.getElementById('processMasterSelect');
        if (procSelect) {
            let html = '<option value="">-- Select Applied Process (Optional) --</option>';
            masterDataCache.processes.forEach(p => {
                html += `<option value="${p.id}">${escapeHtml(p.name)} (${p.group_name})</option>`;
            });
            procSelect.innerHTML = html;
        }
    }

    // Fibre Rows Management
    let fibreRowCounter = 1;

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
        if (parts.length > 0 && (!summaryInput.value || summaryInput.dataset.autofilled === "true")) {
            summaryInput.value = parts.join(' / ');
            summaryInput.dataset.autofilled = "true";
        }
    }

    // Step 5 Review Preparation
    function prepareReviewAndGo(step) {
        document.getElementById('reviewProductName').innerText = document.getElementById('productNameInput').value || '—';
        document.getElementById('reviewClassification').innerText = document.getElementById('selectedProductName').innerText + ' (' + document.getElementById('selectedProductCode').innerText + ')';
        document.getElementById('reviewHsCode').innerText = document.getElementById('hsCodeInput').value || 'N/A';
        
        const clusterSelect = document.querySelector('select[name="cluster"]');
        document.getElementById('reviewCluster').innerText = clusterSelect.value || 'General / Pan-India';

        const moq = document.querySelector('input[name="moq"]').value;
        const uom = document.querySelector('select[name="unit_of_measurement"]').value;
        document.getElementById('reviewCommercial').innerText = moq ? `MOQ: ${moq} ${uom}` : `Unit: ${uom}`;

        document.getElementById('reviewFibre').innerText = document.getElementById('materialCompositionSummary').value || 'Standard specification';

        const specs = [];
        const width = document.querySelector('input[name="width_size"]').value;
        if (width) specs.push(`Width: ${width}`);
        const pattern = document.querySelector('input[name="pattern"]').value;
        if (pattern) specs.push(`Pattern: ${pattern}`);
        const color = document.querySelector('input[name="color"]').value;
        if (color) specs.push(`Color: ${color}`);
        document.getElementById('reviewSpecs').innerText = specs.length > 0 ? specs.join(' | ') : 'Detailed attributes saved';

        goToStep(step);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    // Initial check if editing or old values present
    document.addEventListener('DOMContentLoaded', function() {
        // If old textile_product_id was submitted, trigger category attributes loading
        const initialProdId = document.getElementById('textile_product_id').value;
        if (initialProdId) {
            fetch(`{{ url('api/classifications/search') }}?q=`) // Or load specific
                .then(() => {});
        }
    });
</script>
@endsection
