@extends('layouts.app')

@section('title', 'Major Textile Clusters — TEFC')

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
.clusters-page-container {
  background: var(--bg-light);
  color: var(--text-dark);
  font-family: 'Outfit', 'Inter', 'Segoe UI', sans-serif;
  padding-bottom: 5rem;
}

/* ── HERO BANNER ── */
.cluster-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 4.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.cluster-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(179, 142, 93, 0.15) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.cluster-hero-inner {
  position: relative;
  z-index: 2;
}
.cluster-breadcrumbs {
  font-size: 0.8rem;
  margin-bottom: 1.2rem;
  opacity: 0.8;
  display: flex;
  gap: 0.4rem;
  align-items: center;
}
.cluster-breadcrumbs a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.cluster-breadcrumbs a:hover {
  color: var(--accent-gold);
}
.cluster-hero h1 {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.02em;
}
.cluster-hero p {
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 750px;
  line-height: 1.6;
  margin-bottom: 2.5rem;
}

/* ── HERO STATS STRIP ── */
.hero-stats-strip {
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

/* ── SEARCH & FILTER STRIP ── */
.cluster-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  padding: 1.2rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.filter-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1.5rem;
  flex-wrap: wrap;
}
.filter-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
}
.filter-tab {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.3);
  color: var(--text-dark);
  padding: 0.4rem 1.1rem;
  border-radius: 30px;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}
.filter-tab:hover {
  border-color: var(--primary-burgundy);
  color: var(--primary-burgundy);
}
.filter-tab.active {
  background: var(--primary-burgundy);
  border-color: var(--primary-burgundy);
  color: #fff;
}
.search-box-wrapper {
  position: relative;
  min-width: 280px;
}
.search-box-wrapper input {
  width: 100%;
  padding: 0.45rem 1rem 0.45rem 2.2rem;
  border: 1px solid rgba(179, 142, 93, 0.3);
  border-radius: 30px;
  font-size: 0.85rem;
  color: var(--text-dark);
  outline: none;
  transition: all 0.2s;
}
.search-box-wrapper input:focus {
  border-color: var(--primary-burgundy);
  box-shadow: 0 0 0 3px rgba(108, 21, 63, 0.1);
}
.search-box-wrapper i {
  position: absolute;
  left: 0.9rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-muted);
  font-size: 0.85rem;
}

/* ── GRID SECTION ── */
.cluster-grid-section {
  padding: 3rem 0;
}
.grid-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
.grid-header h2 {
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--primary-burgundy);
}
.grid-header select {
  padding: 0.4rem 1.5rem 0.4rem 1rem;
  border: 1px solid rgba(179, 142, 93, 0.3);
  border-radius: 8px;
  font-size: 0.82rem;
  color: var(--text-dark);
  outline: none;
  background-color: #fff;
  cursor: pointer;
}

/* ── CLUSTER CARDS ── */
.cluster-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 26px;
}

/* ── DETAIL LAYOUT (Initially hidden) ── */
.cluster-detail-container {
  display: none;
}
.detail-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.45) 0%, rgba(108, 19, 64, 0.35) 100%),
              url('{{ asset('images/page-hero-bg.jpg') }}') center center / cover no-repeat;
  padding: 3.5rem 0;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.detail-meta-badges {
  display: flex;
  gap: 0.8rem;
  margin-top: 1.2rem;
  flex-wrap: wrap;
}
.detail-badge {
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 0.35rem 0.8rem;
  border-radius: 6px;
  font-size: 0.76rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}
.detail-badge i {
  color: var(--accent-gold);
}

.stats-strip-container {
  background: #fff;
  border-bottom: 1px solid rgba(179, 142, 93, 0.2);
  box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.detail-stats-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
}
.detail-stat-cell {
  padding: 2rem 1.5rem;
  text-align: center;
  border-right: 1px solid rgba(179, 142, 93, 0.15);
}
.detail-stat-cell:last-child {
  border-right: none;
}
.detail-stat-num {
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  line-height: 1.1;
}
.detail-stat-lbl {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-muted);
  margin-top: 0.5rem;
  font-weight: 700;
}

/* ── OVERVIEW SPLIT ── */
.overview-section {
  padding: 4.5rem 0;
}
.overview-grid {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 3.5rem;
  align-items: start;
}
.overview-text h3 {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1.5rem;
}
.overview-text p {
  font-size: 1.02rem;
  line-height: 1.85;
  color: var(--text-muted);
  margin-bottom: 2rem;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.facts-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(108, 21, 63, 0.04);
  align-self: start;
  height: fit-content;
  position: sticky;
  top: 85px;
}
.facts-card h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1.5rem;
  border-bottom: 2px solid var(--border-color);
  padding-bottom: 0.6rem;
}
.fact-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.2rem;
}
.fact-row:last-child {
  margin-bottom: 0;
}
.fact-icon {
  width: 38px;
  height: 38px;
  background: var(--bg-light);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-burgundy);
  font-size: 0.95rem;
  flex-shrink: 0;
}
.fact-details {
  display: flex;
  flex-direction: column;
}
.fact-lbl {
  font-size: 0.72rem;
  color: var(--text-muted);
}
.fact-val {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text-dark);
}

/* ── KEY HIGHLIGHTS ── */
.highlights-section {
  padding: 4rem 0;
  background: var(--bg-light);
  scroll-margin-top: 80px;
}
.highlights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 1.5rem;
  justify-content: center;
  align-items: stretch;
}
@media (min-width: 1200px) {
  .highlights-grid.highlights-5 {
    grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
  }
  .highlights-grid.highlights-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
  }
  .highlights-grid.highlights-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
  }
}
.highlight-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.18);
  border-radius: 14px;
  padding: 1.5rem 1.25rem;
  text-align: center;
  transition: all 0.25s ease;
  box-shadow: 0 4px 14px rgba(108, 21, 63, 0.03);
  display: flex;
  flex-direction: column;
}
.highlight-card:hover {
  transform: translateY(-4px);
  border-color: var(--primary-burgundy);
  box-shadow: 0 10px 24px rgba(108, 21, 63, 0.08);
}
.highlight-pattern {
  height: 140px;
  background: linear-gradient(135deg, rgba(108, 21, 63, 0.05) 0%, rgba(108, 21, 63, 0.15) 100%);
  border-radius: 10px;
  margin-bottom: 1.2rem;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}
.highlight-pattern img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  transition: transform 0.3s ease;
}
.highlight-card:hover .highlight-pattern img {
  transform: scale(1.05);
}
.highlight-pattern::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(108, 21, 63, 0.05) 10px, rgba(108, 21, 63, 0.05) 11px);
}
.highlight-pattern i {
  font-size: 2.2rem;
  color: var(--primary-burgundy);
  opacity: 0.4;
  z-index: 2;
}
.highlight-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.4rem;
}

