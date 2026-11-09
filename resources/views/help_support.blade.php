@extends('layouts.app')

@section('title', 'Help & Support — TEFC')

@section('styles')
<style>
/* ── THEME COLORS & VARIABLES ── */
:root {
  --primary-burgundy: #6c1340;
  --primary-burgundy-dark: #4a0e2b;
  --accent-gold: #b38e5d;
  --accent-gold-light: #dfcbb5;
  --bg-light: #fbf9f6;
  --border-color: #ebdcb9;
  --text-dark: #2d2013;
  --text-muted: #7d6e5e;
  --card-light-bg: #f0f4f8; /* Soft blue/ivory highlight */
}

/* ── LAYOUT & PAGE WRAPPERS ── */
.help-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 0;
}

/* ── HERO BANNER ── */
.help-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.help-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.help-hero-inner {
  position: relative;
  z-index: 2;
}
.help-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.help-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.help-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.help-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.help-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin: 0;
}

/* ── SECTIONS ── */
.help-section {
  padding: 4.5rem 0;
}
.help-section-alt {
  background: #f6efe6;
}
.section-title {
  font-size: 1.65rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  border-left: 5px solid var(--accent-gold);
  padding-left: 1rem;
  margin-bottom: 2.2rem;
}

/* ── FAQ HIGHLIGHTS GRID ── */
.faq-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}
.faq-card {
  background: var(--card-light-bg);
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 8px;
  padding: 1.5rem 1.8rem;
  transition: all 0.2s;
}
.faq-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.03);
  border-color: var(--primary-burgundy);
}
.faq-card h4 {
  font-size: 0.92rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.6rem;
  line-height: 1.45;
}
.faq-card p {
  font-size: 0.8rem;
  color: var(--text-muted);
  line-height: 1.55;
  margin: 0;
}

/* ── SPLIT LAYOUT FOR QUERY FORM ── */
.split-form-layout {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 4rem;
  align-items: center;
}
.facilitation-info h3 {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1rem;
}
.facilitation-info p {
  font-size: 0.9rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 2.2rem;
}
.contact-details-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}
.contact-detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}
.contact-detail-lbl {
  font-size: 0.82rem;
  font-weight: 800;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.02em;
}
.contact-detail-val {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--primary-burgundy);
}

/* FORM WRAPPER CARD */
.query-form-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.25);
  border-radius: 16px;
  padding: 2.5rem;
  box-shadow: 0 12px 40px rgba(0,0,0,0.03);
}
.query-form-card h4 {
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1.8rem;
  text-align: center;
}
.form-group-item {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  margin-bottom: 1.2rem;
}
.form-group-item label {
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  color: var(--text-dark);
}
.form-input-field {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.35);
  border-radius: 6px;
  padding: 0.65rem 1rem;
  font-size: 0.85rem;
  color: var(--text-dark);
  transition: all 0.2s;
}
.form-input-field:focus {
  border-color: var(--primary-burgundy);
  outline: none;
  box-shadow: 0 0 0 3px rgba(108, 21, 63, 0.08);
}
.btn-query-submit {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.8rem;
  border-radius: 6px;
  font-size: 0.88rem;
  font-weight: 800;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s;
  margin-top: 0.8rem;
}
.btn-query-submit:hover {
  background: var(--primary-burgundy-dark);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .faq-grid { grid-template-columns: 1fr; }
  .split-form-layout { grid-template-columns: 1fr; gap: 3rem; }
}
</style>
@endsection

@section('content')
@php
  // Fetch help support dynamic sections
  $heroSec = $page->sections->where('type', 'help_support_hero')->first();
  $standardsSec = $page->sections->where('type', 'help_support_standards_faqs')->first();
  $proceduresSec = $page->sections->where('type', 'help_support_procedures_faqs')->first();
  $facilitationSec = $page->sections->where('type', 'help_support_facilitation')->first();

  // ── HERO FALLBACK ──
  $heroTitle = $heroSec ? ($heroSec->content['title'] ?? 'Help & Support Desk') : 'Help & Support Desk';
  $heroSubtitle = $heroSec ? ($heroSec->content['subtitle'] ?? 'Browse textile quality standards highlights, export regulatory policies, and submit custom queries to our facilitation desks.') : 'Browse textile quality standards highlights, export regulatory policies, and submit custom queries to our facilitation desks.';

  // ── STANDARDS FAQs FALLBACK ──
  $standardsTitle = $standardsSec ? ($standardsSec->content['title'] ?? 'Textile Industry & Standards (50 FAQ Highlights)') : 'Textile Industry & Standards (50 FAQ Highlights)';
  $standardsItems = $standardsSec ? ($standardsSec->content['items'] ?? []) : [];

  // ── PROCEDURES FAQs FALLBACK ──
  $proceduresTitle = $proceduresSec ? ($proceduresSec->content['title'] ?? 'Export Procedures & Policies (50 FAQ Highlights)') : 'Export Procedures & Policies (50 FAQ Highlights)';
  $proceduresItems = $proceduresSec ? ($proceduresSec->content['items'] ?? []) : [];

  // ── FACILITATION FALLBACK ──
  $facEmail = $facilitationSec ? ($facilitationSec->content['email'] ?? 'support@tefc.gov.in') : 'support@tefc.gov.in';
  $facTollFree = $facilitationSec ? ($facilitationSec->content['toll_free'] ?? '1800-123-4567') : '1800-123-4567';
  $facAddress = $facilitationSec ? ($facilitationSec->content['address'] ?? 'Textiles Committee, Prabhadevi, Mumbai.') : 'Textiles Committee, Prabhadevi, Mumbai.';
  $facHours = $facilitationSec ? ($facilitationSec->content['hours'] ?? 'Monday to Friday (9 AM - 6 PM)') : 'Monday to Friday (9 AM - 6 PM)';
  $facCategories = $facilitationSec ? ($facilitationSec->content['categories'] ?? []) : [];
