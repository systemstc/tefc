@extends('layouts.app')

@section('title', 'FTA Benefits — TEFC')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ── DESIGN SYSTEM & CONSTANTS ── */
:root {
  --primary-burgundy: #6c1340;
  --primary-burgundy-dark: #4a0e2b;
  --accent-gold: #b38e5d;
  --bg-light: #fbf9f6;
  --border-color: #ebdcb9;
  --text-dark: #2d2013;
  --text-muted: #7d6e5e;
}

body {
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  background: var(--bg-light);
  color: var(--text-dark);
}

/* ── HERO BANNER ── */
.fta-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4rem 2.5rem;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.fta-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.fta-hero-inner {
  position: relative;
  z-index: 2;
  max-width: 1200px;
  margin: 0 auto;
}
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1.2rem;
  font-size: 0.8rem;
  opacity: 0.8;
}
.breadcrumb a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.breadcrumb a:hover {
  color: var(--accent-gold);
}
.fta-hero h1 {
  font-size: 2.6rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.fta-hero p {
  font-size: 1rem;
  opacity: 0.9;
  max-width: 800px;
  line-height: 1.6;
  margin-bottom: 2rem;
}
.fta-pills-wrap {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}
.fta-hero-pill {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #fff;
  padding: 0.5rem 1.2rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}
.fta-hero-pill i {
  color: var(--accent-gold);
}

/* ── STATS STRIP ── */
.stats-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1px;
  background: var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  margin-bottom: 3rem;
  border: 1px solid var(--border-color);
}
.stat-cell {
  background: #fff;
  padding: 1.5rem;
  text-align: center;
}
.stat-cell h3 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.4rem;
}
.stat-cell p {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.stat-cell span.subtext {
  font-size: 0.72rem;
  color: #1e7e44;
  font-weight: 600;
  display: block;
  margin-top: 4px;
}

/* ── SECTION STYLING ── */
.fta-section {
  padding: 3rem 0;
}
.section-title-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1.8rem;
  border-bottom: 2px solid var(--border-color);
  padding-bottom: 1rem;
}
.section-icon {
  color: var(--primary-burgundy);
  font-size: 1.5rem;
}
.fta-section h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0;
}
.section-subtitle {
  font-size: 0.88rem;
  color: var(--text-muted);
  margin-top: -1.2rem;
  margin-bottom: 2rem;
}

/* ── WHY LEVERAGE FTA BENEFITS ── */
.benefits-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}
.benefit-card {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 1.8rem;
  display: flex;
  gap: 1.2rem;
  transition: all 0.3s ease;
}
.benefit-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(108, 21, 63, 0.06);
  border-color: var(--primary-burgundy);
}
.benefit-icon-badge {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: #fbf1f5;
  color: var(--primary-burgundy);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.benefit-content h4 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin-bottom: 0.6rem;
}
.benefit-content p {
  font-size: 0.8rem;
  color: var(--text-dark);
  line-height: 1.65;
  margin: 0;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}

/* ── GOVERNMENT INCENTIVE SCHEMES ── */
.filter-header-wrap {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.8rem;
  flex-wrap: wrap;
  gap: 1rem;
}
.filter-tabs {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}
.filter-tab {
  background: #fff;
  border: 1px solid var(--border-color);
  color: var(--text-muted);
  padding: 0.45rem 1.2rem;
  border-radius: 30px;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}
.filter-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.filter-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
}
.btn-view-all-link {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--primary-burgundy);
  text-decoration: none;
  transition: color 0.2s;
}
.btn-view-all-link:hover {
  color: var(--primary-burgundy-dark);
}

