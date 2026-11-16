@extends('layouts.app')

@section('title', 'Resources & Trade Guides — TEFC')

@section('styles')
<style>
/* ── THEME TOKENS ── */
:root {
  --wine: #74113c;
  --wine-dark: #4b0c27;
  --wine-deep: #430b25;
  --gold: #efb62d;
  --gold-soft: #fbf5e4;
  --gold-light: #fef9ed;
  --ink: #211d22;
  --text-dark: #24222a;
  --muted: #706b75;
  --line: #e7e1e6;
  --canvas: #faf8f7;
  --surface: #ffffff;
  --radius: 18px;
  --blue: #2f68a8;
  --blue-soft: #edf4fb;
  --focus: #135fce;
}

/* ── LAYOUT & PAGE WRAPPERS ── */
.resources-page-container {
  background: var(--canvas);
  color: var(--ink);
  font-family: 'DM Sans', 'Noto Sans', 'Inter', -apple-system, sans-serif;
  padding-bottom: 0;
  -webkit-font-smoothing: antialiased;
}

/* ── HERO BANNER ── */
.resources-hero {
  background: linear-gradient(135deg, rgba(40, 5, 22, 0.72) 0%, rgba(108, 19, 64, 0.65) 100%),
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
  background-image: radial-gradient(circle at 80% 20%, rgba(239, 182, 45, 0.18) 0%, transparent 50%);
  opacity: 0.8;
  pointer-events: none;
}
.resources-hero-inner {
  position: relative;
  z-index: 2;
}
.resources-breadcrumbs {
  font-size: 0.84rem;
  margin-bottom: 1.2rem;
  opacity: 0.85;
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
  color: var(--gold);
}
.resources-hero h1 {
  font-size: clamp(2.4rem, 4.5vw, 3.4rem);
  font-weight: 800;
  margin-bottom: 0.8rem;
  letter-spacing: -0.03em;
  line-height: 1.15;
}
.resources-hero p {
  font-size: 1.05rem;
  opacity: 0.95;
  max-width: 800px;
  line-height: 1.65;
  margin: 0;
  color: #f7eff3;
}

/* ── STICKY TAB NAV STRIP ── */
.resources-filter-strip {
  background: #fff;
  border-bottom: 1px solid rgba(231, 225, 230, 0.9);
  padding: 0.9rem 0;
  position: sticky;
  top: 0;
  z-index: 95;
  box-shadow: 0 4px 18px rgba(0,0,0,0.03);
}
.resources-tabs {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
  justify-content: center;
}
.resources-tab {
  background: #fff;
  border: 1px solid rgba(116, 17, 60, 0.2);
  color: var(--ink);
  padding: 0.45rem 1.25rem;
  border-radius: 30px;
  font-size: 0.84rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  text-decoration: none;
}
.resources-tab:hover {
  border-color: var(--wine);
  color: var(--wine);
  background: #fbf5f8;
}
.resources-tab.active {
  background: var(--wine);
  border-color: var(--wine);
  color: #fff;
  box-shadow: 0 4px 12px rgba(116, 17, 60, 0.2);
}

/* ── SECTION COMMON ── */
.res-section-pad {
  padding: clamp(48px, 6vw, 76px) 0;
}
.res-section-alt {
  background: #fdfaf8;
  border-top: 1px solid var(--line);
  border-bottom: 1px solid var(--line);
}

/* ═════════════════════════════════════════════════════════
   1. SECTION: EXPLORE EXPORT MARKETS (.market-section)
═════════════════════════════════════════════════════════ */
.market-section .section-top {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  align-items: end;
  gap: 28px;
  margin-bottom: 32px;
}
.market-section .kicker {
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--wine);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .16em;
  text-transform: uppercase;
}
.market-section .kicker:before {
  content: "";
  width: 26px;
  height: 2px;
  background: var(--gold);
}
.market-section h2 {
  font-size: clamp(28px, 3.8vw, 44px);
  font-weight: 800;
  line-height: 1.1;
  letter-spacing: -.045em;
  margin: 12px 0;
  color: var(--wine-dark);
}
.market-section .intro {
  font-size: 14.5px;
  line-height: 1.7;
  color: var(--muted);
  max-width: 680px;
  margin: 0;
}
.market-section .view-all {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0 6px;
  border-bottom: 1px solid #c8a4b5;
  text-decoration: none;
  color: var(--wine);
  font-size: 13.5px;
  font-weight: 800;
  white-space: nowrap;
  transition: gap .2s, border-color .2s;
}
.market-section .view-all:hover {
  gap: 18px;
  border-color: var(--wine);
}
.market-section .view-all span {
  font-size: 17px;
  line-height: 1;
}

.market-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
}
.market-card {
  position: relative;
  isolation: isolate;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  min-height: 360px;
  background: var(--surface);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  text-decoration: none;
  transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
  color: inherit;
}
.market-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 42px rgba(57, 22, 39, .10);
  border-color: #d9c2ce;
  color: inherit;
}
.market-card .visual {
  height: 174px;
  position: relative;
  overflow: hidden;
  background: var(--tint, #f1e7ed);
}
.market-card .visual:before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(30,14,24,.06), rgba(30,14,24,.36));
  z-index: 0;
  pointer-events: none;
}
.market-card .visual:after {
  content: "";
  position: absolute;
  width: 145px;
  height: 145px;
  right: 20px;
  bottom: -85px;
  background: var(--orb, #c9a4b7);
  opacity: .28;
  border-radius: 50%;
  z-index: 0;
  pointer-events: none;
}
.market-card .country-code {
  position: absolute;
  z-index: 2;
  left: 20px;
  bottom: 8px;
  font-size: clamp(56px, 5.5vw, 80px);
  line-height: .9;
  font-weight: 900;
  letter-spacing: -.08em;
  color: #fff;
  text-shadow: 0 2px 18px rgba(0,0,0,.25);
}
.market-card .visual-label {
  position: absolute;
  z-index: 2;
  top: 16px;
  left: 20px;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: #fff;
  opacity: .95;
  text-shadow: 0 1px 8px rgba(0,0,0,.3);
}
.market-card .visual-index {
  position: absolute;
  z-index: 2;
  right: 20px;
  top: 16px;
  font-size: 11px;
  font-weight: 800;
  color: #fff;
  opacity: .95;
  text-shadow: 0 1px 8px rgba(0,0,0,.3);
}
.market-card .card-content {
  padding: 20px 20px 18px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.market-card .region {
  font-size: 10px;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--wine);
  font-weight: 800;
  margin-bottom: 6px;
}
.market-card h3 {
  font-size: 19px;
  line-height: 1.2;
  letter-spacing: -.03em;
  font-weight: 800;
  margin: 0 0 8px;
  color: var(--wine-dark);
}
.market-card .card-content p {
  font-size: 12.5px;
  line-height: 1.65;
  color: var(--muted);
  margin: 0 0 18px;
}
.market-card .card-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid #f0ecee;
  color: var(--wine);
  font-size: 12px;
  font-weight: 800;
}
.market-card .circle-arrow {
  display: grid;
  place-items: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #f6edf1;
  color: var(--wine);
  font-size: 15px;
  transition: transform .2s, background .2s;
}
.market-card:hover .circle-arrow {
  transform: translate(2px, -2px);
  background: #efdee6;
}

