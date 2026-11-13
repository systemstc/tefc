<section class="section section-alt tefc-clusters-section" id="clusters-section">
  <div class="container" style="max-width: 1582px;">
    <div class="sec-head text-center">
      <h2>{{ $title ?? 'Major Textile Clusters' }}</h2>
      @if(isset($data['description']))
        <p class="max-w-700 mx-auto">{{ $data['description'] }}</p>
      @else
        <p class="max-w-700 mx-auto">Centres of Excellence driving regional textile entrepreneurship, technological upgradation, and export facilitation across India's premier manufacturing districts.</p>
      @endif
      <div class="sec-head-line"></div>
    </div>

    @php
      $items = $data['items'] ?? [];
      
      // Calculate total units present
      $totalUnits = 0;
      foreach ($items as $it) {
        $uCount = isset($it['units_count']) ? (int) preg_replace('/[^0-9]/', '', (string)$it['units_count']) : 0;
        $totalUnits += $uCount;
      }
      if ($totalUnits === 0) $totalUnits = 1478;
    @endphp

    <!-- Interactive Map Card & Intelligence Showcase (Can be toggled Active/Inactive in CMS) -->
    @if(!empty($data['show_map_card']))
    <!-- Top Key Metrics Summary Bar -->
    <div class="cluster-summary-bar">
      <div class="cluster-summary-item">
        <div class="cluster-summary-icon"><i class="fas fa-network-wired"></i></div>
        <div class="cluster-summary-text">
          <strong>{{ count($items) > 0 ? count($items) : 6 }} Strategic Centres</strong>
          <span>Pan-India Grid</span>
        </div>
      </div>
      <div class="cluster-summary-item">
        <div class="cluster-summary-icon"><i class="fas fa-map-marked-alt"></i></div>
        <div class="cluster-summary-text">
          <strong>10 Covered Districts</strong>
          <span>Designated Jurisdiction</span>
        </div>
      </div>
      <div class="cluster-summary-item">
        <div class="cluster-summary-icon"><i class="fas fa-industry"></i></div>
        <div class="cluster-summary-text">
          <strong>{{ number_format($totalUnits) }}+ Units Present</strong>
          <span>MSMEs Facilitated</span>
        </div>
      </div>
    </div>

    <!-- Interactive Animated Map & Intelligence Showcase -->
    <div class="tefc-map-showcase">
      <div class="tefc-map-layout">
        <div class="tefc-map-col-map">
          @include('cms.partials.india_clusters_map')
        </div>

        <div class="tefc-map-col-info">
          <div class="tefc-intelligence-panel">
            <div>
              <span class="intel-badge-title"><i class="fas fa-map-marked-alt text-primary"></i> District Facilitation Grid</span>
              <h3 class="intel-heading">District Export Presence</h3>
              <p class="intel-subtext">Click any animated node or district chip to inspect designated jurisdiction coverage and active textile manufacturing units present.</p>
            </div>

            <div class="intel-chips-wrapper" id="intelChipsContainer">
              @foreach($items as $idx => $it)
                @php
                  $code = strtolower($it['code'] ?? $it['name'] ?? 'cluster');
                  $uCount = $it['units_count'] ?? '100+';
                @endphp
                <button type="button" 
                        class="intel-chip {{ $idx === 0 ? 'is-active' : '' }}" 
                        data-centre="{{ $code }}"
                        onclick="activateCluster('{{ $code }}')">
                  <i class="fas fa-map-pin text-primary small"></i>
                  {{ $it['name'] ?? 'Cluster' }}
                  <span class="chip-count">{{ $uCount }}</span>
                </button>
              @endforeach
            </div>

            <div class="intel-spotlight-card" id="intelSpotlightCard">
              @php
                $firstItem = $items[0] ?? [
                  'name' => 'Surat',
                  'code' => 'surat',
                  'state' => 'Gujarat',
                  'districts' => 'Surat, Navsari',
                  'units_count' => '115',
                  'specialization' => 'Man-made Fibre (MMF), Synthetic Fabrics, Technical Textiles & Jacquards',
                  'description' => "India's synthetic textile capital supplying global markets with high-grade yarn, fabrics, and technical textiles."
                ];
              @endphp
              <div class="spotlight-top">
                <div>
                  <h4 class="spotlight-centre-title" id="spotlightTitle">{{ $firstItem['name'] ?? 'Surat' }} TEFC Centre</h4>
                  <div class="spotlight-state" id="spotlightState"><i class="fas fa-landmark text-primary me-1"></i> {{ $firstItem['state'] ?? 'Gujarat' }}</div>
                </div>
                <div class="spotlight-count-badge">
                  <strong id="spotlightCount">{{ $firstItem['units_count'] ?? '115' }}</strong>
                  <span>Units in District</span>
                </div>
              </div>

              <div class="spotlight-meta-grid">
                <div class="spotlight-meta-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <div>
                    <strong>Covered Districts:</strong>
                    <div id="spotlightDistricts" class="text-dark fw-bold">{{ $firstItem['districts'] ?? 'Surat, Navsari' }}</div>
                  </div>
                </div>
                <div class="spotlight-meta-item">
                  <i class="fas fa-tags"></i>
                  <div>
                    <strong>Specialization:</strong>
                    <div id="spotlightSpec" class="text-muted small">{{ $firstItem['specialization'] ?? 'Textile Manufacturing & Exports' }}</div>
                  </div>
                </div>
                <div class="spotlight-meta-item">
                  <i class="fas fa-envelope-open-text"></i>
                  <div>
                    <strong>Helpline & Facilitation Email:</strong>
                    <div id="spotlightEmail" class="text-primary small fw-semibold">center.{{ strtolower($firstItem['code'] ?? $firstItem['name'] ?? 'surat') }}@tefc.gov.in</div>
                  </div>
                </div>
              </div>

              <div class="spotlight-actions">
                <a href="#card-{{ strtolower($firstItem['code'] ?? $firstItem['name'] ?? 'surat') }}" class="spotlight-btn-primary" id="spotlightViewBtn">
                  <i class="fas fa-external-link-alt"></i> View Cluster Card
                </a>
                <a href="{{ route('register') }}" class="spotlight-btn-outline">
                  <i class="fas fa-user-plus"></i> Register from this District
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Cluster Cards Grid: Clean Modern Cards matching reference design -->
    <div class="clusters-grid">
      @if(isset($items))
        @foreach($items as $item)
          @php
            $code = strtolower($item['code'] ?? $item['name'] ?? 'cluster');
            $uCount = $item['units_count'] ?? '100+';

            $stateClassMap = [
              'gujarat' => 'gujarat',
              'tamil nadu' => 'tamilnadu',
              'tamilnadu' => 'tamilnadu',
              'maharashtra' => 'maharashtra',
              'rajasthan' => 'rajasthan',
              'uttar pradesh' => 'uttarpradesh',
              'uttarpradesh' => 'uttarpradesh',
              'punjab' => 'punjab',
            ];
            $stateClass = $stateClassMap[strtolower($item['state'] ?? '')] ?? $code;

            $codeKey = ($code === 'kolhapur') ? 'ichalkaranji' : $code;
            $defaultCard = 'images/clusters/' . $codeKey . '_card.png';

            // Resolve card image: custom uploaded image takes priority, otherwise use official cluster card asset
            $cardImageUrl = '';
            if (!empty($item['image'])) {
                $rawImg = trim($item['image']);
                if (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) {
                    $cardImageUrl = $rawImg;
                } else {
                    $cardImageUrl = asset(ltrim($rawImg, '/'));
                }
            } else {
                $cardImageUrl = asset($defaultCard);
            }
          @endphp
          <div class="cluster-card {{ $stateClass }} tefc-cluster-card cluster-district-header" id="card-{{ $code }}" data-centre="{{ $code }}">
            <img src="{{ $cardImageUrl }}" class="card-image" alt="{{ $item['districts'] ?? $item['name'] }}" onerror="this.onerror=null; this.src='{{ asset($defaultCard) }}';">
            <div class="card-content">
              <div class="top-row">
                <div class="state-tag">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>
                  </svg>
                  <span class="cluster-state-pill">{{ strtoupper($item['state'] ?? 'India') }}</span>
                </div>
              </div>

              <div class="main-info">
                <h2 class="city district-focus-title">{{ $item['districts'] ?? $item['name'] }}</h2>
                <!-- <p class="jurisdiction district-jurisdiction-label">Designated Revenue Jurisdiction</p> -->
              </div>

              <a href="{{ $item['url'] ?? url('/p/clusters?cluster=' . $code) }}" class="explore-btn cluster-explore-btn">
                <span>Explore Cluster</span>
                <span class="arrow-circle">→</span>
              </a>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>

