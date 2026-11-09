@extends('layouts.app')

@section('title', 'Media Center — TEFC')

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
.media-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 5rem;
}

/* ── HERO BANNER ── */
.media-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4.5rem 0 3.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.media-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.media-hero-inner {
  position: relative;
  z-index: 2;
}
.media-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.media-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.media-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.media-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.media-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin-bottom: 2rem;
}

/* Quick Navigation Links */
.media-quick-links {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}
.media-quick-btn {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #fff;
  padding: 0.5rem 1.5rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}
.media-quick-btn:hover {
  background: var(--accent-gold);
  border-color: var(--accent-gold);
  color: var(--primary-burgundy-dark);
}

/* Stats Inline bar */
.media-stats-inline {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}
.media-stat-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85rem;
  opacity: 0.9;
}
.media-stat-item i {
  color: var(--accent-gold-light);
}

/* ── STICKY NAVIGATION TABS ── */
.media-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 0.8rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.media-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
  justify-content: center;
}
.media-tab {
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
.media-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.media-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
}

/* ── SECTION COMMON ── */
.media-section {
  padding: 4.5rem 0;
}
.media-section-alt {
  background: #fff;
  border-top: 1px solid rgba(179, 142, 93, 0.15);
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
}
.sec-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
  padding-bottom: 1.2rem;
}
.sec-head-left h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.3rem;
  display: flex;
  align-items: center;
  gap: 10px;
}
.sec-head-left h2 i {
  color: var(--accent-gold);
}
.sec-head-left p {
  font-size: 0.9rem;
  color: var(--text-muted);
  margin: 0;
}
.sec-head-right a {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--primary-burgundy);
  text-decoration: none;
  border-bottom: 2px solid var(--accent-gold);
  padding-bottom: 2px;
  transition: all 0.2s;
}
.sec-head-right a:hover {
  border-color: var(--primary-burgundy);
}

/* ── SUB FILTER TABS ── */
.sub-filter-strip {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2.2rem;
  flex-wrap: wrap;
}
.sub-filter-tab {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  color: var(--text-muted);
  padding: 0.35rem 1rem;
  border-radius: 20px;
  font-size: 0.76rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.sub-filter-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.sub-filter-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
}

/* ── FEATURED VIDEO GRID ── */
.featured-video-layout {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 3rem;
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.25);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 35px rgba(0,0,0,0.02);
  margin-bottom: 3.5rem;
  align-items: center;
}
.featured-video-player {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, var(--primary-burgundy) 100%);
  position: relative;
  aspect-ratio: 16/9;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