/* ── CHART CONTAINER ── */
.chart-section {
  padding: 4.5rem 0;
  background: #fff;
  border-top: 1px solid rgba(179, 142, 93, 0.15);
  border-bottom: 1px solid rgba(179, 142, 93, 0.15);
}
.chart-container-card {
  background: var(--bg-light);
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 2.5rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.01);
}
.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
.chart-title h3 {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0;
}
.chart-title p {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin: 0.2rem 0 0;
}
.chart-legend {
  display: flex;
  gap: 1.5rem;
}
.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  font-weight: 600;
}
.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}
.legend-curr { background: var(--primary-burgundy); }
.legend-prev { background: var(--accent-gold); }

/* ── EXPORTERS SECTION ── */
.exporters-section {
  padding: 4.5rem 0;
  background: #fff;
}
.exporter-filter-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1.5rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}
.exporter-card {
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  background: #fff;
  padding: 1.5rem;
  margin-bottom: 1.2rem;
  transition: all 0.2s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
}
.exporter-card:hover {
  border-color: var(--primary-burgundy);
  box-shadow: 0 8px 25px rgba(108, 21, 63, 0.05);
}
.exporter-header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1.5rem;
}
.exporter-identity h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin: 0 0 0.3rem 0;
}
.exporter-identity p {
  font-size: 0.76rem;
  color: var(--text-muted);
  margin: 0;
}
.btn-exporter-details {
  background: var(--primary-burgundy);
  color: #fff;
  border: none;
  padding: 0.45rem 1.2rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-exporter-details:hover {
  background: var(--primary-burgundy-dark);
}
.exporter-details-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1.5fr;
  gap: 2rem;
  border-top: 1px solid rgba(179, 142, 93, 0.12);
  margin-top: 1rem;
  padding-top: 1rem;
}
.exporter-meta-block {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
.exporter-meta-lbl {
  font-size: 0.68rem;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  color: var(--text-muted);
  font-weight: 600;
}
.exporter-meta-val {
  font-size: 0.8rem;
  color: var(--text-dark);
  font-weight: 700;
}
.exporter-meta-tags {
  display: flex;
  gap: 0.3rem;
  flex-wrap: wrap;
  margin-top: 0.2rem;
}

/* ── FACILITIES SECTION ── */
.facilities-section {
  padding: 4.5rem 0;
  background: var(--bg-light);
}
.facilities-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.facility-card {
  background: #fff;
  border: 1px solid rgba(179, 142, 93, 0.15);
  border-radius: 12px;
  padding: 1.8rem 1.5rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.01);
  transition: transform 0.2s;
}
.facility-card:hover {
  transform: translateY(-3px);
  border-color: var(--primary-burgundy);
}
.facility-icon-circle {
  width: 50px;
  height: 50px;
  background: rgba(108, 21, 63, 0.06);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.2rem;
  color: var(--primary-burgundy);
  font-size: 1.25rem;
}
.facility-card h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.6rem;
}
.facility-card p {
  font-size: 0.78rem;
  color: var(--text-muted);
  line-height: 1.65;
  margin: 0;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}

/* ── EVENTS SECTION (NEW) ── */
.events-section {
  padding: 4.5rem 0;
  background: #ffffff;
  border-top: 1px solid rgba(179, 142, 93, 0.12);
  border-bottom: 1px solid rgba(179, 142, 93, 0.12);
}
.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
  gap: 1.8rem;
}
.event-card {
  background: var(--bg-light, #fbf9f6);
  border: 1px solid rgba(179, 142, 93, 0.22);
  border-radius: 16px;
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  box-shadow: 0 4px 18px rgba(108, 19, 64, 0.03);
  position: relative;
  overflow: hidden;
}
.event-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-burgundy) 0%, var(--accent-gold) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}
.event-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 14px 32px rgba(108, 19, 64, 0.1);
  border-color: var(--primary-burgundy);
  background: #fff;
}
.event-card:hover::before {
  opacity: 1;
}
.event-header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.2rem;
  gap: 1rem;
}
.event-date-box {
  background: #fff;
  border: 1px solid rgba(108, 19, 64, 0.18);
  border-radius: 10px;
  padding: 0.45rem 0.85rem;
  text-align: center;
  min-width: 65px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.event-date-month {
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  color: var(--primary-burgundy);
  letter-spacing: 0.05em;
  display: block;
}
.event-date-day {
  font-size: 1.25rem;
  font-weight: 900;
  color: #1a1a2e;
  line-height: 1.1;
}
.event-badges-wrap {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.35rem;
}
.event-type-pill {
  font-size: 0.72rem;
  font-weight: 700;
  padding: 0.25rem 0.65rem;
  border-radius: 20px;
  background: rgba(179, 142, 93, 0.15);
  color: #7d5926;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}
.event-status-badge {
  font-size: 0.7rem;
  font-weight: 700;
  padding: 0.2rem 0.55rem;
  border-radius: 12px;
  background: #e6f7ec;
  color: #1b7a43;
}
.event-title {
  font-size: 1.12rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.65rem;
  line-height: 1.35;
}
.event-meta-info {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  margin-bottom: 0.9rem;
  font-size: 0.82rem;
  color: var(--text-muted);
}
.event-meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.event-meta-item i {
  color: var(--accent-gold);
  width: 14px;
}
.event-desc {
  font-size: 0.84rem;
  color: #4a4a5a;
  line-height: 1.65;
  margin-bottom: 1.4rem;
  flex-grow: 1;
  text-align: justify !important;
  text-justify: inter-word !important;
  hyphens: auto;
}
.event-footer-action {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid rgba(179, 142, 93, 0.15);
  padding-top: 1rem;
}
.btn-event-reg {
  background: var(--primary-burgundy);
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.82rem;
  padding: 0.5rem 1.1rem;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  transition: all 0.2s ease;
}
.btn-event-reg:hover {
  background: var(--primary-burgundy-dark);
  color: #fff;
  transform: translateY(-2px);
}

/* ── LOCATION & CONTACT ── */
.location-section {
  padding: 4.5rem 0;
  background: #fff;
}
.location-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: center;
}
.contact-info-card {
  background: var(--bg-light);
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 2.5rem;
}
.contact-info-card h4 {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 1.8rem;
}
.contact-row {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
.contact-icon {
  color: var(--primary-burgundy);
  font-size: 1rem;
  margin-top: 0.15rem;
}
.contact-details h5 {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin: 0 0 0.25rem 0;
  text-transform: uppercase;
}
.contact-details p {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}
.map-mockup-card {
  background: var(--bg-light);
  border: 1px solid rgba(179, 142, 93, 0.2);
  border-radius: 16px;
  padding: 2rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.01);
}
.map-mockup-icon {
  font-size: 2.5rem;
  color: var(--primary-burgundy);
  margin-bottom: 1rem;
}
.map-mockup-card h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary-burgundy);
  margin-bottom: 0.4rem;
}
.map-mockup-card p {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin-bottom: 1.5rem;
}

