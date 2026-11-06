@php
    $branchesRaw = $data['branches'] ?? ['Ludhiana', 'Varanasi', 'Jaipur', 'Surat', 'Ichalkaranji', 'Karur'];
    $colCount = 6;
    $columns = [];
    if (count($branchesRaw) >= 18) {
        for ($i = 0; $i < $colCount; $i++) {
            $columns[] = [
                'cluster' => $branchesRaw[$i] ?? ('Cluster ' . ($i + 1)),
                'nodal'   => $branchesRaw[$i + $colCount] ?? 'Nodal Officer (TEFC)',
                'yp'      => $branchesRaw[$i + ($colCount * 2)] ?? 'Young Professional',
            ];
        }
    } elseif (count($branchesRaw) >= 12) {
        for ($i = 0; $i < $colCount; $i++) {
            $columns[] = [
                'cluster' => $branchesRaw[$i] ?? ('Cluster ' . ($i + 1)),
                'nodal'   => $branchesRaw[$i + $colCount] ?? 'Nodal Officer (TEFC)',
                'yp'      => 'Young Professional',
            ];
        }
    } else {
        for ($i = 0; $i < min(count($branchesRaw), $colCount); $i++) {
            $columns[] = [
                'cluster' => $branchesRaw[$i],
                'nodal'   => 'Nodal Officer (TEFC)',
                'yp'      => 'Young Professional',
            ];
        }
        if (empty($columns)) {
            foreach (['Ludhiana', 'Varanasi', 'Jaipur', 'Surat', 'Ichalkaranji', 'Karur'] as $c) {
                $columns[] = [
                    'cluster' => $c,
                    'nodal'   => 'Nodal Officer (TEFC)',
                    'yp'      => 'Young Professional',
                ];
            }
        }
    }
@endphp

<section style="padding: 6rem 0; background: var(--sky2, #fbf9f6); border-top: 1px solid rgba(179, 142, 93, 0.15); border-bottom: 1px solid rgba(179, 142, 93, 0.15);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #6c1340; margin-bottom: 0.5rem;">{{ $title ?? 'Organisational Structure' }}</h2>
            <p style="color: #7d6e5e; font-size: 0.95rem;">{{ $data['tagline'] ?? 'Our administrative and operational framework.' }}</p>
            <div style="width: 60px; height: 4px; background: #b38e5d; margin: 1rem auto 0; border-radius: 2px;"></div>
        </div>
        
        <style>
            .cms-org-wrapper { display: flex; flex-direction: column; align-items: center; width: 100%; }
            .cms-org-node { padding: 1.1rem 2.5rem; border-radius: 12px; font-weight: 800; font-size: 0.92rem; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
            .cms-org-line { width: 2px; height: 32px; background: #b38e5d; margin: 0 auto; }
            .cms-org-tree-outer { width: 100%; overflow-x: auto; padding: 0 0 1.5rem; -webkit-overflow-scrolling: touch; }
            .cms-org-tree-inner { min-width: 960px; max-width: 1200px; margin: 0 auto; }
            .cms-org-branches { display: flex; width: 100%; margin: 0; padding: 0; list-style: none; }
            .cms-org-col { flex: 1 1 0; display: flex; flex-direction: column; align-items: center; position: relative; padding: 0 8px; }
            .cms-col-top-bar { position: relative; width: 100%; height: 28px; }
            .cms-col-top-bar::before { content: ''; position: absolute; top: 0; height: 2px; background: #b38e5d; left: 0; right: 0; }
            .cms-org-col:first-child .cms-col-top-bar::before { left: 50%; }
            .cms-org-col:last-child .cms-col-top-bar::before { right: 50%; }
            .cms-org-col:only-child .cms-col-top-bar::before { display: none; }
            .cms-col-top-bar::after { content: ''; position: absolute; top: 0; left: 50%; width: 2px; height: 100%; background: #b38e5d; transform: translateX(-50%); }
            .cms-node-connector { width: 2px; height: 20px; background: #b38e5d; margin: 0 auto; flex-shrink: 0; }
            .cms-branch-card { background: #fff; border: 1px solid rgba(179, 142, 93, 0.25); border-radius: 10px; padding: 0.85rem 0.6rem; font-size: 0.82rem; font-weight: 700; color: #6c1340; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease; width: 100%; min-height: 52px; display: flex; align-items: center; justify-content: center; line-height: 1.35; }
            .cms-branch-card:hover { border-color: #6c1340; box-shadow: 0 4px 12px rgba(108, 21, 63, 0.08); transform: translateY(-2px); }
            .cms-node-cluster { background: #fff; border-color: rgba(179, 142, 93, 0.4); font-weight: 800; }
            .cms-node-nodal { background: #fdfbf9; border-color: rgba(179, 142, 93, 0.25); }
            .cms-node-yp { background: #fff; border-color: rgba(179, 142, 93, 0.25); color: #554438; }
        </style>

        <div class="cms-org-wrapper">
            <div class="cms-org-node" style="background: #6c1340; color: #fff; border: 1px solid #4a0e2b;">
                {{ $data['level_1'] ?? 'Ministry of Textiles, Govt. of India' }}
            </div>
            <div class="cms-org-line"></div>
            <div class="cms-org-node" style="background: #4a0e2b; color: #fff; border: 1px solid #32091c;">
                {{ $data['level_2'] ?? 'Textiles Committee' }}
            </div>
            <div class="cms-org-line"></div>
            <div class="cms-org-node" style="background: #fff; color: #6c1340; border: 2px solid #b38e5d;">
                {{ $data['level_3'] ?? 'Textiles Export Facilitation Center (TEFC)' }}
            </div>
            <div class="cms-org-line"></div>
            
            <div class="cms-org-tree-outer">
                <div class="cms-org-tree-inner">
                    <div class="cms-org-branches">
                        @foreach($columns as $col)
                            <div class="cms-org-col">
                                <div class="cms-col-top-bar"></div>
                                <div class="cms-branch-card cms-node-cluster">{{ $col['cluster'] }}</div>
                                <div class="cms-node-connector"></div>
                                <div class="cms-branch-card cms-node-nodal">{{ $col['nodal'] }}</div>
                                <div class="cms-node-connector"></div>
                                <div class="cms-branch-card cms-node-yp">{{ $col['yp'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
