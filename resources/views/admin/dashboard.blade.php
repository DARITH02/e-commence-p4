@extends('layouts.admin')

@section('title', __('admin.dashboard'))
@section('page_title', __('admin.dashboard'))

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS
═══════════════════════════════════════════ */
:root {
    --surface-0:  #0b0d12;
    --surface-1:  #13151d;
    --surface-2:  #191c27;
    --surface-3:  #1f2233;
    --border-1:   rgba(255,255,255,0.06);
    --border-2:   rgba(255,255,255,0.11);
    --text-1:     #eceef4;
    --text-2:     #9aa0b8;
    --text-3:     #545b74;
    --blue:       #4f72f5;
    --blue-dim:   rgba(79,114,245,0.12);
    --blue-mid:   rgba(79,114,245,0.22);
    --green:      #1fba72;
    --green-dim:  rgba(31,186,114,0.1);
    --amber:      #e8a000;
    --amber-dim:  rgba(232,160,0,0.1);
    --red:        #e84545;
    --red-dim:    rgba(232,69,69,0.1);
    --violet:     #8b5cf6;
    --violet-dim: rgba(139,92,246,0.1);
    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  16px;
    --radius-xl:  20px;
    --shadow-card: 0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
    --shadow-hover:0 4px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(79,114,245,0.25);
    --transition: 0.18s cubic-bezier(0.4,0,0.2,1);
}

html.light {
    --surface-0:  #f0f2f7;
    --surface-1:  #ffffff;
    --surface-2:  #f7f8fc;
    --surface-3:  #eef0f6;
    --border-1:   rgba(0,0,0,0.07);
    --border-2:   rgba(0,0,0,0.13);
    --text-1:     #0f1117;
    --text-2:     #4a5068;
    --text-3:     #9aa0b8;
    --blue:       #2d52e0;
    --blue-dim:   rgba(45,82,224,0.08);
    --blue-mid:   rgba(45,82,224,0.16);
    --green:      #059669;
    --green-dim:  rgba(5,150,105,0.08);
    --amber:      #b45309;
    --amber-dim:  rgba(180,83,9,0.08);
    --red:        #c92b2b;
    --red-dim:    rgba(201,43,43,0.08);
    --violet:     #6d28d9;
    --violet-dim: rgba(109,40,217,0.08);
    --shadow-card: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
    --shadow-hover:0 4px 24px rgba(0,0,0,0.12), 0 0 0 1px rgba(45,82,224,0.2);
}

body { background: var(--surface-0) !important; }

/* ═══════════════════════════════════════════
   PAGE LAYOUT
═══════════════════════════════════════════ */
.content-inner { max-width: none !important; }

.dash {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 296px;
    grid-template-rows: auto auto auto auto;
    gap: 20px;
    animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Stack to single column below 1200px */
@media (max-width: 1199px) {
    .dash { grid-template-columns: 1fr; }
    .dash-sidebar { display: contents; }
}

/* ── Top greeting bar ── */
.dash-topbar {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.dash-greeting h2 {
    font-size: 21px;
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.02em;
}

.dash-greeting p {
    font-family: 'DM Mono', monospace;
    font-size: 10.5px;
    color: var(--text-3);
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.03em;
}

.live-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--green);
    flex-shrink: 0;
    box-shadow: 0 0 0 0 var(--green);
    animation: livePulse 2.2s ease infinite;
}
@keyframes livePulse {
    0%   { box-shadow: 0 0 0 0 rgba(31,186,114,0.5); }
    70%  { box-shadow: 0 0 0 6px rgba(31,186,114,0); }
    100% { box-shadow: 0 0 0 0 rgba(31,186,114,0); }
}

.dash-controls { display: flex; align-items: center; gap: 8px; }

