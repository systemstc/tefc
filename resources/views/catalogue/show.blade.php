@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .container { max-width: 1200px; margin: auto; padding: 0 15px; }
    :root { --primary: #6c1340; --accent: #f8b803; --sky: #fce8f2; }
    .bg-primary { background-color: var(--primary) !important; }
    .text-primary { color: var(--primary) !important; }
    .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
    .btn-outline-primary:hover { background-color: var(--primary); color: #fff; }
    .btn-primary { background-color: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background-color: #4a0e2b; border-color: #4a0e2b; }
    .badge.bg-secondary { background-color: var(--accent) !important; color: #000; font-weight: 700; }
    .theme-alert { background-color: var(--sky); border: 1px solid #e8d0dc; }
    .img-zoom-container { overflow: hidden; border-radius: 8px; }
    .img-zoom-container img { transition: transform 0.5s ease; cursor: zoom-in; }
    .img-zoom-container img:hover { transform: scale(1.15); }
    .related-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: 0.3s; }
</style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track interactions
        document.querySelectorAll('.track-interaction').forEach(element => {
            element.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                const metadata = this.getAttribute('data-metadata') ? JSON.parse(this.getAttribute('data-metadata')) : {};
                
                fetch('{{ route("catalogue.interaction", $product) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        interaction_type: type,
                        metadata: metadata
                    })
                });
            });
        });

        // Track video play
        document.querySelectorAll('video').forEach(video => {
            video.addEventListener('play', function() {
                fetch('{{ route("catalogue.interaction", $product) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        interaction_type: 'video_play',
                        metadata: { src: this.querySelector('source').src }
                    })
                });
            }, { once: true }); // Only track first play per load
        });
    });
