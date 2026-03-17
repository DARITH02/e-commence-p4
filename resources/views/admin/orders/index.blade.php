@extends('layouts.admin')

@section('title', __('admin.orders'))
@section('page_title', __('admin.orders'))

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
    --surface-0:  #f0f2f7; --surface-1:  #ffffff;
    --surface-2:  #f7f8fc; --surface-3:  #eef0f6;
    --border-1:   rgba(0,0,0,0.07); --border-2: rgba(0,0,0,0.13);
    --text-1:     #0f1117;  --text-2:    #4a5068; --text-3: #9aa0b8;
    --blue:       #2d52e0;  --blue-dim:  rgba(45,82,224,0.08);
    --blue-mid:   rgba(45,82,224,0.16);
    --green:      #059669;  --green-dim: rgba(5,150,105,0.08);
    --amber:      #b45309;  --amber-dim: rgba(180,83,9,0.08);
    --red:        #c92b2b;  --red-dim:   rgba(201,43,43,0.08);
    --violet:     #6d28d9;  --violet-dim:rgba(109,40,217,0.08);
    --shadow-card:0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}
body { background: var(--surface-0) !important; }
.content-inner { max-width: none !important; }

/* ─── Page animation ─── */
.orders-page {
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
    justify-content: space-between; gap:16px; flex-wrap:wrap;
}
.page-heading { display:flex; flex-direction:column; gap:4px; }
.page-heading h1 {
    font-size:22px; font-weight:700; color:var(--text-1);
    letter-spacing:-0.02em; line-height:1;
}
.page-heading p {
    font-family:'DM Mono',monospace; font-size:10px;
    color:var(--text-3); letter-spacing:0.06em;
    display:flex; align-items:center; gap:6px;
}
.live-dot {
    width:6px; height:6px; border-radius:50%; background:var(--green);
    animation:livePulse 2.2s ease infinite;
}
@keyframes livePulse {
    0%   { box-shadow:0 0 0 0 rgba(31,186,114,0.5); }
    70%  { box-shadow:0 0 0 6px rgba(31,186,114,0); }
    100% { box-shadow:0 0 0 0 rgba(31,186,114,0); }
}

/* ─── KPI row ─── */
.kpi-row {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
}
@media(max-width:900px){ .kpi-row{grid-template-columns:repeat(2,1fr);} }
@media(max-width:520px){ .kpi-row{grid-template-columns:1fr;} }

.kpi {
    background:var(--surface-1); border:1px solid var(--border-1);
    border-radius:var(--radius-lg); padding:18px 20px;
    display:flex; flex-direction:column; gap:10px;
    box-shadow:var(--shadow-card);
    transition:box-shadow var(--transition), border-color var(--transition), transform var(--transition);
    position:relative; overflow:hidden;
}
.kpi:hover { box-shadow:var(--shadow-hover); transform:translateY(-2px); }
.kpi-head  { display:flex; align-items:center; justify-content:space-between; }
.kpi-ico {
    width:34px; height:34px; border-radius:var(--radius-sm);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
    transition:transform var(--transition);
}
.kpi:hover .kpi-ico { transform:scale(1.08); }
.kpi-ico svg { width:15px; height:15px; }
.c-blue   { background:var(--blue-dim);   color:var(--blue); }
.c-green  { background:var(--green-dim);  color:var(--green); }
.c-amber  { background:var(--amber-dim);  color:var(--amber); }
.c-red    { background:var(--red-dim);    color:var(--red); }
.c-violet { background:var(--violet-dim); color:var(--violet); }

