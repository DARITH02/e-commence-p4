@extends('layouts.admin')

@section('title', __('admin.customers'))
@section('page_title', __('admin.customers'))

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS — unified with dashboard
═══════════════════════════════════════════ */
:root {
    --surface-0:   #0b0d12;
    --surface-1:   #13151d;
    --surface-2:   #191c27;
    --surface-3:   #1f2233;
    --border-1:    rgba(255,255,255,0.06);
    --border-2:    rgba(255,255,255,0.11);
    --text-1:      #eceef4;
    --text-2:      #9aa0b8;
    --text-3:      #545b74;
    --blue:        #4f72f5;
    --blue-dim:    rgba(79,114,245,0.12);
    --blue-mid:    rgba(79,114,245,0.22);
    --green:       #1fba72;
    --green-dim:   rgba(31,186,114,0.1);
    --amber:       #e8a000;
    --amber-dim:   rgba(232,160,0,0.1);
    --red:         #e84545;
    --red-dim:     rgba(232,69,69,0.1);
    --violet:      #8b5cf6;
    --violet-dim:  rgba(139,92,246,0.1);
    --radius-sm:   8px;
    --radius-md:   12px;
    --radius-lg:   16px;
    --radius-xl:   20px;
    --shadow-card: 0 1px 3px rgba(0,0,0,.3), 0 4px 16px rgba(0,0,0,.2);
    --shadow-hover:0 4px 24px rgba(0,0,0,.35), 0 0 0 1px rgba(79,114,245,0.25);
    --transition:  0.18s cubic-bezier(0.4,0,0.2,1);
}
html.light {
    --surface-0:  #f0f2f7; --surface-1: #ffffff;
    --surface-2:  #f7f8fc; --surface-3: #eef0f6;
    --border-1:   rgba(0,0,0,0.07); --border-2: rgba(0,0,0,0.13);
    --text-1:     #0f1117; --text-2: #4a5068; --text-3: #9aa0b8;
    --blue:       #2d52e0; --blue-dim: rgba(45,82,224,0.08);
    --blue-mid:   rgba(45,82,224,0.16);
    --green:      #059669; --green-dim: rgba(5,150,105,0.08);
    --amber:      #b45309; --amber-dim: rgba(180,83,9,0.08);
    --red:        #c92b2b; --red-dim: rgba(201,43,43,0.08);
    --violet:     #6d28d9; --violet-dim: rgba(109,40,217,0.08);
    --shadow-card:0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}
body { background: var(--surface-0) !important; }
.content-inner { max-width: none !important; }

/* ─── Page animation ─── */
.customers-page {
    display: flex; flex-direction: column; gap: 20px;
    animation: fadeUp .45s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─── Page header ─── */
.page-header {
    display: flex; align-items: center;
    justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.page-heading { display: flex; flex-direction: column; gap: 4px; }
.page-heading h1 {
    font-size: 22px; font-weight: 700; color: var(--text-1);
    letter-spacing: -0.02em; line-height: 1;
}
.page-heading p {
    font-family: 'DM Mono', monospace; font-size: 10px;
    color: var(--text-3); letter-spacing: 0.06em;
    display: flex; align-items: center; gap: 6px;
}
.live-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--green);
    animation: livePulse 2.2s ease infinite;
}
@keyframes livePulse {
    0%   { box-shadow: 0 0 0 0 rgba(31,186,114,0.5); }
    70%  { box-shadow: 0 0 0 6px rgba(31,186,114,0); }
    100% { box-shadow: 0 0 0 0 rgba(31,186,114,0); }
}

/* ─── KPI row ─── */
.kpi-row {
    display: grid; grid-template-columns: repeat(4,1fr); gap: 14px;
}
@media(max-width:900px){ .kpi-row { grid-template-columns: repeat(2,1fr); } }
@media(max-width:520px){ .kpi-row { grid-template-columns: 1fr; } }

