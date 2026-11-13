@extends('layouts.app')

@section('title', 'About TEFC — TEFC')

@section('styles')
<style>
/* ── THEME COLORS ── */
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
.about-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 5rem;
}

/* ── HERO BANNER ── */
.about-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.about-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.about-hero-inner {
  position: relative;
  z-index: 2;
}
.about-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.about-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.about-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.about-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.about-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin-bottom: 2.5rem;
}

/* ── HERO STATS STRIP ── */
.hero-stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-top: 2rem;
}
.hero-stat-card {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 12px;
  padding: 1.2rem;
  transition: transform 0.3s;
}
.hero-stat-card:hover {
  transform: translateY(-3px);
  background: rgba(255, 255, 255, 0.12);
}
.hero-stat-val {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--accent-gold-light);
  line-height: 1.2;
}
.hero-stat-lbl {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  opacity: 0.8;
  margin-top: 0.3rem;
}

/* ── STICKY TABS ── */
.about-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 0.8rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.about-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
  justify-content: center;
}
.about-tab {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.3);
  color: var(--text-dark);
  padding: 0.45rem 1.2rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}
.about-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.about-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
}

/* ── SECTION COMMON ── */
.about-section {
  padding: 4.5rem 0;
}
.about-section-alt {
  background: #fff;
  border-top: 1px solid rgba(179, 142, 93, 0.15);
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
}
.sec-head {
  text-align: center;
  margin-bottom: 3.5rem;
}
.sec-head h2 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.5rem;
}
.sec-head p {
  font-size: 0.9rem;
  color: var(--text-muted);
}
.sec-head-line {
  width: 60px;
  height: 4px;
  background: var(--accent-gold);
  margin: 1rem auto 0;
  border-radius: 2px;
}

/* ── OVERVIEW ── */
.overview-content {
  width: 100%;
}
.overview-text h3 {
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1.2rem;
}
.overview-text p {
  font-size: 1.02rem;
  line-height: 1.85;
  color: var(--text-muted);
  margin-bottom: 2.5rem;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.feature-cards-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.feature-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: transform 0.2s;
}
.feature-card:hover {
  transform: translateY(-3px);
  border-color: var(--primary-burgundy);
}
.feature-icon-circle {
  width: 44px;
  height: 44px;
  background: rgba(108, 21, 63, 0.06);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-burgundy);
  font-size: 1.1rem;
  margin-bottom: 1rem;
}
.feature-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.4rem;
}
.feature-card p {
  font-size: 0.78rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin: 0;
}

