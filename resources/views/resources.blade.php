@extends('layouts.app')

@section('title', 'Resources — TEFC')

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
}

/* ── LAYOUT & PAGE WRAPPERS ── */
.resources-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 0;
}

/* ── HERO BANNER ── */
.resources-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.resources-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.resources-hero-inner {
  position: relative;
  z-index: 2;
}
.resources-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.resources-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.resources-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.resources-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.resources-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin: 0;
}

/* ── STICKY TAB NAV ── */
.resources-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 1.1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.resources-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
  justify-content: center;
}
.resources-tab {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.3);
  color: var(--text-dark);
  padding: 0.45rem 1.3rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}
.resources-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.resources-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
  box-shadow: 0 4px 12px rgba(108, 21, 63, 0.15);
}

/* ── SECTION COMMON ── */
.resources-section {
  padding: 5rem 0;
}
.resources-section-alt {
  background: #f6efe6;
}
.section-title-wrap {
  margin-bottom: 3rem;
}
.section-title-wrap h2 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.4rem;
  display: flex;
  align-items: center;
  gap: 10px;
}
.section-title-wrap p {
  font-size: 0.88rem;
  color: var(--text-muted);
}

/* ── MARKET RESEARCH SECTION ── */
.research-featured-block {
  display: grid;
  grid-template-columns: 1.2fr 2fr;
  gap: 2rem;
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.25);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.02);
  margin-bottom: 2.5rem;
}
.research-featured-left {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  color: #fff;
  padding: 3rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  position: relative;
}
.research-featured-left::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 10% 90%, rgba(255,255,255,0.03) 0%, transparent 60%);
}
.research-featured-val {
  font-size: 3.5rem;
  font-weight: 900;
  color: var(--accent-gold-light);
  line-height: 1;
}
.research-featured-lbl {
  font-size: 0.82rem;
  text-transform: uppercase;
  margin-top: 0.5rem;
  letter-spacing: 0.05em;
  opacity: 0.9;
}
.research-featured-right {
  padding: 3rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.research-featured-right h3 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  line-height: 1.35;
  margin-bottom: 1rem;
}
.research-featured-right p {
  font-size: 0.9rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.reports-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.8rem;
}
.report-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.8rem;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  display: flex;
  flex-direction: column;
}
.report-card:hover {
  transform: translateY(-4px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 12px 30px rgba(108, 21, 63, 0.06);
}
.report-card-badge {
  font-size: 0.65rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  margin-bottom: 0.6rem;
  display: inline-block;
}
.report-card h4 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  line-height: 1.45;
  margin-bottom: 0.8rem;
}
.report-card p {
  font-size: 0.8rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 1.5rem;
  flex: 1;
}
.report-btn {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.55rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 800;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}
.report-btn:hover {
  background: var(--primary-burgundy-dark);
}

/* ── EXPORT PROCESS TIMELINE ── */
.process-split-layout {
  display: grid;
  grid-template-columns: 2.2fr 1fr;
  gap: 3rem;
}
.process-timeline {
  position: relative;
  padding-left: 2.5rem;
}
.process-timeline::before {
  content: '';
  position: absolute;
  left: 15px;
  top: 10px;
  bottom: 10px;
  width: 2px;
  background: rgba(179, 142, 93, 0.25);
}
.timeline-item {
  position: relative;
  margin-bottom: 2.2rem;
}
.timeline-item:last-child {
  margin-bottom: 0;
}
.timeline-bullet {
  position: absolute;
  left: -40px;
  top: 2px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--primary-burgundy);
  color: #fff;
  font-size: 0.85rem;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 10px rgba(108, 21, 63, 0.2);
}
.timeline-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 12px;
  padding: 1.5rem;
}
.timeline-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}
.timeline-header h4 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
}
.timeline-status {
  background: rgba(179, 142, 93, 0.1);
  color: var(--accent-gold);
  font-size: 0.65rem;
  font-weight: 800;
  padding: 2px 8px;
  border-radius: 4px;
}
.timeline-card p {
  font-size: 0.82rem;
  color: var(--text-muted);
  line-height: 1.5;
}

.sidebar-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}
.sidebar-card h4 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
  padding-bottom: 0.6rem;
  margin-bottom: 1rem;
}
.sidebar-links {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}
.sidebar-link-item {
  color: var(--text-dark);
  text-decoration: none;
  font-size: 0.82rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color 0.2s;
}
.sidebar-link-item:hover {
  color: var(--primary-burgundy);
}
.support-block {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  color: #fff;
  border: 1px solid var(--accent-gold);
}
.support-block h4 {
  color: #fff;
  border-color: rgba(255,255,255,0.15);
}
.support-block p {
  font-size: 0.78rem;
  opacity: 0.9;
  line-height: 1.6;
  margin-bottom: 1.2rem;
}
.support-btn {
  background: #fff;
  color: var(--primary-burgundy);
  border: none;
  padding: 0.55rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 800;
  text-align: center;
  cursor: pointer;
  width: 100%;
  display: block;
  text-decoration: none;
  transition: all 0.2s;
}
.support-btn:hover {
  background: var(--accent-gold-light);
}

