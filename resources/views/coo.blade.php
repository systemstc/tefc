@extends('layouts.app')

@section('title', 'Certificate of Origin (CoO) — TEFC')

@section('styles')
<style>
.page-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
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
  $title = $section ? $section->title : 'Certificate of Origin (CoO)';
  $data = $section && is_array($section->content) ? $section->content : [];
  $subtitle = $data['subtitle'] ?? 'Essential export documentation for preferential trade benefits.';
  $whatIs = $data['what_is'] ?? 'A Certificate of Origin (CoO) is an official document certifying that the goods being exported are manufactured or processed in India. Customs authorities in importing countries use this to determine tariff rates and eligibility under Free Trade Agreements (FTAs).';
  $documents = $data['documents_required'] ?? [
    'Commercial Invoice & Packing List',
    'Shipping Bill (for post-shipment CoO)',
    'Import Export Code (IEC)',
    'RCMC (Registration-cum-Membership Certificate)',
    'Manufacturing Process Flowchart (if applicable)'
  ];
  $steps = $data['application_steps'] ?? [
    'Register on the Common Digital Platform (DGFT).',
    'Select the relevant trade agreement (Preferential or Non-Preferential).',
    'Upload the required invoices and shipping documents.',
    'Pay the requisite fee online.',
    'Receive the digitally signed certificate.'
  ];
  $ctaText = $data['cta_text'] ?? 'Apply on DGFT CoO Portal →';
  $ctaUrl = $data['cta_url'] ?? 'https://coo.dgft.gov.in/';
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
    <h3>1. What is a Certificate of Origin?</h3>
    <p>{{ $whatIs }}</p>
  </div>

  <div class="section">
    <h3>2. Documents Required for Application</h3>
    <ul>
      @foreach($documents as $doc)
        <li>{{ $doc }}</li>
      @endforeach
    </ul>
  </div>

  <div class="section">
    <h3>3. Application Process</h3>
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