.kpi {
    background: var(--surface-1); border: 1px solid var(--border-1);
    border-radius: var(--radius-lg); padding: 18px 20px;
    display: flex; flex-direction: column; gap: 10px;
    box-shadow: var(--shadow-card);
    transition: box-shadow var(--transition), border-color var(--transition), transform var(--transition);
    position: relative; overflow: hidden;
}
.kpi:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.kpi-head  { display: flex; align-items: center; justify-content: space-between; }
.kpi-ico {
    width: 34px; height: 34px; border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: transform var(--transition);
}
.kpi:hover .kpi-ico { transform: scale(1.08); }
.kpi-ico svg { width: 15px; height: 15px; }
.c-blue   { background: var(--blue-dim);   color: var(--blue); }
.c-green  { background: var(--green-dim);  color: var(--green); }
.c-amber  { background: var(--amber-dim);  color: var(--amber); }
.c-violet { background: var(--violet-dim); color: var(--violet); }
.kpi-label {
    font-family: 'DM Mono', monospace; font-size: 9.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.09em; color: var(--text-3);
}
.kpi-val  { font-size: 22px; font-weight: 700; color: var(--text-1); letter-spacing: -0.02em; line-height: 1; }
.kpi-sub  { font-size: 10.5px; color: var(--text-2); }

/* ─── Toolbar ─── */
.toolbar {
    background: var(--surface-1); border: 1px solid var(--border-1);
    border-radius: var(--radius-lg); padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    flex-wrap: wrap; box-shadow: var(--shadow-card);
}
.search-wrap { position: relative; flex: 1; min-width: 220px; }
.search-wrap svg {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; color: var(--text-3); pointer-events: none;
}
.search-wrap input {
    width: 100%; background: var(--surface-2); border: 1px solid var(--border-1);
    border-radius: var(--radius-md); padding: 9px 14px 9px 38px;
    color: var(--text-1); font-family: 'DM Sans', sans-serif;
    font-size: 13px; font-weight: 500;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.search-wrap input::placeholder { color: var(--text-3); }
.search-wrap input:focus {
    outline: none; border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-dim); background: var(--surface-3);
}
.filter-select {
    background: var(--surface-2); border: 1px solid var(--border-1);
    border-radius: var(--radius-md); padding: 9px 14px;
    color: var(--text-2); font-family: 'DM Sans', sans-serif;
    font-size: 13px; font-weight: 500; cursor: pointer;
    transition: border-color var(--transition);
}
.filter-select:focus { outline: none; border-color: var(--blue); }
.toolbar-sep { width: 1px; height: 28px; background: var(--border-1); flex-shrink: 0; }
.btn-export {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--surface-2); color: var(--text-2);
    border: 1px solid var(--border-1); border-radius: var(--radius-md);
    padding: 9px 14px; font-family: 'DM Sans', sans-serif;
    font-size: 12px; font-weight: 600; cursor: pointer;
    transition: all var(--transition); white-space: nowrap; flex-shrink: 0;
}
.btn-export svg { width: 13px; height: 13px; flex-shrink: 0; }
.btn-export:hover { background: var(--surface-3); color: var(--text-1); border-color: var(--border-2); }

/* Toolbar stats */
.toolbar-stats { display: flex; align-items: center; gap: 8px; margin-left: auto; }
.stat-pill {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: var(--radius-sm);
    font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700;
    letter-spacing: 0.04em; white-space: nowrap;
}
.stat-pill.total  { background: var(--blue-dim);  color: var(--blue); }
.stat-pill.active { background: var(--green-dim); color: var(--green); }
.stat-pill.new    { background: var(--violet-dim);color: var(--violet); }
.stat-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ─── Table card ─── */
.table-card {
    background: var(--surface-1); border: 1px solid var(--border-1);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-card); overflow: hidden;
}
.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
    padding: 10px 20px;
    font-family: 'DM Mono', monospace; font-size: 9.5px;
    text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-3);
    font-weight: 700; background: var(--surface-2);
    text-align: left; white-space: nowrap;
    border-bottom: 1px solid var(--border-1); user-select: none;
}
thead th:last-child { text-align: right; }
thead th.sortable { cursor: pointer; transition: color var(--transition); }
thead th.sortable:hover { color: var(--text-1); }
thead th .sort-icon { display: inline-block; margin-left: 4px; opacity: .4; font-size: 8px; vertical-align: middle; }
thead th.sorted   .sort-icon { opacity: 1; color: var(--blue); }

tbody tr { border-top: 1px solid var(--border-1); transition: background var(--transition); cursor: pointer; }
tbody tr:hover { background: var(--surface-2); }
tbody td { padding: 13px 20px; vertical-align: middle; }

