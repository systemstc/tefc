@extends('layouts.portal')

@section('title', 'Content Management System (CMS) — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}" class="active"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-primary">Content Management System (CMS)</h2>
    <p class="text-muted">Directly manage the text, images, and visual components of every page on the TEFC Portal.</p>
</div>

<div class="cms-grid">
    @foreach($pages as $page)
        <div class="portal-card mb-5">
            <div class="portal-card-header bg-light">
                <div>
                    <h3 class="mb-0 text-dark" style="font-weight: 800;">{{ $page->title }} Page</h3>
                    <div class="text-muted small">Live URL: <a href="{{ url('/') . ($page->slug == 'home' ? '' : '/p/' . $page->slug) }}" target="_blank" class="text-primary text-decoration-none">{{ url('/') . ($page->slug == 'home' ? '' : '/p/' . $page->slug) }} <i class="fas fa-external-link-alt ms-1"></i></a></div>
                </div>
                <span class="badge bg-white text-primary border rounded-pill px-3">{{ $page->sections->count() }} Modular Sections</span>
            </div>
            
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 32%;">Section Name (Component)</th>
                            <th style="width: 15%;">Technical Type</th>
                            <th style="width: 12%;">Status</th>
                            <th>Description</th>
                            <th class="text-end" style="width: 25%;">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($page->sections as $section)
                            <tr class="{{ $section->is_active ? '' : 'table-light opacity-75' }}">
                                <td class="fw-bold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-puzzle-piece {{ $section->is_active ? 'text-primary' : 'text-muted' }}"></i>
                                        <span>{{ $section->title ?: ucwords(str_replace('_', ' ', $section->type)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-muted border text-uppercase" style="font-size: 0.7rem;">{{ $section->type }}</span>
                                </td>
                                <td>
                                    @if($section->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fw-bold">
                                            <i class="fas fa-eye-slash me-1"></i> Inactive
                                        </span>
                                    @endif

                                    @if($section->type === 'clusters_grid')
                                        <div class="mt-1">
                                            @if(!empty($section->content['show_map_card']))
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.72rem;" title="Interactive India Map is currently shown on homepage">
                                                    <i class="fas fa-map-marked-alt me-1"></i> Map: Active
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.72rem;" title="Interactive India Map is currently hidden (Cards only)">
                                                    <i class="fas fa-map-marked-alt me-1"></i> Map: Inactive
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    @if($section->type == 'registration_modal')
                                        First-visit exporter registration popup modal with title, subtitle, and call-to-action button.
                                    @elseif($section->type == 'hero')
                                        Main banner, slider images, and entrance text.
                                    @elseif($section->type == 'announcement')
                                        Live scrolling announcement bar, trade notifications, circulars, and marquee updates.
                                    @elseif($section->type == 'gallery')
                                        Visual cluster gallery, photo expos, workshops, and lightbox view.
                                    @elseif($section->type == 'clusters_grid')
                                        Interactive cluster cards with state tags, units, and explore links.
                                    @elseif($section->type == 'clusters_data')
                                        Textile clusters directory data with state, city, units, and designated jurisdiction.
                                    @elseif($section->type == 'about_intro')
                                        Introductory paragraphs, mission highlights, and leadership messages.
                                    @elseif($section->type == 'about_hero')
                                        About page hero banner with breadcrumbs and highlight metrics.
                                    @elseif($section->type == 'about_overview')
                                        Core overview narrative, 4 feature support blocks, and platform pulse stats.
                                    @elseif($section->type == 'mission_vision')
                                        Mission statement, long-term vision, and strategic objectives list.
                                    @elseif($section->type == 'people_grid')
                                        Key people directory with portraits, designations, and statutory charter.
                                    @elseif($section->type == 'org_structure')
                                        Hierarchical administrative flow and regional branch node grid.
                                    @elseif($section->type == 'milestone_timeline')
                                        Chronological progression cards of institutional milestones.
                                    @elseif($section->type == 'fta_benefits')
                                        Free Trade Agreement schemes and direct benefits overview.
                                    @elseif($section->type == 'fta_hero')
                                        FTA page main banner with subtitle and strategic trade metric counters.
                                    @elseif($section->type == 'fta_schemes')
                                        Government incentive schemes (RoDTEP, RoSCTL, PM-MITRA, PLI, EPCG, MAI).
                                    @elseif($section->type == 'fta_agreements')
                                        Bilateral and multilateral trade agreements with partner countries and duty benefits.
                                    @elseif($section->type == 'services_hero')
                                        Online Services page hero header and portal introduction.
                                    @elseif($section->type == 'services_grid')
                                        Single-window digital service desks directory and facility tiles.
                                    @elseif($section->type == 'service_detail')
                                        Facilitation guide with overview, required documents, process steps, and official portal link.
                                    @elseif($section->type == 'online_services_grid')
                                        Quick access service tiles and interactive exporter facilities.
                                    @elseif($section->type == 'news_list')
                                        Trade news, notifications, and ministerial updates.
                                    @elseif($section->type == 'media_center')
                                        Video and photo media gallery items.
                                    @elseif($section->type == 'help_faq')
                                        Frequently asked questions and direct support contacts.
                                    @elseif($section->type == 'assistance_banner')
                                        Direct call-to-action banner and contact support button.
                                    @else
                                        Custom modular content block for page data.
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        @if($section->type === 'clusters_grid')
                                            <form action="{{ route('admin.sections.toggle-map-card', $section->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ !empty($section->content['show_map_card']) ? 'btn-outline-danger' : 'btn-outline-primary' }} px-2 fw-semibold" title="{{ !empty($section->content['show_map_card']) ? 'Inactivate Map Card (Hide map showcase, keep cluster cards)' : 'Activate Map Card (Show map showcase)' }}">
                                                    <i class="fas fa-map-marked-alt me-1"></i>
                                                    {{ !empty($section->content['show_map_card']) ? 'Inactivate Map' : 'Activate Map' }}
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.sections.toggle', $section->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} px-2" title="{{ $section->is_active ? 'Inactivate (Hide section from live site)' : 'Activate (Show section on live site)' }}">
                                                @if($section->is_active)
                                                    <i class="fas fa-eye-slash me-1"></i> Inactivate
                                                @else
                                                    <i class="fas fa-eye me-1"></i> Activate
                                                @endif
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.sections.edit', $section->id) }}" class="btn btn-sm btn-primary px-3 fw-bold" title="Edit Content"><i class="fas fa-pen-nib me-1"></i> Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
