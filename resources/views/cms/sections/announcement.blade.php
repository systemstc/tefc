@php
    $badge = $data['badge'] ?? 'Latest Announcements';
    $viewAllText = $data['view_all_text'] ?? 'All Updates';
    $viewAllUrl = $data['view_all_url'] ?? url('/p/news');
    $items = $data['items'] ?? [];

    if (empty($items)) {
        $items = [
            [
                'category' => 'RoDTEP',
                'text' => 'Enhanced RoDTEP & RoSCTL rates notified for textile exporters — single-window advisory active across all 6 TEFC clusters.',
                'url' => url('/p/fta-benefits'),
                'is_new' => true,
            ],
            [
                'category' => 'Registration',
                'text' => 'Single-Window Exporter Registration is open for textile MSMEs to access dedicated nodal officer support and export facilitation.',
                'url' => url('/register'),
                'is_new' => true,
            ],
            [
                'category' => 'FTA Alert',
                'text' => 'India-EFTA and UAE-CEPA preferential duty guidelines published — access zero-duty tariff schedules for spun yarns and technical fabrics.',
                'url' => url('/p/fta-benefits'),
                'is_new' => false,
            ],
            [
                'category' => 'Outreach',
                'text' => 'Special export facilitation camps ongoing across Surat, Karur, Ichalkaranji, Jaipur, Varanasi & Ludhiana centres.',
                'url' => url('/p/clusters'),
                'is_new' => false,
            ],
        ];
    }
@endphp

<!-- TEFC Announcement Strip -->
<div class="tefc-announcement-strip" role="region" aria-label="Official Announcements Ticker">
    <div class="container announcement-inner-container">
        <!-- Announcement Left Badge -->
        <div class="announcement-badge-wrap">
            <span class="live-pulse-indicator">
                <span class="pulse-ring"></span>
                <span class="pulse-core"></span>
            </span>
            <span class="announcement-badge-icon">
                <i class="fas fa-bullhorn"></i>
            </span>
            <span class="announcement-badge-text">{{ $badge }}</span>
        </div>

        <!-- Announcement Ticker Scroller -->
        <div class="announcement-ticker-viewport">
            <div class="announcement-ticker-track" id="announcementTrack">
                @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                    @foreach ($items as $item)
                        @php
                            $cat = $item['category'] ?? 'Notice';
                            $text = $item['text'] ?? '';
                            $url = $item['url'] ?? '';
                            $isNew = !empty($item['is_new']);
                        @endphp
                        <div class="announcement-ticker-item">
                            <span class="announcement-cat-pill cat-{{ strtolower(preg_replace('/[^a-z0-9]/', '', $cat)) }}">{{ $cat }}</span>
                            @if($isNew)
                                <span class="announcement-new-badge">NEW</span>
                            @endif
                            @if(!empty($url))
                                <a href="{{ $url }}" class="announcement-text-link">
                                    {{ $text }}
                                </a>
                            @else
                                <span class="announcement-text">{{ $text }}</span>
                            @endif
                            <span class="announcement-divider" aria-hidden="true">•</span>
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>

        <!-- Announcement Right Actions -->
        <div class="announcement-actions">
            <button type="button" class="announcement-btn-ctrl" id="btnAnnouncementToggle" aria-label="Pause announcement ticker" title="Pause / Play Marquee">
                <i class="fas fa-pause" id="announcementCtrlIcon"></i>
            </button>
            @if(!empty($viewAllUrl))
                <a href="{{ $viewAllUrl }}" class="announcement-view-all">
                    <span>{{ $viewAllText }}</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<style>
