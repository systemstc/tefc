@extends('layouts.app')

@section('title', 'Import Export Code (IEC) — TEFC')

@section('styles')
<style>
.page-hero {
  background: linear-gradient(135deg, var(--navy-dark), var(--navy));
  padding: 50px 0;
  color: #fff;
}
.hero-inner { position: relative; z-index: 2; }
.hero-inner h1 { margin: 0; font-size: 28px; }
.hero-inner p { opacity: 0.85; margin-top: 6px; }

.section { margin-bottom: 30px; background: #f9f9f9; padding: 25px; border-radius: 12px; border: 1px solid var(--border); }
.section h3 { color: var(--navy); margin-top: 0; }
.section p { line-height: 1.6; color: var(--muted); }
ul, ol { padding-left: 20px; line-height: 1.8; color: var(--muted); font-size: 0.9rem; }

.btn-primary {
  background: var(--navy);
  color: #fff;
  padding: 12px 24px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-block;
  font-weight: 600;
  margin-top: 10px;
}
.back-link { display: inline-block; margin-bottom: 20px; color: var(--navy); text-decoration: none; font-weight: 600; font-size: 0.9rem; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .hero { padding: 3rem 1.5rem; text-align: center; }
  .hero h1 { font-size: 1.5rem; }
  .container { padding: 2rem 1.5rem; }
  .section { padding: 1.5rem; }
}
</style>
@endsection

@section('content')
@php
  $section = isset($page) && $page->sections ? $page->sections->where('type', 'service_detail')->first() : null;
  $title = $section ? $section->title : 'Import Export Code (IEC)';
  $data = $section && is_array($section->content) ? $section->content : [];
  $subtitle = $data['subtitle'] ?? 'The primary registration required to start a textile export business in India.';
  $whatIs = $data['what_is'] ?? 'The Import Export Code (IEC) is a 10-digit identification number issued by the Director General of Foreign Trade (DGFT), Ministry of Commerce and Industry. It is mandatory for any person or company to obtain an IEC before starting an import or export business in India.';
  $documents = $data['documents_required'] ?? [
    'PAN Card of the entity or individual.',
    'Aadhaar Card or Passport (for individuals/partners).',
    'Cancelled Cheque of the business bank account.',
    'Proof of Business Address (Rent Agreement/Electricity Bill).',
    'User Profile on DGFT Portal (using Aadhaar or DSC).'
  ];
  $steps = $data['application_steps'] ?? [
    'Visit the official DGFT website and register as an Exporter/Importer.',
    'Fill in the \'Apply for IEC\' form (ANF-2A).',
    'Upload the scanned copies of required documents.',
    'Pay the application fee (currently ₹500) via net banking or credit/debit card.',
    'Once approved, you can download the e-IEC certificate immediately.'
  ];
  $ctaText = $data['cta_text'] ?? 'Apply on DGFT Portal →';
  $ctaUrl = $data['cta_url'] ?? 'https://www.dgft.gov.in/';
@endphp
<div class="page-hero">
  <div class="container hero-inner">
    <h1>{{ $title }}</h1>
    <p>{{ $subtitle }}</p>
  </div>
</div>

<div class="container" style="padding: 40px 0;">
  <a href="{{ url('/p/online-services') }}" class="back-link">← Back to Services</a>

  <div class="section">
    <h3>1. What is an IEC?</h3>
    <p>{{ $whatIs }}</p>
  </div>

  <div class="section">
    <h3>2. Essential Documents</h3>
    <ul>
      @foreach($documents as $doc)
        <li>{{ $doc }}</li>
      @endforeach
    </ul>
  </div>

  <div class="section">
    <h3>3. Step-by-Step Registration</h3>
    <ol>
      @foreach($steps as $step)
        <li>{{ $step }}</li>
      @endforeach
    </ol>
  </div>

  <div style="text-align: center; margin-top: 2rem;">
    <a href="{{ $ctaUrl }}" target="_blank" class="btn-primary">{{ $ctaText }}</a>
  </div>
</div>
@endsection