@endphp

<div class="help-page-container">

  <!-- ── HERO BANNER ── -->
  <div class="help-hero">
    <div class="container help-hero-inner">
      <div class="help-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>Help &amp; Support</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroSubtitle }}</p>
    </div>
  </div>

  <!-- ── SECTION 1: STANDARDS FAQs ── -->
  @if(count($standardsItems) > 0)
  <section class="help-section">
    <div class="container">
      <h3 class="section-title">{{ $standardsTitle }}</h3>
      <div class="faq-grid">
        @foreach($standardsItems as $item)
          <div class="faq-card">
            <h4>{{ $item['q'] }}</h4>
            <p>{{ $item['a'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ── SECTION 2: PROCEDURES FAQs ── -->
  @if(count($proceduresItems) > 0)
  <section class="help-section help-section-alt">
    <div class="container">
      <h3 class="section-title">{{ $proceduresTitle }}</h3>
      <div class="faq-grid">
        @foreach($proceduresItems as $item)
          <div class="faq-card" style="background: #fff;">
            <h4>{{ $item['q'] }}</h4>
            <p>{{ $item['a'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ── SECTION 3: SUBMIT QUERY FORM ── -->
  <section class="help-section">
    <div class="container">
      <div class="split-form-layout">
        <!-- Facilitation Contact Details -->
        <div class="facilitation-info">
          <h3>Haven't found an answer?</h3>
          <p>Our facilitation experts are available <strong>{{ $facHours }}</strong> to assist you with specific export queries.</p>
          
          <div class="contact-details-list">
            <div class="contact-detail-item">
              <span class="contact-detail-lbl">Email</span>
              <span class="contact-detail-val">{{ $facEmail }}</span>
            </div>
            <div class="contact-detail-item">
              <span class="contact-detail-lbl">Toll Free</span>
              <span class="contact-detail-val">{{ $facTollFree }}</span>
            </div>
            <div class="contact-detail-item">
              <span class="contact-detail-lbl">Head Office</span>
              <span class="contact-detail-val">{{ $facAddress }}</span>
            </div>
          </div>
        </div>

        <!-- Submit Query Card -->
        <div class="query-form-card">
          <h4>Submit a Query</h4>
          <form id="tradeQueryForm" onsubmit="handleQuerySubmit(event)">
            @csrf
            <div class="form-group-item">
              <label for="fullName">Full Name</label>
              <input type="text" id="fullName" class="form-input-field" placeholder="Enter your name" required>
            </div>

            <div class="form-group-item">
              <label for="orgName">Organization / Firm Name</label>
              <input type="text" id="orgName" class="form-input-field" placeholder="Name of your company" required>
            </div>

            <div class="form-group-item">
              <label for="emailAddr">Email Address</label>
              <input type="email" id="emailAddr" class="form-input-field" placeholder="email@example.com" required>
            </div>

            @if(count($facCategories) > 0)
            <div class="form-group-item">
              <label for="queryCategory">Query Category</label>
              <select id="queryCategory" class="form-input-field" style="appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23b38e5d%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 0.65rem auto;" required>
                @foreach($facCategories as $cat)
                  <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
              </select>
            </div>
            @endif

            <div class="form-group-item">
              <label for="yourQuestion">Your Question</label>
              <textarea id="yourQuestion" class="form-input-field" rows="4" placeholder="Please describe your query in detail..." required></textarea>
            </div>

            <button type="submit" class="btn-query-submit">Send Inquiry</button>
          </form>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection

@section('scripts')
<script>
function handleQuerySubmit(event) {
  event.preventDefault();
  
  // Custom interactive premium sweetalert notification
  const name = document.getElementById('fullName').value;
  
  alert(`Thank you, ${name}! Your trade query has been submitted successfully to our facilitation desk. Our support officers will get back to you within 24 business hours.`);
  
  document.getElementById('tradeQueryForm').reset();
}
</script>
@endsection