.kpi-label {
    font-family:'DM Mono',monospace; font-size:9.5px; font-weight:700;
    text-transform:uppercase; letter-spacing:0.09em; color:var(--text-3);
}
.kpi-val {
    font-size:22px; font-weight:700; color:var(--text-1);
    letter-spacing:-0.02em; line-height:1;
}
.kpi-sub { font-size:10.5px; color:var(--text-2); }

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
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:100px;
    font-size:11px; font-weight:600;
    border:1px solid transparent; white-space:nowrap;
}
.s-pill::before {
    content:''; width:5px; height:5px;
    border-radius:50%; background:currentColor; flex-shrink:0;
}
.s-pending    { background:var(--amber-dim);  color:var(--amber);  border-color:rgba(232,160,0,0.2); }
.s-processing { background:var(--blue-dim);   color:var(--blue);   border-color:rgba(79,114,245,0.2); }
.s-shipped    { background:var(--violet-dim); color:var(--violet); border-color:rgba(139,92,246,0.2); }
.s-completed  { background:var(--green-dim);  color:var(--green);  border-color:rgba(31,186,114,0.2); }
.s-cancelled  { background:var(--red-dim);    color:var(--red);    border-color:rgba(232,69,69,0.2); }

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
    padding:16px 20px; border-top:1px solid var(--border-1);
    background:var(--surface-2);
}
.pagination-wrap .pagination {
    display:flex; align-items:center; gap:4px; flex-wrap:wrap;
}
.pagination-wrap .pagination li a,
.pagination-wrap .pagination li span {
    display:flex; align-items:center; justify-content:center;
    min-width:32px; height:32px; padding:0 8px;
    border-radius:var(--radius-sm);
    font-family:'DM Mono',monospace; font-size:11px; font-weight:700;
    color:var(--text-2); background:var(--surface-1);
    border:1px solid var(--border-1); text-decoration:none;
    transition:all var(--transition);
}
.pagination-wrap .pagination li a:hover {
    background:var(--blue-dim); border-color:rgba(79,114,245,0.25); color:var(--blue);
}
.pagination-wrap .pagination li.active span {
    background:var(--blue); border-color:var(--blue); color:#fff;
}
.pagination-wrap .pagination li.disabled span { opacity:.3; cursor:not-allowed; }

/* ═══════════════════════════════════════════
   ORDER DETAIL DRAWER
═══════════════════════════════════════════ */
.drawer-overlay {
    position:fixed; inset:0;
    background:rgba(0,0,0,0.55);
    backdrop-filter:blur(4px);
    z-index:1000;
    opacity:0; pointer-events:none;
    transition:opacity .25s ease;
}
.drawer-overlay.open { opacity:1; pointer-events:all; }

.drawer {
    position:fixed; top:0; right:0; bottom:0;
    width:100%; max-width:480px;
    background:var(--surface-1);
    border-left:1px solid var(--border-2);
    box-shadow:-16px 0 48px rgba(0,0,0,.4);
    z-index:1001;
    display:flex; flex-direction:column;
    transform:translateX(100%);
    transition:transform .28s cubic-bezier(0.16,1,0.3,1);
    overflow:hidden;
}
.drawer.open { transform:translateX(0); }

.drawer-header {
    padding:20px 24px;
    border-bottom:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:space-between;
    flex-shrink:0; background:var(--surface-2);
}
.drawer-title { font-size:15px; font-weight:700; color:var(--text-1); letter-spacing:-.01em; }
.drawer-close {
    width:32px; height:32px; border-radius:var(--radius-sm);
    background:var(--surface-1); border:1px solid var(--border-1);
    color:var(--text-2); display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:all var(--transition);
}
.drawer-close:hover { background:var(--red-dim); border-color:rgba(232,69,69,.25); color:var(--red); transform:rotate(90deg); }
.drawer-close svg { width:14px; height:14px; }

.drawer-body {
    flex:1; overflow-y:auto; padding:24px;
    scrollbar-width:thin; scrollbar-color:var(--border-2) transparent;
}
.drawer-section { margin-bottom:24px; }
.drawer-section-title {
    font-family:'DM Mono',monospace; font-size:9.5px; font-weight:700;
    text-transform:uppercase; letter-spacing:.1em; color:var(--text-3);
    margin-bottom:12px; display:flex; align-items:center; gap:8px;
}
.drawer-section-title::after {
    content:''; flex:1; height:1px; background:var(--border-1);
}

/* Customer card in drawer */
.drawer-cust {
    display:flex; align-items:center; gap:12px;
    padding:14px 16px;
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md);
}
.drawer-cust img {
    width:40px; height:40px; border-radius:var(--radius-sm);
    object-fit:cover; border:1px solid var(--border-2);
}
.drawer-cust-nm  { font-size:13px; font-weight:700; color:var(--text-1); }
.drawer-cust-em  { font-size:11px; color:var(--text-3); margin-top:2px; }

