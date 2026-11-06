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
    .list-group-item.active { background-color: var(--primary) !important; border-color: var(--primary) !important; }
    .pagination .page-item.active .page-link {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #fff !important;
    }
    .pagination .page-link {
        color: var(--primary);
    }
    .pagination .page-link:hover {
        background-color: var(--sky);
        color: var(--primary);
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2>Product Catalogue</h2>
            <p class="text-muted mb-0">Discover verified textile products from Indian exporters.</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="d-flex justify-content-md-end gap-2">
                <form action="{{ route('catalogue.index') }}" method="GET" class="d-flex flex-grow-1" style="max-width: 350px;">
                    <input type="text" name="q" class="form-control me-2" placeholder="Search products..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#generalEnquiryModal" style="background-color: var(--accent); color: #1b1b18; font-weight: 600; white-space: nowrap; transition: all 0.2s;">
                    <i class="fas fa-paper-plane me-1"></i> Send Enquiry
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar filters -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Categories</h5>
                    @php $categories = ['Home Textiles', 'Technical Textiles', 'Handloom Products', 'Carpets', 'Woollen Products', 'Garments', 'Yarn', 'Fabrics', 'Sustainable Textiles', 'Organic Products', 'Other']; @endphp
                    <div class="list-group list-group-flush">
                        <a href="{{ route('catalogue.index', request()->except('category')) }}" class="list-group-item list-group-item-action @if(!request('category')) active @endif">All Categories</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('catalogue.index', array_merge(request()->query(), ['category' => $cat])) }}" class="list-group-item list-group-item-action @if(request('category') == $cat) active @endif">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Clusters</h5>
                    @php $clusters = ['Tirupur', 'Surat', 'Panipat', 'Ludhiana', 'Varanasi', 'Bhilwara', 'Kanchipuram', 'Bhagalpur', 'Kolkata', 'Other']; @endphp
                    <div class="list-group list-group-flush">
                        <a href="{{ route('catalogue.index', request()->except('cluster')) }}" class="list-group-item list-group-item-action @if(!request('cluster')) active @endif">All Clusters</a>
                        @foreach($clusters as $c)
                            <a href="{{ route('catalogue.index', array_merge(request()->query(), ['cluster' => $c])) }}" class="list-group-item list-group-item-action @if(request('cluster') == $c) active @endif">
                                {{ $c }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm product-card transition-all" style="transition: transform 0.2s;">
                        @php $img = $product->media->where('media_type', 'image')->first(); @endphp
                        @if($img)
                            <img src="{{ asset('storage/' . $img->file_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                            <p class="card-text text-muted small mb-2">{{ is_array($product->category) ? implode(', ', $product->category) : $product->category }}</p>
                            <p class="card-text small text-truncate">{{ Str::limit($product->material_composition, 50) }}</p>
                            <p class="mb-0 small text-primary"><i class="fas fa-industry me-1"></i> {{ optional($product->user->exporterProfile)->company_name ?? $product->user->name ?? 'Verified Exporter' }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('catalogue.show', $product) }}" class="btn btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">No products found matching your criteria.</div>
                </div>
                @endforelse
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.btn-accent:hover {
    background-color: #d19a02 !important;
    border-color: #d19a02 !important;
}
</style>

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
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
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
                console.error(err);
            });
        });
    }

    // Reset modal when closed
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
