@extends('layouts.app')

@section('title', 'Portal Sitemap — Textile Export Facilitation Centre')

@section('content')
<div class="sitemap-page-wrapper py-5" style="background: #fbf9fb; min-height: 80vh;">
    <div class="container" style="max-width: 1200px;">
        <!-- Breadcrumb & Page Head -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--primary);"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Portal Sitemap</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2" style="color: var(--primary); font-size: 2.2rem;">
                <i class="fas fa-sitemap me-2" style="color: var(--accent);"></i> TEFC Portal Sitemap
            </h1>
            <p class="text-muted" style="font-size: 1.05rem;">
                Complete architectural index of public pages, digital single-window services, cluster networks, and trade facilitation desks.
            </p>
            <div style="height: 3px; width: 60px; background: var(--accent); border-radius: 2px;"></div>
        </div>

        <div class="row g-4 mt-2">
            <!-- Col 1: Main Portal Navigation -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; background: rgba(108, 21, 63, 0.08); color: var(--primary);">
                            <i class="fas fa-compass fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold fs-5 mb-0" style="color: var(--primary);">Primary Navigation</h3>
                            <span class="text-muted small">Main header menu destinations</span>
                        </div>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.95rem;">
                        <li><a href="{{ url('/') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Home Page</a></li>
                        <li><a href="{{ url('/p/about') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> About TEFC &amp; Committee</a></li>
                        <li><a href="{{ url('/p/clusters') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Major Textile Clusters</a></li>
                        <li><a href="{{ url('/p/fta-benefits') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> FTA Strategic Benefits</a></li>
                        <li><a href="{{ url('/p/online-services') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Online Services Desks</a></li>
                        <li><a href="{{ url('/p/news') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Latest News &amp; Circulars</a></li>
                        <li><a href="{{ url('/p/media') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Media Center &amp; Gallery</a></li>
                        <li><a href="{{ url('/p/help-support') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-angle-right me-2" style="color: var(--accent);"></i> Help &amp; FAQs Support</a></li>
                    </ul>
                </div>
            </div>

            <!-- Col 2: Online Digital Services -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; background: rgba(248, 184, 3, 0.15); color: #b87b00;">
                            <i class="fas fa-laptop-code fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold fs-5 mb-0" style="color: var(--primary);">Facilitation Services</h3>
                            <span class="text-muted small">Specialized trade &amp; export desks</span>
                        </div>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.95rem;">
                        <li><a href="{{ url('/p/coo') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-certificate me-2 text-warning"></i> Certificate of Origin (CoO)</a></li>
                        <li><a href="{{ url('/p/ecgc') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-shield-alt me-2 text-warning"></i> ECGC Export Credit Guarantee</a></li>
                        <li><a href="{{ url('/p/hs-code') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-barcode me-2 text-warning"></i> Harmonized System (HS) Code Search</a></li>
                        <li><a href="{{ url('/p/iec') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-id-card me-2 text-warning"></i> Import Export Code (IEC) Guide</a></li>
                        <li><a href="{{ url('/p/letter-of-credit') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i> Letter of Credit (LC) Verification</a></li>
                        <li><a href="{{ route('catalogue.index') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-boxes-stacked me-2 text-warning"></i> Verified Product Catalogue</a></li>
                        <li><a href="{{ route('kpi.dashboard') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-chart-pie me-2 text-warning"></i> National KPI Dashboard</a></li>
                    </ul>
                </div>
            </div>

            <!-- Col 3: Exporter Onboarding & Portals -->
            <div class="col-lg-4 col-md-12">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; background: rgba(30, 126, 68, 0.1); color: #1e7e44;">
                            <i class="fas fa-users-gear fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold fs-5 mb-0" style="color: var(--primary);">Portals &amp; Access</h3>
                            <span class="text-muted small">Registration and authentication</span>
                        </div>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.95rem;">
                        <li><a href="{{ route('register') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-user-plus me-2 text-success"></i> Exporter &amp; Buyer Registration</a></li>
                        <li><a href="{{ url('/login') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-sign-in-alt me-2 text-success"></i> Registered Member Sign In</a></li>
                        <li><a href="{{ url('/admin-login') }}" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-lock me-2 text-success"></i> Official Officer Admin Login</a></li>
                        <li><a href="http://textilescommittee.nic.in" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-external-link-alt me-2 text-muted"></i> Textiles Committee Official Portal</a></li>
                        <li><a href="https://texmin.nic.in" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex align-items-center py-1"><i class="fas fa-external-link-alt me-2 text-muted"></i> Ministry of Textiles, Govt. of India</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