/* Meta grid */
.meta-grid {
    display:grid; grid-template-columns:1fr 1fr; gap:10px;
}
.meta-item {
    padding:12px 14px;
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md);
}
.meta-label {
    font-family:'DM Mono',monospace; font-size:9px; font-weight:700;
    text-transform:uppercase; letter-spacing:.08em; color:var(--text-3);
    margin-bottom:4px;
}
.meta-val { font-size:13px; font-weight:600; color:var(--text-1); }

/* Order items in drawer */
.order-item-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 0;
    border-bottom:1px solid var(--border-1);
}
.order-item-row:last-child { border-bottom:none; }
.order-item-thumb {
    width:40px; height:40px; border-radius:var(--radius-sm);
    background:var(--surface-2); border:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; overflow:hidden;
}
.order-item-thumb img { width:100%; height:100%; object-fit:cover; }
.order-item-thumb svg { width:16px; height:16px; color:var(--text-3); }
.order-item-name { font-size:12.5px; font-weight:600; color:var(--text-1); line-height:1.3; }
.order-item-qty  { font-family:'DM Mono',monospace; font-size:10px; color:var(--text-3); margin-top:2px; }
.order-item-price { font-size:13px; font-weight:700; color:var(--text-1); margin-left:auto; flex-shrink:0; }

/* Totals */
.totals-block {
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md); overflow:hidden;
}
.totals-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:10px 16px; border-bottom:1px solid var(--border-1);
    font-size:12px; color:var(--text-2);
}
.totals-row:last-child { border-bottom:none; }
.totals-row.total {
    font-size:14px; font-weight:700; color:var(--text-1);
    background:var(--surface-3);
}
.totals-row span:last-child { font-weight:600; color:var(--text-1); }
.totals-row.total span:last-child { color:var(--blue); font-size:16px; }

/* Status update in drawer */
.status-update-row {
    display:flex; align-items:center; gap:10px;
    padding:14px 16px;
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md);
}
.status-update-label { font-size:12px; font-weight:600; color:var(--text-1); flex:1; }
.drawer-status-select {
    background:var(--surface-3); border:1px solid var(--border-2);
    border-radius:var(--radius-sm); padding:7px 12px;
    color:var(--text-1); font-family:'DM Sans',sans-serif;
    font-size:12px; font-weight:600; cursor:pointer;
    transition:border-color var(--transition);
}
.drawer-status-select:focus { outline:none; border-color:var(--blue); }