/* Customer identity cell */
.cust-identity { display: flex; align-items: center; gap: 12px; }
.cust-av {
    width: 38px; height: 38px; border-radius: var(--radius-md);
    object-fit: cover; border: 1px solid var(--border-2);
    flex-shrink: 0; background: var(--surface-3);
}
.cust-nm   { font-size: 13px; font-weight: 700; color: var(--text-1); line-height: 1; }
.cust-em   { font-size: 10.5px; color: var(--text-3); margin-top: 3px; font-family: 'DM Mono', monospace; }

/* Joined date */
.joined-date { font-size: 11.5px; color: var(--text-2); white-space: nowrap; }
.joined-rel  { font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3); margin-top: 2px; }

/* Order count badge */
.orders-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'DM Mono', monospace; font-size: 12px; font-weight: 700;
    color: var(--text-1);
}
.orders-badge span { font-size: 9.5px; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; }

/* Spend amount */
.spend-val { font-size: 14px; font-weight: 700; color: var(--text-1); white-space: nowrap; line-height: 1; }
.spend-avg { font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3); margin-top: 3px; }

/* Status badges */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 100px;
    font-size: 11px; font-weight: 600;
    border: 1px solid transparent; white-space: nowrap;
}
.status-badge::before {
    content: ''; width: 5px; height: 5px;
    border-radius: 50%; background: currentColor; flex-shrink: 0;
}
.status-active   { background: var(--green-dim);  color: var(--green);  border-color: rgba(31,186,114,0.2); }
.status-inactive { background: var(--red-dim);    color: var(--red);    border-color: rgba(232,69,69,0.2); }
.status-new      { background: var(--violet-dim); color: var(--violet); border-color: rgba(139,92,246,0.2); }

/* Action buttons */
.action-group { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
.btn-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--surface-2); border: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-2); cursor: pointer;
    transition: all var(--transition); flex-shrink: 0;
}
.btn-icon:hover { background: var(--blue-dim); border-color: rgba(79,114,245,0.25); color: var(--blue); }
.btn-icon svg { width: 14px; height: 14px; }

/* Empty state */
.empty-state {
    padding: 80px 32px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.empty-icon {
    width: 52px; height: 52px; border-radius: var(--radius-lg);
    background: var(--surface-2); border: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-3); margin-bottom: 4px;
}
.empty-icon svg { width: 22px; height: 22px; }
.empty-title { font-size: 14px; font-weight: 700; color: var(--text-2); }
.empty-sub   { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--text-3); letter-spacing: 0.04em; }

/* Pagination */
.pagination-wrap {
    padding: 16px 20px; border-top: 1px solid var(--border-1);
    background: var(--surface-2);
}
.pagination-wrap .pagination { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
.pagination-wrap .pagination li a,
.pagination-wrap .pagination li span {
    display: flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; padding: 0 8px;
    border-radius: var(--radius-sm);
    font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 700;
    color: var(--text-2); background: var(--surface-1);
    border: 1px solid var(--border-1); text-decoration: none;
    transition: all var(--transition);
}
.pagination-wrap .pagination li a:hover {
    background: var(--blue-dim); border-color: rgba(79,114,245,0.25); color: var(--blue);
}
.pagination-wrap .pagination li.active span {
    background: var(--blue); border-color: var(--blue); color: #fff;
}
.pagination-wrap .pagination li.disabled span { opacity: .3; cursor: not-allowed; }

/* ═══════════════════════════════════════════
   CUSTOMER PROFILE DRAWER
═══════════════════════════════════════════ */
.drawer-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);
    z-index: 1000; opacity: 0; pointer-events: none; transition: opacity .25s ease;
}
.drawer-overlay.open { opacity: 1; pointer-events: all; }
.drawer {
    position: fixed; top: 0; right: 0; bottom: 0;
    width: 100%; max-width: 460px;
    background: var(--surface-1); border-left: 1px solid var(--border-2);
    box-shadow: -16px 0 48px rgba(0,0,0,.4);
    z-index: 1001; display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(0.16,1,0.3,1); overflow: hidden;
}
.drawer.open { transform: translateX(0); }

