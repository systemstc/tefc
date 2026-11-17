@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root { --primary: #6c1340; --accent: #f8b803; --sky: #fce8f2; }
    .bg-primary { background-color: var(--primary) !important; }
    .text-primary { color: var(--primary) !important; }
    .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
    .btn-outline-primary:hover { background-color: var(--primary); color: #fff; }
    .btn-primary { background-color: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background-color: #4a0e2b; border-color: #4a0e2b; }
    .pagination .page-item.active .page-link {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #fff !important;
    }
    .pagination .page-link { color: var(--primary); }
    .pagination .page-link:hover { background-color: var(--sky); color: var(--primary); }
    
    .cat-page { --line: #efe3ea; }

    /* ---------- Sidebar ---------- */
    .cat-page .cat-sidebar {
        position: sticky;
        top: 16px;               /* increase if your header is sticky */
        align-self: flex-start;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        padding-right: 4px;
    }
    .cat-page .cat-sidebar::-webkit-scrollbar { width: 5px; }
    .cat-page .cat-sidebar::-webkit-scrollbar-thumb { background: #dcc3d0; border-radius: 10px; }

    .cat-page .cat-sidebar .card {
        border: 1px solid var(--line) !important;
        box-shadow: 0 2px 10px rgba(108, 19, 64, .05) !important;
    }
    .cat-page .cat-sidebar .card-header { background: #fff; border-bottom: 1px solid var(--line) !important; }
    .cat-page .cat-sidebar h6 { font-size: .95rem; letter-spacing: .2px; }

    /* Categories accordion */
    .cat-accordion .accordion-button {
        font-size: .9rem;
        font-weight: 600;
        color: #2b2b2b;
        padding: .7rem 1rem;
        background: #fff;
    }
    .cat-accordion .accordion-button:not(.collapsed) {
        background-color: var(--sky);
        color: var(--primary);
        box-shadow: none;
    }
    .cat-accordion .accordion-button:focus { box-shadow: none; }
    .cat-accordion .accordion-button::after { transform: scale(.8); }
    .cat-accordion .accordion-item { border-color: var(--line); }
    .cat-accordion .accordion-body { background: #fdf8fb !important; padding: .5rem !important; }

    .cat-sub-item {
        display: flex;
        align-items: center;
        padding: 7px 12px;
        margin-bottom: 2px;
        color: #495057;
        text-decoration: none;
        font-size: .85rem;
        border-radius: 8px;
        transition: all .15s;
    }
    .cat-sub-item:hover { background: var(--sky); color: var(--primary); }
    .cat-sub-item.active { background: var(--primary); color: #fff; font-weight: 600; }
    .cat-sub-item.active:hover { background: #4a0e2b; color: #fff; }

    /* Fibre chips */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 13px;
        background: #fff;
        border: 1px solid #e2d6dd;
        border-radius: 50px;
        font-size: .8rem;
        color: #495057;
        margin: 0 6px 8px 0;
        text-decoration: none;
        transition: all .15s;
    }
    .filter-chip:hover { border-color: var(--primary); color: var(--primary); background: var(--sky); }
    .filter-chip.active { background: var(--primary); color: #fff; border-color: var(--primary); }
    .filter-chip.active::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: .6rem;
        margin-right: 6px;
    }

    /* Cluster list */
    .cat-page .list-group-item-action { transition: all .15s; margin-bottom: 1px; }
    .cat-page .list-group-item-action:hover { background: var(--sky) !important; color: var(--primary) !important; }

    /* ---------- Active filters bar ---------- */
    .cat-page > .card.bg-light {
        background: #fff !important;
        border: 1px solid var(--line) !important;
        box-shadow: 0 2px 10px rgba(108, 19, 64, .05);
    }
    .cat-page > .card.bg-light .badge {
        background: var(--sky) !important;
        color: var(--primary) !important;
        border: 1px solid #f0cfe0 !important;
        border-radius: 50px;
        padding: .45rem .5rem .45rem .8rem !important;
        font-weight: 500;
        font-size: .75rem;
    }
    .cat-page > .card.bg-light .badge a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px; height: 18px;
        margin-left: 6px;
        border-radius: 50%;
        background: #fff;
        color: var(--primary) !important;
        font-size: .85rem;
        line-height: 1;
    }
    .cat-page > .card.bg-light .badge a:hover { background: var(--primary); color: #fff !important; }

    /* ---------- Search box ---------- */
    .cat-page .input-group .form-control { border-radius: 50px 0 0 50px; padding-left: 18px; border-color: #e2d6dd; }
    .cat-page .input-group .btn { border-radius: 0 50px 50px 0; padding: 0 18px; }
    .cat-page .input-group .form-control:focus { box-shadow: none; border-color: var(--primary); }

    /* ---------- Results bar ---------- */
    .cat-results-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 10px 16px;
        margin-bottom: 1.25rem;
        font-size: .88rem;
        color: #6c757d;
    }

    /* ---------- Product cards ---------- */
    .product-card {
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04) !important;
        transition: transform .25s, box-shadow .25s, border-color .25s;
    }
    .product-card:hover {
        transform: translateY(-4px);
        border-color: #e3c3d4;
        box-shadow: 0 14px 28px rgba(108, 19, 64, .13) !important;
    }
    .product-card .position-relative img { transition: transform .5s; }
    .product-card:hover .position-relative img { transform: scale(1.06); }
    .product-card h6 { font-size: .98rem; }
    .product-card h6 a:hover { color: var(--primary) !important; }
    .product-card .badge.bg-light {
        background: var(--sky) !important;
        color: var(--primary) !important;
        border-color: transparent !important;
        font-weight: 600;
    }
    .product-card .border-top { border-top-color: var(--line) !important; }
    .product-card .btn-outline-primary { border-radius: 50px; padding: 4px 12px !important; }

    /* Empty state */
    .cat-page .card.bg-light.p-5 { background: #fdf8fb !important; border: 1px dashed #e3c3d4 !important; }

    /* Mobile / tablet: no sticky sidebar */
    @media (max-width: 991.98px) {
        .cat-page .cat-sidebar { position: static; max-height: none; overflow: visible; }
    }
</style>
@endsection

@section('content')
<div class="container py-5 cat-page">
    <!-- Top Banner & Header -->
    @php
        // Multi-select helpers: filters live in the URL as arrays (?fibre[]=Cotton&fibre[]=Nylon)
        $vals = fn (string $key) => array_values(array_filter(
            \Illuminate\Support\Arr::wrap(request()->query($key)),
            fn ($v) => $v !== null && $v !== ''
        ));
        $isOn = fn (string $key, $value) => in_array((string) $value, array_map('strval', $vals($key)), true);
        // URL that adds $value to the filter if absent, removes it if present
        $toggle = function (string $key, $value) use ($vals) {
            $q = request()->query();
            unset($q['page']);
            $cur = array_map('strval', $vals($key));
            $value = (string) $value;
            $cur = in_array($value, $cur, true) ? array_values(array_diff($cur, [$value])) : array_merge($cur, [$value]);
            if ($cur) { $q[$key] = $cur; } else { unset($q[$key]); }
            return route('catalogue.index', $q);
        };
    @endphp

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-1" style="color: var(--primary);">Textile Product Catalogue</h2>
            <p class="text-muted mb-0">Discover verified Indian textile products categorized by standard classification taxonomy.</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="d-flex justify-content-md-end gap-2">
                <form action="{{ route('catalogue.index') }}" method="GET" class="d-flex flex-grow-1" style="max-width: 380px;">
                    @foreach(['category_id', 'subcategory_id', 'fibre', 'fabric', 'process', 'cluster', 'category'] as $hk)
                        @foreach($vals($hk) as $hv)
                            <input type="hidden" name="{{ $hk }}[]" value="{{ $hv }}">
                        @endforeach
                    @endforeach
                    
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search product, fibre, HS code..." value="{{ request('q') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#generalEnquiryModal" style="background-color: var(--accent); color: #1b1b18; font-weight: 600; white-space: nowrap;">
                    <i class="fas fa-paper-plane me-1"></i> Send Enquiry
                </button>
            </div>
        </div>
    </div>

    <!-- Active Filters Row -->
    @if(request()->hasAny(['category_id', 'subcategory_id', 'fibre', 'fabric', 'process', 'cluster', 'category', 'q']))
        @php $allSubs = $textileCategories->pluck('subcategories')->flatten(); @endphp
        <div class="card bg-light border-0 rounded-4 p-3 mb-4">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="small fw-bold text-muted me-2"><i class="fas fa-filter me-1"></i> Active Filters:</span>
                @foreach($vals('category_id') as $cid)
                    <span class="badge bg-white text-dark border px-2 py-1">
                        Category: {{ optional($textileCategories->firstWhere('id', $cid))->name ?? $cid }}
                        <a href="{{ $toggle('category_id', $cid) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endforeach
                @foreach($vals('subcategory_id') as $sid)
                    <span class="badge bg-white text-dark border px-2 py-1">
                        Subcategory: {{ optional($allSubs->firstWhere('id', $sid))->name ?? $sid }}
                        <a href="{{ $toggle('subcategory_id', $sid) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endforeach
                @foreach($vals('category') as $c)
                    <span class="badge bg-white text-dark border px-2 py-1">
                        {{ $c }}
                        <a href="{{ $toggle('category', $c) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endforeach
                @foreach($vals('fibre') as $f)
                    <span class="badge bg-white text-dark border px-2 py-1">
                        Fibre: {{ $f }}
                        <a href="{{ $toggle('fibre', $f) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endforeach
                @foreach($vals('cluster') as $c)
                    <span class="badge bg-white text-dark border px-2 py-1">
                        Cluster: {{ $c }}
                        <a href="{{ $toggle('cluster', $c) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endforeach
                @if(request('q'))
                    <span class="badge bg-white text-dark border px-2 py-1">
                        "{{ request('q') }}"
                        <a href="{{ route('catalogue.index', request()->except(['q', 'page'])) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                    </span>
                @endif
                <a href="{{ route('catalogue.index') }}" class="btn btn-link btn-sm text-danger p-0 ms-auto text-decoration-none">Clear All Filters</a>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Sidebar filters -->
        <div class="col-lg-3 col-md-4 mb-4 cat-sidebar">
            <!-- TEXTILE HIERARCHICAL CLASSIFICATION -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-sitemap me-2 text-primary"></i> Textile Categories</h6>
                    <a href="{{ route('catalogue.index', request()->except(['category_id', 'subcategory_id', 'category', 'page'])) }}" class="small text-muted text-decoration-none">All</a>
                </div>
                <div class="accordion accordion-flush cat-accordion" id="categoryAccordion">
                    @foreach($textileCategories as $idx => $tCat)
                        @php
                            $isCatSelected = $isOn('category_id', $tCat->id);
                            $hasSubSelected = $tCat->subcategories->contains(fn($s) => $isOn('subcategory_id', $s->id));
                            $isCatOpen = $isCatSelected || $hasSubSelected;
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $tCat->id }}">
                                <button class="accordion-button {{ $isCatOpen ? '' : 'collapsed' }} py-2 px-3 small" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $tCat->id }}">
                                    <!-- <span class="badge bg-secondary-subtle text-secondary me-2" style="font-size:0.65rem;">{{ $tCat->code }}</span> -->
                                    {{ $tCat->name }}
                                </button>
                            </h2>
                            {{-- no data-bs-parent: several categories can stay open together --}}
                            <div id="collapse{{ $tCat->id }}" class="accordion-collapse collapse {{ $isCatOpen ? 'show' : '' }}">
                                <div class="accordion-body p-2 bg-light">
                                    <a href="{{ $toggle('category_id', $tCat->id) }}" 
                                       class="cat-sub-item fw-bold {{ $isCatSelected ? 'active' : '' }}">
                                        @if($isCatSelected) <i class="fas fa-check me-1"></i> @endif All in {{ $tCat->name }}
                                    </a>
                                    @foreach($tCat->subcategories as $sub)
                                        @php $isSubActive = $isOn('subcategory_id', $sub->id); @endphp
                                        <a href="{{ $toggle('subcategory_id', $sub->id) }}" 
                                           class="cat-sub-item {{ $isSubActive ? 'active' : '' }}">
                                            @if($isSubActive) <i class="fas fa-check me-1"></i> @endif{{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- FIBRE / MATERIAL FILTER -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-seedling me-2 text-success"></i> Fibre / Material</h6>
                    <div class="d-flex flex-wrap">
                        @php 
                            $popularFibres = ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Viscose', 'Acrylic', 'Nylon', 'Jute', 'Spandex']; 
                        @endphp
                        @foreach($popularFibres as $f)
                            <a href="{{ $toggle('fibre', $f) }}" 
                               class="filter-chip {{ $isOn('fibre', $f) ? 'active' : '' }}">
                                {{ $f }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- CLUSTERS FILTER -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-map-marker-alt me-2 text-danger"></i> Manufacturing Clusters</h6>
                    @php $clusters = ['Tirupur', 'Surat', 'Panipat', 'Ludhiana', 'Varanasi', 'Bhilwara', 'Karur', 'Ichalkaranji', 'Jaipur', 'Kanchipuram']; @endphp
                    <div class="list-group list-group-flush small">
                        <a href="{{ route('catalogue.index', request()->except(['cluster', 'page'])) }}" 
                           class="list-group-item list-group-item-action border-0 py-1 px-2 rounded {{ empty($vals('cluster')) ? 'fw-bold text-primary' : 'text-muted' }}">
                            All Clusters
                        </a>
                        @foreach($clusters as $c)
                            <a href="{{ $toggle('cluster', $c) }}" 
                               class="list-group-item list-group-item-action border-0 py-1 px-2 rounded {{ $isOn('cluster', $c) ? 'fw-bold text-primary bg-light' : 'text-muted' }}">
                                @if($isOn('cluster', $c)) <i class="fas fa-check me-1"></i> @endif{{ $c }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9 col-md-8">
            <div class="cat-results-bar">
                <span><i class="fas fa-box-open me-2 text-primary"></i>Showing <strong>{{ $products->total() }}</strong> verified product{{ $products->total() == 1 ? '' : 's' }}</span>
                @if($products->total())
                    <span class="small">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                @endif
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 product-card shadow-sm">
                        <!-- Primary Image -->
                        @php 
                            $primaryImg = $product->media->where('media_type', 'image')->where('is_primary', true)->first() 
                                       ?? $product->media->where('media_type', 'image')->first(); 
                        @endphp
                        <div class="position-relative overflow-hidden bg-light text-center" style="height: 210px;">
                            @if($primaryImg)
                                <img src="{{ asset('storage/' . $primaryImg->file_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fas fa-boxes fa-2x mb-2 text-secondary opacity-50"></i>
                                    <span class="small">No Image Provided</span>
                                </div>
                            @endif

                            @if($product->cluster)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 text-white" style="font-size: 0.7rem;">
                                    <i class="fas fa-map-pin me-1"></i> {{ explode(' ', $product->cluster)[0] }}
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-3 d-flex flex-column">
                            <!-- Classification Category Badge -->
                            <div class="mb-1">
                                @if($product->textileCategory)
                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $product->textileCategory->name }}</span>
                                    @if($product->textileSubcategory)
                                        <span class="text-muted" style="font-size: 0.65rem;">&bull; {{ $product->textileSubcategory->name }}</span>
                                    @endif
                                @elseif(is_array($product->category) && count($product->category) > 0)
                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $product->category[0] }}</span>
                                @endif
                            </div>

                            <!-- Product Name -->
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                                <a href="{{ route('catalogue.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                            </h6>

                            <!-- Material / Fibre composition -->
                            <p class="text-muted small mb-2 text-truncate" style="font-size: 0.8rem;">
                                {{ $product->material_composition ?: ($product->fibres->first()?->attribute_name ? $product->fibres->pluck('attribute_name')->implode(', ') : 'Verified Textile Product') }}
                            </p>

                            <!-- Commercial & Capacity Details -->
                            <div class="mt-auto pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center small text-muted mb-2" style="font-size: 0.75rem;">
                                    <span>
                                        @if($product->moq)
                                            <strong>MOQ:</strong> {{ $product->moq }} {{ $product->unit_of_measurement ?? '' }}
                                        @else
                                            <strong>HS:</strong> {{ $product->hs_code ?: 'Tariff Code' }}
                                        @endif
                                    </span>
                                    @if($product->production_capacity)
                                        <span title="Production Capacity"><i class="fas fa-tachometer-alt me-1"></i> {{ Str::limit($product->production_capacity, 15) }}</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small text-truncate" style="max-width: 130px; font-size: 0.75rem; color: var(--primary);">
                                        <i class="fas fa-industry me-1"></i>
                                        {{ optional($product->user->exporterProfile)->company_name ?? $product->user->name ?? 'Verified Exporter' }}
                                    </div>
                                    <a href="{{ route('catalogue.show', $product) }}" class="btn btn-sm btn-outline-primary px-2 py-1" style="font-size: 0.8rem;">
                                        View Specs <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 py-5 text-center">
                    <div class="card border-0 bg-light p-5 rounded-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-dark">No Products Found</h5>
                        <p class="text-muted">No textile products matched your selected criteria or search term.</p>
                        <div>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-primary px-4">
                                <i class="fas fa-undo me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- General Enquiry Modal -->
<div class="modal fade" id="generalEnquiryModal" tabindex="-1" aria-labelledby="generalEnquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background-color: var(--primary); padding: 1.5rem 2rem;">
                <h5 class="modal-title" id="generalEnquiryModalLabel" style="font-weight: 700; letter-spacing: 0.5px;">
                    <i class="fas fa-paper-plane me-2" style="color: var(--accent);"></i>Submit General Trade Enquiry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #fdfdfc;">
                @auth
                    @if(auth()->user()->hasRole('buyer'))
                        <!-- Success message container -->
                        <div id="enquirySuccessMessage" class="d-none text-center py-4">
                            <div class="mb-3">
                                <span class="display-1 text-success"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <h4 class="text-dark fw-bold">Thank You!</h4>
                            <p class="text-muted px-4">Your general trade enquiry has been broadcasted successfully. Verified exporters matching your requirements will contact you shortly.</p>
                            <button type="button" class="btn btn-outline-secondary mt-3 px-4" data-bs-dismiss="modal">Close</button>
                        </div>

                        <form id="generalEnquiryForm" method="POST" action="{{ route('catalogue.general-inquiry') }}">
                            @csrf
                            
                            <div class="alert alert-danger d-none" id="enquiryErrorAlert">
                                <ul class="mb-0" id="enquiryErrorList"></ul>
                            </div>

                            <div class="row g-3">
                                <!-- Buyer Contact Details -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Your Name *</label>
                                    <input type="text" name="buyer_name" class="form-control" placeholder="E.g. John Doe" value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Email Address *</label>
                                    <input type="email" name="buyer_email" class="form-control" placeholder="john@example.com" value="{{ auth()->user()->email ?? '' }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Phone Number</label>
                                    <input type="text" name="buyer_phone" class="form-control" placeholder="Optional" value="{{ auth()->user()->phone ?? '' }}">
                                </div>

                                <div class="col-12 my-2">
                                    <hr class="text-muted opacity-25">
                                </div>

                                <!-- Product Requirements -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Category *</label>
                                    <select name="category" class="form-select" required>
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($textileCategories as $tCat)
                                            <option value="{{ $tCat->name }}">{{ $tCat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Material *</label>
                                    <select name="material" class="form-select" required>
                                        <option value="" disabled selected>Select Material</option>
                                        <option value="Cotton">Cotton</option>
                                        <option value="Polyester">Polyester</option>
                                        <option value="Silk">Silk</option>
                                        <option value="Wool">Wool</option>
                                        <option value="Linen">Linen</option>
                                        <option value="Nylon">Nylon</option>
                                        <option value="Acrylic">Acrylic</option>
                                        <option value="Viscose/Rayon">Viscose/Rayon</option>
                                        <option value="Jute">Jute</option>
                                        <option value="Organic/Recycled Blend">Organic/Recycled Blend</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Quantity Required *</label>
                                    <input type="text" name="quantity_required" class="form-control" placeholder="E.g. 500 Meters, 1000 Kgs" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Pattern *</label>
                                    <input type="text" name="pattern" class="form-control" placeholder="E.g. Plain, Dyed, Printed, Jacquard" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Width / Size *</label>
                                    <input type="text" name="width_size" class="form-control" placeholder="E.g. 44 inches, King Size" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark small">Additional Requirements</label>
                                    <textarea name="additional_requirements" class="form-control" rows="3" placeholder="Describe any technical specifications, certifications, packing instructions, delivery timelines, etc."></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submitEnquiryBtn" class="btn text-white px-5" style="background-color: var(--primary); font-weight: 600;">
                                    <span class="spinner-border spinner-border-sm d-none me-1" id="btnSpinner" role="status" aria-hidden="true"></span>
                                    Submit Enquiry
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Logged in as non-buyer -->
                        <div class="text-center py-4 px-3">
                            <div class="mb-3 text-warning">
                                <i class="fas fa-exclamation-triangle fa-4x animate-pulse"></i>
                            </div>
                            <h4 class="text-dark fw-bold">Buyer Account Required</h4>
                            <p class="text-muted mt-2 px-md-5">
                                You are currently logged in as a <strong>{{ ucfirst(auth()->user()->roles->first()->name ?? 'user') }}</strong>. 
                                Sourcing enquiries can only be submitted by registered buyers.
                            </p>
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Not logged in -->
                    <div class="text-center py-4 px-3">
                        <div class="mb-3" style="color: var(--primary);">
                            <i class="fas fa-user-shield fa-4x"></i>
                        </div>
                        <h4 class="text-dark fw-bold">Sign In Required</h4>
                        <p class="text-muted mt-2 px-md-5">
                            To submit trade enquiries and connect with Indian exporters, you must be logged in with a registered buyer account.
                        </p>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                            <a href="{{ route('login') }}?redirect=back" class="btn text-white px-4" style="background-color: var(--primary); font-weight: 600;">
                                Log In
                            </a>
                            <a href="{{ route('register') }}?role=buyer&redirect=back" class="btn btn-outline-primary px-4" style="border-color: var(--primary); color: var(--primary); font-weight: 600;">
                                Register as Buyer
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('generalEnquiryForm');
    const btn = document.getElementById('submitEnquiryBtn');
    const spinner = document.getElementById('btnSpinner');
    const errorAlert = document.getElementById('enquiryErrorAlert');
    const errorList = document.getElementById('enquiryErrorList');
    const successMsg = document.getElementById('enquirySuccessMessage');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            btn.disabled = true;
            spinner.classList.remove('d-none');
            errorAlert.classList.add('d-none');
            errorList.innerHTML = '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 || res.body.success) {
                    form.classList.add('d-none');
                    successMsg.classList.remove('d-none');
                    form.reset();
                } else {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    errorAlert.classList.remove('d-none');
                    
                    if (res.body.errors) {
                        Object.values(res.body.errors).forEach(errs => {
                            errs.forEach(err => {
                                const li = document.createElement('li');
                                li.textContent = err;
                                errorList.appendChild(li);
                            });
                        });
                    } else if (res.body.message) {
                        const li = document.createElement('li');
                        li.textContent = res.body.message;
                        errorList.appendChild(li);
                    } else {
                        const li = document.createElement('li');
                        li.textContent = 'An unexpected error occurred. Please try again.';
                        errorList.appendChild(li);
                    }
                }
            })
            .catch(err => {
                btn.disabled = false;
                spinner.classList.add('d-none');
                errorAlert.classList.remove('d-none');
                const li = document.createElement('li');
                li.textContent = 'Network error. Please check your connection and try again.';
                errorList.appendChild(li);
            });
        });
    }

    const modalEl = document.getElementById('generalEnquiryModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            form.classList.remove('d-none');
            successMsg.classList.add('d-none');
            errorAlert.classList.add('d-none');
            errorList.innerHTML = '';
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
    }
});
</script>
@endsection