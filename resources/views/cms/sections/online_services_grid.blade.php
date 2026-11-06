<section class="section section-alt">
  <div class="container" style="max-width: 1582px;">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      @if(isset($data['description']))
        <p>{{ $data['description'] }}</p>
      @endif
      <div class="sec-head-line"></div>
    </div>
    
    @php
      $items = $data['items'] ?? [];
      $itemCount = is_countable($items) ? count($items) : 0;
      $gridClass = 'os-grid-items-' . min(max($itemCount, 1), 4);
    @endphp

    <div class="os-grid {{ $gridClass }}">
      @if(!empty($items))
        @foreach($items as $item)
          @php
            $hasUrl = !empty($item['url']) && $item['url'] !== '#';
            $tag = $hasUrl ? 'a' : 'div';
            $attrs = $hasUrl ? 'href="' . e($item['url']) . '"' : '';
          @endphp
          <{{ $tag }} {!! $attrs !!} class="os-card">
            <div class="os-icon">
              @if(!empty($item['icon']) && file_exists(public_path($item['icon'])) && str_ends_with($item['icon'], '.svg'))
                {!! file_get_contents(public_path($item['icon'])) !!}
              @elseif(!empty($item['icon']) && file_exists(public_path($item['icon'])))
                <img src="{{ asset($item['icon']) }}" alt="{{ $item['label'] ?? '' }}">
              @else
                <span>{{ $item['icon'] ?? '⚡' }}</span>
              @endif
            </div>
            <h4 class="os-card-title">{{ $item['label'] ?? 'Service' }}</h4>
            <p class="os-card-desc">{{ $item['text'] ?? $item['description'] ?? 'Strategic support and trade facilitation services provided by TEFC.' }}</p>
          </{{ $tag }}>
        @endforeach
      @endif
    </div>
  </div>
</section>

<style>
  .os-grid {
    display: grid !important;
    gap: 2.2rem;
    justify-content: center !important;
    align-items: stretch;
    margin: 0 auto !important;
    width: 100%;
  }
  .os-grid.os-grid-items-1 {
    grid-template-columns: minmax(0, 420px) !important;
    max-width: 440px !important;
  }
  .os-grid.os-grid-items-2 {
    grid-template-columns: repeat(2, minmax(0, 400px)) !important;
    max-width: 860px !important;
  }
  .os-grid.os-grid-items-3 {
    grid-template-columns: repeat(3, minmax(0, 380px)) !important;
    max-width: 1240px !important;
  }
  .os-grid.os-grid-items-4 {
    grid-template-columns: repeat(4, minmax(0, 340px)) !important;
    max-width: 1480px !important;
  }
  .os-card {
    background: #ffffff !important;
    border-radius: 24px !important;
    border: 1px solid var(--border, #eedde5) !important;
    padding: 3rem 2rem 2.5rem !important;
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: flex-start !important;
    text-decoration: none !important;
    color: inherit !important;
    box-shadow: 0 6px 22px rgba(108, 19, 64, 0.04) !important;
    transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.35s ease, border-color 0.35s ease !important;
    height: 100% !important;
  }
  .os-card:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 18px 38px rgba(108, 19, 64, 0.14) !important;
    border-color: #6c1340 !important;
  }
  .os-icon {
    width: 96px !important;
    height: 96px !important;
    border-radius: 50% !important;
    background: var(--sky, #fce8f2) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 1.6rem !important;
    color: #6c1340 !important;
    transition: transform 0.3s ease, background 0.3s ease !important;
    flex-shrink: 0 !important;
  }
  .os-card:hover .os-icon {
    background: #f8d5e6 !important;
    transform: scale(1.06) !important;
  }
  .os-icon svg {
    width: 52px !important;
    height: 52px !important;
    max-width: 52px !important;
    max-height: 52px !important;
    display: block !important;
    margin: auto !important;
  }
  .os-icon img {
    max-height: 52px !important;
    max-width: 52px !important;
    object-fit: contain !important;
  }
  .os-card-title {
    color: #6c1340 !important;
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    margin-bottom: 0.85rem !important;
    line-height: 1.35 !important;
  }
  .os-card-desc {
    color: var(--muted, #5a6a82) !important;
    font-size: 0.92rem !important;
    line-height: 1.65 !important;
    margin: 0 !important;
  }
  @media (max-width: 991px) {
    .os-grid,
    .os-grid.os-grid-items-3,
    .os-grid.os-grid-items-4 {
      grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
      max-width: 820px !important;
    }
  }
  @media (max-width: 640px) {
    .os-grid,
    .os-grid.os-grid-items-2,
    .os-grid.os-grid-items-3,
    .os-grid.os-grid-items-4 {
      grid-template-columns: 1fr !important;
      max-width: 100% !important;
    }
  }
</style>
