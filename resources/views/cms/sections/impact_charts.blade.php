<section class="section section-alt">
  <div class="container">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      <div class="sec-head-line"></div>
    </div>
    <div class="impact-bottom">
      <!-- Bar Chart -->
      <div class="cluster-card" style="padding: 2.5rem; border-radius: 15px;">
        <h4 style="color: var(--primary); margin-bottom: 2rem;">{{ $data['bar_chart_title'] ?? 'Export Volume by Cluster' }}</h4>
        @if(isset($data['bar_items']))
          @foreach($data['bar_items'] as $bar)
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 1.2rem;">
              <span style="width: 100px; font-size: 0.9rem; color: var(--muted); font-weight: 600;">{{ $bar['label'] }}</span>
              <div style="flex:1; height: 14px; background: var(--sky); border-radius: 7px; overflow: hidden;">
                <div class="bar-fill" data-width="{{ $bar['value'] }}" style="height: 100%; background: {{ $bar['color'] ?? 'var(--primary)' }}; border-radius: 7px; width: 0; transition: 1.5s;"></div>
              </div>
              <span style="width: 45px; font-size: 0.9rem; font-weight: 700; color: var(--primary); text-align: right;">{{ $bar['value'] }}</span>
            </div>
          @endforeach
        @endif
      </div>

      <!-- Donut Chart -->
      <div class="cluster-card" style="padding: 2.5rem; border-radius: 15px; text-align: center;">
        <h4 style="color: var(--primary); margin-bottom: 2rem;">{{ $data['donut_chart_title'] ?? 'Production Share' }}</h4>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 2rem;">
          <div class="donut" style="width: 150px; height: 150px; border-radius: 50%; background: {{ $data['donut_gradient'] ?? 'var(--primary)' }}; display: flex; align-items: center; justify-content: center; position: relative;">
             <div style="width: 110px; height: 110px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800; color: var(--primary);">{{ $data['donut_center_text'] ?? '2026' }}</div>
          </div>
          <ul style="list-style: none; width: 100%; text-align: left;">
            @if(isset($data['donut_items']))
              @foreach($data['donut_items'] as $di)
                <li style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 0.9rem; color: var(--muted); font-weight: 600;">
                  <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $di['color'] ?? 'var(--primary)' }};"></div>
                  {{ $di['label'] }} — {{ $di['value'] }}
                </li>
              @endforeach
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
