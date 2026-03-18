@extends('layouts.admin')

@section('title', __('admin.order'))
@section('page_title', __('admin.order'))

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS — unified with dashboard
═══════════════════════════════════════════ */
/* Orders page uses global design tokens from app.css */

body { background: var(--bg) !important; }
.content-inner { max-width: none !important; }

/* ─── Page animation ─── */
.orders-page {
    display: flex; flex-direction: column; gap: 24px;
    animation: fadeUp .6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─── Page header ─── */
.page-header {
    display: flex; align-items: center;
    justify-content: space-between; gap:20px; flex-wrap:wrap;
}
.page-heading { display:flex; flex-direction:column; gap:6px; }
.page-heading h1 {
    font-size:24px; font-weight:800; color:var(--text-1);
    letter-spacing:-0.03em; line-height:1;
}
.page-heading p {
    font-family:'DM Mono',monospace; font-size:10.5px;
    color:var(--text-3); letter-spacing:0.04em;
    display:flex; align-items:center; gap:8px;
}
.live-dot {
    width:7px; height:7px; border-radius:50%; background:var(--green);
    animation:livePulse 2.5s ease infinite; position:relative;
}
.live-dot::after {
    content:''; position:absolute; inset:-4px; border-radius:inherit;
    border:2px solid var(--green); opacity:0; animation:liveRipple 2.5s ease infinite;
}
@keyframes liveRipple {
    0%   { transform:scale(0.8); opacity:0.5; }
    100% { transform:scale(2.2); opacity:0; }
}

/* ─── KPI row ─── */
.kpi-row {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
}
@media(max-width:1024px){ .kpi-row{grid-template-columns:repeat(2,1fr);} }
@media(max-width:640px){ .kpi-row{grid-template-columns:1fr;} }

.kpi {
    background:var(--surface); border:1px solid var(--border-1);
    border-radius:var(--radius-xl); padding:24px;
    display:flex; flex-direction:column; gap:12px;
    box-shadow:var(--shadow-card);
    transition:all .3s var(--transition);
    position:relative; overflow:hidden;
}
.kpi:hover { transform:scale(1.02); border-color:var(--accent-dim); box-shadow:var(--shadow-hover); }
.kpi-head  { display:flex; align-items:center; justify-content:space-between; }
.kpi-ico {
    width:42px; height:42px; border-radius:var(--radius-md);
    display:flex; align-items:center; justify-content:center;
    transition:all .3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.kpi:hover .kpi-ico { transform:rotate(8deg) scale(1.1); }
.kpi-ico svg { width:20px; height:20px; stroke-width:2; }

.kpi-label {
    font-family:'DM Mono',monospace; font-size:10px; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; color:var(--text-3);
}
.kpi-val {
    font-size:28px; font-weight:800; color:var(--text-1);
    letter-spacing:-0.02em; line-height:1;
}
.kpi-sub { font-size:12px; color:var(--text-2); font-weight: 500; }

/* ─── Toolbar ─── */
.toolbar {
    background:var(--surface-1); border:1px solid var(--border-1);
    border-radius:var(--radius-lg); padding:14px 16px;
    display:flex; align-items:center; gap:12px;
    flex-wrap:wrap; box-shadow:var(--shadow-card);
}
.search-wrap { position:relative; flex:1; min-width:220px; }
.search-wrap svg {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    width:15px; height:15px; color:var(--text-3); pointer-events:none;
}
.search-wrap input {
    width:100%; background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md); padding:9px 14px 9px 38px;
    color:var(--text-1); font-family:'DM Sans',sans-serif;
    font-size:13px; font-weight:500;
    transition:border-color var(--transition), box-shadow var(--transition);
}
.search-wrap input::placeholder { color:var(--text-3); }
.search-wrap input:focus {
    outline:none; border-color:var(--blue);
    box-shadow:0 0 0 3px var(--blue-dim); background:var(--surface-3);
}
.filter-select {
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md); padding:9px 14px;
    color:var(--text-2); font-family:'DM Sans',sans-serif;
    font-size:13px; font-weight:500; cursor:pointer;
    transition:border-color var(--transition);
}
.filter-select:focus { outline:none; border-color:var(--blue); }
.toolbar-sep { width:1px; height:28px; background:var(--border-1); flex-shrink:0; }