</script>
@endsection

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalogue.index') }}" class="text-decoration-none text-muted">Catalogue</a></li>
                @if($product->textileCategory)
                    <li class="breadcrumb-item"><a href="{{ route('catalogue.index', ['category_id' => $product->textileCategory->id]) }}" class="text-decoration-none text-muted">{{ $product->textileCategory->name }}</a></li>
                @elseif(is_array($product->category) && count($product->category) > 0)
                    <li class="breadcrumb-item"><a href="{{ route('catalogue.index', ['category' => $product->category[0]]) }}" class="text-decoration-none text-muted">{{ $product->category[0] }}</a></li>
                @endif
                @if($product->textileSubcategory)
                    <li class="breadcrumb-item"><a href="{{ route('catalogue.index', ['category_id' => $product->textileCategory?->id, 'subcategory_id' => $product->textileSubcategory->id]) }}" class="text-decoration-none text-muted">{{ $product->textileSubcategory->name }}</a></li>
                @endif
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Product Media -->
        <div class="col-md-6 mb-4">
            @php $images = $product->media->where('media_type', 'image'); @endphp
            @if($images->count() > 0)
                <div class="mb-4">
                    @foreach($images as $img)
                        <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                            <div class="card-body p-0 img-zoom-container position-relative">
                                @if($img->is_primary)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-success shadow-sm" style="font-size: 0.75rem;">
                                        <i class="fas fa-check-circle me-1"></i> Main Photo
                                    </span>
                                @endif
                                <img src="{{ asset('storage/' . $img->file_path) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; max-height: 500px; object-fit: contain; background: #f8f9fa;">
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center py-5 bg-light rounded">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No images available for this product</p>
                    </div>
                </div>
            @endif
            
            @php $videos = $product->media->where('media_type', 'video'); @endphp
            @if($videos->count() > 0)
                <div class="mt-4">
                    <h5 class="mb-3" style="color: var(--primary);"><i class="fas fa-video me-2"></i>Product Videos</h5>
                    @foreach($videos as $vid)
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body p-0 overflow-hidden rounded">
                                <video width="100%" controls>
                                    <source src="{{ asset('storage/' . $vid->file_path) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Information -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <!-- Classification Header -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        @if($product->textileCategory)
                            <span class="badge" style="background-color: var(--primary); color: #fff;">{{ $product->textileCategory->name }}</span>
                            @if($product->textileSubcategory)
                                <span class="badge bg-light text-dark border">{{ $product->textileSubcategory->name }}</span>
                            @endif
                            @if($product->textileProduct)
                                <span class="badge bg-secondary-subtle text-secondary border">Code: {{ $product->textileProduct->code }}</span>
                            @endif
                        @elseif(is_array($product->category))
                            @foreach($product->category as $cat)
                                <span class="badge bg-light text-dark border">{{ $cat }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-light text-dark border">{{ $product->category }}</span>
                        @endif

                        @if($product->hs_code)
                            <span class="badge bg-info-subtle text-info border">HS: {{ $product->hs_code }}</span>
                        @endif
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-3">{{ $product->name }}</h1>
                    
                    <!-- Exporter / Supplier Profile -->
                    <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background-color: var(--sky); border: 1px solid #ebd0de;">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                <i class="fas fa-industry"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 fw-bold text-dark">{{ optional($product->user->exporterProfile)->company_name ?? $product->user->name ?? 'Verified Exporter' }}</h6>
                            <small class="text-success fw-semibold"><i class="fas fa-certificate me-1"></i> Verified Indian Exporter &bull; Ministry of Textiles Recognized</small>
                        </div>
                    </div>

                    <!-- FIBRE COMPOSITION BREAKDOWN -->
                    @if($product->fibres->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-seedling text-success me-1"></i> Fibre Composition</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->fibres as $f)
                                    <span class="badge bg-white text-dark border px-3 py-2 shadow-sm rounded-pill" style="font-size: 0.85rem;">
                                        <strong>{{ $f->percentage ? ($f->percentage . '%') : '' }}</strong> {{ $f->attribute_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- TECHNICAL SPECIFICATIONS TABLE -->
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-sliders-h text-primary me-1"></i> Technical Specifications</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm small mb-0">
                            <tbody>
                                @if($product->material_composition)
                                <tr>
                                    <th class="bg-light text-muted w-35">Material Summary</th>
                                    <td class="fw-semibold">{{ $product->material_composition }}</td>
                                </tr>
                                @endif

                                @if($product->yarns->count() > 0)
                                    @foreach($product->yarns as $y)
                                    <tr>
                                        <th class="bg-light text-muted">Yarn ({{ $y->attribute_name }})</th>
                                        <td>{{ $y->attribute_value ?: 'Standard specification' }}</td>
                                    </tr>
                                    @endforeach
                                @endif

                                @if($product->fabrics->count() > 0)
                                    @foreach($product->fabrics as $fb)
                                    <tr>
                                        <th class="bg-light text-muted">Fabric ({{ $fb->attribute_name }})</th>
                                        <td>{{ $fb->attribute_value ?: 'Standard specification' }}</td>
                                    </tr>
                                    @endforeach
                                @endif

                                @if($product->width_size)
                                <tr>
                                    <th class="bg-light text-muted">Width / Size</th>
                                    <td>{{ $product->width_size }}</td>
                                </tr>
                                @endif

                                @if($product->pattern)
                                <tr>
                                    <th class="bg-light text-muted">Pattern / Structure</th>
                                    <td>{{ $product->pattern }}</td>
                                </tr>
                                @endif

                                @if($product->color)
                                <tr>
                                    <th class="bg-light text-muted">Color / Shades</th>
                                    <td>{{ $product->color }}</td>
                                </tr>
                                @endif

                                @if($product->processes->count() > 0)
                                    @foreach($product->processes as $pr)
                                    <tr>
                                        <th class="bg-light text-muted">Finishing / Treatment</th>
                                        <td>{{ $pr->attribute_name }}: {{ $pr->attribute_value }}</td>
                                    </tr>
                                    @endforeach
                                @endif

                                @if($product->moq)
                                <tr>
                                    <th class="bg-light text-muted">Minimum Order (MOQ)</th>
                                    <td class="fw-bold text-primary">{{ $product->moq }} {{ $product->unit_of_measurement ?? 'Units' }}</td>
                                </tr>
                                @endif

                                @if($product->production_capacity)
                                <tr>
                                    <th class="bg-light text-muted">Production Capacity</th>
                                    <td>{{ $product->production_capacity }}</td>
                                </tr>
                                @endif

                                @if($product->cluster)
                                <tr>
                                    <th class="bg-light text-muted">Manufacturing Cluster</th>
                                    <td><i class="fas fa-map-pin text-danger me-1"></i> {{ $product->cluster }}</td>
                                </tr>
                                @endif

                                @if($product->certifications)
                                <tr>
                                    <th class="bg-light text-muted">Certifications</th>
                                    <td>{{ $product->certifications }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if($product->tags)
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tags text-muted me-1"></i> Keywords</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(explode(',', $product->tags) as $tag)
                                    <span class="badge bg-light text-secondary border">{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($product->technical_specifications)
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Additional Technical Notes</h6>
                        <p class="text-muted small mb-0 p-3 bg-light rounded">{{ $product->technical_specifications }}</p>
                    </div>
                    @endif

                    @if($product->contact_information)
                    <div class="theme-alert rounded p-3 mb-4">
                        <h6 class="mb-2" style="color: var(--primary);"><i class="fas fa-envelope me-2"></i>Product Specific Contact</h6>
                        <p class="mb-0 small">{{ $product->contact_information }}</p>
                    </div>
                    @endif

                    <div class="theme-alert rounded p-3 mb-4">
                        <h6 class="mb-2" style="color: var(--primary);"><i class="fas fa-address-card me-2"></i>Supplier Direct Connect</h6>
                        <p class="mb-1 small"><i class="fas fa-building me-2 text-muted"></i> {{ optional($product->user->exporterProfile)->company_name ?? $product->user->name }}</p>
                        @if($product->user->email)
                        <p class="mb-1 small"><i class="fas fa-envelope me-2 text-muted"></i> <a href="mailto:{{ $product->user->email }}" style="color: var(--primary); text-decoration: none;" class="track-interaction" data-type="email_click">{{ $product->user->email }}</a></p>
                        @endif
                        @if(optional($product->user->exporterProfile)->contact_phone)
                        <p class="mb-1 small"><i class="fas fa-phone me-2 text-muted"></i> {{ $product->user->exporterProfile->contact_phone }}</p>
                        @endif
                        @if(optional($product->user->exporterProfile)->website)
                        <p class="mb-0 small"><i class="fas fa-globe me-2 text-muted"></i> <a href="{{ $product->user->exporterProfile->website }}" target="_blank" style="color: var(--primary); text-decoration: none;" class="track-interaction" data-type="website_click">{{ $product->user->exporterProfile->website }}</a></p>
                        @endif
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid var(--primary) !important;">
                        <div class="card-body">
                            <h5 class="mb-2" style="color: var(--primary);"><i class="fas fa-paper-plane me-2"></i>Direct Buyer Enquiry</h5>
                            <p class="text-muted small mb-3">Submit your specifications, request custom quotes, or verify dispatch lead times with this exporter.</p>
                            <button type="button" class="btn btn-primary w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#productEnquiryModal">
                                <i class="fas fa-paper-plane me-1"></i> Send Inquiry to Exporter
                            </button>
                        </div>
                    </div>

                    @php $brochures = $product->media->where('media_type', 'brochure'); @endphp
                    @if($brochures->count() > 0)
                        <h6 class="fw-bold text-dark mb-2">Technical Downloads</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($brochures as $brochure)
                                <a href="{{ asset('storage/' . $brochure->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm track-interaction" data-type="brochure_download" data-metadata='{"file": "{{ basename($brochure->file_path) }}"}'>
                                    <i class="fas fa-file-pdf me-1 text-danger"></i> Download Document
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="mb-4" style="color: var(--primary);">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 related-card">
                    @php $rImg = $related->media->where('media_type', 'image')->first(); @endphp
                    @if($rImg)
                        <img src="{{ asset('storage/' . $rImg->file_path) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <span class="text-muted small">No Image</span>
                        </div>
                    @endif
                    <div class="card-body p-3">
                        <h6 class="card-title text-truncate mb-1">{{ $related->name }}</h6>
                        <p class="text-muted small mb-2">{{ is_array($related->category) ? implode(', ', array_slice($related->category, 0, 1)) : $related->category }}</p>
                        <a href="{{ route('catalogue.show', $related) }}" class="btn btn-sm btn-outline-primary w-100 stretched-link">View Product</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Product Enquiry Modal -->
<div class="modal fade" id="productEnquiryModal" tabindex="-1" aria-labelledby="productEnquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background-color: var(--primary); padding: 1.5rem 2rem;">
                <h5 class="modal-title" id="productEnquiryModalLabel" style="font-weight: 700; letter-spacing: 0.5px;">
                    <i class="fas fa-paper-plane me-2" style="color: var(--accent);"></i>Enquire About: {{ $product->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #fdfdfc;">
                @auth
                    @if(auth()->user()->hasRole('buyer'))
                        <!-- Success message container -->
                        <div id="productEnquirySuccessMessage" class="d-none text-center py-4">
                            <div class="mb-3">
                                <span class="display-1 text-success"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <h4 class="text-dark fw-bold">Enquiry Sent!</h4>
                            <p class="text-muted px-4">Your inquiry for <strong>{{ $product->name }}</strong> has been sent to the exporter successfully. The exporter will get in touch with you shortly.</p>
                            <button type="button" class="btn btn-outline-secondary mt-3 px-4" data-bs-dismiss="modal">Close</button>
                        </div>

                        <form id="productEnquiryForm" method="POST" action="{{ route('catalogue.inquiry', $product) }}">
                            @csrf
                            
                            <div class="alert alert-danger d-none" id="productEnquiryErrorAlert">
                                <ul class="mb-0" id="productEnquiryErrorList"></ul>
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
                                    @php
                                        $categories = ['Home Textiles', 'Technical Textiles', 'Handloom Products', 'Carpets', 'Woollen Products', 'Garments', 'Yarn', 'Fabrics', 'Sustainable Textiles', 'Organic Products', 'Other'];
                                        $productPrimaryCat = '';
                                        if (is_array($product->category) && count($product->category) > 0) {
                                            $productPrimaryCat = $product->category[0];
                                        } elseif (is_string($product->category)) {
                                            $productPrimaryCat = $product->category;
                                        }
                                    @endphp
                                    <select name="category" class="form-select" required>
                                        <option value="" disabled>Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" @if($cat === $productPrimaryCat) selected @endif>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Material *</label>
                                    @php
                                        $materials = ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Nylon', 'Acrylic', 'Viscose/Rayon', 'Jute', 'Organic/Recycled Blend', 'Other'];
                                        $productMaterial = 'Cotton';
                                        foreach ($materials as $mat) {
                                            if (stripos($product->material_composition, $mat) !== false) {
                                                $productMaterial = $mat;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <select name="material" class="form-select" required>
                                        <option value="" disabled>Select Material</option>
                                        @foreach($materials as $mat)
                                            <option value="{{ $mat }}" @if($mat === $productMaterial) selected @endif>{{ $mat }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Quantity Required *</label>
                                    <input type="text" name="quantity_required" class="form-control" placeholder="E.g. 500 Meters, 1000 Kgs" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Pattern *</label>
                                    <input type="text" name="pattern" class="form-control" placeholder="E.g. Plain, Dyed, Printed, Jacquard" value="{{ $product->pattern ?? '' }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark small">Width / Size *</label>
                                    <input type="text" name="width_size" class="form-control" placeholder="E.g. 44 inches, King Size" value="{{ $product->width_size ?? '' }}" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark small">Additional Requirements</label>
                                    <textarea name="additional_requirements" class="form-control" rows="3" placeholder="Describe any technical specifications, packing instructions, delivery timelines, etc."></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submitProductEnquiryBtn" class="btn text-white px-5" style="background-color: var(--primary); font-weight: 600;">
                                    <span class="spinner-border spinner-border-sm d-none me-1" id="productBtnSpinner" role="status" aria-hidden="true"></span>
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
    const form = document.getElementById('productEnquiryForm');
    const btn = document.getElementById('submitProductEnquiryBtn');
    const spinner = document.getElementById('productBtnSpinner');
    const errorAlert = document.getElementById('productEnquiryErrorAlert');
    const errorList = document.getElementById('productEnquiryErrorList');
    const successMsg = document.getElementById('productEnquirySuccessMessage');

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
                console.error(err);
            });
        });
    }

    // Reset modal when closed
    const modalEl = document.getElementById('productEnquiryModal');
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
