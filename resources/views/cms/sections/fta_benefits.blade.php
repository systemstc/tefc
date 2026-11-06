<section class="section">
  <div class="container" style="max-width: 1582px;">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      @if(isset($data['description']))
        <p>{{ $data['description'] }}</p>
      @endif
      <div class="sec-head-line"></div>
    </div>
    <div class="fta-grid">
      @if(isset($data['items']))
        @foreach($data['items'] as $item)
          <div class="fta-card">
            <h3>{{ $item['name'] ?? 'Scheme' }} @if(isset($item['status'])) <span class="status" style="font-size: 0.65rem; background: var(--sky); color: var(--primary); padding: 3px 10px; border-radius: 12px; font-weight: 800; text-transform: uppercase; vertical-align: middle;">{{ $item['status'] }}</span> @endif</h3>
            @if(isset($item['description']) && $item['description'])
              <p class="portal-content-justified" style="font-size: 0.95rem; color: #555555; line-height: 1.65; margin: 0.75rem 0 1.25rem; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $item['description'] }}</p>
            @endif
            @if(isset($item['benefits']) && is_array($item['benefits']))
              <ul class="fta-list" style="list-style: none; margin: 1rem 0; flex: 1;">
                @foreach($item['benefits'] as $benefit)
                  <li style="font-size: 0.9rem; color: var(--muted); margin-bottom: 0.8rem; display: flex; gap: 10px;"><span style="color:#1e7e44;font-weight:900;">✓</span> {{ $benefit }}</li>
                @endforeach
              </ul>
            @endif
            @if(isset($item['eligibility']))
              <div class="fta-eligibility" style="font-size: 0.85rem; margin-bottom: 1.5rem; color: var(--muted);"><strong>Eligibility:</strong> {{ $item['eligibility'] }}</div>
            @endif
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