/* Period picker */
.period-group {
    display: flex;
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-md);
    padding: 3px;
    gap: 2px;
}
.period-btn {
    padding: 6px 14px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-3);
    border: none;
    background: transparent;
    cursor: pointer;
    transition: background var(--transition), color var(--transition);
    font-family: 'DM Sans', sans-serif;
    white-space: nowrap;
}
.period-btn:hover { color: var(--text-1); }
.period-btn.active {
    background: var(--surface-1);
    color: var(--text-1);
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
    border: 1px solid var(--border-1);
}

/* Theme toggle */
.theme-btn {
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--text-2);
    transition: background var(--transition), color var(--transition);
}
.theme-btn:hover { background: var(--surface-3); color: var(--text-1); }
.theme-btn svg { width: 15px; height: 15px; }
html.light       .icon-moon { display: none; }
html:not(.light) .icon-sun  { display: none; }

/* ═══════════════════════════════════════════
   KPI CARDS
═══════════════════════════════════════════ */
.kpi-row {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
@media (max-width: 900px)  { .kpi-row { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 520px)  { .kpi-row { grid-template-columns: 1fr; } }

.kpi {
    background: var(--surface-1);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-lg);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    box-shadow: var(--shadow-card);
    transition: box-shadow var(--transition), border-color var(--transition), transform var(--transition);
    position: relative;
    overflow: hidden;
}
.kpi:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--blue);
    transform: translateY(-2px);
}
/* Subtle color wash in top-right corner */
.kpi::after {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--icon-clr, var(--blue-dim)) 0%, transparent 70%);
    pointer-events: none;
    transition: opacity var(--transition);
    opacity: 0.7;
}
.kpi:hover::after { opacity: 1; }

.kpi-head { display: flex; align-items: center; justify-content: space-between; }

.kpi-ico {
    width: 36px; height: 36px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: transform var(--transition);
}
.kpi:hover .kpi-ico { transform: scale(1.08); }
.kpi-ico svg { width: 16px; height: 16px; }

.kpi-ico.c-blue   { background: var(--blue-dim);   color: var(--blue);   --icon-clr: var(--blue-dim); }
.kpi-ico.c-green  { background: var(--green-dim);  color: var(--green);  --icon-clr: var(--green-dim); }
.kpi-ico.c-amber  { background: var(--amber-dim);  color: var(--amber);  --icon-clr: var(--amber-dim); }
.kpi-ico.c-violet { background: var(--violet-dim); color: var(--violet); --icon-clr: var(--violet-dim); }

.kpi-chip {
    font-family: 'DM Mono', monospace;
    font-size: 10.5px; font-weight: 600;
    padding: 3px 8px; border-radius: 100px;
    display: inline-flex; align-items: center; gap: 3px;
}
.kpi-chip svg { width: 10px; height: 10px; flex-shrink: 0; }
.chip-up   { background: var(--green-dim); color: var(--green); }
.chip-down { background: var(--red-dim);   color: var(--red);   }

.kpi-body { display: flex; flex-direction: column; gap: 3px; }
.kpi-label {
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 0.08em;
    text-transform: uppercase; color: var(--text-3);
}
.kpi-val {
    font-size: 24px; font-weight: 700;
    color: var(--text-1); letter-spacing: -0.025em; line-height: 1;
}
.kpi-sub { font-size: 11px; color: var(--text-2); }

/* Sparkline */
.kpi-spark { height: 36px; width: 100%; }

/* ═══════════════════════════════════════════
   CARD SHELL
═══════════════════════════════════════════ */
.card {
    background: var(--surface-1);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    transition: border-color var(--transition);
}
.card:hover { border-color: var(--border-2); }

.card-head {
    padding: 18px 20px 0;
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
}
.card-title {
    font-size: 14px; font-weight: 700;
    color: var(--text-1); letter-spacing: -0.01em;
    display: flex; align-items: center; gap: 8px;
}
.card-sub {
    font-family: 'DM Mono', monospace;
    font-size: 10px; color: var(--text-3);
    margin-top: 2px; letter-spacing: 0.05em;
}

