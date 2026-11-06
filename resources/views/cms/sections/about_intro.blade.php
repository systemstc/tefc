@php
    // Fetch latest general enquiries. If none exist, we fall back to pre-seeded ones.
    $enquiries = \App\Models\ProductInquiry::whereNull('product_id')->latest()->take(10)->get();
    
    if ($enquiries->isEmpty()) {
        $mockEnquiries = [
            (object)[
                'buyer_name' => 'Textile Import Inc.',
                'buyer_email' => 'imports@textileinc.com',
                'buyer_phone' => '+1 (555) 019-2834',
                'category' => 'Home Textiles',
                'material' => 'Cotton',
                'quantity_required' => '5,000 Meters',
                'pattern' => 'Jacquard Loomed',
                'width_size' => '120 inches',
                'additional_requirements' => 'SGS Certification required. Needed for US hospitality market.',
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'buyer_name' => 'EuroDesigns SpA',
                'buyer_email' => 'sourcing@eurodesigns.it',
                'buyer_phone' => '+39 02 8765 4321',
                'category' => 'Fabrics',
                'material' => 'Linen',
                'quantity_required' => '2,500 Meters',
                'pattern' => 'Solid Dyed, Pre-washed',
                'width_size' => '58 inches',
                'additional_requirements' => 'Organic linen preferred, seeking immediate shipment.',
                'created_at' => now()->subHours(5)
            ],
            (object)[
                'buyer_name' => 'Gulf Garment Hub',
                'buyer_email' => 'buyer@garmenthub.ae',
                'buyer_phone' => '+971 4 123 4567',
                'category' => 'Garments',
                'material' => 'Polyester',
                'quantity_required' => '10,000 Pieces',
                'pattern' => 'Sublimation Printed',
                'width_size' => 'Standard M to XXL',
                'additional_requirements' => 'Activewear shirts, quick-dry fabric specification.',
                'created_at' => now()->subDay()
            ],
            (object)[
                'buyer_name' => 'Global Carpets Ltd',
                'buyer_email' => 'procurement@globalcarpets.co.uk',
                'buyer_phone' => '+44 20 7946 0958',
                'category' => 'Carpets',
                'material' => 'Wool',
                'quantity_required' => '500 Sq. Meters',
                'pattern' => 'Hand-tufted Traditional',
                'width_size' => 'Various Sizes',
                'additional_requirements' => 'New Zealand wool, seeking custom backing options.',
                'created_at' => now()->subDays(2)
            ],
            (object)[
                'buyer_name' => 'GreenThread Textiles',
                'buyer_email' => 'info@greenthread.de',
                'buyer_phone' => '+49 69 9876 5430',
                'category' => 'Sustainable Textiles',
                'material' => 'Organic/Recycled Blend',
                'quantity_required' => '15,000 Meters',
                'pattern' => 'Plain Weave',
                'width_size' => '60 inches',
                'additional_requirements' => 'GOTS certified yarn and fabrics only.',
                'created_at' => now()->subDays(3)
            ]
        ];
        $enquiries = collect($mockEnquiries);
    }
@endphp