/* ── BILATERAL TARIFFS ── */
.tariffs-stats-bar {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: 3rem;
}
.tariff-stat-card {
  background: #fff;
  border-top: 3px solid var(--accent-gold);
  border-radius: 8px;
  padding: 1.2rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
}
.tariff-stat-val {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--primary-burgundy);
}
.tariff-stat-lbl {
  font-size: 0.72rem;
  color: var(--text-muted);
  margin-top: 0.25rem;
}

.tariffs-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}
.tariff-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.18);
  border-radius: 12px;
  padding: 1.8rem;
  position: relative;
}
.tariff-card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.8rem;
}
.tariff-card h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
}
.tariff-badge {
  color: #fff;
  font-size: 0.65rem;
  font-weight: 800;
  padding: 2px 8px;
  border-radius: 4px;
  text-transform: uppercase;
}
.tariff-card-date {
  font-size: 0.72rem;
  color: var(--text-muted);
  margin-bottom: 0.8rem;
}
.tariff-card p {
  font-size: 0.82rem;
  color: var(--text-muted);
  line-height: 1.6;
}

/* ── TRADE ADVISORIES ── */
.advisories-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.8rem;
}
.advisory-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
}
.advisory-icon-box {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(108, 21, 63, 0.05);
  color: var(--primary-burgundy);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
  margin-bottom: 1.2rem;
}
.advisory-card-badge {
  font-size: 0.65rem;
  font-weight: 800;
  text-transform: uppercase;
  margin-bottom: 0.4rem;
}
.advisory-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  line-height: 1.4;
  margin-bottom: 0.6rem;
}
.advisory-card p {
  font-size: 0.8rem;
  color: var(--text-muted);
  line-height: 1.5;
  flex: 1;
}

/* ── FAQS ── */
.faq-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.8rem;
}
.faq-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.2s;
}
.faq-card:hover {
  border-color: var(--primary-burgundy);
}
.faq-question {
  font-size: 0.9rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}
.faq-answer {
  font-size: 0.8rem;
  color: var(--text-muted);
  line-height: 1.5;
  margin-top: 0.8rem;
  border-top: 1px dashed rgba(179, 142, 93, 0.15);
  padding-top: 0.8rem;
}

/* ── DOWNLOADS SECTION ── */
.downloads-category-bar {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}
.downloads-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.download-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  transition: all 0.2s;
}
.download-card:hover {
  transform: translateY(-2px);
  border-color: var(--primary-burgundy);
}
.download-icon {
  width: 100%;
  height: 90px;
  background: var(--primary-burgundy);
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 2.2rem;
  margin-bottom: 1rem;
}
.download-card h4 {
  font-size: 0.8rem;
  font-weight: 800;
  line-height: 1.4;
  color: var(--text-dark);
  margin-bottom: 0.6rem;
  height: 2.3rem;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}
.download-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.68rem;
  color: var(--text-muted);
  border-top: 1px dashed rgba(179, 142, 93, 0.15);
  padding-top: 0.8rem;
  margin-top: auto;
}
.download-link {
  color: var(--primary-burgundy);
  font-weight: 800;
  text-decoration: none;
}
.download-link:hover {
  color: var(--accent-gold);
}

/* ── STATS STRIP ── */
.stats-strip {
  background: var(--primary-burgundy);
  border-top: 1px solid rgba(179, 142, 93, 0.2);
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 2.2rem 0;
  color: #fff;
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1.5rem;
  text-align: center;
}
.stat-val {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--accent-gold-light);
}
.stat-lbl {
  font-size: 0.72rem;
  text-transform: uppercase;
  opacity: 0.85;
  margin-top: 0.3rem;
  letter-spacing: 0.02em;
}