/* Date range */
.date-range {
    display:flex; align-items:center; gap:6px;
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md); padding:6px 12px;
}
.date-range input[type="date"] {
    background:transparent; border:none; color:var(--text-2);
    font-family:'DM Mono',monospace; font-size:11px; font-weight:600;
    cursor:pointer; outline:none; width:120px;
}
.date-range input[type="date"]::-webkit-calendar-picker-indicator {
    filter:invert(0.5); cursor:pointer;
}
.date-sep { font-family:'DM Mono',monospace; font-size:10px; color:var(--text-3); }

/* Export btn */
.btn-export {
    display:inline-flex; align-items:center; gap:7px;
    background:var(--surface-2); color:var(--text-2);
    border:1px solid var(--border-1); border-radius:var(--radius-md);
    padding:9px 14px; font-family:'DM Sans',sans-serif;
    font-size:12px; font-weight:600; cursor:pointer;
    transition:all var(--transition); white-space:nowrap; flex-shrink:0;
}
.btn-export svg { width:13px; height:13px; flex-shrink:0; }
.btn-export:hover { background:var(--surface-3); color:var(--text-1); border-color:var(--border-2); }

/* ─── Table card ─── */
.table-card {
    background:var(--surface-1); border:1px solid var(--border-1);
    border-radius:var(--radius-lg); box-shadow:var(--shadow-card); overflow:hidden;
}
.table-scroll { overflow-x:auto; }
table { width:100%; border-collapse:collapse; }
thead th {
    padding:10px 20px;
    font-family:'DM Mono',monospace; font-size:9.5px;
    text-transform:uppercase; letter-spacing:0.1em;
    color:var(--text-3); font-weight:700;
    background:var(--surface-2); text-align:left;
    white-space:nowrap; border-bottom:1px solid var(--border-1);
    user-select:none;
}
thead th:last-child { text-align:right; }
thead th.sortable { cursor:pointer; transition:color var(--transition); }
thead th.sortable:hover { color:var(--text-1); }
thead th .sort-icon { display:inline-block; margin-left:4px; opacity:.4; font-size:8px; vertical-align:middle; }
thead th.sorted   .sort-icon { opacity:1; color:var(--blue); }

tbody tr { border-top:1px solid var(--border-1); transition:background var(--transition); cursor:pointer; }
tbody tr:hover { background:var(--surface-2); }
tbody td { padding:13px 20px; vertical-align:middle; }

/* Order number */
.order-num {
    font-family:'DM Mono',monospace; font-size:11.5px; font-weight:700;
    color:var(--blue); background:var(--blue-dim);
    padding:3px 9px; border-radius:6px; white-space:nowrap;
    display:inline-block;
}

/* Customer cell */
.cust-row { display:flex; align-items:center; gap:10px; }
.cust-av {
    width:32px; height:32px; border-radius:var(--radius-sm);
    object-fit:cover; border:1px solid var(--border-2);
    flex-shrink:0; background:var(--surface-3);
}
.cust-nm { font-size:13px; font-weight:600; color:var(--text-1); line-height:1; }
.cust-em { font-size:10.5px; color:var(--text-3); margin-top:2px; }

/* Status pills */
.s-pill {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:100px;
    font-size:11.5px; font-weight:700;
    border:1px solid transparent; white-space:nowrap;
    letter-spacing:0.01em;
}
.s-pill::before {
    content:''; width:6px; height:6px;
    border-radius:50%; background:currentColor; flex-shrink:0;
    box-shadow:0 0 10px currentColor;
}
.s-pending    { background:var(--amber-dim);  color:var(--amber);  border-color:rgba(232,160,0,0.15); }
.s-processing { background:var(--accent-dim); color:var(--accent); border-color:rgba(79,114,245,0.15); }
.s-shipped    { background:var(--violet-dim); color:var(--violet); border-color:rgba(139,92,246,0.15); }
.s-completed  { background:var(--green-dim);  color:var(--green);  border-color:rgba(31,186,114,0.15); }
.s-cancelled  { background:var(--red-dim);    color:var(--red);    border-color:rgba(232,69,69,0.15); }