/* ── MISSION, VISION & OBJECTIVES ── */
.mvo-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}
.mvo-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.02);
  transition: transform 0.3s;
}
.mvo-card:hover {
  transform: translateY(-4px);
}
.mvo-header {
  color: #fff;
  padding: 1.8rem;
  text-align: center;
  position: relative;
}
.mvo-header i {
  font-size: 2.2rem;
  margin-bottom: 0.8rem;
  opacity: 0.9;
}
.mvo-header h3 {
  font-size: 1.2rem;
  font-weight: 800;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.mvo-body {
  padding: 2rem;
}
.mvo-body p {
  font-size: 0.9rem;
  line-height: 1.75;
  color: var(--text-muted);
  margin: 0;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.mvo-list {
  padding-left: 1.2rem;
  margin: 0;
}
.mvo-list li {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin-bottom: 0.8rem;
  line-height: 1.6;
}
.mvo-list li:last-child {
  margin-bottom: 0;
}

/* ── KEY PEOPLE GRID ── */
.people-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2rem;
  max-width: 1280px;
  margin: 0 auto;
}
.person-card {
  flex: 0 1 calc(25% - 1.5rem);
  min-width: 240px;
  max-width: 285px;
  text-align: center;
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 2.2rem 1.4rem;
  transition: all 0.25s ease;
  box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.person-card:hover {
  transform: translateY(-4px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 10px 25px rgba(108, 21, 63, 0.08);
}
.person-avatar-wrapper {
  width: 150px;
  height: 150px;
  background: var(--bg-light);
  border-radius: 50%;
  margin: 0 auto 1.3rem;
  overflow: hidden;
  border: 4px solid var(--accent-gold-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-burgundy);
  box-shadow: 0 4px 15px rgba(179, 142, 93, 0.15);
}
.person-avatar-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.person-card h4 {
  font-size: 1.05rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.35rem;
}
.person-card p {
  font-size: 0.78rem;
  color: var(--text-muted);
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}

.people-footer-about {
  margin-top: 4.5rem;
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  padding: 3rem;
  border-radius: 16px;
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 3.5rem;
  align-items: center;
}
.people-footer-title h3 {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0;
}
.people-footer-title-line {
  width: 45px;
  height: 4px;
  background: var(--accent-gold);
  margin-top: 0.8rem;
  border-radius: 2px;
}
.people-footer-desc p {
  font-size: 0.98rem;
  color: var(--text-muted);
  line-height: 1.85;
  margin: 0 0 1.2rem 0;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.people-footer-desc a {
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--primary-burgundy);
  text-decoration: none;
  border-bottom: 2px solid var(--accent-gold);
  padding-bottom: 2px;
  transition: all 0.2s;
}
.people-footer-desc a:hover {
  color: var(--primary-burgundy-dark);
  border-color: var(--primary-burgundy);
}

/* ── ORGANISATIONAL STRUCTURE CHART ── */
.org-chart-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 2rem;
  width: 100%;
}
.org-node {
  padding: 1.1rem 2.5rem;
  border-radius: 12px;
  font-weight: 800;
  font-size: 0.92rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}
.org-node-level1 {
  background: var(--primary-burgundy);
  color: #fff;
  border: 1px solid var(--primary-burgundy-dark);
}
.org-node-level2 {
  background: var(--primary-burgundy-dark);
  color: #fff;
  border: 1px solid #32091c;
}
.org-node-level3 {
  background: #fff;
  color: var(--primary-burgundy);
  border: 2px solid var(--accent-gold);
}
.org-line {
  width: 2px;
  height: 32px;
  background: var(--accent-gold);
}

/* Tree branches container & connectors */
.org-tree-outer {
  width: 100%;
  overflow-x: auto;
  padding: 0 0 1.5rem;
  -webkit-overflow-scrolling: touch;
}
.org-tree-inner {
  min-width: 960px;
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  padding-top: 32px;
}
.org-stem-line {
  position: absolute;
  top: 0;
  left: 50%;
  width: 2px;
  height: 34px;
  background: var(--accent-gold);
  transform: translateX(-50%);
  z-index: 2;
}
.org-horizontal-bus {
  position: absolute;
  top: 32px;
  left: calc(100% / 12);
  right: calc(100% / 12);
  height: 2px;
  background: var(--accent-gold);
  z-index: 2;
}
.org-tree-branches {
  display: flex;
  width: 100%;
  margin: 0;
  padding: 0;
  list-style: none;
}
.org-tree-col {
  flex: 1 1 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  padding: 0 8px;
}

/* Vertical drop line from horizontal bus into each column's top card */
.org-col-drop-line {
  width: 2px;
  height: 26px;
  background: var(--accent-gold);
  margin: 0 auto;
  flex-shrink: 0;
}

/* Intra-column vertical connecting lines */
.org-node-connector {
  width: 2px;
  height: 20px;
  background: var(--accent-gold);
  margin: 0 auto;
  flex-shrink: 0;
}

/* Branch Cards */
.org-branch-node {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.25);
  border-radius: 10px;
  padding: 0.85rem 0.6rem;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--primary-burgundy);
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.02);
  transition: all 0.2s ease;
  width: 100%;
  min-height: 52px;
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1.35;
}
.org-branch-node:hover {
  border-color: var(--primary-burgundy);
  box-shadow: 0 4px 12px rgba(108, 21, 63, 0.08);
  transform: translateY(-2px);
}
.org-node-cluster {
  background: #fff;
  border-color: rgba(179, 142, 93, 0.4);
  font-weight: 800;
  color: var(--primary-burgundy);
}
.org-node-nodal {
  background: #fdfbf9;
  border-color: rgba(179, 142, 93, 0.25);
}
.org-node-yp {
  background: #fff;
  border-color: rgba(179, 142, 93, 0.25);
  color: #554438;
}