/* ── ASSISTANCE BANNER ── */
.assistance-banner {
  background: var(--primary-burgundy);
  color: #fff;
  text-align: center;
  padding: 4rem 1rem;
  margin-top: 2rem;
  border-radius: 24px;
}
.assistance-banner h2 {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  letter-spacing: -0.02em;
}
.btn-assistance {
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
.btn-assistance:hover {
  background: var(--accent-gold-light);
  color: var(--primary-burgundy);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .cluster-grid { grid-template-columns: repeat(2, 1fr); }
  .hero-stats-strip { grid-template-columns: repeat(2, 1fr); }
  .detail-stats-strip { grid-template-columns: repeat(2, 1fr); }
  .overview-grid { grid-template-columns: 1fr; }
  .highlights-grid { grid-template-columns: repeat(2, 1fr); }
  .facilities-grid { grid-template-columns: repeat(2, 1fr); }
  .location-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .cluster-grid { grid-template-columns: 1fr; }
  .highlights-grid { grid-template-columns: 1fr; }
  .facilities-grid { grid-template-columns: 1fr; }
  .detail-stats-strip { grid-template-columns: 1fr; }
  .detail-stat-cell { border-right: none; border-bottom: 1px solid rgba(179, 142, 93, 0.15); }
  .detail-stat-cell:last-child { border-bottom: none; }
  .cluster-hero h1 { font-size: 2.2rem; }
  .cluster-hero p { font-size: 0.95rem; }
}
</style>
@endsection

@section('content')
@php
  $clustersSection = $page->sections->where('type', 'clusters_data')->first();
  $clustersSubtitle = $clustersSection ? ($clustersSection->content['subtitle'] ?? 'Explore India\'s 6 major textile export cluster regions — products, exporters, facilities and more.') : 'Explore India\'s 6 major textile export cluster regions — products, exporters, facilities and more.';
  $clustersItems = $clustersSection ? ($clustersSection->content['items'] ?? []) : [];
  
  // Count stats dynamically based on database items!
  $totalClusters = count($clustersItems);
  $totalExporters = 0;
  $totalWorkforceNum = 0;
  $totalExportValueNum = 0;
  
  foreach($clustersItems as $c) {
    if (isset($c['exporters'])) {
      $totalExporters += count($c['exporters']);
    }
    
    // Extract workforce numbers
    if (isset($c['workforce'])) {
      $wf = floatval(str_replace(['Lakh+', ' ', ','], '', $c['workforce']));
      $totalWorkforceNum += $wf;
    }
    
    // Extract export value
    if (isset($c['exportNum'])) {
      $totalExportValueNum += intval($c['exportNum']);
    }
  }
  
  $totalWorkforceText = $totalWorkforceNum > 0 ? ($totalWorkforceNum . ' Lakh+') : '17 Lakh+';
  $totalExportValueText = $totalExportValueNum > 0 ? ('Rs.' . number_format($totalExportValueNum) . ' Cr') : 'Rs.18,800 Cr';
@endphp

<div class="clusters-page-container">
  
  <!-- ── LISTING VIEW ── -->
  <div id="listing-view">
    <div class="cluster-hero">
      <div class="container cluster-hero-inner">
        <div class="cluster-breadcrumbs">
          <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span>Clusters</span>
        </div>
        <h1>Key Textile Clusters</h1>
        <p>{{ $clustersSubtitle }}</p>
        
        <div class="hero-stats-strip">
          <div class="hero-stat-card">
            <div class="hero-stat-val">{{ $totalClusters }}</div>
            <div class="hero-stat-lbl">Total Clusters</div>
          </div>
          <div class="hero-stat-card">
            <div class="hero-stat-val">{{ $totalExporters > 0 ? ($totalExporters . '+') : '1000+' }}</div>
            <div class="hero-stat-lbl">Exporters Listed</div>
          </div>
          <div class="hero-stat-card">
            <div class="hero-stat-val">{{ $totalExportValueText }}</div>
            <div class="hero-stat-lbl">Combined Export Value</div>
          </div>
          <div class="hero-stat-card">
            <div class="hero-stat-val">{{ $totalWorkforceText }}</div>
            <div class="hero-stat-lbl">Total Workforce</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search & Filter Strip -->
    <div class="cluster-filter-strip">
      <div class="container filter-container">
        <div class="filter-tabs">
          <button class="filter-tab active" onclick="filterByState('All')">All States</button>
          <button class="filter-tab" onclick="filterByState('Tamil Nadu')">Tamil Nadu</button>
          <button class="filter-tab" onclick="filterByState('Gujarat')">Gujarat</button>
          <button class="filter-tab" onclick="filterByState('Maharashtra')">Maharashtra</button>
          <button class="filter-tab" onclick="filterByState('Rajasthan')">Rajasthan</button>
          <button class="filter-tab" onclick="filterByState('Uttar Pradesh')">Uttar Pradesh</button>
          <button class="filter-tab" onclick="filterByState('Punjab')">Punjab</button>
        </div>
        <div class="search-box-wrapper">
          <i class="fas fa-search"></i>
          <input type="text" id="cluster-search" placeholder="Search clusters..." onkeyup="searchClusters()">
        </div>
      </div>
    </div>

    <!-- Grid Section -->
    <div class="cluster-grid-section">
      <div class="container">
        <div class="grid-header">
          <h2>All Textile Clusters <span style="font-size: 0.95rem; font-weight:600; color:var(--text-muted);">(6 Clusters)</span></h2>
          <select id="sort-select" onchange="sortClusters()">
            <option value="name">Sort by: Name</option>
            <option value="export">Sort by: Export Value</option>
          </select>
        </div>

        <div class="cluster-grid" id="cluster-cards-container">
          <!-- Dynamically populated or statically rendered for SEO -->
        </div>
      </div>
    </div>
  </div>

  <!-- ── DETAIL VIEW ── -->
  <div id="detail-view" class="cluster-detail-container">
    <div class="detail-hero">
      <div class="container">
        <div class="cluster-breadcrumbs">
          <a href="javascript:void(0)" onclick="goBackToListing()">Clusters</a> <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> <span id="det-bc-name">Karur</span>
        </div>
        <h1 id="det-hero-title">Karur Textile Cluster</h1>
        <p id="det-hero-subtitle" style="opacity: 0.9; font-size:1.05rem;">Hub for home textiles and export-quality products</p>
        
        <div class="detail-meta-badges">
          <span class="detail-badge"><i class="fas fa-map-marker-alt"></i> <span id="det-badge-location">Tamil Nadu</span></span>
          <span class="detail-badge"><i class="fas fa-tags"></i> <span id="det-badge-spec">Home Textiles</span></span>
          <span class="detail-badge"><i class="fas fa-history"></i> <span id="det-badge-era">Late 19th Century</span></span>
        </div>
      </div>
    </div>

    <!-- Stats Strip -->
    <div class="stats-strip-container" id="sec-cluster-stats">
      <div class="container detail-stats-strip">
        <div class="detail-stat-cell">
          <div class="detail-stat-num" id="det-stat-units">1,500+</div>
          <div class="detail-stat-lbl">Manufacturing Units</div>
        </div>
        <div class="detail-stat-cell">
          <div class="detail-stat-num" id="det-stat-value">Rs.4,000 Cr+</div>
          <div class="detail-stat-lbl">Annual Export Value</div>
        </div>
        <div class="detail-stat-cell">
          <div class="detail-stat-num" id="det-stat-workforce">1 Lakh+</div>
          <div class="detail-stat-lbl">Workforce</div>
        </div>
        <div class="detail-stat-cell">
          <div class="detail-stat-num" id="det-stat-dests">60+</div>
          <div class="detail-stat-lbl">Export Destinations</div>
        </div>
      </div>
    </div>

    <!-- Overview Section -->
    <section class="overview-section" id="sec-cluster-overview">
      <div class="container overview-grid">
        <div class="overview-text">
          <h3>Cluster Overview</h3>
          <p id="det-overview-p" class="portal-content-justified" style="text-align: justify; text-justify: inter-word; hyphens: auto; line-height: 1.85;">Karur is known as the "Textile Capital of Tamil Nadu", renowned for its production of high-quality home textiles catering to domestic and international markets...</p>
        </div>
        <div class="facts-card">
          <h4>Key Facts</h4>
          <div class="fact-row">
            <div class="fact-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="fact-details">
              <span class="fact-lbl">Established Year</span>
              <span class="fact-val" id="det-fact-est">Late 19th C</span>
            </div>
          </div>
          <div class="fact-row">
            <div class="fact-icon"><i class="fas fa-industry"></i></div>
            <div class="fact-details">
              <span class="fact-lbl">Number of Units</span>
              <span class="fact-val" id="det-fact-units">1,500+</span>
            </div>
          </div>
          <div class="fact-row">
            <div class="fact-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="fact-details">
              <span class="fact-lbl">Annual Export Volume</span>
              <span class="fact-val" id="det-fact-volume">Rs.4,000 Cr+</span>
            </div>
          </div>
          <div class="fact-row">
            <div class="fact-icon"><i class="fas fa-users"></i></div>
            <div class="fact-details">
              <span class="fact-lbl">Workforce</span>
              <span class="fact-val" id="det-fact-work">1 Lakh+</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Key Highlights Section -->
    <section class="highlights-section" id="sec-cluster-highlights">
      <div class="container">
        <div class="grid-header">
          <h2>Key Highlights</h2>
        </div>
        <div class="highlights-grid" id="det-highlights-container">
          <!-- Dynamic Highlights -->
        </div>
      </div>
    </section>

    <!-- Volume of Export Flow Section -->
    <section class="chart-section" id="sec-cluster-chart">
      <div class="container">
        <div class="chart-container-card">
          <div class="chart-header">
            <div class="chart-title">
              <h3>Volume of Export Flow</h3>
              <p>Monthly Export Volume (Rs. Cr)</p>
            </div>
            <div class="chart-legend">
              <div class="legend-item"><span class="legend-dot legend-curr"></span> Current Year</div>
              <div class="legend-item"><span class="legend-dot legend-prev"></span> Previous Year</div>
            </div>
          </div>

          <!-- Dynamic high-fidelity crisp SVG chart -->
          <div id="chart-svg-container" style="position:relative; width:100%; height:250px;">
            <!-- SVG will be dynamically generated by Javascript with accurate points! -->
          </div>
        </div>
      </div>
    </section>

    <!-- Exporters List Section -->
    <section class="exporters-section" id="sec-cluster-exporters">
      <div class="container">
        <div class="exporter-filter-row">
          <h2 style="font-size:1.6rem; font-weight:800; color:var(--primary-burgundy);">Exporters & Traders</h2>
          <div style="display:flex; gap:1rem;">
            <div class="search-box-wrapper" style="min-width:240px;">
              <i class="fas fa-search"></i>
              <input type="text" id="exporter-search" placeholder="Search exporters..." onkeyup="searchExporters()">
            </div>
          </div>
        </div>

        <div id="exporters-list-container">
          <!-- Exporters Dynamically Placed -->
        </div>
      </div>
    </section>

    <!-- Facilities Section -->
    <section class="facilities-section" id="sec-cluster-facilities">
      <div class="container">
        <div class="grid-header" style="justify-content:center; text-align:center; margin-bottom:3rem;">
          <h2 style="font-size:2rem;">Facilities & Support</h2>
        </div>
        <div class="facilities-grid" id="det-facilities-container">
          <div class="facility-card">
            <div class="facility-icon-circle"><i class="fas fa-flask"></i></div>
            <h4>Testing Laboratories</h4>
            <p>Modern labs for textile quality testing - fabric strength, colour fastness, shrinkage and chemical safety.</p>
          </div>
          <div class="facility-card">
            <div class="facility-icon-circle"><i class="fas fa-award"></i></div>
            <h4>Certification Assistance</h4>
            <p>Full support with OEKO-TEX, GOTS, ISO 9001, and buyer-specific certification processes.</p>
          </div>
          <div class="facility-card">
            <div class="facility-icon-circle"><i class="fas fa-graduation-cap"></i></div>
            <h4>Training Programmes</h4>
            <p>Skill development workshops, export management training, and capacity building for weavers.</p>
          </div>
          <div class="facility-card">
            <div class="facility-icon-circle"><i class="fas fa-truck-moving"></i></div>
            <h4>Logistic Support</h4>
            <p>Warehousing, freight forwarding partnerships, and safe packing infrastructure for export shipments.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Events Section (NEW: below Facilities & Support) -->
    <section class="events-section" id="sec-cluster-events">
      <div class="container">
        <div class="grid-header" style="justify-content:center; text-align:center; margin-bottom:2.5rem;">
          <span style="display:inline-block; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; background:rgba(179, 142, 93, 0.15); color:var(--primary-burgundy); padding:4px 14px; border-radius:20px; margin-bottom:0.5rem;">Upcoming & Recent</span>
          <h2 style="font-size:2rem; margin:0; color:var(--primary-burgundy);">Cluster Events & Workshops</h2>
          <p style="color:var(--text-muted); font-size:0.92rem; margin-top:0.3rem;">Scheduled conferences, buyer-seller delegations and technical clinics for this textile cluster.</p>
        </div>
        <div class="events-grid" id="det-events-container">
          <!-- Populated dynamically via JavaScript -->
        </div>
      </div>
    </section>

    <!-- Location & Contact Section -->
    <section class="location-section" id="sec-cluster-office">
      <div class="container location-grid">
        <div class="contact-info-card">
          <h4 id="det-contact-title">TEFC Cluster Office — Karur</h4>
          <div class="contact-row">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="contact-details">
              <h5>Address</h5>
              <p id="det-contact-address">TEFC Office, Hill 15, Textile Park, Karur — 639 002, Tamil Nadu</p>
            </div>
          </div>
          <div class="contact-row">
            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
            <div class="contact-details">
              <h5>Phone</h5>
              <p id="det-contact-phone">+91 4324 123456</p>
            </div>
          </div>
          <div class="contact-row">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <div class="contact-details">
              <h5>Email</h5>
              <p id="det-contact-email">karur@tefc.gov.in</p>
            </div>
          </div>
          <div class="contact-row">
            <div class="contact-icon"><i class="fas fa-clock"></i></div>
            <div class="contact-details">
              <h5>Working Hours</h5>
              <p>Mon — Fri, 9:00 AM — 6:00 PM IST</p>
            </div>
          </div>
        </div>

        <div class="cluster-map-card" style="background:#fff; border:1px solid rgba(179, 142, 93, 0.25); border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,0.06); display:flex; flex-direction:column;">
          <div style="padding:1rem 1.25rem; background:linear-gradient(135deg, rgba(108,21,64,0.06) 0%, rgba(108,21,64,0.02) 100%); border-bottom:1px solid rgba(179,142,93,0.2); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
            <div>
              <h4 id="det-map-title" style="margin:0; font-size:1.1rem; font-weight:800; color:var(--primary-burgundy);"><i class="fas fa-map-marker-alt text-danger me-1"></i> Karur, Tamil Nadu</h4>
              <small style="color:var(--text-muted); font-size:0.78rem;">Interactive TEFC Cluster Jurisdiction &amp; Location Map</small>
            </div>
            <a href="#" id="det-map-btn" target="_blank" class="btn-view-details" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1.2rem; border-radius:30px; font-size:0.8rem; text-decoration:none;">
              <i class="fas fa-external-link-alt"></i> View on Google Maps
            </a>
          </div>
          <div style="width:100%; height:340px; background:#e9ecef; position:relative;">
            <iframe id="det-map-iframe" width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=Karur%2C+Tamil+Nadu%2C+India&t=&z=12&ie=UTF8&iwloc=&output=embed"></iframe>
          </div>
        </div>
      </div>
    </section>

    <!-- Support Banner -->
    <div class="container">
      <div class="assistance-banner">
        <h2>Need Assistance? We're here to help.</h2>
        <a href="{{ url('/p/help-support') }}" class="btn-assistance">Contact Support</a>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
// ── DATA FROM DATABASE CMS ──
const clustersData = @json($clustersItems);
const registeredExportersByCentre = @json($registeredExporters ?? []);
const ASSET_BASE = @json(asset('/'));

function formatAssetUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const cleanPath = path.replace(/^\/+/, '');
  const cleanBase = ASSET_BASE.replace(/\/+$/, '');
  return cleanBase + '/' + cleanPath;
}