/* Live indicator */
.live-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-family: 'DM Mono', monospace;
    font-size: 9px; font-weight: 600; letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--green);
    background: var(--green-dim);
    border: 1px solid rgba(31,186,114,0.18);
    padding: 3px 8px; border-radius: 100px;
}
.live-badge::before {
    content: '';
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--green);
    animation: livePulse 2s infinite;
}

/* Card link */
.card-link {
    font-size: 11.5px; font-weight: 600;
    color: var(--blue); text-decoration: none;
    padding: 5px 10px; border-radius: var(--radius-sm);
    background: var(--blue-dim);
    border: 1px solid rgba(79,114,245,0.18);
    transition: background var(--transition);
    white-space: nowrap;
}
.card-link:hover { background: var(--blue-mid); }

/* ═══════════════════════════════════════════
   CHART CARD
═══════════════════════════════════════════ */
.chart-card {
    grid-column: 1;
    grid-row: 3;
}
.chart-wrap { padding: 0 20px 20px; }
.chart-wrap canvas { display: block; }

.chart-legend {
    display: flex; align-items: center; gap: 16px;
}
.legend-item {
    display: flex; align-items: center; gap: 5px;
    font-family: 'DM Mono', monospace; font-size: 10px;
    color: var(--text-3);
}
.legend-swatch { width: 20px; height: 3px; border-radius: 2px; }
.legend-dashed  { background: repeating-linear-gradient(90deg, var(--text-3) 0px, var(--text-3) 4px, transparent 4px, transparent 8px); }

/* ═══════════════════════════════════════════
   ORDERS TABLE
═══════════════════════════════════════════ */
.orders-card {
    grid-column: 1;
    grid-row: 4;
}
.table-scroll { overflow-x: auto; }

table { width: 100%; border-collapse: collapse; }

thead th {
    padding: 9px 20px;
    font-family: 'DM Mono', monospace;
    font-size: 9.5px; text-transform: uppercase;
    letter-spacing: 0.1em; color: var(--text-3);
    font-weight: 600; background: var(--surface-2);
    text-align: left; white-space: nowrap;
}
thead th:last-child { text-align: right; }

tbody tr {
    border-top: 1px solid var(--border-1);
    transition: background var(--transition);
    cursor: pointer;
}
tbody tr:hover { background: var(--surface-2); }

tbody td { padding: 12px 20px; vertical-align: middle; }

.oid {
    font-family: 'DM Mono', monospace;
    font-size: 11.5px; font-weight: 600;
    color: var(--blue);
    background: var(--blue-dim);
    padding: 3px 8px; border-radius: 6px;
    white-space: nowrap;
}

.cust-row { display: flex; align-items: center; gap: 10px; }
.cust-av {
    width: 30px; height: 30px; border-radius: 8px;
    object-fit: cover;
    border: 1px solid var(--border-2);
    flex-shrink: 0;
    background: var(--surface-3);
}
.cust-nm { font-size: 13px; font-weight: 600; color: var(--text-1); line-height: 1; }
.cust-em { font-size: 10.5px; color: var(--text-3); margin-top: 2px; }

.s-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 100px;
    font-size: 11px; font-weight: 600;
    border: 1px solid transparent; white-space: nowrap;
}
.s-pill::before {
    content: ''; width: 5px; height: 5px;
    border-radius: 50%; background: currentColor;
    flex-shrink: 0;
}
.s-pending    { background: var(--amber-dim);  color: var(--amber);  border-color: rgba(232,160,0,0.2); }
.s-processing { background: var(--blue-dim);   color: var(--blue);   border-color: rgba(79,114,245,0.2); }
.s-shipped    { background: var(--violet-dim); color: var(--violet); border-color: rgba(139,92,246,0.2); }
.s-completed  { background: var(--green-dim);  color: var(--green);  border-color: rgba(31,186,114,0.2); }
.s-cancelled  { background: var(--red-dim);    color: var(--red);    border-color: rgba(232,69,69,0.2); }