.us { --tint: #f1e7ed; --orb: #c9a4b7; --code: #74113c; }
.de { --tint: #f0ece5; --orb: #c9bba4; --code: #493c30; }
.jp { --tint: #f5e9e7; --orb: #d7aaa4; --code: #803c42; }
.ae { --tint: #e9efeb; --orb: #a8c1b5; --code: #275c4a; }

.bottom-note {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-top: 22px;
  padding: 14px 18px;
  border: 1px solid #eadfe4;
  border-radius: 12px;
  background: #fff;
}
.bottom-note .note-icon {
  width: 24px;
  height: 24px;
  flex: none;
  display: grid;
  place-items: center;
  border-radius: 50%;
  background: #f4e7ed;
  color: var(--wine);
  font-size: 12px;
  font-weight: 800;
}
.bottom-note p {
  margin: 0;
  color: var(--muted);
  font-size: 12px;
  line-height: 1.65;
}

/* ═════════════════════════════════════════════════════════
   2. SECTION: TEXTILE EXPORT PROCESS (.export-process)
═════════════════════════════════════════════════════════ */
.process-hero {
  margin: 0 0 22px;
  padding: clamp(26px, 4.5vw, 44px);
  border-radius: 18px;
  background: linear-gradient(115deg, #4d0b28, #74113c 70%, #8b2851);
  color: white;
  position: relative;
  overflow: hidden;
}
.process-hero:after {
  content: "";
  position: absolute;
  width: 320px;
  height: 320px;
  border: 1px solid #ffffff25;
  border-radius: 50%;
  right: -80px;
  top: -140px;
  box-shadow: 0 0 0 35px #ffffff09, 0 0 0 75px #ffffff06;
}
.process-hero > * {
  position: relative;
  z-index: 1;
}
.process-eyebrow {
  color: var(--gold);
  text-transform: uppercase;
  letter-spacing: .14em;
  font-size: .75rem;
  font-weight: 800;
}
.process-hero h2 {
  font-size: clamp(2rem, 3.8vw, 2.9rem);
  line-height: 1.12;
  letter-spacing: -.04em;
  margin: 8px 0 12px;
  color: #fff;
  font-weight: 800;
}
.process-hero p {
  max-width: 780px;
  color: #f4e9ef;
  margin: 0;
  font-size: 1rem;
  line-height: 1.65;
}
.process-chips {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 18px;
}
.process-chips span {
  border: 1px solid #ffffff4a;
  background: #ffffff12;
  padding: 5px 11px;
  border-radius: 30px;
  font-size: .8rem;
  font-weight: 600;
}

.process-notice {
  background: #fff9ec;
  border: 1px solid #eedda9;
  border-radius: 12px;
  padding: 13px 18px;
  color: #5f4e28;
  font-size: .86rem;
  line-height: 1.6;
  margin-bottom: 24px;
}

.process-layout {
  display: grid;
  grid-template-columns: 260px minmax(0, 1fr);
  gap: 24px;
  align-items: start;
}
.process-side {
  background: white;
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 18px;
  position: sticky;
  top: 75px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.02);
}
.process-side h3 {
  font-size: .95rem;
  font-weight: 800;
  color: var(--wine-dark);
  margin: 0 0 12px;
}
.process-meter {
  display: flex;
  justify-content: space-between;
  font-size: .78rem;
  color: var(--muted);
  font-weight: 700;
}
.process-track {
  height: 8px;
  border-radius: 9px;
  background: #eee8ed;
  overflow: hidden;
  margin-top: 7px;
}
.process-track span {
  display: block;
  width: 0%;
  height: 100%;
  background: var(--gold);
  transition: width .3s ease;
}
.process-side ol {
  padding: 0;
  list-style: none;
  display: grid;
  gap: 4px;
  margin: 16px 0 0;
}
.process-side a {
  display: flex;
  gap: 9px;
  align-items: center;
  padding: 7px 9px;
  border-radius: 8px;
  text-decoration: none;
  color: #514b53;
  font-size: .82rem;
  line-height: 1.35;
  transition: all .15s;
}
.process-side a:hover,
.process-side a.active {
  background: #f8edf2;
  color: var(--wine);
  font-weight: 700;
}
.process-side b {
  display: grid;
  place-items: center;
  background: #f0ecef;
  border-radius: 7px;
  width: 24px;
  height: 24px;
  flex: none;
  font-size: .75rem;
}
.process-side a.active b {
  background: var(--wine);
  color: white;
}

.process-mainhead {
  display: flex;
  justify-content: space-between;
  align-items: end;
  gap: 12px;
  margin-bottom: 16px;
}
.process-mainhead h3 {
  margin: 0;
  color: var(--wine-dark);
  font-size: 1.35rem;
  font-weight: 800;
}
.process-mainhead p {
  margin: 3px 0 0;
  font-size: .85rem;
  color: var(--muted);
}
.process-toggle-btn {
  border: 1px solid var(--line);
  background: #fff;
  color: var(--wine);
  font-weight: 800;
  border-radius: 8px;
  padding: 8px 14px;
  font-size: .8rem;
  cursor: pointer;
  transition: background .2s;
}
.process-toggle-btn:hover {
  background: #fcf4f7;
}

.process-step {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 14px;
  margin-bottom: 12px;
  overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}
.process-step[open] {
  border-color: #dcc4d0;
  box-shadow: 0 8px 24px rgba(50, 17, 38, .05);
}
.process-step summary {
  list-style: none;
  cursor: pointer;
  padding: 17px;
  display: grid;
  grid-template-columns: 42px 1fr auto 15px;
  gap: 14px;
  align-items: center;
}
.process-step summary::-webkit-details-marker {
  display: none;
}
.step-num {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: #f8edf2;
  color: var(--wine);
  display: grid;
  place-items: center;
  font-weight: 800;
  font-size: .95rem;
  transition: background .2s, color .2s;
}
.process-step[open] .step-num {
  background: var(--wine);
  color: white;
}
.step-stitle {
  font-weight: 800;
  font-size: 1.02rem;
  color: var(--wine-dark);
  line-height: 1.35;
}
.step-sub {
  display: block;
  color: var(--muted);
  font-size: .81rem;
  font-weight: 400;
  margin-top: 4px;
}
.step-time {
  font-size: .73rem;
  font-weight: 800;
  background: #fff7df;
  color: #72540a;
  border: 1px solid #f0dfaa;
  border-radius: 7px;
  padding: 5px 8px;
  white-space: nowrap;
}
.step-chev {
  color: var(--wine);
  font-size: 1.1rem;
  transition: transform .2s;
}
.process-step[open] .step-chev {
  transform: rotate(180deg);
}

.step-content {
  padding: 0 20px 22px 73px;
  border-top: 1px solid #f9f5f7;
}
.step-content p {
  margin: 12px 0 12px;
  color: #514c54;
  font-size: .88rem;
  line-height: 1.65;
}
.step-content h4 {
  font-size: .88rem;
  font-weight: 800;
  color: var(--wine-dark);
  margin: 16px 0 6px;
}
.step-content ul {
  padding-left: 18px;
  margin: 6px 0 12px;
}
.step-content li {
  padding: 2px 0;
  color: #514c54;
  font-size: .84rem;
}
.step-cols {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin: 12px 0;
}
.step-panel {
  background: #fbf9fb;
  border: 1px solid #eee7ed;
  border-radius: 10px;
  padding: 13px;
}
.step-panel h4 {
  margin: 0 0 6px;
}
.step-links {
  display: flex;
  gap: 7px;
  flex-wrap: wrap;
  margin: 14px 0 8px;
}
.step-links a {
  border: 1px solid #e4cdd7;
  background: #fff9fc;
  border-radius: 7px;
  padding: 6px 10px;
  text-decoration: none;
  font-size: .78rem;
  font-weight: 800;
  color: var(--wine);
  transition: background .2s;
}
.step-links a:hover {
  background: #f7e6ee;
  text-decoration: underline;
}
.step-footnote {
  font-size: .78rem !important;
  color: var(--muted) !important;
  margin-top: 8px !important;
}

.process-final,
.process-sources {
  background: white;
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 22px;
  margin: 22px 0;
}
.process-final h3,
.process-sources h3 {
  margin: 0 0 6px;
  color: var(--wine-dark);
  font-size: 1.18rem;
  font-weight: 800;
}
.checkgrid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 14px;
}
.check-item {
  display: flex;
  gap: 10px;
  border: 1px solid var(--line);
  border-radius: 9px;
  padding: 10px 12px;
  font-size: .84rem;
  color: var(--ink);
  cursor: pointer;
  background: #fff;
  transition: all .15s;
  user-select: none;
}
.check-item input {
  accent-color: var(--wine);
  margin-top: 4px;
  cursor: pointer;
}
.check-item:has(input:checked) {
  background: #f3faf6;
  border-color: #b9d8c7;
  font-weight: 700;
  color: #1b6238;
}

.sourcegrid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 14px;
}
.sourcegrid a {
  display: block;
  text-decoration: none;
  border: 1px solid var(--line);
  border-radius: 10px;
  padding: 12px 14px;
  background: #fff;
  transition: border-color .2s, transform .2s;
}
.sourcegrid a:hover {
  border-color: #d6b7c5;
  transform: translateY(-2px);
}
.sourcegrid strong {
  display: block;
  font-size: .86rem;
  color: var(--wine);
}
.sourcegrid span {
  font-size: .77rem;
  color: var(--muted);
  display: block;
  margin-top: 2px;
}

/* ═════════════════════════════════════════════════════════
   3. SECTION: RECENTLY LAUNCHED SERVICES (.services)
═════════════════════════════════════════════════════════ */
.services-section .eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--wine);
  font-size: 11px;
  letter-spacing: .15em;
  text-transform: uppercase;
  font-weight: 800;
}
.services-section .eyebrow-mark {
  width: 30px;
  height: 30px;
  border-radius: 9px;
  background: #f2e6ec;
  display: grid;
  place-items: center;
  color: var(--wine);
}
.services-section .heading-row {
  display: flex;
  justify-content: space-between;
  align-items: end;
  gap: 28px;
  margin: 14px 0 24px;
}
.services-section h2 {
  font-size: clamp(28px, 3.2vw, 38px);
  line-height: 1.12;
  letter-spacing: -.04em;
  margin: 0;
  font-weight: 800;
  color: var(--wine-dark);
}
.services-section .heading-copy {
  max-width: 480px;
  margin: 0;
  color: var(--muted);
  font-size: 14px;
  line-height: 1.7;
}
.services-section .gold-rule {
  height: 1px;
  background: linear-gradient(90deg, var(--gold), #eadfe4 75%, transparent);
  margin-bottom: 26px;
}
.services-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}
.service-card {
  display: flex;
  gap: 20px;
  min-width: 0;
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 16px;
  padding: 26px;
  box-shadow: 0 3px 12px rgba(57, 21, 39, .04);
  transition: transform .22s, border-color .22s, box-shadow .22s;
}
.service-card:hover {
  transform: translateY(-3px);
  border-color: #d8b6c6;
  box-shadow: 0 14px 34px rgba(57, 21, 39, .10);
}
.service-card .icon-box {
  width: 52px;
  height: 52px;
  flex: 0 0 52px;
  border-radius: 14px;
  display: grid;
  place-items: center;
}
.service-card .icon-box.blue {
  background: var(--blue-soft);
  color: var(--blue);
}
.service-card .icon-box.gold {
  background: var(--gold-soft);
  color: #b98a16;
}
.service-card .icon-box svg {
  width: 25px;
  height: 25px;
}
.service-card .card-content {
  min-width: 0;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  flex: 1;
}
.service-card .card-title-row {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin: 2px 0 10px;
}
.service-card h3 {
  font-size: 17px;
  line-height: 1.35;
  margin: 0;
  color: var(--wine-dark);
  font-weight: 800;
  letter-spacing: -.02em;
}
.service-card .badge-new {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  border-radius: 5px;
  padding: 4px 7px;
  background: #e9f1fb;
  color: #275b93;
  font-size: 9px;
  letter-spacing: .08em;
  text-transform: uppercase;
  font-weight: 800;
}
.service-card .badge-new:before {
  content: "";
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: currentColor;
}
.service-card .description {
  font-size: 13px;
  line-height: 1.7;
  color: var(--muted);
  margin: 0 0 18px;
  max-width: 460px;
}
.service-card .card-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  margin-top: auto;
}
.service-card .action-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-height: 42px;
  padding: 0 18px;
  background: var(--wine);
  color: #fff;
  text-decoration: none;
  border: 1px solid var(--wine);
  border-radius: 8px;
  font-size: 12.5px;
  font-weight: 800;
  transition: background .2s, transform .2s;
}
.service-card .action-btn:hover {
  background: var(--wine-dark);
  color: #fff;
  transform: translateY(-1px);
}
.service-card .meta-text {
  font-size: 11px;
  line-height: 1.5;
  color: #8d8088;
  text-align: right;
}
.services-footer-note {
  margin-top: 20px;
  padding: 13px 18px;
  border: 1px solid #eee5e9;
  border-radius: 11px;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  align-items: flex-start;
  gap: 11px;
  color: #776d73;
  font-size: 11.5px;
  line-height: 1.65;
}
.services-footer-note .info-icon {
  width: 19px;
  height: 19px;
  flex: 0 0 19px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  background: #f1e5eb;
  color: var(--wine);
  font-size: 11px;
  font-weight: 800;
}

