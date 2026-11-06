<section class="section media-center-showcase" id="gallery-section">
  <div class="container" style="max-width: 1540px;">
    <!-- Section Header -->
    <div class="media-center-head text-center mb-5">
      <h2 class="media-center-title">{{ $title ?? 'Media Center' }}</h2>
      <div class="media-center-gold-line"></div>
      <p class="media-center-subtitle">
        {{ $data['subtitle'] ?? 'Explore the latest videos and visual updates from the Textiles Export Facilitation Centre.' }}
      </p>
    </div>

    @php
      $videos = $data['videos'] ?? [];
      $images = $data['items'] ?? [];

      // Fallback default videos if none configured
      if (empty($videos)) {
        $videos = [
          [
            'title' => 'TEFC introduction video thumbnail',
            'thumbnail' => 'images/slider/1783571586_841_Hero_Banner_2.png',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
          ],
          [
            'title' => 'IEC registration guide video thumbnail',
            'thumbnail' => 'images/lead1.png',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
          ],
          [
            'title' => 'Electronic Certificate of Origin (e-CoO) Demonstration',
            'thumbnail' => 'images/lead2.png',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
          ],
          [
            'title' => 'Indian Textile Clusters Export Journey',
            'thumbnail' => 'images/karur.png',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
          ]
        ];
      }

      // Fallback default images if none configured
      if (empty($images)) {
        $images = [
          [
            'title' => 'Textile activity in the Karur cluster',
            'image' => 'images/karur_card.png',
            'category' => 'Karur Cluster'
          ],
          [
            'title' => 'Textile activity in the Varanasi cluster',
            'image' => 'images/varanasi_card.png',
            'category' => 'Varanasi Cluster'
          ],
          [
            'title' => 'Surat Man-Made Fabric & Jacquard Production',
            'image' => 'images/surat_card.png',
            'category' => 'Surat Cluster'
          ],
          [
            'title' => 'Ichalkaranji Powerloom & Grey Cloth Facilities',
            'image' => 'images/ichalkaranji_card.png',
            'category' => 'Ichalkaranji Cluster'
          ]
        ];
      }
    @endphp

    <!-- 2-Column Side-by-Side Cards Container -->
    <div class="media-center-grid">
      <!-- ── CARD 1: LATEST VIDEOS ── -->
      <div class="media-card-box">
        <div class="media-card-header">
          <h3 class="media-card-heading">Latest Videos</h3>
          <span class="media-card-hint">Scroll to explore</span>
        </div>

        <div class="media-slider-wrap">
          <div class="media-horizontal-slider" id="videosSlider">
            @foreach($videos as $vIdx => $vid)
              @php
                $vThumb = !empty($vid['thumbnail']) ? asset(ltrim($vid['thumbnail'], '/')) : asset('images/lead1.png');
                $vTitle = $vid['title'] ?? 'TEFC Training Video';
                $vUrl = $vid['video_url'] ?? $vid['url'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ';
              @endphp
              <div class="media-slide-item video-slide" onclick="openHomeVideoModal('{{ addslashes($vTitle) }}', '{{ addslashes($vUrl) }}')">
                <div class="media-thumb-container">
                  <img src="{{ $vThumb }}" alt="{{ $vTitle }}" loading="lazy" onerror="this.src='{{ asset('images/lead1.png') }}'">
                  <div class="media-title-overlay">
                    <span>{{ $vTitle }}</span>
                  </div>
                  <div class="media-play-circle" title="Play Video">
                    <i class="fas fa-play"></i>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Scroll Indicator & Arrows Navigation -->
        <div class="media-card-footer">
          <div class="media-track-bar">
            <div class="media-track-fill" id="videosTrackFill"></div>
          </div>
          <div class="media-nav-buttons">
            <button type="button" class="media-arrow-btn" onclick="scrollMediaSlider('videosSlider', -340)" aria-label="Previous Videos">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="media-arrow-btn" onclick="scrollMediaSlider('videosSlider', 340)" aria-label="Next Videos">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- ── CARD 2: LATEST IMAGES ── -->
      <div class="media-card-box">
        <div class="media-card-header">
          <h3 class="media-card-heading">Latest Images</h3>
          <span class="media-card-hint">Scroll to explore</span>
        </div>

        <div class="media-slider-wrap">
          <div class="media-horizontal-slider" id="imagesSlider">
            @foreach($images as $imgIdx => $img)
              @php
                $rawImg = $img['image'] ?? $img['thumb'] ?? 'images/gallery/gallery_1.jpg';
                $imgSrc = asset(ltrim($rawImg, '/'));
                $imgTitle = $img['title'] ?? $img['category'] ?? 'Textile activity in cluster';
              @endphp
              <div class="media-slide-item image-slide" onclick="openHomeImageModal('{{ addslashes($imgSrc) }}', '{{ addslashes($imgTitle) }}')">
                <div class="media-thumb-container">
                  <img src="{{ $imgSrc }}" alt="{{ $imgTitle }}" loading="lazy" onerror="this.src='{{ asset('images/gallery/gallery_1.jpg') }}'">
                  <div class="media-title-overlay">
                    <span>{{ $imgTitle }}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Scroll Indicator & Arrows Navigation -->
        <div class="media-card-footer">
          <div class="media-track-bar">
            <div class="media-track-fill" id="imagesTrackFill"></div>
          </div>
          <div class="media-nav-buttons">
            <button type="button" class="media-arrow-btn" onclick="scrollMediaSlider('imagesSlider', -340)" aria-label="Previous Images">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="media-arrow-btn" onclick="scrollMediaSlider('imagesSlider', 340)" aria-label="Next Images">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Center Button: View All Media -->
    <div class="text-center mt-5">
      <a href="{{ url('/p/media') }}" class="btn-view-all-media">
        View All Media &rarr;
      </a>
    </div>
  </div>

  <!-- Video Modal Popup -->
  <div id="homeVideoModal" class="home-media-modal" onclick="closeHomeVideoModal(event)">
    <div class="home-modal-dialog">
      <div class="home-modal-content">
        <div class="home-modal-header">
          <h4 id="homeVideoModalTitle" class="m-0 text-truncate">Watch Video</h4>
          <button type="button" class="home-modal-close" onclick="closeHomeVideoModal(null)">&times;</button>
        </div>
        <div class="home-modal-body">
          <iframe id="homeVideoIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Image Lightbox Popup -->
  <div id="homeImageModal" class="home-media-modal" onclick="closeHomeImageModal(event)">
    <div class="home-modal-dialog">
      <div class="home-modal-content image-content">
        <button type="button" class="home-modal-close floating" onclick="closeHomeImageModal(null)">&times;</button>
        <img id="homeImageModalImg" src="" alt="Full Preview">
        <div id="homeImageModalTitle" class="home-image-caption"></div>
      </div>
    </div>
  </div>
</section>

<style>
/* ── MEDIA CENTER / OUR GALLERY SHOWCASE (EXACT MATCH TO REFERENCE) ── */
.media-center-showcase {
  background: #ffffff;
  padding: 4.5rem 0;
  border-bottom: 1px solid #f0edf0;
}
.media-center-title {
  color: #6a153d;
  font-size: 2.25rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}
.media-center-gold-line {
  width: 48px;
  height: 3px;
  background: #f8b803;
  margin: 0.6rem auto 1rem;
  border-radius: 2px;
}
.media-center-subtitle {
  color: #555555;
  font-size: 1.05rem;
  max-width: 720px;
  margin: 0 auto;
}

/* 2-Column Grid */
.media-center-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

/* Outer Card Container */
.media-card-box {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-top: 4px solid #6a153d;
  border-radius: 12px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.media-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.1rem 1.4rem;
  border-bottom: 1px solid #f1f5f9;
  background: #ffffff;
}
.media-card-heading {
  color: #6a153d;
  font-size: 1.18rem;
  font-weight: 800;
  margin: 0;
}
.media-card-hint {
  color: #888888;
  font-size: 0.8rem;
  font-weight: 500;
}

/* Slider Track */
.media-slider-wrap {
  padding: 1.25rem 1.4rem;
  position: relative;
  background: #fafbfc;
}
.media-horizontal-slider {
  display: flex;
  gap: 1.15rem;
  overflow-x: auto;
  scroll-behavior: smooth;
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE/Edge */
  padding-bottom: 4px;
}
.media-horizontal-slider::-webkit-scrollbar {
  display: none; /* Chrome/Safari */
}

/* Slide Cards */
.media-slide-item {
  flex: 0 0 calc(50% - 0.6rem);
  min-width: 250px;
  max-width: 360px;
  cursor: pointer;
}
.media-thumb-container {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 10;
  background: #eaedf2;
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.media-slide-item:hover .media-thumb-container {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(106, 21, 61, 0.12);
}
.media-thumb-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Overlay Title */
.media-title-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  padding: 8px 12px;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.6) 0%, transparent 100%);
  color: #ffffff;
  font-size: 0.82rem;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  pointer-events: none;
}