<!-- Interactive Map Synchronization Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const clusterData = @json($items);
  const dataLookup = {};
  
  clusterData.forEach(item => {
    const code = (item.code || item.name || '').toLowerCase();
    dataLookup[code] = item;
  });

  const tooltip = document.getElementById('tefcMapTooltip');
  const spotlightTitle = document.getElementById('spotlightTitle');
  const spotlightState = document.getElementById('spotlightState');
  const spotlightCount = document.getElementById('spotlightCount');
  const spotlightDistricts = document.getElementById('spotlightDistricts');
  const spotlightSpec = document.getElementById('spotlightSpec');
  const spotlightEmail = document.getElementById('spotlightEmail');
  const spotlightViewBtn = document.getElementById('spotlightViewBtn');

  // Activate cluster function
  window.activateCluster = function (code, scrollIntoView = false) {
    const it = dataLookup[code];
    if (!it) return;

    // Update Spotlight Card
    if (spotlightTitle) spotlightTitle.textContent = (it.name || 'Cluster') + ' TEFC Centre';
    if (spotlightState) spotlightState.innerHTML = '<i class="fas fa-landmark text-primary me-1"></i> ' + (it.state || 'India');
    if (spotlightCount) spotlightCount.textContent = it.units_count || '100+';
    if (spotlightDistricts) spotlightDistricts.textContent = it.districts || 'All Designated Districts';
    if (spotlightSpec) spotlightSpec.textContent = it.specialization || 'Comprehensive Textile Facilitation';
    if (spotlightEmail) spotlightEmail.textContent = 'center.' + code + '@tefc.gov.in';
    if (spotlightViewBtn) spotlightViewBtn.setAttribute('href', '#card-' + code);

    // Update Chips
    document.querySelectorAll('.intel-chip').forEach(chip => {
      if (chip.getAttribute('data-centre') === code) {
        chip.classList.add('is-active');
      } else {
        chip.classList.remove('is-active');
      }
    });

    // Update Map Pins
    document.querySelectorAll('.centre-pin-node').forEach(node => {
      if (node.getAttribute('data-code') === code) {
        node.classList.add('is-active');
      } else {
        node.classList.remove('is-active');
      }
    });

    // Update State Highlights on SVG
    document.querySelectorAll('.state-path').forEach(path => {
      if (path.getAttribute('data-centre-code') === code) {
        path.classList.add('is-active');
      } else {
        path.classList.remove('is-active');
      }
    });

    // Update Cluster Cards below
    document.querySelectorAll('.cluster-card').forEach(card => {
      if (card.getAttribute('data-centre') === code) {
        card.classList.add('is-highlighted');
        if (scrollIntoView) {
          card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      } else {
        card.classList.remove('is-highlighted');
      }
    });
  };

  // Node Pins Hover & Tooltip Events
  document.querySelectorAll('.centre-pin-node').forEach(node => {
    node.addEventListener('mouseenter', function (e) {
      const code = this.getAttribute('data-code');
      const it = dataLookup[code];
      if (!it || !tooltip) return;

      document.getElementById('ttStateBadge').textContent = it.state || 'India';
      document.getElementById('ttCentreName').textContent = (it.name || 'Cluster') + ' Centre';
      document.getElementById('ttDistricts').textContent = it.districts || 'Coverage';
      document.getElementById('ttUnitsCount').textContent = (it.units_count || '100+') + ' Units Present';
      document.getElementById('ttSpecialization').textContent = it.specialization || 'Textile Sector';

      // Position tooltip relative to map wrapper
      const wrapper = document.querySelector('.tefc-india-map-wrapper');
      const wrapperRect = wrapper.getBoundingClientRect();
      const nodeRect = this.getBoundingClientRect();

      const left = nodeRect.left - wrapperRect.left + (nodeRect.width / 2);
      const top = nodeRect.top - wrapperRect.top;

      tooltip.style.left = left + 'px';
      tooltip.style.top = top + 'px';
      tooltip.classList.add('is-visible');
    });

    node.addEventListener('mouseleave', function () {
      if (tooltip) tooltip.classList.remove('is-visible');
    });

    node.addEventListener('click', function () {
      const code = this.getAttribute('data-code');
      activateCluster(code, true);
    });
  });

  // State Paths Click / Hover
  document.querySelectorAll('.state-tefc').forEach(stateEl => {
    stateEl.addEventListener('click', function () {
      const code = this.getAttribute('data-centre-code');
      if (code) activateCluster(code, true);
    });
  });

  // Cluster Cards Hover
  document.querySelectorAll('.cluster-card').forEach(card => {
    card.addEventListener('mouseenter', function () {
      const code = this.getAttribute('data-centre');
      if (code) activateCluster(code, false);
    });
  });
});
</script>
