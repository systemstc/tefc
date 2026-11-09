@extends('layouts.app')

@section('title', 'News & Updates — TEFC')

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
.news-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 0;
}

/* ── HERO BANNER ── */
.news-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.news-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.news-hero-inner {
  position: relative;
  z-index: 2;
}
.news-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.news-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.news-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.news-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.news-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin: 0;
}

/* ── TICKER BANNER ── */
.news-ticker-strip {
  background: var(--primary-burgundy-dark);
  border-top: 1px solid rgba(179, 142, 93, 0.25);
  border-bottom: 1px solid rgba(179, 142, 93, 0.25);
  padding: 0.6rem 0;
  color: #fff;
  overflow: hidden;
}
.ticker-flex {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}
.ticker-badge {
  background: var(--accent-gold);
  color: #fff;
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.25rem 0.8rem;
  border-radius: 4px;
  white-space: nowrap;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}
.ticker-slider {
  flex: 1;
  overflow: hidden;
  position: relative;
}
.ticker-track {
  display: flex;
  gap: 2.5rem;
  white-space: nowrap;
  animation: scrollTicker 35s linear infinite;
}
.ticker-track:hover {
  animation-play-state: paused;
}
.ticker-item {
  font-size: 0.82rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}
.ticker-item span {
  color: var(--accent-gold-light);
  font-weight: 800;
}
@keyframes scrollTicker {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

/* ── STICKY TAB NAV ── */
.news-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 1.1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.news-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
  justify-content: center;
}
.news-tab {
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
.news-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.news-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
  box-shadow: 0 4px 12px rgba(108, 21, 63, 0.15);
}

/* ── GRID COMMON ── */
.news-section {
  padding: 4.5rem 0;
}
.news-section-alt {
  background: #f6efe6;
}
.section-title-wrap {
  margin-bottom: 2.5rem;
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

/* ── SECTION 1: LATEST NEWS ── */
.latest-grid-layout {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 2.5rem;
}
.latest-featured-card {
  background: var(--primary-burgundy-dark);
  border: 1px solid rgba(179, 142, 93, 0.3);
  border-radius: 16px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  min-height: 480px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}
.featured-image-bg {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  transition: transform 0.5s;
}
.latest-featured-card:hover .featured-image-bg {
  transform: scale(1.03);
}
.featured-image-bg::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(74, 14, 43, 0.95) 0%, rgba(74, 14, 43, 0.4) 60%, transparent 100%);
}
.featured-text-content {
  position: relative;
  z-index: 2;
  padding: 2.5rem;
  color: #fff;
}
.featured-category-badge {
  background: var(--accent-gold);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 800;
  padding: 0.3rem 0.8rem;
  border-radius: 4px;
  text-transform: uppercase;
  margin-bottom: 1rem;
  display: inline-block;
}
.featured-text-content h3 {
  font-size: 1.6rem;
  font-weight: 800;
  line-height: 1.35;
  margin-bottom: 1rem;
  color: #fff;
}
.featured-text-content p {
  font-size: 0.9rem;
  opacity: 0.85;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}
.featured-meta {
  display: flex;
  gap: 1.5rem;
  font-size: 0.78rem;
  opacity: 0.8;
}