.schemes-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}
.scheme-card {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: all 0.3s ease;
}
.scheme-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(108, 21, 63, 0.08);
  border-color: var(--primary-burgundy);
}
.scheme-card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}
.scheme-card-header h3 {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin: 0;
}
.badge-active {
  background: #eafaf1;
  color: #1e7e44;
  border: 1px solid #b2f5ea;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 12px;
}
.scheme-desc {
  font-size: 0.72rem;
  color: var(--text-muted);
  margin-bottom: 1.2rem;
}
.scheme-rate-highlight {
  background: #fbf1f5;
  border: 1px solid var(--border-color);
  padding: 0.8rem 1rem;
  border-radius: 8px;
  margin-bottom: 1.2rem;
}
.scheme-rate-highlight strong {
  display: block;
  font-size: 1.1rem;
  color: var(--primary-burgundy);
  font-weight: 800;
}
.scheme-rate-highlight span {
  font-size: 0.72rem;
  color: var(--text-dark);
}
.scheme-card-body ul {
  list-style: none;
  padding: 0;
  margin: 0 0 1.5rem 0;
}
.scheme-card-body li {
  font-size: 0.78rem;
  color: var(--text-dark);
  line-height: 1.5;
  margin-bottom: 0.6rem;
  display: flex;
  gap: 8px;
}
.scheme-card-body li i {
  color: var(--primary-burgundy);
  font-size: 0.65rem;
  margin-top: 5px;
  flex-shrink: 0;
}
.scheme-eligibility {
  border-top: 1px solid rgba(179, 142, 93, 0.15);
  padding-top: 1rem;
  margin-bottom: 1.5rem;
}
.scheme-eligibility strong {
  font-size: 0.72rem;
  text-transform: uppercase;
  color: var(--text-muted);
  display: block;
  margin-bottom: 2px;
}
.scheme-eligibility p {
  font-size: 0.78rem;
  color: var(--text-dark);
  margin: 0;
  line-height: 1.5;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.scheme-actions {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 8px;
}
.btn-scheme-apply {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.55rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  text-decoration: none;
  text-align: center;
  transition: background 0.2s;
}
.btn-scheme-apply:hover {
  background: var(--primary-burgundy-dark);
  color: #fff;
}
.btn-scheme-details {
  background: transparent;
  border: 1px solid var(--border-color);
  color: var(--primary-burgundy);
  padding: 0.55rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  text-decoration: none;
  text-align: center;
  transition: all 0.2s;
}
.btn-scheme-details:hover {
  background: var(--primary-burgundy);
  color: #fff;
  border-color: var(--primary-burgundy);
}

.caution-note-box {
  background: #fffaf0;
  border: 1px solid #fbd38d;
  color: #744210;
  padding: 1.2rem;
  border-radius: 8px;
  font-size: 0.8rem;
  line-height: 1.6;
  margin-top: 2rem;
  display: flex;
  gap: 12px;
  align-items: flex-start;
}
.caution-note-box i {
  font-size: 1.1rem;
  margin-top: 2px;
}

/* ── AGREEMENTS TABLE ── */
.agreements-table-wrap {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
}
.agreements-table {
  width: 100%;
  border-collapse: collapse;
}
.agreements-table th {
  background: #fbf1f5;
  color: var(--primary-burgundy);
  font-weight: 700;
  font-size: 0.8rem;
  text-transform: uppercase;
  padding: 1rem 1.5rem;
  border-bottom: 2px solid var(--border-color);
  text-align: left;
}
.agreements-table td {
  padding: 1.1rem 1.5rem;
  font-size: 0.82rem;
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
  color: var(--text-dark);
}
.badge-status-inforce {
  background: #eafaf1;
  color: #1e7e44;
  border: 1px solid #c6f6d5;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 4px;
}
.badge-status-signed {
  background: #ebf8ff;
  color: #2b6cb0;
  border: 1px solid #bee3f8;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 4px;
}
.badge-status-negotiating {
  background: #fffaf0;
  color: #dd6b20;
  border: 1px solid #feebc8;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 4px;
}
.btn-agreement-action {
  font-weight: 700;
  font-size: 0.78rem;
  color: var(--primary-burgundy);
  text-decoration: none;
  transition: color 0.2s;
}
.btn-agreement-action:hover {
  color: var(--primary-burgundy-dark);
}

/* ── COO STEPS ── */
.coo-steps-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 2.5rem;
  position: relative;
}
.coo-step-card {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 1.8rem;
  text-align: center;
  position: relative;
  transition: all 0.3s ease;
}
.coo-step-card:hover {
  transform: translateY(-4px);
  border-color: var(--primary-burgundy);
}
.coo-step-number {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--primary-burgundy);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 0.9rem;
  margin: 0 auto 1.2rem auto;
}
.coo-step-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin-bottom: 0.6rem;
}
.coo-step-card p {
  font-size: 0.78rem;
  color: var(--text-muted);
  line-height: 1.5;
  margin: 0;
}
.coo-step-arrow {
  position: absolute;
  top: 50%;
  right: -18px;
  transform: translateY(-50%);
  color: var(--border-color);
  font-size: 1.2rem;
  z-index: 5;
}