.featured-video-player::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.25);
  transition: background 0.3s;
}
.featured-video-player:hover::after {
  background: rgba(0,0,0,0.15);
}
.play-btn-circle {
  width: 76px;
  height: 76px;
  background: #fff;
  color: var(--primary-burgundy);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  z-index: 5;
  box-shadow: 0 6px 20px rgba(0,0,0,0.2);
  transition: transform 0.3s;
  padding-left: 5px; /* offset play icon */
}
.featured-video-player:hover .play-btn-circle {
  transform: scale(1.1);
}
.featured-badge {
  position: absolute;
  top: 1.2rem;
  left: 1.2rem;
  background: var(--accent-gold);
  color: var(--primary-burgundy-dark);
  font-size: 0.68rem;
  font-weight: 800;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  z-index: 5;
}
.duration-badge {
  position: absolute;
  bottom: 1.2rem;
  right: 1.2rem;
  background: rgba(0,0,0,0.7);
  color: #fff;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 0.2rem 0.6rem;
  border-radius: 4px;
  z-index: 5;
}
.featured-video-desc {
  padding: 3rem;
}
.featured-video-desc h4 {
  color: var(--primary-burgundy-dark);
  font-size: 1.4rem;
  font-weight: 800;
  margin-bottom: 1rem;
}
.featured-video-desc p {
  font-size: 0.92rem;
  color: var(--text-muted);
  line-height: 1.7;
  margin-bottom: 2rem;
}
.featured-video-meta {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin-bottom: 1.5rem;
  display: flex;
  gap: 1.5rem;
}
.featured-video-meta span {
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-featured-play {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.7rem 2.2rem;
  border-radius: 30px;
  font-size: 0.88rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn-featured-play:hover {
  background: var(--primary-burgundy-dark);
}

/* ── GRID OF 6 CARDS ── */
.media-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.8rem;
}
.media-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: all 0.3s;
}
.media-card:hover {
  transform: translateY(-4px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 8px 25px rgba(108, 21, 63, 0.04);
}
.media-card-thumbnail {
  background: linear-gradient(135deg, var(--primary-burgundy) 0%, var(--primary-burgundy-dark) 100%);
  position: relative;
  aspect-ratio: 16/9;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
.media-card-thumbnail::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.2);
  transition: background 0.3s;
}
.media-card:hover .media-card-thumbnail::after {
  background: rgba(0,0,0,0.1);
}
.card-play-btn {
  width: 52px;
  height: 52px;
  background: rgba(255,255,255,0.9);
  color: var(--primary-burgundy);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  z-index: 5;
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
  transition: all 0.3s;
  padding-left: 3px;
}
.media-card:hover .card-play-btn {
  transform: scale(1.1);
  background: #fff;
}
.card-category-badge {
  position: absolute;
  top: 0.8rem;
  left: 0.8rem;
  background: rgba(255, 255, 255, 0.9);
  color: var(--primary-burgundy-dark);
  font-size: 0.64rem;
  font-weight: 700;
  padding: 0.2rem 0.6rem;
  border-radius: 12px;
  z-index: 5;
  text-transform: uppercase;
  border: 1px solid rgba(179, 142, 93, 0.2);
}
.media-card-body {
  padding: 1.5rem;
}
.media-card-body h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  line-height: 1.5;
  margin-bottom: 0.8rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  height: 2.85rem;
}
.media-card-meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.74rem;
  color: var(--text-muted);
  border-top: 1px solid rgba(179, 142, 93, 0.1);
  padding-top: 0.8rem;
}

/* Centered Button Wrapper */
.load-more-wrapper {
  text-align: center;
  margin-top: 3rem;
}
.btn-media-outline {
  background: transparent;
  border: 1px solid var(--primary-burgundy);
  color: var(--primary-burgundy);
  padding: 0.6rem 2rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-media-outline:hover {
  background: var(--primary-burgundy);
  color: #fff;
}

/* ── SOLID STATS STRIP ── */
.media-stats-banner {
  background: var(--primary-burgundy);
  color: #fff;
  padding: 3rem 0;
  margin: 4.5rem 0;
  border-top: 2px solid var(--accent-gold);
  border-bottom: 2px solid var(--accent-gold);
}
.media-stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  text-align: center;
}
.media-stat-card h3 {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--accent-gold-light);
  margin-bottom: 0.4rem;
}
.media-stat-card p {
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  opacity: 0.85;
  margin: 0;
}

/* ── PHOTO GALLERY ── */
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.gallery-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.18);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: all 0.3s;
}
.gallery-card:hover {
  transform: translateY(-3px);
  border-color: var(--primary-burgundy);
}
.gallery-card-img {
  background: linear-gradient(135deg, var(--primary-burgundy-dark) 0%, #8a1e50 100%);
  aspect-ratio: 4/3;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255, 255, 255, 0.15);
  transition: all 0.3s;
}
.gallery-card:hover .gallery-card-img {
  color: var(--accent-gold-light);
}
.gallery-card-img i {
  font-size: 3rem;
  transition: transform 0.3s;
}
.gallery-card:hover .gallery-card-img i {
  transform: scale(1.15);
}
.gallery-card-body {
  padding: 1.2rem;
}
.gallery-card-body h4 {
  font-size: 0.82rem;
  font-weight: 800;
  color: var(--primary-burgundy-dark);
  margin-bottom: 0.4rem;
}
.gallery-card-body p {
  font-size: 0.7rem;
  color: var(--text-muted);
  margin: 0;
}