.latest-side-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}
.side-news-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.2rem;
  display: flex;
  gap: 1rem;
  transition: all 0.2s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  text-decoration: none;
  color: inherit;
}
.side-news-card:hover {
  transform: translateX(4px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 8px 25px rgba(108, 21, 63, 0.05);
}
.side-card-icon {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(108, 21, 63, 0.06);
  color: var(--primary-burgundy);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 1rem;
}
.side-card-details {
  flex: 1;
}
.side-card-badge {
  font-size: 0.65rem;
  font-weight: 800;
  text-transform: uppercase;
  margin-bottom: 0.25rem;
  display: inline-block;
}
.side-card-title {
  font-size: 0.88rem;
  font-weight: 700;
  line-height: 1.4;
  color: var(--text-dark);
  margin-bottom: 0.3rem;
}
.side-card-date {
  font-size: 0.72rem;
  color: var(--text-muted);
}

/* ── SECTION 2: IN FOCUS ── */
.infocus-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.8rem;
}
.infocus-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 16px;
  padding: 2rem;
  transition: all 0.3s;
  box-shadow: 0 10px 30px rgba(0,0,0,0.01);
  display: flex;
  flex-direction: column;
}
.infocus-card:hover {
  transform: translateY(-5px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 15px 35px rgba(108, 21, 63, 0.08);
}
.infocus-icon-box {
  width: 54px;
  height: 54px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.3rem;
  margin-bottom: 1.5rem;
}
.infocus-card-badge {
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  color: var(--accent-gold);
  letter-spacing: 0.03em;
  margin-bottom: 0.5rem;
}
.infocus-card h4 {
  font-size: 1.05rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  line-height: 1.4;
  margin-bottom: 0.8rem;
}
.infocus-card p {
  font-size: 0.8rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 1.5rem;
  flex: 1;
}
.infocus-readmore {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.78rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  text-decoration: none;
}
.infocus-readmore:hover {
  color: var(--accent-gold);
}

/* ── DOMESTIC VS INTERNATIONAL SPLIT ── */
.split-news-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
}
.split-column-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 2px solid rgba(179, 142, 93, 0.25);
  padding-bottom: 0.8rem;
  margin-bottom: 1.5rem;
}
.split-column-title {
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--primary-burgundy);
}
.split-column-count {
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--text-muted);
}
.news-numbered-list {
  display: flex;
  flex-direction: column;
}
.numbered-news-item {
  display: flex;
  gap: 1.2rem;
  padding: 1.2rem 0;
  border-bottom: 1px dashed rgba(179, 142, 93, 0.2);
  align-items: flex-start;
  transition: background-color 0.2s;
}
.numbered-news-item:hover {
  background-color: rgba(108, 21, 63, 0.01);
}
.numbered-news-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--accent-gold);
  opacity: 0.6;
  line-height: 1;
  width: 30px;
}
.numbered-news-details {
  flex: 1;
}
.numbered-news-top {
  display: flex;
  gap: 8px;
  align-items: center;
  margin-bottom: 0.3rem;
}
.numbered-news-badge {
  background: rgba(108, 21, 63, 0.05);
  color: var(--primary-burgundy);
  font-size: 0.65rem;
  font-weight: 800;
  padding: 1px 6px;
  border-radius: 3px;
  text-transform: uppercase;
}
.numbered-news-date {
  font-size: 0.68rem;
  color: var(--text-muted);
}
.numbered-news-title {
  font-size: 0.88rem;
  font-weight: 700;
  line-height: 1.45;
  color: var(--text-dark);
}

/* ── MOST POPULAR & SIDEBAR ── */
.popular-split-layout {
  display: grid;
  grid-template-columns: 1.8fr 1fr;
  gap: 3.5rem;
}
.popular-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.popular-item-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.2rem 1.5rem;
  display: flex;
  gap: 1.2rem;
  align-items: center;
  transition: all 0.2s;
}
.popular-item-card:hover {
  transform: translateX(4px);
  border-color: var(--primary-burgundy);
}
.popular-num-circle {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.1rem;
  font-weight: 800;
  flex-shrink: 0;
}
.popular-item-details {
  flex: 1;
}
.popular-item-badge {
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 0.03em;
  margin-bottom: 0.2rem;
  display: inline-block;
}
.popular-item-title {
  font-size: 0.9rem;
  font-weight: 750;
  line-height: 1.4;
  color: var(--text-dark);
}

/* SIDEBAR CARDS */
.sidebar-wrapper {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}
.newsletter-card {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  border: 2px solid var(--accent-gold);
  border-radius: 16px;
  padding: 2rem;
  color: #fff;
  position: relative;
  overflow: hidden;
}
.newsletter-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 10% 90%, rgba(255,255,255,0.03) 0%, transparent 60%);
}
.newsletter-card h4 {
  font-size: 1.2rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 0.5rem;
}
.newsletter-card p {
  font-size: 0.78rem;
  opacity: 0.85;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}