.coo-action-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}
.btn-coo-action {
  background: transparent;
  border: 1px solid var(--border-color);
  color: var(--primary-burgundy);
  padding: 0.6rem 1.6rem;
  border-radius: 30px;
  font-size: 0.8rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-coo-action.primary {
  background: var(--primary-burgundy);
  color: #fff;
  border-color: var(--primary-burgundy);
}
.btn-coo-action:hover {
  background: var(--primary-burgundy);
  color: #fff;
  border-color: var(--primary-burgundy);
}

/* ── CALLOUT BANNER ── */
.callout-counselor-banner {
  background: var(--primary-burgundy-dark);
  color: #fff;
  border: 2px solid var(--accent-gold);
  border-radius: 12px;
  padding: 2rem 2.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.5rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  margin-top: 3rem;
}
.callout-counselor-banner h3 {
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0;
  max-width: 600px;
  line-height: 1.5;
}
.btn-counselor {
  background: #fff;
  color: var(--primary-burgundy-dark);
  border: none;
  padding: 0.7rem 2rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.btn-counselor:hover {
  background: var(--accent-gold-light);
  color: var(--primary-burgundy-dark);
}

/* ── RESPONSIVE DESIGN ── */
@media (max-width: 1024px) {
  .benefits-grid { grid-template-columns: repeat(2, 1fr); }
  .schemes-grid { grid-template-columns: repeat(2, 1fr); }
  .coo-steps-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem; }
  .coo-step-arrow { display: none; }
  .stats-strip { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .benefits-grid { grid-template-columns: 1fr; }
  .schemes-grid { grid-template-columns: 1fr; }
  .coo-steps-grid { grid-template-columns: 1fr; }
  .stats-strip { grid-template-columns: 1fr; }
  .fta-hero h1 { font-size: 2.1rem; }
  .callout-counselor-banner { flex-direction: column; text-align: center; gap: 1.2rem; }
  .agreements-table-wrap { overflow-x: auto; }
  .agreements-table { min-width: 800px; }
}
</style>
@endsection

@section('content')
@php
  $heroSection = isset($page) && $page->sections ? $page->sections->where('type', 'fta_hero')->first() : null;
  $heroTitle = $heroSection ? $heroSection->title : 'FTA & Trade Agreement Benefits';
  $heroSubtitle = $heroSection && isset($heroSection->content['subtitle']) 
    ? $heroSection->content['subtitle'] 
    : 'Leverage India\'s Free Trade Agreements, Preferential Trade Agreements and Government incentive schemes to maximize your textile export competitiveness and access global markets with reduced duties.';
@endphp
<div class="fta-hero">
  <div class="fta-hero-inner">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span class="sep">›</span> <span>FTA Benefits</span>
    </div>
    <h1>{{ $heroTitle }}</h1>
    <p>{{ $heroSubtitle }}</p>
    
    <div class="fta-pills-wrap">
      <span class="fta-hero-pill"><i class="fas fa-check-circle"></i> 16 Active FTAs / PTAs</span>
      <span class="fta-hero-pill"><i class="fas fa-globe"></i> 58+ Partner Countries</span>
      <span class="fta-hero-pill"><i class="fas fa-bullseye"></i> ₹4,200 Cr Export Target</span>
    </div>
  </div>
</div>

<div class="container my-5">

  <!-- SECTION 1: STATISTICS BAR -->
  <div class="stats-strip">
    <div class="stat-cell">
      <h3>16</h3>
      <p>Active FTAs & PTAs</p>
      <span class="subtext">+ 2 more in 2025-26</span>
    </div>
    <div class="stat-cell">
      <h3>58+</h3>
      <p>Partner Countries</p>
      <span class="subtext">Across 5 continents</span>
    </div>
    <div class="stat-cell">
      <h3>₹4,200 Cr</h3>
      <p>Projected Export Growth FY26</p>
      <span class="subtext">+ 85% YoY growth</span>
    </div>
    <div class="stat-cell">
      <h3>6</h3>
      <p>Government Incentive Schemes</p>
      <span class="subtext">Currently active</span>
    </div>
  </div>

  <!-- SECTION 2: WHY LEVERAGE FTA BENEFITS -->
  <section class="fta-section">
    <div class="section-title-wrap">
      <div class="section-icon"><i class="fas fa-question-circle"></i></div>
      <h2>Why Leverage FTA Benefits</h2>
    </div>
    <div class="section-subtitle">How FTAs and government schemes directly improve your export margins and market access</div>

    <div class="benefits-grid">
      <!-- Card 1 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-percentage"></i></div>
        <div class="benefit-content">
          <h4>Reduced Duty Barrier</h4>
          <p>FTAs preferential rates cut import duties from 12-15% to near zero in key markets, directly improving your price competitiveness against rivals from non-FTA countries.</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-expand-arrows-alt"></i></div>
        <div class="benefit-content">
          <h4>Expanded Market Access</h4>
          <p>Access 58+ countries across Asia, Europe, Africa & the Middle East with preferential terms, allowing you to diversify your buyer base and reduce dependency on any single market.</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-shipping-fast"></i></div>
        <div class="benefit-content">
          <h4>Faster Customs Clearance</h4>
          <p>FTA shipments with valid CoO documentation experience streamlined customs processing in partner countries, reducing transit time and demurrage charges.</p>
        </div>
      </div>
      <!-- Card 4 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-layer-group"></i></div>
        <div class="benefit-content">
          <h4>Stack Multiple Benefits</h4>
          <p>Combine FTA duty advantages with RoDTEP, RoSCTL, and other export incentive schemes for maximum margin improvement — subject to DGFT tracking guidelines.</p>
        </div>
      </div>
      <!-- Card 5 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-flag"></i></div>
        <div class="benefit-content">
          <h4>Brand India Advantage</h4>
          <p>CoO documentation certifies Indian origin, allowing buyers to promote "Made in India" — a growing preference in EU, US, and Australian markets for ethical sourcing.</p>
        </div>
      </div>
      <!-- Card 6 -->
      <div class="benefit-card">
        <div class="benefit-icon-badge"><i class="fas fa-info-circle"></i></div>
        <div class="benefit-content">
          <h4>TEFC Advisory Support</h4>
          <p>Our cluster-based export counselors help you identify applicable FTAs, navigate HS classification, prepare CoO documentation and file scheme applications without cost.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SECTION 3: GOVERNMENT INCENTIVE SCHEMES -->
  <section class="fta-section">
    <div class="section-title-wrap" style="border:none; margin-bottom: 0.5rem;">
      <div class="section-icon"><i class="fas fa-award"></i></div>
      <h2>Government Incentive Schemes</h2>
    </div>
    <div class="section-subtitle">Financial and non-financial support programmes available to textile exporters</div>

    <div class="filter-header-wrap">
      <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterSchemes('all', this)">All Schemes</button>
        <button class="filter-tab" onclick="filterSchemes('export', this)">Export Incentives</button>
        <button class="filter-tab" onclick="filterSchemes('capital', this)">Capital Subsidy</button>
        <button class="filter-tab" onclick="filterSchemes('pli', this)">Production-Linked</button>
        <button class="filter-tab" onclick="filterSchemes('msme', this)">MSME Focus</button>
      </div>
      <a href="https://www.dgft.gov.in" target="_blank" class="btn-view-all-link">View All Schemes <i class="fas fa-chevron-right"></i></a>
    </div>

    <div class="schemes-grid">
      <!-- Card 1: RoDTEP -->
      <div class="scheme-card filter-scheme export">
        <div>
          <div class="scheme-card-header">
            <h3>RoDTEP</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">Remission of Duties & Taxes on Exported Products</p>
          <div class="scheme-rate-highlight">
            <strong>0.5% - 4.3%</strong>
            <span>Rebate rate on FOB export value via transferable duty credit scrips</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> Refund of non-refunded Central, State and local taxes</li>
              <li><i class="fas fa-check"></i> Duty credit scrips issued to be exchanged</li>
              <li><i class="fas fa-check"></i> Covers 40+ chapters & 10,000+ textile categories</li>
              <li><i class="fas fa-check"></i> No cap on exporter benefit amount</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>All textile exporters with valid IEC and GST registration</p>
          </div>
          <div class="scheme-actions">
            <a href="https://www.dgft.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>

      <!-- Card 2: PLI Scheme -->
      <div class="scheme-card filter-scheme pli">
        <div>
          <div class="scheme-card-header">
            <h3>PLI Scheme</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">Production Linked Incentive for Textiles</p>
          <div class="scheme-rate-highlight">
            <strong>Up to ₹10 Cr</strong>
            <span>Annual incentives on incremental production over base year</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> 10% incentives on incremental sales (Years 1-5)</li>
              <li><i class="fas fa-check"></i> Covers Manmade Fibres (MMF), Technical Textiles</li>
              <li><i class="fas fa-check"></i> Minimum investment thresholds: ₹300 Cr (Part 1) / ₹100 Cr (Part 2)</li>
              <li><i class="fas fa-check"></i> 5-year incentive period from commencement year</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>MMF & Technical Textile manufacturers meeting minimum investment criteria</p>
          </div>
          <div class="scheme-actions">
            <a href="https://www.dgft.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>

      <!-- Card 3: TUFS -->
      <div class="scheme-card filter-scheme capital">
        <div>
          <div class="scheme-card-header">
            <h3>TUFS</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">Technology Upgradation Fund Scheme</p>
          <div class="scheme-rate-highlight">
            <strong>15% Subsidy</strong>
            <span>Capital investment incentive for machinery upgradation</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> Capital subsidy on new textile machinery purchase</li>
              <li><i class="fas fa-check"></i> Covers weaving, processing, garmenting & technical textiles</li>
              <li><i class="fas fa-check"></i> Additional 10% for SC/ST, women-owned & MSME segments</li>
              <li><i class="fas fa-check"></i> Interest reimbursement on term loans up to ₹5 Cr p.a.</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>Weaving and upgrading garmenting units</p>
          </div>
          <div class="scheme-actions">
            <a href="https://www.dgft.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>

      <!-- Card 4: RoSCTL -->
      <div class="scheme-card filter-scheme export">
        <div>
          <div class="scheme-card-header">
            <h3>RoSCTL</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">Rebate of State and Central Taxes & Levies</p>
          <div class="scheme-rate-highlight">
            <strong>0.5% - 8.2%</strong>
            <span>Rebate rate on FOB value for apparel & madeups</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> Specific to apparel (HS 61 & 62) & made-ups (HS 63)</li>
              <li><i class="fas fa-check"></i> Remission of SGST, stamp duty, electricity duty & other levies</li>
              <li><i class="fas fa-check"></i> Issued as transferable scrips via ICEGATE portal</li>
              <li><i class="fas fa-check"></i> Higher rates for higher-value added garments</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>Garment & made-up exporters with valid IEC; excludes SEZ units claiming other benefits</p>
          </div>
          <div class="scheme-actions">
            <a href="https://www.dgft.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>

      <!-- Card 5: PMS/DP -->
      <div class="scheme-card filter-scheme msme capital">
        <div>
          <div class="scheme-card-header">
            <h3>PMS/DP</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">PM Development Demonstration Programme</p>
          <div class="scheme-rate-highlight">
            <strong>15% - 35%</strong>
            <span>Margin money subsidy on project cost of setup/expansion</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> Subsidy up to ₹25 Lakh for manufacturing units</li>
              <li><i class="fas fa-check"></i> 35% for rural, SC/ST, women-led units, 25% urban</li>
              <li><i class="fas fa-check"></i> Applicable for handlooms, khadi & small garment units</li>
              <li><i class="fas fa-check"></i> Back-ended credit program via nationalized banks</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>Individuals, SHGs, self-help groups, registered societies, cooperative societies & artisans</p>
          </div>
          <div class="scheme-actions">
            <a href="https://www.dgft.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>

      <!-- Card 6: SFURTI -->
      <div class="scheme-card filter-scheme msme">
        <div>
          <div class="scheme-card-header">
            <h3>SFURTI</h3>
            <span class="badge-active">Active</span>
          </div>
          <p class="scheme-desc">Scheme of Fund for Regeneration of Traditional Industries</p>
          <div class="scheme-rate-highlight">
            <strong>₹2 - 5 Cr</strong>
            <span>Grant per cluster for capacity building & infrastructure</span>
          </div>
          <div class="scheme-card-body">
            <ul>
              <li><i class="fas fa-check"></i> Cluster development for handloom, silk & jute sectors</li>
              <li><i class="fas fa-check"></i> Common facility centers, design & testing labs</li>
              <li><i class="fas fa-check"></i> Training & skill development for artisan clusters</li>
              <li><i class="fas fa-check"></i> Market linkage & branding support for traditional textiles</li>
            </ul>
          </div>
        </div>
        <div>
          <div class="scheme-eligibility">
            <strong>Eligibility</strong>
            <p>Clusters with min. 500 artisans in traditional textile sectors, institutional implementing agencies</p>
          </div>
          <div class="scheme-actions">
            <a href="https://samadhaan.msme.gov.in" target="_blank" class="btn-scheme-apply">Apply Online</a>
            <a href="{{ url('/p/help-support') }}" class="btn-scheme-details">View Details</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Note Box -->
    <div class="caution-note-box">
      <i class="fas fa-info-circle text-warning"></i>
      <div>
        <strong>Note:</strong> Scheme rates and eligibility criteria are subject to periodic revision by the Ministry of Textiles and DGFT. Exporters are advised to verify current rates on the official DGFT portal (dgft.gov.in) or contact their nearest TEFC cluster office before filing applications. TEFC does not guarantee approval of scheme benefits.
      </div>
    </div>
  </section>

  <!-- SECTION 4: FREE TRADE & PREFERENTIAL TRADE AGREEMENTS -->
  <section class="fta-section">
    <div class="section-title-wrap" style="border:none; margin-bottom: 0.5rem;">
      <div class="section-icon"><i class="fas fa-file-contract"></i></div>
      <h2>Free Trade & Preferential Trade Agreements</h2>
    </div>
    <div class="section-subtitle">India's trade agreements with textile duty benefits for exporters</div>

    <div class="filter-header-wrap">
      <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterAgreements('all', this)">All</button>
        <button class="filter-tab" onclick="filterAgreements('inforce', this)">In Force</button>
        <button class="filter-tab" onclick="filterAgreements('signed', this)">Signed</button>
        <button class="filter-tab" onclick="filterAgreements('negotiating', this)">Under Negotiation</button>
        <button class="filter-tab" onclick="filterAgreements('asiapacific', this)">Asia-Pacific</button>
        <button class="filter-tab" onclick="filterAgreements('europe', this)">Europe</button>
        <button class="filter-tab" onclick="filterAgreements('americas', this)">Americas</button>
        <button class="filter-tab" onclick="filterAgreements('middleeast', this)">Middle East & Africa</button>
      </div>
      <a href="{{ url('/resources') }}" class="btn-view-all-link">Download Full List (PDF) <i class="fas fa-chevron-right"></i></a>
    </div>

    <div class="agreements-table-wrap">
      <table class="agreements-table">
        <thead>
          <tr>
            <th>Agreement</th>
            <th>Partner Country</th>
            <th>Status</th>
            <th>Textile Coverage</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Row 1 -->
          <tr class="agreement-row inforce asiapacific middleeast">
            <td><strong>India-UAE CEPA</strong></td>
            <td>UAE</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Zero duty on apparel & made-ups; 5% on fabrics (phased)</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 2 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>India-Australia ECTA</strong></td>
            <td>Australia</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Zero duty on imports of all textile lines immediate</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 3 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>SAFTA</strong></td>
            <td>Sri Lanka, Bangladesh, etc.</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Preferential duties on selected textile categories</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 4 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>India-ASEAN FTA</strong></td>
            <td>Singapore, Vietnam, etc.</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Phased tariff reduction — most textile lines 0-5%</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 5 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>India-Japan CEPA</strong></td>
            <td>Japan</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Zero duty on most textile categories</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 6 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>India-Korea CEPA</strong></td>
            <td>South Korea</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Significant tariff reduction on yarns & parts</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 7 -->
          <tr class="agreement-row negotiating europe">
            <td><strong>India-EU BTIA</strong></td>
            <td>Germany, France, etc.</td>
            <td><span class="badge-status-negotiating">Negotiating</span></td>
            <td>Expected: Zero duty on yarn & textile lines</td>
            <td><a href="{{ url('/p/help-support') }}" class="btn-agreement-action">Trade Progress</a></td>
          </tr>
          <!-- Row 8 -->
          <tr class="agreement-row signed europe">
            <td><strong>India-UK FTA</strong></td>
            <td>UK</td>
            <td><span class="badge-status-signed">Signed</span></td>
            <td>Zero duty phased over 3 years — all textile chapters</td>
            <td><a href="{{ url('/p/help-support') }}" class="btn-agreement-action">Trade Progress</a></td>
          </tr>
          <!-- Row 9 -->
          <tr class="agreement-row negotiating americas">
            <td><strong>India-Canada CEPA</strong></td>
            <td>Canada</td>
            <td><span class="badge-status-negotiating">Negotiating</span></td>
            <td>Expected: Significant reduction on apparel duties</td>
            <td><a href="{{ url('/p/help-support') }}" class="btn-agreement-action">Trade Progress</a></td>
          </tr>
          <!-- Row 10 -->
          <tr class="agreement-row negotiating middleeast">
            <td><strong>India-GCC FTA</strong></td>
            <td>Saudi Arabia, Kuwait, etc.</td>
            <td><span class="badge-status-negotiating">Negotiating</span></td>
            <td>Expected: 0% tariff for textile exports to GCC</td>
            <td><a href="{{ url('/p/help-support') }}" class="btn-agreement-action">Trade Progress</a></td>
          </tr>
          <!-- Row 11 -->
          <tr class="agreement-row inforce middleeast">
            <td><strong>India-Mauritius CECPA</strong></td>
            <td>Mauritius</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Preferential access for yarn, fabric & apparel categories</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
          <!-- Row 12 -->
          <tr class="agreement-row inforce asiapacific">
            <td><strong>India-Thailand FTA</strong></td>
            <td>Thailand</td>
            <td><span class="badge-status-inforce">In-Force</span></td>
            <td>Elimination of synthetic fibres & garment categories</td>
            <td><a href="https://www.dgft.gov.in" target="_blank" class="btn-agreement-action">View Schedule</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- SECTION 5: CERTIFICATE OF ORIGIN (COO) — HOW TO APPLY -->
  <section class="fta-section">
    <div class="section-title-wrap">
      <div class="section-icon"><i class="fas fa-file-invoice"></i></div>
      <h2>Certificate of Origin (CoO) — How to Apply</h2>
    </div>
    <div class="section-subtitle">CoO is mandatory to claim preferential status under all FTAs. The Textiles Committee is the authorized issuing body for textile CoOs.</div>

    <div class="coo-steps-grid">
      <!-- Step 1 -->
      <div class="coo-step-card">
        <div class="coo-step-number">1</div>
        <h4>Register on DGFT Portal</h4>
        <p>Create your exporter account on coo.dgft.gov.in with IEC, GST and bank details.</p>
        <div class="coo-step-arrow"><i class="fas fa-chevron-right"></i></div>
      </div>
      <!-- Step 2 -->
      <div class="coo-step-card">
        <div class="coo-step-number">2</div>
        <h4>Submit Shipment Details</h4>
        <p>Enter invoice, packing list, HS code, buyer details and target country PTA.</p>
        <div class="coo-step-arrow"><i class="fas fa-chevron-right"></i></div>
      </div>
      <!-- Step 3 -->
      <div class="coo-step-card">
        <div class="coo-step-number">3</div>
        <h4>Document Verification</h4>
        <p>TEFC / TC officer verifies submissions within 1-2 working days. Physical inspection may apply.</p>
        <div class="coo-step-arrow"><i class="fas fa-chevron-right"></i></div>
      </div>
      <!-- Step 4 -->
      <div class="coo-step-card">
        <div class="coo-step-number">4</div>
        <h4>CoO Issuance</h4>
        <p>Digitally signed CoO document is emailed for custom clearance. Also physical (paper) copy required.</p>
      </div>
    </div>

    <div class="coo-action-buttons">
      <a href="https://coo.dgft.gov.in" target="_blank" class="btn-coo-action primary">Apply for CoO Online</a>
      <a href="https://coo.dgft.gov.in" target="_blank" class="btn-coo-action">Track CoO Application</a>
      <a href="{{ url('/resources') }}" class="btn-coo-action">Download CoO Form</a>
      <a href="{{ url('/p/help-support') }}" class="btn-coo-action">Locate Nearest TEFC Office</a>
    </div>
  </section>

  <!-- COUNSELOR CALLOUT BANNER -->
  <div class="callout-counselor-banner">
    <h3>Need help identifying which FTA or scheme applies to your products?</h3>
    <a href="{{ url('/p/help-support') }}" class="btn-counselor">Speak to an Export Counselor</a>
  </div>

</div>
@endsection

@section('scripts')
<script>
// ── SCHEMES FILTER FUNCTION ──
function filterSchemes(category, btnElement) {
  // Update filter tabs classes
  const parent = btnElement.parentElement;
  parent.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  btnElement.classList.add('active');

  // Filter scheme cards
  const cards = document.querySelectorAll('.filter-scheme');
  cards.forEach(card => {
    if (category === 'all' || card.classList.contains(category)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

// ── AGREEMENTS FILTER FUNCTION ──
function filterAgreements(category, btnElement) {
  // Update filter tabs classes
  const parent = btnElement.parentElement;
  parent.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  btnElement.classList.add('active');

  // Filter agreement table rows
  const rows = document.querySelectorAll('.agreement-row');
  rows.forEach(row => {
    if (category === 'all' || row.classList.contains(category)) {
      row.style.display = 'table-row';
    } else {
      row.style.display = 'none';
    }
  });
}
</script>
@endsection
