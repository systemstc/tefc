@extends('layouts.app')

@section('title', 'HS Code (Harmonized System) — TEFC')

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
  $title = $section ? $section->title : 'HS Code (Harmonized System Code)';
  $data = $section && is_array($section->content) ? $section->content : [];
  $subtitle = $data['subtitle'] ?? 'Universal classification system for international trade goods.';
  $whatIs = $data['what_is'] ?? 'The Harmonized System (HS) Code is an internationally standardized numerical method of classifying traded products. Developed by the World Customs Organization (WCO), it is used by customs authorities globally to identify products, assess duties/taxes, and monitor trade statistics.';
  $documents = $data['documents_required'] ?? [
    'Determines exact duty rates under MFN or FTA.',
    'Required for filing Shipping Bills and Bill of Lading.',
    'Identifies whether the product requires specific quality certifications.',
    'Helps in claiming export incentives like RoDTEP or Drawback.',
    'Avoids customs delays and misclassification penalties.'
  ];
  $steps = $data['application_steps'] ?? [
    'Identify the primary material (e.g., Cotton, Silk, Synthetic).',
    'Determine the product type (e.g., Yarn, Fabric, Garment).',
    'Consult the ITC (HS) classification list provided by DGFT.',
    'Use the 8-digit Indian Trade Classification (ITC-HS) for precise documentation.'
  ];
  $ctaText = $data['cta_text'] ?? 'Search ITC-HS Codes →';
  $ctaUrl = $data['cta_url'] ?? 'https://www.dgft.gov.in/CP/?opt=itchs';
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
    <h3>1. What is an HS Code?</h3>
    <p>{{ $whatIs }}</p>
  </div>

  <div class="section">
    <h3>2. Importance & Chapters in Textile Exports</h3>
    <ul>
      @foreach($documents as $doc)
        <li>{{ $doc }}</li>
      @endforeach
    </ul>
  </div>

  <div class="section">
    <h3>3. How to Find the Correct HS Code</h3>
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