let currentFilter = 'All';
let currentSearchQuery = '';
let currentSort = 'name';

// Set active cluster dynamically to the first available cluster in clustersData
const clusterKeys = Object.keys(clustersData);
let activeClusterName = clusterKeys.length > 0 ? clusterKeys[0] : '';

// ── RENDER CLUSTER LIST ──
function renderClusterCards() {
  const container = document.getElementById('cluster-cards-container');
  container.innerHTML = '';
  
  let list = Object.values(clustersData);
  
  // Filter by state
  if (currentFilter !== 'All') {
    list = list.filter(c => c.state === currentFilter);
  }
  
  // Filter by search
  if (currentSearchQuery) {
    list = list.filter(c => c.name.toLowerCase().includes(currentSearchQuery.toLowerCase()) || 
                           c.spec.toLowerCase().includes(currentSearchQuery.toLowerCase()) ||
                           c.state.toLowerCase().includes(currentSearchQuery.toLowerCase()));
  }
  
  // Sort
  if (currentSort === 'name') {
    list.sort((a,b) => a.name.localeCompare(b.name));
  } else if (currentSort === 'export') {
    list.sort((a,b) => b.exportNum - a.exportNum);
  }
  
  if (list.length === 0) {
    container.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding:3rem; color:var(--text-muted);">No clusters match your search criteria.</div>';
    return;
  }
  
  const stateClassMap = {
    'gujarat': 'gujarat',
    'tamil nadu': 'tamilnadu',
    'tamilnadu': 'tamilnadu',
    'maharashtra': 'maharashtra',
    'rajasthan': 'rajasthan',
    'uttar pradesh': 'uttarpradesh',
    'uttarpradesh': 'uttarpradesh',
    'punjab': 'punjab'
  };

  const districtNames = {
    'surat': 'Surat, Navsari',
    'karur': 'Karur',
    'ichalkaranji': 'Kolhapur',
    'kolhapur': 'Kolhapur',
    'jaipur': 'Jaipur',
    'varanasi': 'Varanasi, Sant Ravidas Nagar (Bhadohi), Mirzapur, Chunar',
    'ludhiana': 'Ludhiana'
  };

  list.forEach(c => {
    const card = document.createElement('div');
    const key = (c.name || '').toLowerCase();
    const stateKey = (c.state || '').toLowerCase();
    const stateClass = stateClassMap[stateKey] || key;
    const codeKey = (key === 'kolhapur') ? 'ichalkaranji' : key;
    const defaultCard = 'images/clusters/' + codeKey + '_card.png';
    const imgUrl = c.image ? formatAssetUrl(c.image) : formatAssetUrl(defaultCard);
    const distTitle = districtNames[key] || c.name;
    const unitsText = c.units || '100+';

    card.className = `cluster-card ${stateClass} tefc-cluster-card`;
    card.setAttribute('data-centre', key);
    card.style.cursor = 'pointer';

    card.innerHTML = `
      <img src="${imgUrl}" class="card-image" alt="${distTitle}" onerror="this.onerror=null; this.src='${formatAssetUrl(defaultCard)}';">
      <div class="card-content">
        <div class="top-row">
          <div class="state-tag">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>
            </svg>
            ${(c.state || '').toUpperCase()}
          </div>
          <div class="unit-tag">
            <svg viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; vertical-align:middle; width:18px; height:18px;">
              <path d="M4 19V9l6 3V9l6 3V7l4 2v10H4zm2-2h12v-6.5l-2-1V13l-6-3v4.5l-4-2V17z"/>
            </svg>
            ${unitsText}
          </div>
        </div>

        <div class="main-info">
          <h2 class="city">${distTitle}</h2>
          <p class="jurisdiction">Designated Revenue Jurisdiction</p>
        </div>

        <a href="javascript:void(0)" class="explore-btn">
          <span>Explore Cluster</span>
          <span class="arrow-circle">→</span>
        </a>
      </div>
    `;

    card.onclick = (e) => {
      e.preventDefault();
      viewClusterDetail(c.name);
    };
    container.appendChild(card);
  });
}