.newsletter-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}
.newsletter-input {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 6px;
  padding: 0.6rem 1rem;
  font-size: 0.82rem;
  color: #fff;
}
.newsletter-input::placeholder {
  color: rgba(255,255,255,0.5);
}
.newsletter-btn {
  background: #fff;
  color: var(--primary-burgundy);
  border: none;
  padding: 0.65rem;
  border-radius: 6px;
  font-size: 0.82rem;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.2s;
}
.newsletter-btn:hover {
  background: var(--accent-gold-light);
}

.events-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 1.8rem;
}
.events-card h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
  padding-bottom: 0.8rem;
  margin-bottom: 1.2rem;
}
.event-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  padding-bottom: 1rem;
  margin-bottom: 1rem;
  border-bottom: 1px dashed rgba(179, 142, 93, 0.12);
}
.event-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
  margin-bottom: 0;
}
.event-item-title {
  font-size: 0.82rem;
  font-weight: 800;
  color: var(--text-dark);
}
.event-item-meta {
  font-size: 0.72rem;
  color: var(--text-muted);
}

/* ── TRENDING ARTICLES ── */
.trending-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.trending-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: all 0.2s;
}
.trending-card:hover {
  transform: translateY(-3px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 8px 25px rgba(108, 21, 63, 0.05);
}
.trending-img-box {
  height: 140px;
  background-size: cover;
  background-position: center;
  position: relative;
}
.trending-card-body {
  padding: 1.2rem;
}
.trending-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.65rem;
  font-weight: 800;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}
.trending-card-title {
  font-size: 0.82rem;
  font-weight: 800;
  line-height: 1.4;
  color: var(--text-dark);
  margin-bottom: 0.8rem;
  height: 2.8rem;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* ── METRICS FOOTER STRIP ── */
.metrics-strip {
  background: var(--primary-burgundy);
  border-top: 1px solid rgba(179, 142, 93, 0.2);
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 2.2rem 0;
  color: #fff;
}
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1.5rem;
  text-align: center;
}
.metric-val {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--accent-gold-light);
}
.metric-lbl {
  font-size: 0.72rem;
  text-transform: uppercase;
  opacity: 0.85;
  margin-top: 0.3rem;
  letter-spacing: 0.02em;
}