/* Video Play Button */
.media-play-circle {
  position: absolute;
  right: 14px;
  bottom: 14px;
  width: 38px;
  height: 38px;
  background: #6a153d;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
  padding-left: 2px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35);
  transition: transform 0.2s ease, background 0.2s ease;
}
.media-slide-item:hover .media-play-circle {
  transform: scale(1.12);
  background: #871c4f;
}

/* Card Footer: Track & Navigation Buttons */
.media-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.2rem;
  padding: 0.85rem 1.4rem 1.1rem;
  background: #ffffff;
  border-top: 1px solid #f1f5f9;
}
.media-track-bar {
  flex-grow: 1;
  height: 5px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
  position: relative;
}
.media-track-fill {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 30%;
  background: #6a153d;
  border-radius: 4px;
  transition: width 0.2s ease, left 0.2s ease;
}
.media-nav-buttons {
  display: flex;
  gap: 6px;
  flex-shrink: 0;
}
.media-arrow-btn {
  width: 32px;
  height: 32px;
  border: 1px solid #cbd5e1;
  background: #ffffff;
  color: #475569;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  cursor: pointer;
  transition: all 0.2s ease;
}
.media-arrow-btn:hover {
  border-color: #6a153d;
  background: #fdf2f6;
  color: #6a153d;
}

