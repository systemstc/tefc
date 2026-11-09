@extends('layouts.app')

@section('title', 'Online Services — TEFC')

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
.services-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4rem 2.5rem;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.services-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.services-hero-inner {
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
.breadcrumb span.sep {
  opacity: 0.6;
}
.services-hero h1 {
  font-size: 2.6rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
  color: #fff;
}
.services-hero p {
  font-size: 1rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin: 0;
}

/* ── SECTION STYLING ── */
.services-section {
  padding: 3rem 0;
}
.section-title-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 2rem;
  border-bottom: 2px solid var(--border-color);
  padding-bottom: 1rem;
}
.section-icon {
  color: var(--primary-burgundy);
  font-size: 1.5rem;
}
.services-section h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0;
}
.section-subtitle {
  font-size: 0.88rem;
  color: var(--text-muted);
  margin-top: -1.5rem;
  margin-bottom: 2rem;
}

/* ── TEFC DIGITAL SERVICES ── */
.digital-services-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}
.digital-card {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
}
.digital-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(108, 21, 63, 0.08);
  border-color: var(--primary-burgundy);
}
.digital-card-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
.icon-badge {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}
.badge-burgundy { background: #fbf1f5; color: var(--primary-burgundy); }
.badge-gold { background: #fff9e6; color: #d49c02; }
.badge-green { background: #eafaf1; color: #1e7e44; }
.badge-blue { background: #eaf4fa; color: #1464a0; }
.badge-purple { background: #f3e8ff; color: #7c3aed; }

.digital-card-header h3 {
  font-size: 1.05rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin: 0;
}
.digital-card-body ul {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem 0;
}
.digital-card-body li {
  font-size: 0.8rem;
  color: var(--text-dark);
  line-height: 1.6;
  margin-bottom: 0.8rem;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}
.digital-card-body li i {
  color: var(--primary-burgundy);
  font-size: 0.7rem;
  margin-top: 4px;
  flex-shrink: 0;
}
.btn-know-more {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.6rem 1.4rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  text-decoration: none;
  text-align: center;
  transition: background 0.2s;
  display: inline-block;
  align-self: flex-start;
}
.btn-know-more:hover {
  background: var(--primary-burgundy-dark);
  color: #fff;
}

/* ── EXTERNAL GOVERNMENT PORTALS ── */
.external-portals-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.2rem;
}
.portal-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.18);
  border-radius: 12px;
  padding: 1.5rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  transition: all 0.3s ease;
}
.portal-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.02);
  border-color: var(--primary-burgundy);
}
.portal-card .icon-badge {
  margin-bottom: 1rem;
}
.portal-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin-bottom: 0.6rem;
}
.portal-card p {
  font-size: 0.75rem;
  color: var(--text-muted);
  line-height: 1.5;
  margin-bottom: 1.2rem;
  min-height: 48px;
}
.btn-visit-portal {
  background: transparent;
  border: 1px solid var(--border-color);
  color: var(--primary-burgundy);
  padding: 0.5rem 1.2rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}
.btn-visit-portal:hover {
  background: var(--primary-burgundy);
  color: #fff;
  border-color: var(--primary-burgundy);
}

/* ── RECENTLY LAUNCHED SERVICES ── */
.recent-services-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}
.recent-card {
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 1.8rem;
  display: flex;
  gap: 1.2rem;
  align-items: flex-start;
  transition: all 0.3s ease;
}
.recent-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(108, 21, 63, 0.05);
  border-color: var(--primary-burgundy);
}
.recent-card-content {
  flex: 1;
}
.recent-card-header {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 0.8rem;
}
.recent-card-header h4 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin: 0;
}
.badge-new {
  background: #1464a0;
  color: #fff;
  font-size: 0.62rem;
  font-weight: 800;
  padding: 2px 6px;
  border-radius: 4px;
  text-transform: uppercase;
}
.recent-card p {
  font-size: 0.78rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 1.2rem;
}
.btn-launch-tool {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.55rem 1.4rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  text-decoration: none;
  display: inline-block;
  transition: background 0.2s;
}
.btn-launch-tool:hover {
  background: var(--primary-burgundy-dark);
  color: #fff;
}

