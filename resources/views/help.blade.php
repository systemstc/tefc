@extends('layouts.app')

@section('title', 'Help & Support — TEFC')

@section('styles')
<style>
/* ── SUPPORT HERO ── */
.support-hero {
  background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy) 60%, #a0245e 100%);
  padding: 4rem 2.5rem;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
}
.support-hero::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 45%;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="200"><rect width="400" height="200" fill="%234a0e2b"/><line x1="0" y1="0" x2="400" y2="200" stroke="%23ffffff08" stroke-width="1"/></svg>') center/cover;
  opacity: 0.6;
}
.support-hero-inner { position: relative; z-index: 2; }
.support-hero h1 { font-size: 2.2rem; margin-bottom: 1rem; font-weight: 700; }
.search-container { max-width: 600px; margin: 1.5rem auto 0; position: relative; }
.search-container input {
  width: 100%;
  padding: 1rem 1.5rem;
  border-radius: 30px;
  border: none;
  font-size: 1rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  font-family: 'Inter', sans-serif;
}

/* ── FAQ LAYOUT ── */
/* ── FAQ LAYOUT ── */
.faq-section-title {
  color: var(--navy);
  border-left: 4px solid var(--navy);
  padding-left: 1rem;
  margin: 3rem 0 1.5rem;
  font-size: 1.5rem;
  font-weight: 700;
}
.faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.faq-item {
  background: var(--sky2);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 1.2rem;
  transition: transform 0.2s, border-color 0.2s;
}
.faq-item:hover { transform: translateY(-2px); border-color: var(--navy); }
.faq-item h4 { color: var(--navy); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600; }
.faq-item p { font-size: 0.85rem; color: var(--muted); }

/* ── CONTACT SECTION ── */
.contact-wrapper { background: var(--sky); padding: 4rem 2rem; margin-top: 4rem; }
.contact-container {
  max-width: 900px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 3rem;
}
.contact-info h2 { color: var(--navy); margin-bottom: 1rem; font-weight: 700; }
.contact-info p { font-size: 0.9rem; color: var(--text); }
.contact-form {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(108, 21, 63, 0.1);
}
.form-group { margin-bottom: 1.2rem; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--navy); }
.form-group input, .form-group textarea, .form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.85rem;
}
.btn-submit {
  background: var(--navy);
  color: white;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  font-family: 'Inter', sans-serif;
  transition: background 0.15s;
}
.btn-submit:hover { background: var(--navy-dark); }

@media (max-width: 768px) {
  .faq-grid, .contact-container { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<!-- SUPPORT HERO -->
<div class="support-hero">
  <div class="container support-hero-inner">
    <h1>Help &amp; Support</h1>
    <p>Find answers to common export queries or get in touch with our facilitation experts.</p>
    <div class="search-container">
      <input type="text" placeholder="Search for questions, procedures, or schemes...">
    </div>
  </div>
</div>

<div class="container">
  <!-- SECTION 1: TEXTILE STANDARDS -->
  <h2 class="faq-section-title">Textile Industry &amp; Standards</h2>
  <div class="faq-grid" id="textile-faqs">
    <div class="faq-item">
      <h4>What is the Jute Mark India (JMI) scheme?</h4>
      <p>JMI is a certification mark that provides an assurance of quality and origin for jute products manufactured in India.</p>
    </div>
    <div class="faq-item">
      <h4>How do I apply for Handloom Mark?</h4>
      <p>You can apply through the Textiles Committee portal by submitting your looms details and fabric samples for testing.</p>
    </div>
    <div class="faq-item">
      <h4>What are Technical Textiles?</h4>
      <p>These are textiles manufactured primarily for their technical performance and functional properties rather than aesthetic characteristics.</p>
    </div>
    <div class="faq-item">
      <h4>Which lab tests are mandatory for cotton export?</h4>
      <p>Common tests include GSM, Color Fastness, Tensile Strength, and Shrinkage tests as per ISO or AATCC standards.</p>
    </div>
  </div>

  <!-- SECTION 2: EXPORT PROCEDURES -->
  <h2 class="faq-section-title">Export Procedures &amp; Policies</h2>
  <div class="faq-grid" id="export-faqs">
    <div class="faq-item">
      <h4>What is the RoDTEP Scheme?</h4>
      <p>Remission of Duties and Taxes on Exported Products provides a rebate on central/state taxes not otherwise refunded.</p>
    </div>
    <div class="faq-item">
      <h4>How to obtain an IEC (Import Export Code)?</h4>
      <p>You must apply online through the DGFT website using your PAN and bank details.</p>
    </div>
    <div class="faq-item">
      <h4>What is a Certificate of Origin (COO)?</h4>
      <p>A document declaring in which country a commodity was manufactured. Crucial for claiming FTA benefits.</p>
    </div>
    <div class="faq-item">
      <h4>What is a Letter of Credit (LC)?</h4>
      <p>A payment mechanism where the buyer's bank guarantees payment to the exporter upon submission of valid shipping documents.</p>
    </div>
  </div>
</div>

<!-- CONTACT SECTION -->
<section class="contact-wrapper">
  <div class="container">
    <div class="contact-container">
      <div class="contact-info">
        <h2>Haven't found an answer?</h2>
        <p>Our facilitation experts are available Monday to Friday (9 AM - 6 PM) to assist you with specific export queries.</p>
        <div style="margin-top:1.5rem;">
          <p><strong>Email:</strong> support@tefc.gov.in</p>
          <p><strong>Toll Free:</strong> 1800-123-4567</p>
          <p><strong>Head Office:</strong> Textiles Committee, Prabhadevi, Mumbai.</p>
        </div>
      </div>

    <div class="contact-form">
      <h3>Submit a Query</h3>
      <form action="#" style="margin-top:1rem;">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
          <label>Organization / Firm Name</label>
          <input type="text" placeholder="Name of your company">
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" placeholder="email@example.com" required>
        </div>
        <div class="form-group">
          <label>Query Category</label>
          <select>
            <option>Textile Quality Testing</option>
            <option>Export Schemes (RoDTEP/PLI)</option>
            <option>FTA / Trade Agreements</option>
            <option>Market Intelligence</option>
            <option>Others</option>
          </select>
        </div>
        <div class="form-group">
          <label>Your Question</label>
          <textarea rows="5" placeholder="Please describe your query in detail..."></textarea>
        </div>
        <button type="submit" class="btn-submit">Send Inquiry</button>
      </form>
    </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  // Programmatically populate additional FAQs if needed
  const textileQs = [
    "What is the role of TEFC in cluster development?",
    "Requirements for Silk Mark certification?",
    "Benefits of ZED certification for textile MSMEs?",
    "What are the azo-dye restrictions for EU exports?"
  ];

  const exportQs = [
    "Difference between FOB and CIF contracts?",
    "What is the duration of an EPCG scheme obligation?",
    "Role of ECGC in protecting textile exporters?",
    "Benefits of the Advance Authorization Scheme?"
  ];

  function populateFaqs(id, list) {
    const grid = document.getElementById(id);
    list.forEach(q => {
      const div = document.createElement('div');
      div.className = 'faq-item';
      div.innerHTML = `<h4>${q}</h4><p>Contact our facilitation desk for the detailed compliance checklist regarding this specific query.</p>`;
      grid.appendChild(div);
    });
  }

  window.onload = () => {
    populateFaqs('textile-faqs', textileQs);
    populateFaqs('export-faqs', exportQs);
  };
</script>
@endsection
