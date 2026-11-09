@extends('layouts.app')

@section('title', 'Help & Support — TEFC')

@section('styles')
<style>
/* ── HERO ── */
.support-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4rem 2.5rem;
  text-align: center;
  color: white;
}
.support-hero h1 {font-size: 2.2rem; margin-bottom: 1rem;}
.search-container {
  max-width: 600px;
  margin: 1.5rem auto 0;
  position: relative;
}
.search-container input {
  width: 100%;
  padding: 1rem 1.5rem;
  border-radius: 30px;
  border: none;
  font-size: 1rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* ── FAQ ── */
/* ── FAQ ── */
.faq-section-title {
  color: var(--navy);
  border-left: 4px solid var(--navy);
  padding-left: 1rem;
  margin: 3rem 0 1.5rem;
  font-size: 1.5rem;
}
.faq-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}
.faq-item {
  background: var(--sky2);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.2s;
}
.faq-item:hover {transform: translateY(-3px); border-color: var(--navy); box-shadow: 0 8px 20px rgba(108, 21, 63, 0.08);}
.faq-item h4 {color: var(--navy); font-size: 1rem; margin-bottom: 0.8rem; font-weight: 700;}
.faq-item p {font-size: 0.85rem; color: var(--muted); line-height: 1.6;}

/* ── CONTACT ── */
.contact-wrapper {
  background: var(--sky);
  padding: 5rem 2rem;
  margin-top: 4rem;
}
.contact-container {
  max-width: 1000px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 4rem;
}
.contact-info h2 {color: var(--navy); font-size: 1.8rem; margin-bottom: 1.5rem; font-weight: 700;}
.contact-info p {color: var(--muted); margin-bottom: 1.5rem;}
.contact-form {
  background: white;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 15px 40px rgba(108, 21, 63, 0.1);
}
.form-group {margin-bottom: 1.2rem;}
.form-group label {display: block; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--navy); text-transform: uppercase;}
.form-group input, .form-group textarea, .form-group select {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 0.9rem;
}
.btn-submit {
  background: var(--navy);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 6px;
  font-weight: 700;
  cursor: pointer;
  width: 100%;
  font-size: 0.9rem;
  transition: background 0.2s;
}
.btn-submit:hover {background: var(--navy-dark);}

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  .faq-grid { grid-template-columns: 1fr; }
  .contact-container { grid-template-columns: 1fr; gap: 2rem; }
  .contact-wrapper { padding: 3rem 1.5rem; }
}

@media (max-width: 600px) {
  .support-hero { padding: 3rem 1.5rem; }
  .support-hero h1 { font-size: 1.8rem; }
  .container { padding: 0 1.5rem; margin: 2rem auto; }
  .contact-form { padding: 1.5rem; }
}
</style>
@endsection

@section('content')
<div class="support-hero">
  <div class="container">
    <h1>How can we help you today?</h1>
    <p>Find answers to common export queries or connect with our facilitation desk</p>
    <div class="search-container">
      <input type="text" placeholder="Search for 'RoDTEP', 'Jute Mark', 'FTA'...">
    </div>
  </div>
</div>

<div class="container">
  <h2 class="faq-section-title">Common Export Queries</h2>
  <div class="faq-grid">
    <div class="faq-item">
      <h4>What is the Jute Mark India (JMI) scheme?</h4>
      <p>JMI is a certification mark that provides an assurance of quality and origin for jute products manufactured in India.</p>
    </div>
    <div class="faq-item">
      <h4>How do I apply for Handloom Mark?</h4>
      <p>You can apply through the Textiles Committee portal by submitting your looms details and fabric samples for testing.</p>
    </div>
    <div class="faq-item">
      <h4>What is the RoDTEP Scheme?</h4>
      <p>Remission of Duties and Taxes on Exported Products provides a rebate on central/state taxes not otherwise refunded.</p>
    </div>
    <div class="faq-item">
      <h4>How to obtain an IEC Code?</h4>
      <p>You must apply online through the DGFT website using your PAN and bank details. It is mandatory for all exports.</p>
    </div>
    <div class="faq-item">
      <h4>What is a Certificate of Origin (COO)?</h4>
      <p>A document declaring where a commodity was manufactured. Crucial for claiming benefits under various FTAs.</p>
    </div>
    <div class="faq-item">
      <h4>What are Technical Textiles?</h4>
      <p>Textiles manufactured primarily for their technical performance and functional properties rather than aesthetics.</p>
    </div>
  </div>
</div>

<section class="contact-wrapper">
  <div class="container">
    <div class="contact-container">
      <div class="contact-info">
        <h2>Haven't found an answer?</h2>
        <p>Our facilitation experts are available Monday to Friday (9 AM - 6 PM) to assist you with specific export queries.</p>
        <div style="margin-top:2rem;">
          <p><strong>📧 Email:</strong> support@tefc.gov.in</p>
          <p><strong>📞 Toll Free:</strong> 1800-123-4567</p>
          <p><strong>🏢 Head Office:</strong> Textiles Committee, Prabhadevi, Mumbai.</p>
        </div>
      </div>

    <div class="contact-form">
      <h3>Submit a Query</h3>
      <p style="font-size:0.8rem; color:var(--muted); margin-bottom:1.5rem;">Fill out the form below and we'll get back to you within 24 hours.</p>
      <form action="#">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" placeholder="Enter your name" required>
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
          <textarea rows="4" placeholder="Please describe your query in detail..."></textarea>
        </div>
        <button type="submit" class="btn-submit">Send Inquiry</button>
      </form>
    </div>
    </div>
  </div>
</section>
@endsection