/* Center Bottom Button */
.btn-view-all-media {
  background: #6a153d;
  color: #ffffff;
  font-weight: 700;
  font-size: 0.98rem;
  padding: 13px 32px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  box-shadow: 0 4px 14px rgba(106, 21, 61, 0.25);
  transition: all 0.25s ease;
}
.btn-view-all-media:hover {
  background: #4f0e2d;
  color: #ffffff;
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(106, 21, 61, 0.35);
}

/* ── MODALS (VIDEO & IMAGE) ── */
.home-media-modal {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 10000;
  background: rgba(0, 0, 0, 0.85);
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  backdrop-filter: blur(4px);
}
.home-media-modal.open {
  display: flex;
}
.home-modal-dialog {
  width: 100%;
  max-width: 860px;
}
.home-modal-content {
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
  position: relative;
}
.home-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 20px;
  border-bottom: 1px solid #eee;
  background: #faf7f9;
}
.home-modal-header h4 {
  font-size: 1.05rem;
  font-weight: 700;
  color: #6a153d;
}
.home-modal-close {
  background: none;
  border: none;
  font-size: 1.8rem;
  line-height: 1;
  color: #888;
  cursor: pointer;
  padding: 0 4px;
}
.home-modal-close:hover {
  color: #000;
}
.home-modal-close.floating {
  position: absolute;
  top: 10px;
  right: 14px;
  z-index: 10;
  color: #ffffff;
  text-shadow: 0 2px 4px rgba(0,0,0,0.8);
}
.home-modal-body {
  position: relative;
  aspect-ratio: 16 / 9;
  background: #000;
}
.home-modal-body iframe {
  width: 100%;
  height: 100%;
  border: none;
}
.home-modal-content.image-content {
  background: transparent;
  box-shadow: none;
  text-align: center;
}
.home-modal-content.image-content img {
  max-height: 80vh;
  max-width: 100%;
  border-radius: 10px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
}
.home-image-caption {
  color: #ffffff;
  font-size: 0.95rem;
  font-weight: 600;
  margin-top: 12px;
  text-align: center;
}