/* ── ANNOUNCEMENT STRIP STYLING ── */
.tefc-announcement-strip {
    background: linear-gradient(90deg, #420824 0%, #580d32 40%, #6c1340 100%);
    border-top: 1px solid rgba(223, 203, 181, 0.25);
    border-bottom: 2px solid #b38e5d;
    box-shadow: 0 4px 18px rgba(35, 10, 25, 0.18);
    position: relative;
    z-index: 5;
    padding: 0.55rem 0;
    color: #ffffff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.announcement-inner-container {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    max-width: 1582px;
}

/* Badge on Left */
.announcement-badge-wrap {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    background: linear-gradient(135deg, #b38e5d 0%, #8c6738 100%);
    color: #ffffff;
    padding: 0.38rem 0.95rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.22);
    border: 1px solid rgba(255, 255, 255, 0.25);
    flex-shrink: 0;
}

.announcement-badge-icon {
    font-size: 0.85rem;
    color: #fff;
    transform: rotate(-10deg);
    display: inline-block;
}

.live-pulse-indicator {
    position: relative;
    width: 8px;
    height: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.pulse-core {
    width: 7px;
    height: 7px;
    background-color: #ffffff;
    border-radius: 50%;
    display: inline-block;
}

.pulse-ring {
    position: absolute;
    width: 14px;
    height: 14px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    animation: liveRingPulse 1.8s infinite cubic-bezier(0.25, 1, 0.5, 1);
    opacity: 0.75;
}

@keyframes liveRingPulse {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
}

/* Marquee Viewport & Track */
.announcement-ticker-viewport {
    flex: 1;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(to right, transparent, black 25px, black calc(100% - 25px), transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 25px, black calc(100% - 25px), transparent);
}

.announcement-ticker-track {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    white-space: nowrap;
    will-change: transform;
    animation: announcementMarquee 42s linear infinite;
}

.announcement-ticker-track:hover,
.announcement-ticker-track.is-paused {
    animation-play-state: paused !important;
}

@keyframes announcementMarquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Individual Announcement Item */
.announcement-ticker-item {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.88rem;
    font-weight: 500;
    color: #ffffff;
    flex-shrink: 0;
}

.announcement-cat-pill {
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    padding: 0.18rem 0.55rem;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(223, 203, 181, 0.4);
    color: #dfcbb5;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.announcement-new-badge {
    background: #e63946;
    color: #ffffff;
    font-size: 0.62rem;
    font-weight: 800;
    padding: 0.15rem 0.45rem;
    border-radius: 3px;
    letter-spacing: 0.05em;
    animation: newBadgeFlash 2.5s infinite ease-in-out;
}

@keyframes newBadgeFlash {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.announcement-text {
    color: #f7f3ed;
    line-height: 1.4;
}

.announcement-text-link {
    color: #ffffff;
    text-decoration: none;
    line-height: 1.4;
    transition: color 0.2s ease, text-decoration 0.2s ease;
}

.announcement-text-link:hover {
    color: #f8c857;
    text-decoration: underline;
}

.announcement-divider {
    color: #b38e5d;
    font-size: 0.95rem;
    opacity: 0.8;
    margin-left: 0.5rem;
}

/* Right Side Actions */
.announcement-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.announcement-btn-ctrl {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.72rem;
    transition: all 0.2s ease;
}

.announcement-btn-ctrl:hover {
    background: #b38e5d;
    border-color: #b38e5d;
    transform: scale(1.08);
}

.announcement-view-all {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: #dfcbb5;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(223, 203, 181, 0.28);
    transition: all 0.2s ease;
    white-space: nowrap;
}

.announcement-view-all:hover {
    background: #b38e5d;
    color: #ffffff;
    border-color: #b38e5d;
}

.announcement-view-all i {
    font-size: 0.7rem;
    transition: transform 0.2s ease;
}

.announcement-view-all:hover i {
    transform: translateX(3px);
}

/* Responsive Rules */
@media (max-width: 991px) {
    .announcement-inner-container {
        gap: 0.8rem;
    }
    .announcement-ticker-item {
        font-size: 0.82rem;
    }
}

@media (max-width: 768px) {
    .announcement-badge-wrap {
        padding: 0.28rem 0.65rem;
        font-size: 0.7rem;
    }
    .announcement-view-all span {
        display: none;
    }
    .announcement-view-all {
        padding: 0.3rem 0.5rem;
    }
    .announcement-ticker-track {
        animation-duration: 32s;
    }
}

@media (prefers-reduced-motion: reduce) {
    .announcement-ticker-track {
        animation: none;
        overflow-x: auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('announcementTrack');
    const btnToggle = document.getElementById('btnAnnouncementToggle');
    const icon = document.getElementById('announcementCtrlIcon');
    if (!track || !btnToggle) return;

    let isPaused = false;

    btnToggle.addEventListener('click', function () {
        isPaused = !isPaused;
        if (isPaused) {
            track.classList.add('is-paused');
            if (icon) {
                icon.className = 'fas fa-play';
            }
            btnToggle.setAttribute('aria-label', 'Resume announcement ticker');
            btnToggle.title = 'Resume scrolling';
        } else {
            track.classList.remove('is-paused');
            if (icon) {
                icon.className = 'fas fa-pause';
            }
            btnToggle.setAttribute('aria-label', 'Pause announcement ticker');
            btnToggle.title = 'Pause scrolling';
        }
    });
});
</script>