.order-ts  { font-size: 11.5px; color: var(--text-2); white-space: nowrap; }
.order-ts2 { font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3); margin-top: 2px; }
.order-amt { font-size: 14px; font-weight: 700; color: var(--text-1); text-align: right; display: block; white-space: nowrap; }

/* ═══════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════ */
.dash-sidebar {
    grid-column: 2;
    grid-row: 2 / 5;
    display: flex;
    flex-direction: column;
    gap: 14px;
    align-self: start;
    position: sticky;
    top: 20px;
}

@media (max-width: 1199px) {
    .chart-card  { grid-column: 1; grid-row: auto; }
    .orders-card { grid-column: 1; grid-row: auto; }
    .dash-sidebar { grid-column: 1; grid-row: auto; position: static; }
}

/* Quick actions */
.qa-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    padding: 16px;
}
.qa-btn {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 8px;
    padding: 16px 10px;
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-md);
    text-decoration: none;
    color: var(--text-2);
    font-size: 11px; font-weight: 600;
    text-align: center; line-height: 1.2;
    transition: all var(--transition);
}
.qa-btn svg { width: 18px; height: 18px; flex-shrink: 0; }
.qa-btn:hover {
    background: var(--blue-dim);
    border-color: rgba(79,114,245,0.3);
    color: var(--blue);
    transform: translateY(-1px);
}

/* Category bars */
.cat-wrap { padding: 0 16px 16px; display: flex; flex-direction: column; gap: 14px; }
.cat-row2 { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px; }
.cat-nm2  { font-size: 12px; font-weight: 600; color: var(--text-2); }
.cat-pct2 { font-family: 'DM Mono', monospace; font-size: 10.5px; color: var(--blue); }
.bar-bg   { height: 4px; background: var(--surface-3); border-radius: 100px; overflow: hidden; }
.bar-fill { height: 100%; background: var(--blue); border-radius: 100px; transition: width 1s cubic-bezier(0.4,0,0.2,1); }

/* Activity stream */
.activity { padding: 0 16px 16px; display: flex; flex-direction: column; gap: 16px; position: relative; }
.activity::before {
    content: '';
    position: absolute;
    left: 28px; top: 0; bottom: 0;
    width: 1px; background: var(--border-1);
}
.act-item { display: flex; gap: 12px; position: relative; z-index: 1; }
.act-marker {
    width: 28px; height: 28px; border-radius: 8px;
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--blue);
}
.act-marker svg { width: 12px; height: 12px; }
.act-user  { font-size: 12.5px; font-weight: 700; color: var(--text-1); line-height: 1; }
.act-desc  { font-size: 11px; color: var(--text-2); margin-top: 3px; line-height: 1.4; }
.act-time  { font-family: 'DM Mono', monospace; font-size: 9px; color: var(--text-3); margin-top: 5px; text-transform: uppercase; letter-spacing: 0.06em; }

/* Top products */
.prod-list { padding: 0 16px 16px; display: flex; flex-direction: column; gap: 12px; }
.prod-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px;
    border-radius: var(--radius-md);
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    transition: all var(--transition);
}
.prod-item:hover {
    background: var(--blue-dim);
    border-color: rgba(79,114,245,0.25);
}
.prod-rank {
    font-family: 'DM Mono', monospace;
    font-size: 10px; font-weight: 700;
    color: var(--text-3); width: 18px; text-align: center; flex-shrink: 0;
}
.prod-ico {
    width: 32px; height: 32px; border-radius: 9px;
    background: var(--surface-3);
    border: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); flex-shrink: 0;
}
.prod-ico svg { width: 14px; height: 14px; }
.prod-nm  { font-size: 12.5px; font-weight: 700; color: var(--text-1); line-height: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }
.prod-cnt { font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3); margin-top: 2px; }

