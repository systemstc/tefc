<section class="section section-alt">
  <div class="container">
    <div class="media-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
      <h2 style="color: var(--primary); font-size: 1.8rem; font-weight: 700;">{{ $title }}</h2>
      <a href="{{ $data['view_more_url'] ?? url('/media') }}" style="color: var(--primary); font-size: 0.95rem; font-weight: 700; text-decoration: none;">View More ›</a>
    </div>
    <div class="media-grid">
      @if(isset($data['items']))
        @foreach($data['items'] as $item)
          <div class="media-card" style="height: 250px;">
            <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: var(--primary); position: relative;">
              @if(isset($item['image']))
                <img src="{{ asset($item['image']) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.55;">
              @endif
              <div style="position: absolute; width: 60px; height: 60px; background: rgba(255, 255, 255, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;"><div style="width: 0; height: 0; border-top: 14px solid transparent; border-bottom: 14px solid transparent; border-left: 20px solid var(--primary); margin-left: 6px;"></div></div>
              <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.9)); padding: 1.8rem; color: #fff; font-size: 1rem; font-weight: 700; z-index: 3;">{{ $item['label'] ?? 'Watch Video' }}</div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
