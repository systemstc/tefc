@extends('layouts.app')

@section('title', 'Portal Sitemap — Textile Export Facilitation Centre')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .sitemap-page {
        --sm-primary: var(--primary, #6c1340);
        --sm-accent: var(--accent, #f8b803);
        --sm-sky: var(--sky, #fce8f2);
        --sm-line: #efe3ea;
        background: #fbf8fa;
        padding-bottom: 4rem;
    }
    .sitemap-page *, .sitemap-page *::before, .sitemap-page *::after { box-sizing: border-box; }
    .sitemap-page a { text-decoration: none; }
    .sm-container { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

    /* ---------- Hero ---------- */
    .sm-hero {
        background: linear-gradient(135deg, #6c1340 0%, #4a0e2b 100%);
        color: #fff;
        padding: 36px 0 70px;
        position: relative;
        overflow: hidden;
    }
    .sm-hero::after {
        content: "";
        position: absolute; right: -60px; top: -60px;
        width: 260px; height: 260px; border-radius: 50%;
        background: rgba(248, 184, 3, .12);
    }
    .sm-hero::before {
        content: "";
        position: absolute; right: 120px; bottom: -90px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255, 255, 255, .06);
    }
    .sm-crumb { display: flex; gap: 8px; align-items: center; font-size: .82rem; margin-bottom: 14px; color: rgba(255,255,255,.75); list-style: none; padding: 0; }
    .sm-crumb a { color: #fff; opacity: .9; }
    .sm-crumb a:hover { opacity: 1; text-decoration: underline; }
    .sm-hero h1 { font-size: 2.1rem; font-weight: 800; margin: 0 0 8px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .sm-hero h1 i { color: var(--sm-accent); }
    .sm-hero p { margin: 0; max-width: 680px; color: rgba(255,255,255,.85); font-size: 1rem; line-height: 1.55; }
    .sm-hero-row { display: flex; gap: 24px; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; position: relative; z-index: 1; }
    .sm-search { position: relative; width: 340px; max-width: 100%; }
    .sm-search i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9a8591; }
    .sm-search input {
        width: 100%; border: 0; border-radius: 50px; padding: 13px 18px 13px 44px;
        font-size: .95rem; outline: none; box-shadow: 0 6px 20px rgba(0,0,0,.18);
    }
    .sm-search input:focus { box-shadow: 0 0 0 3px rgba(248,184,3,.55), 0 6px 20px rgba(0,0,0,.18); }

    /* ---------- Stats + jump bar ---------- */
    .sm-bar {
        margin-top: -38px; position: relative; z-index: 2;
        background: #fff; border: 1px solid var(--sm-line); border-radius: 16px;
        box-shadow: 0 8px 24px rgba(108, 19, 64, .08);
        padding: 14px 18px; display: flex; flex-wrap: wrap; gap: 14px 22px; align-items: center; justify-content: space-between;
    }
    .sm-stats { display: flex; gap: 22px; }
    .sm-stat b { display: block; font-size: 1.3rem; color: var(--sm-primary); line-height: 1.1; }
    .sm-stat span { font-size: .75rem; color: #6c757d; text-transform: uppercase; letter-spacing: .5px; }
    .sm-jump { display: flex; flex-wrap: wrap; gap: 8px; }
    .sm-jump a {
        font-size: .8rem; padding: 6px 13px; border-radius: 50px; color: var(--sm-primary);
        background: var(--sm-sky); border: 1px solid #f0cfe0; transition: all .15s;
    }
    .sm-jump a:hover { background: var(--sm-primary); color: #fff; border-color: var(--sm-primary); }

    /* ---------- Grid & cards ---------- */
    .sm-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 28px; }
    .sm-card {
        background: #fff; border: 1px solid var(--sm-line); border-radius: 18px;
        padding: 20px; box-shadow: 0 2px 10px rgba(108,19,64,.05);
        scroll-margin-top: 90px; transition: box-shadow .2s, transform .2s;
    }
    .sm-card:hover { box-shadow: 0 12px 28px rgba(108,19,64,.1); transform: translateY(-2px); }
    .sm-card.sm-wide { grid-column: 1 / -1; }
    .sm-head { display: flex; gap: 14px; align-items: center; padding-bottom: 14px; margin-bottom: 10px; border-bottom: 1px solid var(--sm-line); }
    .sm-ico {
        width: 46px; height: 46px; border-radius: 12px; flex: 0 0 46px;
        display: flex; align-items: center; justify-content: center; font-size: 1.15rem;
    }
    .tone-maroon .sm-ico { background: rgba(108,19,64,.09); color: #6c1340; }
    .tone-gold   .sm-ico { background: rgba(248,184,3,.18); color: #a87400; }
    .tone-green  .sm-ico { background: rgba(30,126,68,.11); color: #1e7e44; }
    .tone-blue   .sm-ico { background: rgba(13,110,253,.1); color: #0b5ed7; }
    .tone-teal   .sm-ico { background: rgba(13,148,136,.12); color: #0d9488; }
    .tone-slate  .sm-ico { background: rgba(73,80,87,.1); color: #495057; }
    .sm-head h2 { font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--sm-primary); }
    .sm-head small { color: #7a6f75; font-size: .78rem; }
    .sm-badge {
        margin-left: auto; font-size: .68rem; font-weight: 600; padding: 4px 10px; border-radius: 50px;
        background: #fff4d1; color: #8a6100; border: 1px solid #f6dd8a; white-space: nowrap;
    }
    .sm-badge i { margin-right: 4px; }

    .sm-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; }
    .sm-link {
        display: flex; align-items: center; gap: 12px; padding: 9px 10px; border-radius: 10px;
        color: #2b2b2b; transition: background .15s, padding-left .15s;
    }
    .sm-link .l-ico { width: 20px; text-align: center; color: var(--sm-accent); font-size: .95rem; flex: 0 0 20px; }
    .tone-green .sm-link .l-ico { color: #1e7e44; }
    .tone-slate .sm-link .l-ico { color: #6c757d; }
    .sm-link .l-txt { flex: 1; min-width: 0; }
    .sm-link .l-txt b { display: block; font-size: .92rem; font-weight: 600; }
    .sm-link .l-txt span { display: block; font-size: .76rem; color: #7a6f75; line-height: 1.35; }
    .sm-link .l-arr { color: #cbb7c3; font-size: .75rem; transition: transform .15s, color .15s; }
    .sm-link:hover { background: var(--sm-sky); padding-left: 14px; color: var(--sm-primary); }
    .sm-link:hover .l-arr { color: var(--sm-primary); transform: translateX(3px); }

    /* Catalogue deep-link block */
    .sm-split { display: grid; grid-template-columns: 1.1fr 1fr 1fr; gap: 26px; }
    .sm-sub { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #8d7a85; margin: 4px 0 10px; }
    .sm-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .sm-chip {
        font-size: .8rem; padding: 5px 13px; border-radius: 50px; background: #fff;
        border: 1px solid #e2d6dd; color: #495057; transition: all .15s;
    }
    .sm-chip:hover { background: var(--sm-primary); color: #fff; border-color: var(--sm-primary); }
    .sm-tip { margin-top: 14px; font-size: .78rem; color: #7a6f75; }
    .sm-tip i { color: var(--sm-accent); margin-right: 5px; }

    /* Search states */
    .sm-hide { display: none !important; }
    .sm-empty { display: none; text-align: center; padding: 50px 20px; margin-top: 28px; background: #fff; border: 1px dashed #e3c3d4; border-radius: 18px; }
    .sm-empty.show { display: block; }
    .sm-empty i { font-size: 2rem; color: #cbb7c3; margin-bottom: 10px; }

    /* Help strip */
    .sm-help {
        margin-top: 28px; background: linear-gradient(90deg, #fff 0%, var(--sm-sky) 100%);
        border: 1px solid var(--sm-line); border-radius: 16px; padding: 18px 22px;
        display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
    }
    .sm-help b { color: var(--sm-primary); }
    .sm-help a.btn-sm {
        background: var(--sm-primary); color: #fff; padding: 9px 18px; border-radius: 50px; font-size: .85rem; font-weight: 600;
    }
    .sm-help a.btn-sm:hover { background: #4a0e2b; }

    @media (max-width: 991.98px) {
        .sm-grid { grid-template-columns: repeat(2, 1fr); }
        .sm-split { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .sm-grid { grid-template-columns: 1fr; }
        .sm-hero h1 { font-size: 1.6rem; }
        .sm-search { width: 100%; }
        .sm-badge { display: none; }
    }
    @media print {
        .sm-hero, .sm-search, .sm-jump, .sm-help { display: none !important; }
        .sm-card { box-shadow: none; break-inside: avoid; }
    }
</style>
@endsection

@section('content')
@php
    // [icon, label, description, url, external?]
    $sections = [
        [
            'id' => 'primary', 'title' => 'Primary Navigation', 'sub' => 'Main menu destinations',
            'icon' => 'fa-compass', 'tone' => 'maroon', 'badge' => null,
            'links' => [
                ['fa-house', 'Home Page', 'Portal landing page', url('/')],
                ['fa-building-columns', 'About TEFC & Committee', 'Mandate, structure and background', url('/p/about')],
                ['fa-industry', 'Major Textile Clusters', 'Cluster profiles and networks', url('/p/clusters')],
                ['fa-handshake', 'FTA Strategic Benefits', 'Benefits under free trade agreements', url('/p/fta-benefits')],
                ['fa-headset', 'Online Services Desks', 'Digital single-window services', url('/p/online-services')],
                ['fa-newspaper', 'Latest News & Circulars', 'Notices, circulars and updates', url('/p/news')],
                ['fa-photo-film', 'Media Center & Gallery', 'Photos, videos and press', url('/p/media')],
                ['fa-folder-open', 'Resources', 'Downloadable documents and publications', route('resources.list')],
                ['fa-life-ring', 'Help & FAQs Support', 'Answers and support contacts', url('/p/help-support')],
            ],
        ],
        [
            'id' => 'services', 'title' => 'Facilitation Services', 'sub' => 'Trade & export desks',
            'icon' => 'fa-laptop-code', 'tone' => 'gold', 'badge' => null,
            'links' => [
                ['fa-certificate', 'Certificate of Origin (CoO)', 'Origin certification guidance', url('/p/coo')],
                ['fa-shield-halved', 'ECGC Export Credit Guarantee', 'Export credit risk cover', url('/p/ecgc')],
                ['fa-barcode', 'HS Code Search', 'Harmonized System tariff codes', url('/p/hs-code')],
                ['fa-id-card', 'Import Export Code (IEC)', 'IEC application guide', url('/p/iec')],
                ['fa-file-invoice-dollar', 'Letter of Credit (LC)', 'LC process and verification', url('/p/letter-of-credit')],
                ['fa-chart-pie', 'National KPI Dashboard', 'Performance indicators at a glance', route('kpi.dashboard')],
            ],
        ],
        [
            'id' => 'access', 'title' => 'Registration & Sign In', 'sub' => 'Access the portal',
            'icon' => 'fa-users-gear', 'tone' => 'green', 'badge' => null,
            'links' => [
                ['fa-user-plus', 'Exporter & Buyer Registration', 'Create a new member account', route('register')],
                ['fa-right-to-bracket', 'Registered Member Sign In', 'Exporters and buyers', route('login')],
                ['fa-lock', 'Officer & Admin Login', 'Centre officers and HO administrators', url('/admin-login')],
            ],
        ],
        [
            'id' => 'exporter', 'title' => 'Exporter Workspace', 'sub' => 'For registered sellers',
            'icon' => 'fa-store', 'tone' => 'teal', 'badge' => 'Login required',
            'links' => [
                ['fa-gauge', 'Exporter Dashboard & Profile', 'Company profile and account', route('exporter-portal')],
                ['fa-boxes-stacked', 'My Products', 'Listings and approval status', route('seller.products.index')],
                ['fa-circle-plus', 'Add New Product', 'Upload specifications and media', route('seller.products.create')],
                ['fa-envelope-open-text', 'Buyer Inquiries Received', 'Respond to buyer interest', route('seller.inquiries')],
                ['fa-user-pen', 'Data Entry Operators', 'Manage authorized operators', route('exporter.operators.index')],
            ],
        ],
        [
            'id' => 'buyer', 'title' => 'Buyer Workspace', 'sub' => 'For registered buyers',
            'icon' => 'fa-cart-shopping', 'tone' => 'blue', 'badge' => 'Login required',
            'links' => [
                ['fa-address-book', 'Seller Directory', 'Find verified exporters', route('buyer.sellers')],
                ['fa-bookmark', 'Saved Sellers', 'Your shortlisted exporters', route('buyer.saved-sellers')],
                ['fa-paper-plane', 'My Inquiries', 'Track inquiries you have sent', route('buyer.inquiries')],
            ],
        ],
        [
            'id' => 'official', 'title' => 'Official Links', 'sub' => 'Opens in a new tab',
            'icon' => 'fa-arrow-up-right-from-square', 'tone' => 'slate', 'badge' => null,
            'links' => [
                ['fa-up-right-from-square', 'Textiles Committee Official Portal', 'textilescommittee.gov.in', 'https://textilescommittee.gov.in', true],
                ['fa-up-right-from-square', 'Ministry of Textiles, Govt. of India', 'texmin.nic.in', 'https://texmin.nic.in', true],
            ],
        ],
    ];

    $fibres = ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Viscose', 'Acrylic', 'Nylon', 'Jute', 'Spandex'];
    $clusters = ['Tirupur', 'Surat', 'Panipat', 'Ludhiana', 'Varanasi', 'Bhilwara', 'Karur', 'Ichalkaranji', 'Jaipur', 'Kanchipuram'];
@endphp

<div class="sitemap-page">
    {{-- ================= HERO ================= --}}
    <div class="sm-hero">
        <div class="sm-container">
            <ol class="sm-crumb">
                <li><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Home</a></li>
                <li><i class="fas fa-angle-right" style="font-size:.7rem;"></i></li>
                <li>Portal Sitemap</li>
            </ol>
            <div class="sm-hero-row">
                <div>
                    <h1><i class="fas fa-sitemap"></i> TEFC Portal Sitemap</h1>
                    <p>Complete index of public pages, digital single-window services, the verified product catalogue, cluster networks and member workspaces.</p>
                </div>
                <div class="sm-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="smSearch" placeholder="Search pages, services, fibres, clusters..." autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="sm-container">
        {{-- ================= STATS + JUMP ================= --}}
        <div class="sm-bar">
            <div class="sm-stats">
                <div class="sm-stat"><b id="smCountPages">0</b><span>Pages</span></div>
                <div class="sm-stat"><b>{{ count($sections) + 1 }}</b><span>Sections</span></div>
                <div class="sm-stat"><b>{{ count($clusters) }}</b><span>Clusters</span></div>
            </div>
            <div class="sm-jump">
                @foreach($sections as $s)
                    <a href="#sec-{{ $s['id'] }}">{{ $s['title'] }}</a>
                @endforeach
                <a href="#sec-catalogue">Product Catalogue</a>
            </div>
        </div>

        {{-- ================= SECTIONS ================= --}}
        <div class="sm-grid" id="smGrid">
            @foreach($sections as $s)
                <section class="sm-card tone-{{ $s['tone'] }}" id="sec-{{ $s['id'] }}">
                    <div class="sm-head">
                        <div class="sm-ico"><i class="fas {{ $s['icon'] }}"></i></div>
                        <div>
                            <h2>{{ $s['title'] }}</h2>
                            <small>{{ $s['sub'] }}</small>
                        </div>
                        @if($s['badge'])
                            <span class="sm-badge"><i class="fas fa-lock"></i>{{ $s['badge'] }}</span>
                        @endif
                    </div>
                    <ul class="sm-list">
                        @foreach($s['links'] as $l)
                            <li>
                                <a href="{{ $l[3] }}" class="sm-link sm-item sm-page"
                                   data-text="{{ strtolower($l[1] . ' ' . $l[2] . ' ' . $s['title']) }}"
                                   @if(!empty($l[4])) target="_blank" rel="noopener noreferrer" @endif>
                                    <span class="l-ico"><i class="fas {{ $l[0] }}"></i></span>
                                    <span class="l-txt"><b>{{ $l[1] }}</b><span>{{ $l[2] }}</span></span>
                                    <i class="fas fa-chevron-right l-arr"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach

            {{-- ===== Product catalogue with deep links into the multi-select filters ===== --}}
            <section class="sm-card sm-wide tone-gold" id="sec-catalogue">
                <div class="sm-head">
                    <div class="sm-ico"><i class="fas fa-boxes-stacked"></i></div>
                    <div>
                        <h2>Verified Product Catalogue</h2>
                        <small>Browse approved textile products by fibre or manufacturing cluster</small>
                    </div>
                </div>
                <div class="sm-split">
                    <div>
                        <div class="sm-sub">Catalogue</div>
                        <ul class="sm-list">
                            <li>
                                <a href="{{ route('catalogue.index') }}" class="sm-link sm-item sm-page" data-text="verified product catalogue all products">
                                    <span class="l-ico"><i class="fas fa-border-all"></i></span>
                                    <span class="l-txt"><b>All Products</b><span>Full verified catalogue with filters</span></span>
                                    <i class="fas fa-chevron-right l-arr"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="sm-tip"><i class="fas fa-lightbulb"></i>Filters are multi-select: combine several fibres, clusters and categories in one search.</div>
                    </div>
                    <div>
                        <div class="sm-sub">By Fibre / Material</div>
                        <div class="sm-chips">
                            @foreach($fibres as $f)
                                <a href="{{ route('catalogue.index', ['fibre' => [$f]]) }}" class="sm-chip sm-item" data-text="{{ strtolower($f . ' fibre material catalogue') }}">{{ $f }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <div class="sm-sub">By Manufacturing Cluster</div>
                        <div class="sm-chips">
                            @foreach($clusters as $c)
                                <a href="{{ route('catalogue.index', ['cluster' => [$c]]) }}" class="sm-chip sm-item" data-text="{{ strtolower($c . ' cluster manufacturing catalogue') }}">{{ $c }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="sm-empty" id="smEmpty">
            <i class="fas fa-magnifying-glass"></i>
            <h5 class="fw-bold mb-1" style="color: var(--sm-primary);">No matching pages</h5>
            <p class="text-muted mb-0">Try a different keyword, such as "export", "cotton" or "login".</p>
        </div>

        {{-- ================= HELP STRIP ================= --}}
        <div class="sm-help">
            <div><b>Can't find what you need?</b> Our help desk can point you to the right service or officer.</div>
            <a href="{{ url('/p/help-support') }}" class="btn-sm"><i class="fas fa-headset me-1"></i> Go to Help &amp; Support</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('smSearch');
    var items = document.querySelectorAll('.sm-item');
    var cards = document.querySelectorAll('.sm-card');
    var empty = document.getElementById('smEmpty');

    document.getElementById('smCountPages').textContent = document.querySelectorAll('.sm-page').length;

    function run() {
        var q = input.value.trim().toLowerCase();
        var shown = 0;
        items.forEach(function (el) {
            var match = !q || el.getAttribute('data-text').indexOf(q) !== -1;
            // hide the wrapping <li> for list links, the chip itself otherwise
            var target = el.closest('li') || el;
            target.classList.toggle('sm-hide', !match);
            if (match) shown++;
        });
        cards.forEach(function (c) {
            var any = Array.prototype.some.call(c.querySelectorAll('.sm-item'), function (el) {
                return !(el.closest('li') || el).classList.contains('sm-hide');
            });
            c.classList.toggle('sm-hide', !any);
        });
        empty.classList.toggle('show', shown === 0);
    }
    input.addEventListener('input', run);
});
</script>
@endsection