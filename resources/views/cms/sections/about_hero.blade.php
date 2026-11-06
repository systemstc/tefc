<section class="about-hero" style="background: var(--primary); color: #fff; padding: 6rem 0; position: relative; overflow: hidden;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 5rem; align-items: center;">
            <div>
                <nav style="margin-bottom: 1rem; font-size: 0.9rem; opacity: 0.8; background: none; padding: 0;">
                    Home • About TEFC
                </nav>
                <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem;">{{ $title }}</h1>
                <p style="font-size: 1.2rem; line-height: 1.6; opacity: 0.9; margin-bottom: 2.5rem; max-width: 700px;">
                    {{ $data['subtitle'] ?? '' }}
                </p>
                <div style="display: flex; gap: 1.5rem;">
                    <a href="{{ url('/register') }}" class="btn-premium" style="background: var(--accent); color: var(--primary); padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700;">Registration</a>
                    <a href="{{ url('/catalogue') }}" class="btn-premium" style="background: transparent; border: 2px solid rgba(255,255,255,0.3); color: #fff; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700;">View Catalogue</a>
                </div>
            </div>
            
            <div style="background: rgba(0,0,0,0.2); backdrop-filter: blur(10px); padding: 2.5rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
                @foreach($data['side_stats'] ?? [] as $stat)
                    <div style="margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1.5rem;">
                        <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent);">{{ $stat['value'] }}</div>
                        <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7;">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
