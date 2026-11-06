<section style="padding: 6rem 0; background: #fff;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #6c1340; margin-bottom: 0.5rem;">{{ $title ?? 'Key People in Organisation' }}</h2>
            <p style="color: #7d6e5e; font-size: 0.95rem;">{{ $data['tagline'] ?? 'Guiding the strategic export initiatives of India.' }}</p>
            <div style="width: 60px; height: 4px; background: #b38e5d; margin: 1rem auto 0; border-radius: 2px;"></div>
        </div>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; max-width: 1280px; margin: 0 auto;">
            @foreach($data['people'] ?? [] as $person)
                <div style="flex: 0 1 270px; min-width: 240px; max-width: 285px; text-align: center; background: #fff; border: 1px solid rgba(179, 142, 93, 0.2); border-radius: 16px; padding: 2.2rem 1.4rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                    <div style="width: 150px; height: 150px; background: #fbf9f6; border-radius: 50%; margin: 0 auto 1.3rem; overflow: hidden; border: 4px solid #dfcbb5; display: flex; align-items: center; justify-content: center; color: #6c1340; box-shadow: 0 4px 15px rgba(179, 142, 93, 0.15);">
                        @if(isset($person['image']) && $person['image'])
                            <img src="{{ asset($person['image']) }}" alt="{{ trim($person['name'] ?? '') }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-user-tie fa-3x" style="opacity: 0.85;"></i>
                        @endif
                    </div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #6c1340; margin-bottom: 0.35rem;">{{ trim($person['name'] ?? '') }}</h4>
                    <p style="font-size: 0.78rem; color: #7d6e5e; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.03em;">{{ trim($person['designation'] ?? '') }}</p>
                </div>
            @endforeach
        </div>
        
        @if(!empty($data['footer_about']))
            <div style="margin-top: 4.5rem; background: #fbf9f6; border: 1px solid rgba(179, 142, 93, 0.2); padding: 3rem; border-radius: 16px; display: grid; grid-template-columns: 240px 1fr; gap: 3.5rem; align-items: center;">
                 <div>
                     <h3 style="font-size: 1.8rem; font-weight: 800; color: #6c1340; margin-bottom: 0.5rem;">{{ $data['statutory_title'] ?? 'Statutory Oversight' }}</h3>
                     <div style="width: 50px; height: 4px; background: #b38e5d;"></div>
                 </div>
                 <div>
                     <p class="portal-content-justified" style="color: #7d6e5e; line-height: 1.85; font-size: 1rem; text-align: justify; text-justify: inter-word; hyphens: auto; margin: 0;">
                         {{ $data['footer_about'] }}
                     </p>
                     <a href="#org" style="display: inline-block; margin-top: 1.2rem; color: #6c1340; font-weight: 700; text-decoration: none; border-bottom: 2px solid #b38e5d;">View Team Hierarchy →</a>
                 </div>
            </div>
        @endif
    </div>
</section>