/* ═════════════════════════════════════════════════════════
   4. SECTION: GOVERNMENT SCHEMES (.scheme-section)
═════════════════════════════════════════════════════════ */
.scheme-section .header-center {
  text-align: center;
  max-width: 900px;
  margin: 0 auto 34px;
}
.scheme-section h2 {
  margin: 0;
  color: var(--wine);
  font-size: clamp(28px, 3.6vw, 38px);
  line-height: 1.2;
  font-weight: 800;
}
.scheme-section .rule-gold {
  width: 52px;
  height: 3px;
  margin: 10px auto 14px;
  background: var(--gold);
  border-radius: 3px;
}
.scheme-section .header-center p {
  margin: 0;
  color: #4B5563;
  font-size: 15px;
  line-height: 1.65;
}

.scheme-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 20px;
}
.scheme-card {
  display: flex;
  flex-direction: column;
  min-height: 320px;
  padding: 24px;
  border: 1px solid var(--line);
  border-top: 4px solid var(--wine);
  border-radius: 10px;
  background: var(--surface);
  box-shadow: 0 3px 12px rgba(32,33,36,.055);
  transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease;
}
.scheme-card:hover {
  transform: translateY(-3px);
  border-color: #CBA8B7;
  box-shadow: 0 10px 24px rgba(32,33,36,.09);
}
.scheme-icon-box {
  display: grid;
  place-items: center;
  width: 48px;
  height: 48px;
  margin-bottom: 16px;
  border: 1px solid #E5C8D5;
  border-radius: 8px;
  color: var(--wine);
  background: #FBF4F7;
}
.scheme-icon-box svg {
  width: 25px;
  height: 25px;
  fill: none;
  stroke: currentColor;
  stroke-width: 1.8;
  stroke-linecap: round;
  stroke-linejoin: round;
}
.scheme-eyebrow {
  margin-bottom: 6px;
  color: var(--wine);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .55px;
  text-transform: uppercase;
}
.scheme-card h3 {
  margin: 0 0 10px;
  font-size: 19px;
  line-height: 1.35;
  font-weight: 800;
  color: var(--text-dark);
}
.scheme-card p {
  margin: 0;
  color: #4B5563;
  font-size: 13px;
  line-height: 1.65;
}
.know-more-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: auto;
  padding-top: 18px;
  color: var(--wine);
  font-size: 13px;
  font-weight: 800;
  text-decoration: none;
  transition: gap .15s ease;
}
.know-more-btn span {
  font-size: 17px;
  transition: transform .15s ease;
}
.know-more-btn:hover {
  text-decoration: underline;
  gap: 12px;
}
.scheme-disclaimer {
  margin-top: 24px;
  padding: 13px 18px;
  border-left: 4px solid var(--gold);
  border-radius: 6px;
  background: #FFF9E8;
  color: #4A4A4A;
  font-size: 12px;
  line-height: 1.6;
}
.scheme-disclaimer strong {
  color: var(--wine);
  font-weight: 800;
}