.bottom-callout-banner {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  color: #fff;
  padding: 3.5rem 0;
  text-align: center;
}
.bottom-callout-banner h3 {
  font-size: 1.6rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
}
.btn-bottom-submit {
  background: #fff;
  color: var(--primary-burgundy);
  border: none;
  padding: 0.8rem 2.5rem;
  border-radius: 30px;
  font-size: 0.92rem;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  display: inline-block;
}
.btn-bottom-submit:hover {
  background: var(--accent-gold-light);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .research-featured-block { grid-template-columns: 1fr; }
  .reports-grid { grid-template-columns: repeat(2, 1fr); }
  .process-split-layout { grid-template-columns: 1fr; }
  .tariffs-grid { grid-template-columns: 1fr; }
  .advisories-grid { grid-template-columns: repeat(2, 1fr); }
  .faq-grid { grid-template-columns: 1fr; }
  .downloads-grid { grid-template-columns: repeat(2, 1fr); }
  .stats-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
  .reports-grid { grid-template-columns: 1fr; }
  .advisories-grid { grid-template-columns: 1fr; }
  .downloads-grid { grid-template-columns: 1fr; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .resources-hero h1 { font-size: 2.2rem; }
}
</style>
@endsection

@section('content')
@php
  // Fetch dynamic sections
  $heroSec = $page ? $page->sections->where('type', 'resources_hero')->first() : null;
  $marketSec = $page ? $page->sections->where('type', 'resources_market_research')->first() : null;
  $processSec = $page ? $page->sections->where('type', 'resources_export_process')->first() : null;
  $tariffsSec = $page ? $page->sections->where('type', 'resources_tariffs')->first() : null;
  $advisoriesSec = $page ? $page->sections->where('type', 'resources_advisories')->first() : null;
  $faqsSec = $page ? $page->sections->where('type', 'resources_faqs')->first() : null;
  $downloadsSec = $page ? $page->sections->where('type', 'resources_downloads')->first() : null;
  $metricsSec = $page ? $page->sections->where('type', 'resources_metrics')->first() : null;

  // ── HERO FALLBACK ──
  $heroTitle = $heroSec ? ($heroSec->content['title'] ?? 'Resource Center') : 'Resource Center';
  $heroSubtitle = $heroSec ? ($heroSec->content['subtitle'] ?? 'Access essential trade guides, bilateral tariff structures, step-by-step export processes, and market intelligence reports.') : 'Access essential trade guides, bilateral tariff structures, step-by-step export processes, and market intelligence reports.';

  // ── MARKET RESEARCH FALLBACK ──
  $featuredReport = $marketSec ? ($marketSec->content['featured'] ?? null) : null;
  $marketItems = $marketSec ? ($marketSec->content['items'] ?? []) : [];

  // ── EXPORT PROCESS FALLBACK ──
  $processSteps = $processSec ? ($processSec->content['steps'] ?? []) : [];
  $usefulLinks = $processSec ? ($processSec->content['useful_links'] ?? []) : [];

  // ── TARIFFS FALLBACK ──
  $tariffStats = $tariffsSec ? ($tariffsSec->content['stats'] ?? []) : [];
  $tariffItems = $tariffsSec ? ($tariffsSec->content['items'] ?? []) : [];

  // ── ADVISORIES FALLBACK ──
  $advisoriesItems = $advisoriesSec ? ($advisoriesSec->content['items'] ?? []) : [];

  // ── FAQS FALLBACK ──
  $faqItems = $faqsSec ? ($faqsSec->content['items'] ?? []) : [];

  // ── DOWNLOADS FALLBACK ──
  $downloadItems = $downloadsSec ? ($downloadsSec->content['items'] ?? []) : [];

  // ── METRICS FALLBACK ──
  $statsCounters = $metricsSec ? ($metricsSec->content['stats'] ?? []) : [];
@endphp

<div class="resources-page-container">

  <!-- ── HERO BANNER ── -->
  <div class="resources-hero">
    <div class="container resources-hero-inner">
      <div class="resources-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>Resources</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroSubtitle }}</p>
    </div>
  </div>

  <!-- ── STICKY TAB NAV ── -->
  <div class="resources-filter-strip">
    <div class="container resources-tabs">
      <button class="resources-tab active" onclick="scrollToSection('research', this)"><i class="fas fa-search me-1"></i> Market Research</button>
      <button class="resources-tab" onclick="scrollToSection('process', this)"><i class="fas fa-tasks me-1"></i> Export Process</button>
      <button class="resources-tab" onclick="scrollToSection('tariffs', this)"><i class="fas fa-percent me-1"></i> Bilateral Tariffs</button>
      <button class="resources-tab" onclick="scrollToSection('advisories', this)"><i class="fas fa-bullhorn me-1"></i> Advisories &amp; FAQs</button>
      <button class="resources-tab" onclick="scrollToSection('downloads', this)"><i class="fas fa-download me-1"></i> Downloads</button>
    </div>
  </div>

  <!-- ── SECTION 1: MARKET RESEARCH ── -->
  <section class="resources-section" id="research">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="fas fa-search"></i> Market Research &amp; Intelligence</h2>
        <p>Expert sector briefings, global consumer dynamics and roadmap analysis</p>
      </div>

      <!-- Spotlight Feature Block -->
      @if($featuredReport)
      <div class="research-featured-block">
        <div class="research-featured-left">
          <div class="research-featured-val">{{ $featuredReport['value'] }}</div>
          <div class="research-featured-lbl">{{ $featuredReport['value_label'] }}</div>
        </div>
        <div class="research-featured-right">
          <span class="report-card-badge" style="color: var(--accent-gold);">{{ $featuredReport['category'] }}</span>
          <h3>{{ $featuredReport['title'] }}</h3>
          <p>{{ $featuredReport['desc'] }}</p>
          <a href="#" class="report-btn" style="align-self: flex-start; padding: 0.65rem 2rem;">Read Full Vision Guide</a>
        </div>
      </div>
      @endif

      <!-- Grid of 6 smaller reports -->
      <div class="reports-grid">
        @foreach($marketItems as $item)
          <div class="report-card">
            <span class="report-card-badge" style="color: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">{{ $item['category'] }}</span>
            <h4>{{ $item['title'] }}</h4>
            <p>{{ $item['desc'] }}</p>
            <a href="#" class="report-btn">Access Report</a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ── SECTION 2: EXPORT PROCESS TIMELINE ── -->
  <section class="resources-section resources-section-alt" id="process">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="fas fa-tasks"></i> Step-by-Step Export Process</h2>
        <p>Dynamic timeline mapping and required compliance milestones for Indian exporters</p>
      </div>

      <div class="process-split-layout">
        <!-- Vertical Timeline -->
        <div class="process-timeline">
          @foreach($processSteps as $step)
            <div class="timeline-item">
              <div class="timeline-bullet">{{ $step['step'] }}</div>
              <div class="timeline-card">
                <div class="timeline-header">
                  <h4>{{ $step['title'] }}</h4>
                  <span class="timeline-status">{{ $step['status'] }}</span>
                </div>
                <p>{{ $step['desc'] }}</p>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Sidebar Widgets -->
        <div>
          <!-- Useful Links -->
          @if(count($usefulLinks) > 0)
          <div class="sidebar-card">
            <h4>Quick Links</h4>
            <div class="sidebar-links">
              @foreach($usefulLinks as $link)
                <a href="{{ $link['url'] }}" class="sidebar-link-item"><i class="fas fa-external-link-alt" style="font-size: 0.72rem; color: var(--accent-gold);"></i> {{ $link['title'] }}</a>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Helpdesk Support -->
          <div class="sidebar-card support-block">
            <h4>Single Window Helpdesk</h4>
            <p>Stuck at registering your Import Export Code or mapping organic supply chains? Our support officers are available 24/7 to resolve customs clearances.</p>
            <a href="{{ url('/p/help-support') }}" class="support-btn">Contact Trade Officer</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── SECTION 3: BILATERAL TARIFFS ── -->
  <section class="resources-section" id="tariffs">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="fas fa-percent"></i> Bilateral Tariffs &amp; Trade Agreements (FTAs)</h2>
        <p>Explore instant duty eliminations, agreed HS codes and custom certification criteria</p>
      </div>

      <!-- Tariff Agreements Stats Strip -->
      @if(count($tariffStats) > 0)
      <div class="tariffs-stats-bar">
        @foreach($tariffStats as $stat)
          <div class="tariff-stat-card">
            <div class="tariff-stat-val">{{ $stat['value'] }}</div>
            <div class="tariff-stat-lbl">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
      @endif

      <!-- Tariffs Grid -->
      <div class="tariffs-grid">
        @foreach($tariffItems as $item)
          <div class="tariff-card">
            <div class="tariff-card-header">
              <h4>{{ $item['title'] }}</h4>
              <span class="tariff-badge" style="background: {{ $item['status_color'] ?? 'var(--accent-gold)' }};">{{ $item['status'] }}</span>
            </div>
            <div class="tariff-card-date"><i class="far fa-calendar-alt me-1"></i> {{ $item['date'] }}</div>
            <p>{{ $item['details'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ── SECTION 4: ADVISORIES & FAQS ── -->
  <section class="resources-section resources-section-alt" id="advisories">
    <div class="container">
      <!-- Advisories -->
      @if(count($advisoriesItems) > 0)
      <div class="section-title-wrap" style="margin-bottom: 2.5rem;">
        <h2><i class="fas fa-bullhorn"></i> Live Trade Advisories</h2>
        <p>Active policy warnings, freight delays, and global certification mandates</p>
      </div>
      <div class="advisories-grid" style="margin-bottom: 5rem;">
        @foreach($advisoriesItems as $item)
          <div class="advisory-card">
            <div class="advisory-icon-box">
              <i class="fas {{ $item['icon'] ?? 'fa-bullhorn' }}"></i>
            </div>
            <span class="advisory-card-badge" style="color: {{ $item['color'] ?? 'var(--accent-gold)' }}">{{ $item['category'] }}</span>
            <h4>{{ $item['title'] }}</h4>
            <p>{{ $item['desc'] }}</p>
          </div>
        @endforeach
      </div>
      @endif

      <!-- FAQs -->
      @if(count($faqItems) > 0)
      <div class="section-title-wrap" style="margin-bottom: 2.5rem;">
        <h2><i class="far fa-question-circle"></i> Frequently Asked Questions</h2>
        <p>Clear guidance on registration schemes, tariff schedules and incentive submissions</p>
      </div>
      <div class="faq-grid">
        @foreach($faqItems as $item)
          <div class="faq-card" onclick="toggleFaq(this)">
            <div class="faq-question">
              <span>{{ $item['q'] }}</span>
              <i class="fas fa-chevron-down" style="font-size:0.75rem; color: var(--accent-gold); transition: transform 0.2s;"></i>
            </div>
            <div class="faq-answer" style="display: none;">
              {{ $item['a'] }}
            </div>
          </div>
        @endforeach
      </div>
      @endif
    </div>
  </section>

  <!-- ── METRICS STRIP ── -->
  @if(count($statsCounters) > 0)
  <div class="stats-strip">
    <div class="container stats-grid">
      @foreach($statsCounters as $stat)
        <div>
          <div class="stat-val">{{ $stat['value'] }}</div>
          <div class="stat-lbl">{{ $stat['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- ── SECTION 5: DOWNLOADS ── -->
  <section class="resources-section" id="downloads">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="fas fa-download"></i> Official Downloads &amp; Forms</h2>
        <p>Access ready-to-file registration guidelines, Certificate of Origin templates, and policy SOPs</p>
      </div>

      <!-- Downloads Grid -->
      <div class="downloads-grid">
        @foreach($downloadItems as $item)
          <div class="download-card">
            <div class="download-icon">
              <i class="far fa-file-pdf"></i>
            </div>
            <h4>{{ $item['title'] }}</h4>
            <div class="download-card-meta">
              <span>{{ $item['type'] }} • {{ $item['size'] }}</span>
              <a href="#" class="download-link">Download <i class="fas fa-arrow-down" style="font-size:0.65rem;"></i></a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ── BOTTOM CALLOUT BANNER ── -->
  <div class="bottom-callout-banner">
    <div class="container">
      <h3>Need Custom Trade Advisory Support?</h3>
      <a href="{{ url('/p/help-support') }}" class="btn-bottom-submit">Contact Our Support Team</a>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
// ── SMOOTH SCROLL TO SECTION ──
function scrollToSection(id, btn) {
  const section = document.getElementById(id);
  if (section) {
    const offset = 80;
    const bodyRect = document.body.getBoundingClientRect().top;
    const sectionRect = section.getBoundingClientRect().top;
    const sectionPosition = sectionRect - bodyRect;
    const offsetPosition = sectionPosition - offset;

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });
  }
}

// ── SCROLL SPY DETECT ACTIVE TAB ──
const listSections = ['research', 'process', 'tariffs', 'advisories', 'downloads'];
const tabButtons = document.querySelectorAll('.resources-tab');

window.addEventListener('scroll', () => {
  let activeSec = '';
  
  listSections.forEach(id => {
    const secEl = document.getElementById(id);
    if (secEl) {
      const rect = secEl.getBoundingClientRect();
      if (rect.top <= 150) {
        activeSec = id;
      }
    }
  });
  
  if (!activeSec && listSections.length > 0) {
    activeSec = listSections[0];
  }
  
  tabButtons.forEach((tab, index) => {
    if (listSections[index] === activeSec) {
      tab.classList.add('active');
    } else {
      tab.classList.remove('active');
    }
  });
});

// ── FAQ COLLAPSIBLE ACCORDION ──
function toggleFaq(card) {
  const answer = card.querySelector('.faq-answer');
  const icon = card.querySelector('.fa-chevron-down');
  
  if (answer.style.display === 'none' || answer.style.display === '') {
    answer.style.display = 'block';
    icon.style.transform = 'rotate(180deg)';
  } else {
    answer.style.display = 'none';
    icon.style.transform = 'rotate(0deg)';
  }
}
</script>
@endsection