<style>
@keyframes scrollUp {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-50%);
    }
}
.lead-item {
    background: #fdfdfc;
    border: 1px solid rgba(108, 21, 63, 0.06);
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 4px 12px rgba(108, 21, 63, 0.01);
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
.lead-item:hover {
    transform: translateY(-2px);
    border-color: rgba(108, 21, 63, 0.25) !important;
    box-shadow: 0 8px 20px rgba(108, 21, 63, 0.06) !important;
}
.leadership-card-item:hover {
    transform: translateY(-4px);
}
.leadership-card-item:hover .leadership-card-item > div:first-child img {
    transform: scale(1.03);
}
.about-intro-section p,
.portal-content-justified {
    text-align: justify !important;
    text-justify: inter-word !important;
    -webkit-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto;
    line-height: 1.85;
}
@media (max-width: 991px) {
    .about-grid {
        grid-template-columns: 1fr !important;
        gap: 3rem !important;
    }
}
@media (max-width: 640px) {
    .leadership-grid {
        grid-template-columns: 1fr !important;
        gap: 1.25rem !important;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<section class="section about-intro-section" style="padding: 3.5rem 0;">
  <div class="container" style="max-width: 1582px;">
    <div class="about-grid" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 3.5rem; align-items: center;">
      
      <!-- Left Column: About TEFC Description & Image -->
      <div>
        <div class="sec-head" style="text-align: left; margin-bottom: 1.25rem;">
          <h2 style="font-size: 2.4rem; color: var(--primary); font-weight: 800; margin-bottom: 0.35rem;">{{ $title }}</h2>
          <div class="sec-head-line" style="margin: 0.4rem 0 0; background-color: var(--accent); height: 4px; width: 60px; border-radius: 2px;"></div>
        </div>
        
        @if(isset($data['text_1']))
          <p class="portal-content-justified" style="color: var(--muted); line-height: 1.85; margin-bottom: 1.25rem; font-size: 1.05rem; font-weight: 500; text-align: justify; text-justify: inter-word; hyphens: auto;">
            {{ $data['text_1'] }}
          </p>
        @endif
        
        @if(isset($data['text_2']))
          <p class="portal-content-justified" style="color: var(--muted); line-height: 1.85; margin-bottom: 1.5rem; font-size: 1rem; text-align: justify; text-justify: inter-word; hyphens: auto;">
            {{ $data['text_2'] }}
          </p>
        @endif

        @if(!empty($data['people']) && is_array($data['people']))
          <div class="leadership-cards-wrapper" style="margin-bottom: 2rem; background: #ffffff; border-radius: 22px; padding: 1.4rem; box-shadow: 0 10px 32px rgba(108, 21, 63, 0.07); border: 1px solid rgba(108, 21, 63, 0.07);">
            <div class="leadership-grid" style="display: grid; grid-template-columns: repeat({{ count($data['people']) }}, minmax(0, 1fr)); gap: 1.25rem;">
              @foreach($data['people'] as $person)
                @php
                  $personLink = !empty($person['link']) ? $person['link'] : null;
                @endphp
                <{{ $personLink ? 'a' : 'div' }} {!! $personLink ? 'href="' . e($personLink) . '"' : '' !!} class="leadership-card-item" style="display: flex; flex-direction: column; align-items: stretch; text-decoration: none; position: relative; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                  <!-- Portrait Image Container with Pastel Mauve Backdrop -->
                  <div style="background: linear-gradient(180deg, #fdf3f6 0%, #f6e4eb 100%); border-radius: 14px 14px 0 0; height: 165px; display: flex; align-items: flex-end; justify-content: center; overflow: hidden; position: relative;">
                    <img src="{{ asset($person['image'] ?? 'images/people/neelam_shami_rao.png') }}" 
                         alt="{{ $person['name'] ?? 'TEFC Leadership' }}" 
                         style="max-height: 100%; width: auto; object-fit: contain; object-position: bottom; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.06)); display: block;"
                         onerror="this.onerror=null; this.src='{{ asset('images/people/neelam_shami_rao.png') }}';">
                  </div>
                  <!-- Name & Designation Pill overlapping photo bottom -->
                  <div style="background: linear-gradient(180deg, #fdfaf6 0%, #f6ede3 100%); border: 1px solid rgba(179, 142, 93, 0.28); border-radius: 10px; padding: 0.65rem 0.5rem; text-align: center; margin-top: -10px; z-index: 2; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);">
                    <div style="color: #5c1439; font-weight: 800; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1.25; margin-bottom: 2px;">
                      {{ $person['name'] ?? '' }}
                    </div>
                    <div style="color: #555452; font-size: 0.78rem; font-style: italic; line-height: 1.2;">
                      {{ $person['designation'] ?? '' }}
                    </div>
                  </div>
                </{{ $personLink ? 'a' : 'div' }}>
              @endforeach
            </div>
          </div>
        @elseif(isset($data['image']))
          <div style="margin-bottom: 1.75rem; border-radius: 16px; overflow: hidden; box-shadow: 0 15px 35px rgba(108, 21, 63, 0.1);">
            <img src="{{ asset($data['image']) }}" style="width: 100%; display: block; object-fit: cover; max-height: 230px;">
          </div>
        @endif

        @if(isset($data['btn_text']))
          <a href="{{ $data['btn_url'] ?? '#' }}" class="btn-view" style="padding: 0.8rem 2rem; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; background-color: var(--primary); color: #fff; text-decoration: none; border-radius: 50px; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(108, 21, 63, 0.2);">
            {{ $data['btn_text'] }} <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
          </a>
        @endif
      </div>

      <!-- Right Column: Active Trade Leads / Buyer Enquiries Ticker -->
      <div>
        <div class="trade-leads-card" style="background: #fff; border: 1px solid rgba(108, 21, 63, 0.08); border-radius: 20px; box-shadow: 0 15px 40px rgba(108, 21, 63, 0.06); padding: 1.75rem; height: 480px; display: flex; flex-direction: column; overflow: hidden;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; border-bottom: 2px solid rgba(108, 21, 63, 0.05); padding-bottom: 0.85rem;">
            <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
              <i class="fas fa-rss text-danger animate-pulse"></i> Live Buyer Leads
            </h3>
            <span class="badge" style="background-color: rgba(108, 21, 63, 0.08); color: var(--primary); font-weight: 600; font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 50px;">
              Ticker Board
            </span>
          </div>

          <!-- Scrolling Ticker Container -->
          <div class="ticker-wrap" 
               onmouseover="const scroll = this.querySelector('.ticker-scroll'); if (scroll) scroll.style.animationPlayState = 'paused';" 
               onmouseout="const scroll = this.querySelector('.ticker-scroll'); if (scroll) scroll.style.animationPlayState = 'running';" 
               style="flex: 1; overflow: hidden; position: relative; width: 100%;">
            <div class="ticker-scroll" style="display: flex; flex-direction: column; gap: 1rem; position: absolute; width: 100%; animation: scrollUp 30s linear infinite;">
              <!-- Render enquiries list twice to create a seamless infinite loop -->
              @foreach($enquiries->concat($enquiries) as $inquiry)
                <div class="lead-item" onclick="handleLeadClick(this)"
                     data-category="{{ $inquiry->category }}"
                     data-material="{{ $inquiry->material }}"
                     data-qty="{{ $inquiry->quantity_required }}"
                     data-pattern="{{ $inquiry->pattern }}"
                     data-size="{{ $inquiry->width_size }}"
                     data-reqs="{{ $inquiry->additional_requirements }}"
                     data-name="{{ $inquiry->buyer_name ?? 'N/A' }}"
                     data-email="{{ $inquiry->buyer_email ?? 'N/A' }}"
                     data-phone="{{ $inquiry->buyer_phone ?? 'N/A' }}"
                     data-date="{{ is_string($inquiry->created_at) ? $inquiry->created_at : $inquiry->created_at->diffForHumans() }}">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">
                      {{ $inquiry->category }} <span style="font-weight: 400; color: #706f6c; font-size: 0.85rem;">({{ $inquiry->material }})</span>
                    </span>
                    <span style="font-size: 0.75rem; color: #a1a09a;">
                      {{ is_string($inquiry->created_at) ? $inquiry->created_at : $inquiry->created_at->diffForHumans() }}
                    </span>
                  </div>
                  <div style="font-size: 0.85rem; line-height: 1.6; color: #4b4b45;">
                    <div style="margin-bottom: 0.25rem;">
                      <strong>Quantity Required:</strong> {{ $inquiry->quantity_required }}
                    </div>
                    <div style="margin-bottom: 0.25rem;">
                      <strong>Pattern & Size:</strong> {{ $inquiry->pattern }} | {{ $inquiry->width_size }}
                    </div>
                    @if($inquiry->additional_requirements)
                      <div style="font-style: italic; color: #706f6c; border-left: 2.5px solid var(--accent); padding-left: 0.5rem; margin-top: 0.5rem; background: rgba(248, 184, 3, 0.05); padding-top: 0.25rem; padding-bottom: 0.25rem; border-radius: 0 4px 4px 0;">
                        "{{ Str::limit($inquiry->additional_requirements, 90) }}"
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</section>

<!-- Lead Details Modal (Glow Overlay & Clean Formatting) -->
<div id="leadDetailsModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 10, 13, 0.6); backdrop-filter: blur(8px); z-index: 11000; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div style="background: #fff; width: 90%; max-width: 550px; border-radius: 24px; box-shadow: 0 25px 60px rgba(108, 21, 63, 0.15); border: 1px solid var(--border); overflow: hidden; transform: scale(0.9); transition: transform 0.3s ease; position: relative;">
        <!-- Header -->
        <div style="background: var(--primary); padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; color: #fff;">
            <h4 style="margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.6rem;">
                <i class="fas fa-bullhorn text-warning animate-pulse"></i> Trade Lead Sourcing Details
            </h4>
            <button onclick="closeLeadModal()" style="background: none; border: none; color: #fff; opacity: 0.8; font-size: 1.25rem; cursor: pointer; transition: 0.2s;"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Body -->
        <div style="padding: 2rem; max-height: 70vh; overflow-y: auto;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Category</label>
                    <span id="modalLeadCategory" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Material</label>
                    <span id="modalLeadMaterial" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Quantity Required</label>
                    <span id="modalLeadQty" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Posted On</label>
                    <span id="modalLeadDate" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Pattern Style</label>
                    <span id="modalLeadPattern" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Width / Size</label>
                    <span id="modalLeadSize" style="font-weight: 700; color: #1a1a2e; font-size: 0.95rem;"></span>
                </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Additional Requirements</label>
                <div id="modalLeadReqs" style="font-style: italic; color: #4b4b45; border-left: 3px solid var(--accent); padding-left: 0.75rem; background: rgba(248, 184, 3, 0.05); padding-top: 0.6rem; padding-bottom: 0.6rem; border-radius: 0 6px 6px 0; line-height: 1.6; font-size: 0.95rem;"></div>
            </div>
            
            <!-- Contact Details Card -->
            <div style="background: var(--sky2); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem;">
                <h5 style="color: var(--primary); font-weight: 800; font-size: 1rem; margin-top: 0; margin-bottom: 1.2rem; border-bottom: 1px solid var(--border); padding-bottom: 0.6rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-address-card text-primary"></i> Buyer Contact Information
                </h5>
                <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.95rem;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-user text-muted" style="width: 18px;"></i>
                        <span id="modalLeadName" style="font-weight: 700; color: #1a1a2e;"></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-envelope text-muted" style="width: 18px;"></i>
                        <a id="modalLeadEmailLink" href="" style="color: var(--primary); font-weight: 700; text-decoration: none;"><span id="modalLeadEmail"></span></a>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-phone text-muted" style="width: 18px;"></i>
                        <a id="modalLeadPhoneLink" href="" style="color: var(--primary); font-weight: 700; text-decoration: none;"><span id="modalLeadPhone"></span></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: #fafafa; padding: 1.25rem 2rem; text-align: right; border-top: 1px solid #eee;">
            <button onclick="closeLeadModal()" class="btn" style="background: var(--primary); color: #fff; font-weight: bold; padding: 0.6rem 2rem; border-radius: 10px; border: none; cursor: pointer; transition: 0.2s;">Close</button>
        </div>
    </div>
</div>

<script>
function handleLeadClick(element) {
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    if (!isAuthenticated) {
        window.location.href = "{{ route('login') }}?redirect=back";
        return;
    }
    
    // Fill lead details into the modal
    document.getElementById('modalLeadCategory').innerText = element.getAttribute('data-category');
    document.getElementById('modalLeadMaterial').innerText = element.getAttribute('data-material');
    document.getElementById('modalLeadQty').innerText = element.getAttribute('data-qty');
    document.getElementById('modalLeadPattern').innerText = element.getAttribute('data-pattern');
    document.getElementById('modalLeadSize').innerText = element.getAttribute('data-size');
    document.getElementById('modalLeadReqs').innerText = element.getAttribute('data-reqs') || 'No additional requirements provided.';
    document.getElementById('modalLeadName').innerText = element.getAttribute('data-name');
    document.getElementById('modalLeadEmail').innerText = element.getAttribute('data-email');
    document.getElementById('modalLeadEmailLink').href = 'mailto:' + element.getAttribute('data-email');
    document.getElementById('modalLeadPhone').innerText = element.getAttribute('data-phone') || 'N/A';
    document.getElementById('modalLeadPhoneLink').href = 'tel:' + element.getAttribute('data-phone');
    document.getElementById('modalLeadDate').innerText = element.getAttribute('data-date');
    
    // Display the modal
    const modal = document.getElementById('leadDetailsModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeLeadModal() {
    const modal = document.getElementById('leadDetailsModal');
    modal.classList.remove('show');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Add CSS classes for modal animation
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.innerHTML = `
        #leadDetailsModal.show { opacity: 1 !important; }
        #leadDetailsModal.show > div { transform: scale(1) !important; }
    `;
    document.head.appendChild(style);
});
</script>