/* Status select inside table row (inline edit) */
.status-select {
    background:transparent; border:none; outline:none;
    font-family:inherit; font-size:11px; font-weight:600;
    cursor:pointer; color:inherit; padding:0;
    -webkit-appearance:none; appearance:none;
}

/* Date */
.order-date  { font-size:11.5px; color:var(--text-2); white-space:nowrap; }
.order-time  { font-family:'DM Mono',monospace; font-size:9.5px; color:var(--text-3); margin-top:2px; }

/* Amount */
.order-amt { font-size:14px; font-weight:700; color:var(--text-1); white-space:nowrap; }

/* Actions */
.action-group { display:flex; align-items:center; justify-content:flex-end; gap:6px; }
.btn-icon {
    width:32px; height:32px; border-radius:var(--radius-sm);
    background:var(--surface-2); border:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:center;
    color:var(--text-2); cursor:pointer;
    transition:all var(--transition); flex-shrink:0;
}
.btn-icon:hover { background:var(--blue-dim); border-color:rgba(79,114,245,0.25); color:var(--blue); }
.btn-icon svg { width:14px; height:14px; }

/* Empty state */
.empty-state {
    padding:80px 32px; text-align:center;
    display:flex; flex-direction:column; align-items:center; gap:12px;
}
.empty-icon {
    width:52px; height:52px; border-radius:var(--radius-lg);
    background:var(--surface-2); border:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:center;
    color:var(--text-3); margin-bottom:4px;
}
.empty-icon svg { width:22px; height:22px; }
.empty-title { font-size:14px; font-weight:700; color:var(--text-2); }
.empty-sub   { font-family:'DM Mono',monospace; font-size:10px; color:var(--text-3); letter-spacing:0.04em; }

/* Pagination */
.pagination-wrap {
    padding: 16px 24px;
    background: var(--surface-2);
    border-top: 1px solid var(--border-1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.pagination-info {
    font-size: 13px;
    color: var(--text-3);
    font-weight: 500;
}

.pagination-info b {
    color: var(--text-1);
    font-weight: 600;
}

/* Robust overrides for Laravel's default nav output */
.pagination-wrap nav {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.pagination-wrap nav > div:first-child,
.pagination-wrap nav p {
    display: none !important;
}

.pagination-wrap nav > div:last-child {
    display: flex !important;
    align-items: center !important;
    border: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

.pagination-wrap nav > div:last-child > div:first-child {
    display: none !important;
}

.pagination-wrap nav > div:last-child > div:last-child {
    display: flex !important;
    gap: 6px !important;
    border: none !important;
}

.pagination-wrap span, 
.pagination-wrap a {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 12px !important;
    border-radius: var(--radius-md) !important;
    background: var(--surface-1) !important;
    border: 1px solid var(--border-1) !important;
    color: var(--text-2) !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer !important;
}

.pagination-wrap a:hover {
    background: var(--surface-3) !important;
    border-color: var(--border-2) !important;
    color: var(--text-1) !important;
    transform: translateY(-1px) !important;
}

.pagination-wrap [aria-current="page"] span,
.pagination-wrap .active span {
    background: var(--blue) !important;
    border-color: var(--blue) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(79, 114, 245, 0.25) !important;
}

.pagination-wrap [aria-disabled="true"] span,
.pagination-wrap .disabled span {
    opacity: 0.4 !important;
    cursor: not-allowed !important;
    background: var(--surface-2) !important;
}


/* ═══════════════════════════════════════════
   ORDER DETAIL DRAWER
═══════════════════════════════════════════ */
/* ═══════════════════════════════════════════
   MODAL (Premium Style)
   ═══════════════════════════════════════════ */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.65);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 1000; display: none; align-items: center; justify-content: center;
    padding: 24px; opacity: 0; transition: opacity 0.3s;
}
.modal-overlay.open { display: flex; opacity: 1; }
.modal-container {
    background: var(--surface-1); border: 1px solid var(--border-1);
    border-radius: var(--radius-xl); width: 100%; max-width: 600px;
    max-height: 90vh; display: flex; flex-direction: column;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
    overflow: hidden;
}
.modal-overlay.open .modal-container { transform: translateY(0); }

.modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border-1); display: flex; align-items: center; justify-content: space-between; flex-shrink:0; }
.modal-head-left { display: flex; align-items: center; gap: 14px; }
.modal-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--accent-dim); color: var(--accent); display: flex; align-items: center; justify-content: center; }
.modal-icon svg { width: 20px; height: 20px; }
.modal-title { font-size: 16px; font-weight: 800; color: var(--text-1); }
.modal-subtitle { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.modal-x { color: var(--text-3); padding: 8px; border-radius: 8px; transition: all 0.2s; background: none; border: none; cursor: pointer; }
.modal-x:hover { background: var(--surface-2); color: var(--text-1); }

.modal-tabs { display: flex; gap: 4px; padding: 0 24px; border-bottom: 1px solid var(--border-1); background: var(--surface-2); }
.modal-tab { padding: 12px 20px; font-size: 13px; font-weight: 700; color: var(--text-3); border-bottom: 2px solid transparent; transition: all 0.2s; position: relative; top: 1px; background: none; border: none; cursor: pointer; }
.modal-tab:hover { color: var(--text-2); }
.modal-tab.active { color: var(--accent); border-bottom-color: var(--accent); }

.modal-body { padding: 24px; overflow-y: auto; display: none; flex: 1; }
.modal-body.active { display: block; }

.modal-foot { padding: 16px 24px; border-top: 1px solid var(--border-1); background: var(--surface-2); display: flex; align-items: center; justify-content: flex-end; gap: 12px; }

/* Blocks in body */
.drawer-section { margin-bottom: 24px; }
.drawer-section-title {
    font-family: 'DM Mono', monospace; font-size: 9.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; color: var(--text-3);
    margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
}
.drawer-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border-1); }

