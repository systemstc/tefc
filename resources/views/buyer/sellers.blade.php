@extends('layouts.portal')

@section('title', 'Seller Directory — TEFC Portal')

@section('styles')
<style>
.seller-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 3rem;
}
.seller-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    display: flex;
    gap: 2rem;
    transition: all 0.3s;
}
.seller-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
}
.seller-logo {
    width: 80px;
    height: 80px;
    background: var(--sky);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
}
.seller-info { flex: 1; }
.seller-info h3 { color: var(--primary); margin-bottom: 0.5rem; font-size: 1.35rem; font-weight: 700; }
.seller-meta {
    display: flex;
    gap: 15px;
    color: var(--muted);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.seller-tags { display: flex; gap: 8px; flex-wrap: wrap; }
.tag {
    background: var(--sky2);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid var(--border);
}
.action-btns {
    align-self: center;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.btn-view {
    padding: 0.7rem 1.5rem;
    background: var(--primary);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 700;
    text-align: center;
    font-size: 0.9rem;
}
.btn-view:hover { background: var(--primary-dark); }
.btn-save {
    padding: 0.7rem 1.5rem;
    background: #fff;
    color: var(--primary);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.9rem;
}
.btn-save:hover { background: var(--sky); }
.btn-saved {
    background: #fff5f8;
    color: #c53030;
    border-color: #feb2b2;
}

@media (max-width: 768px) {
    .seller-card { flex-direction: column; text-align: center; }
    .seller-logo { margin: 0 auto; }
}
</style>
@endsection

@section('sidebar')
    <li><a href="{{ route('exporter-portal') }}" class="{{ request()->routeIs('exporter-portal') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('buyer.sellers') }}" class="{{ request()->routeIs('buyer.sellers') ? 'active' : '' }}"><i class="fas fa-store"></i> Seller Directory</a></li>
    <li><a href="{{ route('buyer.saved-sellers') }}" class="{{ request()->routeIs('buyer.saved-sellers') ? 'active' : '' }}"><i class="fas fa-heart"></i> Saved Sellers</a></li>
    <li><a href="{{ route('buyer.inquiries') }}" class="{{ request()->routeIs('buyer.inquiries') ? 'active' : '' }}"><i class="fas fa-paper-plane"></i> Sent Requests</a></li>
@endsection

@section('content')
<div class="portal-card mb-4">
    <div class="portal-card-header flex-column align-items-start gap-2 p-4">
        <h3 class="mb-0">Seller Directory</h3>
        <p class="text-muted small mb-0">Discover verified Indian textile exporters and manufacturers.</p>
        
        <form action="{{ route('buyer.sellers') }}" method="GET" class="w-100 d-flex gap-2 mt-3">
            <input type="text" name="search" class="form-control" placeholder="Search by company name, product or HS code..." value="{{ request('search') }}" style="border-radius: 8px; border: 1px solid var(--border);">
            <button type="submit" class="btn btn-primary px-4" style="border-radius: 8px; font-weight: bold; background-color: var(--primary);">Search</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="seller-grid">
    @forelse($sellers as $seller)
        <div class="seller-card d-flex flex-column gap-3">
            <div class="d-flex gap-4 flex-md-row flex-column w-100">
                <div class="seller-logo">🏢</div>
                <div class="seller-info">
                    <h3>{{ $seller->exporterProfile->company_name ?? $seller->name }}</h3>
                    <div class="seller-meta flex-wrap">
                        <span>📍 {{ $seller->exporterProfile->district ?? $seller->exporterProfile->city ?? 'India' }}, {{ $seller->exporterProfile->state ?? '' }}</span>
                        <span>📦 IEC: {{ $seller->exporterProfile->iec_code ?? 'Pending' }}</span>
                        <span>🗓️ Estd: {{ $seller->exporterProfile->year_of_establishment ?? 'N/A' }}</span>
                        <span>🧵 Segment: {{ $seller->exporterProfile->textile_segment ?? 'N/A' }}</span>
                        <span>⚡ Capacity: {{ $seller->exporterProfile->installed_capacity ?? 'N/A' }} {{ $seller->exporterProfile->capacity_unit ?? '' }}</span>
                    </div>
                    <p style="color: var(--muted); margin-bottom: 1rem; font-size: 0.95rem; line-height: 1.6;">
                        {{ Str::limit($seller->exporterProfile->profile_description ?? 'A verified textile exporter specializing in high-quality fabrics and garments.', 150) }}
                    </p>
                    <div class="seller-tags">
                        @php
                            $tags = explode(',', $seller->exporterProfile->certifications ?? 'Textiles, Garments, Fabrics');
                        @endphp
                        @foreach($tags as $tag)
                            <span class="tag">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    
                    <button class="btn btn-link p-0 text-decoration-none fw-bold mt-3 d-block toggle-profile-details" data-target="profile-details-{{ $seller->id }}" style="color: var(--primary); font-size: 0.9rem;"><i class="fas fa-chevron-down"></i> Show Business Profile & Factory Details</button>
                </div>
                <div class="action-btns align-self-md-center align-self-stretch" style="min-width: 160px;">
                    <a href="{{ route('catalogue.index') }}?exporter={{ $seller->id }}" class="btn-view d-block mb-2">View Products</a>
                    <form action="{{ route('buyer.sellers.toggle-save', $seller->id) }}" method="POST" class="w-100">
                        @csrf
                        @php $isSaved = Auth::user()->savedSellers->contains($seller->id); @endphp
                        <button type="submit" class="btn-save w-100 {{ $isSaved ? 'btn-saved' : '' }}">
                            {{ $isSaved ? '❤️ Saved' : '🤍 Save Exporter' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Expandable Profile Details Container -->
            <div id="profile-details-{{ $seller->id }}" class="profile-details-container p-3 rounded" style="display: none; background-color: var(--sky2); border: 1px solid var(--border);">
                <div class="row g-2 text-dark small">
                    <div class="col-md-6"><strong>Factory Address:</strong> <span style="white-space: pre-line;">{{ $seller->exporterProfile->factory_address ?? 'N/A' }}</span></div>
                    <div class="col-md-3"><strong>District:</strong> {{ $seller->exporterProfile->district ?? 'N/A' }}</div>
                    <div class="col-md-3"><strong>State:</strong> {{ $seller->exporterProfile->state ?? 'N/A' }}</div>
                    
                    <div class="col-12 border-top my-2"></div>
                    
                    <div class="col-md-4"><strong>Main Product HSN:</strong> {{ $seller->exporterProfile->main_product_hsn ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Installed Capacity:</strong> {{ $seller->exporterProfile->installed_capacity ?? 'N/A' }} {{ $seller->exporterProfile->capacity_unit ?? '' }}</div>
                    <div class="col-md-4"><strong>Monthly Production:</strong> {{ $seller->exporterProfile->monthly_production ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Annual Turnover:</strong> {{ $seller->exporterProfile->annual_turnover ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Exporter Status:</strong> {{ ($seller->exporterProfile->is_exporter ?? 'N') === 'Y' ? 'Yes' : 'No' }}</div>
                    @if(($seller->exporterProfile->is_exporter ?? 'N') === 'Y')
                        <div class="col-md-4"><strong>Export Turnover:</strong> {{ $seller->exporterProfile->export_turnover ?? 'N/A' }}</div>
                        <div class="col-md-4"><strong>Export Countries:</strong> {{ $seller->exporterProfile->major_exporting_countries ?? 'N/A' }}</div>
                    @endif
                    
                    <div class="col-12 border-top my-2"></div>
                    
                    <div class="col-12"><strong>Major Machineries / Technology:</strong> <p class="mb-1 text-muted">{{ $seller->exporterProfile->major_machineries ?? 'N/A' }}</p></div>
                    <div class="col-12"><strong>Certifications:</strong> <p class="mb-1 text-muted">{{ $seller->exporterProfile->certifications ?? 'N/A' }}</p></div>
                    @if($seller->exporterProfile->export_potential)
                        <div class="col-12"><strong>Export Potential:</strong> <p class="mb-1 text-muted">{{ $seller->exporterProfile->export_potential }}</p></div>
                    @endif
                    @if($seller->exporterProfile->remarks)
                        <div class="col-12"><strong>Remarks:</strong> <p class="mb-0 text-muted">{{ $seller->exporterProfile->remarks }}</p></div>
                    @endif
                    
                    @if($seller->exporterProfile->product_images && count($seller->exporterProfile->product_images) > 0)
                        <div class="col-12 border-top my-2"></div>
                        <div class="col-12">
                            <strong>Product Showcase:</strong>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($seller->exporterProfile->product_images as $path)
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $path) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);" title="Click to view full image">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="portal-card p-5 text-center">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
            <h3>No sellers found</h3>
            <p class="text-muted">Try adjusting your search terms or filters.</p>
        </div>
    @endforelse

    <div class="mt-3">
        {{ $sellers->appends(request()->input())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleLinks = document.querySelectorAll('.toggle-profile-details');
    toggleLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (container.style.display === 'none') {
                container.style.display = 'block';
                icon.className = 'fas fa-chevron-up';
                this.innerHTML = `<i class="fas fa-chevron-up"></i> Hide Business Profile & Factory Details`;
            } else {
                container.style.display = 'none';
                icon.className = 'fas fa-chevron-down';
                this.innerHTML = `<i class="fas fa-chevron-down"></i> Show Business Profile & Factory Details`;
            }
        });
    });
});
</script>
@endsection
