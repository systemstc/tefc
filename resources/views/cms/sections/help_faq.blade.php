<section class="section">
  <div class="container">
    <div class="sec-head">
      <h2>{{ $title }}</h2>
      @if(isset($data['description']))
        <p>{{ $data['description'] }}</p>
      @endif
      <div class="sec-head-line"></div>
    </div>
    
    <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">
      @if(isset($data['items']))
        @foreach($data['items'] as $item)
          <div style="border: 1px solid var(--border); border-radius: 12px; padding: 2rem; background: #fff;">
            <h4 style="color: var(--primary); font-size: 1.2rem; margin-bottom: 1rem; display: flex; gap: 15px;">
              <span style="color: var(--accent);">Q.</span> {{ $item['question'] ?? 'Frequently Asked Question?' }}
            </h4>
            <p class="portal-content-justified" style="color: var(--muted); line-height: 1.75; padding-left: 33px; text-align: justify; text-justify: inter-word; hyphens: auto;">
              <span style="font-weight: 700; color: var(--text); margin-right: 5px;">A.</span> {{ $item['answer'] ?? 'This is the detailed answer providing support and guidance to the user.' }}
            </p>
          </div>
        @endforeach
      @endif
    </div>

    @if(isset($data['support_email']))
    <div style="text-align: center; margin-top: 4rem; padding: 3rem; background: var(--sky2); border-radius: 20px;">
        <h3 style="color: var(--primary); margin-bottom: 1rem;">Still need help?</h3>
        <p style="color: var(--muted); margin-bottom: 2rem;">Our support team is available 24/7 to assist you with any export-related queries.</p>
        <a href="mailto:{{ $data['support_email'] }}" class="btn-premium" style="display: inline-block;">Email Support</a>
    </div>
    @endif
  </div>
</section>