/* ── ASSISTANCE BANNER ── */
.media-assistance-banner {
  background: var(--primary-burgundy);
  color: #fff;
  text-align: center;
  padding: 4rem 1rem;
  margin-top: 4.5rem;
  border-radius: 24px;
}
.media-assistance-banner h2 {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  letter-spacing: -0.02em;
}
.btn-media-assistance {
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
.btn-media-assistance:hover {
  background: var(--accent-gold-light);
  color: var(--primary-burgundy);
}

/* ── CUSTOM VIDEO MODAL ── */
.custom-modal {
  display: none; 
  position: fixed; 
  z-index: 10000; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.85);
  backdrop-filter: blur(8px);
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}
.custom-modal.active {
  display: flex;
}
.custom-modal-content {
  background: var(--primary-burgundy-dark);
  border: 2px solid var(--accent-gold);
  border-radius: 16px;
  width: 90%;
  max-width: 800px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  overflow: hidden;
}
.custom-modal-header {
  padding: 1.2rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(179,142,93,0.25);
  color: #fff;
}
.custom-modal-title {
  font-size: 1.15rem;
  font-weight: 800;
  margin: 0;
  color: #fff;
}
.custom-modal-close {
  background: none;
  border: none;
  color: #fff;
  font-size: 1.8rem;
  cursor: pointer;
  opacity: 0.8;
  transition: opacity 0.2s;
  line-height: 1;
}
.custom-modal-close:hover {
  opacity: 1;
}
.custom-modal-body {
  padding: 1.5rem;
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .featured-video-layout { grid-template-columns: 1fr; }
  .media-grid { grid-template-columns: repeat(2, 1fr); }
  .gallery-grid { grid-template-columns: repeat(2, 1fr); }
  .media-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .media-quick-links { flex-direction: column; }
  .media-grid { grid-template-columns: 1fr; }
  .gallery-grid { grid-template-columns: 1fr; }
  .media-hero h1 { font-size: 2.2rem; }
  .media-hero p { font-size: 0.95rem; }
}
</style>
@endsection