.drawer-header {
    padding: 20px 24px; border-bottom: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0; background: var(--surface-2);
}
.drawer-title { font-size: 15px; font-weight: 700; color: var(--text-1); letter-spacing: -.01em; }
.drawer-close {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--surface-1); border: 1px solid var(--border-1);
    color: var(--text-2); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--transition);
}
.drawer-close:hover { background: var(--red-dim); border-color: rgba(232,69,69,.25); color: var(--red); transform: rotate(90deg); }
.drawer-close svg { width: 14px; height: 14px; }
.drawer-body {
    flex: 1; overflow-y: auto; padding: 24px;
    scrollbar-width: thin; scrollbar-color: var(--border-2) transparent;
}

/* Profile hero */
.profile-hero {
    display: flex; align-items: center; gap: 16px;
    padding: 20px; border-radius: var(--radius-lg);
    background: var(--surface-2); border: 1px solid var(--border-1);
    margin-bottom: 20px;
}
.profile-av {
    width: 56px; height: 56px; border-radius: var(--radius-md);
    object-fit: cover; border: 2px solid var(--border-2); flex-shrink: 0;
}
.profile-nm { font-size: 16px; font-weight: 700; color: var(--text-1); line-height: 1; }
.profile-em { font-family: 'DM Mono', monospace; font-size: 10.5px; color: var(--text-3); margin-top: 4px; }
.profile-badges { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; }

/* Section */
.drawer-section { margin-bottom: 20px; }
.drawer-section-title {
    font-family: 'DM Mono', monospace; font-size: 9.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; color: var(--text-3);
    margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
}
.drawer-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border-1); }

/* Meta grid */
.meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.meta-item {
    padding: 12px 14px; background: var(--surface-2);
    border: 1px solid var(--border-1); border-radius: var(--radius-md);
}
.meta-label {
    font-family: 'DM Mono', monospace; font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em; color: var(--text-3); margin-bottom: 4px;
}
.meta-val { font-size: 13px; font-weight: 600; color: var(--text-1); }
.meta-val.highlight { color: var(--blue); font-size: 16px; }

/* Spend bar */
.spend-bar-wrap {
    padding: 14px 16px; background: var(--surface-2);
    border: 1px solid var(--border-1); border-radius: var(--radius-md);
}
.spend-bar-header {
    display: flex; justify-content: space-between; align-items: baseline;
    margin-bottom: 10px;
}
.spend-bar-label { font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3); text-transform: uppercase; letter-spacing: .06em; }
.spend-bar-val   { font-size: 15px; font-weight: 700; color: var(--text-1); }
.bar-track { height: 4px; background: var(--surface-3); border-radius: 100px; overflow: hidden; }
.bar-fill  { height: 100%; background: var(--blue); border-radius: 100px; transition: width 1s cubic-bezier(.4,0,.2,1); }
.bar-fill.high { background: var(--green); }
.bar-fill.mid  { background: var(--amber); }

/* Recent orders mini list */
.order-mini {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 0; border-bottom: 1px solid var(--border-1);
}
.order-mini:last-child { border-bottom: none; }
.order-mini-num {
    font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 700;
    color: var(--blue); background: var(--blue-dim);
    padding: 2px 7px; border-radius: 5px; flex-shrink: 0;
}
.order-mini-date { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.order-mini-pill { margin-left: auto; }
.order-mini-amt  { font-size: 12px; font-weight: 700; color: var(--text-1); margin-left: 10px; flex-shrink: 0; }

/* Status pills (reused in drawer) */
.s-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 8px; border-radius: 100px;
    font-size: 10px; font-weight: 600; border: 1px solid transparent; white-space: nowrap;
}
.s-pill::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
.s-completed { background: var(--green-dim);  color: var(--green);  border-color: rgba(31,186,114,0.2); }
.s-pending   { background: var(--amber-dim);  color: var(--amber);  border-color: rgba(232,160,0,0.2); }
.s-cancelled { background: var(--red-dim);    color: var(--red);    border-color: rgba(232,69,69,0.2); }
.s-shipped   { background: var(--violet-dim); color: var(--violet); border-color: rgba(139,92,246,0.2); }
.s-processing{ background: var(--blue-dim);   color: var(--blue);   border-color: rgba(79,114,245,0.2); }