.drawer-cust {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px; background: var(--surface-2); border: 1px solid var(--border-1);
    border-radius: var(--radius-md);
}
.drawer-cust img { width: 40px; height: 40px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--border-2); }
.drawer-cust-nm { font-size: 13px; font-weight: 700; color: var(--text-1); }
.drawer-cust-em { font-size: 11px; color: var(--text-3); margin-top: 2px; }

.meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.meta-item { padding: 12px 14px; background: var(--surface-2); border: 1px solid var(--border-1); border-radius: var(--radius-md); }
.meta-label { font-family: 'DM Mono', monospace; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-3); margin-bottom: 4px; }
.meta-val { font-size: 13px; font-weight: 600; color: var(--text-1); }

.order-item-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-1); }
.order-item-row:last-child { border-bottom: none; }
.order-item-thumb { width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--surface-2); border: 1px solid var(--border-1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
.order-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.order-item-thumb svg { width: 16px; height: 16px; color: var(--text-3); }
.order-item-name { font-size: 12.5px; font-weight: 600; color: var(--text-1); line-height: 1.3; }
.order-item-qty { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--text-3); margin-top: 2px; }
.order-item-price { font-size: 13px; font-weight: 700; color: var(--text-1); margin-left: auto; flex-shrink: 0; }

.totals-block { background: var(--surface-2); border: 1px solid var(--border-1); border-radius: var(--radius-md); overflow: hidden; }
.totals-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; border-bottom: 1px solid var(--border-1); font-size: 12px; color: var(--text-2); }
.totals-row.total { font-size: 15px; font-weight: 800; color: var(--text-1); background: var(--surface-3); border-color: var(--border-2); }
.totals-row span:last-child { font-weight: 700; color: var(--text-1); }
.totals-row.total span:last-child { color: var(--accent); font-size: 18px; }

.status-update-row { display: flex; align-items: center; gap: 10px; padding: 14px 16px; background: var(--surface-2); border: 1px solid var(--border-1); border-radius: var(--radius-md); }
.status-update-label { font-size: 12px; font-weight: 600; color: var(--text-1); flex: 1; }
.drawer-status-select { background: var(--surface-3); border: 1px solid var(--border-2); border-radius: var(--radius-sm); padding: 7px 12px; color: var(--text-1); font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; transition: border-color var(--transition); }
.drawer-status-select:focus { outline: none; border-color: var(--blue); }

