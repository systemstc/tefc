@extends('layouts.app')

@section('title', 'Letter of Credit (LC) — TEFC')

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
</style>
@endsection

@section('content')
@php
  $section = isset($page) && $page->sections ? $page->sections->where('type', 'service_detail')->first() : null;
  $title = $section ? $section->title : 'Letter of Credit (LC)';
  $data = $section && is_array($section->content) ? $section->content : [];
  $subtitle = $data['subtitle'] ?? 'Secure payment mechanisms for international textile trade.';
  $whatIs = $data['what_is'] ?? 'A Letter of Credit (LC) is a financial instrument issued by a bank that guarantees payment to the exporter on behalf of the importer, provided that all the specified documents and conditions are strictly fulfilled. It is the most secure payment method for global trade, especially in the textile sector.';
  $documents = $data['documents_required'] ?? [
    'Signed Commercial Invoices.',
    'Full set of clean \'On Board\' Bill of Lading.',
    'Packing List.',
    'Certificate of Origin (Preferential/Non-preferential).',
    'Insurance Policy or Certificate.',
    'Inspection Certificate (if required by LC).'
  ];
  $steps = $data['application_steps'] ?? [
    'The buyer (importer) applies to their bank to open an LC in favor of the exporter.',
    'The issuing bank sends the LC to an advising bank in India.',
    'The exporter reviews the LC terms and ships the textile goods.',
    'The exporter submits the required documents to the advising/negotiating bank.',
    'The bank verifies the documents against LC terms and releases the payment.',
    'The issuing bank recovers the payment from the buyer and releases the documents for customs clearance.'
  ];
  $ctaText = $data['cta_text'] ?? 'View RBI Trade Guidelines →';
  $ctaUrl = $data['cta_url'] ?? 'https://www.rbi.org.in/';
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
    <h3>1. What is a Letter of Credit?</h3>
    <p>{{ $whatIs }}</p>
  </div>

  <div class="section">
    <h3>2. Essential LC Documents</h3>
    <ul>
      @foreach($documents as $doc)
        <li>{{ $doc }}</li>
      @endforeach
    </ul>
  </div>

  <div class="section">
    <h3>3. The LC Workflow</h3>
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