/* ═════════════════════════════════════════════════════════
   5. SECTION: DOWNLOADS & MEDIA
═════════════════════════════════════════════════════════ */
.downloads-category-bar {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}
.downloads-category-btn {
  background: #fff;
  border: 1px solid rgba(116, 17, 60, 0.2);
  color: var(--ink);
  padding: 0.4rem 1.1rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
}
.downloads-category-btn.active,
.downloads-category-btn:hover {
  background: var(--wine);
  color: #fff;
  border-color: var(--wine);
}
.downloads-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}
.download-card {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 1.4rem;
  display: flex;
  flex-direction: column;
  transition: transform .2s, border-color .2s;
}
.download-card:hover {
  transform: translateY(-3px);
  border-color: var(--wine);
}
.download-card-icon {
  width: 100%;
  height: 80px;
  background: var(--wine);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 2rem;
  margin-bottom: 1rem;
}
.download-card h4 {
  font-size: 0.86rem;
  font-weight: 800;
  line-height: 1.4;
  color: var(--text-dark);
  margin-bottom: 0.6rem;
}
.download-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.72rem;
  color: var(--muted);
  border-top: 1px dashed var(--line);
  padding-top: 0.8rem;
  margin-top: auto;
}

/* ── RESPONSIVE MEDIA QUERIES ── */
@media (max-width: 1050px) {
  .market-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .scheme-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .downloads-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 950px) {
  .process-layout { grid-template-columns: 1fr; }
  .process-side { position: static; }
  .process-side ol { grid-template-columns: 1fr 1fr; }
  .step-content { padding-left: 18px; }
  .services-grid { grid-template-columns: 1fr; }
}
@media (max-width: 650px) {
  .market-grid { grid-template-columns: 1fr; }
  .scheme-grid { grid-template-columns: 1fr; }
  .downloads-grid { grid-template-columns: 1fr; }
  .step-cols, .checkgrid, .sourcegrid { grid-template-columns: 1fr; }
  .market-section .section-top { grid-template-columns: 1fr; gap: 14px; }
  .services-section .heading-row { flex-direction: column; align-items: flex-start; gap: 12px; }
  .process-step summary { grid-template-columns: 36px 1fr 14px; gap: 10px; }
  .step-time { grid-column: 2; justify-self: start; }
}
</style>
@endsection