@section('content')
<div class="media-page-container">

  <!-- Fetch sections from DB with robust fallbacks -->
  @php
    $heroSection = $page->sections->where('type', 'media_hero')->first();
    $heroTitle = $heroSection ? $heroSection->title : 'Media Center';
    $heroData = $heroSection ? $heroSection->content : [];

    $trainingSection = $page->sections->where('type', 'media_training')->first();
    $trainingTitle = $trainingSection ? $trainingSection->title : 'Training Videos';
    $trainingData = $trainingSection ? $trainingSection->content : [];

    $clusterSection = $page->sections->where('type', 'media_cluster')->first();
    $clusterTitle = $clusterSection ? $clusterSection->title : 'Cluster Videos';
    $clusterData = $clusterSection ? $clusterSection->content : [];

    $otherSection = $page->sections->where('type', 'media_other')->first();
    $otherTitle = $otherSection ? $otherSection->title : 'Other Videos';
    $otherData = $otherSection ? $otherSection->content : [];

    $statsSection = $page->sections->where('type', 'media_stats')->first(); // optional

    $photosSection = $page->sections->where('type', 'media_photos')->first();
    $photosTitle = $photosSection ? $photosSection->title : 'Photo Gallery';
    $photosData = $photosSection ? $photosSection->content : [];
  @endphp

  <!-- ── HERO SECTION ── -->
  <div class="media-hero">
    <div class="container media-hero-inner">
      <div class="media-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>Media Center</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroData['subtitle'] ?? 'Access comprehensive video tutorials, cluster documentaries, photos and news updates about the Indian textile export community.' }}</p>
      
      <div class="media-quick-links">
        <a href="javascript:void(0)" onclick="scrollToSec('training')" class="media-quick-btn">Training Videos</a>
        <a href="javascript:void(0)" onclick="scrollToSec('cluster')" class="media-quick-btn">Cluster Videos</a>
        <a href="javascript:void(0)" onclick="scrollToSec('photos')" class="media-quick-btn">Photo Gallery</a>
      </div>

      <div class="media-stats-inline">
        @php
          $defaultStats = [
            ['label' => 'Training Videos', 'value' => '12'],
            ['label' => 'Cluster Videos', 'value' => '15'],
            ['label' => 'Other Videos', 'value' => '8'],
            ['label' => 'Photo Gallery', 'value' => '24']
          ];
          $statsInline = $heroData['stats'] ?? $defaultStats;
        @endphp
        @foreach($statsInline as $stat)
          <div class="media-stat-item">
            <i class="fas fa-circle-play"></i>
            <strong>{{ $stat['label'] }}</strong>: {{ $stat['value'] }}
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- ── STICKY TAB STRIP ── -->
  <div class="media-filter-strip">
    <div class="container">
      <div class="media-tabs">
        <button class="media-tab active" onclick="scrollToSec('training', this)">Training Videos</button>
        <button class="media-tab" onclick="scrollToSec('cluster', this)">Cluster Videos</button>
        <button class="media-tab" onclick="scrollToSec('other', this)">Other Videos</button>
        <button class="media-tab" onclick="scrollToSec('photos', this)">Photo Gallery</button>
      </div>
    </div>
  </div>

  <!-- ── TRAINING VIDEOS SECTION ── -->
  <section class="media-section" id="training">
    <div class="container">
      <div class="sec-head">
        <div class="sec-head-left">
          <h2><i class="fas fa-graduation-cap"></i> {{ $trainingTitle }}</h2>
          <p>{{ $trainingData['subtitle'] ?? 'Educational guides, compliance courses and webinars for textile MSMEs.' }}</p>
        </div>
        <div class="sec-head-right">
          <a href="javascript:void(0)">View All Training Videos</a>
        </div>
      </div>

      <!-- Training Sub-Filter -->
      <div class="sub-filter-strip">
        <button class="sub-filter-tab active" onclick="filterGrid('training', 'all', this)">All</button>
        @php
          $trainingCategories = collect($trainingData['items'] ?? [])->pluck('category')->unique();
        @endphp
        @foreach($trainingCategories as $cat)
          <button class="sub-filter-tab" onclick="filterGrid('training', '{{ Str::slug($cat) }}', this)">{{ $cat }}</button>
        @endforeach
      </div>

      <!-- Featured Video -->
      @php
        $defaultFeaturedTraining = [
          'title' => 'Export Documentation Checklist - 2025',
          'description' => 'A comprehensive walk-through of the necessary steps and paperwork required for Indian textile exporters starting out in the EU and North American markets under new trade rules.',
          'duration' => '12:45 Mins',
          'published' => 'Jan 2025',
          'url' => '#'
        ];
        $featuredTraining = $trainingData['featured_video'] ?? $defaultFeaturedTraining;
      @endphp
      <div class="featured-video-layout">
        <div class="featured-video-player" onclick="playVideoModal('{{ addslashes($featuredTraining['title']) }}', '{{ $featuredTraining['url'] ?? '' }}')">
          <span class="featured-badge">Featured</span>
          <div class="play-btn-circle"><i class="fas fa-play"></i></div>
          <span class="duration-badge">{{ $featuredTraining['duration'] }}</span>
        </div>
        <div class="featured-video-desc">
          <h4>{{ $featuredTraining['title'] }}</h4>
          <p>{{ $featuredTraining['description'] }}</p>
          <div class="featured-video-meta">
            <span><i class="far fa-clock"></i> Duration: {{ $featuredTraining['duration'] }}</span>
            <span><i class="far fa-calendar-alt"></i> Published: {{ $featuredTraining['published'] }}</span>
          </div>
          <button class="btn-featured-play" onclick="playVideoModal('{{ addslashes($featuredTraining['title']) }}', '{{ $featuredTraining['url'] ?? '' }}')"><i class="fas fa-play"></i> Play Video</button>
        </div>
      </div>

      <!-- 6 Training Grid Cards -->
      <div class="media-grid" id="training-grid">
        @php
          $defaultTrainingItems = [
            ['title' => 'Introduction to FTA for Exporters', 'category' => 'Export Promotion', 'published' => 'Jan 2025', 'duration' => '15:20'],
            ['title' => 'Quality Standard Certifications for Global Markets', 'category' => 'Quality Standards', 'published' => 'Dec 2024', 'duration' => '18:30'],
            ['title' => 'Exploring PLI Scheme benefits', 'category' => 'Documentation', 'published' => 'Oct 2024', 'duration' => '10:15'],
            ['title' => 'Finance Essentials for Textile Exporters', 'category' => 'FTA Benefits', 'published' => 'Sep 2024', 'duration' => '22:40'],
            ['title' => 'Packaging and Labelling requirements', 'category' => 'MSME Capacity', 'published' => 'Aug 2024', 'duration' => '14:50'],
            ['title' => 'Digital Tools for MSME Exporters', 'category' => 'MSME Capacity', 'published' => 'Jul 2024', 'duration' => '11:30']
          ];
          $trainingItems = $trainingData['items'] ?? $defaultTrainingItems;
        @endphp
        @foreach($trainingItems as $item)
          <div class="media-card filter-item-training {{ Str::slug($item['category']) }}">
            <div class="media-card-thumbnail" onclick="playVideoModal('{{ addslashes($item['title']) }}', '{{ $item['url'] ?? '' }}')">
              <span class="card-category-badge">{{ $item['category'] }}</span>
              <div class="card-play-btn"><i class="fas fa-play"></i></div>
              <span class="duration-badge">{{ $item['duration'] }}</span>
            </div>
            <div class="media-card-body">
              <h4>{{ $item['title'] }}</h4>
              <div class="media-card-meta">
                <span><i class="far fa-calendar-alt"></i> {{ $item['published'] }}</span>
                <span><i class="far fa-clock"></i> {{ $item['duration'] }} Mins</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="load-more-wrapper">
        <button class="btn-media-outline">Load More Training Videos</button>
      </div>

    </div>
  </section>

  <!-- ── CLUSTER VIDEOS SECTION ── -->
  <section class="media-section media-section-alt" id="cluster">
    <div class="container">
      <div class="sec-head">
        <div class="sec-head-left">
          <h2><i class="fas fa-industry"></i> {{ $clusterTitle }}</h2>
          <p>{{ $clusterData['subtitle'] ?? 'Documentaries showcasing prominent textile cluster regions, craftsmanship and scale.' }}</p>
        </div>
        <div class="sec-head-right">
          <a href="javascript:void(0)">View All Cluster Videos</a>
        </div>
      </div>

      <!-- Cluster Sub-Filter -->
      <div class="sub-filter-strip">
        <button class="sub-filter-tab active" onclick="filterGrid('cluster', 'all', this)">All Clusters</button>
        @php
          $clusterCategories = collect($clusterData['items'] ?? [])->pluck('category')->unique();
        @endphp
        @foreach($clusterCategories as $cat)
          <button class="sub-filter-tab" onclick="filterGrid('cluster', '{{ Str::slug($cat) }}', this)">{{ $cat }}</button>
        @endforeach
      </div>

      <!-- Featured Video -->
      @php
        $defaultFeaturedCluster = [
          'title' => 'Karur Cluster - Home Textiles Global Giant',
          'description' => 'Go inside India\'s home textiles capital - Karur. See how handlooms and modern technology blend to produce export-grade linen, from raw yarn management to finished product.',
          'duration' => '18:15 Mins',
          'published' => 'Jan 2025',
          'url' => '#'
        ];
        $featuredCluster = $clusterData['featured_video'] ?? $defaultFeaturedCluster;
      @endphp
      <div class="featured-video-layout">
        <div class="featured-video-player" onclick="playVideoModal('{{ addslashes($featuredCluster['title']) }}', '{{ $featuredCluster['url'] ?? '' }}')">
          <span class="featured-badge">Featured</span>
          <div class="play-btn-circle"><i class="fas fa-play"></i></div>
          <span class="duration-badge">{{ $featuredCluster['duration'] }}</span>
        </div>
        <div class="featured-video-desc">
          <h4>{{ $featuredCluster['title'] }}</h4>
          <p>{{ $featuredCluster['description'] }}</p>
          <div class="featured-video-meta">
            <span><i class="far fa-clock"></i> Duration: {{ $featuredCluster['duration'] }}</span>
            <span><i class="far fa-calendar-alt"></i> Published: {{ $featuredCluster['published'] }}</span>
          </div>
          <button class="btn-featured-play" onclick="playVideoModal('{{ addslashes($featuredCluster['title']) }}', '{{ $featuredCluster['url'] ?? '' }}')"><i class="fas fa-play"></i> Play Video</button>
        </div>
      </div>

      <!-- 6 Cluster Grid Cards -->
      <div class="media-grid" id="cluster-grid">
        @php
          $defaultClusterItems = [
            ['title' => 'Surat - The Synthetic Capital', 'category' => 'Surat', 'published' => 'Dec 2024', 'duration' => '15:40'],
            ['title' => 'Ichalkaranji Weaving Sector', 'category' => 'Ichalkaranji', 'published' => 'Nov 2024', 'duration' => '12:50'],
            ['title' => 'Jaipur Traditional Block Printing', 'category' => 'Jaipur', 'published' => 'Oct 2024', 'duration' => '14:15'],
            ['title' => 'Ludhiana Knitwear Powerhouse', 'category' => 'Ludhiana', 'published' => 'Sep 2024', 'duration' => '17:35'],
            ['title' => 'Varanasi - Legacy of Handloom Silk', 'category' => 'Varanasi', 'published' => 'Aug 2024', 'duration' => '19:20'],
            ['title' => 'Bhagalpur - Tussar Silk weavers', 'category' => 'Other', 'published' => 'Jul 2024', 'duration' => '11:45']
          ];
          $clusterItems = $clusterData['items'] ?? $defaultClusterItems;
        @endphp
        @foreach($clusterItems as $item)
          <div class="media-card filter-item-cluster {{ Str::slug($item['category']) }}">
            <div class="media-card-thumbnail" onclick="playVideoModal('{{ addslashes($item['title']) }}', '{{ $item['url'] ?? '' }}')">
              <span class="card-category-badge">{{ $item['category'] }}</span>
              <div class="card-play-btn"><i class="fas fa-play"></i></div>
              <span class="duration-badge">{{ $item['duration'] }}</span>
            </div>
            <div class="media-card-body">
              <h4>{{ $item['title'] }}</h4>
              <div class="media-card-meta">
                <span><i class="far fa-calendar-alt"></i> {{ $item['published'] }}</span>
                <span><i class="far fa-clock"></i> {{ $item['duration'] }} Mins</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="load-more-wrapper">
        <button class="btn-media-outline">Load More Cluster Videos</button>
      </div>

    </div>
  </section>

  <!-- ── OTHER VIDEOS SECTION ── -->
  <section class="media-section" id="other">
    <div class="container">
      <div class="sec-head">
        <div class="sec-head-left">
          <h2><i class="fas fa-photo-film"></i> {{ $otherTitle }}</h2>
          <p>{{ $otherData['subtitle'] ?? 'Recent events, expert talks, trade shows and media coverage.' }}</p>
        </div>
        <div class="sec-head-right">
          <a href="javascript:void(0)">View All Videos</a>
        </div>
      </div>

      <!-- 6 Other Grid Cards -->
      <div class="media-grid">
        @php
          $defaultOtherItems = [
            ['title' => 'IITF 2024 - Textile Pavilion Highlights', 'category' => 'Event', 'published' => 'Nov 2024', 'duration' => '05:25'],
            ['title' => 'Budget 2025 - Impact on Textile Sector', 'category' => 'Expert', 'published' => 'Feb 2025', 'duration' => '11:50'],
            ['title' => 'Ministry Address at TEFC Launch Ceremony', 'category' => 'Event', 'published' => 'Aug 2024', 'duration' => '08:35'],
            ['title' => 'Interviews: Exporters on New Free Trade Agreements', 'category' => 'Expert', 'published' => 'Jan 2025', 'duration' => '14:10'],
            ['title' => 'Sustainable Textiles: Future Trend Seminar', 'category' => 'Seminar', 'published' => 'Oct 2024', 'duration' => '21:15'],
            ['title' => 'Weaver Handloom Workshop - Varanasi', 'category' => 'Workshop', 'published' => 'Sep 2024', 'duration' => '10:45']
          ];
          $otherItems = $otherData['items'] ?? $defaultOtherItems;
        @endphp
        @foreach($otherItems as $item)
          <div class="media-card">
            <div class="media-card-thumbnail" onclick="playVideoModal('{{ addslashes($item['title']) }}', '{{ $item['url'] ?? '' }}')">
              <span class="card-category-badge">{{ $item['category'] }}</span>
              <div class="card-play-btn"><i class="fas fa-play"></i></div>
              <span class="duration-badge">{{ $item['duration'] }}</span>
            </div>
            <div class="media-card-body">
              <h4>{{ $item['title'] }}</h4>
              <div class="media-card-meta">
                <span><i class="far fa-calendar-alt"></i> {{ $item['published'] }}</span>
                <span><i class="far fa-clock"></i> {{ $item['duration'] }} Mins</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="load-more-wrapper">
        <button class="btn-media-outline">Load More Videos</button>
      </div>

    </div>
  </section>

  <!-- ── SOLID STATS BANNER ── -->
  <div class="media-stats-banner">
    <div class="container">
      <div class="media-stats-grid">
        <div class="media-stat-card">
          <h3>36</h3>
          <p>Videos in Library</p>
        </div>
        <div class="media-stat-card">
          <h3>1.2 Lakh+</h3>
          <p>Video Views</p>
        </div>
        <div class="media-stat-card">
          <h3>48 Hrs+</h3>
          <p>Total Content</p>
        </div>
        <div class="media-stat-card">
          <h3>6</h3>
          <p>Featured Playlists</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ── PHOTO GALLERY SECTION ── -->
  <section class="media-section media-section-alt" id="photos">
    <div class="container">
      <div class="sec-head">
        <div class="sec-head-left">
          <h2><i class="fas fa-images"></i> {{ $photosTitle }}</h2>
          <p>{{ $photosData['subtitle'] ?? 'Images from standard quality workshops, trade delegations and exhibitions.' }}</p>
        </div>
        <div class="sec-head-right">
          <a href="javascript:void(0)">View All Photos</a>
        </div>
      </div>

      <!-- Photos Sub-Filter -->
      <div class="sub-filter-strip">
        <button class="sub-filter-tab active" onclick="filterGrid('photos', 'all', this)">All Photos</button>
        @php
          $photoCategories = collect($photosData['items'] ?? [])->pluck('category')->unique();
        @endphp
        @foreach($photoCategories as $cat)
          <button class="sub-filter-tab" onclick="filterGrid('photos', '{{ Str::slug($cat) }}', this)">{{ $cat }}</button>
        @endforeach
      </div>

      <!-- 8 Photo Cards -->
      <div class="gallery-grid" id="photos-grid">
        @php
          $defaultPhotoItems = [
            ['title' => 'Varanasi Skill Training Workshop', 'category' => 'Capacity Building', 'published' => 'Feb 2025'],
            ['title' => 'Karur Buyer Seller Meet 2024', 'category' => 'Buyer-Seller Meets', 'published' => 'Jan 2025'],
            ['title' => 'Surat Fabric Quality Forum', 'category' => 'Quality Forums', 'published' => 'Dec 2024'],
            ['title' => 'Ludhiana Knitwear Expo', 'category' => 'Exhibitions', 'published' => 'Nov 2024'],
            ['title' => 'Weavers Meet - Bhagalpur', 'category' => 'Capacity Building', 'published' => 'Oct 2024'],
            ['title' => 'Jaipur Block Print Demonstration', 'category' => 'Training Programmes', 'published' => 'Sep 2024'],
            ['title' => 'TEFC Delhi Launch Ceremony', 'category' => 'Exhibitions', 'published' => 'Aug 2024'],
            ['title' => 'Mumbai Apparel Fashion Show', 'category' => 'Buyer-Seller Meets', 'published' => 'Jul 2024']
          ];
          $photoItems = $photosData['items'] ?? $defaultPhotoItems;
        @endphp
        @foreach($photoItems as $item)
          <div class="gallery-card filter-item-photos {{ Str::slug($item['category']) }}">
            <div class="gallery-card-img">
              @if(!empty($item['image']))
                <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
              @else
                <i class="far fa-image"></i>
              @endif
            </div>
            <div class="gallery-card-body">
              <h4>{{ $item['title'] }}</h4>
              <p><span class="badge bg-light text-muted border text-uppercase" style="font-size:0.6rem; font-weight:700;">{{ $item['category'] }}</span></p>
            </div>
          </div>
        @endforeach
      </div>

      <div class="load-more-wrapper">
        <button class="btn-media-outline">Load More Photos</button>
      </div>

      <!-- Support Callout Banner -->
      <div class="media-assistance-banner">
        <h2>Need Assistance? We're here to help.</h2>
        <a href="{{ url('/p/help-support') }}" class="btn-media-assistance">Contact Support</a>
      </div>

    </div>
  </section>

