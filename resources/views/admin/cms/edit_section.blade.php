@extends('layouts.portal')

@section('title', 'Edit Section — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}" class="active"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="portal-card">
    <div class="portal-card-header">
        <div>
            <h3 class="mb-1">Edit Section: {{ $section->title }}</h3>
            <p class="text-muted small mb-0">Modifying content for page: <strong>{{ $section->page->title }}</strong></p>
        </div>
        <a href="{{ route('admin.cms') }}" class="btn btn-outline-secondary btn-sm">Back to CMS</a>
    </div>

    <div class="p-4">
        <form action="{{ route('admin.sections.update', $section->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border bg-white shadow-sm mb-2">
                        <div class="card-body p-3">
                            <div class="row align-items-center g-3">
                                <div class="col-lg-7">
                                    <label class="form-label fw-bold mb-1">
                                        Section Display Title 
                                        @if($section->type === 'hero') 
                                            <span class="badge bg-light text-muted border fw-normal ms-1">Optional for Hero Banner</span> 
                                        @else 
                                            <span class="text-danger">*</span> 
                                        @endif
                                    </label>
                                    <input type="text" name="title" value="{{ $section->title }}" class="form-control" placeholder="{{ $section->type === 'hero' ? 'Optional (leave blank for pure banner images without text overlay)' : 'Section Display Title' }}" {{ $section->type === 'hero' ? '' : 'required' }}>
                                    @if($section->type === 'hero')
                                        <div class="form-text text-muted small">For Hero Section, Title, Subtitle, and Buttons are completely optional. If left blank, the homepage will display pure, full-width banner graphics.</div>
                                    @endif
                                </div>
                                <div class="col-lg-5">
                                    <label class="form-label fw-bold mb-1 d-flex justify-content-between align-items-center">
                                        <span>Live Portal Display Status</span>
                                        <span id="status_state_badge" class="badge {{ $section->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $section->is_active ? 'Active' : 'Inactive (Hidden)' }}
                                        </span>
                                    </label>
                                    <input type="hidden" name="is_active_submitted" value="1">
                                    <div class="p-2 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas {{ $section->is_active ? 'fa-eye text-success' : 'fa-eye-slash text-secondary' }} fs-5" id="status_icon"></i>
                                            <div>
                                                <div class="fw-bold small" id="status_heading">
                                                    {{ $section->is_active ? 'Section is Live' : 'Section is Hidden' }}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem;" id="status_subtext">
                                                    {{ $section->is_active ? 'Shown to all public website visitors' : 'Hidden from public website' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="is_active_toggle" {{ $section->is_active ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($section->type == 'hero')
                    <!-- Dimension Guidelines & Banner Specifications -->
                    <div class="col-12">
                        <div class="card border-primary border-opacity-25 bg-primary-subtle bg-opacity-10 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-primary mt-1">
                                        <i class="fas fa-ruler-combined fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                            <h6 class="fw-bold text-dark mb-0 fs-6">
                                                <i class="fas fa-image me-1 text-primary"></i> Recommended Hero Banner Dimensions
                                            </h6>
                                            <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
                                                1920 px (Width) &times; 500 px (Height)
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            To keep the hero section small in height and ensure all banner graphics display uniformly without cropping or distortion, design and upload all banners at <strong>1920 &times; 500 px</strong> (Aspect Ratio <strong>~3.84 : 1</strong>).
                                        </p>
                                        <div class="d-flex flex-wrap gap-2 small">
                                            <span class="badge bg-white text-dark border p-2">
                                                <i class="fas fa-arrows-alt-h text-primary me-1"></i> <strong>Width:</strong> 1920 px (min. 1600 px)
                                            </span>
                                            <span class="badge bg-white text-dark border p-2">
                                                <i class="fas fa-arrows-alt-v text-primary me-1"></i> <strong>Height:</strong> 500 px (compact)
                                            </span>
                                            <span class="badge bg-white text-dark border p-2">
                                                <i class="fas fa-desktop text-primary me-1"></i> <strong>Aspect Ratio:</strong> ~3.84 : 1 (Panoramic)
                                            </span>
                                            <span class="badge bg-white text-dark border p-2">
                                                <i class="fas fa-file-image text-primary me-1"></i> <strong>Formats:</strong> PNG, JPG, WebP
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Display Height Setting -->
                    <div class="col-12">
                        <div class="p-3 border rounded-3 bg-white shadow-sm">
                            <div class="row align-items-center g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-dark mb-1">
                                        <i class="fas fa-arrows-alt-v text-primary me-1"></i> Hero Section Display Height (Desktop)
                                    </label>
                                    <div class="small text-muted">
                                        The hero section desktop height is set to <strong>600 px</strong>. You can fine-tune the display height here. Tablet and mobile heights scale down automatically.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold text-muted">Desktop Height</span>
                                        <input type="number" name="banner_height" value="{{ $section->content['banner_height'] ?? 600 }}" min="280" max="850" step="10" class="form-control fw-bold text-primary" placeholder="600">
                                        <span class="input-group-text bg-light">px</span>
                                    </div>
                                    <div class="form-text small text-muted text-end">Default: 600 px</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">Fallback Banner Image</label>
                                <span class="badge bg-secondary-subtle text-secondary small">1920 &times; 500 px</span>
                            </div>
                            @if(isset($section->content['background_image']))
                                @php
                                    $bgPath = public_path($section->content['background_image']);
                                    $bgSize = file_exists($bgPath) ? @getimagesize($bgPath) : null;
                                @endphp
                                <div class="mb-2 text-center bg-white p-2 border rounded">
                                    <img src="{{ asset($section->content['background_image']) }}" class="img-fluid rounded border mb-1" style="max-height: 85px; object-fit: cover;">
                                    @if($bgSize)
                                        <div class="small text-muted" style="font-size: 0.72rem;">Current: <strong>{{ $bgSize[0] }} &times; {{ $bgSize[1] }} px</strong></div>
                                    @endif
                                </div>
                            @endif
                            <input type="file" name="background_image" class="form-control form-control-sm">
                            <div class="form-text small text-primary fw-semibold mt-1">
                                <i class="fas fa-info-circle me-1"></i> Upload size: 1920 &times; 500 px. Used if no slider images are present.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">Banner Slider Images</label>
                                <span class="badge bg-primary-subtle text-primary small">Upload size: 1920 &times; 500 px</span>
                            </div>
                            @if(!empty($section->content['images']))
                                <div class="d-flex gap-2 mb-3 flex-wrap" id="existingSliderImagesList">
                                    @foreach($section->content['images'] as $imgIndex => $img)
                                        @php
                                            $imgPath = public_path($img);
                                            $imgSz = file_exists($imgPath) ? @getimagesize($imgPath) : null;
                                        @endphp
                                        <div class="border rounded bg-white p-2 text-center shadow-sm" id="img_wrap_{{ $imgIndex }}" style="width: 130px;">
                                            <img src="{{ asset($img) }}" class="rounded mb-1" style="width: 100%; height: 55px; object-fit: cover;">
                                            @if($imgSz)
                                                <div class="badge bg-light text-dark border mb-1 d-block text-truncate" style="font-size: 0.65rem;" title="{{ $imgSz[0] }} × {{ $imgSz[1] }} px">
                                                    {{ $imgSz[0] }} &times; {{ $imgSz[1] }} px
                                                </div>
                                            @endif
                                            <input type="hidden" name="existing_slider_images[]" value="{{ $img }}">
                                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 w-100" style="font-size: 0.7rem;" onclick="removeSliderImage({{ $imgIndex }})">
                                                <i class="fas fa-trash-alt me-1"></i> Remove
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="slider_images[]" class="form-control form-control-sm" multiple>
                            <div class="form-text small text-primary fw-semibold mt-1">
                                <i class="fas fa-info-circle me-1"></i> Upload size: 1920 &times; 500 px. Hold Ctrl/Cmd to select multiple images.
                            </div>
                        </div>
                    </div>

                    <!-- Optional Text Overlay & Action Buttons -->
                    <div class="col-12">
                        <div class="card border bg-white shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold text-primary"><i class="fas fa-heading me-1"></i> Optional Hero Text & Buttons (Leave blank for banner-only)</span>
                                <span class="badge bg-secondary-subtle text-secondary fw-normal">Optional</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Hero Subtitle</label>
                                        <textarea name="subtitle" class="form-control" rows="2" placeholder="Optional subtitle text (leave empty if not needed)">{{ $section->content['subtitle'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Primary Button Text</label>
                                        <input type="text" name="primary_btn_text" value="{{ $section->content['primary_btn_text'] ?? '' }}" class="form-control" placeholder="e.g. Explore Services (Optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Primary Button URL</label>
                                        <input type="text" name="primary_btn_url" value="{{ $section->content['primary_btn_url'] ?? '' }}" class="form-control" placeholder="e.g. /p/online-services (Optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Secondary Button Text</label>
                                        <input type="text" name="secondary_btn_text" value="{{ $section->content['secondary_btn_text'] ?? '' }}" class="form-control" placeholder="e.g. View Clusters (Optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Secondary Button URL</label>
                                        <input type="text" name="secondary_btn_url" value="{{ $section->content['secondary_btn_url'] ?? '' }}" class="form-control" placeholder="e.g. /p/clusters (Optional)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($section->type == 'announcement')
                    <div class="col-12">
                        <div class="alert alert-primary d-flex align-items-center mb-2" role="alert">
                            <i class="fas fa-bullhorn fa-2x me-3 text-primary"></i>
                            <div>
                                <strong class="d-block text-dark">Live Marquee Announcement Strip</strong>
                                <span class="small text-muted">This strip appears directly beneath the hero slider on the homepage. You can manage the announcement badge, news ticker items, categories, and direct action links. Visitors can pause or resume the marquee at any time.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Badge / Pill Text</label>
                            <input type="text" name="badge" value="{{ $section->content['badge'] ?? 'Latest Announcements' }}" class="form-control" placeholder="e.g. Latest Announcements">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">"View All" Button Text</label>
                            <input type="text" name="view_all_text" value="{{ $section->content['view_all_text'] ?? 'All Updates' }}" class="form-control" placeholder="e.g. All Updates">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">"View All" Target URL</label>
                            <input type="text" name="view_all_url" value="{{ $section->content['view_all_url'] ?? '/p/news' }}" class="form-control" placeholder="e.g. /p/news">
                        </div>
                    </div>
                @elseif($section->type == 'clusters_grid')
                    <!-- Map Card Active / Inactive Switch -->
                    <div class="col-12">
                        <div class="p-3 border rounded-3 bg-white shadow-sm mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-circle bg-primary-subtle text-primary">
                                        <i class="fas fa-map-marked-alt fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <label class="form-check-label fw-bold text-dark mb-0 fs-6" for="show_map_card">
                                                Interactive India Map Card (District Facilitation Grid)
                                            </label>
                                            <span id="map_card_badge" class="badge {{ !empty($section->content['show_map_card']) ? 'bg-success' : 'bg-secondary' }}">
                                                {{ !empty($section->content['show_map_card']) ? 'Active (Shown on Homepage)' : 'Inactive (Hidden from Homepage)' }}
                                            </span>
                                        </div>
                                        <span class="small text-muted d-block">
                                            Keep this <strong>OFF / Inactive</strong> if you only want the Cluster Cards displayed on the homepage. Turn <strong>ON</strong> to also display the top interactive India map and metrics summary bar.
                                        </span>
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="show_map_card_submitted" value="1">
                                    <input class="form-check-input" type="checkbox" role="switch" name="show_map_card" value="1" id="show_map_card" {{ !empty($section->content['show_map_card']) ? 'checked' : '' }} style="width: 3.2em; height: 1.6em; cursor: pointer;">
                                </div>
                            </div>
                            <div id="map_card_auto_feedback" class="mt-2 text-success small fw-bold d-none">
                                <i class="fas fa-check-circle me-1"></i> <span id="map_card_feedback_text">Setting updated successfully!</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-primary d-flex align-items-center mb-2" role="alert">
                            <i class="fas fa-map-marked-alt fa-2x me-3 text-primary"></i>
                            <div>
                                <strong class="d-block text-dark">Interactive Animated Map & District Grid Active</strong>
                                <span class="small text-muted">The website displays an interactive vector map of India with animated location pins showing how many textile units/MSMEs are present in each covered district. <strong>Adding images is completely optional</strong> — the district information badge is generated automatically!</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section Intro Description -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Section Subtitle / Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief introduction to TEFC textile clusters across India">{{ $section->content['description'] ?? 'Centres of Excellence driving regional textile entrepreneurship, technological upgradation, and export facilitation across India\'s premier manufacturing districts.' }}</textarea>
                        </div>
                    </div>

                    <!-- Dedicated Textile Clusters & Districts List Editor -->
                    <div class="col-12">
                        <div class="p-4 border rounded-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <div>
                                    <h5 class="fw-bold text-primary mb-1"><i class="fas fa-layer-group me-2"></i> Facilitation Centres & Covered Districts</h5>
                                    <p class="text-muted small mb-0">Manage the operational centres, designated coverage districts, and how many units/MSMEs are present in each district.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-semibold" id="btnLoadOfficialCentres">
                                        <i class="fas fa-magic me-1 text-primary"></i> Auto-Fill Official 6 TEFC Centres
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-editor-btn" data-key="items">
                                        <i class="fas fa-code me-1"></i> Switch to Raw JSON
                                    </button>
                                </div>
                            </div>

                            <!-- Visual Clusters Cards List -->
                            <div id="clusters_visual_list" class="d-flex flex-column gap-3 mb-3">
                                <!-- Populated via Javascript -->
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <button type="button" class="btn btn-sm btn-primary fw-bold" id="btnAddCluster">
                                    <i class="fas fa-plus me-1"></i> Add Another Cluster Centre
                                </button>
                                <span class="text-muted small"><i class="fas fa-info-circle me-1"></i> Changes synchronize automatically to the live homepage map.</span>
                            </div>

                            <!-- Hidden Raw JSON Input for submission -->
                            <div id="json_container_items" class="d-none mt-3">
                                <textarea name="json_items" id="json_input_items" class="form-control bg-dark text-light" rows="10" style="font-family: 'Courier New', Courier, monospace; font-size: 0.85rem;">{{ json_encode($section->content['items'] ?? [], JSON_PRETTY_PRINT) }}</textarea>
                                <div class="form-text small text-muted mt-2">Modify the structured clusters data above. Ensure valid JSON syntax.</div>
                            </div>
                        </div>
                    </div>
                @elseif($section->type == 'clusters_data')
                    <!-- DEDICATED USER-FRIENDLY CLUSTER DATA WORKSPACE -->
                    <div class="col-12">
                        <div class="p-3 border rounded-3 bg-white shadow-sm mb-3">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-heading me-1 text-primary"></i> Cluster Page Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $section->content['subtitle'] ?? 'Explore India\'s 6 major textile export cluster regions — products, exporters, facilities and more.' }}">
                        </div>
                    </div>

                    <!-- Cluster Selector Tabs Bar -->
                    <div class="col-12">
                        <div class="card border bg-white shadow-sm mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <strong class="text-primary"><i class="fas fa-city me-1"></i> Textile Cluster Workspace</strong>
                                    <span class="small text-muted ms-2">Select a cluster to edit its overview, facts, metrics, events, and active/inactive section visibility:</span>
                                </div>
                                <a href="{{ url('/p/clusters') }}" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="fas fa-external-link-alt me-1"></i> Preview Live Clusters
                                </a>
                            </div>
                            <div class="card-body p-3">
                                <ul class="nav nav-pills gap-2" id="adminClusterTabs">
                                    <!-- Populated via JS for Karur, Surat, Jaipur, Ludhiana, Varanasi, Ichalkaranji -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden JSON submission textarea for clusters_data -->
                    <textarea name="json_items" id="json_input_clusters_data" class="d-none">{{ json_encode($section->content['items'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}</textarea>

                    <!-- Active Cluster Editor Container -->
                    <div class="col-12" id="cluster_editor_workspace">
                        <!-- Cluster Header Banner -->
                        <div class="card border-primary border-opacity-25 bg-primary-subtle bg-opacity-10 shadow-sm mb-3 p-3 d-flex flex-row justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary text-white text-uppercase" id="adm_cluster_state_badge">State</span>
                                <h4 class="fw-bold text-primary mb-0 mt-1" id="adm_cluster_name_title">Cluster Name</h4>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-check-circle text-success me-1"></i> Synchronizes to Live Portal
                                </span>
                            </div>
                        </div>

                        <!-- Section Display Switches Card -->
                        <div class="card border bg-white shadow-sm mb-4">
                            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                <strong class="text-dark small"><i class="fas fa-toggle-on text-primary me-1"></i> Section Display Controls (Active / Inactive) for <span id="adm_sec_cluster_name">Cluster</span></strong>
                                <span class="badge bg-secondary-subtle text-secondary small">Toggle switches to hide or show sections on the live cluster detail page</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Stats Strip</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_stats" data-sec="stats">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Overview &amp; Facts</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_overview" data-sec="overview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Key Highlights</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_highlights" data-sec="highlights">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Export Volume Graph</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_chart" data-sec="chart">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Exporters Directory</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_exporters" data-sec="exporters">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Facilities &amp; Support</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_facilities" data-sec="facilities">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center" style="border:1px solid #b38e5d !important;">
                                            <span class="small fw-bold text-primary"><i class="fas fa-calendar-alt text-warning me-1"></i> Events Section</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_events" data-sec="events">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="p-2 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold">Cluster Office &amp; Map</span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input adm-sec-sw" type="checkbox" id="adm_sw_office" data-sec="office">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subtabs for Cluster Attributes -->
                        <div class="card border bg-white shadow-sm mb-4">
                            <div class="card-header bg-white p-0">
                                <ul class="nav nav-tabs px-3 pt-2 gap-1 border-0" id="admSubTabs">
                                    <li class="nav-item">
                                        <button class="nav-link active fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-metrics" type="button">
                                            <i class="fas fa-sliders-h me-1"></i> Metrics &amp; General
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-overview" type="button">
                                            <i class="fas fa-file-alt me-1"></i> Overview &amp; Highlights
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-events" type="button">
                                            <i class="fas fa-calendar-check text-warning me-1"></i> Events (<span id="adm_events_count">0</span>)
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-exporters" type="button">
                                            <i class="fas fa-store me-1"></i> Exporters (<span id="adm_exporters_count">0</span>)
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-chart" type="button">
                                            <i class="fas fa-chart-line me-1"></i> Export Volume Flow
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#admsub-contact" type="button">
                                            <i class="fas fa-map-marker-alt me-1"></i> Contact &amp; Office
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-4 tab-content" id="admSubTabContent">
                                <!-- Subtab 1: Metrics -->
                                <div class="tab-pane fade show active" id="admsub-metrics">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Cluster Name</label>
                                            <input type="text" id="adm_f_name" class="form-control form-control-sm" onchange="syncActiveClusterField('name', this.value)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">State Jurisdiction</label>
                                            <input type="text" id="adm_f_state" class="form-control form-control-sm" onchange="syncActiveClusterField('state', this.value)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Primary Specialization</label>
                                            <input type="text" id="adm_f_spec" class="form-control form-control-sm" onchange="syncActiveClusterField('spec', this.value)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Heritage / Established Era</label>
                                            <input type="text" id="adm_f_era" class="form-control form-control-sm" onchange="syncActiveClusterField('era', this.value)">
                                        </div>
                                        <!-- Inline Stats Strip Active/Inactive Toggle -->
                                        <div class="col-12 mt-3 mb-1">
                                            <div class="p-2 px-3 rounded-3 d-flex justify-content-between align-items-center" style="background: #fdf6f9; border: 1px solid rgba(108,19,64,0.18);">
                                                <div>
                                                    <span class="fw-bold text-primary small d-block"><i class="fas fa-chart-line me-1" style="color:#6c1340;"></i> Key Metrics &amp; Stats Strip</span>
                                                    <small class="text-muted" style="font-size:0.75rem;">Display the 4 key stat boxes (Units, Export Value, Workforce, Destinations) on the cluster page</small>
                                                </div>
                                                <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="checkbox" id="adm_sw_stats_inline" style="cursor:pointer; width:2.5em; height:1.3em;">
                                                    <label class="form-check-label small fw-bold" for="adm_sw_stats_inline" id="adm_lbl_stats_inline">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label fw-bold small">Units Count</label>
                                            <input type="text" id="adm_f_units" class="form-control form-control-sm" onchange="syncActiveClusterField('units', this.value)">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label fw-bold small">Export Value</label>
                                            <input type="text" id="adm_f_exportVal" class="form-control form-control-sm" onchange="syncActiveClusterField('exportVal', this.value)">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label fw-bold small">Workforce Employed</label>
                                            <input type="text" id="adm_f_workforce" class="form-control form-control-sm" onchange="syncActiveClusterField('workforce', this.value)">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label fw-bold small">Export Destinations</label>
                                            <input type="text" id="adm_f_dests" class="form-control form-control-sm" onchange="syncActiveClusterField('dests', this.value)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Subtab 2: Overview & Highlights -->
                                <div class="tab-pane fade" id="admsub-overview">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Overview Narrative</label>
                                        <textarea id="adm_f_desc" class="form-control" rows="4" onchange="syncActiveClusterField('desc', this.value)"></textarea>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold small mb-0">Product Highlights</label>
                                            <span class="text-muted small" style="font-size:0.75rem;"><i class="fas fa-info-circle me-1"></i>You can add product names and upload product images</span>
                                        </div>
                                        <div id="adm_highlights_tags" class="d-flex flex-wrap gap-2 mb-3"></div>
                                        <div class="card p-3 bg-light border rounded-3">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-5">
                                                    <label class="form-label small fw-bold mb-1">Product Title <span class="text-danger">*</span></label>
                                                    <input type="text" id="adm_new_highlight_input" class="form-control form-control-sm" placeholder="e.g. Cotton Yarn">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold mb-1">Product Image <small class="text-muted">(Optional)</small></label>
                                                    <input type="file" id="adm_new_highlight_file" class="form-control form-control-sm" accept="image/*">
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-sm btn-primary w-100 fw-bold" onclick="admAddHighlight()">
                                                        <i class="fas fa-plus me-1"></i> Add Product
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subtab 3: Events & Workshops -->
                                <div class="tab-pane fade" id="admsub-events">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong class="text-primary small"><i class="fas fa-calendar-alt text-warning me-1"></i> Events Listed for this Cluster</strong>
                                        <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="admAddEvent()">
                                            <i class="fas fa-plus me-1"></i> Add Event
                                        </button>
                                    </div>
                                    <div id="adm_events_container" class="d-flex flex-column gap-2"></div>
                                </div>

                                <!-- Subtab 4: Exporters -->
                                <div class="tab-pane fade" id="admsub-exporters">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong class="text-primary small"><i class="fas fa-store me-1"></i> Exporters in this Cluster</strong>
                                        <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="admAddExporter()">
                                            <i class="fas fa-plus me-1"></i> Add Exporter
                                        </button>
                                    </div>
                                    <div id="adm_exporters_container" class="d-flex flex-column gap-2"></div>
                                </div>

                                <!-- Subtab 5: Chart Data -->
                                <div class="tab-pane fade" id="admsub-chart">
                                    <p class="small text-muted mb-2">Monthly export numbers (in Rs. Cr) for the Volume of Export Flow chart:</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered text-center align-middle" style="font-size:0.8rem;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th><th>Jun</th>
                                                    <th>Jul</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
                                                </tr>
                                            </thead>
                                            <tbody id="adm_chart_table_body">
                                                <!-- Populated via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Subtab 6: Contact & Office -->
                                <div class="tab-pane fade" id="admsub-contact">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label fw-bold small">Office Address</label>
                                            <input type="text" id="adm_f_address" class="form-control form-control-sm" onchange="syncActiveClusterField('address', this.value)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Phone</label>
                                            <input type="text" id="adm_f_phone" class="form-control form-control-sm" onchange="syncActiveClusterField('phone', this.value)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Email</label>
                                            <input type="email" id="adm_f_email" class="form-control form-control-sm" onchange="syncActiveClusterField('email', this.value)">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold small">Google Maps URL</label>
                                            <input type="url" id="adm_f_mapUrl" class="form-control form-control-sm" onchange="syncActiveClusterField('mapUrl', this.value)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($section->type == 'about_intro')
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center mb-2" role="alert">
                            <i class="fas fa-users-cog fa-2x me-3 text-primary"></i>
                            <div>
                                <strong class="d-block text-dark">Leadership & Key People Showcase</strong>
                                <span class="small text-muted">You can customize the introduction paragraphs, button, and manage each leadership member's photo, name, and designation below. Changes will immediately update the live homepage cards.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Intro Texts & Button -->
                    <div class="col-12">
                        <div class="card border bg-white shadow-sm mb-3">
                            <div class="card-header bg-light py-2 fw-bold text-primary">
                                <i class="fas fa-align-left me-1"></i> Introduction Content & Call to Action
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Paragraph 1 (Primary Summary)</label>
                                        <textarea name="text_1" class="form-control" rows="3" placeholder="A strategic initiative by the Textiles Committee...">{{ $section->content['text_1'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Paragraph 2 (Secondary Details)</label>
                                        <textarea name="text_2" class="form-control" rows="2" placeholder="We bridge the gap between local craftsmanship...">{{ $section->content['text_2'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Button Text</label>
                                        <input type="text" name="btn_text" value="{{ $section->content['btn_text'] ?? 'Read Our Full Story' }}" class="form-control" placeholder="e.g. Read Our Full Story">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Button URL</label>
                                        <input type="text" name="btn_url" value="{{ $section->content['btn_url'] ?? '/p/about' }}" class="form-control" placeholder="e.g. /p/about">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key People Management -->
                    <div class="col-12">
                        <div class="card border bg-white shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold text-primary"><i class="fas fa-id-card me-1"></i> Key People & Leadership Team (Images, Names & Designations)</span>
                                <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="addPersonCard()">
                                    <i class="fas fa-plus me-1"></i> Add Another Person
                                </button>
                            </div>
                            <div class="card-body">
                                @php
                                    $peopleList = $section->content['people'] ?? [
                                        ['name' => 'NEELAM SHAMI RAO', 'designation' => "Chairperson's Message", 'image' => 'images/people/neelam_shami_rao.png', 'link' => '/p/about'],
                                        ['name' => 'Vrunda Manohar Desai', 'designation' => "Vice-Chairperson's Message", 'image' => 'images/people/vrunda_desai.png', 'link' => '/p/about'],
                                        ['name' => 'Kartikay Dhanda', 'designation' => 'CEO Message', 'image' => 'images/people/kartikay_dhanda.png', 'link' => '/p/about']
                                    ];
                                @endphp

                                <div class="row g-3" id="people_cards_list">
                                    @foreach($peopleList as $pIdx => $person)
                                        <div class="col-md-4 person-edit-card" id="person_card_{{ $pIdx }}">
                                            <div class="p-3 border rounded-3 bg-light h-100 position-relative shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-secondary">Person #{{ $pIdx + 1 }}</span>
                                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removePersonCard(this)" title="Remove Person">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <div class="text-center mb-3">
                                                    <div class="position-relative d-inline-block">
                                                        <img id="person_preview_{{ $pIdx }}" src="{{ asset($person['image'] ?? 'images/people/neelam_shami_rao.png') }}" class="rounded border shadow-sm" style="width: 110px; height: 120px; object-fit: cover; background: #fff;">
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label fw-bold small mb-1">Upload Photo</label>
                                                    <input type="file" name="people_image_{{ $pIdx }}" class="form-control form-control-sm" accept="image/*" onchange="previewPersonImage(this, {{ $pIdx }})">
                                                    <input type="hidden" name="people_existing_image[]" value="{{ $person['image'] ?? '' }}">
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label fw-bold small mb-1">Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="people_name[]" value="{{ $person['name'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. Officer Name" required>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label fw-bold small mb-1">Designation / Role</label>
                                                    <input type="text" name="people_designation[]" value="{{ $person['designation'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. Chairperson's Message">
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label fw-bold small mb-1">Link URL (Optional)</label>
                                                    <input type="text" name="people_link[]" value="{{ $person['link'] ?? '' }}" class="form-control form-control-sm" placeholder="/p/about">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($section->type == 'registration_modal')
                    <!-- Dedicated Exporter Registration Modal Editor -->
                    <div class="col-12">
                        <div class="alert alert-primary d-flex align-items-center mb-2" role="alert">
                            <i class="fas fa-window-restore fa-2x me-3 text-primary"></i>
                            <div>
                                <strong class="d-block text-dark">First-Visit Exporter Registration Popup Modal</strong>
                                <span class="small text-muted">This popup modal is automatically presented to new website visitors after 2 seconds to encourage exporters to onboard. When dismissed or submitted, the choice is saved so the visitor is not interrupted on subsequent page reloads.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-4 border rounded-3 bg-white shadow-sm mb-2">
                            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-edit me-2"></i> Modal Text & Call to Action</h5>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modal Subtitle / Invitation Message <span class="text-danger">*</span></label>
                                <textarea name="subtitle" class="form-control" rows="2" placeholder="e.g. Join India's premier textile facilitation network and unlock global trade opportunities.">{{ $section->content['subtitle'] ?? 'Join India\'s premier textile facilitation network and unlock global trade opportunities.' }}</textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Primary Button Text <span class="text-danger">*</span></label>
                                    <input type="text" name="btn_text" value="{{ $section->content['btn_text'] ?? 'Register on Portal Now' }}" class="form-control" placeholder="Register on Portal Now">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Primary Button Link URL <span class="text-danger">*</span></label>
                                    <input type="text" name="btn_url" value="{{ $section->content['btn_url'] ?? route('register', ['role' => 'exporter']) }}" class="form-control" placeholder="/register?role=exporter">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Secondary Dismissal Link Text</label>
                                    <input type="text" name="secondary_text" value="{{ $section->content['secondary_text'] ?? 'Maybe Later, Skip to Directory' }}" class="form-control" placeholder="Maybe Later, Skip to Directory">
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($section->type == 'service_detail')
                    <!-- Dedicated Service Detail Page Editor (CoO, ECGC, HS-Code, IEC, LC) -->
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center mb-2" role="alert">
                            <i class="fas fa-info-circle fa-2x me-3 text-primary"></i>
                            <div>
                                <strong class="d-block text-dark">Digital Facilitation Desk Service Guide</strong>
                                <span class="small text-muted">Customize the detailed overview, required documentation checklist, process steps, and official portal action link.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-4 border rounded-3 bg-white shadow-sm mb-2">
                            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-heading me-2"></i> Service Banner & Overview</h5>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Subtitle / Tagline</label>
                                <input type="text" name="subtitle" value="{{ $section->content['subtitle'] ?? '' }}" class="form-control" placeholder="Essential documentation and guidance">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">1. What is this Service? (Comprehensive Explanation) <span class="text-danger">*</span></label>
                                <textarea name="what_is" class="form-control" rows="4" placeholder="Detailed explanation of the statutory and trade facilitation benefits...">{{ $section->content['what_is'] ?? '' }}</textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Official External Action Button Text</label>
                                    <input type="text" name="cta_text" value="{{ $section->content['cta_text'] ?? 'Apply on Official Portal →' }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Official External Action URL</label>
                                    <input type="text" name="cta_url" value="{{ $section->content['cta_url'] ?? '#' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Dynamic Content Fields (Auto-detecting type for non-hero or extra keys) -->
                @php
                    $heroHandledKeys = ['background_image', 'images', 'subtitle', 'primary_btn_text', 'primary_btn_url', 'secondary_btn_text', 'secondary_btn_url', 'banner_height'];
                    $clustersHandledKeys = ['items', 'description', 'show_map_card', 'show_map_card_submitted'];
                    $clustersDataHandledKeys = ['items', 'subtitle'];
                    $aboutIntroHandledKeys = ['image', 'people', 'text_1', 'text_2', 'btn_text', 'btn_url'];
                    $regModalHandledKeys = ['subtitle', 'btn_text', 'btn_url', 'secondary_text'];
                    $serviceDetailHandledKeys = ['subtitle', 'what_is', 'cta_text', 'cta_url'];
                    $announcementHandledKeys = ['badge', 'view_all_text', 'view_all_url'];
                @endphp
                @foreach($section->content as $key => $value)
                    @if($section->type == 'hero' && in_array($key, $heroHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'announcement' && in_array($key, $announcementHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'clusters_grid' && in_array($key, $clustersHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'clusters_data' && in_array($key, $clustersDataHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'about_intro' && in_array($key, $aboutIntroHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'registration_modal' && in_array($key, $regModalHandledKeys))
                        @continue
                    @endif
                    @if($section->type == 'service_detail' && in_array($key, $serviceDetailHandledKeys))
                        @continue
                    @endif

                    @if(is_array($value))
                        <div class="col-12">
                            <div class="p-4 border rounded-3 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold text-primary mb-0" style="font-size: 1.1rem;">
                                        <i class="fas fa-list me-2"></i> {{ ucwords(str_replace('_', ' ', $key)) }}
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-editor-btn" data-key="{{ $key }}">
                                        <i class="fas fa-code me-1"></i> Switch to Raw JSON
                                    </button>
                                </div>

                                @if($section->type == 'gallery' && $key == 'items')
                                    <div class="alert alert-light border d-flex align-items-center mb-3 p-3">
                                        <div class="me-3 text-primary"><i class="fas fa-images fa-2x"></i></div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block text-dark">Quick Bulk Upload Photos</strong>
                                            <span class="small text-muted d-block mb-2">Select one or multiple photos directly from your computer to quickly add them to this gallery.</span>
                                            <input type="file" name="gallery_images[]" class="form-control form-control-sm" multiple accept="image/*">
                                        </div>
                                    </div>
                                @endif

                                <!-- Visual Form Editor Container -->
                                <div id="visual_container_{{ $key }}" class="visual-editor-container mb-2">
                                    <!-- Populated via Javascript -->
                                </div>

                                <!-- Raw JSON Fallback Container (Hidden by default) -->
                                <div id="json_container_{{ $key }}" class="d-none">
                                    @php
                                        $inputName = 'json_' . $key;
                                    @endphp
                                    <textarea name="{{ $inputName }}" id="json_input_{{ $key }}" class="form-control bg-dark text-light" rows="8" style="font-family: 'Courier New', Courier, monospace; font-size: 0.85rem;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
                                    <div class="form-text small text-muted mt-2">Modify the structured data above. Ensure valid JSON syntax.</div>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $isLongText = strlen($value) > 120 || str_contains($value, "\n") || in_array($key, ['subtitle', 'text_1', 'text_2', 'description', 'mission', 'vision', 'footer_about']);
                            $isImageOrFile = in_array(strtolower($key), ['image', 'background_image', 'banner', 'logo', 'icon', 'file', 'document', 'photo', 'pdf', 'certificate_path', 'brochure'])
                                          || str_ends_with(strtolower($key), '_image') || str_ends_with(strtolower($key), '_file') || str_ends_with(strtolower($key), '_doc') || str_ends_with(strtolower($key), '_icon') || str_ends_with(strtolower($key), '_pdf')
                                          || (is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|webp|svg|pdf|docx?|xlsx?)$/i', $value));
                        @endphp
                        @if($isImageOrFile)
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold text-capitalize mb-0 text-dark">
                                            <i class="fas fa-file-image text-primary me-1"></i> {{ str_replace('_', ' ', $key) }}
                                        </label>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">File / Image Field</span>
                                    </div>
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <div id="preview_wrap_{{ $key }}" class="border rounded bg-white p-1 text-center shadow-sm" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                @if(!empty($value))
                                                    @php
                                                        $isImgVal = preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $value) || str_contains($value, 'images/');
                                                    @endphp
                                                    @if($isImgVal)
                                                        <img src="{{ asset($value) }}" id="preview_img_{{ $key }}" style="max-width: 100%; max-height: 100%; object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-file-alt text-muted fa-2x\'></i>';">
                                                    @else
                                                        <a href="{{ asset($value) }}" target="_blank" class="text-primary text-decoration-none" title="View Document">
                                                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <i class="fas fa-image text-muted fa-2x" id="preview_icon_{{ $key }}"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <input type="text" name="{{ $key }}" id="input_path_{{ $key }}" value="{{ $value }}" class="form-control" placeholder="File path or URL (auto-filled on upload)">
                                                <label class="btn btn-primary fw-bold px-3" for="file_upload_{{ $key }}" style="cursor: pointer;">
                                                    <i class="fas fa-upload me-1"></i> Upload File
                                                    <input type="file" id="file_upload_{{ $key }}" class="d-none" onchange="uploadSingleCmsFile(this, '{{ $key }}')">
                                                </label>
                                                <button type="button" class="btn btn-outline-danger" onclick="clearSingleCmsFile('{{ $key }}')" title="Clear / Remove File">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="small text-muted d-flex align-items-center justify-content-between">
                                                <span id="upload_status_{{ $key }}"><i class="fas fa-info-circle text-primary me-1"></i> Upload any image (JPG, PNG, WebP, SVG) or file (PDF, Doc, Excel). Saved automatically.</span>
                                                @if(!empty($value))
                                                    <a href="{{ asset($value) }}" target="_blank" class="text-decoration-none small text-primary fw-semibold">
                                                        <i class="fas fa-external-link-alt me-1"></i> Open Current Asset
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($isLongText)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-capitalize">{{ str_replace('_', ' ', $key) }}</label>
                                    <textarea name="{{ $key }}" class="form-control" rows="4">{{ $value }}</textarea>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-capitalize">{{ str_replace('_', ' ', $key) }}</label>
                                    <input type="text" name="{{ $key }}" value="{{ $value }}" class="form-control">
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="mt-5 pt-4 border-top text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold">Publish Changes to Site</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const ASSET_BASE = @json(asset('/'));
function formatAssetUrl(path) {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    const cleanPath = path.replace(/^\/+/, '');
    const cleanBase = ASSET_BASE.replace(/\/+$/, '');
    return cleanBase + '/' + cleanPath;
}

window.uploadSingleCmsFile = async function(fileInput, fieldKey) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

    const statusEl = document.getElementById(`upload_status_${fieldKey}`);
    const inputEl = document.getElementById(`input_path_${fieldKey}`);
    const previewWrap = document.getElementById(`preview_wrap_${fieldKey}`);

    if (statusEl) statusEl.innerHTML = '<span class="text-primary fw-bold"><i class="fas fa-spinner fa-spin me-1"></i> Uploading file, please wait...</span>';

    try {
        const res = await fetch("{{ route('cms.upload-file') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            inputEl.value = data.path || data.url;
            if (statusEl) statusEl.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> File uploaded successfully!</span>';
            if (previewWrap) {
                if (data.is_image) {
                    previewWrap.innerHTML = `<img src="${formatAssetUrl(data.path || data.url)}" style="max-width: 100%; max-height: 100%; object-fit: cover;">`;
                } else {
                    previewWrap.innerHTML = `<a href="${data.url}" target="_blank" class="text-primary"><i class="fas fa-file-pdf text-danger fa-2x"></i></a>`;
                }
            }
        } else {
            alert(data.message || 'Upload failed.');
            if (statusEl) statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Upload failed.</span>';
        }
    } catch(e) {
        console.error(e);
        alert('An error occurred during upload.');
        if (statusEl) statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Upload error.</span>';
    }
};

window.clearSingleCmsFile = function(fieldKey) {
    const inputEl = document.getElementById(`input_path_${fieldKey}`);
    const previewWrap = document.getElementById(`preview_wrap_${fieldKey}`);
    const statusEl = document.getElementById(`upload_status_${fieldKey}`);
    if (inputEl) inputEl.value = '';
    if (previewWrap) previewWrap.innerHTML = `<i class="fas fa-image text-muted fa-2x"></i>`;
    if (statusEl) statusEl.innerHTML = '<span class="text-muted"><i class="fas fa-info-circle me-1"></i> File cleared.</span>';
};

window.uploadTableRowFile = async function(fileInput, arrayKey, idx, colKey) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

    const label = fileInput.closest('label');
    const origHtml = label ? label.innerHTML : '';
    if (label) label.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

    try {
        const res = await fetch("{{ route('cms.upload-file') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            const jsonInput = document.getElementById(`json_input_${arrayKey}`);
            const visualContainer = document.getElementById(`visual_container_${arrayKey}`);
            const jsonContainer = document.getElementById(`json_container_${arrayKey}`);
            let list = JSON.parse(jsonInput.value);
            if (list[idx]) {
                list[idx][colKey] = data.path || data.url;
                jsonInput.value = JSON.stringify(list, null, 4);
                renderVisualEditor(arrayKey, list, visualContainer, jsonInput, jsonContainer);
            }
        } else {
            alert(data.message || 'Upload failed.');
            if (label) label.innerHTML = origHtml;
        }
    } catch(e) {
        console.error(e);
        alert('An error occurred while uploading.');
        if (label) label.innerHTML = origHtml;
    }
};

window.clearTableRowFile = function(arrayKey, idx, colKey) {
    const jsonInput = document.getElementById(`json_input_${arrayKey}`);
    const visualContainer = document.getElementById(`visual_container_${arrayKey}`);
    const jsonContainer = document.getElementById(`json_container_${arrayKey}`);
    let list = JSON.parse(jsonInput.value);
    if (list[idx]) {
        list[idx][colKey] = '';
        jsonInput.value = JSON.stringify(list, null, 4);
        renderVisualEditor(arrayKey, list, visualContainer, jsonInput, jsonContainer);
    }
};

window.uploadClusterGridCardPhoto = async function(fileInput, idx) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

    const label = fileInput.closest('label');
    const origHtml = label ? label.innerHTML : '';
    if (label) label.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

    try {
        const res = await fetch("{{ route('cms.upload-file') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            const jsonInput = document.getElementById('json_input_items');
            if (window.clusterGridItems && window.clusterGridItems[idx]) {
                window.clusterGridItems[idx].image = data.path || data.url;
                jsonInput.value = JSON.stringify(window.clusterGridItems);
                if (typeof window.renderClusterGridCards === 'function') {
                    window.renderClusterGridCards();
                }
            }
        } else {
            alert(data.message || 'Upload failed.');
            if (label) label.innerHTML = origHtml;
        }
    } catch(e) {
        console.error(e);
        alert('An error occurred while uploading photo.');
        if (label) label.innerHTML = origHtml;
    }
};

document.addEventListener('DOMContentLoaded', function () {
    @php
        $arrayKeys = [];
        foreach($section->content as $k => $v) {
            if (is_array($v) && !($section->type === 'hero' && ($k === 'images' || $k === 'background_image')) && !($section->type === 'clusters_grid' && $k === 'items') && !($section->type === 'clusters_data' && $k === 'items')) {
                $arrayKeys[] = $k;
            }
        }
    @endphp
    
    const arrayKeys = @json($arrayKeys);
    
    window.currentSectionType = "{{ $section->type }}";
    
    arrayKeys.forEach(key => {
        const jsonInput = document.getElementById(`json_input_${key}`);
        const visualContainer = document.getElementById(`visual_container_${key}`);
        const jsonContainer = document.getElementById(`json_container_${key}`);
        
        if (!jsonInput || !visualContainer) return;
        
        let data = [];
        try {
            data = JSON.parse(jsonInput.value);
        } catch (e) {
            console.error(`Invalid initial JSON for ${key}`, e);
            showJsonEditor(key, jsonContainer, visualContainer);
            return;
        }

        if (!Array.isArray(data)) {
            if (typeof data === 'object' && data !== null) {
                renderAssociativeObjectEditor(key, data, visualContainer, jsonInput);
                return;
            }
            showJsonEditor(key, jsonContainer, visualContainer);
            return;
        }

        renderVisualEditor(key, data, visualContainer, jsonInput, jsonContainer);
    });

    @if($section->type === 'clusters_grid')
        initClustersGridEditor();
    @endif

    @if($section->type === 'clusters_data')
        initClustersDataEditor();
    @endif

    // Live display status switch feedback
    const statusToggle = document.getElementById('is_active_toggle');
    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            const badge = document.getElementById('status_state_badge');
            const icon = document.getElementById('status_icon');
            const heading = document.getElementById('status_heading');
            const subtext = document.getElementById('status_subtext');
            if (this.checked) {
                if (badge) { badge.className = 'badge bg-success'; badge.textContent = 'Active'; }
                if (icon) icon.className = 'fas fa-eye text-success fs-5';
                if (heading) heading.textContent = 'Section is Live';
                if (subtext) subtext.textContent = 'Shown to all public website visitors';
            } else {
                if (badge) { badge.className = 'badge bg-secondary'; badge.textContent = 'Inactive (Hidden)'; }
                if (icon) icon.className = 'fas fa-eye-slash text-secondary fs-5';
                if (heading) heading.textContent = 'Section is Hidden';
                if (subtext) subtext.textContent = 'Hidden from public website';
            }
        });
    }

    // Map Card Toggle Live Feedback & Instant AJAX sync
    const mapCardToggle = document.getElementById('show_map_card');
    if (mapCardToggle) {
        mapCardToggle.addEventListener('change', function() {
            const isChecked = this.checked;
            const badge = document.getElementById('map_card_badge');
            const feedback = document.getElementById('map_card_auto_feedback');
            const feedbackText = document.getElementById('map_card_feedback_text');

            if (badge) {
                badge.className = isChecked ? 'badge bg-success' : 'badge bg-secondary';
                badge.textContent = isChecked ? 'Active (Shown on Homepage)' : 'Inactive (Hidden from Homepage)';
            }

            const csrfToken = document.querySelector('input[name="_token"]')?.value;

            fetch('{{ route('admin.sections.toggle-map-card', $section->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ state: isChecked })
            })
            .then(res => res.json())
            .then(data => {
                if (feedback && feedbackText) {
                    feedbackText.textContent = data.message || 'Updated successfully!';
                    feedback.classList.remove('d-none');
                    setTimeout(() => { feedback.classList.add('d-none'); }, 3500);
                }
            })
            .catch(err => {
                console.error('Error toggling map card state:', err);
            });
        });
    }

    document.querySelectorAll('.toggle-editor-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const key = this.getAttribute('data-key');
            const jsonContainer = document.getElementById(`json_container_${key}`);
            const visualContainer = document.getElementById(`visual_container_${key}`);
            
            if (jsonContainer.classList.contains('d-none')) {
                jsonContainer.classList.remove('d-none');
                visualContainer.classList.add('d-none');
                this.innerHTML = '<i class="fas fa-list me-1"></i> Switch to Visual Editor';
            } else {
                const jsonInput = document.getElementById(`json_input_${key}`);
                try {
                    const parsed = JSON.parse(jsonInput.value);
                    if (Array.isArray(parsed)) {
                        renderVisualEditor(key, parsed, visualContainer, jsonInput, jsonContainer);
                    } else if (typeof parsed === 'object' && parsed !== null) {
                        renderAssociativeObjectEditor(key, parsed, visualContainer, jsonInput);
                    }
                } catch(e) {
                    alert('Cannot switch to Visual Editor: Current text is not valid JSON. Please fix syntax errors first.');
                    return;
                }
                jsonContainer.classList.add('d-none');
                visualContainer.classList.remove('d-none');
                this.innerHTML = '<i class="fas fa-code me-1"></i> Switch to Raw JSON';
            }
        });
    });
});

function renderAssociativeObjectEditor(key, data, container, jsonInput) {
    container.innerHTML = '';
    const table = document.createElement('table');
    table.className = 'table table-bordered table-sm align-middle bg-white mb-2';
    table.innerHTML = `
        <thead>
            <tr>
                <th style="width: 35%;" class="bg-light text-muted small py-2 px-3">Field Name</th>
                <th class="bg-light text-muted small py-2 px-3">Value</th>
            </tr>
        </thead>
        <tbody></tbody>
    `;
    const tbody = table.querySelector('tbody');
    Object.keys(data).forEach(subKey => {
        const tr = document.createElement('tr');
        const val = data[subKey] !== undefined ? data[subKey] : '';
        tr.innerHTML = `
            <td class="p-2 fw-semibold text-dark text-capitalize">${subKey.replace(/_/g, ' ')}</td>
            <td class="p-2">
                <input type="text" class="form-control form-control-sm assoc-object-field" data-subkey="${subKey}" value="${escapeHtml(String(val))}">
            </td>
        `;
        tbody.appendChild(tr);
    });
    container.appendChild(table);

    container.querySelectorAll('.assoc-object-field').forEach(input => {
        input.addEventListener('input', () => {
            const compiled = {};
            container.querySelectorAll('.assoc-object-field').forEach(field => {
                compiled[field.getAttribute('data-subkey')] = field.value;
            });
            jsonInput.value = JSON.stringify(compiled, null, 4);
        });
    });
}

function showJsonEditor(key, jsonContainer, visualContainer) {
    jsonContainer.classList.remove('d-none');
    visualContainer.classList.add('d-none');
    const btn = document.querySelector(`.toggle-editor-btn[data-key="${key}"]`);
    if (btn) btn.style.display = 'none';
}

function renderVisualEditor(key, data, container, jsonInput, jsonContainer) {
    container.innerHTML = '';
    
    if (data.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info py-2 small mb-2">No items present. Click below to add one.</div>
            <button type="button" class="btn btn-sm btn-primary add-item-btn" data-key="${key}"><i class="fas fa-plus"></i> Add Item</button>
        `;
        setupAddButtonListener(key, data, container, jsonInput, jsonContainer);
        return;
    }

    const firstItem = data[0];
    const isStringArray = (typeof firstItem === 'string');

    if (isStringArray) {
        const divList = document.createElement('div');
        divList.className = 'd-flex flex-column gap-2 mb-3';
        
        data.forEach((val, idx) => {
            const row = document.createElement('div');
            row.className = 'd-flex align-items-center gap-2';
            row.innerHTML = `
                <input type="text" class="form-control string-input" data-index="${idx}" value="${escapeHtml(val)}">
                <button type="button" class="btn btn-outline-danger btn-sm delete-item-btn" data-index="${idx}"><i class="fas fa-trash"></i></button>
            `;
            divList.appendChild(row);
        });
        
        container.appendChild(divList);
    } else if (typeof firstItem === 'object' && firstItem !== null) {
        let keys = Object.keys(firstItem);

        // Special handling for FTA Benefits: URL is not required, only Name and Description
        if (window.currentSectionType === 'fta_benefits' && key === 'items') {
            keys = ['name', 'description'];
            data.forEach(item => {
                delete item.url;
                if (item.name === undefined) item.name = '';
                if (item.description === undefined) item.description = '';
            });
        }

        const table = document.createElement('table');
        table.className = 'table table-bordered table-sm align-middle bg-white mb-3';
        
        let thead = '<thead><tr>';
        keys.forEach(k => {
            const colWidth = (k === 'name') ? 'width: 35%;' : (k === 'description' ? 'width: 60%;' : '');
            thead += `<th style="${colWidth}" class="text-capitalize small py-2 px-3 bg-light text-muted">${k.replace('_', ' ')}</th>`;
        });
        thead += '<th style="width: 50px;"></th></tr></thead>';
        table.innerHTML = thead;

        const tbody = document.createElement('tbody');
        data.forEach((item, idx) => {
            const tr = document.createElement('tr');
            let cols = '';
            keys.forEach(k => {
                const val = item[k] !== undefined ? item[k] : '';
                const isImageCol = ['image', 'icon', 'photo', 'file', 'doc', 'document', 'pdf', 'certificate'].includes(k.toLowerCase()) || 
                                   k.toLowerCase().endsWith('_image') || k.toLowerCase().endsWith('_file');

                if (isImageCol) {
                    const previewUrl = val ? formatAssetUrl(String(val)) : '';
                    cols += `
                        <td class="p-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="table-cell-thumb border rounded bg-white p-1 text-center shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                    ${previewUrl ? `<img src="${previewUrl}" style="max-width: 100%; max-height: 100%; object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-file-alt text-muted\\'></i>';">` : `<i class="fas fa-image text-muted"></i>`}
                                </div>
                                <div class="flex-grow-1" style="min-width: 150px;">
                                    <input type="text" class="form-control form-control-sm object-field mb-1" data-index="${idx}" data-field="${k}" value="${escapeHtml(String(val))}" placeholder="Path or upload ->">
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; cursor: pointer;">
                                            <i class="fas fa-upload"></i> Upload
                                            <input type="file" style="display:none;" onchange="uploadTableRowFile(this, '${key}', ${idx}, '${k}')">
                                        </label>
                                        ${val ? `<button type="button" class="btn btn-link text-danger p-0 ms-1" style="font-size:0.75rem;" onclick="clearTableRowFile('${key}', ${idx}, '${k}')" title="Clear"><i class="fas fa-times"></i></button>` : ''}
                                    </div>
                                </div>
                            </div>
                        </td>
                    `;
                } else if (k === 'description' || (window.currentSectionType === 'fta_benefits' && k === 'description')) {
                    cols += `<td class="p-2"><textarea class="form-control form-control-sm object-field" rows="2" data-index="${idx}" data-field="${k}" placeholder="Enter detailed description...">${escapeHtml(String(val))}</textarea></td>`;
                } else {
                    cols += `<td class="p-2"><input type="text" class="form-control form-control-sm object-field" data-index="${idx}" data-field="${k}" value="${escapeHtml(String(val))}"></td>`;
                }
            });
            cols += `<td class="text-center p-2"><button type="button" class="btn btn-outline-danger btn-sm delete-item-btn" data-index="${idx}"><i class="fas fa-trash"></i></button></td>`;
            tr.innerHTML = cols;
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);
        container.appendChild(table);
    } else {
        showJsonEditor(key, jsonContainer, container);
        return;
    }

    const controls = document.createElement('div');
    controls.innerHTML = `<button type="button" class="btn btn-sm btn-primary add-item-btn" data-key="${key}"><i class="fas fa-plus"></i> Add Item</button>`;
    container.appendChild(controls);

    container.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => {
            updateJsonFromVisual(key, container, jsonInput);
        });
    });

    container.querySelectorAll('.delete-item-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const idx = parseInt(this.getAttribute('data-index'));
            data.splice(idx, 1);
            renderVisualEditor(key, data, container, jsonInput, jsonContainer);
            updateJsonFromVisual(key, container, jsonInput);
        });
    });

    setupAddButtonListener(key, data, container, jsonInput, jsonContainer);
}

function setupAddButtonListener(key, data, container, jsonInput, jsonContainer) {
    const addBtn = container.querySelector('.add-item-btn');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            if (data.length === 0) {
                if (key === 'images') {
                    data.push('');
                } else if (key === 'stats' || key === 'side_stats' || key === 'pulse_stats') {
                    data.push({ label: '', value: '' });
                } else if (key === 'items') {
                    if (window.currentSectionType === 'fta_benefits') {
                        data.push({ name: '', description: '' });
                    } else if (window.currentSectionType === 'gallery') {
                        data.push({ image: 'images/karur.png', title: '', category: 'Clusters', caption: '' });
                    } else {
                        data.push({ title: '', text: '', icon: '' });
                    }
                } else if (key === 'features') {
                    data.push({ title: '', text: '', icon: '' });
                } else if (key === 'people') {
                    data.push({ name: '', designation: '', image: '' });
                } else if (key === 'milestones') {
                    data.push({ year: '', title: '', text: '' });
                } else if (key === 'bar_items' || key === 'donut_items') {
                    data.push({ label: '', value: '' });
                } else if (key === 'objectives' || key === 'branches' || key === 'documents_required' || key === 'application_steps') {
                    data.push('');
                } else {
                    data.push({});
                }
            } else {
                if (window.currentSectionType === 'fta_benefits' && key === 'items') {
                    data.push({ name: '', description: '' });
                } else {
                    const firstItem = data[0];
                    if (typeof firstItem === 'string') {
                        data.push('');
                    } else if (typeof firstItem === 'object' && firstItem !== null) {
                        const newItem = {};
                        Object.keys(firstItem).forEach(k => {
                            newItem[k] = '';
                        });
                        data.push(newItem);
                    }
                }
            }
            renderVisualEditor(key, data, container, jsonInput, jsonContainer);
            updateJsonFromVisual(key, container, jsonInput);
        });
    }
}

function updateJsonFromVisual(key, container, jsonInput) {
    const stringInputs = container.querySelectorAll('.string-input');
    const objectFields = container.querySelectorAll('.object-field');
    
    let compiled = [];
    
    if (stringInputs.length > 0) {
        stringInputs.forEach(input => {
            compiled.push(input.value);
        });
    } else if (objectFields.length > 0) {
        objectFields.forEach(input => {
            const idx = parseInt(input.getAttribute('data-index'));
            const field = input.getAttribute('data-field');
            if (!compiled[idx]) compiled[idx] = {};
            compiled[idx][field] = input.value;
        });
    }
    
    jsonInput.value = JSON.stringify(compiled, null, 4);
}

function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

const OFFICIAL_CENTRES = [
    {
        name: "Surat",
        code: "surat",
        state: "Gujarat",
        districts: "Surat, Navsari",
        units_count: "115",
        specialization: "Man-made Fibre (MMF), Synthetic Fabrics, Technical Textiles & Jacquards",
        description: "India's synthetic textile capital supplying global markets with high-grade yarn, fabrics, and technical textiles.",
        image: ""
    },
    {
        name: "Karur",
        code: "karur",
        state: "Tamil Nadu",
        districts: "Karur",
        units_count: "89",
        specialization: "Home Textiles, Table Linen, Kitchen Textiles & Made-ups",
        description: "Renowned home textile hub exporting high quality bed linens, table covers, and kitchen furnishings internationally.",
        image: ""
    },
    {
        name: "Ichalkaranji",
        code: "ichalkaranji",
        state: "Maharashtra",
        districts: "Kolhapur",
        units_count: "240",
        specialization: "Powerloom Weaving, Yarn Processing & Grey Fabric",
        description: "The 'Manchester of Maharashtra' known for extensive powerloom networks, premium cotton yarn, and grey cloth.",
        image: ""
    },
    {
        name: "Jaipur",
        code: "jaipur",
        state: "Rajasthan",
        districts: "Jaipur",
        units_count: "484",
        specialization: "Block Printed Textiles, Made-ups, Garments & Quilts",
        description: "World-famous craft and heritage textile cluster specializing in hand block printing, quilts, and ethnic apparel.",
        image: ""
    },
    {
        name: "Varanasi",
        code: "varanasi",
        state: "Uttar Pradesh",
        districts: "Varanasi, Sant Ravidas Nagar (Bhadohi), Mirzapur, Chunar",
        units_count: "197",
        specialization: "Banarasi Silk, Handloom Brocades, Handmade Carpets & Rugs",
        description: "Legendary silk weaving and artisanal carpet centre creating opulent zari brocades and hand-knotted export rugs.",
        image: ""
    },
    {
        name: "Ludhiana",
        code: "ludhiana",
        state: "Punjab",
        districts: "Ludhiana",
        units_count: "353",
        specialization: "Knitwear, Hosiery, Woollen Garments & Thermal Fabrics",
        description: "India's premier knitwear hub producing sweaters, woollen garments, socks, and active thermal wear.",
        image: ""
    }
];

function initClustersGridEditor() {
    const jsonInput = document.getElementById('json_input_items');
    const visualList = document.getElementById('clusters_visual_list');
    const btnAdd = document.getElementById('btnAddCluster');
    const btnOfficial = document.getElementById('btnLoadOfficialCentres');
    if (!jsonInput || !visualList) return;

    let items = [];
    try {
        items = JSON.parse(jsonInput.value);
        if (!Array.isArray(items)) items = [];
    } catch(e) {
        items = [];
    }
    window.clusterGridItems = items;
    window.renderClusterGridCards = renderCards;

    function renderCards() {
        visualList.innerHTML = '';
        if (items.length === 0) {
            visualList.innerHTML = '<div class="alert alert-info py-2 small mb-0">No centres added yet. Click "+ Add Another Cluster Centre" or auto-fill official centres below.</div>';
            return;
        }

        items.forEach((item, idx) => {
            const card = document.createElement('div');
            card.className = 'border rounded-3 p-3 bg-light position-relative shadow-sm';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary px-2 py-1">Centre #${idx + 1}</span>
                        <strong class="text-dark centre-card-title">${escapeHtml(item.name || 'Untitled Centre')}</strong>
                        <span class="badge bg-secondary-subtle text-secondary small centre-card-state">${escapeHtml(item.state || '')}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-dark border"><i class="fas fa-industry text-warning me-1"></i> <span class="centre-card-units">${escapeHtml(String(item.units_count || '0'))}</span> Units Present</span>
                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="deleteClusterItem(${idx})">
                            <i class="fas fa-trash-alt me-1"></i> Remove
                        </button>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Centre Name *</label>
                        <input type="text" class="form-control form-control-sm cluster-field" data-index="${idx}" data-field="name" value="${escapeHtml(item.name || '')}" placeholder="e.g. Surat" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Map Node Key *</label>
                        <select class="form-select form-select-sm cluster-field" data-index="${idx}" data-field="code">
                            <option value="surat" ${item.code === 'surat' ? 'selected' : ''}>surat (Gujarat)</option>
                            <option value="karur" ${item.code === 'karur' ? 'selected' : ''}>karur (Tamil Nadu)</option>
                            <option value="ichalkaranji" ${item.code === 'ichalkaranji' ? 'selected' : ''}>ichalkaranji (Maharashtra)</option>
                            <option value="jaipur" ${item.code === 'jaipur' ? 'selected' : ''}>jaipur (Rajasthan)</option>
                            <option value="varanasi" ${item.code === 'varanasi' ? 'selected' : ''}>varanasi (Uttar Pradesh)</option>
                            <option value="ludhiana" ${item.code === 'ludhiana' ? 'selected' : ''}>ludhiana (Punjab)</option>
                            <option value="${escapeHtml(item.code || '')}" ${['surat','karur','ichalkaranji','jaipur','varanasi','ludhiana'].includes(item.code) ? '' : 'selected'}>Custom / Other (${escapeHtml(item.code || 'none')})</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">State *</label>
                        <input type="text" class="form-control form-control-sm cluster-field" data-index="${idx}" data-field="state" value="${escapeHtml(item.state || '')}" placeholder="e.g. Gujarat" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 text-primary">Units / MSMEs in District *</label>
                        <input type="text" class="form-control form-control-sm cluster-field fw-bold" data-index="${idx}" data-field="units_count" value="${escapeHtml(String(item.units_count || ''))}" placeholder="e.g. 115" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">Covered Revenue District(s) *</label>
                        <input type="text" class="form-control form-control-sm cluster-field" data-index="${idx}" data-field="districts" value="${escapeHtml(item.districts || '')}" placeholder="e.g. Surat, Navsari">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">Textile Specialization</label>
                        <input type="text" class="form-control form-control-sm cluster-field" data-index="${idx}" data-field="specialization" value="${escapeHtml(item.specialization || '')}" placeholder="e.g. Man-Made Fibre (MMF) & Technical Textiles">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold mb-1">Overview Description</label>
                        <input type="text" class="form-control form-control-sm cluster-field" data-index="${idx}" data-field="description" value="${escapeHtml(item.description || '')}" placeholder="Short description of this cluster">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1 text-dark">Cluster Card Photo (Optional)</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="cluster-grid-img-thumb border rounded bg-white p-1 text-center shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                ${item.image ? `<img src="${formatAssetUrl(item.image)}" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-image text-muted\\'></i>';" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="fas fa-image text-muted" style="font-size:0.8rem;"></i>`}
                            </div>
                            <div class="flex-grow-1">
                                <input type="text" class="form-control form-control-sm cluster-field mb-1" data-index="${idx}" data-field="image" value="${escapeHtml(item.image || '')}" placeholder="Leave blank for Vector Map">
                                <label class="btn btn-outline-primary btn-sm py-0 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; cursor: pointer;">
                                    <i class="fas fa-upload"></i> Upload Photo
                                    <input type="file" accept="image/*" style="display:none;" onchange="uploadClusterGridCardPhoto(this, ${idx})">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            visualList.appendChild(card);
        });

        // Add event listeners to input fields
        visualList.querySelectorAll('.cluster-field').forEach(input => {
            input.addEventListener('input', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                const field = this.getAttribute('data-field');
                if (items[idx]) {
                    items[idx][field] = this.value;
                    const card = this.closest('.border.rounded-3');
                    if (card) {
                        if (field === 'name') {
                            const titleEl = card.querySelector('.centre-card-title');
                            if (titleEl) titleEl.textContent = this.value || 'Untitled Centre';
                        }
                        if (field === 'state') {
                            const stateEl = card.querySelector('.centre-card-state');
                            if (stateEl) stateEl.textContent = this.value;
                        }
                        if (field === 'units_count') {
                            const unitsEl = card.querySelector('.centre-card-units');
                            if (unitsEl) unitsEl.textContent = this.value;
                        }
                    }
                    syncJson();
                }
            });
            input.addEventListener('change', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                const field = this.getAttribute('data-field');
                if (items[idx]) {
                    items[idx][field] = this.value;
                    syncJson();
                }
            });
        });
    }

    function syncJson() {
        jsonInput.value = JSON.stringify(items, null, 4);
    }

    window.deleteClusterItem = function(idx) {
        if (confirm('Are you sure you want to remove this centre?')) {
            items.splice(idx, 1);
            renderCards();
            syncJson();
        }
    };

    if (btnAdd) {
        btnAdd.addEventListener('click', function () {
            items.push({
                name: 'New Centre',
                code: 'surat',
                state: '',
                districts: '',
                units_count: '100',
                specialization: '',
                description: '',
                image: ''
            });
            renderCards();
            syncJson();
        });
    }

    if (btnOfficial) {
        btnOfficial.addEventListener('click', function () {
            if (confirm('Load the official 6 TEFC Centres data (Surat, Karur, Ichalkaranji, Jaipur, Varanasi, Ludhiana)? This will populate all official district and unit metrics.')) {
                items = JSON.parse(JSON.stringify(OFFICIAL_CENTRES));
                renderCards();
                syncJson();
            }
        });
    }

    renderCards();
}

function removeSliderImage(index) {
    const wrap = document.getElementById('img_wrap_' + index);
    if (wrap) {
        wrap.remove();
    }
}

// Leadership People Cards Management
let personCardIndex = {{ isset($section->content['people']) && is_array($section->content['people']) ? count($section->content['people']) : 3 }};

function previewPersonImage(input, idx) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('person_preview_' + idx);
            if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePersonCard(btn) {
    const card = btn.closest('.person-edit-card');
    if (card) {
        card.remove();
        // Update person badges
        const cards = document.querySelectorAll('.person-edit-card');
        cards.forEach((c, i) => {
            const badge = c.querySelector('.badge');
            if (badge) badge.textContent = `Person #${i + 1}`;
        });
    }
}

function addPersonCard() {
    const container = document.getElementById('people_cards_list');
    if (!container) return;
    const idx = personCardIndex++;
    const count = container.children.length + 1;
    const col = document.createElement('div');
    col.className = 'col-md-4 person-edit-card';
    col.id = 'person_card_' + idx;
    col.innerHTML = `
        <div class="p-3 border rounded-3 bg-light h-100 position-relative shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-secondary">Person #${count}</span>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removePersonCard(this)" title="Remove Person">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="text-center mb-3">
                <div class="position-relative d-inline-block">
                    <img id="person_preview_${idx}" src="{{ asset('images/people/neelam_shami_rao.png') }}" class="rounded border shadow-sm" style="width: 110px; height: 120px; object-fit: cover; background: #fff;">
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label fw-bold small mb-1">Upload Photo</label>
                <input type="file" name="people_image_${idx}" class="form-control form-control-sm" accept="image/*" onchange="previewPersonImage(this, ${idx})">
                <input type="hidden" name="people_existing_image[]" value="">
            </div>

            <div class="mb-2">
                <label class="form-label fw-bold small mb-1">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="people_name[]" value="" class="form-control form-control-sm" placeholder="e.g. Officer Name" required>
            </div>

            <div class="mb-2">
                <label class="form-label fw-bold small mb-1">Designation / Role</label>
                <input type="text" name="people_designation[]" value="" class="form-control form-control-sm" placeholder="e.g. Chairperson's Message">
            </div>

            <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Link URL (Optional)</label>
                <input type="text" name="people_link[]" value="/p/about" class="form-control form-control-sm" placeholder="/p/about">
            </div>
        </div>
    `;
    container.appendChild(col);
}

// ══════════════════════════════════════════════════════════════════
// DEDICATED USER-FRIENDLY CLUSTER DATA WORKSPACE INITIALIZER
// ══════════════════════════════════════════════════════════════════
function initClustersDataEditor() {
    const jsonInput = document.getElementById('json_input_clusters_data');
    if (!jsonInput) return;

    let clustersMap = {};
    try {
        clustersMap = JSON.parse(jsonInput.value || '{}');
    } catch(e) {
        console.error('Failed to parse clusters_data JSON', e);
        return;
    }

    const clusterNames = Object.keys(clustersMap);
    if (clusterNames.length === 0) return;

    let activeClusterKey = clusterNames[0];

    const tabsContainer = document.getElementById('adminClusterTabs');

    function renderClusterTabs() {
        tabsContainer.innerHTML = '';
        clusterNames.forEach(k => {
            const cl = clustersMap[k];
            const li = document.createElement('li');
            li.className = 'nav-item';
            const isActive = k === activeClusterKey;
            li.innerHTML = `
                <button type="button" class="nav-link ${isActive ? 'active' : ''} fw-bold d-flex align-items-center gap-2" style="${isActive ? 'background: #6c1340; color:#fff;' : 'background:#fff; color:#4a5568; border:1px solid #e2e8f0;'} border-radius:10px; padding:0.5rem 1rem;">
                    <i class="fas fa-city"></i>
                    <span>${cl.name || k}</span>
                    <span class="badge ${isActive ? 'bg-light text-dark' : 'bg-secondary text-white'}" style="font-size:0.68rem;">${cl.state || ''}</span>
                </button>
            `;
            li.querySelector('button').onclick = () => {
                activeClusterKey = k;
                renderClusterTabs();
                loadClusterIntoForm();
            };
            tabsContainer.appendChild(li);
        });
    }

    function loadClusterIntoForm() {
        const c = clustersMap[activeClusterKey];
        if (!c) return;

        // Ensure default sections active
        c.sections_active = c.sections_active || {
            stats: true, overview: true, highlights: true, chart: true,
            exporters: true, facilities: true, events: true, office: true
        };
        c.events = c.events || [];
        c.highlights = c.highlights || [];
        c.exporters = c.exporters || [];
        c.chartCurr = c.chartCurr || [25, 28, 30, 29, 35, 34, 38, 40, 42, 38, 45, 48];
        c.chartPrev = c.chartPrev || [20, 22, 25, 24, 28, 27, 30, 32, 35, 30, 36, 40];

        // Banner
        document.getElementById('adm_cluster_state_badge').textContent = c.state || 'India';
        document.getElementById('adm_cluster_name_title').textContent = `${c.name || activeClusterKey} Textile Cluster`;
        document.getElementById('adm_sec_cluster_name').textContent = c.name || activeClusterKey;

        // Inline Stats Strip switch
        const inlineStatsSw = document.getElementById('adm_sw_stats_inline');
        const inlineStatsLbl = document.getElementById('adm_lbl_stats_inline');
        if (inlineStatsSw) {
            const isStatsActive = c.sections_active.stats !== false;
            inlineStatsSw.checked = isStatsActive;
            if (inlineStatsLbl) {
                inlineStatsLbl.textContent = isStatsActive ? 'Active' : 'Inactive';
                inlineStatsLbl.className = `form-check-label small fw-bold ${isStatsActive ? 'text-success' : 'text-danger'}`;
            }
            inlineStatsSw.onchange = function() {
                c.sections_active.stats = this.checked;
                const topSw = document.getElementById('adm_sw_stats');
                if (topSw) topSw.checked = this.checked;
                if (inlineStatsLbl) {
                    inlineStatsLbl.textContent = this.checked ? 'Active' : 'Inactive';
                    inlineStatsLbl.className = `form-check-label small fw-bold ${this.checked ? 'text-success' : 'text-danger'}`;
                }
                syncJson();
            };
        }

        // Switches
        ['stats', 'overview', 'highlights', 'chart', 'exporters', 'facilities', 'events', 'office'].forEach(s => {
            const sw = document.getElementById(`adm_sw_${s}`);
            if (sw) {
                sw.checked = c.sections_active[s] !== false;
                sw.onchange = function() {
                    c.sections_active[s] = this.checked;
                    if (s === 'stats' && inlineStatsSw) {
                        inlineStatsSw.checked = this.checked;
                        if (inlineStatsLbl) {
                            inlineStatsLbl.textContent = this.checked ? 'Active' : 'Inactive';
                            inlineStatsLbl.className = `form-check-label small fw-bold ${this.checked ? 'text-success' : 'text-danger'}`;
                        }
                    }
                    syncJson();
                };
            }
        });

        // Metrics & General
        document.getElementById('adm_f_name').value = c.name || '';
        document.getElementById('adm_f_state').value = c.state || '';
        document.getElementById('adm_f_spec').value = c.spec || '';
        document.getElementById('adm_f_era').value = c.era || '';
        document.getElementById('adm_f_units').value = c.units || '';
        document.getElementById('adm_f_exportVal').value = c.exportVal || '';
        document.getElementById('adm_f_workforce').value = c.workforce || '';
        document.getElementById('adm_f_dests').value = c.dests || '';

        // Overview
        document.getElementById('adm_f_desc').value = c.desc || '';

        // Contact
        document.getElementById('adm_f_address').value = c.address || '';
        document.getElementById('adm_f_phone').value = c.phone || '';
        document.getElementById('adm_f_email').value = c.email || '';
        document.getElementById('adm_f_mapUrl').value = c.mapUrl || '';

        // Highlights
        renderAdmHighlights(c);

        // Events
        renderAdmEvents(c);

        // Exporters
        renderAdmExporters(c);

        // Chart Table
        renderAdmChart(c);
    }

    function syncJson() {
        jsonInput.value = JSON.stringify(clustersMap);
    }

    window.syncActiveClusterField = function(field, val) {
        if (clustersMap[activeClusterKey]) {
            clustersMap[activeClusterKey][field] = val;
            if (field === 'name' || field === 'state') {
                renderClusterTabs();
                document.getElementById('adm_cluster_name_title').textContent = `${clustersMap[activeClusterKey].name} Textile Cluster`;
                document.getElementById('adm_cluster_state_badge').textContent = clustersMap[activeClusterKey].state;
                document.getElementById('adm_sec_cluster_name').textContent = clustersMap[activeClusterKey].name;
            }
            syncJson();
        }
    };

    function renderAdmHighlights(c) {
        const wrap = document.getElementById('adm_highlights_tags');
        wrap.innerHTML = '';
        const list = c.highlights || [];
        if (list.length === 0) {
            wrap.innerHTML = '<span class="text-muted small">No product highlights added yet. Use form below to add.</span>';
            return;
        }

        list.forEach((h, i) => {
            const name = typeof h === 'object' ? (h.name || '') : h;
            const img = (typeof h === 'object' && h.image) ? h.image : '';
            const card = document.createElement('div');
            card.className = 'border rounded-3 p-2 bg-white shadow-sm d-flex align-items-center gap-2';
            card.style.minWidth = '220px';
            const imgPreview = img ? formatAssetUrl(img) : '';
            
            card.innerHTML = `
                <div style="width:44px; height:44px; border-radius:6px; background:#f8f9fa; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center; border:1px solid #dee2e6;">
                    ${imgPreview ? `<img src="${imgPreview}" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-image text-muted\\'></i>';" style="width:100%; height:100%; object-fit:cover;">` : `<i class="fas fa-image text-muted"></i>`}
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-bold small text-truncate" title="${name}">${name}</div>
                    <label class="btn btn-link p-0 text-decoration-none text-primary" style="font-size:0.72rem; cursor:pointer;">
                        <i class="fas fa-camera me-1"></i>${img ? 'Change Image' : 'Add Image'}
                        <input type="file" accept="image/*" style="display:none;" onchange="admUploadHighlightImg(${i}, this)">
                    </label>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="admRemoveHighlight(${i})" title="Remove Highlight"><i class="fas fa-times"></i></button>
            `;
            wrap.appendChild(card);
        });
    }

    window.admUploadHighlightImg = async function(idx, fileInput) {
        if (!fileInput.files || !fileInput.files[0]) return;
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

        const card = fileInput.closest('.border');
        const label = card ? card.querySelector('label') : null;
        const origText = label ? label.innerHTML : '';
        if (label) label.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

        try {
            fileInput.disabled = true;
            const res = await fetch("{{ route('cms.upload-cluster-image') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                const c = clustersMap[activeClusterKey];
                const current = c.highlights[idx];
                const name = typeof current === 'object' ? current.name : current;
                c.highlights[idx] = { name: name, image: data.path || data.url };
                renderAdmHighlights(c);
                syncJson();
            } else {
                alert(data.message || 'Image upload failed.');
                if (label) label.innerHTML = origText;
            }
        } catch(err) {
            console.error(err);
            alert('An error occurred while uploading image.');
            if (label) label.innerHTML = origText;
        } finally {
            fileInput.disabled = false;
        }
    };

    window.admAddHighlight = async function() {
        const inp = document.getElementById('adm_new_highlight_input');
        const fileInp = document.getElementById('adm_new_highlight_file');
        const val = inp.value.trim();
        const c = clustersMap[activeClusterKey];
        if (!val || !c) {
            alert('Please enter a product title.');
            return;
        }

        c.highlights = c.highlights || [];
        let imgPath = '';

        if (fileInp && fileInp.files && fileInp.files[0]) {
            const formData = new FormData();
            formData.append('image', fileInp.files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
            
            const addBtn = fileInp.closest('.card')?.querySelector('button');
            const origBtnText = addBtn ? addBtn.innerHTML : '';
            if (addBtn) { addBtn.disabled = true; addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...'; }

            try {
                const res = await fetch("{{ route('cms.upload-cluster-image') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    imgPath = data.path || data.url;
                } else {
                    alert(data.message || 'Image upload failed.');
                }
            } catch(e) {
                console.error(e);
                alert('An error occurred while uploading product image.');
            } finally {
                if (addBtn) { addBtn.disabled = false; addBtn.innerHTML = origBtnText; }
            }
        }

        c.highlights.push({ name: val, image: imgPath });
        inp.value = '';
        if (fileInp) fileInp.value = '';
        renderAdmHighlights(c);
        syncJson();
    };

    window.admRemoveHighlight = function(i) {
        const c = clustersMap[activeClusterKey];
        if (c && c.highlights) {
            c.highlights.splice(i, 1);
            renderAdmHighlights(c);
            syncJson();
        }
    };

    function renderAdmEvents(c) {
        const wrap = document.getElementById('adm_events_container');
        wrap.innerHTML = '';
        const evts = c.events || [];
        document.getElementById('adm_events_count').textContent = evts.length;

        if (evts.length === 0) {
            wrap.innerHTML = `
                <div class="text-center p-3 border rounded-3 bg-light text-muted small">
                    No events listed for this cluster yet. Click "+ Add Event" to publish one.
                </div>
            `;
            return;
        }

        evts.forEach((evt, i) => {
            const card = document.createElement('div');
            card.className = 'p-3 border rounded-3 bg-light position-relative';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-warning text-dark fw-bold">Event #${i + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="admRemoveEvent(${i})"><i class="fas fa-trash me-1"></i> Delete</button>
                </div>
                <div class="row g-2">
                    <div class="col-md-7">
                        <label class="small text-muted fw-bold">Title</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.title || ''}" onchange="admUpdateEvent(${i}, 'title', this.value)" placeholder="Event Title" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold">Type</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.type || ''}" onchange="admUpdateEvent(${i}, 'type', this.value)" placeholder="e.g. Workshop">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted fw-bold">Status</label>
                        <select class="form-select form-select-sm" onchange="admUpdateEvent(${i}, 'status', this.value)">
                            <option value="Upcoming" ${evt.status === 'Upcoming' ? 'selected' : ''}>Upcoming</option>
                            <option value="Registration Open" ${evt.status === 'Registration Open' ? 'selected' : ''}>Registration Open</option>
                            <option value="Scheduled" ${evt.status === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
                            <option value="Completed" ${evt.status === 'Completed' ? 'selected' : ''}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Date</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.date || ''}" onchange="admUpdateEvent(${i}, 'date', this.value)" placeholder="e.g. Dec 15, 2026">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Time</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.time || ''}" onchange="admUpdateEvent(${i}, 'time', this.value)" placeholder="e.g. 10:00 AM – 3:30 PM IST">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Badge</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.badge || ''}" onchange="admUpdateEvent(${i}, 'badge', this.value)" placeholder="e.g. Free Registration">
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Venue</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.venue || ''}" onchange="admUpdateEvent(${i}, 'venue', this.value)" placeholder="Venue">
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Registration / Info Link</label>
                        <input type="text" class="form-control form-control-sm" value="${evt.reg_url || ''}" onchange="admUpdateEvent(${i}, 'reg_url', this.value)" placeholder="#register">
                    </div>
                    <div class="col-12">
                        <label class="small text-muted fw-bold">Description</label>
                        <textarea class="form-control form-control-sm" rows="2" onchange="admUpdateEvent(${i}, 'desc', this.value)">${evt.desc || ''}</textarea>
                    </div>
                </div>
            `;
            wrap.appendChild(card);
        });
    }

    window.admAddEvent = function() {
        const c = clustersMap[activeClusterKey];
        if (c) {
            c.events = c.events || [];
            c.events.push({
                title: 'New Scheduled Event',
                type: 'Workshop',
                status: 'Registration Open',
                date: 'Jan 20, 2027',
                time: '10:00 AM – 3:30 PM IST',
                badge: 'Free Registration',
                venue: 'TEFC Facilitation Centre',
                reg_url: '#register',
                desc: 'Textile export facilitation and technical training session.'
            });
            renderAdmEvents(c);
            syncJson();
        }
    };

    window.admUpdateEvent = function(i, field, val) {
        const c = clustersMap[activeClusterKey];
        if (c && c.events && c.events[i]) {
            c.events[i][field] = val;
            syncJson();
        }
    };

    window.admRemoveEvent = function(i) {
        const c = clustersMap[activeClusterKey];
        if (c && c.events && confirm('Remove this event?')) {
            c.events.splice(i, 1);
            renderAdmEvents(c);
            syncJson();
        }
    };

    function renderAdmExporters(c) {
        const wrap = document.getElementById('adm_exporters_container');
        wrap.innerHTML = '';
        const exps = c.exporters || [];
        document.getElementById('adm_exporters_count').textContent = exps.length;

        if (exps.length === 0) {
            wrap.innerHTML = `
                <div class="text-center p-3 border rounded-3 bg-light text-muted small">
                    No exporters listed. Click "+ Add Exporter" to add verified exporters.
                </div>
            `;
            return;
        }

        exps.forEach((exp, i) => {
            const card = document.createElement('div');
            card.className = 'p-3 border rounded-3 bg-light position-relative';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary text-white fw-bold">Exporter #${i + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="admRemoveExporter(${i})"><i class="fas fa-trash me-1"></i> Delete</button>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Company Name</label>
                        <input type="text" class="form-control form-control-sm" value="${exp.name || ''}" onchange="admUpdateExporter(${i}, 'name', this.value)" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Proprietor / Contact</label>
                        <input type="text" class="form-control form-control-sm" value="${exp.owner || ''}" onchange="admUpdateExporter(${i}, 'owner', this.value)">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted fw-bold">Est. Year</label>
                        <input type="text" class="form-control form-control-sm" value="${exp.est || ''}" onchange="admUpdateExporter(${i}, 'est', this.value)">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Products (comma-separated)</label>
                        <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.prods) ? exp.prods.join(', ') : (exp.prods || '')}" onchange="admUpdateExporterArray(${i}, 'prods', this.value)">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Destinations (comma-separated)</label>
                        <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.exports) ? exp.exports.join(', ') : (exp.exports || '')}" onchange="admUpdateExporterArray(${i}, 'exports', this.value)">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Certifications (comma-separated)</label>
                        <input type="text" class="form-control form-control-sm" value="${Array.isArray(exp.certs) ? exp.certs.join(', ') : (exp.certs || '')}" onchange="admUpdateExporterArray(${i}, 'certs', this.value)">
                    </div>
                </div>
            `;
            wrap.appendChild(card);
        });
    }

    window.admAddExporter = function() {
        const c = clustersMap[activeClusterKey];
        if (c) {
            c.exporters = c.exporters || [];
            c.exporters.push({
                name: 'New Textile Exporter',
                owner: 'Promoter Name',
                est: '2015',
                prods: ['Fabrics'],
                exports: ['USA'],
                certs: ['ISO 9001']
            });
            renderAdmExporters(c);
            syncJson();
        }
    };

    window.admUpdateExporter = function(i, field, val) {
        const c = clustersMap[activeClusterKey];
        if (c && c.exporters && c.exporters[i]) {
            c.exporters[i][field] = val;
            syncJson();
        }
    };

    window.admUpdateExporterArray = function(i, field, str) {
        const c = clustersMap[activeClusterKey];
        if (c && c.exporters && c.exporters[i]) {
            c.exporters[i][field] = str.split(',').map(s => s.trim()).filter(Boolean);
            syncJson();
        }
    };

    window.admRemoveExporter = function(i) {
        const c = clustersMap[activeClusterKey];
        if (c && c.exporters && confirm('Delete this exporter?')) {
            c.exporters.splice(i, 1);
            renderAdmExporters(c);
            syncJson();
        }
    };

    function renderAdmChart(c) {
        const tbody = document.getElementById('adm_chart_table_body');
        tbody.innerHTML = '';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        let currRow = '<tr><td class="fw-bold text-primary text-start">Current Year</td>';
        months.forEach((m, idx) => {
            currRow += `<td><input type="number" step="any" class="form-control form-control-sm text-center p-1" style="min-width:48px;" value="${(c.chartCurr && c.chartCurr[idx]) ?? 0}" onchange="admUpdateChart(${idx}, 'chartCurr', this.value)"></td>`;
        });
        currRow += '</tr>';

        let prevRow = '<tr><td class="fw-bold text-muted text-start">Previous Year</td>';
        months.forEach((m, idx) => {
            prevRow += `<td><input type="number" step="any" class="form-control form-control-sm text-center p-1" style="min-width:48px;" value="${(c.chartPrev && c.chartPrev[idx]) ?? 0}" onchange="admUpdateChart(${idx}, 'chartPrev', this.value)"></td>`;
        });
        prevRow += '</tr>';

        tbody.innerHTML = currRow + prevRow;
    }

    window.admUpdateChart = function(idx, arrKey, val) {
        const c = clustersMap[activeClusterKey];
        if (c) {
            c[arrKey] = c[arrKey] || [];
            c[arrKey][idx] = parseFloat(val) || 0;
            syncJson();
        }
    };

    // Initialize
    renderClusterTabs();
    loadClusterIntoForm();
}
</script>
@endsection