/* Responsive */
@media (max-width: 991px) {
  .media-center-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  .media-slide-item {
    flex: 0 0 260px;
  }
}
@media (max-width: 600px) {
  .media-center-title {
    font-size: 1.8rem;
  }
  .media-slide-item {
    flex: 0 0 230px;
  }
  .btn-view-all-media {
    width: 100%;
    justify-content: center;
  }
}
</style>

<script>
// Scroll slider horizontally
function scrollMediaSlider(sliderId, distance) {
  const slider = document.getElementById(sliderId);
  if (slider) {
    slider.scrollBy({ left: distance, behavior: 'smooth' });
  }
}

// Update scrollbar fill on slider scroll
function updateSliderProgress(sliderId, fillId) {
  const slider = document.getElementById(sliderId);
  const fill = document.getElementById(fillId);
  if (!slider || !fill) return;

  const maxScroll = slider.scrollWidth - slider.clientWidth;
  if (maxScroll <= 0) {
    fill.style.width = '100%';
    fill.style.left = '0%';
    return;
  }

  const scrollPercent = (slider.scrollLeft / maxScroll) * 100;
  const barWidth = 35; // percentage width of scroll thumb
  const maxLeft = 100 - barWidth;
  const leftPos = (scrollPercent / 100) * maxLeft;

  fill.style.width = barWidth + '%';
  fill.style.left = leftPos + '%';
}

document.addEventListener('DOMContentLoaded', function () {
  const vSlider = document.getElementById('videosSlider');
  const iSlider = document.getElementById('imagesSlider');

  if (vSlider) {
    vSlider.addEventListener('scroll', () => updateSliderProgress('videosSlider', 'videosTrackFill'));
    updateSliderProgress('videosSlider', 'videosTrackFill');
  }

  if (iSlider) {
    iSlider.addEventListener('scroll', () => updateSliderProgress('imagesSlider', 'imagesTrackFill'));
    updateSliderProgress('imagesSlider', 'imagesTrackFill');
  }
});

// Video Modal
function openHomeVideoModal(title, url) {
  const modal = document.getElementById('homeVideoModal');
  const titleEl = document.getElementById('homeVideoModalTitle');
  const iframe = document.getElementById('homeVideoIframe');

  if (titleEl) titleEl.textContent = title || 'TEFC Video';

  let embedUrl = url;
  if (url && (url.includes('youtube.com/watch') || url.includes('youtu.be/'))) {
    let videoId = '';
    if (url.includes('watch?v=')) {
      videoId = url.split('watch?v=')[1].split('&')[0];
    } else if (url.includes('youtu.be/')) {
      videoId = url.split('youtu.be/')[1].split('?')[0];
    }
    if (videoId) embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
  }

  if (iframe) iframe.src = embedUrl;
  if (modal) modal.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeHomeVideoModal(e) {
  if (e && e.target && e.target.id !== 'homeVideoModal' && !e.target.classList.contains('home-modal-close')) {
    return;
  }
  const modal = document.getElementById('homeVideoModal');
  const iframe = document.getElementById('homeVideoIframe');
  if (iframe) iframe.src = '';
  if (modal) modal.classList.remove('open');
  document.body.style.overflow = '';
}

// Image Modal
function openHomeImageModal(src, title) {
  const modal = document.getElementById('homeImageModal');
  const img = document.getElementById('homeImageModalImg');
  const cap = document.getElementById('homeImageModalTitle');

  if (img) img.src = src;
  if (cap) cap.textContent = title || '';
  if (modal) modal.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeHomeImageModal(e) {
  if (e && e.target && e.target.id !== 'homeImageModal' && !e.target.classList.contains('home-modal-close')) {
    return;
  }
  const modal = document.getElementById('homeImageModal');
  if (modal) modal.classList.remove('open');
  document.body.style.overflow = '';
}
</script>
