<section style="padding: 8rem 0; background: #fff;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 6rem;">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary);">Key Milestones</h2>
            <p style="color: var(--muted);">A journey of textile excellence and global facilitation.</p>
            <div style="width: 80px; height: 4px; background: var(--accent); margin: 1.5rem auto;"></div>
        </div>
        
        <div style="position: relative;">
            <!-- Center Line -->
            <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 4px; background: var(--sky); transform: translateX(-50%);"></div>
            
            <div class="timeline" style="display: flex; flex-direction: column; gap: 4rem;">
                @foreach($data['milestones'] ?? [] as $index => $item)
                    <div style="display: flex; justify-content: {{ $index % 2 == 0 ? 'flex-start' : 'flex-end' }}; width: 100%; position: relative;">
                        <!-- Dot -->
                        <div style="position: absolute; left: 50%; top: 20px; width: 20px; height: 20px; background: #fff; border: 4px solid var(--primary); border-radius: 50%; transform: translateX(-50%); z-index: 10;"></div>
                        
                        <div style="width: 45%; background: var(--sky2); padding: 2.5rem; border-radius: 25px; border: 1px solid var(--border); position: relative;">
                            <div style="font-weight: 800; color: var(--primary); margin-bottom: 1rem; font-size: 1.4rem;">{{ $item['year'] }}</div>
                            <h4 style="font-weight: 800; color: var(--text); margin-bottom: 0.8rem;">{{ $item['title'] }}</h4>
                            <p class="portal-content-justified" style="color: var(--muted); font-size: 0.95rem; line-height: 1.75; text-align: justify; text-justify: inter-word; hyphens: auto;">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div style="margin-top: 8rem; background: var(--primary); padding: 6rem; border-radius: 40px; text-align: center; color: #fff;">
             <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 2rem;">Want to know more about TEFC? Speak to our team directly.</h2>
             <a href="{{ url('/p/contact') }}" class="btn-premium" style="background: #fff; color: var(--primary); padding: 1.2rem 4rem; border-radius: 50px; font-weight: 800; text-decoration: none;">Contact Us</a>
        </div>
    </div>
</section>