/* ── TIMELINE ── */
.timeline-container {
  position: relative;
  margin-top: 2rem;
}
.timeline-midline {
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--border-color);
  transform: translateX(-50%);
}
.timeline-row-wrapper {
  display: flex;
  width: 100%;
  position: relative;
  margin-bottom: 3.5rem;
}
.timeline-row-wrapper:last-child {
  margin-bottom: 0;
}
.timeline-row-left {
  justify-content: flex-start;
}
.timeline-row-right {
  justify-content: flex-end;
}
.timeline-dot {
  position: absolute;
  left: 50%;
  top: 22px;
  width: 18px;
  height: 18px;
  background: #fff;
  border: 4px solid var(--primary-burgundy);
  border-radius: 50%;
  transform: translateX(-50%);
  z-index: 10;
}
.timeline-card {
  width: 45%;
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.25);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  position: relative;
}
.timeline-card h4 {
  font-size: 1.3rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0 0 0.5rem 0;
}
.timeline-card h5 {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 0.6rem 0;
}
.timeline-card p {
  font-size: 0.84rem;
  color: var(--text-muted);
  line-height: 1.65;
  margin: 0;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
  margin: 0;
}

/* ── ASSISTANCE BANNER ── */
.about-assistance-banner {
  background: var(--primary-burgundy);
  color: #fff;
  text-align: center;
  padding: 4rem 1rem;
  margin-top: 4.5rem;
  border-radius: 24px;
}
.about-assistance-banner h2 {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  letter-spacing: -0.02em;
}
.btn-about-assistance {
  background: #fff;
  color: var(--primary-burgundy);
  border: none;
  padding: 0.8rem 2.5rem;
  border-radius: 30px;
  font-size: 0.95rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  text-decoration: none;
}
.btn-about-assistance:hover {
  background: var(--accent-gold-light);
  color: var(--primary-burgundy);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .hero-stats-grid { grid-template-columns: repeat(2, 1fr); }
  .feature-cards-grid { grid-template-columns: repeat(2, 1fr); }
  .mvo-grid { grid-template-columns: 1fr; }
  .person-card { flex: 0 1 calc(50% - 1.5rem); max-width: 320px; }
  .people-footer-about { grid-template-columns: 1fr; gap: 1.5rem; text-align: center; }
  .people-footer-title-line { margin: 0.8rem auto 0; }
  .timeline-midline { display: none; }
  .timeline-dot { display: none; }
  .timeline-card { width: 100%; }
}
@media (max-width: 768px) {
  .about-hero h1 { font-size: 2.2rem; }
  .about-hero p { font-size: 0.95rem; }
  .feature-cards-grid { grid-template-columns: 1fr; }
  .person-card { flex: 1 1 100%; max-width: 320px; }
}
</style>
@endsection

