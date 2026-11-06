@extends('layouts.app')

@section('title', 'ECGC (Export Credit Guarantee) — TEFC')

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
  $title = $section ? $section->title : 'Export Credit Guarantee Corporation (ECGC)';
  $data = $section && is_array($section->content) ? $section->content : [];
  $subtitle = $data['subtitle'] ?? 'Protecting Indian textile exporters against payment and political risks.';
  $whatIs = $data['what_is'] ?? 'The Export Credit Guarantee Corporation (ECGC) provides credit risk insurance to exporters against non-payment by overseas buyers due to commercial or political risks. It enables textile exporters to expand confidently into global markets by safeguarding their receivables.';
  $documents = $data['documents_required'] ?? [
    'Import Export Code (IEC)',
    'Business Registration Certificate (MSME/GST)',
    'Buyer Credit Information / Reports',
    'Export Invoices & Shipping Documents',
    'Bank Certificate (BRC/FIRC)'
  ];
  $steps = $data['application_steps'] ?? [
    'Exporter applies for an ECGC policy (e.g., Shipments Comprehensive Risks).',
    'ECGC assesses the creditworthiness of the foreign buyer and the country risk.',
    'Policy is issued with specific credit limits.',
    'Exporter ships goods and declares them to ECGC.',
    'If the buyer fails to pay due to commercial or political reasons, ECGC compensates up to 90% of the loss.'
  ];
  $ctaText = $data['cta_text'] ?? 'Visit ECGC Portal →';
  $ctaUrl = $data['cta_url'] ?? 'https://www.ecgc.in/';
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
    <h3>1. What is ECGC?</h3>
    <p>{{ $whatIs }}</p>
  </div>

  <div class="section">
    <h3>2. Documents Required</h3>
    <ul>
      @foreach($documents as $doc)
        <li>{{ $doc }}</li>
      @endforeach
    </ul>
  </div>

  <div class="section">
    <h3>3. How ECGC Protection Works</h3>
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
