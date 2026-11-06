<section style="padding: 8rem 0; background: var(--sky2);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 5rem;">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary);">Mission, Vision & Objectives</h2>
            <div style="width: 80px; height: 4px; background: var(--accent); margin: 1.5rem auto;"></div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem;">
            <!-- Mission -->
            <div style="background: #fff; border-radius: 30px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.05); border: 1px solid var(--border);">
                <div style="background: var(--primary); color: #fff; padding: 2rem; text-align: center;">
                    <i class="fas fa-bullseye fa-3x"></i>
                    <h3 style="margin-top: 1rem; font-weight: 800;">Our Mission</h3>
                </div>
                <div style="padding: 2.5rem;">
                    <p class="portal-content-justified" style="color: var(--muted); line-height: 1.85; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $data['mission'] ?? '' }}</p>
                </div>
            </div>
            
            <!-- Vision -->
            <div style="background: #fff; border-radius: 30px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.05); border: 1px solid var(--border);">
                <div style="background: #2b6cb0; color: #fff; padding: 2rem; text-align: center;">
                    <i class="fas fa-eye fa-3x"></i>
                    <h3 style="margin-top: 1rem; font-weight: 800;">Our Vision</h3>
                </div>
                <div style="padding: 2.5rem;">
                    <p class="portal-content-justified" style="color: var(--muted); line-height: 1.85; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $data['vision'] ?? '' }}</p>
                </div>
            </div>
            
            <!-- Objectives -->
            <div style="background: #fff; border-radius: 30px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.05); border: 1px solid var(--border);">
                <div style="background: #2f855a; color: #fff; padding: 2rem; text-align: center;">
                    <i class="fas fa-list-check fa-3x"></i>
                    <h3 style="margin-top: 1rem; font-weight: 800;">Our Objectives</h3>
                </div>
                <div style="padding: 2.5rem;">
                    <ul style="padding-left: 1.2rem; color: var(--muted); line-height: 1.8;">
                        @foreach($data['objectives'] ?? [] as $obj)
                            <li style="margin-bottom: 0.8rem;">{{ $obj }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