@section('content')
@php
  // Fetch modular sections for the Resources page
  $heroSec = $page ? $page->sections->where('type', 'resources_hero')->where('is_active', true)->first() : null;
  $marketsSec = $page ? $page->sections->where('type', 'resources_export_markets')->where('is_active', true)->first() : null;
  $processSec = $page ? $page->sections->where('type', 'resources_export_process')->where('is_active', true)->first() : null;
  $servicesSec = $page ? $page->sections->where('type', 'resources_launched_services')->where('is_active', true)->first() : null;
  $schemesSec = $page ? $page->sections->where('type', 'resources_govt_schemes')->where('is_active', true)->first() : null;
  $downloadsSec = $page ? $page->sections->where('type', 'resources_downloads')->where('is_active', true)->first() : null;

  // 1. HERO DATA
  $heroTitle = $heroSec ? ($heroSec->content['title'] ?? 'Resource Center') : 'Resource Center';
  $heroSubtitle = $heroSec ? ($heroSec->content['subtitle'] ?? 'Access essential export guides, market intelligence reports, government schemes, and step-by-step export processes to accelerate your global textile trade.') : 'Access essential export guides, market intelligence reports, government schemes, and step-by-step export processes to accelerate your global textile trade.';

  // 2. EXPORT MARKETS DATA
  $mKicker = $marketsSec ? ($marketsSec->content['kicker'] ?? 'Market intelligence · International trade') : 'Market intelligence · International trade';
  $mHeading = $marketsSec ? ($marketsSec->content['heading'] ?? 'Explore your next export market.') : 'Explore your next export market.';
  $mIntro = $marketsSec ? ($marketsSec->content['intro'] ?? 'Start with a featured destination, then continue to Trade Connect for the official country guide and detailed market information.') : 'Start with a featured destination, then continue to Trade Connect for the official country guide and detailed market information.';
  $mViewAllText = $marketsSec ? ($marketsSec->content['view_all_text'] ?? 'Explore all country guides') : 'Explore all country guides';
  $mViewAllUrl = $marketsSec ? ($marketsSec->content['view_all_url'] ?? 'https://www.trade.gov.in/pages/country-guide') : 'https://www.trade.gov.in/pages/country-guide';
  $marketCards = $marketsSec ? ($marketsSec->content['cards'] ?? []) : [];
  $mBottomNote = $marketsSec ? ($marketsSec->content['bottom_note'] ?? 'TEFC and Trade Connect are collaborative initiatives. Country guidance is curated by the Directorate General of Foreign Trade (DGFT) and the Ministry of Commerce & Industry.') : 'TEFC and Trade Connect are collaborative initiatives. Country guidance is curated by the Directorate General of Foreign Trade (DGFT) and the Ministry of Commerce & Industry.';

  // 3. EXPORT PROCESS DATA
  $pEyebrow = $processSec ? ($processSec->content['eyebrow'] ?? 'Exporter learning guide') : 'Exporter learning guide';
  $pHeading = $processSec ? ($processSec->content['heading'] ?? 'Textile Export Process') : 'Textile Export Process';
  $pIntro = $processSec ? ($processSec->content['intro'] ?? 'A practical guide to exporting textile products from India — from selecting a product and finding a buyer to customs clearance, shipment and receipt of export payment.') : 'A practical guide to exporting textile products from India — from selecting a product and finding a buyer to customs clearance, shipment and receipt of export payment.';
  $pChips = $processSec ? ($processSec->content['chips'] ?? ['12 practical steps', 'Document checklists', 'Official resources', 'Indicative timelines']) : [];
  $pNotice = $processSec ? ($processSec->content['notice'] ?? 'Important: Timelines below are indicative planning estimates, not government-guaranteed service levels. Requirements vary by product/HS code, destination, buyer contract, transport mode and current notifications. Confirm current requirements with DGFT, Customs, your bank, the relevant textile Export Promotion Council and the buyer before shipment.') : '';
  $pSideHeading = $processSec ? ($processSec->content['sidebar_heading'] ?? 'Your export journey') : 'Your export journey';
  $pStepsHeading = $processSec ? ($processSec->content['steps_heading'] ?? '12 steps from order to payment') : '12 steps from order to payment';
  $pStepsIntro = $processSec ? ($processSec->content['steps_intro'] ?? 'Open each step for documents, actions and source links.') : 'Open each step for documents, actions and source links.';
  $processSteps = $processSec ? ($processSec->content['steps'] ?? []) : [];
  $checklistHeading = $processSec ? ($processSec->content['checklist_heading'] ?? 'Export readiness checklist') : 'Export readiness checklist';
  $checklistSub = $processSec ? ($processSec->content['checklist_sub'] ?? 'This checklist is for your own tracking. Checking a box does not submit a form to a government department.') : '';
  $checklistItems = $processSec ? ($processSec->content['checklist_items'] ?? []) : [];
  $sourcesHeading = $processSec ? ($processSec->content['sources_heading'] ?? 'Official portals and textile resources') : 'Official portals and textile resources';
  $sourcesSub = $processSec ? ($processSec->content['sources_sub'] ?? 'Use these as starting points and check the latest notification, product-specific rule and portal instructions. A general information page is not a substitute for current law.') : '';
  $sourceLinks = $processSec ? ($processSec->content['sources'] ?? []) : [];
  $pDisclaimer = $processSec ? ($processSec->content['disclaimer'] ?? 'This educational guide is not legal, tax, customs-broker, laboratory or banking advice. TEFC is an information and facilitation initiative; it does not issue export licences, certify textiles or conduct product testing.') : '';

  // 4. LAUNCHED SERVICES DATA
  $sEyebrow = $servicesSec ? ($servicesSec->content['eyebrow'] ?? 'TEFC digital initiatives') : 'TEFC digital initiatives';
  $sHeading = $servicesSec ? ($servicesSec->content['heading'] ?? 'Recently Launched Services') : 'Recently Launched Services';
  $sIntro = $servicesSec ? ($servicesSec->content['intro'] ?? 'Discover digital tools designed to help textile exporters find relevant trade information and navigate export-related queries.') : '';
  $launchedServices = $servicesSec ? ($servicesSec->content['services'] ?? []) : [];
  $sFooterNote = $servicesSec ? ($servicesSec->content['footer_note'] ?? 'These services provide information and facilitation support. HS code suggestions, duty details and AI-generated responses should be independently verified against the relevant official sources before making export or compliance decisions.') : '';

  // 5. GOVERNMENT SCHEMES DATA
  $schHeading = $schemesSec ? ($schemesSec->content['heading'] ?? 'Government Schemes') : 'Government Schemes';
  $schIntro = $schemesSec ? ($schemesSec->content['intro'] ?? 'Explore Government of India schemes and programmes that support textile manufacturing, export competitiveness, market access and export-enabling infrastructure.') : '';
  $govtSchemes = $schemesSec ? ($schemesSec->content['schemes'] ?? []) : [];
  $schDisclaimer = $schemesSec ? ($schemesSec->content['disclaimer'] ?? 'TEFC role: TEFC provides information and facilitation support. Scheme approval, eligibility determination, sanction and disbursement remain with the competent Government authority and implementing agency.') : '';

  // 6. DOWNLOADS DATA
  $dHeading = $downloadsSec ? ($downloadsSec->content['heading'] ?? 'Downloads & Official Documents') : 'Downloads & Official Documents';
  $dIntro = $downloadsSec ? ($downloadsSec->content['intro'] ?? 'Download official policy documents, export compliance guidelines, circulars, and standard operating procedures directly.') : '';
@endphp