</div>

<!-- ── DYNAMIC POPUP MODAL FOR VIDEOS ── -->
<div class="custom-modal" id="videoModal">
  <div class="custom-modal-content">
    <div class="custom-modal-header">
      <h5 class="custom-modal-title" id="videoModalLabel">Video Title</h5>
      <button class="custom-modal-close" onclick="closeVideoModal()">&times;</button>
    </div>
    <div class="custom-modal-body">
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; border: 1px solid rgba(179,142,93,0.3);">
        <iframe id="videoIframe" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; display: none;" allowfullscreen></iframe>
        <video id="videoPlayer" controls autoplay style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; background: #000; display: none;"></video>
      </div>
      <div style="margin-top: 1rem; color: #fff; font-size: 0.76rem; opacity: 0.8; display: flex; align-items: center; gap: 6px;">
        <i class="fas fa-info-circle" style="color: var(--accent-gold-light);"></i> Playing interactive documentary training stream powered by TEFC Facilitation Channel.
      </div>
    </div>
  </div>
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

// ── PLAY VIDEO MODAL TRIGGER ──
function playVideoModal(title, url) {
  document.getElementById('videoModalLabel').innerText = title;
  const iframe = document.getElementById('videoIframe');
  const videoPlayer = document.getElementById('videoPlayer');
  
  if (!url) {
    url = 'https://www.youtube.com/embed/dQw4w9WgXcQ';
  }

  let isYouTube = false;
  let embedUrl = '';
  
  if (url.includes('youtube.com') || url.includes('youtu.be')) {
    isYouTube = true;
    let videoId = '';
    
    if (url.includes('youtube.com/watch')) {
      const urlParams = new URLSearchParams(new URL(url).search);
      videoId = urlParams.get('v');
    } else if (url.includes('youtu.be/')) {
      videoId = url.split('youtu.be/')[1]?.split('?')[0];
    } else if (url.includes('youtube.com/embed/')) {
      videoId = url.split('youtube.com/embed/')[1]?.split('?')[0];
    }
    
    if (videoId) {
      embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    } else {
      embedUrl = url;
    }
  }

  if (isYouTube) {
    videoPlayer.style.display = 'none';
    videoPlayer.src = '';
    
    iframe.src = embedUrl;
    iframe.style.display = 'block';
  } else {
    iframe.style.display = 'none';
    iframe.src = '';
    
    let fullUrl = url;
    if (!url.startsWith('http://') && !url.startsWith('https://')) {
      if (!url.startsWith('/')) {
        fullUrl = '{{ asset("") }}' + url;
      } else {
        fullUrl = '{{ asset("") }}' + url.substring(1);
      }
    }
    videoPlayer.src = fullUrl;
    videoPlayer.style.display = 'block';
    videoPlayer.load();
  }

  document.getElementById('videoModal').classList.add('active');
}

