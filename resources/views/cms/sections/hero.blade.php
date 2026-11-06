@php
  $images = $data['images'] ?? [$data['background_image'] ?? 'images/hero_map.jpg'];
  $images = is_array($images) ? array_values(array_filter($images)) : [$images];
  if (empty($images)) {
      $images = ['images/hero_map.jpg'];
  }
  $hasTitle = !empty(trim(strip_tags($title ?? '')));
  $hasSubtitle = !empty(trim($data['subtitle'] ?? ''));
  $hasPrimaryBtn = !empty(trim($data['primary_btn_text'] ?? ''));
  $hasSecondaryBtn = !empty(trim($data['secondary_btn_text'] ?? ''));
  $hasButtons = $hasPrimaryBtn || $hasSecondaryBtn;
  $hasStats = !empty($data['stats']) && is_array($data['stats']) && count($data['stats']) > 0;
  $hasTextOverlay = $hasTitle || $hasSubtitle || $hasButtons || $hasStats;
  $bannerHeight = !empty($data['banner_height']) ? intval($data['banner_height']) : 600;
@endphp

<section class="hero-slider-wrapper" style="--hero-desktop-height: {{ $bannerHeight }}px;">
  <div class="hero-slider">
    @foreach($images as $index => $image)
      <div class="slide {{ $index === 0 ? 'active' : '' }}" style="{{ $hasTextOverlay ? "background: linear-gradient(rgba(10, 2, 6, 0.7), rgba(108, 21, 63, 0.6)), url('" . asset($image) . "') no-repeat center center / cover;" : "background: url('" . asset($image) . "') no-repeat center center / cover;" }}">
        @if($hasTextOverlay)
          <div class="container hero-container">
            <div class="hero-left">
              @if($hasTitle)
                <h1>{!! $title !!}</h1>
              @endif
              @if($hasSubtitle)
                <p class="hero-sub">{{ $data['subtitle'] }}</p>
              @endif
              @if($hasButtons)
                <div class="hero-btns">
                  @if($hasPrimaryBtn)
                    <a href="{{ $data['primary_btn_url'] ?? '#' }}" class="btn-premium">{{ $data['primary_btn_text'] }}</a>
                  @endif
                  @if($hasSecondaryBtn)
                    <a href="{{ $data['secondary_btn_url'] ?? '#' }}" class="btn-glass">{{ $data['secondary_btn_text'] }} ›</a>
                  @endif
                </div>
              @endif
            </div>
            @if($hasStats)
              <div class="hero-right">
                <div class="hero-badge-group">
                  @foreach($data['stats'] as $stat)
                    <div class="h-badge"><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif
      </div>
    @endforeach
  </div>
  
  @if(count($images) > 1)
    <button type="button" class="hero-arrow hero-prev" onclick="prevSlide()" aria-label="Previous Banner Slide">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button type="button" class="hero-arrow hero-next" onclick="nextSlide()" aria-label="Next Banner Slide">
      <i class="fas fa-chevron-right"></i>
    </button>
    <div class="slider-dots">
      @foreach($images as $index => $image)
        <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
      @endforeach
    </div>
  @endif
</section>

<style>
.hero-slider-wrapper {
  position: relative;
  width: 100%;
  height: var(--hero-desktop-height, 600px);
  overflow: hidden;
  background: #0d0408;
}
.hero-slider {
  height: 100%;
  position: relative;
}
.slide { 
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%; 
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out; 
  display: flex;
  align-items: center;
  z-index: 1;
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
}
.slide.active {
  opacity: 1;
  visibility: visible;
  z-index: 2;
}

.hero-container {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 3rem;
  align-items: center;
  width: 100%;
  max-width: 1582px;
}
.hero-left h1 {
  font-size: 2.2rem;
  line-height: 1.25;
  margin-bottom: 0.6rem;
}
.hero-sub {
  font-size: 1.05rem;
  line-height: 1.5;
  margin-bottom: 1.25rem;
}

.hero-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(15, 10, 13, 0.45);
  backdrop-filter: blur(4px);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.25);
  width: 44px;
  height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 10;
  transition: all 0.25s ease;
  font-size: 1rem;
}
.hero-arrow:hover {
  background: var(--primary, #6c1340);
  border-color: #fff;
  transform: translateY(-50%) scale(1.08);
}
.hero-arrow.hero-prev { left: 24px; }
.hero-arrow.hero-next { right: 24px; }

.slider-dots {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 10;
}
.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.4);
  cursor: pointer;
  transition: 0.3s;
  border: 2px solid transparent;
}
.dot.active {
  background: var(--accent, #f8b803);
  transform: scale(1.3);
  border-color: #fff;
}

@media (max-width: 1200px) {
  .hero-slider-wrapper {
    height: calc(var(--hero-desktop-height, 600px) * 0.85);
  }
}
@media (max-width: 1024px) {
  .hero-container {
    grid-template-columns: 1fr;
    text-align: center;
  }
  .hero-right {
    display: none;
  }
}
@media (max-width: 991px) {
  .hero-slider-wrapper {
    height: calc(var(--hero-desktop-height, 600px) * 0.72);
  }
  .hero-arrow {
    width: 36px;
    height: 36px;
    font-size: 0.85rem;
  }
  .hero-arrow.hero-prev { left: 12px; }
  .hero-arrow.hero-next { right: 12px; }
}
@media (max-width: 768px) {
  .hero-slider-wrapper {
    height: calc(var(--hero-desktop-height, 600px) * 0.58);
  }
}
@media (max-width: 480px) {
  .hero-slider-wrapper {
    height: calc(var(--hero-desktop-height, 600px) * 0.5);
    min-height: 250px;
  }
  .hero-left h1 {
    font-size: 1.5rem;
  }
  .hero-arrow {
    display: none;
  }
}
</style>

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  let heroInterval = null;

  function goToSlide(n) {
    if (!slides || slides.length === 0) return;
    slides[currentSlide].classList.remove('active');
    if (dots.length > currentSlide) dots[currentSlide].classList.remove('active');
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    if (dots.length > currentSlide) dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    goToSlide(currentSlide + 1);
  }

  function prevSlide() {
    goToSlide(currentSlide - 1);
  }

  function startHeroTimer() {
    if (slides.length > 1) {
      clearInterval(heroInterval);
      heroInterval = setInterval(nextSlide, 5000);
    }
  }

  startHeroTimer();

  const heroSliderWrap = document.querySelector('.hero-slider-wrapper');
  if (heroSliderWrap) {
    heroSliderWrap.addEventListener('mouseenter', () => clearInterval(heroInterval));
    heroSliderWrap.addEventListener('mouseleave', () => startHeroTimer());
  }
</script>