/* ── ASSISTANCE STRIP ── */
.assistance-strip {
  background: var(--primary-burgundy);
  color: #fff;
  padding: 1.5rem 2.5rem;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2rem;
  box-shadow: 0 4px 15px rgba(108, 21, 63, 0.1);
}
.assistance-strip h3 {
  font-size: 1.15rem;
  font-weight: 700;
  margin: 0;
}
.btn-assistance {
  background: #fff;
  color: var(--primary-burgundy);
  border: none;
  padding: 0.6rem 1.8rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-assistance:hover {
  background: var(--bg-light);
  color: var(--primary-burgundy-dark);
}

/* ── RESPONSIVE DESIGN ── */
@media (max-width: 1024px) {
  .digital-services-grid { grid-template-columns: repeat(2, 1fr); }
  .external-portals-grid { grid-template-columns: repeat(2, 1fr); }
  .recent-services-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .digital-services-grid { grid-template-columns: 1fr; }
  .external-portals-grid { grid-template-columns: 1fr; }
  .assistance-strip { flex-direction: column; gap: 1rem; text-align: center; }
  .services-hero h1 { font-size: 2.1rem; }
}
</style>
@endsection

@section('content')
@php
  $heroSection = isset($page) && $page->sections ? $page->sections->where('type', 'services_hero')->first() : null;
  $heroTitle = $heroSection ? $heroSection->title : 'Online Services';
  $heroSubtitle = $heroSection && isset($heroSection->content['subtitle']) 
    ? $heroSection->content['subtitle'] 
    : 'Access all TEFC digital services and government portals from one place. Submit applications, track status, register for training, apply for certifications and more — all online, all paperless.';
@endphp
<div class="services-hero">
  <div class="services-hero-inner">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span class="sep">›</span> <span>Online Services</span>
    </div>
    <h1>{{ $heroTitle }}</h1>
    <p>{{ $heroSubtitle }}</p>
  </div>
</div>

<div class="container my-5">
  
  <!-- SECTION 1: TEFC DIGITAL SERVICES -->
  <section class="services-section">
    <div class="section-title-wrap">
      <div class="section-icon"><i class="fas fa-desktop"></i></div>
      <h2>TEFC Digital Services</h2>
    </div>
    
    <div class="digital-services-grid">
      <!-- Card 1: Certificate of Origin -->
      <div class="digital-card">
        <div>
          <div class="digital-card-header">
            <div class="icon-badge badge-burgundy"><i class="fas fa-stamp"></i></div>
            <h3>Certificate of Origin</h3>
          </div>
          <div class="digital-card-body">
            <ul>
              <li><i class="fas fa-chevron-right"></i> Official document certifying that a product was manufactured or processed in India.</li>
              <li><i class="fas fa-chevron-right"></i> Mandatory for most international shipments to clear customs at the destination.</li>
              <li><i class="fas fa-chevron-right"></i> Available in both Non-Preferential (standard) and Preferential (trade agreement) formats.</li>
              <li><i class="fas fa-chevron-right"></i> Issued by authorized bodies like the Textiles Committee or Chambers of Commerce.</li>
              <li><i class="fas fa-chevron-right"></i> Can be applied for and issued electronically through the DGFT portal.</li>
            </ul>
          </div>
        </div>
        <a href="{{ url('/p/coo') }}" class="btn-know-more">Know More</a>
      </div>

      <!-- Card 2: Export Credit Guarantee (ECGC) -->
      <div class="digital-card">
        <div>
          <div class="digital-card-header">
            <div class="icon-badge badge-gold"><i class="fas fa-shield-alt"></i></div>
            <h3>Export Credit Guarantee (ECGC)</h3>
          </div>
          <div class="digital-card-body">
            <ul>
              <li><i class="fas fa-chevron-right"></i> Provides insurance to exporters against the risk of non-payment by foreign buyers.</li>
              <li><i class="fas fa-chevron-right"></i> Protects against commercial risks (insolvency) and political risks (war/trade bans).</li>
              <li><i class="fas fa-chevron-right"></i> Makes it easier for exporters to secure pre-shipment or post-shipment bank loans.</li>
              <li><i class="fas fa-chevron-right"></i> Enables confident entry into new or high-risk international markets.</li>
              <li><i class="fas fa-chevron-right"></i> Ensures business continuity by compensating for losses from payment failures.</li>
            </ul>
          </div>
        </div>
        <a href="{{ url('/p/ecgc') }}" class="btn-know-more">Know More</a>
      </div>

      <!-- Card 3: Letter of Credit -->
      <div class="digital-card">
        <div>
          <div class="digital-card-header">
            <div class="icon-badge badge-green"><i class="fas fa-handshake"></i></div>
            <h3>Letter of Credit</h3>
          </div>
          <div class="digital-card-body">
            <ul>
              <li><i class="fas fa-chevron-right"></i> A guarantee from the buyer's bank that payment will be made once terms are met.</li>
              <li><i class="fas fa-chevron-right"></i> Acts as one of the most secure payment methods in international trade.</li>
              <li><i class="fas fa-chevron-right"></i> Payment is triggered only upon submission of proof of shipment and required papers.</li>
              <li><i class="fas fa-chevron-right"></i> Governed by international rules (UCP 600) to ensure global uniformity.</li>
              <li><i class="fas fa-chevron-right"></i> Bridges the trust gap between Indian sellers and foreign buyers.</li>
            </ul>
          </div>
        </div>
        <a href="{{ url('/p/letter-of-credit') }}" class="btn-know-more">Know More</a>
      </div>

      <!-- Card 4: HS Code (Harmonized System) -->
      <div class="digital-card">
        <div>
          <div class="digital-card-header">
            <div class="icon-badge badge-blue"><i class="fas fa-barcode"></i></div>
            <h3>HS Code (Harmonized System)</h3>
          </div>
          <div class="digital-card-body">
            <ul>
              <li><i class="fas fa-chevron-right"></i> A 6-to-8-digit standardized numerical method used to classify products worldwide.</li>
              <li><i class="fas fa-chevron-right"></i> Determines the exact customs duty and GST rates applicable to textile goods.</li>
              <li><i class="fas fa-chevron-right"></i> Ensures correct product identification globally, regardless of language barriers.</li>
              <li><i class="fas fa-chevron-right"></i> Essential for filing shipping bills and avoiding logistical delays at customs.</li>
              <li><i class="fas fa-chevron-right"></i> Helps exporters check eligibility for specific government incentives or schemes.</li>
            </ul>
          </div>
        </div>
        <a href="{{ url('/p/hs-code') }}" class="btn-know-more">Know More</a>
      </div>

      <!-- Card 5: IEC (Import Export Code) -->
      <div class="digital-card">
        <div>
          <div class="digital-card-header">
            <div class="icon-badge badge-purple"><i class="fas fa-address-card"></i></div>
            <h3>IEC (Import Export Code)</h3>
          </div>
          <div class="digital-card-body">
            <ul>
              <li><i class="fas fa-chevron-right"></i> A unique 10-digit identification number mandatory for all Indian export businesses.</li>
              <li><i class="fas fa-chevron-right"></i> Granted by the Directorate General of Foreign Trade (DGFT), Ministry of Commerce.</li>
              <li><i class="fas fa-chevron-right"></i> Valid for a lifetime, subject to a simple annual online update.</li>
              <li><i class="fas fa-chevron-right"></i> Required for clearing shipments and making/receiving foreign currency payments.</li>
              <li><i class="fas fa-chevron-right"></i> Fully digital application process linked directly to the business's PAN card.</li>
            </ul>
          </div>
        </div>
        <a href="{{ url('/p/iec') }}" class="btn-know-more">Know More</a>
      </div>
    </div>
  </section>

  <!-- SECTION 2: EXTERNAL GOVERNMENT PORTALS -->
  <section class="services-section">
    <div class="section-title-wrap">
      <div class="section-icon"><i class="fas fa-globe"></i></div>
      <h2>External Government Portals</h2>
    </div>
    <div class="section-subtitle">Direct links to key government portals relevant to textile exporters</div>
    
    <div class="external-portals-grid">
      <!-- DGFT Portal -->
      <div class="portal-card">
        <div class="icon-badge badge-burgundy"><i class="fas fa-university"></i></div>
        <h4>DGFT Portal</h4>
        <p>Licences, IEC registration, export-import policy & RoDTEP/RoSCTL applications</p>
        <a href="https://www.dgft.gov.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- ICEGATE -->
      <div class="portal-card">
        <div class="icon-badge badge-gold"><i class="fas fa-ship"></i></div>
        <h4>ICEGATE</h4>
        <p>Customs e-filing, shipping bill status, duty scrip redemption & customs EDI services</p>
        <a href="https://www.icegate.gov.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- Trade Connect -->
      <div class="portal-card">
        <div class="icon-badge badge-green"><i class="fas fa-network-wired"></i></div>
        <h4>Trade Connect</h4>
        <p>National trade portal for B2B connections, buyer directories & foreign trade enquiries</p>
        <a href="https://www.tradeconnect.gov.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- Textiles Committee -->
      <div class="portal-card">
        <div class="icon-badge badge-blue"><i class="fas fa-award"></i></div>
        <h4>Textiles Committee</h4>
        <p>Quality testing labs, CoO for non-TEFC exporters, IS/ISO compliance certification</p>
        <a href="https://textilescommittee.nic.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- MSME Sambandh -->
      <div class="portal-card">
        <div class="icon-badge badge-purple"><i class="fas fa-piggy-bank"></i></div>
        <h4>MSME Sambandh</h4>
        <p>MSME registration (Udyam), loan schemes, cluster development & government procurement</p>
        <a href="https://samadhaan.msme.gov.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- SIDBI Portal -->
      <div class="portal-card">
        <div class="icon-badge badge-burgundy"><i class="fas fa-chart-line"></i></div>
        <h4>SIDBI Portal</h4>
        <p>Export credit schemes, working capital loans & technology upgradation finance for MSMEs</p>
        <a href="https://www.sidbi.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- ECGC Portal -->
      <div class="portal-card">
        <div class="icon-badge badge-gold"><i class="fas fa-shield-alt"></i></div>
        <h4>ECGC Portal</h4>
        <p>Export credit insurance, buyer risk covers & post-shipment credit guarantee for exporters</p>
        <a href="https://www.ecgc.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>

      <!-- GST Portal -->
      <div class="portal-card">
        <div class="icon-badge badge-green"><i class="fas fa-file-invoice-dollar"></i></div>
        <h4>GST Portal</h4>
        <p>GST registration, LUT filing for zero-rated exports, refund claims & return filing</p>
        <a href="https://www.gst.gov.in" target="_blank" class="btn-visit-portal">Visit Portal <i class="fas fa-external-link-alt"></i></a>
      </div>
    </div>
  </section>

  <!-- SECTION 3: RECENTLY LAUNCHED SERVICES -->
  <section class="services-section">
    <div class="section-title-wrap">
      <div class="section-icon"><i class="fas fa-star"></i></div>
      <h2>Recently Launched Services</h2>
    </div>
    <div class="section-subtitle">New digital tools and services added to the TEFC portal in 2025</div>
    
    <div class="recent-services-grid">
      <!-- HS Code Intelligence Tool -->
      <div class="recent-card">
        <div class="icon-badge badge-blue"><i class="fas fa-brain"></i></div>
        <div class="recent-card-content">
          <div class="recent-card-header">
            <h4>HS Code Intelligence Tool</h4>
            <span class="badge-new">NEW</span>
          </div>
          <p>Enter product description to get recommended HS code, applicable FTA duty rates and scheme eligibility — all in one search.</p>
          <a href="{{ url('/p/hs-code') }}" class="btn-launch-tool">Try Now →</a>
        </div>
      </div>

      <!-- AI Export Query Assistant -->
      <div class="recent-card">
        <div class="icon-badge badge-gold"><i class="fas fa-comments"></i></div>
        <div class="recent-card-content">
          <div class="recent-card-header">
            <h4>AI Export Query Assistant</h4>
            <span class="badge-new">NEW</span>
          </div>
          <p>Ask any export-related question — duties, documents, regulations, market entry — and get instant answers based on TEFC's knowledge base.</p>
          <a href="{{ url('/p/help-support') }}" class="btn-launch-tool">Ask Now →</a>
        </div>
      </div>
    </div>
  </section>
  
  <!-- BOTTOM ASSISTANCE BANNER -->
  <div class="assistance-strip">
    <h3>Need Assistance? We're here to help.</h3>
    <a href="{{ url('/p/help-support') }}" class="btn-assistance">Help & Support</a>
  </div>

</div>
@endsection
