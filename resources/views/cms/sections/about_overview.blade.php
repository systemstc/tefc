<section class="about-overview" style="padding: 8rem 0; background: #fff;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 6rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 2rem;">
                    <div style="width: 50px; height: 50px; background: var(--sky); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <i class="fas fa-search-plus fa-lg"></i>
                    </div>
                    <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--primary);">Overview</h2>
                </div>
                
                <p class="portal-content-justified" style="font-size: 1.1rem; line-height: 1.85; color: var(--muted); margin-bottom: 3rem; text-align: justify; text-justify: inter-word; hyphens: auto;">
                    {{ $data['description'] ?? '' }}
                </p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    @foreach($data['features'] ?? [] as $feature)
                        <div style="background: var(--sky2); padding: 2rem; border-radius: 20px; border: 1px solid var(--border);">
                            <div style="color: var(--primary); margin-bottom: 1rem;"><i class="{{ $feature['icon'] }} fa-2x"></i></div>
                            <h4 style="font-weight: 800; margin-bottom: 0.5rem; color: var(--primary);">{{ $feature['title'] }}</h4>
                            <p class="portal-content-justified" style="font-size: 0.9rem; color: var(--muted); line-height: 1.65; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div style="background: var(--primary); color: #fff; border-radius: 30px; padding: 3rem; position: relative;">
                <h3 style="font-weight: 800; margin-bottom: 2.5rem; border-bottom: 2px solid var(--accent); padding-bottom: 1rem;">Platform Pulse</h3>
                @foreach($data['pulse_stats'] ?? [] as $pulse)
                    <div style="margin-bottom: 2.5rem;">
                        <div style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.6; margin-bottom: 5px;">{{ $pulse['label'] }}</div>
                        <div style="font-size: 2rem; font-weight: 800;">{{ $pulse['value'] }}</div>
                        <div style="font-size: 0.8rem; color: var(--accent);">{{ $pulse['trend'] ?? '' }}</div>
                    </div>
                @endforeach
                <div style="margin-top: 2rem; background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 15px; font-size: 0.85rem;">
                    <i class="fas fa-info-circle me-2"></i> Statistics verified as of {{ date('M Y') }}
                </div>
            </div>
        </div>
    </div>
</section>