// ── SEARCH & FILTER ACTIONS ──
function filterByState(state) {
  currentFilter = state;
  document.querySelectorAll('.filter-tab').forEach(btn => {
    if (btn.textContent.trim() === (state === 'All' ? 'All States' : state)) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });
  renderClusterCards();
}

function searchClusters() {
  currentSearchQuery = document.getElementById('cluster-search').value;
  renderClusterCards();
}

function sortClusters() {
  currentSort = document.getElementById('sort-select').value;
  renderClusterCards();
}

// ── DETAIL VIEW ACTIONS ──
function viewClusterDetail(name) {
  activeClusterName = name;
  const data = clustersData[name];
  if (!data) return;
  
  // Update texts
  document.getElementById('det-bc-name').textContent = data.name;
  document.getElementById('det-hero-title').textContent = data.name + " Textile Cluster";
  document.getElementById('det-hero-subtitle').textContent = `Strategic export & manufacturing hub for ${data.spec.toLowerCase()}`;
  document.getElementById('det-badge-location').textContent = data.state;
  document.getElementById('det-badge-spec').textContent = data.spec;
  document.getElementById('det-badge-era').textContent = data.era;
  
  const exportVolumeStr = (data.exportVal && data.exportVal.trim() !== '') 
    ? data.exportVal 
    : (data.exportNum ? `Rs.${Number(data.exportNum).toLocaleString('en-IN')} Cr+` : (data.exportValue || 'Rs.4,000 Cr+'));
  
  document.getElementById('det-stat-units').textContent = data.units || '1,500+';
  document.getElementById('det-stat-value').textContent = exportVolumeStr;
  document.getElementById('det-stat-workforce').textContent = data.workforce || '1 Lakh+';
  document.getElementById('det-stat-dests').textContent = data.dests || '60+';
  
  document.getElementById('det-overview-p').textContent = data.desc;
  
  document.getElementById('det-fact-est').textContent = data.era || '2026';
  document.getElementById('det-fact-units').textContent = data.units || '1,500+';
  document.getElementById('det-fact-volume').textContent = exportVolumeStr;
  document.getElementById('det-fact-work').textContent = data.workforce || '1 Lakh+';
  
  // Render highlights
  const highlightsContainer = document.getElementById('det-highlights-container');
  highlightsContainer.innerHTML = '';
  const highlightsList = data.highlights || [];
  highlightsContainer.className = 'highlights-grid highlights-' + highlightsList.length;
  highlightsList.forEach(h => {
    const name = typeof h === 'object' ? (h.name || '') : h;
    const img = (typeof h === 'object' && h.image) ? h.image : null;
    const card = document.createElement('div');
    card.className = 'highlight-card';
    const imgUrl = img ? formatAssetUrl(img) : '';
    card.innerHTML = `
      <div class="highlight-pattern" style="${imgUrl ? 'background:none; overflow:hidden;' : ''}">
        ${imgUrl ? `<img src="${imgUrl}" alt="${name}" onerror="this.onerror=null; this.parentElement.style.background=''; this.parentElement.innerHTML='<i class=\\'fas fa-cube\\'></i>';" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">` : `<i class="fas fa-cube"></i>`}
      </div>
      <h4>${name}</h4>
      <p style="font-size:0.75rem; color:var(--text-muted);">Premium grade exports</p>
    `;
    highlightsContainer.appendChild(card);
  });
  
  // Render exporter contact details
  document.getElementById('det-contact-title').textContent = `TEFC Cluster Office — ${data.name}`;
  document.getElementById('det-contact-address').textContent = data.address;
  document.getElementById('det-contact-phone').textContent = data.phone;
  document.getElementById('det-contact-email').textContent = data.email;
  
  document.getElementById('det-map-title').innerHTML = `<i class="fas fa-map-marker-alt text-danger me-1"></i> ${data.name}, ${data.state}`;
  document.getElementById('det-map-btn').href = data.mapUrl || `https://maps.google.com/?q=${encodeURIComponent(data.name + ', ' + data.state + ', India')}`;
  const mapIframe = document.getElementById('det-map-iframe');
  if (mapIframe) {
    mapIframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(data.name + ', ' + data.state + ', India')}&t=&z=12&ie=UTF8&iwloc=&output=embed`;
  }
  
  // Render high-fidelity SVG chart
  renderSVGChart(data.chartCurr, data.chartPrev);
  
  // Render dynamic registered exporters from database + static cluster items
  const centreKey = name.toLowerCase().trim();
  const rawRegExps = (typeof registeredExportersByCentre !== 'undefined' && registeredExportersByCentre[centreKey]) ? registeredExportersByCentre[centreKey] : [];
  const dynamicExps = rawRegExps.map(u => {
    const prof = u.exporter_profile || {};
    const prods = prof.textile_segment ? prof.textile_segment.split(',').map(s => s.trim()) : ['Textile Products'];
    const exports = prof.major_exporting_countries ? prof.major_exporting_countries.split(',').map(s => s.trim()) : ['Global Markets'];
    const certs = prof.certifications ? prof.certifications.split(',').map(s => s.trim()) : ['Verified Registered Exporter', 'IEC Active'];
    return {
      name: prof.company_name || u.name,
      owner: u.name,
      est: prof.year_of_establishment || 'Registered Unit',
      prods: prods,
      exports: exports,
      certs: certs,
      is_registered: true,
      iec_code: prof.iec_code,
      gst_no: prof.gst_no,
      address: prof.factory_address || `${data.name}, ${data.state}`,
      phone: prof.contact_phone || u.phone,
      email: u.email
    };
  });

  const mergedExporters = [...dynamicExps, ...(data.exporters || [])];
  renderExportersList(mergedExporters);

  // Render facilities (dynamic or default)
  const facilitiesGrid = document.getElementById('det-facilities-container');
  if (facilitiesGrid && Array.isArray(data.facilities) && data.facilities.length > 0) {
    facilitiesGrid.innerHTML = '';
    data.facilities.forEach(fac => {
      const card = document.createElement('div');
      card.className = 'facility-card';
      card.innerHTML = `
        <div class="facility-icon-circle"><i class="${fac.icon || 'fas fa-building'}"></i></div>
        <h4>${fac.title || 'Facility'}</h4>
        <p>${fac.desc || ''}</p>
      `;
      facilitiesGrid.appendChild(card);
    });
  }

  // Render events (NEW)
  const eventsContainer = document.getElementById('det-events-container');
  if (eventsContainer) {
    eventsContainer.innerHTML = '';
    const eventsList = Array.isArray(data.events) ? data.events : [];
    if (eventsList.length === 0) {
      eventsContainer.innerHTML = `
        <div style="grid-column: 1/-1; background:#fff; border:1px dashed rgba(179, 142, 93, 0.3); border-radius:12px; padding:2.5rem; text-align:center; color:var(--text-muted);">
          <i class="fas fa-calendar-alt fa-2x mb-2" style="opacity:0.4; color:var(--primary-burgundy);"></i>
          <h5 style="color:var(--text-dark); margin-bottom:0.4rem;">No Upcoming Scheduled Events</h5>
          <p style="margin:0; font-size:0.88rem;">New workshops, buyer delegations and technical clinics for ${data.name} will be announced here shortly.</p>
        </div>
      `;
    } else {
      eventsList.forEach(evt => {
        const parts = (evt.date || '').split(' ');
        const month = parts[0] || 'TBD';
        const day = parts[1] ? parts[1].replace(',', '') : '';
        
        const card = document.createElement('div');
        card.className = 'event-card';
        card.innerHTML = `
          <div>
            <div class="event-header-row">
              <div class="event-date-box">
                <span class="event-date-month">${month}</span>
                <span class="event-date-day">${day || '•'}</span>
              </div>
              <div class="event-badges-wrap">
                <span class="event-type-pill">${evt.type || 'Workshop'}</span>
                ${evt.status ? `<span class="event-status-badge"><i class="fas fa-circle" style="font-size:0.4rem; vertical-align:middle; margin-right:3px;"></i>${evt.status}</span>` : ''}
              </div>
            </div>
            <h3 class="event-title">${evt.title}</h3>
            <div class="event-meta-info">
              ${evt.time ? `<div class="event-meta-item"><i class="fas fa-clock"></i><span>${evt.time}</span></div>` : ''}
              ${evt.venue ? `<div class="event-meta-item"><i class="fas fa-map-marker-alt"></i><span>${evt.venue}</span></div>` : ''}
            </div>
            <p class="event-desc">${evt.desc || ''}</p>
          </div>
          <div class="event-footer-action">
            <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">
              ${evt.badge || 'TEFC Facilitation'}
            </span>
            <a href="${evt.reg_url || '#register'}" class="btn-event-reg">
              ${(evt.status && evt.status.toLowerCase().includes('open')) ? 'Register Now' : 'Event Details'} <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
            </a>
          </div>
        `;
        eventsContainer.appendChild(card);
      });
    }
  }

  // Active / Inactive section visibility management
  const secActive = data.sections_active || {};
  const isSectionOn = (key) => secActive[key] !== false;

  const toggleSec = (elementId, isActive) => {
    const el = document.getElementById(elementId);
    if (el) {
      el.style.display = isActive ? '' : 'none';
    }
  };

  toggleSec('sec-cluster-stats', isSectionOn('stats'));
  toggleSec('sec-cluster-overview', isSectionOn('overview'));
  toggleSec('sec-cluster-highlights', isSectionOn('highlights'));
  toggleSec('sec-cluster-chart', isSectionOn('chart'));
  toggleSec('sec-cluster-exporters', isSectionOn('exporters'));
  toggleSec('sec-cluster-facilities', isSectionOn('facilities'));
  toggleSec('sec-cluster-events', isSectionOn('events'));
  toggleSec('sec-cluster-office', isSectionOn('office'));
  
  // Switch view
  document.getElementById('listing-view').style.display = 'none';
  document.getElementById('detail-view').style.display = 'block';
  window.scrollTo({ top: 0, behavior: 'instant' });
}

function goBackToListing() {
  document.getElementById('listing-view').style.display = 'block';
  document.getElementById('detail-view').style.display = 'none';
  window.scrollTo({ top: 0, behavior: 'instant' });
}

// ── SVG HIGH-FIDELITY CHART ──
function renderSVGChart(currData, prevData) {
  const container = document.getElementById('chart-svg-container');
  container.innerHTML = '';
  
  const width = container.clientWidth || 800;
  const height = 220;
  const paddingLeft = 40;
  const paddingBottom = 30;
  const paddingTop = 20;
  const paddingRight = 20;
  
  const chartW = width - paddingLeft - paddingRight;
  const chartH = height - paddingTop - paddingBottom;
  
  const maxVal = Math.max(...currData, ...prevData) * 1.15;
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  
  // Generate points
  const getPointsStr = (data) => {
    return data.map((val, i) => {
      const x = paddingLeft + (i / 11) * chartW;
      const y = height - paddingBottom - (val / maxVal) * chartH;
      return `${x},${y}`;
    }).join(' ');
  };
  
  const currPoints = getPointsStr(currData);
  const prevPoints = getPointsStr(prevData);
  
  // Grid lines
  let gridLines = '';
  for(let i=0; i<=4; i++) {
    const yVal = maxVal * (i/4);
    const yPos = height - paddingBottom - (yVal / maxVal) * chartH;
    gridLines += `
      <line x1="${paddingLeft}" y1="${yPos}" x2="${width - paddingRight}" y2="${yPos}" stroke="#ebdcb9" stroke-width="0.8" stroke-dasharray="4" />
      <text x="${paddingLeft - 8}" y="${yPos + 4}" fill="#7d6e5e" font-size="9" text-anchor="end" font-weight="600">${Math.round(yVal)}</text>
    `;
  }
  
  // Month labels
  let monthLabels = '';
  months.forEach((m, i) => {
    const xPos = paddingLeft + (i / 11) * chartW;
    monthLabels += `<text x="${xPos}" y="${height - 10}" fill="#7d6e5e" font-size="10" text-anchor="middle" font-weight="600">${m}</text>`;
  });
  
  // Create beautiful SVG
  const svgHTML = `
    <svg width="100%" height="100%" viewBox="0 0 ${width} ${height}" style="overflow:visible;">
      <defs>
        <linearGradient id="burgundyGrad" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="#6c1340" stop-opacity="0.15" />
          <stop offset="100%" stop-color="#6c1340" stop-opacity="0.0" />
        </linearGradient>
        <linearGradient id="goldGrad" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="#b38e5d" stop-opacity="0.1" />
          <stop offset="100%" stop-color="#b38e5d" stop-opacity="0.0" />
        </linearGradient>
      </defs>
      
      <!-- Grid -->
      ${gridLines}
      
      <!-- Month Labels -->
      ${monthLabels}
      
      <!-- Previous Year Area & Path -->
      <polygon points="${paddingLeft},${height - paddingBottom} ${prevPoints} ${width - paddingRight},${height - paddingBottom}" fill="url(#goldGrad)" />
      <polyline points="${prevPoints}" fill="none" stroke="#b38e5d" stroke-width="2.5" stroke-linecap="round" />
      
      <!-- Current Year Area & Path -->
      <polygon points="${paddingLeft},${height - paddingBottom} ${currPoints} ${width - paddingRight},${height - paddingBottom}" fill="url(#burgundyGrad)" />
      <polyline points="${currPoints}" fill="none" stroke="#6c1340" stroke-width="3" stroke-linecap="round" />
      
      <!-- Current Points -->
      ${currData.map((val, i) => {
        const x = paddingLeft + (i / 11) * chartW;
        const y = height - paddingBottom - (val / maxVal) * chartH;
        return `<circle cx="${x}" cy="${y}" r="4" fill="#6c1340" stroke="#fff" stroke-width="1.5" style="cursor:pointer;" />`;
      }).join('')}
    </svg>
  `;
  
  container.innerHTML = svgHTML;
}

// ── EXPORTERS RENDER & SEARCH ──
let activeExporters = [];
function renderExportersList(exporters) {
  activeExporters = exporters;
  filterExportersList();
}

function filterExportersList() {
  const query = document.getElementById('exporter-search').value.toLowerCase().trim();
  const container = document.getElementById('exporters-list-container');
  container.innerHTML = '';
  
  let list = activeExporters;
  if (query) {
    list = list.filter(e => (e.name && e.name.toLowerCase().includes(query)) || 
                           (e.owner && e.owner.toLowerCase().includes(query)) ||
                           (e.iec_code && e.iec_code.toLowerCase().includes(query)) ||
                           (e.gst_no && e.gst_no.toLowerCase().includes(query)) ||
                           (e.prods && e.prods.some(p => p.toLowerCase().includes(query))));
  }
  
  if (list.length === 0) {
    container.innerHTML = '<div style="text-align:center; padding:2rem; color:var(--text-muted);">No exporters matched this search.</div>';
    return;
  }
  
  list.forEach(e => {
    const card = document.createElement('div');
    card.className = 'exporter-card';
    
    const prodTags = (e.prods || []).map(p => `<span class="card-tag" style="background:rgba(108,21,63,0.06); color:var(--primary-burgundy); border-color:rgba(108,21,63,0.1);">${p}</span>`).join('');
    const exportCountries = (e.exports || []).join(', ');
    const certTags = (e.certs || []).map(c => `<span class="badge bg-light text-dark border me-1">${c}</span>`).join('');
    
    const safeName = encodeURIComponent(e.name || '');
    const safeEmail = encodeURIComponent(e.email || '');
    const safePhone = encodeURIComponent(e.phone || '');

    card.innerHTML = `
      <div class="exporter-header-row">
        <div class="exporter-identity">
          <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.35rem;">
            <h4 style="margin:0; font-size:1.15rem;">${e.name}</h4>
            ${e.is_registered ? `<span class="badge" style="background:#6c1340; color:#fff; font-size:0.7rem; font-weight:700; padding:3px 8px; border-radius:4px;"><i class="fas fa-check-circle me-1"></i> Registered Exporter</span>` : ''}
            ${e.iec_code ? `<span class="badge bg-light text-dark border" style="font-size:0.7rem; font-family:monospace;">IEC: ${e.iec_code}</span>` : ''}
          </div>
          <p style="margin:0; font-size:0.83rem; color:var(--text-muted);">
            Owner / Head: <strong>${e.owner}</strong> | Established: <strong>${e.est}</strong> 
            ${e.gst_no ? `| GSTIN: <strong style="font-family:monospace;">${e.gst_no}</strong>` : ''}
          </p>
        </div>
        <button class="btn-exporter-details" onclick="contactClusterExporter('${safeName}', '${safeEmail}', '${safePhone}')">Contact Exporter</button>
      </div>
      
      <div class="exporter-details-row">
        <div class="exporter-meta-block">
          <span class="exporter-meta-lbl">Product Range</span>
          <div class="exporter-meta-tags">
            ${prodTags}
          </div>
        </div>
        
        <div class="exporter-meta-block">
          <span class="exporter-meta-lbl">Main Export Markets</span>
          <span class="exporter-meta-val">${exportCountries}</span>
        </div>
        
        <div class="exporter-meta-block">
          <span class="exporter-meta-lbl">Certifications &amp; Accreditations</span>
          <div class="mt-1">
            ${certTags}
          </div>
        </div>
      </div>
    `;
    container.appendChild(card);
  });
}

function contactClusterExporter(name, email, phone) {
  const decName = decodeURIComponent(name);
  const decEmail = decodeURIComponent(email);
  const decPhone = decodeURIComponent(phone);
  let msg = `Connecting with ${decName}:\n\n`;
  if (decEmail) msg += `Email: ${decEmail}\n`;
  if (decPhone) msg += `Phone: ${decPhone}\n`;
  msg += `\nYou may contact this registered exporter directly or place an inquiry via the TEFC Portal.`;
  alert(msg);
}

function searchExporters() {
  filterExportersList();
}

// ── INIT ──
document.addEventListener('DOMContentLoaded', () => {
  renderClusterCards();
  
  // Check for ?cluster=... in URL
  const urlParams = new URLSearchParams(window.location.search);
  const clusterParam = urlParams.get('cluster');
  if (clusterParam) {
    const pLower = clusterParam.toLowerCase();
    const match = Object.values(clustersData).find(c => 
      c.name.toLowerCase() === pLower ||
      (pLower === 'kolhapur' && c.name.toLowerCase() === 'ichalkaranji') ||
      (pLower === 'ichalkaranji' && c.name.toLowerCase() === 'kolhapur')
    );
    if (match) {
      viewClusterDetail(match.name);
    }
  }

  // Resize chart on window resize
  window.addEventListener('resize', () => {
    const detailView = document.getElementById('detail-view');
    if (detailView.style.display === 'block') {
      const data = clustersData[activeClusterName];
      if (data) renderSVGChart(data.chartCurr, data.chartPrev);
    }
  });
});
</script>
@endsection