<div class="resources-page-container">

  <!-- ── HERO BANNER ── -->
  @if($heroSec)
  <div class="resources-hero" style="background-image: linear-gradient(135deg, rgba(40, 5, 22, 0.72) 0%, rgba(108, 19, 64, 0.65) 100%), url('{{ asset(!empty($heroSec->content['background_image']) ? $heroSec->content['background_image'] : 'images/page-hero-bg.jpg') }}');">
    <div class="container resources-hero-inner">
      <div class="resources-breadcrumbs">
        <a href="{{ url('/') }}">Home</a> 
        <i class="fas fa-chevron-right" style="font-size:0.65rem; opacity:0.6;"></i> 
        <span>Resources</span>
      </div>
      <h1>{{ $heroTitle }}</h1>
      <p>{{ $heroSubtitle }}</p>
    </div>
  </div>
  @endif

  <!-- ── STICKY TAB NAV BAR ── -->
  <div class="resources-filter-strip">
    <div class="container resources-tabs">
      @if($marketsSec)
        <button class="resources-tab active" onclick="scrollToSection('markets', this)"><i class="fas fa-globe-americas me-1"></i> Export Markets</button>
      @endif
      @if($processSec)
        <button class="resources-tab {{ !$marketsSec ? 'active' : '' }}" onclick="scrollToSection('process', this)"><i class="fas fa-tasks me-1"></i> Export Process</button>
      @endif
      @if($servicesSec)
        <button class="resources-tab {{ (!$marketsSec && !$processSec) ? 'active' : '' }}" onclick="scrollToSection('services', this)"><i class="fas fa-rocket me-1"></i> Launched Services</button>
      @endif
      @if($schemesSec)
        <button class="resources-tab {{ (!$marketsSec && !$processSec && !$servicesSec) ? 'active' : '' }}" onclick="scrollToSection('schemes', this)"><i class="fas fa-landmark me-1"></i> Government Schemes</button>
      @endif
      @if($downloadsSec)
        <button class="resources-tab {{ (!$marketsSec && !$processSec && !$servicesSec && !$schemesSec) ? 'active' : '' }}" onclick="scrollToSection('downloads', this)"><i class="fas fa-download me-1"></i> Downloads</button>
      @endif
    </div>
  </div>

  <!-- ═════════════════════════════════════════════════════════
       1. SECTION: EXPLORE EXPORT MARKETS
  ══════════════════════════════════════════════════════════ -->
  @if($marketsSec)
  <section class="res-section-pad market-section" id="markets" aria-labelledby="market-heading">
    <div class="container">
      <div class="section-top">
        <div>
          <div class="kicker">{{ $mKicker }}</div>
          <h2 id="market-heading">{{ $mHeading }}</h2>
          <p class="intro">{{ $mIntro }}</p>
        </div>
        @if(!empty($mViewAllUrl))
        <a class="view-all" href="{{ $mViewAllUrl }}" target="_blank" rel="noopener noreferrer">
          {{ $mViewAllText }} <span aria-hidden="true">↗</span>
        </a>
        @endif
      </div>

      <div class="market-grid">
        @foreach($marketCards as $cIdx => $c)
          <a class="market-card" href="{{ $c['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="Know more about {{ $c['name'] ?? '' }} on Trade Connect">
            <div class="visual {{ $c['style_class'] ?? 'us' }}">
              <span class="visual-label">{{ $c['label'] ?? 'DESTINATION' }}</span>
              <span class="visual-index">{{ $c['index'] ?? sprintf('%02d', $cIdx + 1) }}</span>
              <span class="country-code">{{ $c['code'] ?? 'IN' }}</span>
            </div>
            <div class="card-content">
              <span class="region">{{ $c['region'] ?? 'INTERNATIONAL' }}</span>
              <h3>{{ $c['name'] ?? 'Country Name' }}</h3>
              <p>{{ $c['description'] ?? '' }}</p>
              <div class="card-bottom">
                <span>{{ $c['button_text'] ?? 'Know more' }}</span>
                <span class="circle-arrow" aria-hidden="true">↗</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      @if(!empty($mBottomNote))
      <div class="bottom-note">
        <span class="note-icon" aria-hidden="true">i</span>
        <p>{{ $mBottomNote }}</p>
      </div>
      @endif
    </div>
  </section>
  @endif

  <!-- ═════════════════════════════════════════════════════════
       2. SECTION: TEXTILE EXPORT PROCESS (12 STEPS)
  ══════════════════════════════════════════════════════════ -->
  @if($processSec)
  <section class="res-section-pad res-section-alt" id="process">
    <div class="container">
      <!-- Process Hero Card -->
      <div class="process-hero">
        <div class="process-eyebrow">{{ $pEyebrow }}</div>
        <h2>{{ $pHeading }}</h2>
        <p>{{ $pIntro }}</p>
        @if(count($pChips) > 0)
        <div class="process-chips">
          @foreach($pChips as $chip)
            <span>{{ $chip }}</span>
          @endforeach
        </div>
        @endif
      </div>

      <!-- Process Notice Box -->
      @if(!empty($pNotice))
      <div class="process-notice">
        <strong>Important:</strong> {{ $pNotice }}
      </div>
      @endif

      <!-- Split Layout with Sticky Sidebar and 12 Steps -->
      <div class="process-layout">
        <!-- Sidebar Navigation & Checklist Progress Tracker -->
        <aside class="process-side">
          <h3>{{ $pSideHeading }}</h3>
          <div class="process-meter">
            <span id="progressText">0 of {{ count($checklistItems) > 0 ? count($checklistItems) : 12 }} checked</span>
            <span id="pct">0%</span>
          </div>
          <div class="process-track" role="progressbar" aria-label="Checklist completion" aria-valuemin="0" aria-valuemax="{{ count($checklistItems) > 0 ? count($checklistItems) : 12 }}" aria-valuenow="0" id="progressTrack">
            <span id="progressBar"></span>
          </div>
          <ol id="navsteps">
            @foreach($processSteps as $stIdx => $st)
              <li>
                <a href="#s{{ $stIdx + 1 }}" class="{{ $stIdx === 0 ? 'active' : '' }}">
                  <b>{{ $st['num'] ?? sprintf('%02d', $stIdx + 1) }}</b>
                  <span>{{ Str::limit($st['title'] ?? '', 24) }}</span>
                </a>
              </li>
            @endforeach
          </ol>
        </aside>

        <!-- Right Content: 12 Collapsible Steps -->
        <div>
          <div class="process-mainhead">
            <div>
              <h3>{{ $pStepsHeading }}</h3>
              <p>{{ $pStepsIntro }}</p>
            </div>
            <button class="process-toggle-btn" id="toggleAllSteps" type="button">Expand all</button>
          </div>

          <!-- 12 Steps List -->
          @foreach($processSteps as $stIdx => $st)
            <details class="process-step" id="s{{ $stIdx + 1 }}" {{ $stIdx === 0 ? 'open' : '' }}>
              <summary>
                <span class="step-num">{{ $st['num'] ?? sprintf('%02d', $stIdx + 1) }}</span>
                <div>
                  <span class="step-stitle">{{ $st['title'] ?? 'Step Title' }}</span>
                  <span class="step-sub">{{ $st['sub'] ?? '' }}</span>
                </div>
                <span class="step-time">{{ $st['time'] ?? '' }}</span>
                <span class="step-chev" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
              </summary>
              <div class="step-content">
                <p>{{ $st['description'] ?? '' }}</p>

                @if((isset($st['checks']) && count($st['checks']) > 0) || (isset($st['records']) && count($st['records']) > 0))
                <div class="step-cols">
                  @if(isset($st['checks']) && count($st['checks']) > 0)
                  <div class="step-panel">
                    <h4><i class="fas fa-check-circle text-primary me-1"></i> Textile-specific checks</h4>
                    <ul>
                      @foreach($st['checks'] as $chk)
                        <li>{{ $chk }}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif

                  @if(isset($st['records']) && count($st['records']) > 0)
                  <div class="step-panel">
                    <h4><i class="fas fa-file-alt text-success me-1"></i> Prepare these records</h4>
                    <ul>
                      @foreach($st['records'] as $rec)
                        <li>{{ $rec }}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                </div>
                @endif

                @if(isset($st['actions']) && count($st['actions']) > 0)
                  <h4><i class="fas fa-bolt text-warning me-1"></i> Actions</h4>
                  <ul>
                    @foreach($st['actions'] as $act)
                      <li>{{ $act }}</li>
                    @endforeach
                  </ul>
                @endif

                @if(isset($st['links']) && count($st['links']) > 0)
                  <div class="step-links">
                    @foreach($st['links'] as $lnk)
                      <a href="{{ $lnk['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $lnk['title'] ?? 'Official Portal' }}</a>
                    @endforeach
                  </div>
                @endif

                @if(!empty($st['footnote']))
                  <p class="step-footnote">{{ $st['footnote'] }}</p>
                @endif
              </div>
            </details>
          @endforeach

          <!-- Export Readiness Checklist -->
          @if(count($checklistItems) > 0)
          <div class="process-final">
            <h3><i class="fas fa-clipboard-check me-2 text-primary"></i> {{ $checklistHeading }}</h3>
            @if(!empty($checklistSub))
              <p class="text-muted small mb-3">{{ $checklistSub }}</p>
            @endif
            <div class="checkgrid">
              @foreach($checklistItems as $cIndex => $cItem)
                <label class="check-item">
                  <input type="checkbox" class="step-checkbox" id="chk_{{ $cIndex }}">
                  <span>{{ $cItem }}</span>
                </label>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Official Portals and Textile Resources Grid -->
          @if(count($sourceLinks) > 0)
          <div class="process-sources">
            <h3><i class="fas fa-external-link-alt me-2 text-primary"></i> {{ $sourcesHeading }}</h3>
            @if(!empty($sourcesSub))
              <p class="text-muted small mb-3">{{ $sourcesSub }}</p>
            @endif
            <div class="sourcegrid">
              @foreach($sourceLinks as $src)
                <a href="{{ $src['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                  <strong>{{ $src['title'] ?? '' }} ↗</strong>
                  <span>{{ $src['desc'] ?? '' }}</span>
                </a>
              @endforeach
            </div>
            @if(!empty($pDisclaimer))
              <p class="text-muted small mt-3 pt-2 border-top mb-0">{{ $pDisclaimer }}</p>
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ═════════════════════════════════════════════════════════
       3. SECTION: RECENTLY LAUNCHED SERVICES
  ══════════════════════════════════════════════════════════ -->
  @if($servicesSec)
  <section class="res-section-pad services-section" id="services" aria-labelledby="services-title">
    <div class="container">
      <div class="eyebrow">
        <span class="eyebrow-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2.5 14.9 8.4l6.5.9-4.7 4.6 1.1 6.5L12 17.3l-5.8 3.1 1.1-6.5-4.7-4.6 6.5-.9L12 2.5Z"/></svg>
        </span>
        {{ $sEyebrow }}
      </div>

      <div class="heading-row">
        <h2 id="services-title">{{ $sHeading }}</h2>
        @if(!empty($sIntro))
          <p class="heading-copy">{{ $sIntro }}</p>
        @endif
      </div>

      <div class="gold-rule" aria-hidden="true"></div>

      <div class="services-grid">
        @foreach($launchedServices as $srv)
          <article class="service-card">
            <div class="icon-box {{ $srv['color'] ?? 'blue' }}" aria-hidden="true">
              @if(($srv['icon'] ?? '') == 'ai_assistant' || ($srv['color'] ?? '') == 'gold')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 5.5h10a4 4 0 0 1 0 8H9l-4.5 3v-3.2A4 4 0 0 1 4 5.5Z"/>
                  <path d="M14.5 9.5h1.8a4 4 0 0 1 3.7 5.5v2.8l-3.5-2.3h-2"/>
                </svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 3v18M8.5 5.2a3 3 0 0 0-2.2 5.1 3.5 3.5 0 0 0 .2 6.6A3 3 0 0 0 12 19V5a3 3 0 0 0-3.5.2ZM15.5 5.2a3 3 0 0 1 2.2 5.1 3.5 3.5 0 0 1-.2 6.6A3 3 0 0 1 12 19V5a3 3 0 0 1 3.5.2Z"/>
                </svg>
              @endif
            </div>

            <div class="card-content">
              <div class="card-title-row">
                <h3>{{ $srv['title'] ?? 'Digital Service' }}</h3>
                @if(!empty($srv['badge']))
                  <span class="badge-new">{{ $srv['badge'] }}</span>
                @endif
              </div>
              <p class="description">{{ $srv['description'] ?? '' }}</p>
              <div class="card-bottom">
                <a class="action-btn" href="{{ $srv['action_url'] ?? '#' }}" aria-label="{{ $srv['action_text'] ?? 'Try the tool' }}">
                  {{ $srv['action_text'] ?? 'Try the tool' }} <span aria-hidden="true">→</span>
                </a>
                @if(!empty($srv['meta']))
                  <span class="meta-text">{{ $srv['meta'] }}</span>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>

      @if(!empty($sFooterNote))
      <div class="services-footer-note">
        <span class="info-icon" aria-hidden="true">i</span>
        <span>{{ $sFooterNote }}</span>
      </div>
      @endif
    </div>
  </section>
  @endif

  <!-- ═════════════════════════════════════════════════════════
       4. SECTION: GOVERNMENT SCHEMES
  ══════════════════════════════════════════════════════════ -->
  @if($schemesSec)
  <section class="res-section-pad res-section-alt scheme-section" id="schemes" aria-labelledby="schemes-heading">
    <div class="container">
      <div class="header-center">
        <h2 id="schemes-heading">{{ $schHeading }}</h2>
        <div class="rule-gold" aria-hidden="true"></div>
        @if(!empty($schIntro))
          <p>{{ $schIntro }}</p>
        @endif
      </div>

      <div class="scheme-grid">
        @foreach($govtSchemes as $sch)
          <article class="scheme-card">
            <div class="scheme-icon-box" aria-hidden="true">
              @if(($sch['icon'] ?? '') == 'rodtep')
                <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3z"/><path d="M9 8h6M9 12h6M9 16h3"/></svg>
              @elseif(($sch['icon'] ?? '') == 'rosctl')
                <svg viewBox="0 0 24 24"><path d="M9 4l3 2 3-2 5 3-2 4-3-1v10H9V10l-3 1-2-4 5-3z"/></svg>
              @elseif(($sch['icon'] ?? '') == 'pli')
                <svg viewBox="0 0 24 24"><path d="M3 21V9l7 4V9l6 4V6h5v15H3z"/><path d="M7 17h2M12 17h2M17 17h2"/></svg>
              @elseif(($sch['icon'] ?? '') == 'pm_mitra')
                <svg viewBox="0 0 24 24"><path d="M12 21V8"/><path d="M8 12c-3 0-5-2-5-5 3 0 6 1 7 4"/><path d="M16 12c3 0 5-2 5-5-3 0-6 1-7 4"/><path d="M7 17c-2 0-4-1-5-3 3-1 6 0 7 2"/><path d="M17 17c2 0 4-1 5-3-3-1-6 0-7 2"/></svg>
              @elseif(($sch['icon'] ?? '') == 'ties')
                <svg viewBox="0 0 24 24"><path d="M4 21V9l8-6 8 6v12"/><path d="M8 21v-6h8v6M8 10h2M14 10h2"/></svg>
              @else
                <svg viewBox="0 0 24 24"><path d="M4 16l6-6 4 4 6-7"/><path d="M16 7h4v4"/></svg>
              @endif
            </div>

            <span class="scheme-eyebrow">{{ $sch['eyebrow'] ?? 'SCHEME' }}</span>
            <h3>{{ $sch['title'] ?? '' }}</h3>
            <p>{{ $sch['description'] ?? '' }}</p>

            <a class="know-more-btn" href="{{ $sch['link_url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
              {{ $sch['link_text'] ?? 'Know More' }} <span aria-hidden="true">→</span>
            </a>
          </article>
        @endforeach
      </div>

      @if(!empty($schDisclaimer))
      <div class="scheme-disclaimer" role="note">
        <strong>TEFC role:</strong> {{ $schDisclaimer }}
      </div>
      @endif
    </div>
  </section>
  @endif

  <!-- ═════════════════════════════════════════════════════════
       5. SECTION: DOWNLOADS & OFFICIAL DOCUMENTS
  ══════════════════════════════════════════════════════════ -->
  @if($downloadsSec)
  <section class="res-section-pad" id="downloads">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: var(--wine);"><i class="fas fa-file-download me-2"></i> {{ $dHeading }}</h2>
        <p class="text-muted" style="max-width: 700px; margin: 0 auto;">{{ $dIntro }}</p>
      </div>

      <!-- Categories Filter Bar -->
      @if(isset($categories) && count($categories) > 0)
      <div class="downloads-category-bar">
        <button class="downloads-category-btn active" onclick="filterDownloads('all', this)">All Documents</button>
        @foreach($categories as $cat)
          <button class="downloads-category-btn" onclick="filterDownloads('{{ Str::slug($cat->category) }}', this)">{{ $cat->category }}</button>
        @endforeach
      </div>
      @endif

      <!-- Downloads Grid -->
      <div class="downloads-grid">
        @if(isset($resources) && count($resources) > 0)
          @foreach($resources as $res)
            <div class="download-card doc-item" data-category="{{ Str::slug($res->category) }}">
              <div class="download-card-icon">
                @if(in_array(strtolower($res->file_type), ['pdf']))
                  <i class="fas fa-file-pdf"></i>
                @elseif(in_array(strtolower($res->file_type), ['xls', 'xlsx', 'csv']))
                  <i class="fas fa-file-excel"></i>
                @elseif(in_array(strtolower($res->file_type), ['doc', 'docx']))
                  <i class="fas fa-file-word"></i>
                @else
                  <i class="fas fa-file-alt"></i>
                @endif
              </div>
              <h4>{{ $res->title }}</h4>
              <div class="download-card-meta">
                <span>{{ strtoupper($res->file_type) }} &bull; {{ $res->file_size ? number_format($res->file_size / 1024, 1) . ' KB' : 'PDF' }}</span>
                <a href="{{ asset($res->file_path) }}" class="fw-bold text-decoration-none" style="color: var(--wine);" download>Download <i class="fas fa-arrow-down ms-1"></i></a>
              </div>
            </div>
          @endforeach
        @else
          <!-- Default Fallback Official Documents if none uploaded -->
          <div class="download-card">
            <div class="download-card-icon"><i class="fas fa-file-pdf"></i></div>
            <h4>Foreign Trade Policy 2023 Handbook</h4>
            <div class="download-card-meta">
              <span>PDF &bull; Official</span>
              <a href="https://content.dgft.gov.in/Website/dgftprod/61d61bc2-272e-4880-b96c-c8f685a3b244/Foreign%20Trade%20Policy%202023.pdf" target="_blank" class="fw-bold text-decoration-none" style="color: var(--wine);">Download <i class="fas fa-arrow-down ms-1"></i></a>
            </div>
          </div>
          <div class="download-card">
            <div class="download-card-icon"><i class="fas fa-file-pdf"></i></div>
            <h4>RoDTEP Textile Remission Schedule</h4>
            <div class="download-card-meta">
              <span>PDF &bull; Schedule</span>
              <a href="https://www.dgft.gov.in/" target="_blank" class="fw-bold text-decoration-none" style="color: var(--wine);">Download <i class="fas fa-arrow-down ms-1"></i></a>
            </div>
          </div>
          <div class="download-card">
            <div class="download-card-icon"><i class="fas fa-file-pdf"></i></div>
            <h4>eCoO 2.0 User Manual & Guidelines</h4>
            <div class="download-card-meta">
              <span>PDF &bull; Portal SOP</span>
              <a href="https://coo.dgft.gov.in/" target="_blank" class="fw-bold text-decoration-none" style="color: var(--wine);">Download <i class="fas fa-arrow-down ms-1"></i></a>
            </div>
          </div>
          <div class="download-card">
            <div class="download-card-icon"><i class="fas fa-file-pdf"></i></div>
            <h4>Standard Operating Procedure: Export Documentation</h4>
            <div class="download-card-meta">
              <span>PDF &bull; TEFC SOP</span>
              <a href="#" class="fw-bold text-decoration-none" style="color: var(--wine);">Download <i class="fas fa-arrow-down ms-1"></i></a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
  @endif

