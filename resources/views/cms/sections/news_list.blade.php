<section class="section section-alt">
  <div class="container">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      @if(isset($data['description']))
        <p>{{ $data['description'] }}</p>
      @endif
      <div class="sec-head-line"></div>
    </div>
    <div class="news-list" style="display: flex; flex-direction: column; gap: 2rem; max-width: 900px; margin: 0 auto;">
      @if(isset($data['items']))
        @foreach($data['items'] as $item)
          <div class="news-card" style="background: #fff; border: 1px solid var(--border); border-radius: 15px; padding: 2.5rem; display: flex; gap: 2rem; align-items: flex-start; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
            <div class="news-date" style="background: var(--sky); padding: 1rem; border-radius: 10px; text-align: center; min-width: 100px;">
              <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary); line-height: 1;">{{ $item['day'] ?? '01' }}</div>
              <div style="font-size: 0.85rem; font-weight: 700; color: var(--muted); text-transform: uppercase;">{{ $item['month'] ?? 'JAN' }}</div>
            </div>
            <div class="news-content" style="flex: 1;">
              <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.4rem;">{{ $item['title'] ?? 'News Update' }}</h3>
              <p class="portal-content-justified" style="color: var(--muted); line-height: 1.75; margin-bottom: 1.5rem; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $item['summary'] ?? 'Important update regarding TEFC operations and export facilitation.' }}</p>
              <a href="{{ $item['url'] ?? '#' }}" style="color: var(--primary); font-weight: 700; text-decoration: none; border-bottom: 2px solid var(--accent); padding-bottom: 2px;">Read Full Update ›</a>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