.btn-drawer-save { background: var(--blue); color: #fff; border: none; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(79,114,245,.25); transition: 0.2s; }
.btn-drawer-cancel { background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border-1); padding: 10px 16px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: 0.2s; }
.btn-drawer-save:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-drawer-cancel:hover { background: var(--surface-3); color: var(--text-1); }

/* Spinner */
.spinner { width: 13px; height: 13px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; display: inline-block; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Toast */
.toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 2000;
    display: flex; align-items: center; gap: 12px; padding: 14px 20px;
    background: var(--surface-1); border: 1px solid var(--border-2);
    border-radius: var(--radius-lg); box-shadow: 0 12px 32px rgba(0,0,0,0.25);
    transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
    pointer-events: none; min-width: 280px;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast-dot { width: 8px; height: 8px; border-radius: 50%; }
.toast.success .toast-dot { background: var(--green); }
.toast.error .toast-dot { background: var(--red); }

/* Responsive */
@media(max-width:700px) { thead th:nth-child(4), tbody td:nth-child(4) { display:none; } }
@media(max-width:520px) { thead th:nth-child(3), tbody td:nth-child(3) { display:none; } }
</style>
@endpush

@section('content')
<div class="orders-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>@lang('admin.order')</h1>
            <p>
                <span class="live-dot"></span>
                {{ $orders->total() }} @lang('admin.total')
                &nbsp;·&nbsp; @lang('admin.updated_just_now')
            </p>
        </div>
    </div>

    {{-- ── KPI Row ── --}}
    <div class="kpi-row">
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico" style="background:var(--accent-dim);color:var(--accent)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.total')</div>
            <div class="kpi-val">{{ number_format($orders->total()) }}</div>
            <div class="kpi-sub">@lang('admin.all_time')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico" style="background:var(--green-dim);color:var(--green)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.completed')</div>
            <div class="kpi-val">{{ number_format($stats['completed'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.fulfilled')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico" style="background:var(--amber-dim);color:var(--amber)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.pending')</div>
            <div class="kpi-val">{{ number_format($stats['pending'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.awaiting_action')</div>
        </div>

        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico" style="background:var(--violet-dim);color:var(--violet)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.total_revenue')</div>
            <div class="kpi-val">${{ number_format($stats['revenue'] ?? 0, 0) }}</div>
            <div class="kpi-sub">@lang('admin.gross')</div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        {{-- Search --}}
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="order-search" placeholder="@lang('admin.search_orders')" autocomplete="off">
        </div>

        {{-- Status filter --}}
        <select class="filter-select" id="status-filter">
            <option value="">@lang('admin.all_statuses')</option>
            <option value="pending">@lang('admin.status_pending')</option>
            <option value="processing">@lang('admin.status_processing')</option>
            <option value="shipped">@lang('admin.status_shipped')</option>
            <option value="completed">@lang('admin.status_completed')</option>
            <option value="cancelled">@lang('admin.status_cancelled')</option>
        </select>

        {{-- Date range --}}
        <div class="date-range">
            <input type="date" id="date-from" title="@lang('admin.from')">
            <span class="date-sep">→</span>
            <input type="date" id="date-to" title="@lang('admin.to')">
        </div>

        <div class="toolbar-sep"></div>

        {{-- Export --}}
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
                        <th class="sortable sorted" data-col="id">
                            @lang('admin.order') <span class="sort-icon">↓</span>
                        </th>
                        <th>@lang('admin.customer')</th>
                        <th>@lang('admin.status')</th>
                        <th class="sortable" data-col="date">
                            @lang('admin.date') <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="amount" style="text-align:right">
                            @lang('admin.amount') <span class="sort-icon">↕</span>
                        </th>
                        <th style="text-align:right">@lang('admin.actions')</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    @forelse ($orders as $order)
                    @php
                        $smap = [
                            'pending'    => 's-pending',
                            'processing' => 's-processing',
                            'shipped'    => 's-shipped',
                            'completed'  => 's-completed',
                            'cancelled'  => 's-cancelled',
                        ];
                        $sc = $smap[$order->status] ?? 's-pending';
                    @endphp
                    <tr id="order-row-{{ $order->id }}"
                        data-id="{{ $order->id }}"
                        data-status="{{ $order->status }}"
                        data-date="{{ $order->created_at->format('Y-m-d') }}"
                        onclick="openDrawer({{ $order->id }})">
                        <td onclick="event.stopPropagation()">
                            <span class="order-num">#{{ $order->order_number }}</span>
                        </td>
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
                            <span class="s-pill {{ $sc }}">
                                @lang('admin.status_' . $order->status)
                            </span>
                        </td>
                        <td>
                            <div class="order-date">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="order-time">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td style="text-align:right">
                            <span class="order-amt">${{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="action-group">
                                <button class="btn-icon open-order-btn" data-id="{{ $order->id }}" title="@lang('admin.view_details')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div class="empty-title">@lang('admin.no_orders_found')</div>
                                <div class="empty-sub">@lang('admin.orders_will_appear_here')</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                @lang('admin.showing') <b>{{ $orders->firstItem() }}</b> @lang('admin.to') <b>{{ $orders->lastItem() }}</b> @lang('admin.of') <b>{{ $orders->total() }}</b> @lang('admin.results')
            </div>
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════
     ORDER DETAIL DRAWER
══════════════════════════════════════ --}}
<div id="drawer-overlay" class="modal-overlay" onclick="closeDrawer()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon" id="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <div class="modal-title" id="drawer-order-num">@lang('admin.order_details')</div>
                    <div class="modal-subtitle" id="drawer-order-date"></div>
                </div>
            </div>
            <button class="modal-x" onclick="closeDrawer()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" onclick="switchTab(this,'tab-order-details')">@lang('admin.order_details')</button>
        </div>

        <div class="modal-body active" id="tab-order-details">
            <div id="drawer-body">
                {{-- Filled dynamically --}}
                <div style="display:flex;align-items:center;justify-content:center;height:200px;">
                    <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
                </div>
            </div>
        </div>

        <div class="modal-foot">
            <button class="btn-drawer-cancel" onclick="closeDrawer()">@lang('admin.close')</button>
            <button class="btn-drawer-save" id="drawer-save-btn" onclick="saveOrderStatus()">
                @lang('admin.update_status')
            </button>
        </div>
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
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', e => {
        const btn = e.target.closest('.open-order-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            openDrawer(btn.dataset.id);
        }
    });
});

/* ═══════════════════════════════════════
   STATE
═══════════════════════════════════════ */
let activeOrderId  = null;
let currentStatus  = null;

/* ═══════════════════════════════════════
   DRAWER
═══════════════════════════════════════ */
async function openDrawer(id) {
    activeOrderId = id;
    const overlay = document.getElementById('drawer-overlay');

    // Show loading
    document.getElementById('drawer-body').innerHTML = `
        <div style="display:flex;align-items:center;justify-content:center;height:200px;">
            <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
        </div>`;
    document.getElementById('drawer-order-num').textContent = '…';
    document.getElementById('drawer-order-date').textContent = '';

    // Reset tabs
    const firstTab = document.querySelector('.modal-tab');
    if (firstTab) switchTab(firstTab, 'tab-order-details');

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';

    try {
        const res = await fetch(`/admin/orders/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error();
        const order = await res.json();
        renderDrawer(order);
    } catch {
        document.getElementById('drawer-body').innerHTML = `
            <div class="empty-state" style="padding:40px">
                <div class="empty-title">@lang('admin.error_loading')</div>
            </div>`;
    }
}

function closeDrawer() {
    document.getElementById('drawer-overlay').classList.remove('open');
    document.body.style.overflow = '';
    activeOrderId = null;
}

function switchTab(btn, tabId) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.modal-body').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function handleOverlayClick(e) {
    if (e.target.id === 'drawer-overlay') closeDrawer();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

function renderDrawer(order) {
    currentStatus = order.status;

    document.getElementById('drawer-order-num').textContent = `#${order.order_number}`;
    document.getElementById('drawer-order-date').textContent = order.created_at_formatted || order.created_at;

    const statusOptions = ['pending','processing','shipped','completed','cancelled'];
    const statusLabels  = {
        pending:    '{{ __("admin.status_pending") }}',
        processing: '{{ __("admin.status_processing") }}',
        shipped:    '{{ __("admin.status_shipped") }}',
        completed:  '{{ __("admin.status_completed") }}',
        cancelled:  '{{ __("admin.status_cancelled") }}',
    };

    const selectOpts = statusOptions.map(s =>
        `<option value="${s}" ${s === order.status ? 'selected' : ''}>${statusLabels[s] || s}</option>`
    ).join('');

    const items = (order.order_items || order.items || []).map(item => `
        <div class="order-item-row">
            <div class="order-item-thumb">
                ${item.product?.image_url
                    ? `<img src="${item.product.image_url}" alt="">`
                    : `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`
                }
            </div>
            <div style="min-width:0;flex:1">
                <div class="order-item-name">${item.product?.name || item.name || '—'}</div>
                <div class="order-item-qty">× ${item.quantity}</div>
            </div>
            <div class="order-item-price">$${parseFloat(item.price * item.quantity).toFixed(2)}</div>
        </div>
    `).join('');

    const shipping  = parseFloat(order.shipping_amount  || 0);
    const discount  = parseFloat(order.discount_amount  || 0);
    const subtotal  = parseFloat(order.subtotal || order.total_amount) - shipping + discount;
    const total     = parseFloat(order.total_amount);

    document.getElementById('drawer-body').innerHTML = `
        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.customer')</div>
            <div class="drawer-cust">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(order.user?.name || 'G')}&background=1f2233&color=4f72f5&bold=true&size=80" alt="">
                <div>
                    <div class="drawer-cust-nm">${order.user?.name || '{{ __("admin.guest") }}'}</div>
                    <div class="drawer-cust-em">${order.user?.email || '—'}</div>
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.order_info')</div>
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.order_number')</div>
                    <div class="meta-val">#${order.order_number}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.payment')</div>
                    <div class="meta-val">${order.payment_method || '—'}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.date')</div>
                    <div class="meta-val">${order.created_at_formatted || order.created_at}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">@lang('admin.items')</div>
                    <div class="meta-val">${(order.order_items || order.items || []).length}</div>
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.items')</div>
            ${items || `<div style="font-size:12px;color:var(--text-3);padding:12px 0">@lang('admin.no_items')</div>`}
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.totals')</div>
            <div class="totals-block">
                <div class="totals-row">
                    <span>@lang('admin.subtotal')</span>
                    <span>$${subtotal.toFixed(2)}</span>
                </div>
                ${shipping > 0 ? `
                <div class="totals-row">
                    <span>@lang('admin.shipping')</span>
                    <span>$${shipping.toFixed(2)}</span>
                </div>` : ''}
                ${discount > 0 ? `
                <div class="totals-row">
                    <span>@lang('admin.discount')</span>
                    <span style="color:var(--green)">-$${discount.toFixed(2)}</span>
                </div>` : ''}
                <div class="totals-row total">
                    <span>@lang('admin.total')</span>
                    <span>$${total.toFixed(2)}</span>
                </div>
            </div>
        </div>

        <div class="drawer-section">
            <div class="drawer-section-title">@lang('admin.status')</div>
            <div class="status-update-row">
                <span class="status-update-label">@lang('admin.update_order_status')</span>
                <select class="drawer-status-select" id="drawer-status-select" onchange="currentStatus = this.value">
                    ${selectOpts}
                </select>
            </div>
        </div>
    `;
}

/* ═══════════════════════════════════════
   SAVE STATUS
═══════════════════════════════════════ */
async function saveOrderStatus() {
    if (!activeOrderId) return;
    const btn    = document.getElementById('drawer-save-btn');
    const select = document.getElementById('drawer-status-select');
    if (!select) return;

    const newStatus = select.value;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>';

    try {
        const res = await fetch(`/admin/orders/${activeOrderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Error');

        showToast(data.message || '{{ __("admin.status_updated") }}', 'success');

        // Update pill in table row
        const row = document.getElementById(`order-row-${activeOrderId}`);
        if (row) {
            const pill = row.querySelector('.s-pill');
            if (pill) {
                const smap = {
                    pending:'s-pending', processing:'s-processing',
                    shipped:'s-shipped', completed:'s-completed', cancelled:'s-cancelled'
                };
                const labels = {
                    pending:    '{{ __("admin.status_pending") }}',
                    processing: '{{ __("admin.status_processing") }}',
                    shipped:    '{{ __("admin.status_shipped") }}',
                    completed:  '{{ __("admin.status_completed") }}',
                    cancelled:  '{{ __("admin.status_cancelled") }}',
                };
                pill.className = 's-pill ' + (smap[newStatus] || 's-pending');
                pill.childNodes[pill.childNodes.length - 1].textContent = ' ' + (labels[newStatus] || newStatus);
            }
            row.dataset.status = newStatus;
        }
        closeDrawer();
    } catch (err) {
        showToast(err.message || '{{ __("admin.error_saving") }}', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '{{ __("admin.update_status") }}';
    }
}

/* ═══════════════════════════════════════
   LIVE SEARCH + FILTERS
═══════════════════════════════════════ */
function filterTable() {
    const term    = document.getElementById('order-search').value.toLowerCase();
    const status  = document.getElementById('status-filter').value;
    const dateFrom= document.getElementById('date-from').value;
    const dateTo  = document.getElementById('date-to').value;

    document.querySelectorAll('#orders-tbody tr[id^="order-row-"]').forEach(row => {
        const text    = row.innerText.toLowerCase();
        const rowStat = row.dataset.status || '';
        const rowDate = row.dataset.date   || '';

        const okTxt  = !term   || text.includes(term);
        const okStat = !status || rowStat === status;
        const okFrom = !dateFrom || rowDate >= dateFrom;
        const okTo   = !dateTo   || rowDate <= dateTo;

        row.style.display = (okTxt && okStat && okFrom && okTo) ? '' : 'none';
    });
}

['order-search','status-filter','date-from','date-to'].forEach(id =>
    document.getElementById(id)?.addEventListener('input', filterTable)
);
document.getElementById('status-filter')?.addEventListener('change', filterTable);

/* ═══════════════════════════════════════
   COLUMN SORT (client-side)
═══════════════════════════════════════ */
let sortState = { col: 'id', dir: 'desc' };
document.querySelectorAll('thead th.sortable').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.dataset.col;
        if (sortState.col === col) {
            sortState.dir = sortState.dir === 'asc' ? 'desc' : 'asc';
        } else {
            sortState.col = col;
            sortState.dir = 'asc';
        }
        document.querySelectorAll('thead th.sortable').forEach(h => {
            h.classList.remove('sorted');
            h.querySelector('.sort-icon').textContent = '↕';
        });
        th.classList.add('sorted');
        th.querySelector('.sort-icon').textContent = sortState.dir === 'asc' ? '↑' : '↓';
        sortRows(col, sortState.dir);
    });
});

function sortRows(col, dir) {
    const tbody = document.getElementById('orders-tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr[id^="order-row-"]'));

    rows.sort((a, b) => {
        let av = '', bv = '';
        if (col === 'id')     { av = a.id; bv = b.id; }
        if (col === 'date')   { av = a.dataset.date || ''; bv = b.dataset.date || ''; }
        if (col === 'amount') {
            const getAmt = r => {
                const el = r.querySelector('.order-amt');
                return el ? parseFloat(el.textContent.replace(/[^0-9.]/g,'')) : 0;
            };
            av = getAmt(a); bv = getAmt(b);
            return dir === 'asc' ? av - bv : bv - av;
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
    const rows = document.querySelectorAll('#orders-tbody tr[id^="order-row-"]:not([style*="display: none"])');
    let csv = '@lang('admin.order'),@lang('admin.customer'),@lang('admin.status'),@lang('admin.date'),@lang('admin.amount')\n';

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const order  = cells[0]?.textContent.trim().replace(/\s+/g,' ');
        const cust   = cells[1]?.querySelector('.cust-nm')?.textContent.trim() || '';
        const status = cells[2]?.textContent.trim().replace(/\s+/g,' ');
        const date   = cells[3]?.textContent.trim().replace(/\s+/g,' ');
        const amt    = cells[4]?.textContent.trim().replace(/\s+/g,' ');
        csv += `"${order}","${cust}","${status}","${date}","${amt}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `orders-${new Date().toISOString().slice(0,10)}.csv`;
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