function closeVideoModal() {
  document.getElementById('videoModal').classList.remove('active');
  document.getElementById('videoIframe').src = '';
  const videoPlayer = document.getElementById('videoPlayer');
  videoPlayer.src = '';
  videoPlayer.style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
  const modal = document.getElementById('videoModal');
  if (event.target === modal) {
    closeVideoModal();
  }
});

// ── INTERACTIVE LIVE FILTER FOR GRIDS ──
function filterGrid(sectionType, categorySlug, btnElement) {
  // Update sub-filter active class
  const parentContainer = btnElement.parentElement;
  parentContainer.querySelectorAll('.sub-filter-tab').forEach(t => t.classList.remove('active'));
  btnElement.classList.add('active');

  // Filter items
  const items = document.querySelectorAll('.filter-item-' + sectionType);
  items.forEach(item => {
    if (categorySlug === 'all' || item.classList.contains(categorySlug)) {
      item.style.display = 'block';
      // Fade in effect
      setTimeout(() => {
        item.style.opacity = '1';
        item.style.transform = 'scale(1)';
      }, 50);
    } else {
      item.style.opacity = '0';
      item.style.transform = 'scale(0.95)';
      setTimeout(() => {
        item.style.display = 'none';
      }, 200);
    }
  });
}

// ── SCROLL DETECT ACTIVE TABS ──
const sections = ['training', 'cluster', 'other', 'photos'];
const tabs = document.querySelectorAll('.media-tab');

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
