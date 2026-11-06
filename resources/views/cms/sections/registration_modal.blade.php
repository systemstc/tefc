<!-- REGISTRATION MODAL -->
<div class="modal-overlay" id="regModal">
  <div class="modal-content" style="max-width: 600px; padding: 4rem 3rem; border-radius: 30px; border: 1px solid var(--border); box-shadow: 0 30px 100px rgba(108, 21, 63, 0.15); overflow: hidden;">
    <!-- Top premium linear accent bar -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(to right, var(--primary) 0%, var(--accent) 100%);"></div>
    
    <button class="modal-close" onclick="closeModal()">&times;</button>
    <div style="font-size: 3.5rem; margin-bottom: 1.5rem; animation: pulse 2s infinite;"><img src="{{asset('images/Textiles_Committee_Logo_2.png')}}" alt="TEFC Logo"></div>
    <!-- <div style="font-size: 3.5rem; margin-bottom: 1.5rem; animation: pulse 2s infinite;">🏛️</div> -->
    
    <h2 style="color: var(--primary); font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $title ?? 'Exporter Registration' }}</h2>
    <p style="color: var(--muted); font-size: 1.05rem; margin-bottom: 2rem;">{{ $data['subtitle'] ?? 'Join India\'s premier textile facilitation network and unlock global trade opportunities.' }}</p>
    
    <!-- CTA Actions -->
    <div style="display: flex; flex-direction: column; gap: 1rem;">
      <a href="{{ $data['btn_url'] ?? route('register', ['role' => 'exporter']) }}" class="btn-reg-now" onclick="localStorage.setItem('tefc_exporter_popup_seen', 'true')" style="display: block; text-decoration: none; text-align: center; font-size: 1.1rem; padding: 1.2rem; border-radius: 14px; background: var(--primary); color: #fff; font-weight: 800; transition: all 0.3s; box-shadow: 0 6px 20px rgba(108, 21, 63, 0.15);">
        {{ $data['btn_text'] ?? 'Register on Portal Now' }}
      </a>
      <button type="button" onclick="closeModal()" style="background: none; border: none; color: var(--muted); font-weight: 700; font-size: 0.95rem; cursor: pointer; padding: 0.5rem; transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--muted)'">
        {{ $data['secondary_text'] ?? 'Maybe Later, Skip to Directory' }}
      </button>
    </div>
  </div>
</div>
