<section class="section">
  <div class="container">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      <div class="sec-head-line"></div>
    </div>
    <div class="impact-top">
      @if(isset($data['stats']))
        @foreach($data['stats'] as $stat)
          <div class="impact-stat">
            <div class="big">{{ $stat['value'] ?? '0' }}</div>
            <div class="lbl">{{ $stat['label'] ?? 'Metric' }}</div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