/* Alert */
.stock-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: var(--radius-md);
    background: var(--red-dim); border: 1px solid rgba(232,69,69,0.2);
    margin: 0 16px 16px;
}
.alert-pulse {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--red); flex-shrink: 0;
    animation: livePulse 1.5s ease infinite;
}
.alert-label { font-size: 12px; font-weight: 600; color: var(--red); flex: 1; }
.alert-count {
    font-family: 'DM Mono', monospace;
    font-size: 10.5px; color: var(--red);
    background: rgba(232,69,69,0.12);
    padding: 2px 8px; border-radius: 100px;
}

/* ═══════════════════════════════════════════
   RESPONSIVE HIDE
═══════════════════════════════════════════ */
@media (max-width: 680px) {
    thead th:nth-child(4), tbody td:nth-child(4) { display: none; }
    .kpi-spark { display: none; }
}
</style>
@endpush

@section('content')
<div class="dash">

    {{-- ════════════════════════════════
         TOP BAR
    ════════════════════════════════ --}}
    <div class="dash-topbar">
        <div class="dash-greeting">
            <h2>@lang('admin.good_morning'), {{ Auth::user()->name }} 👋</h2>
            <p>
                <span class="live-dot"></span>
                @lang('admin.systems_operational') &nbsp;·&nbsp; @lang('admin.updated_just_now')
            </p>
        </div>
        <div class="dash-controls">
            <div class="period-group">
                <button class="period-btn"        onclick="setPeriod(this,'daily')">@lang('admin.today')</button>
                <button class="period-btn active" onclick="setPeriod(this,'weekly')">@lang('admin.this_week')</button>
                <button class="period-btn"        onclick="setPeriod(this,'monthly')">@lang('admin.this_month')</button>
            </div>
            <button class="theme-btn" onclick="toggleTheme()" title="@lang('admin.toggle_theme')">
                <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════
         KPI ROW
    ════════════════════════════════ --}}
    <div class="kpi-row">

        {{-- Revenue --}}
        <div class="kpi" style="--icon-clr: var(--blue-dim)">
            <div class="kpi-head">
                <div class="kpi-ico c-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['revenue_change'] ?? '12.5' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.total_revenue')</div>
                <div class="kpi-val">${{ number_format($totalRevenue, 0) }}</div>
                <div class="kpi-sub">@lang('admin.vs_previous_period')</div>
            </div>
            <canvas class="kpi-spark" id="spark0" aria-hidden="true"></canvas>
        </div>

        {{-- Orders --}}
        <div class="kpi" style="--icon-clr: var(--amber-dim)">
            <div class="kpi-head">
                <div class="kpi-ico c-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['orders_change'] ?? '8.2' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.total_orders')</div>
                <div class="kpi-val">{{ number_format($ordersCount) }}</div>
                <div class="kpi-sub">@lang('admin.orders_placed')</div>
            </div>
            <canvas class="kpi-spark" id="spark1" aria-hidden="true"></canvas>
        </div>

        {{-- Customers --}}
        <div class="kpi" style="--icon-clr: var(--green-dim)">
            <div class="kpi-head">
                <div class="kpi-ico c-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['customers_change'] ?? '5.1' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.customers')</div>
                <div class="kpi-val">{{ number_format($customersCount) }}</div>
                <div class="kpi-sub">@lang('admin.registered_accounts')</div>
            </div>
            <canvas class="kpi-spark" id="spark2" aria-hidden="true"></canvas>
        </div>

        {{-- Conversion --}}
        <div class="kpi" style="--icon-clr: var(--violet-dim)">
            <div class="kpi-head">
                <div class="kpi-ico c-violet">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-down">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    {{ abs($stats['conversion_change'] ?? 0.3) }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.conversion_rate')</div>
                <div class="kpi-val">3.2%</div>
                <div class="kpi-sub">@lang('admin.visitors_to_buyers')</div>
            </div>
            <canvas class="kpi-spark" id="spark3" aria-hidden="true"></canvas>
        </div>

    </div>

    {{-- ════════════════════════════════
         REVENUE CHART
    ════════════════════════════════ --}}
    <div class="card chart-card">
        <div class="card-head">
            <div>
                <div class="card-title">
                    @lang('admin.revenue_over_time')
                    <span class="live-badge">@lang('admin.live')</span>
                </div>
                <div class="card-sub">@lang('admin.monthly_gross_revenue')</div>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-swatch" style="background:var(--blue)"></span>
                    @lang('admin.this_year')
                </div>
                <div class="legend-item">
                    <span class="legend-swatch legend-dashed"></span>
                    @lang('admin.last_year')
                </div>
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="mainChart" style="height:260px"></canvas>
        </div>
    </div>

    {{-- ════════════════════════════════
         RECENT ORDERS TABLE
    ════════════════════════════════ --}}
    <div class="card orders-card">
        <div class="card-head">
            <div>
                <div class="card-title">@lang('admin.recent_orders')</div>
                <div class="card-sub">@lang('admin.last') {{ count($latestOrders) }} @lang('admin.transactions')</div>
            </div>
            <a href="{{ route('admin.orders') }}" class="card-link">@lang('admin.view_all') →</a>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>@lang('admin.order_number')</th>
                        <th>@lang('admin.customer')</th>
                        <th>@lang('admin.status')</th>
                        <th>@lang('admin.date')</th>
                        <th style="text-align:right">@lang('admin.amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestOrders as $order)
                    <tr>
                        <td><span class="oid">#{{ $order->order_number }}</span></td>
                        <td>
                            <div class="cust-row">
                                <img class="cust-av"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'G') }}&background=1f2233&color=4f72f5&bold=true&size=60"
                                     alt="">
                                <div style="min-width:0">
                                    <div class="cust-nm">{{ $order->user->name ?? __('admin.guest') }}</div>
                                    <div class="cust-em">{{ $order->user->email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $smap = ['pending'=>'s-pending','processing'=>'s-processing','shipped'=>'s-shipped','completed'=>'s-completed','cancelled'=>'s-cancelled'];
                                $sc   = $smap[$order->status] ?? 's-pending';
                            @endphp
                            <span class="s-pill {{ $sc }}">@lang('admin.status_' . $order->status)</span>
                        </td>
                        <td>
                            <div class="order-ts">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="order-ts2">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td><span class="order-amt">${{ number_format($order->total_amount, 2) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ════════════════════════════════
         SIDEBAR
    ════════════════════════════════ --}}
    <div class="dash-sidebar">

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">@lang('admin.quick_actions')</div>
            </div>
            <div class="qa-grid">
                <a href="{{ route('admin.products') }}" class="qa-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    @lang('admin.add_product')
                </a>
                <a href="{{ route('admin.orders') }}" class="qa-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    @lang('admin.nav_orders')
                </a>
                <a href="{{ route('admin.customers') }}" class="qa-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    @lang('admin.nav_customers')
                </a>
                <a href="{{ route('admin.categories') }}" class="qa-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    @lang('admin.nav_categories')
                </a>
            </div>
        </div>

        {{-- Stock alert --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">@lang('admin.stock_alerts')</div>
            </div>
            <div class="stock-alert">
                <span class="alert-pulse"></span>
                <span class="alert-label">@lang('admin.low_stock_products')</span>
                <span class="alert-count">5 @lang('admin.items')</span>
            </div>
            <div style="padding: 0 16px 16px">
                <a href="{{ route('admin.products') }}" class="card-link" style="display:block;text-align:center;padding:8px">
                    @lang('admin.view_all_products')
                </a>
            </div>
        </div>

        {{-- Top categories --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">@lang('admin.top_categories')</div>
            </div>
            <div class="cat-wrap">
                @php $totalProducts = \App\Models\Product::count(); @endphp
                @foreach($trendingCategories as $cat)
                    @php $pct = $totalProducts > 0 ? ($cat->products_count / $totalProducts) * 100 : 0; @endphp
                    <div>
                        <div class="cat-row2">
                            <span class="cat-nm2">{{ $cat->name }}</span>
                            <span class="cat-pct2">{{ round($pct) }}%</span>
                        </div>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top products --}}
        @if(isset($topProducts) && $topProducts->count())
        <div class="card">
            <div class="card-head">
                <div class="card-title">@lang('admin.top_products')</div>
            </div>
            <div class="prod-list">
                @foreach($topProducts as $i => $prod)
                <div class="prod-item">
                    <span class="prod-rank">#{{ $i + 1 }}</span>
                    <div class="prod-ico">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div style="min-width:0">
                        <div class="prod-nm">{{ $prod->name }}</div>
                        <div class="prod-cnt">{{ $prod->order_items_count }} @lang('admin.sales')</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Activity feed --}}
        @if(isset($recentActivity) && $recentActivity->count())
        <div class="card">
            <div class="card-head">
                <div class="card-title">@lang('admin.activity')</div>
            </div>
            <div class="activity">
                @foreach($recentActivity as $act)
                <div class="act-item">
                    <div class="act-marker">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div style="min-width:0">
                        <div class="act-user">{{ $act->user->name }}</div>
                        <div class="act-desc">{{ $act->description }}</div>
                        <div class="act-time">{{ $act->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- /sidebar --}}

</div>
@endsection

@push('scripts')
<script>
/* ══════════════════════════════════════
   THEME
══════════════════════════════════════ */
(function () {
    if (localStorage.getItem('ecomm-theme') === 'light')
        document.documentElement.classList.add('light');
})();

function toggleTheme() {
    const light = document.documentElement.classList.toggle('light');
    localStorage.setItem('ecomm-theme', light ? 'light' : 'dark');
    rebuildMainChart();
    buildSparklines();
}

/* ══════════════════════════════════════
   PERIOD TABS
══════════════════════════════════════ */
function setPeriod(btn, period) {
    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

/* ══════════════════════════════════════
   THEME COLORS HELPER
══════════════════════════════════════ */
function tc() {
    const l = document.documentElement.classList.contains('light');
    return {
        blue:     l ? '#2d52e0' : '#4f72f5',
        blueDim:  l ? 'rgba(45,82,224,0.12)'  : 'rgba(79,114,245,0.18)',
        blueFade: l ? 'rgba(45,82,224,0)'      : 'rgba(79,114,245,0)',
        prevLine: l ? 'rgba(100,110,140,0.35)' : 'rgba(90,96,118,0.3)',
        prevFill: l ? 'rgba(100,110,140,0.06)' : 'rgba(90,96,118,0.04)',
        grid:     l ? 'rgba(0,0,0,0.06)'       : 'rgba(255,255,255,0.04)',
        tick:     l ? '#9aa0b8'                : '#545b74',
        ttBg:     l ? '#ffffff'                : '#191c27',
        ttTitle:  l ? '#0f1117'                : '#eceef4',
        ttBody:   l ? '#4a5068'                : '#9aa0b8',
        ttBorder: l ? 'rgba(0,0,0,0.1)'       : 'rgba(255,255,255,0.1)',
    };
}

/* ══════════════════════════════════════
   SPARKLINES
══════════════════════════════════════ */
const sparkData = [
    [3,5,4,7,6,8,9,8,11,10,12,13],  // revenue
    [5,4,6,5,7,8,6,9,8,10,9,11],    // orders
    [2,3,4,3,5,4,6,5,7,8,7,9],      // customers
    [4,3,5,4,3,5,4,3,4,3,4,3],      // conversion
];
const sparkColors = ['#4f72f5','#e8a000','#1fba72','#8b5cf6'];

function buildSparklines() {
    sparkData.forEach((data, i) => {
        const canvas = document.getElementById('spark' + i);
        if (!canvas) return;
        const existing = Chart.getChart(canvas);
        if (existing) existing.destroy();

        const l   = document.documentElement.classList.contains('light');
        const clr = l
            ? sparkColors[i].replace(/[\d.]+\)$/, '1)') 
            : sparkColors[i];
        const ctx  = canvas.getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 36);
        grad.addColorStop(0, clr + '33');
        grad.addColorStop(1, clr + '00');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(() => ''),
                datasets: [{
                    data,
                    borderColor: clr,
                    borderWidth: 1.5,
                    pointRadius: 0,
                    backgroundColor: grad,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600 },
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: {
                    x: { display: false },
                    y: { display: false, beginAtZero: false }
                },
                elements: { line: { borderCapStyle: 'round' } }
            }
        });
    });
}