.drawer-footer {
    padding:16px 24px; border-top:1px solid var(--border-1);
    display:flex; gap:10px; flex-shrink:0; background:var(--surface-2);
}
.btn-drawer-save {
    flex:1; padding:10px; border-radius:var(--radius-md);
    background:var(--blue); color:#fff; border:none;
    font-family:'DM Sans',sans-serif; font-size:12px; font-weight:700;
    cursor:pointer; transition:all var(--transition);
    box-shadow:0 4px 12px rgba(79,114,245,.25);
}
.btn-drawer-save:hover { background:#3d5de0; transform:translateY(-1px); }
.btn-drawer-save:disabled { opacity:.5; pointer-events:none; }
.btn-drawer-cancel {
    padding:10px 16px; border-radius:var(--radius-md);
    background:transparent; border:1px solid var(--border-1);
    color:var(--text-2); font-family:'DM Sans',sans-serif;
    font-size:12px; font-weight:600; cursor:pointer;
    transition:all var(--transition);
}
.btn-drawer-cancel:hover { background:var(--surface-3); color:var(--text-1); }

/* Spinner */
.spinner {
    width:13px; height:13px;
    border:2px solid rgba(255,255,255,.3);
    border-top-color:#fff; border-radius:50%;
    animation:spin .6s linear infinite; display:inline-block; vertical-align:middle;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* Toast */
.toast {
    position:fixed; bottom:24px; right:24px; z-index:2000;
    display:flex; align-items:center; gap:10px; padding:12px 18px;
    background:var(--surface-1); border:1px solid var(--border-2);
    border-radius:var(--radius-md); box-shadow:0 8px 24px rgba(0,0,0,.4);
    font-size:13px; font-weight:600; color:var(--text-1);
    transform:translateY(12px); opacity:0;
    transition:all .25s cubic-bezier(0.16,1,0.3,1);
    pointer-events:none; max-width:320px;
}
.toast.show { transform:translateY(0); opacity:1; }
.toast-dot  { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.toast.success .toast-dot { background:var(--green); }
.toast.error   .toast-dot { background:var(--red); }

/* Responsive */
@media(max-width:700px) {
    thead th:nth-child(4), tbody td:nth-child(4) { display:none; }
}
@media(max-width:520px) {
    thead th:nth-child(3), tbody td:nth-child(3) { display:none; }
    .drawer { max-width:100%; }
}
</style>
@endpush

@section('content')
<div class="orders-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>@lang('admin.orders')</h1>
            <p>
                <span class="live-dot"></span>
                {{ $orders->total() }} @lang('admin.total_orders')
                &nbsp;·&nbsp; @lang('admin.updated_just_now')
            </p>
        </div>
    </div>

    {{-- ── KPI Row ── --}}
    <div class="kpi-row">
        {{-- Total orders --}}
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.total_orders')</div>
            <div class="kpi-val">{{ number_format($orders->total()) }}</div>
            <div class="kpi-sub">@lang('admin.all_time')</div>
        </div>

        {{-- Completed --}}
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.completed')</div>
            <div class="kpi-val">{{ number_format($stats['completed'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.fulfilled')</div>
        </div>

        {{-- Pending --}}
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="kpi-label">@lang('admin.pending')</div>
            <div class="kpi-val">{{ number_format($stats['pending'] ?? 0) }}</div>
            <div class="kpi-sub">@lang('admin.awaiting_action')</div>
        </div>

        {{-- Revenue --}}
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-violet">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
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
                    @forelse($orders as $order)
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
                                <button class="btn-icon" onclick="openDrawer({{ $order->id }})" title="@lang('admin.view_details')">
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
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════
     ORDER DETAIL DRAWER
══════════════════════════════════════ --}}
<div id="drawer-overlay" class="drawer-overlay" onclick="closeDrawer()"></div>

<div id="order-drawer" class="drawer">
    <div class="drawer-header">
        <div>
            <div class="drawer-title" id="drawer-order-num">@lang('admin.order_details')</div>
            <div style="font-family:'DM Mono',monospace;font-size:9.5px;color:var(--text-3);margin-top:3px;text-transform:uppercase;letter-spacing:0.08em;" id="drawer-order-date"></div>
        </div>
        <button class="drawer-close" onclick="closeDrawer()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="drawer-body" id="drawer-body">
        {{-- Filled dynamically --}}
        <div style="display:flex;align-items:center;justify-content:center;height:200px;">
            <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
        </div>
    </div>

    <div class="drawer-footer">
        <button class="btn-drawer-cancel" onclick="closeDrawer()">@lang('admin.close')</button>
        <button class="btn-drawer-save" id="drawer-save-btn" onclick="saveOrderStatus()">
            @lang('admin.update_status')
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
let activeOrderId  = null;
let currentStatus  = null;

/* ═══════════════════════════════════════
   DRAWER
═══════════════════════════════════════ */
async function openDrawer(id) {
    activeOrderId = id;
    const overlay = document.getElementById('drawer-overlay');
    const drawer  = document.getElementById('order-drawer');

    // Show loading
    document.getElementById('drawer-body').innerHTML = `
        <div style="display:flex;align-items:center;justify-content:center;height:200px;">
            <div class="spinner" style="width:24px;height:24px;border-width:3px;border-color:var(--border-2);border-top-color:var(--blue);"></div>
        </div>`;
    document.getElementById('drawer-order-num').textContent = '…';
    document.getElementById('drawer-order-date').textContent = '';

    overlay.classList.add('open');
    drawer.classList.add('open');
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
    document.getElementById('order-drawer').classList.remove('open');
    document.body.style.overflow = '';
    activeOrderId = null;
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
            const getAmt = r => parseFloat(r.querySelector('.order-amt')?.textContent.replace(/[^0-9.]/g,'') || 0);
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