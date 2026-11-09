<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TEFC — Textiles Exports Facilitation Centre')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ file_exists(public_path('css/style.css')) ? filemtime(public_path('css/style.css')) : time() }}">
    @yield('styles')
</head>
<body>
    <!-- LEFT-SIDE ACCESSIBILITY DRAWER & BACKDROP -->
    <div class="accessibility-drawer-backdrop" id="accessibilityBackdrop"></div>
    <aside class="accessibility-drawer" id="accessibilityDrawer" role="dialog" aria-modal="true" aria-labelledby="accessibilityDrawerTitle" aria-hidden="true">
        <div class="drawer-header">
            <div class="drawer-header-brand">
                <div class="drawer-icon-wrap"><i class="fas fa-universal-access"></i></div>
                <div>
                    <h3 id="accessibilityDrawerTitle" class="drawer-title">Accessibility Options</h3>
                    <span class="drawer-subtitle">सुगमता एवं पहुँच सहायता</span>
                </div>
            </div>
            <button type="button" class="drawer-close-btn" id="btnCloseAccessibility" aria-label="Close Accessibility Menu">&times;</button>
        </div>

        <div class="drawer-body">
            <!-- 1. Text Sizing -->
            <div class="drawer-section">
                <div class="drawer-section-header">
                    <label><i class="fas fa-text-height text-primary"></i> Text Sizing (फॉन्ट आकार)</label>
                    <span class="current-val-pill" id="fontSizeDisplay">100%</span>
                </div>
                <div class="drawer-btn-grid four-cols">
                    <button type="button" class="drawer-btn" id="btnDrawerFontSmall" data-size="small">A- <small>88%</small></button>
                    <button type="button" class="drawer-btn active" id="btnDrawerFontNormal" data-size="normal">A <small>100%</small></button>
                    <button type="button" class="drawer-btn" id="btnDrawerFontLarge" data-size="large">A+ <small>115%</small></button>
                    <button type="button" class="drawer-btn" id="btnDrawerFontXLarge" data-size="xlarge">A++ <small>125%</small></button>
                </div>
            </div>

            <!-- 2. Contrast & Color Themes -->
            <div class="drawer-section">
                <div class="drawer-section-header">
                    <label><i class="fas fa-circle-half-stroke text-primary"></i> Contrast & Themes (रंग और कंट्रास्ट)</label>
                </div>
                <div class="drawer-btn-grid two-cols">
                    <button type="button" class="drawer-btn active" id="btnThemeDefault">
                        <i class="fas fa-sun text-warning me-1"></i> Standard Light
                    </button>
                    <button type="button" class="drawer-btn" id="btnThemeHighContrast">
                        <i class="fas fa-adjust me-1"></i> High Contrast (GIGW)
                    </button>
                    <button type="button" class="drawer-btn" id="btnThemeDarkMode">
                        <i class="fas fa-moon text-primary me-1"></i> Dark Mode
                    </button>
                    <button type="button" class="drawer-btn" id="btnThemeGrayscale">
                        <i class="fas fa-eye-slash me-1"></i> Grayscale
                    </button>
                </div>
            </div>

            <!-- 3. Reading & Typography Enhancements -->
            <div class="drawer-section">
                <div class="drawer-section-header">
                    <label><i class="fas fa-book-open-reader text-primary"></i> Reading Assistance (पठन सहायता)</label>
                </div>
                <div class="drawer-toggle-list">
                    <button type="button" class="drawer-toggle-btn" id="btnToggleDyslexia">
                        <div class="toggle-info">
                            <strong>Dyslexia Friendly Font</strong>
                            <small>High-legibility typography</small>
                        </div>
                        <span class="toggle-indicator"><i class="fas fa-toggle-off"></i></span>
                    </button>

                    <button type="button" class="drawer-toggle-btn" id="btnToggleLineSpacing">
                        <div class="toggle-info">
                            <strong>Expanded Line Spacing</strong>
                            <small>Increases line height for clarity</small>
                        </div>
                        <span class="toggle-indicator"><i class="fas fa-toggle-off"></i></span>
                    </button>

                    <button type="button" class="drawer-toggle-btn" id="btnToggleHighlightLinks">
                        <div class="toggle-info">
                            <strong>Highlight Links</strong>
                            <small>Outlines all clickable hyperlinks</small>
                        </div>
                        <span class="toggle-indicator"><i class="fas fa-toggle-off"></i></span>
                    </button>
                </div>
            </div>

            <!-- 4. Dedicated Bhashini Integration Slot in Drawer -->
            <div class="drawer-section bhashini-drawer-card">
                <div class="drawer-section-header">
                    <label><i class="fas fa-language" style="color: var(--accent);"></i> Digital India भाषिनी (Bhashini API)</label>
                </div>
                <p class="small text-muted mb-2">Translate entire portal content instantly into official Indian languages using AI speech and language models.</p>
                <!-- Bhashini Plugin API Mount Target -->
                <div id="bhashini-drawer-plugin-slot" class="bhashini-slot-mount mb-2">
                    <div class="bhashini-api-badge">
                        <span class="badge-dot"></span> Bhashini AI Translation Bridge Active
                    </div>
                </div>
                <div class="drawer-lang-quick-grid">
                    <button type="button" class="btn-drawer-lang active" data-lang="en" data-name="English">English</button>
                    <button type="button" class="btn-drawer-lang" data-lang="hi" data-name="हिन्दी">हिन्दी</button>
                    <button type="button" class="btn-drawer-lang" data-lang="gu" data-name="ગુજરાતી">ગુજરાતી</button>
                    <button type="button" class="btn-drawer-lang" data-lang="ta" data-name="தமிழ்">தமிழ்</button>
                    <button type="button" class="btn-drawer-lang" data-lang="mr" data-name="मराठी">मराठी</button>
                    <button type="button" class="btn-drawer-lang" data-lang="pa" data-name="ਪੰਜਾਬੀ">ਪੰਜਾਬੀ</button>
                    <button type="button" class="btn-drawer-lang" data-lang="bn" data-name="বাংলা">বাংলা</button>
                </div>
            </div>

            <!-- 5. Reset All Button -->
            <div class="drawer-footer-actions">
                <button type="button" class="btn-drawer-reset" id="btnResetAllAccessibility">
                    <i class="fas fa-rotate-left me-1"></i> Reset All Settings to Default
                </button>
            </div>
        </div>
    </aside>

    <!-- HIDDEN ACCESSIBILITY COMPATIBILITY BRIDGE -->
    <div class="visually-hidden" aria-hidden="true" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;">
        <span>Government of India</span>
        <span>Ministry of Textiles</span>
        <button type="button" id="btnFontDec" aria-label="Decrease font size">A-</button>
        <button type="button" id="btnFontReset" aria-label="Reset font size">A</button>
        <button type="button" id="btnFontInc" aria-label="Increase font size">A+</button>
        <button type="button" id="btnContrastToggle" aria-label="Toggle contrast">Contrast</button>
        <div id="google_translate_element"></div>
    </div>

    <!-- MAIN HEADER (LOGOBAR) -->
    <div class="logobar">
        <div class="logo-emblem">
            <img src="{{asset('images/mot_logo.jpeg')}}" alt="Ministry of Textiles, Government of India">
        </div>

        <div class="logo-text-block">
            <div class="logo-title">
                <span class="red-letter">T</span>extile
                <span class="red-letter">E</span>xport
                <span class="red-letter">F</span>acilitation
                <span class="red-letter">C</span>entre
            </div>

            <div class="logo-subtitle">
                {{ $siteSettings['logo_subtitle'] ?? 'An initiative of Textiles Committee, Ministry of Textiles, Government of India' }}
            </div>
        </div>

        <div class="logo-right-group">
            <!-- 3-ICON ACCESSIBILITY & LANGUAGE CONTROLS (SKIP TO CONTENT | अ/A | ACCESSIBILITY) -->
            <div class="header-util-trio" role="toolbar" aria-label="Accessibility and Language Controls">
                <!-- 1. Skip to Main Content Icon -->
                <a href="#main-content" class="header-util-btn" id="skipToContentLink" title="Skip to Main Content" aria-label="Skip to Main Content">
                    <svg class="header-util-svg" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 6H24C25.1 6 26 6.9 26 8V24C26 25.1 25.1 26 24 26H11C9.9 26 9 25.1 9 24V20"></path>
                        <path d="M9 12V8C9 6.9 9.9 6 11 6"></path>
                        <line x1="15" y1="11" x2="21" y2="11"></line>
                        <line x1="15" y1="16" x2="21" y2="16"></line>
                        <line x1="15" y1="21" x2="21" y2="21"></line>
                        <line x1="5" y1="16" x2="13" y2="16"></line>
                        <polyline points="9 12 13 16 9 20"></polyline>
                    </svg>
                    <!-- <span class="visually-hidden">Skip to Main Content</span> -->
                </a>

                <span class="header-util-sep" aria-hidden="true"></span>

                <!-- 2. Language Switcher (अ / A) with Bhashini API mount & dropdown -->
                <div class="header-util-lang-wrap" id="bhashiniContainer" data-plugin-slot="bhashini-api">
                    <div id="bhashiniPluginSlot" class="bhashini-slot-wrapper">
                        <div id="bhashini-plugin-container" class="bhashini-api-mount">
                            <button type="button" class="header-util-btn" id="bhashiniDropdownBtn" title="Language Translation (भाषिनी Bhashini)" aria-label="Bhashini Language Translation" aria-expanded="false" aria-haspopup="true">
                                <div class="header-util-akshar" aria-hidden="true">
                                    <span class="akshar-hi">अ</span>
                                    <span class="akshar-en">A</span>
                                </div>
                                <!-- <span class="visually-hidden">Bhashini Language Translation</span> -->
                            </button>
                        </div>
                    </div>

                    <!-- Bhashini Dropdown Menu -->
                    <div class="bhashini-dropdown-menu" id="bhashiniDropdownMenu" style="display: none;">
                        <div class="bhashini-menu-header">
                            <div class="bhashini-brand-row">
                                <span class="bhashini-flag-icon">🇮🇳</span>
                                <div class="bhashini-brand-text">
                                    <strong>Digital India भाषिनी</strong>
                                    <small>National Language Translation Mission</small>
                                </div>
                            </div>
                        </div>

                        <div class="bhashini-search-wrap">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="bhashiniLangSearch" placeholder="Search language / भाषा खोजें..." autocomplete="off">
                        </div>

                        <div class="bhashini-menu-body">
                            <div class="bhashini-section-title">
                                <i class="fas fa-map-pin" style="color: var(--accent);"></i> Key Textile Cluster Languages
                            </div>
                            <div class="bhashini-cluster-list">
                                <button type="button" class="bhashini-lang-option active" data-code="en" data-name="English">
                                    <div class="lang-info">
                                        <span class="lang-native">English</span>
                                        <span class="lang-meta">Default Official</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="hi" data-name="हिन्दी">
                                    <div class="lang-info">
                                        <span class="lang-native">हिन्दी (Hindi)</span>
                                        <span class="lang-meta">Varanasi, Gorakhpur, Panipat, Jaipur</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="gu" data-name="ગુજરાતી">
                                    <div class="lang-info">
                                        <span class="lang-native">ગુજરાતી (Gujarati)</span>
                                        <span class="lang-meta">Surat, Ahmedabad, Rajkot</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="ta" data-name="தமிழ்">
                                    <div class="lang-info">
                                        <span class="lang-native">தமிழ் (Tamil)</span>
                                        <span class="lang-meta">Tirupur, Karur, Coimbatore</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="mr" data-name="मराठी">
                                    <div class="lang-info">
                                        <span class="lang-native">मराठी (Marathi)</span>
                                        <span class="lang-meta">Ichalkaranji, Solapur, Bhiwandi</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="pa" data-name="ਪੰਜਾਬੀ">
                                    <div class="lang-info">
                                        <span class="lang-native">ਪੰਜਾਬੀ (Punjabi)</span>
                                        <span class="lang-meta">Ludhiana, Amritsar</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="bn" data-name="বাংলা">
                                    <div class="lang-info">
                                        <span class="lang-native">বাংলা (Bengali)</span>
                                        <span class="lang-meta">Kolkata, Shantipur</span>
                                    </div>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                            </div>

                            <div class="bhashini-section-title" style="margin-top: 8px;">
                                <i class="fas fa-globe-asia"></i> Other Indian Languages
                            </div>
                            <div class="bhashini-other-list">
                                <button type="button" class="bhashini-lang-option" data-code="te" data-name="తెలుగు">
                                    <span class="lang-native">తెలుగు (Telugu)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="kn" data-name="ಕನ್ನಡ">
                                    <span class="lang-native">ಕನ್ನಡ (Kannada)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="ml" data-name="മലയാളം">
                                    <span class="lang-native">മലയാളം (Malayalam)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="or" data-name="ଓଡ଼ିଆ">
                                    <span class="lang-native">ଓଡ଼ିଆ (Odia)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="as" data-name="অসমীয়া">
                                    <span class="lang-native">অসমীয়া (Assamese)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                                <button type="button" class="bhashini-lang-option" data-code="ur" data-name="اردو">
                                    <span class="lang-native">اردو (Urdu)</span>
                                    <span class="lang-check"><i class="fas fa-check"></i></span>
                                </button>
                            </div>
                        </div>

                        <div class="bhashini-menu-footer">
                            <span>Powered by <strong>Bhashini NLTM</strong> &bull; MeitY, Govt of India</span>
                        </div>
                    </div>
                </div>

                <span class="header-util-sep" aria-hidden="true"></span>

                <!-- 3. Universal Accessibility Drawer Trigger (Stick figure icon) -->
                <button type="button" class="header-util-btn" id="btnOpenAccessibility" title="Accessibility Tools (Alt + A)" aria-label="Accessibility Options" aria-haspopup="dialog" aria-expanded="false">
                    <svg class="header-util-svg" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="16" cy="6" r="2.8"></circle>
                        <path d="M7 13C7 12 7.9 11.2 9 11.2H23C24.1 11.2 25 12 25 13C25 14 24.1 14.8 23 14.8H19.5V18.5L22.5 25.5C22.9 26.4 22.4 27.4 21.5 27.8C20.6 28.2 19.6 27.7 19.2 26.8L16.8 21.5H15.2L12.8 26.8C12.4 27.7 11.4 28.2 10.5 27.8C9.6 27.4 9.1 26.4 9.5 25.5L12.5 18.5V14.8H9C7.9 14.8 7 14 7 13Z"></path>
                    </svg>
                    <!-- <span class="visually-hidden">Accessibility Options</span> -->
                </button>
            </div>

            <!-- Textiles Committee Logo -->
            <div class="logo-right-emblem">
                <img src="{{asset('images/Textiles_Committee_Logo_2.png')}}" alt="Textiles Committee Logo">
            </div>
        </div>
    </div>

    <!-- MAIN NAV -->
    <nav class="main-nav">
        <button class="nav-hamburger" id="navHam">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="navLinks" style="list-style: none !important; margin: 0; padding: 0;">
            @if(isset($headerMenu) && $headerMenu->items->count() > 0)
                @foreach($headerMenu->items as $item)
                    <li class="{{ Request::is(trim($item->url, '/')) ? 'active' : '' }}">
                        <a href="{{ $item->final_url }}" @if(Str::startsWith($item->final_url, 'http') && !Str::contains($item->final_url, request()->getHost())) target="_blank" rel="noopener noreferrer" @endif>
                            {{ $item->label }}
                            @if($item->children->count() > 0)
                                <span class="arr">▼</span>
                            @endif
                        </a>
                        @if($item->children->count() > 0)
                            <ul class="dropdown-menu">
                                @foreach($item->children as $child)
                                    <li><a href="{{ $child->final_url }}" @if(Str::startsWith($child->final_url, 'http') && !Str::contains($child->final_url, request()->getHost())) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li><a href="{{ route('catalogue.index') }}" style="color: var(--accent); font-weight: bold;">Product Catalogue</a></li>
                <li class="{{ Request::is('kpi-dashboard*') ? 'active' : '' }}"><a href="{{ route('kpi.dashboard') }}" style="font-weight: bold;"><i class="fas fa-chart-pie me-1"></i> Dashboard</a></li>
            @else
                <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="/">Home</a></li>
                <li class="{{ Request::is('p/about*') ? 'active' : '' }}"><a href="/p/about">About</a></li>
                <li><a href="https://textilescommittee.gov.in" target="_blank" rel="noopener noreferrer">About TC</a></li>
                <li class="{{ Request::is('p/clusters*') ? 'active' : '' }}"><a href="/p/clusters">Clusters</a></li>
                <li class="{{ Request::is('resources*') ? 'active' : '' }}"><a href="/resources">Resources</a></li>
                <li class="{{ Request::is('p/fta-benefits*') ? 'active' : '' }}"><a href="/p/fta-benefits">FTA Benefits</a></li>
                <li class="{{ Request::is('p/online-services*') ? 'active' : '' }}"><a href="/p/online-services">Online Services</a></li>
                <li class="{{ Request::is('p/news*') ? 'active' : '' }}"><a href="/p/news">News</a></li>
                <li class="{{ Request::is('p/media*') ? 'active' : '' }}"><a href="/p/media">Media Center</a></li>
                <li class="{{ Request::is('p/help-support*') ? 'active' : '' }}"><a href="/p/help-support">Help & Support</a></li>
                <li><a href="{{ route('catalogue.index') }}" style="color: var(--accent); font-weight: bold;">Product Catalogue</a></li>
                <li class="{{ Request::is('kpi-dashboard*') ? 'active' : '' }}"><a href="{{ route('kpi.dashboard') }}" style="font-weight: bold;"><i class="fas fa-chart-pie me-1"></i> Dashboard</a></li>
            @endif
        </ul>
        <div class="nav-actions">
            @auth
                <div class="nav-auth-dropdown-wrap" id="userAuthDropdownWrap">
                    <button type="button" class="nav-login-btn logged-in" id="userAuthDropdownBtn" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-circle-user"></i>
                        <span>{{ Str::limit(Auth::user()->name, 14) }}</span>
                        <i class="fas fa-chevron-down nav-caret"></i>
                    </button>
                    <div class="nav-login-menu" id="userAuthMenu" style="display: none;">
                        <div class="nav-menu-header">
                            <span class="user-greeting">Signed in as</span>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role-badge">{{ ucfirst(Auth::user()->roles->first()->name ?? 'User') }}</span>
                        </div>
                        <div class="nav-menu-items">
                            <a href="{{ route('exporter-portal') }}" class="nav-menu-item">
                                <div class="nav-item-icon user-icon">
                                    <i class="fas fa-gauge-high"></i>
                                </div>
                                <div class="nav-item-text">
                                    <strong>Exporter Portal</strong>
                                    <small>Access orders, profile & inquiries</small>
                                </div>
                            </a>
                            <div class="nav-menu-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="nav-menu-item logout-item">
                                    <i class="fas fa-right-from-bracket"></i>
                                    <div class="nav-item-text">
                                        <strong style="color: #c62828;">Logout</strong>
                                        <small>Safely end your session</small>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="nav-login-dropdown-wrap" id="navLoginDropdownWrap">
                    <button type="button" class="nav-login-btn" id="navLoginDropdownBtn" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        <span>Login</span>
                        <i class="fas fa-chevron-down nav-caret"></i>
                    </button>
                    <div class="nav-login-menu" id="navLoginMenu" style="display: none;">
                        <div class="nav-menu-header">
                            <span>Select Portal Login</span>
                        </div>
                        <div class="nav-menu-items">
                            <a href="{{ url('/login') }}" class="nav-menu-item">
                                <div class="nav-item-icon user-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="nav-item-text">
                                    <strong>User Login</strong>
                                    <small>Registered Exporters, Sellers & International Buyers</small>
                                </div>
                            </a>
                            <a href="{{ url('/admin-login') }}" class="nav-menu-item">
                                <div class="nav-item-icon office-icon">
                                    <i class="fas fa-building-columns"></i>
                                </div>
                                <div class="nav-item-text">
                                    <strong>Office Login</strong>
                                    <small>TEFC Officials, Ministry Staff & System Admin</small>
                                </div>
                            </a>
                        </div>
                        <div class="nav-menu-footer">
                            <span>New exporter or buyer? <a href="{{ route('register') }}">Register here</a></span>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main id="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="tefc-main-footer">
        <div class="tefc-footer-wide-container">
            <div class="tefc-footer-top-block">
                <!-- Brand Header -->
                <div class="tefc-footer-brand">
                    <div class="tefc-footer-logo-title">
                        <span class="gold-letter">T</span>extile 
                        <span class="gold-letter">E</span>xport 
                        <span class="gold-letter">F</span>acilitation 
                        <span class="gold-letter">C</span>entre
                    </div>
                    <div class="tefc-footer-subtitle">
                        An initiative of Textiles Committee, Ministry of Textiles, Government of India
                    </div>
                </div>

                <!-- Menu Content Matching the Above Header Navigation + Sitemap -->
                <div class="tefc-footer-nav-wrap">
                    <div class="tefc-footer-nav-heading">Portal Navigation</div>
                    <ul class="tefc-footer-menu-grid">
                        <li><a href="{{ url('/') }}"><i class="fas fa-angle-right"></i> Home</a></li>
                        <li><a href="{{ url('/p/about') }}"><i class="fas fa-angle-right"></i> About Us</a></li>
                        <li><a href="https://textilescommittee.gov.in" target="_blank" rel="noopener noreferrer"><i class="fas fa-angle-right"></i> About TC</a></li>
                        <li><a href="{{ url('/p/clusters') }}"><i class="fas fa-angle-right"></i> Clusters</a></li>
                        <li><a href="{{ url('/p/fta-benefits') }}"><i class="fas fa-angle-right"></i> FTA Strategic Benefits</a></li>
                        <li><a href="{{ url('/p/online-services') }}"><i class="fas fa-angle-right"></i> Online Services</a></li>
                        <li><a href="{{ url('/p/news') }}"><i class="fas fa-angle-right"></i> Latest News</a></li>
                        <li><a href="{{ url('/p/media') }}"><i class="fas fa-angle-right"></i> Media Center</a></li>
                        <li><a href="{{ url('/p/help-support') }}"><i class="fas fa-angle-right"></i> Help &amp; Support</a></li>
                        <li><a href="{{ route('catalogue.index') }}"><i class="fas fa-angle-right"></i> Product Catalogue</a></li>
                        <li><a href="{{ route('kpi.dashboard') }}"><i class="fas fa-angle-right"></i> Dashboard</a></li>
                        <li><a href="{{ route('sitemap') }}" class="footer-sitemap-highlight"><i class="fas fa-sitemap"></i> Sitemap</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Sub-Footer -->
            <div class="tefc-footer-bottom">
                <div class="tefc-footer-copyright">
                    {{ $siteSettings['footer_copyright'] ?? '© 2026 Textile Export Facilitation Centre. Ministry of Textiles, Government of India. All rights reserved.' }}
                </div>
                <div class="tefc-footer-legal">
                    <a href="{{ route('sitemap') }}"><i class="fas fa-sitemap me-1"></i> Sitemap</a>
                    <span>•</span>
                    <a href="{{ url('/p/help-support') }}">Help &amp; Policies</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
    /* ── TEFC STREAMLINED PRESTIGIOUS FOOTER ── */
    .tefc-main-footer {
        background: linear-gradient(180deg, #38081f 0%, #200311 100%);
        color: #ffffff;
        padding: 3.5rem 0 1.8rem 0;
        border-top: 3px solid #b38e5d;
        position: relative;
        overflow: hidden;
    }
    .tefc-footer-wide-container {
        width: 95%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
        z-index: 1;
    }
    .tefc-footer-top-block {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding-bottom: 2.5rem;
        border-bottom: 1px solid rgba(179, 142, 93, 0.22);
    }
    .tefc-footer-brand {
        text-align: center;
    }
    .tefc-footer-logo-title {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        color: #ffffff;
        line-height: 1.25;
        margin-bottom: 0.35rem;
    }
    .tefc-footer-logo-title .gold-letter {
        color: #f8b803;
        font-weight: 900;
    }
    .tefc-footer-subtitle {
        font-size: 0.85rem;
        color: #dfcbb5;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        opacity: 0.9;
    }
    .tefc-footer-nav-wrap {
        text-align: center;
    }
    .tefc-footer-nav-heading {
        color: #f8b803;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        margin-bottom: 1.2rem;
        opacity: 0.85;
    }
    .tefc-footer-menu-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 0.75rem 1.8rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .tefc-footer-menu-grid li a {
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .tefc-footer-menu-grid li a i {
        color: #b38e5d;
        font-size: 0.75rem;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .tefc-footer-menu-grid li a:hover {
        color: #ffffff;
        background: rgba(248, 184, 3, 0.12);
        transform: translateY(-2px);
    }
    .tefc-footer-menu-grid li a:hover i {
        color: #f8b803;
        transform: translateX(2px);
    }
    .tefc-footer-menu-grid li a.footer-sitemap-highlight {
        color: #f8b803;
        font-weight: 700;
        border: 1px solid rgba(248, 184, 3, 0.35);
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(248, 184, 3, 0.08);
    }
    .tefc-footer-menu-grid li a.footer-sitemap-highlight:hover {
        background: #f8b803;
        color: #2b0618;
    }
    .tefc-footer-menu-grid li a.footer-sitemap-highlight:hover i {
        color: #2b0618;
    }
    .tefc-footer-bottom {
        padding-top: 1.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.65);
    }
    .tefc-footer-legal {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .tefc-footer-legal a {
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        transition: color 0.2s;
    }
    .tefc-footer-legal a:hover {
        color: #f8b803;
    }
    @media (max-width: 768px) {
        .tefc-footer-top-block {
            gap: 1.5rem;
        }
        .tefc-footer-menu-grid {
            gap: 0.5rem 1rem;
        }
        .tefc-footer-menu-grid li a {
            font-size: 0.88rem;
        }
        .tefc-footer-bottom {
            flex-direction: column;
            text-align: center;
        }
        .tefc-footer-legal {
            justify-content: center;
        }
    }
    </style>

    <!-- Hidden Translate Bridge Element for Bhashini Web Engine -->
    <div id="google_translate_element" style="display: none;"></div>

    <script>
        // ── IMMEDIATE ACCESSIBILITY RESTORE ──
        (function() {
            var savedContrast = localStorage.getItem('tefc_contrast');
            if (savedContrast === 'high') {
                document.documentElement.classList.add('high-contrast');
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('high-contrast');
                    var btn = document.getElementById('btnContrastToggle');
                    if (btn) btn.classList.add('active');
                });
            } else if (savedContrast === 'dark') {
                document.documentElement.classList.add('dark-mode');
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('dark-mode');
                });
            } else if (savedContrast === 'grayscale') {
                document.documentElement.classList.add('grayscale-mode');
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('grayscale-mode');
                });
            }

            var savedFontSize = localStorage.getItem('tefc_font_size');
            if (savedFontSize) {
                var scale = savedFontSize === 'small' ? '88%' : (savedFontSize === 'large' ? '115%' : (savedFontSize === 'xlarge' ? '125%' : '100%'));
                document.documentElement.style.fontSize = scale;
            }

            if (localStorage.getItem('tefc_dyslexia') === 'true') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('dyslexic-font');
                });
            }
            if (localStorage.getItem('tefc_linespacing') === 'true') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('expanded-spacing');
                });
            }
            if (localStorage.getItem('tefc_highlight_links') === 'true') {
                document.addEventListener('DOMContentLoaded', function() {
                    document.body.classList.add('highlight-links');
                });
            }
        })();

        // ── BHASHINI WEB TRANSLATION BRIDGE INIT ──
        function googleTranslateElementInit() {
            if (window.google && window.google.translate) {
                new window.google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,hi,gu,ta,mr,pa,bn,te,kn,ml,or,as,ur',
                    autoDisplay: false
                }, 'google_translate_element');
            }
        }

        // Global Bhashini API Client
        window.Bhashini = {
            currentLang: localStorage.getItem('tefc_bhashini_lang') || 'en',
            async translate(text, targetLang = null) {
                try {
                    const response = await fetch('{{ route("api.bhashini.translate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            text: text,
                            target_lang: targetLang || this.currentLang,
                            source_lang: 'en'
                        })
                    });
                    return await response.json();
                } catch (e) {
                    console.error('Bhashini translate error:', e);
                    return { success: false, error: e.message };
                }
            }
        };

        function showBhashiniToast(msg) {
            var existingToast = document.getElementById('bhashiniToast');
            if (existingToast) existingToast.remove();

            var toast = document.createElement('div');
            toast.id = 'bhashiniToast';
            toast.innerHTML = '<span style="font-size:1.1rem;margin-right:6px;">🌐</span> ' + msg;
            toast.style.cssText = 'position:fixed;bottom:24px;left:24px;background:#36091e;color:#fff;padding:10px 18px;border-radius:30px;font-size:0.85rem;font-weight:600;box-shadow:0 6px 20px rgba(0,0,0,0.3);z-index:999999;border:1px solid #f8b803;display:flex;align-items:center;animation:bhashiniFadeIn 0.3s ease;';
            document.body.appendChild(toast);
            setTimeout(function() {
                toast.style.transition = 'opacity 0.4s ease';
                toast.style.opacity = '0';
                setTimeout(function() { toast.remove(); }, 400);
            }, 3000);
        }

        function setBhashiniLanguage(code, name) {
            localStorage.setItem('tefc_bhashini_lang', code);
            localStorage.setItem('tefc_bhashini_lang_name', name);
            window.Bhashini.currentLang = code;

            // Update UI pill and active item in topbar and drawer
            var pill = document.getElementById('bhashiniActiveLangBadge');
            if (pill) pill.textContent = name;

            document.querySelectorAll('.bhashini-lang-option').forEach(function(btn) {
                if (btn.getAttribute('data-code') === code) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            document.querySelectorAll('.btn-drawer-lang').forEach(function(btn) {
                if (btn.getAttribute('data-lang') === code) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Set translation cookies
            var hostname = window.location.hostname;
            if (code === 'en') {
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + hostname + ";";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + hostname + ";";
            } else {
                document.cookie = "googtrans=/en/" + code + "; path=/;";
                document.cookie = "googtrans=/en/" + code + "; path=/; domain=" + hostname + ";";
                document.cookie = "googtrans=/en/" + code + "; path=/; domain=." + hostname + ";";
            }

            // Trigger translation element if ready
            var select = document.querySelector('#google_translate_element select');
            if (select) {
                select.value = code;
                select.dispatchEvent(new Event('change'));
                showBhashiniToast('भाषा बदली गई / Language: ' + name);
            } else {
                showBhashiniToast('भाषिनी: अनुवाद जारी है / Translating to ' + name + '...');
                setTimeout(function() {
                    window.location.reload();
                }, 400);
            }
        }

        // ── HOMEPAGE EXPORTER REGISTRATION MODAL ──
        function openModal() {
            const modal = document.getElementById('regModal');
            if (modal) {
                modal.classList.add('open');
                modal.style.opacity = '1';
                modal.style.visibility = 'visible';
            }
        }

        function closeModal() {
            const modal = document.getElementById('regModal');
            if (modal) {
                modal.classList.remove('open');
                modal.style.opacity = '0';
                modal.style.visibility = 'hidden';
            }
            localStorage.setItem('tefc_exporter_popup_seen', 'true');
        }

        // ── LEFT ACCESSIBILITY DRAWER TOGGLES ──
        function openAccessibilityDrawer() {
            const drawer = document.getElementById('accessibilityDrawer');
            const backdrop = document.getElementById('accessibilityBackdrop');
            const trigger = document.getElementById('btnOpenAccessibility');
            if (drawer && backdrop) {
                drawer.classList.add('open');
                backdrop.classList.add('open');
                drawer.setAttribute('aria-hidden', 'false');
                if (trigger) trigger.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAccessibilityDrawer() {
            const drawer = document.getElementById('accessibilityDrawer');
            const backdrop = document.getElementById('accessibilityBackdrop');
            const trigger = document.getElementById('btnOpenAccessibility');
            if (drawer && backdrop) {
                drawer.classList.remove('open');
                backdrop.classList.remove('open');
                drawer.setAttribute('aria-hidden', 'true');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Drawer trigger & close bindings
            var btnOpenAcc = document.getElementById('btnOpenAccessibility');
            var btnCloseAcc = document.getElementById('btnCloseAccessibility');
            var accBackdrop = document.getElementById('accessibilityBackdrop');

            if (btnOpenAcc) btnOpenAcc.addEventListener('click', openAccessibilityDrawer);
            if (btnCloseAcc) btnCloseAcc.addEventListener('click', closeAccessibilityDrawer);
            if (accBackdrop) accBackdrop.addEventListener('click', closeAccessibilityDrawer);

            // Close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAccessibilityDrawer();
                    closeModal();
                }
            });

            // ── FONT SIZE CONTROLS (INLINE + DRAWER) ──
            var btnDec = document.getElementById('btnFontDec');
            var btnReset = document.getElementById('btnFontReset');
            var btnInc = document.getElementById('btnFontInc');
            var fontSizeDisplay = document.getElementById('fontSizeDisplay');

            var drawerFontSmall = document.getElementById('btnDrawerFontSmall');
            var drawerFontNormal = document.getElementById('btnDrawerFontNormal');
            var drawerFontLarge = document.getElementById('btnDrawerFontLarge');
            var drawerFontXLarge = document.getElementById('btnDrawerFontXLarge');

            function applyFontSize(size) {
                var buttons = [btnDec, btnReset, btnInc, drawerFontSmall, drawerFontNormal, drawerFontLarge, drawerFontXLarge];
                buttons.forEach(function(b) { if (b) b.classList.remove('active'); });

                var scale = '100%';
                var label = '100%';

                if (size === 'small') {
                    scale = '88%';
                    label = '88%';
                    if (btnDec) btnDec.classList.add('active');
                    if (drawerFontSmall) drawerFontSmall.classList.add('active');
                } else if (size === 'large') {
                    scale = '115%';
                    label = '115%';
                    if (btnInc) btnInc.classList.add('active');
                    if (drawerFontLarge) drawerFontLarge.classList.add('active');
                } else if (size === 'xlarge') {
                    scale = '125%';
                    label = '125%';
                    if (drawerFontXLarge) drawerFontXLarge.classList.add('active');
                } else {
                    scale = '100%';
                    label = '100%';
                    if (btnReset) btnReset.classList.add('active');
                    if (drawerFontNormal) drawerFontNormal.classList.add('active');
                }

                document.documentElement.style.fontSize = scale;
                if (fontSizeDisplay) fontSizeDisplay.textContent = label;
                localStorage.setItem('tefc_font_size', size);
            }

            if (btnDec) btnDec.addEventListener('click', function() { applyFontSize('small'); });
            if (btnReset) btnReset.addEventListener('click', function() { applyFontSize('normal'); });
            if (btnInc) btnInc.addEventListener('click', function() { applyFontSize('large'); });

            if (drawerFontSmall) drawerFontSmall.addEventListener('click', function() { applyFontSize('small'); });
            if (drawerFontNormal) drawerFontNormal.addEventListener('click', function() { applyFontSize('normal'); });
            if (drawerFontLarge) drawerFontLarge.addEventListener('click', function() { applyFontSize('large'); });
            if (drawerFontXLarge) drawerFontXLarge.addEventListener('click', function() { applyFontSize('xlarge'); });

            var savedSize = localStorage.getItem('tefc_font_size');
            if (savedSize) {
                applyFontSize(savedSize);
            }

            // ── THEME / CONTRAST CONTROLS ──
            var btnContrast = document.getElementById('btnContrastToggle');
            var btnThemeDefault = document.getElementById('btnThemeDefault');
            var btnThemeHighContrast = document.getElementById('btnThemeHighContrast');
            var btnThemeDarkMode = document.getElementById('btnThemeDarkMode');
            var btnThemeGrayscale = document.getElementById('btnThemeGrayscale');

            function applyTheme(theme) {
                document.body.classList.remove('high-contrast', 'dark-mode', 'grayscale-mode');
                document.documentElement.classList.remove('high-contrast', 'dark-mode', 'grayscale-mode');
                [btnThemeDefault, btnThemeHighContrast, btnThemeDarkMode, btnThemeGrayscale].forEach(function(b) {
                    if (b) b.classList.remove('active');
                });
                if (btnContrast) btnContrast.classList.remove('active');

                if (theme === 'high') {
                    document.body.classList.add('high-contrast');
                    document.documentElement.classList.add('high-contrast');
                    if (btnThemeHighContrast) btnThemeHighContrast.classList.add('active');
                    if (btnContrast) btnContrast.classList.add('active');
                    localStorage.setItem('tefc_contrast', 'high');
                } else if (theme === 'dark') {
                    document.body.classList.add('dark-mode');
                    document.documentElement.classList.add('dark-mode');
                    if (btnThemeDarkMode) btnThemeDarkMode.classList.add('active');
                    localStorage.setItem('tefc_contrast', 'dark');
                } else if (theme === 'grayscale') {
                    document.body.classList.add('grayscale-mode');
                    document.documentElement.classList.add('grayscale-mode');
                    if (btnThemeGrayscale) btnThemeGrayscale.classList.add('active');
                    localStorage.setItem('tefc_contrast', 'grayscale');
                } else {
                    if (btnThemeDefault) btnThemeDefault.classList.add('active');
                    localStorage.setItem('tefc_contrast', 'normal');
                }
            }

            if (btnContrast) {
                btnContrast.addEventListener('click', function() {
                    var isCurrentlyHigh = document.body.classList.contains('high-contrast');
                    applyTheme(isCurrentlyHigh ? 'normal' : 'high');
                });
            }
            if (btnThemeDefault) btnThemeDefault.addEventListener('click', function() { applyTheme('normal'); });
            if (btnThemeHighContrast) btnThemeHighContrast.addEventListener('click', function() { applyTheme('high'); });
            if (btnThemeDarkMode) btnThemeDarkMode.addEventListener('click', function() { applyTheme('dark'); });
            if (btnThemeGrayscale) btnThemeGrayscale.addEventListener('click', function() { applyTheme('grayscale'); });

            var savedContrast = localStorage.getItem('tefc_contrast');
            if (savedContrast && savedContrast !== 'normal') {
                applyTheme(savedContrast);
            }

            // ── READING ASSISTANCE TOGGLES ──
            var btnToggleDyslexia = document.getElementById('btnToggleDyslexia');
            var btnToggleLineSpacing = document.getElementById('btnToggleLineSpacing');
            var btnToggleHighlightLinks = document.getElementById('btnToggleHighlightLinks');

            function setupToggle(btn, className, storageKey) {
                if (!btn) return;
                var isActive = localStorage.getItem(storageKey) === 'true';
                if (isActive) {
                    document.body.classList.add(className);
                    btn.classList.add('active');
                    var icon = btn.querySelector('.toggle-indicator i');
                    if (icon) icon.className = 'fas fa-toggle-on';
                }
                btn.addEventListener('click', function() {
                    var active = document.body.classList.toggle(className);
                    btn.classList.toggle('active', active);
                    var icon = btn.querySelector('.toggle-indicator i');
                    if (icon) icon.className = active ? 'fas fa-toggle-on' : 'fas fa-toggle-off';
                    localStorage.setItem(storageKey, active ? 'true' : 'false');
                });
            }

            setupToggle(btnToggleDyslexia, 'dyslexic-font', 'tefc_dyslexia');
            setupToggle(btnToggleLineSpacing, 'expanded-spacing', 'tefc_linespacing');
            setupToggle(btnToggleHighlightLinks, 'highlight-links', 'tefc_highlight_links');

            // ── RESET ALL SETTINGS BUTTON ──
            var btnResetAll = document.getElementById('btnResetAllAccessibility');
            if (btnResetAll) {
                btnResetAll.addEventListener('click', function() {
                    localStorage.removeItem('tefc_font_size');
                    localStorage.removeItem('tefc_contrast');
                    localStorage.removeItem('tefc_dyslexia');
                    localStorage.removeItem('tefc_linespacing');
                    localStorage.removeItem('tefc_highlight_links');

                    applyFontSize('normal');
                    applyTheme('normal');

                    document.body.classList.remove('dyslexic-font', 'expanded-spacing', 'highlight-links');
                    [btnToggleDyslexia, btnToggleLineSpacing, btnToggleHighlightLinks].forEach(function(b) {
                        if (b) {
                            b.classList.remove('active');
                            var icon = b.querySelector('.toggle-indicator i');
                            if (icon) icon.className = 'fas fa-toggle-off';
                        }
                    });

                    showBhashiniToast('Accessibility settings reset to default.');
                });
            }

            // ── BHASHINI DROPDOWN & SEARCH (TOPBAR & DRAWER) ──
            var bhashiniContainer = document.getElementById('bhashiniContainer');
            var bhashiniDropdownBtn = document.getElementById('bhashiniDropdownBtn');
            var bhashiniMenu = document.getElementById('bhashiniDropdownMenu');
            var bhashiniSearch = document.getElementById('bhashiniLangSearch');

            if (bhashiniDropdownBtn && bhashiniMenu) {
                bhashiniDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isHidden = bhashiniMenu.style.display === 'none';
                    bhashiniMenu.style.display = isHidden ? 'block' : 'none';
                    if (bhashiniContainer) bhashiniContainer.classList.toggle('open', isHidden);
                    bhashiniDropdownBtn.setAttribute('aria-expanded', isHidden);
                    if (isHidden && bhashiniSearch) {
                        setTimeout(function() { bhashiniSearch.focus(); }, 100);
                    }
                });

                document.addEventListener('click', function(e) {
                    if (bhashiniContainer && !bhashiniContainer.contains(e.target)) {
                        bhashiniMenu.style.display = 'none';
                        bhashiniContainer.classList.remove('open');
                        bhashiniDropdownBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            if (bhashiniSearch) {
                bhashiniSearch.addEventListener('input', function() {
                    var term = this.value.toLowerCase().trim();
                    var options = document.querySelectorAll('.bhashini-lang-option');
                    options.forEach(function(opt) {
                        var text = opt.textContent.toLowerCase();
                        var code = (opt.getAttribute('data-code') || '').toLowerCase();
                        if (!term || text.includes(term) || code.includes(term)) {
                            opt.style.display = 'flex';
                        } else {
                            opt.style.display = 'none';
                        }
                    });
                });
            }

            document.querySelectorAll('.bhashini-lang-option').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var code = this.getAttribute('data-code');
                    var name = this.getAttribute('data-name');
                    if (bhashiniMenu) {
                        bhashiniMenu.style.display = 'none';
                        if (bhashiniContainer) bhashiniContainer.classList.remove('open');
                        if (bhashiniDropdownBtn) bhashiniDropdownBtn.setAttribute('aria-expanded', 'false');
                    }
                    setBhashiniLanguage(code, name);
                });
            });

            document.querySelectorAll('.btn-drawer-lang').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var code = this.getAttribute('data-lang');
                    var name = this.getAttribute('data-name');
                    setBhashiniLanguage(code, name);
                });
            });

            // Restore saved Bhashini language UI state
            var savedLang = localStorage.getItem('tefc_bhashini_lang');
            var savedLangName = localStorage.getItem('tefc_bhashini_lang_name');
            if (savedLang && savedLangName) {
                var pill = document.getElementById('bhashiniActiveLangBadge');
                if (pill) pill.textContent = savedLangName;
                document.querySelectorAll('.bhashini-lang-option').forEach(function(btn) {
                    if (btn.getAttribute('data-code') === savedLang) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                document.querySelectorAll('.btn-drawer-lang').forEach(function(btn) {
                    if (btn.getAttribute('data-lang') === savedLang) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }

            // ── LOGIN DROPDOWN TOGGLE ──
            var navLoginWrap = document.getElementById('navLoginDropdownWrap');
            var navLoginBtn = document.getElementById('navLoginDropdownBtn');
            var navLoginMenu = document.getElementById('navLoginMenu');

            if (navLoginBtn && navLoginMenu) {
                navLoginBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isClosed = navLoginMenu.style.display === 'none';
                    navLoginMenu.style.display = isClosed ? 'block' : 'none';
                    if (navLoginWrap) navLoginWrap.classList.toggle('open', isClosed);
                    navLoginBtn.setAttribute('aria-expanded', isClosed);
                });

                document.addEventListener('click', function(e) {
                    if (navLoginWrap && !navLoginWrap.contains(e.target)) {
                        navLoginMenu.style.display = 'none';
                        navLoginWrap.classList.remove('open');
                        navLoginBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            var userAuthWrap = document.getElementById('userAuthDropdownWrap');
            var userAuthBtn = document.getElementById('userAuthDropdownBtn');
            var userAuthMenu = document.getElementById('userAuthMenu');

            if (userAuthBtn && userAuthMenu) {
                userAuthBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isClosed = userAuthMenu.style.display === 'none';
                    userAuthMenu.style.display = isClosed ? 'block' : 'none';
                    if (userAuthWrap) userAuthWrap.classList.toggle('open', isClosed);
                    userAuthBtn.setAttribute('aria-expanded', isClosed);
                });

                document.addEventListener('click', function(e) {
                    if (userAuthWrap && !userAuthWrap.contains(e.target)) {
                        userAuthMenu.style.display = 'none';
                        userAuthWrap.classList.remove('open');
                        userAuthBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Hamburger Menu
            const ham = document.getElementById('navHam');
            const links = document.getElementById('navLinks');
            if (ham && links) {
                ham.addEventListener('click', function () {
                    links.classList.toggle('open');
                });
            }

            // ── POPUP MODAL: ONLY ON FIRST TIME LOAD ──
            const path = window.location.pathname;
            const isHome = path === '/tefc/public/' || path === '/' || path.endsWith('/public/') || path.endsWith('/p/home') || path.endsWith('/tefc/public') || path.endsWith('/public');
            if (isHome) {
                if (!localStorage.getItem('tefc_exporter_popup_seen')) {
                    setTimeout(function() {
                        openModal();
                        localStorage.setItem('tefc_exporter_popup_seen', 'true');
                    }, 2000);
                }
            }
        });
    </script>
    <!-- Background Google / Bhashini Web Translation Engine Loader -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
    @yield('scripts')
    @include('components.chatbot')
</body>
</html>
