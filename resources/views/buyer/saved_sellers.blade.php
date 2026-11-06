@extends('layouts.portal')

@section('title', 'Saved Sellers — TEFC Portal')

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
        <h3 class="mb-0">Saved Sellers</h3>
        <p class="text-muted small mb-0">Manage your bookmarked Indian textile exporters.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="seller-grid">
    @forelse($sellers as $seller)
        <div class="seller-card">
            <div class="seller-logo">🏢</div>
            <div class="seller-info">
                <h3>{{ $seller->exporterProfile->company_name ?? $seller->name }}</h3>
                <div class="seller-meta">
                    <span>📍 {{ $seller->exporterProfile->city ?? 'India' }}</span>
                    <span>📦 IEC: {{ $seller->exporterProfile->iec_code ?? 'Pending' }}</span>
                </div>
                <p style="color: var(--muted); margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                    {{ Str::limit($seller->exporterProfile->profile_description ?? 'A verified textile exporter specializing in high-quality fabrics and garments.', 150) }}
                </p>
                <div class="seller-tags">
                    @php
                        $tags = explode(',', $seller->exporterProfile->hs_codes ?? 'Textiles, Garments, Fabrics');
                    @endphp
                    @foreach($tags as $tag)
                        <span class="tag">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
            <div class="action-btns">
                <a href="{{ route('catalogue.index') }}?exporter={{ $seller->id }}" class="btn-view">View Products</a>
                <form action="{{ route('buyer.sellers.toggle-save', $seller->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-save btn-saved">
                        ❤️ Remove
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="portal-card p-5 text-center">
            <div style="font-size: 3rem; margin-bottom: 1rem;">❤️</div>
            <h3>No saved sellers yet</h3>
            <p class="text-muted mb-4">Go to the Seller Directory to start bookmarking exporters.</p>
            <a href="{{ route('buyer.sellers') }}" class="btn btn-primary px-4 py-2 fw-bold" style="background-color: var(--primary); border-radius: 8px;">Browse Directory</a>
        </div>
    @endforelse

    <div class="mt-3">
        {{ $sellers->links() }}
    </div>
</div>
@endsection