.press-tip-banner {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  color: #fff;
  padding: 3.5rem 0;
  text-align: center;
}
.press-tip-banner h3 {
  font-size: 1.6rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
}
.btn-press-submit {
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
.btn-press-submit:hover {
  background: var(--accent-gold-light);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .latest-grid-layout { grid-template-columns: 1fr; }
  .infocus-grid { grid-template-columns: repeat(2, 1fr); }
  .split-news-layout { grid-template-columns: 1fr; gap: 2.5rem; }
  .popular-split-layout { grid-template-columns: 1fr; }
  .trending-grid { grid-template-columns: repeat(2, 1fr); }
  .metrics-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
  .trending-grid { grid-template-columns: 1fr; }
  .infocus-grid { grid-template-columns: 1fr; }
  .metrics-grid { grid-template-columns: repeat(2, 1fr); }
  .news-hero h1 { font-size: 2.2rem; }
}
</style>
@endsection

@section('content')
@php
  // Fetch news page sections
  $heroSec = $page->sections->where('type', 'news_hero')->first();
  $latestSec = $page->sections->where('type', 'news_latest')->first();
  $infocusSec = $page->sections->where('type', 'news_infocus')->first();
  $listsSec = $page->sections->where('type', 'news_lists')->first();
  $popularSec = $page->sections->where('type', 'news_popular')->first();
  $trendingSec = $page->sections->where('type', 'news_trending')->first();
  $metricsSec = $page->sections->where('type', 'news_metrics')->first();

  // ── HERO FALLBACK ──
  $heroTitle = $heroSec ? ($heroSec->content['title'] ?? 'News & Updates') : 'News & Updates';
  $heroSubtitle = $heroSec ? ($heroSec->content['subtitle'] ?? 'Stay informed with the latest domestic and international textile industry news, policy updates, trade developments, and export opportunities.') : 'Stay informed with the latest domestic and international textile industry news, policy updates, trade developments, and export opportunities.';
  $tickerLabel = $heroSec ? ($heroSec->content['ticker_label'] ?? '🔴 Breaking News') : '🔴 Breaking News';
  $tickerItems = $heroSec ? ($heroSec->content['ticker_items'] ?? []) : [];

  // ── LATEST FALLBACK ──
  $latestFeatured = $latestSec ? ($latestSec->content['featured'] ?? null) : null;
  $latestItems = $latestSec ? ($latestSec->content['items'] ?? []) : [];

  // ── INFOCUS FALLBACK ──
  $infocusSubtitle = $infocusSec ? ($infocusSec->content['subtitle'] ?? '') : '';
  $infocusItems = $infocusSec ? ($infocusSec->content['items'] ?? []) : [];

  // ── LISTS FALLBACK ──
  $listsDomestic = $listsSec ? ($listsSec->content['domestic'] ?? []) : [];
  $listsIntl = $listsSec ? ($listsSec->content['international'] ?? []) : [];

  // ── POPULAR FALLBACK ──
  $popularItems = $popularSec ? ($popularSec->content['popular'] ?? []) : [];
  $eventsItems = $popularSec ? ($popularSec->content['events'] ?? []) : [];

  // ── TRENDING FALLBACK ──
  $trendingItems = $trendingSec ? ($trendingSec->content['items'] ?? []) : [];

  // ── METRICS FALLBACK ──
  $metricsStats = $metricsSec ? ($metricsSec->content['stats'] ?? []) : [];
  $metricsFooterTitle = $metricsSec ? ($metricsSec->content['footer_title'] ?? '') : '';
  $metricsBtnText = $metricsSec ? ($metricsSec->content['button_text'] ?? '') : '';
@endphp

<div class="news-page-container">

  <!-- ── HERO BANNER ── -->
  <div class="news-hero">
    <div class="container news-hero-inner">
      <div class="news-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>News</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroSubtitle }}</p>
    </div>
  </div>

  <!-- ── TICKER STRIP ── -->
  @if(count($tickerItems) > 0)
  <div class="news-ticker-strip">
    <div class="container ticker-flex">
      <div class="ticker-badge">{{ $tickerLabel }}</div>
      <div class="ticker-slider">
        <div class="ticker-track">
          @foreach($tickerItems as $item)
            <div class="ticker-item">
              <span>{{ $item['category'] }}:</span> {{ $item['text'] }}
            </div>
          @endforeach
          {{-- Duplicate items for seamless continuous marquee loop --}}
          @foreach($tickerItems as $item)
            <div class="ticker-item">
              <span>{{ $item['category'] }}:</span> {{ $item['text'] }}
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- ── STICKY TAB NAV ── -->
  <div class="news-filter-strip">
    <div class="container news-tabs">
      <button class="news-tab active" onclick="scrollToSec('latest', this)"><i class="far fa-newspaper me-1"></i> Latest News</button>
      <button class="news-tab" onclick="scrollToSec('infocus', this)"><i class="far fa-star me-1"></i> In Focus</button>
      <button class="news-tab" onclick="scrollToSec('split-lists', this)"><i class="fas fa-globe me-1"></i> Regional News</button>
      <button class="news-tab" onclick="scrollToSec('popular-sec', this)"><i class="far fa-heart me-1"></i> Most Popular</button>
      <button class="news-tab" onclick="scrollToSec('trending-sec', this)"><i class="fas fa-chart-line me-1"></i> Trending</button>
    </div>
  </div>

  <!-- ── SECTION 1: LATEST NEWS ── -->
  <section class="news-section" id="latest">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="far fa-newspaper"></i> Latest News</h2>
        <p>Real-time updates on textile policy, trade, market &amp; export developments</p>
      </div>

      <div class="latest-grid-layout">
        <!-- Grand Featured Spotlight Card -->
        @if($latestFeatured)
        <div class="latest-featured-card">
          <div class="featured-image-bg" style="background-image: url('{{ $latestFeatured['image'] }}');"></div>
          <div class="featured-text-content">
            <span class="featured-category-badge">{{ $latestFeatured['category'] }}</span>
            <h3>{{ $latestFeatured['title'] }}</h3>
            <p>{{ $latestFeatured['desc'] }}</p>
            <div class="featured-meta">
              <span><i class="far fa-calendar-alt me-1"></i> {{ $latestFeatured['date'] }}</span>
              <span><i class="far fa-clock me-1"></i> {{ $latestFeatured['duration'] }}</span>
            </div>
          </div>
        </div>
        @endif

        <!-- Side news cards list -->
        <div class="latest-side-list">
          @foreach($latestItems as $index => $item)
            <a href="#" class="side-news-card">
              <div class="side-card-icon" style="color: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">
                <i class="fas fa-bullhorn"></i>
              </div>
              <div class="side-card-details">
                <span class="side-card-badge" style="color: {{ $item['color'] ?? 'var(--accent-gold)' }}">{{ $item['category'] }}</span>
                <h4 class="side-card-title">{{ $item['title'] }}</h4>
                <div class="side-card-date"><i class="far fa-calendar-alt me-1"></i> {{ $item['date'] }}</div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- ── SECTION 2: IN FOCUS ── -->
  @if(count($infocusItems) > 0)
  <section class="news-section news-section-alt" id="infocus">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="far fa-star"></i> In Focus</h2>
        <p>{{ $infocusSubtitle }}</p>
      </div>

      <div class="infocus-grid">
        @foreach($infocusItems as $item)
          <div class="infocus-card">
            <div class="infocus-icon-box" style="background: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">
              <i class="fas {{ $item['icon'] ?? 'fa-chart-line' }}"></i>
            </div>
            <span class="infocus-card-badge">{{ $item['category'] }}</span>
            <h4>{{ $item['title'] }}</h4>
            <p>{{ $item['desc'] }}</p>
            <a href="#" class="infocus-readmore">Read Full Report <i class="fas fa-arrow-right"></i></a>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ── SECTION 3: DOMESTIC VS INTERNATIONAL SPLIT ── -->
  <section class="news-section" id="split-lists">
    <div class="container">
      <div class="split-news-layout">
        <!-- Domestic News -->
        <div>
          <div class="split-column-header">
            <span class="split-column-title"><i class="fas fa-building me-2"></i> Domestic News</span>
            <span class="split-column-count">{{ count($listsDomestic) }} Articles</span>
          </div>
          <div class="news-numbered-list">
            @foreach($listsDomestic as $i => $item)
              <div class="numbered-news-item">
                <div class="numbered-news-num">{{ sprintf("%02d", $i + 1) }}</div>
                <div class="numbered-news-details">
                  <div class="numbered-news-top">
                    <span class="numbered-news-badge">{{ $item['badge'] }}</span>
                    <span class="numbered-news-date"><i class="far fa-calendar-alt me-1"></i> {{ $item['date'] }}</span>
                  </div>
                  <h4 class="numbered-news-title">{{ $item['title'] }}</h4>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- International News -->
        <div>
          <div class="split-column-header">
            <span class="split-column-title"><i class="fas fa-globe-americas me-2"></i> International News</span>
            <span class="split-column-count">{{ count($listsIntl) }} Articles</span>
          </div>
          <div class="news-numbered-list">
            @foreach($listsIntl as $i => $item)
              <div class="numbered-news-item">
                <div class="numbered-news-num">{{ sprintf("%02d", $i + 1) }}</div>
                <div class="numbered-news-details">
                  <div class="numbered-news-top">
                    <span class="numbered-news-badge">{{ $item['badge'] }}</span>
                    <span class="numbered-news-date"><i class="far fa-calendar-alt me-1"></i> {{ $item['date'] }}</span>
                  </div>
                  <h4 class="numbered-news-title">{{ $item['title'] }}</h4>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── SECTION 4: MOST POPULAR & SIDEBAR ── -->
  <section class="news-section news-section-alt" id="popular-sec">
    <div class="container">
      <div class="popular-split-layout">
        <!-- Most Popular items -->
        <div>
          <div class="section-title-wrap" style="margin-bottom: 2rem;">
            <h2><i class="far fa-heart"></i> Most Popular</h2>
            <p>The most read publications and application guides across our portal</p>
          </div>
          <div class="popular-list">
            @foreach($popularItems as $i => $item)
              <div class="popular-item-card">
                <div class="popular-num-circle" style="background: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">
                  {{ $i + 1 }}
                </div>
                <div class="popular-item-details">
                  <span class="popular-item-badge" style="color: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">{{ $item['badge'] }}</span>
                  <h4 class="popular-item-title">{{ $item['title'] }}</h4>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Sidebar Events & Subscription -->
        <div class="sidebar-wrapper">
          <!-- Newsletter Digest -->
          <div class="newsletter-card">
            <h4>TEFC News Digest</h4>
            <p>Receive exclusive textile trade bulletins, revised incentive alerts and bilateral policy reviews directly in your inbox.</p>
            <div class="newsletter-input-group">
              <input type="email" class="newsletter-input" placeholder="Enter your business email...">
              <button class="newsletter-btn">Subscribe Free</button>
            </div>
          </div>

          <!-- Upcoming Events -->
          @if(count($eventsItems) > 0)
          <div class="events-card">
            <h4><i class="far fa-calendar-check me-2"></i> Upcoming Events</h4>
            @foreach($eventsItems as $event)
              <div class="event-item">
                <h5 class="event-item-title">{{ $event['title'] }}</h5>
                <div class="event-item-meta"><i class="far fa-clock me-1"></i> {{ $event['date'] }}</div>
                <div class="event-item-meta"><i class="fas fa-map-marker-alt me-1"></i> {{ $event['location'] }}</div>
              </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- ── SECTION 5: TRENDING ARTICLES ── -->
  @if(count($trendingItems) > 0)
  <section class="news-section" id="trending-sec">
    <div class="container">
      <div class="section-title-wrap">
        <h2><i class="fas fa-chart-line"></i> Trending Articles</h2>
        <p>Highly shared briefings on sustainable fibers and digital weaves</p>
      </div>

      <div class="trending-grid">
        @foreach($trendingItems as $item)
          <div class="trending-card">
            <div class="trending-img-box" style="background-image: url('{{ $item['image'] }}');"></div>
            <div class="trending-card-body">
              <div class="trending-card-meta">
                <span style="color: {{ $item['color'] ?? 'var(--primary-burgundy)' }}">{{ $item['category'] }}</span>
                <span class="text-muted">{{ $item['date'] }}</span>
              </div>
              <h4 class="trending-card-title">{{ $item['title'] }}</h4>
              <a href="#" class="infocus-readmore" style="font-size: 0.72rem;">Read Briefing <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ── METRICS FOOTER STRIP ── -->
  @if(count($metricsStats) > 0)
  <div class="metrics-strip">
    <div class="container metrics-grid">
      @foreach($metricsStats as $stat)
        <div>
          <div class="metric-val">{{ $stat['value'] }}</div>
          <div class="metric-lbl">{{ $stat['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- ── PRESS TIP BANNER ── -->
  @if($metricsFooterTitle)
  <div class="press-tip-banner">
    <div class="container">
      <h3>{{ $metricsFooterTitle }}</h3>
      <a href="{{ url('/p/help-support') }}" class="btn-press-submit">{{ $metricsBtnText }}</a>
    </div>
  </div>
  @endif

</div>
@endsection

@section('scripts')
<script>
// ── SMOOTH SCROLL TO ANCHOR ──
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

// ── SCROLL SPY DETECT ACTIVE TAB ──
const sections = ['latest', 'infocus', 'split-lists', 'popular-sec', 'trending-sec'];
const tabs = document.querySelectorAll('.news-tab');

window.addEventListener('scroll', () => {
  let current = '';
  
  sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      const rect = el.getBoundingClientRect();
      if (rect.top <= 150) {
        current = id;
      }
    }
  });
  
  if (!current && sections.length > 0) {
    current = sections[0];
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