/* ══════════════════════════════════════
   MAIN REVENUE CHART
══════════════════════════════════════ */
let mainChart;

function buildGradients(ctx, height) {
    const c = tc();
    const g1 = ctx.createLinearGradient(0, 0, 0, height);
    g1.addColorStop(0, c.blueDim);
    g1.addColorStop(1, c.blueFade);
    const g2 = ctx.createLinearGradient(0, 0, 0, height);
    g2.addColorStop(0, c.prevFill);
    g2.addColorStop(1, c.prevFill);
    return [g1, g2];
}

function rebuildMainChart() {
    if (mainChart) { mainChart.destroy(); mainChart = null; }
    const canvas = document.getElementById('mainChart');
    if (!canvas) return;
    const ctx    = canvas.getContext('2d');
    const height = 260;
    const c      = tc();
    const [g1, g2] = buildGradients(ctx, height);

    const labels = @json([
        __('admin.month_jan'), __('admin.month_feb'), __('admin.month_mar'),
        __('admin.month_apr'), __('admin.month_may'), __('admin.month_jun'),
        __('admin.month_jul'), __('admin.month_aug'), __('admin.month_sep'),
        __('admin.month_oct'), __('admin.month_nov'), __('admin.month_dec'),
    ]);

    const current  = {!! isset($chartData) ? json_encode($chartData['current'])  : json_encode([12000,19000,15000,25000,22000,30000,28000,35000,32000,40000,38000,45000]) !!};
    const previous = {!! isset($chartData) ? json_encode($chartData['previous']) : json_encode([10000,15000,12000,20000,18000,25000,23000,29000,27000,34000,32000,38000]) !!};

    mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: '{{ __("admin.this_year") }}',
                    data: current,
                    borderColor: c.blue,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: c.blue,
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    backgroundColor: g1,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: '{{ __("admin.last_year") }}',
                    data: previous,
                    borderColor: c.prevLine,
                    borderWidth: 1.5,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    backgroundColor: g2,
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            animation: { duration: 500, easing: 'easeOutCubic' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: c.ttBg,
                    titleColor:      c.ttTitle,
                    bodyColor:       c.ttBody,
                    borderColor:     c.ttBorder,
                    borderWidth:     1,
                    padding:         14,
                    cornerRadius:    12,
                    displayColors:   true,
                    boxWidth:        8, boxHeight:8,
                    titleFont: { family: 'DM Mono', size: 11, weight: '600' },
                    bodyFont:  { family: 'DM Mono', size: 12 },
                    callbacks: {
                        label: ctx => ' ' + ctx.dataset.label + '  $' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid:  { color: c.grid, drawBorder: false },
                    ticks: { color: c.tick, font: { family: 'DM Mono', size: 10 }, callback: v => '$' + (v/1000) + 'K' }
                },
                x: {
                    grid:  { display: false, drawBorder: false },
                    ticks: { color: c.tick, font: { family: 'DM Mono', size: 10 } }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    buildSparklines();
    rebuildMainChart();
});
</script>
@endpush