@section('content')
<div class="about-page-container">

  <!-- Fetch sections with robust fallbacks -->
  @php
    $heroSection = $page->sections->where('type', 'about_hero')->first();
    $heroTitle = $heroSection ? $heroSection->title : 'About TEFC';
    $heroData = $heroSection ? $heroSection->content : [];

    $overviewSection = $page->sections->where('type', 'about_overview')->first();
    $overviewTitle = $overviewSection ? $overviewSection->title : 'Overview';
    $overviewData = $overviewSection ? $overviewSection->content : [];

    $mvoSection = $page->sections->where('type', 'mission_vision')->first();
    $mvoTitle = $mvoSection ? $mvoSection->title : 'Mission, Vision & Objectives';
    $mvoData = $mvoSection ? $mvoSection->content : [];

    $peopleSection = $page->sections->where('type', 'people_grid')->first();
    $peopleTitle = $peopleSection ? $peopleSection->title : 'Key People in Organisation';
    $peopleData = $peopleSection ? $peopleSection->content : [];

    $orgSection = $page->sections->where('type', 'org_structure')->first();
    $orgTitle = $orgSection ? $orgSection->title : 'Organisational Structure';
    $orgData = $orgSection ? $orgSection->content : [];

    $timelineSection = $page->sections->where('type', 'milestone_timeline')->first();
    $timelineTitle = $timelineSection ? $timelineSection->title : 'Key Milestones';
    $timelineData = $timelineSection ? $timelineSection->content : [];

    $assistSection = $page->sections->where('type', 'assistance_banner')->first();
    $assistTitle = $assistSection ? $assistSection->title : 'Want to know more about TEFC? Speak to our team directly.';
    $assistData = $assistSection ? $assistSection->content : [];
  @endphp

  <!-- ── HERO SECTION ── -->
  @if($heroSection)
  <div class="about-hero">
    <div class="container about-hero-inner">
      <div class="about-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>About Us</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroData['subtitle'] ?? 'The Textiles Export Facilitation Centre (TEFC) is a strategic initiative of the Textiles Committee, Ministry of Textiles, Government of India, created to empower MSMEs and textile exporters to compete in the global market.' }}</p>
      
      <!-- Translucent Stats Block -->
      <div class="hero-stats-grid">
        @php
          $defaultStats = [
            ['label' => 'Total MSMEs Empowered', 'value' => '4,500+'],
            ['label' => 'Exporters Supported', 'value' => '1,200+'],
            ['label' => 'Total Trade Facilitated', 'value' => '15,000 Cr'],
            ['label' => 'Founded in Year', 'value' => 'Est. 2022']
          ];
          $stats = $heroData['side_stats'] ?? $defaultStats;
        @endphp
        @foreach($stats as $stat)
          <div class="hero-stat-card">
            <div class="hero-stat-val">{{ $stat['value'] }}</div>
            <div class="hero-stat-lbl">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  @php
    $activeTabs = [];
    if($overviewSection) $activeTabs[] = ['id' => 'overview', 'label' => 'Overview'];
    if($mvoSection) $activeTabs[] = ['id' => 'mvo', 'label' => 'Mission & Vision'];
    if($peopleSection) $activeTabs[] = ['id' => 'people', 'label' => 'Key People'];
    if($orgSection) $activeTabs[] = ['id' => 'org', 'label' => 'Organisation Structure'];
    if($timelineSection) $activeTabs[] = ['id' => 'milestones', 'label' => 'Milestones'];
  @endphp

  @if(count($activeTabs) > 0)
  <!-- ── STICKY TABS STRIP ── -->
  <div class="about-filter-strip">
    <div class="container">
      <div class="about-tabs">
        @foreach($activeTabs as $idx => $tab)
          <button class="about-tab {{ $idx === 0 ? 'active' : '' }}" onclick="scrollToSec('{{ $tab['id'] }}', this)">{{ $tab['label'] }}</button>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  @if($overviewSection)
  <!-- ── OVERVIEW SECTION ── -->
  <section class="about-section" id="overview">
    <div class="container">
      <div class="sec-head">
        <h2>{{ $overviewTitle }}</h2>
        <p>{{ $overviewData['tagline'] ?? "Empowering India's textile heartlands for global reach." }}</p>
        <div class="sec-head-line"></div>
      </div>
      
      <div class="overview-content">
        <div class="overview-text">
          <h3>{{ $overviewData['heading'] ?? 'Facilitating Excellence' }}</h3>
          <p>{!! nl2br(e($overviewData['description'] ?? 'The Textiles Export Facilitation Centre (TEFC) is a dedicated support ecosystem established by the Textiles Committee. We serve as a one-stop-shop for Indian textile exporters, providing technical guidance, market intelligence, and logistical support to ensure that the heritage of Indian textiles reaches every corner of the world.')) !!}</p>
        </div>
          
        <div class="feature-cards-grid">
          @php
            $defaultFeatures = [
              ['icon' => 'fas fa-globe', 'title' => 'Global Market Access', 'text' => 'Connecting local weavers to international buyers through verified trade portals.'],
              ['icon' => 'fas fa-hand-holding-heart', 'title' => 'Handholding Support', 'text' => 'End-to-end guidance from registration to final export documentation.'],
              ['icon' => 'fas fa-chart-line', 'title' => 'Capacity Building', 'text' => 'Regular workshops on quality standards and global market trends.'],
              ['icon' => 'fas fa-file-contract', 'title' => 'Policy Guidance', 'text' => 'Detailed information on FTAs and government scheme benefits.']
            ];
            $features = $overviewData['features'] ?? $defaultFeatures;
          @endphp
          @foreach($features as $feature)
            <div class="feature-card">
              <div class="feature-icon-circle"><i class="{{ $feature['icon'] }}"></i></div>
              <h4>{{ $feature['title'] }}</h4>
              <p>{{ $feature['text'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  @if($mvoSection)
  <!-- ── MISSION, VISION & OBJECTIVES ── -->
  <section class="about-section about-section-alt" id="mvo">
    <div class="container">
      <div class="sec-head">
        <h2>{{ $mvoTitle }}</h2>
        <p>{{ $mvoData['tagline'] ?? "Our roadmap for India's textile export future." }}</p>
        <div class="sec-head-line"></div>
      </div>
      
      <div class="mvo-grid">
        <!-- Mission Card -->
        <div class="mvo-card">
          <div class="mvo-header" style="background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);">
            <i class="fas fa-bullseye"></i>
            <h3>Our Mission</h3>
          </div>
          <div class="mvo-body">
            <p>{{ $mvoData['mission'] ?? 'To facilitate and strengthen the export ecosystem of India\'s textile industry by providing technical support, market intelligence, quality certification, and policy guidance—enabling growth for MSMEs and established exporters.' }}</p>
          </div>
        </div>
        
        <!-- Vision Card -->
        <div class="mvo-card">
          <div class="mvo-header" style="background: linear-gradient(135deg, #1f3c72 0%, #2a5298 100%);">
            <i class="fas fa-eye"></i>
            <h3>Our Vision</h3>
          </div>
          <div class="mvo-body">
            <p>{{ $mvoData['vision'] ?? 'To emerge as India\'s premier destination for high-quality, sustainable, and competitive textile exports, serving as the bridge between Indian craftsmanship and global demand.' }}</p>
          </div>
        </div>
        
        <!-- Objectives Card -->
        <div class="mvo-card">
          <div class="mvo-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="fas fa-list-check"></i>
            <h3>Our Objectives</h3>
          </div>
          <div class="mvo-body">
            <ul class="mvo-list">
              @php
                $defaultObjs = [
                  'Empower MSMEs through direct export assistance.',
                  'Maintain world-class quality standards through certification.',
                  'Facilitate trade through Free Trade Agreements (FTAs).',
                  'Foster innovation in textile design and manufacturing.'
                ];
                $objs = $mvoData['objectives'] ?? $defaultObjs;
              @endphp
              @foreach($objs as $obj)
                <li>{{ $obj }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

  @if($peopleSection)
  <!-- ── KEY PEOPLE GRID ── -->
  <section class="about-section" id="people">
    <div class="container">
      <div class="sec-head">
        <h2>{{ $peopleTitle }}</h2>
        <p>{{ $peopleData['tagline'] ?? 'Guiding the strategic export initiatives of India.' }}</p>
        <div class="sec-head-line"></div>
      </div>
      
      <div class="people-grid">
        @php
          $defaultPeople = [
            ['name' => 'Shri. Ajit Singh', 'designation' => 'Chairman, Textiles Committee', 'image' => ''],
            ['name' => 'Smt. Roopa Dutta', 'designation' => 'Member Secretary', 'image' => ''],
            ['name' => 'Shri. Kartikeya Mishra', 'designation' => 'Director (Exports)', 'image' => ''],
            ['name' => 'Dr. Meena Rani', 'designation' => 'Nodal Officer, TEFC', 'image' => '']
          ];
          $people = $peopleData['people'] ?? $defaultPeople;
        @endphp
        @foreach($people as $person)
          <div class="person-card">
            <div class="person-avatar-wrapper">
              @if(isset($person['image']) && $person['image'])
                <img src="{{ asset($person['image']) }}" alt="{{ trim($person['name'] ?? '') }}">
              @else
                <i class="fas fa-user-tie fa-3x" style="opacity: 0.85;"></i>
              @endif
            </div>
            <h4>{{ trim($person['name'] ?? '') }}</h4>
            <p>{{ trim($person['designation'] ?? '') }}</p>
          </div>
        @endforeach
      </div>

      <!-- About Us Quote / Footer -->
      <div class="people-footer-about">
        <div class="people-footer-title">
          <h3>Statutory Oversight</h3>
          <div class="people-footer-title-line"></div>
        </div>
        <div class="people-footer-desc">
          <p>{{ $peopleData['footer_about'] ?? 'The Textiles Committee (TEFC) was established by the Textiles Committee Act, 1963. We are a statutory body under the Ministry of Textiles, Government of India. Our primary objective is to ensure the quality of textiles and textile machinery both for internal consumption and export.' }}</p>
          <a href="javascript:void(0)" onclick="scrollToSec('org')">View Team Hierarchy <i class="fas fa-arrow-down" style="font-size:0.75rem; margin-left:4px;"></i></a>
        </div>
      </div>
    </div>
  </section>
  @endif

  @if($orgSection)
  <!-- ── ORGANISATIONAL STRUCTURE CHART ── -->
  <section class="about-section about-section-alt" id="org">
    <div class="container">
      <div class="sec-head">
        <h2>{{ $orgTitle }}</h2>
        <p>{{ $orgData['tagline'] ?? 'Our administrative and operational framework.' }}</p>
        <div class="sec-head-line"></div>
      </div>
      
      <div class="org-chart-wrapper">
        <div class="org-node org-node-level1">{{ $orgData['level_1'] ?? 'Ministry of Textiles, Govt. of India' }}</div>
        <div class="org-line"></div>
        <div class="org-node org-node-level2">{{ $orgData['level_2'] ?? 'Textiles Committee' }}</div>
        <div class="org-line"></div>
        <div class="org-node org-node-level3">{{ $orgData['level_3'] ?? 'Textiles Export Facilitation Center (TEFC)' }}</div>
        
        <div class="org-tree-outer">
          <div class="org-tree-inner">
            @php
              $branchesRaw = $orgData['branches'] ?? ['Ludhiana', 'Varanasi', 'Jaipur', 'Surat', 'Ichalkaranji', 'Karur'];
              $colCount = 6;
              $columns = [];
              if (count($branchesRaw) >= 18) {
                  for ($i = 0; $i < $colCount; $i++) {
                      $columns[] = [
                          'cluster' => $branchesRaw[$i] ?? ('Cluster ' . ($i + 1)),
                          'nodal'   => $branchesRaw[$i + $colCount] ?? 'Nodal Officer (TEFC)',
                          'yp'      => $branchesRaw[$i + ($colCount * 2)] ?? 'Young Professional',
                      ];
                  }
              } elseif (count($branchesRaw) >= 12) {
                  for ($i = 0; $i < $colCount; $i++) {
                      $columns[] = [
                          'cluster' => $branchesRaw[$i] ?? ('Cluster ' . ($i + 1)),
                          'nodal'   => $branchesRaw[$i + $colCount] ?? 'Nodal Officer (TEFC)',
                          'yp'      => 'Young Professional',
                      ];
                  }
              } else {
                  for ($i = 0; $i < min(count($branchesRaw), $colCount); $i++) {
                      $columns[] = [
                          'cluster' => $branchesRaw[$i],
                          'nodal'   => 'Nodal Officer (TEFC)',
                          'yp'      => 'Young Professional',
                      ];
                  }
                  if (empty($columns)) {
                      foreach (['Ludhiana', 'Varanasi', 'Jaipur', 'Surat', 'Ichalkaranji', 'Karur'] as $c) {
                          $columns[] = [
                              'cluster' => $c,
                              'nodal'   => 'Nodal Officer (TEFC)',
                              'yp'      => 'Young Professional',
                          ];
                      }
                  }
              }
            @endphp

            <!-- Single continuous stem and horizontal bus bar -->
            <div class="org-stem-line"></div>
            <div class="org-horizontal-bus" style="left: calc(100% / {{ count($columns) * 2 }}); right: calc(100% / {{ count($columns) * 2 }});"></div>

            <div class="org-tree-branches">
              @foreach($columns as $col)
                <div class="org-tree-col">
                  <!-- Drop line from horizontal bus into cluster card -->
                  <div class="org-col-drop-line"></div>

                  <!-- Tier 1: Cluster Name -->
                  <div class="org-branch-node org-node-cluster">{{ $col['cluster'] }}</div>

                  <!-- Connector line -->
                  <div class="org-node-connector"></div>

                  <!-- Tier 2: Nodal Officer -->
                  <div class="org-branch-node org-node-nodal">{{ $col['nodal'] }}</div>

                  <!-- Connector line -->
                  <div class="org-node-connector"></div>

                  <!-- Tier 3: Young Professional -->
                  <div class="org-branch-node org-node-yp">{{ $col['yp'] }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

  @if($timelineSection)
  <!-- ── TIMELINE SECTION ── -->
  <section class="about-section" id="milestones">
    <div class="container">
      <div class="sec-head">
        <h2>{{ $timelineTitle }}</h2>
        <p>{{ $timelineData['tagline'] ?? 'A journey of textile excellence and global facilitation.' }}</p>
        <div class="sec-head-line"></div>
      </div>
      
      <div class="timeline-container">
        <div class="timeline-midline"></div>
        
        <div class="timeline">
          @php
            $defaultMilestones = [
              ['year' => '2022', 'title' => 'TEFC Foundation', 'text' => 'The Textiles Committee officially announced the formation of the Export Facilitation Centre to support MSMEs.'],
              ['year' => '2023', 'title' => 'Digital Portal Launch', 'text' => 'The first version of the TEFC digital portal was launched, connecting 500+ sellers.'],
              ['year' => '2024', 'title' => 'Global Expansion', 'text' => 'Strategic MoUs signed with international trade bodies in Europe and the Middle East.'],
              ['year' => '2025', 'title' => 'AI-Powered Insights', 'text' => 'Integrated advanced trade analytics to help exporters predict global demand trends.']
            ];
            $milestones = $timelineData['milestones'] ?? $defaultMilestones;
          @endphp
          @foreach($milestones as $index => $item)
            <div class="timeline-row-wrapper {{ $index % 2 == 0 ? 'timeline-row-left' : 'timeline-row-right' }}">
              <div class="timeline-dot"></div>
              <div class="timeline-card">
                <h4>{{ $item['year'] }}</h4>
                <h5>{{ $item['title'] }}</h5>
                <p>{{ $item['text'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  @if($assistSection)
  <!-- ── ASSISTANCE CALLOUT BANNER ── -->
  <div class="about-assistance-wrapper" style="padding: 2rem 0 4rem 0;">
    <div class="container">
      <div class="about-assistance-banner">
        <h2>{{ $assistTitle }}</h2>
        <a href="{{ $assistData['btn_url'] ?? url('/p/help-support') }}" class="btn-about-assistance">{{ $assistData['btn_text'] ?? 'Contact Us' }}</a>
      </div>
    </div>
  </div>
  @endif

</div>
@endsection

@section('scripts')
<script>
// ── SMOOTH SCROLL TO SECTION ──
function scrollToSec(id, el) {
  const element = document.getElementById(id);
  if (element) {
    const offset = 80;
    const bodyRect = document.body.getBoundingClientRect().top;
    const elementRect = element.getBoundingClientRect().top;
    const elementPosition = elementRect - bodyRect;
    const offsetPosition = elementPosition - offset;

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });
  }
}

// ── TAB ACTIVE CLASS HIGHLIGHT ON SCROLL ──
const sections = @json(array_column($activeTabs ?? [], 'id'));
const tabs = document.querySelectorAll('.about-tab');

window.addEventListener('scroll', () => {
  if (!sections || sections.length === 0) return;
  let current = '';
  
  sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      const rect = el.getBoundingClientRect();
      // Section is at least partially in the upper part of the viewport
      if (rect.top <= 150) {
        current = id;
      }
    }
  });
  
  if (!current && sections.length > 0) {
    current = sections[0]; // fallback to first
  }
  
  tabs.forEach((tab, i) => {
    if (sections[i] === current) {
      tab.classList.add('active');
    } else {
      tab.classList.remove('active');
    }
  });
});
</script>
@endsection