.drawer-footer {
    padding: 16px 24px; border-top: 1px solid var(--border-1);
    display: flex; gap: 10px; flex-shrink: 0; background: var(--surface-2);
}
.btn-drawer-primary {
    flex: 1; padding: 10px; border-radius: var(--radius-md);
    background: var(--blue); color: #fff; border: none;
    font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 700;
    cursor: pointer; transition: all var(--transition);
    box-shadow: 0 4px 12px rgba(79,114,245,.25);
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-drawer-primary svg { width: 13px; height: 13px; }
.btn-drawer-primary:hover { background: #3d5de0; transform: translateY(-1px); }
.btn-drawer-secondary {
    padding: 10px 16px; border-radius: var(--radius-md);
    background: transparent; border: 1px solid var(--border-1);
    color: var(--text-2); font-family: 'DM Sans', sans-serif;
    font-size: 12px; font-weight: 600; cursor: pointer;
    transition: all var(--transition);
}
.btn-drawer-secondary:hover { background: var(--surface-3); color: var(--text-1); }

/* Spinner */
.spinner {
    width: 13px; height: 13px;
    border: 2px solid rgba(255,255,255,.3); border-top-color: #fff;
    border-radius: 50%; animation: spin .6s linear infinite; display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Toast */
.toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 2000;
    display: flex; align-items: center; gap: 10px; padding: 12px 18px;
    background: var(--surface-1); border: 1px solid var(--border-2);
    border-radius: var(--radius-md); box-shadow: 0 8px 24px rgba(0,0,0,.4);
    font-size: 13px; font-weight: 600; color: var(--text-1);
    transform: translateY(12px); opacity: 0;
    transition: all .25s cubic-bezier(0.16,1,0.3,1);
    pointer-events: none; max-width: 320px;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.toast.success .toast-dot { background: var(--green); }
.toast.error   .toast-dot { background: var(--red); }

/* Responsive */
@media(max-width:900px){
    thead th:nth-child(4), tbody td:nth-child(4) { display: none; }
}
@media(max-width:700px){
    thead th:nth-child(5), tbody td:nth-child(5) { display: none; }
    .drawer { max-width: 100%; }
}
@media(max-width:520px){
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="customers-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>@lang('admin.customers')</h1>
            <p>
                <span class="live-dot"></span>
                {{ $customers->total() }} @lang('admin.registered_accounts')
                &nbsp;·&nbsp; @lang('admin.updated_just_now')
            </p>
        </div>
    </div>

    {{-- ── KPI row ── --}}
    <div class="kpi-row">
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.total_customers')</div>
            <div class="kpi-val">{{ number_format($customers->total()) }}</div>
            <div class="kpi-sub">@lang('admin.registered_accounts')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.with_orders')</div>
            <div class="kpi-val">{{ number_format($stats['with_orders'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.have_purchased')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-violet">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.new_this_month')</div>
            <div class="kpi-val">{{ number_format($stats['new_this_month'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.joined_recently')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.total_revenue')</div>
            <div class="kpi-val">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
            <div class="kpi-sub">@lang('admin.from_all_customers')</div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="cust-search" placeholder="@lang('admin.search_customers')" autocomplete="off">
        </div>

        <select class="filter-select" id="sort-select">
            <option value="newest">@lang('admin.newest_first')</option>
            <option value="oldest">@lang('admin.oldest_first')</option>
            <option value="most_orders">@lang('admin.most_orders')</option>
            <option value="most_spent">@lang('admin.most_spent')</option>
        </select>

        <div class="toolbar-sep"></div>

        <div class="toolbar-stats">
            @php
                $custCol    = $customers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customers->getCollection() : collect($customers);
                $newCount   = $custCol->filter(fn($c) => $c->created_at >= now()->subDays(30))->count();
            @endphp
            <div class="stat-pill total">
                <span class="stat-dot"></span>
                {{ $customers->total() }} @lang('admin.total')
            </div>
            <div class="stat-pill active">
                <span class="stat-dot"></span>
                {{ $stats['with_orders'] ?? 0 }} @lang('admin.buyers')
            </div>
            <div class="stat-pill new">
                <span class="stat-dot"></span>
                {{ $newCount }} @lang('admin.new')
            </div>
        </div>

        <div class="toolbar-sep"></div>

        <button class="btn-export" onclick="exportCSV()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            @lang('admin.export')
        </button>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>@lang('admin.customer')</th>
                        <th class="sortable" data-col="joined">
                            @lang('admin.joined') <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="orders">
                            @lang('admin.orders') <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="spent">
                            @lang('admin.total_spent') <span class="sort-icon">↕</span>
                        </th>
                        <th>@lang('admin.status')</th>
                        <th style="text-align:right">@lang('admin.actions')</th>
                    </tr>
                </thead>
                <tbody id="custs-tbody">
                    @forelse($customers as $customer)
                    @php
                        $totalSpent  = $customer->orders_sum_total_amount ?? 0;
                        $orderCount  = $customer->orders_count ?? 0;
                        $avgOrder    = $orderCount > 0 ? $totalSpent / $orderCount : 0;
                        $isNew       = $customer->created_at >= now()->subDays(30);
                        $statusClass = $isNew ? 'status-new' : ($customer->is_active ?? true ? 'status-active' : 'status-inactive');
                        $statusLabel = $isNew ? __('admin.new') : ($customer->is_active ?? true ? __('admin.active') : __('admin.inactive'));
                    @endphp
                    <tr id="cust-row-{{ $customer->id }}"
                        data-joined="{{ $customer->created_at->format('Y-m-d') }}"
                        data-orders="{{ $orderCount }}"
                        data-spent="{{ $totalSpent }}"
                        onclick="openDrawer({{ $customer->id }})">
                        <td>
                            <div class="cust-identity">
                                <img class="cust-av"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=1f2233&color=4f72f5&bold=true&size=80"
                                     alt="{{ $customer->name }}">
                                <div style="min-width:0">
                                    <div class="cust-nm">{{ $customer->name }}</div>
                                    <div class="cust-em">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="joined-date">{{ $customer->created_at->format('d M Y') }}</div>
                            <div class="joined-rel">{{ $customer->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="orders-badge">
                                {{ $orderCount }}
                                <span>@lang('admin.orders')</span>
                            </div>
                        </td>
                        <td>
                            <div class="spend-val">${{ number_format($totalSpent, 2) }}</div>
                            @if($orderCount > 0)
                                <div class="spend-avg">~${{ number_format($avgOrder, 0) }} @lang('admin.avg')</div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="action-group">
                                <button onclick="openDrawer({{ $customer->id }})" class="btn-icon" title="@lang('admin.view_profile')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <a href="mailto:{{ $customer->email }}" class="btn-icon" title="@lang('admin.send_email')" onclick="event.stopPropagation()">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div class="empty-title">@lang('admin.no_customers_found')</div>
                                <div class="empty-sub">@lang('admin.customers_will_appear_here')</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="pagination-wrap">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════
     CUSTOMER PROFILE DRAWER
══════════════════════════════════════ --}}
<div id="drawer-overlay" class="drawer-overlay" onclick="closeDrawer()"></div>

<div id="cust-drawer" class="drawer">
    <div class="drawer-header">
        <div>
            <div class="drawer-title" id="drawer-name">@lang('admin.customer_profile')</div>
            <div style="font-family:'DM Mono',monospace;font-size:9.5px;color:var(--text-3);margin-top:3px;text-transform:uppercase;letter-spacing:.08em" id="drawer-joined"></div>
        </div>
        <button class="drawer-close" onclick="closeDrawer()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="drawer-body" id="drawer-body">
        <div style="display:flex;align-items:center;justify-content:center;height:200px;">
            <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
        </div>
    </div>

    <div class="drawer-footer">
        <button class="btn-drawer-secondary" onclick="closeDrawer()">@lang('admin.close')</button>
        <button class="btn-drawer-primary" id="drawer-email-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            @lang('admin.send_email')
        </button>
    </div>
</div>

{{-- Toast --}}
<div class="toast" id="toast">
    <span class="toast-dot"></span>
    <span id="toast-msg"></span>
</div>

@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════
   STATE
═══════════════════════════════════════ */
let activeCustomerId = null;

/* ═══════════════════════════════════════
   DRAWER
═══════════════════════════════════════ */
async function openDrawer(id) {
    activeCustomerId = id;
    const overlay = document.getElementById('drawer-overlay');
    const drawer  = document.getElementById('cust-drawer');

    document.getElementById('drawer-body').innerHTML = `
        <div style="display:flex;align-items:center;justify-content:center;height:200px;">
            <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
        </div>`;
    document.getElementById('drawer-name').textContent   = '…';
    document.getElementById('drawer-joined').textContent = '';

    overlay.classList.add('open');
    drawer.classList.add('open');
    document.body.style.overflow = 'hidden';

    try {
        const res = await fetch(`/admin/customers/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error();
        const customer = await res.json();
        renderDrawer(customer);
    } catch {
        document.getElementById('drawer-body').innerHTML = `
            <div class="empty-state" style="padding:40px">
                <div class="empty-title">@lang('admin.error_loading')</div>
            </div>`;
    }
}

function closeDrawer() {
    document.getElementById('drawer-overlay').classList.remove('open');
    document.getElementById('cust-drawer').classList.remove('open');
    document.body.style.overflow = '';
    activeCustomerId = null;
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

/* ═══════════════════════════════════════
   RENDER DRAWER
═══════════════════════════════════════ */
function renderDrawer(c) {
    const totalSpent = parseFloat(c.orders_sum_total_amount || 0);
    const orderCount = parseInt(c.orders_count || 0);
    const avgOrder   = orderCount > 0 ? totalSpent / orderCount : 0;
    const maxSpend   = 5000; // relative bar max
    const barPct     = Math.min((totalSpent / maxSpend) * 100, 100).toFixed(1);
    const barClass   = totalSpent >= 1000 ? 'high' : totalSpent >= 300 ? 'mid' : '';

    document.getElementById('drawer-name').textContent   = c.name;
    document.getElementById('drawer-joined').textContent = `@lang('admin.joined') ${c.created_at_formatted || c.created_at}`;

    // Email button
    const emailBtn = document.getElementById('drawer-email-btn');
    emailBtn.onclick = () => window.location.href = `mailto:${c.email}`;

    // Status label
    const isNew = c.is_new;
    const statusClass = isNew ? 'status-new' : (c.is_active ? 'status-active' : 'status-inactive');
    const statusLabel = isNew ? '{{ __("admin.new") }}' : (c.is_active ? '{{ __("admin.active") }}' : '{{ __("admin.inactive") }}');

    // Orders
    const spmap = { completed:'s-completed', pending:'s-pending', cancelled:'s-cancelled', shipped:'s-shipped', processing:'s-processing' };
    const slmap = {
        completed:  '{{ __("admin.status_completed") }}',
        pending:    '{{ __("admin.status_pending") }}',
        cancelled:  '{{ __("admin.status_cancelled") }}',
        shipped:    '{{ __("admin.status_shipped") }}',
        processing: '{{ __("admin.status_processing") }}',
    };

    const ordersHtml = (c.recent_orders || c.orders || []).slice(0, 5).map(o => `
        <div class="order-mini">
            <span class="order-mini-num">#${o.order_number}</span>
            <div style="min-width:0;flex:1">
                <div style="font-size:11px;color:var(--text-2)">${o.created_at_formatted || o.created_at}</div>
            </div>
            <span class="s-pill ${spmap[o.status] || 's-pending'} order-mini-pill">${slmap[o.status] || o.status}</span>
            <span class="order-mini-amt">$${parseFloat(o.total_amount).toFixed(2)}</span>
        </div>
    `).join('') || `<div style="font-size:12px;color:var(--text-3);padding:12px 0">@lang('admin.no_orders_yet')</div>`;

    document.getElementById('drawer-body').innerHTML = `
        <div class="profile-hero">
            <img class="profile-av"
                 src="https://ui-avatars.com/api/?name=${encodeURIComponent(c.name)}&background=1f2233&color=4f72f5&bold=true&size=112"
                 alt="">
            <div style="min-width:0">
                <div class="profile-nm">${c.name}</div>
                <div class="profile-em">${c.email}</div>
                <div class="profile-badges">
                    <span class="status-badge ${statusClass}">${statusLabel}</span>
                    ${orderCount > 0 ? `<span class="status-badge status-active">${orderCount} @lang('admin.orders')</span>` : ''}
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.overview')</div>
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.total_orders')</div>
                    <div class="meta-val highlight">${orderCount}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.total_spent')</div>
                    <div class="meta-val highlight">$${totalSpent.toFixed(2)}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.avg_order')</div>
                    <div class="meta-val">$${avgOrder.toFixed(2)}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.member_since')</div>
                    <div class="meta-val">${c.created_at_formatted || c.created_at}</div>
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.lifetime_value')</div>
            <div class="spend-bar-wrap">
                <div class="spend-bar-header">
                    <span class="spend-bar-label">@lang('admin.total_spent')</span>
                    <span class="spend-bar-val">$${totalSpent.toFixed(2)}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill ${barClass}" style="width:${barPct}%"></div>
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.recent_orders')</div>
            ${ordersHtml}
        </div>

        ${c.phone || c.address ? `
        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.contact')</div>
            <div class="meta-grid">
                ${c.phone ? `<div class="meta-item"><div class="meta-label">@lang('admin.phone')</div><div class="meta-val">${c.phone}</div></div>` : ''}
                ${c.address ? `<div class="meta-item" style="grid-column:span 2"><div class="meta-label">@lang('admin.address')</div><div class="meta-val" style="font-size:11px;line-height:1.5">${c.address}</div></div>` : ''}
            </div>
        </div>` : ''}
    `;
}

/* ═══════════════════════════════════════
   LIVE SEARCH
═══════════════════════════════════════ */
document.getElementById('cust-search').addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#custs-tbody tr[id^="cust-row-"]').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
    });
});

/* ═══════════════════════════════════════
   CLIENT-SIDE SORT
═══════════════════════════════════════ */
let sortState = { col: 'joined', dir: 'desc' };

document.querySelectorAll('thead th.sortable').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.dataset.col;
        sortState.dir = (sortState.col === col && sortState.dir === 'desc') ? 'asc' : 'desc';
        sortState.col = col;

        document.querySelectorAll('thead th.sortable').forEach(h => {
            h.classList.remove('sorted');
            h.querySelector('.sort-icon').textContent = '↕';
        });
        th.classList.add('sorted');
        th.querySelector('.sort-icon').textContent = sortState.dir === 'asc' ? '↑' : '↓';
        sortRows(col, sortState.dir);
    });
});

/* Sort select dropdown */
document.getElementById('sort-select').addEventListener('change', function () {
    const map = { newest:'joined desc', oldest:'joined asc', most_orders:'orders desc', most_spent:'spent desc' };
    const [col, dir] = (map[this.value] || 'joined desc').split(' ');
    sortState = { col, dir };
    document.querySelectorAll('thead th.sortable').forEach(h => {
        h.classList.remove('sorted');
        h.querySelector('.sort-icon').textContent = '↕';
        if (h.dataset.col === col) {
            h.classList.add('sorted');
            h.querySelector('.sort-icon').textContent = dir === 'asc' ? '↑' : '↓';
        }
    });
    sortRows(col, dir);
});

function sortRows(col, dir) {
    const tbody = document.getElementById('custs-tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr[id^="cust-row-"]'));
    rows.sort((a, b) => {
        let av = a.dataset[col] || '';
        let bv = b.dataset[col] || '';
        if (col === 'orders' || col === 'spent') {
            return dir === 'asc' ? parseFloat(av) - parseFloat(bv) : parseFloat(bv) - parseFloat(av);
        }
        return dir === 'asc'
            ? av.toString().localeCompare(bv.toString())
            : bv.toString().localeCompare(av.toString());
    });
    rows.forEach(r => tbody.appendChild(r));
}

/* ═══════════════════════════════════════
   CSV EXPORT
═══════════════════════════════════════ */
function exportCSV() {
    const rows = document.querySelectorAll('#custs-tbody tr[id^="cust-row-"]:not([style*="display: none"])');
    let csv = '@lang('admin.name'),@lang('admin.email'),@lang('admin.joined'),@lang('admin.orders'),@lang('admin.total_spent')\n';
    rows.forEach(row => {
        const nm   = row.querySelector('.cust-nm')?.textContent.trim() || '';
        const em   = row.querySelector('.cust-em')?.textContent.trim() || '';
        const date = row.querySelector('.joined-date')?.textContent.trim() || '';
        const ord  = row.dataset.orders || '0';
        const spt  = row.dataset.spent  || '0';
        csv += `"${nm}","${em}","${date}","${ord}","$${parseFloat(spt).toFixed(2)}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `customers-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
    showToast('{{ __("admin.export_ready") }}', 'success');
}

/* ═══════════════════════════════════════
   TOAST
═══════════════════════════════════════ */
let toastTimer;
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.className = 'toast ' + type;
    document.getElementById('toast-msg').textContent = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
}
</script>
@endpush