</div>
@endsection

@section('scripts')
<script>
// ── SMOOTH SCROLL TO SECTION ──
function scrollToSection(sectionId, btn) {
  const el = document.getElementById(sectionId);
  if (el) {
    const navHeight = 65;
    const y = el.getBoundingClientRect().top + window.pageYOffset - navHeight;
    window.scrollTo({ top: y, behavior: 'smooth' });

    document.querySelectorAll('.resources-tab').forEach(t => t.classList.remove('active'));
    if (btn) btn.classList.add('active');
  }
}

// ── EXPORT PROCESS SCRIPT LOGIC ──
document.addEventListener('DOMContentLoaded', () => {
  const stepElements = [...document.querySelectorAll('.process-step')];
  const sideNavLinks = [...document.querySelectorAll('#navsteps a')];
  const checkBoxes = [...document.querySelectorAll('.step-checkbox')];
  const progressBar = document.querySelector('#progressBar');
  const progressTrack = document.querySelector('#progressTrack');
  const progressText = document.querySelector('#progressText');
  const pctText = document.querySelector('#pct');
  const toggleBtn = document.querySelector('#toggleAllSteps');

  // Update Progress Tracker
  function updateProgress() {
    if (!checkBoxes.length) return;
    const checkedCount = checkBoxes.filter(c => c.checked).length;
    const totalCount = checkBoxes.length;
    const pct = Math.round((checkedCount / totalCount) * 100);

    if (progressText) progressText.textContent = `${checkedCount} of ${totalCount} checked`;
    if (pctText) pctText.textContent = `${pct}%`;
    if (progressBar) progressBar.style.width = `${pct}%`;
    if (progressTrack) progressTrack.setAttribute('aria-valuenow', checkedCount);
  }

  checkBoxes.forEach(chk => chk.addEventListener('change', updateProgress));

  // Toggle All Steps
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const anyClosed = stepElements.some(s => !s.open);
      stepElements.forEach(s => s.open = anyClosed);
      toggleBtn.textContent = anyClosed ? 'Collapse all' : 'Expand all';
    });
  }

  // Sidebar Link Click Opens Step
  sideNavLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      if (targetId && targetId.startsWith('#')) {
        const stepTarget = document.querySelector(targetId);
        if (stepTarget) {
          stepTarget.open = true;
        }
      }
    });
  });

  // Intersection Observer for Sidebar Active Highlight
  if (stepElements.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const currentId = '#' + entry.target.id;
          sideNavLinks.forEach(a => {
            a.classList.toggle('active', a.getAttribute('href') === currentId);
          });
        }
      });
    }, { rootMargin: '-20% 0px -60% 0px' });

    stepElements.forEach(s => observer.observe(s));
  }
});

// ── DOWNLOADS CATEGORY FILTER ──
function filterDownloads(categorySlug, btn) {
  document.querySelectorAll('.downloads-category-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');

  const items = document.querySelectorAll('.doc-item');
  items.forEach(item => {
    if (categorySlug === 'all' || item.getAttribute('data-category') === categorySlug) {
      item.style.display = 'flex';
    } else {
      item.style.display = 'none';
    }
  });
}
</script>
@endsection
