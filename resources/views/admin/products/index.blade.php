@extends('layouts.admin')

@section('title', __('admin.product'))
@section('page_title', __('admin.product'))

@push('styles')
<style>

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg) !important; font-family: 'DM Sans', system-ui, sans-serif; color: var(--text); }
.content-inner { max-width: none !important; padding: 0 !important; }
button { font-family: inherit; cursor: pointer; border: none; background: none; }
input, select, textarea { font-family: inherit; outline: none; }
input::placeholder, textarea::placeholder { color: var(--text-3); }

/* ── Page Header ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 8px;
}
.page-header-left h1 {
    font-size: 28px;
    font-weight: 850;
    color: var(--text);
    letter-spacing: -0.04em;
    line-height: 1.1;
}
.page-header-left p {
    font-size: 13px;
    color: var(--text-3);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}
.page-header-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ── Toolbar ── */
.toolbar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
}
.search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
}
.search-wrap svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: var(--text-3);
    pointer-events: none;
}
.search-wrap input {
    width: 100%;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 10px 16px 10px 42px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text);
    transition: all 0.2s;
}
.search-wrap input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 4px var(--accent-dim);
    background: var(--surface);
}

.filter-select {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-2);
    cursor: pointer;
    transition: all 0.2s;
    min-width: 140px;
}
.filter-select:hover {
    border-color: var(--border-2);
    color: var(--text);
}

.toolbar-sep {
    width: 1px;
    height: 24px;
    background: var(--border);
    margin: 0 4px;
}

.filter-tabs {
    display: flex;
    padding: 4px;
    background: var(--bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
}
.filter-chip {
    padding: 6px 14px;
    border-radius: calc(var(--radius) - 2px);
    font-size: 12px;
    font-weight: 700;
    color: var(--text-3);
    transition: all 0.2s;
}
.filter-chip.active {
    background: var(--surface);
    color: var(--accent);
    box-shadow: var(--shadow-sm);
}
.filter-chip:hover:not(.active) {
    color: var(--text-2);
}

.toolbar-results {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    margin-left: auto;
    padding-right: 8px;
}

/* ── Specific Button Overrides ── */
.btn-primary {
    background: var(--accent);
    color: #fff;
    padding: 10px 20px;
    border-radius: var(--radius);
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(79,110,247,0.25);
    transition: all 0.2s;
}
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(79,110,247,0.35);
}
.btn-ghost {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-2);
    padding: 9px 16px;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.btn-ghost:hover {
    border-color: var(--border-2);
    background: var(--surface-2);
    color: var(--text);
}

/* ── Table Actions ── */
.action-group {
    display: flex;
    justify-content: flex-end;
    gap: 6px;
}
.btn-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: var(--text-3);
    border: 1px solid transparent;
    transition: all 0.25s;
    background: transparent;
}
.btn-icon svg { width: 15px; height: 15px; }
.btn-icon:hover {
    background: var(--surface-2);
    color: var(--accent);
    border-color: var(--border);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}
.btn-icon.delete:hover {
    color: var(--red);
    background: var(--red-dim);
    border-color: rgba(240,71,71,0.1);
}
.btn-icon:active { transform: scale(0.92); }

/* Stats Card */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
@media (max-width: 800px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 460px) { .stats-row { grid-template-columns: 1fr; } }
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow);
    transition: border-color var(--transition), transform var(--transition);
}
.stat-card:hover { border-color: var(--border-2); transform: translateY(-1px); }
.stat-ico { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-ico svg { width: 18px; height: 18px; }
.stat-ico.blue   { background: var(--accent-dim); color: var(--accent); }
.stat-ico.green  { background: var(--green-dim);  color: var(--green);  }
.stat-ico.amber  { background: var(--amber-dim);  color: var(--amber);  }
.stat-ico.red    { background: var(--red-dim);    color: var(--red);    }
.stat-label { font-size: 10px; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.08em; font-family: 'DM Mono', monospace; }
.stat-val   { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; line-height: 1.1; }

/* Table */
.table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow); }
.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
    padding: 14px 20px; text-align: left; font-family: 'DM Mono', monospace; font-size: 10px;
    font-weight: 850; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-3);
    background: var(--surface-2); border-bottom: 1px solid var(--border); white-space: nowrap;
}
thead th:last-child { text-align: right; }
tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
tbody tr:hover { background: var(--surface-2); }
tbody td { padding: 14px 20px; vertical-align: middle; }

/* Product Row Specifics */
.prod-cell { display: flex; align-items: center; gap: 12px; }
.prod-thumb {
    width: 44px; height: 44px; border-radius: 12px; border: 1px solid var(--border);
    background: var(--surface-3); display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
}
.prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
.prod-name { font-size: 14px; font-weight: 800; color: var(--text); line-height: 1.1; }
.prod-sku  { font-family: 'DM Mono', monospace; font-size: 11px; color: var(--text-3); margin-top: 4px; }
.cat-pills { display: flex; flex-wrap: wrap; gap: 6px; }
.cat-pill { 
    display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; 
    font-size: 11px; font-weight: 750; background: var(--surface-2); border: 1px solid var(--border); 
    color: var(--text-2); transition: all 0.2s; cursor: default;
}
.cat-pill:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-dim); transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.price-main { font-size: 14.5px; font-weight: 850; color: var(--text); }
.price-sale { font-size: 11.5px; font-weight: 800; color: #fff; background: var(--green); padding: 2px 10px; border-radius: 100px; display: inline-block; margin-top: 4px; box-shadow: 0 4px 12px rgba(34,201,122,0.25); }
.price-was { font-size: 11px; color: var(--text-3); text-decoration: line-through; }

/* Status */
.status-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 100px; border: 1px solid transparent; white-space: nowrap; }
.status-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
.status-active   { background: var(--green-dim); color: var(--green); border-color: rgba(34,201,122,0.2); }
.status-inactive { background: var(--surface-3); color: var(--text-3); border-color: var(--border); }

/* Toast */
.toast {
    position:fixed; bottom:24px; right:24px; z-index:2000;
    display:flex; align-items:center; gap:12px; padding:14px 20px;
    background:var(--surface); border:1px solid var(--border-2);
    border-radius:var(--radius-lg); box-shadow:0 12px 32px rgba(0,0,0,0.25);
    transform:translateY(20px); opacity:0; transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
    pointer-events:none; min-width:280px;
}
.toast.show { transform:translateY(0); opacity:1; }
.toast-dot { width:8px; height:8px; border-radius:50%; }
.toast.success .toast-dot { background:var(--green); }
.toast.error .toast-dot { background:var(--red); }
/* ── Modals ── */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.65);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 1000; display: none; align-items: center; justify-content: center;
    padding: 24px; opacity: 0; transition: opacity 0.3s;
}
.modal-overlay.open { display: flex; opacity: 1; }
.modal-container {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-xl); width: 100%; max-width: 720px;
    max-height: 90vh; display: flex; flex-direction: column;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
    overflow: hidden;
}
.modal-overlay.open .modal-container { transform: translateY(0); }

.modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-head-left { display: flex; align-items: center; gap: 14px; }
.modal-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--accent-dim); color: var(--accent); display: flex; align-items: center; justify-content: center; }
.modal-title { font-size: 16px; font-weight: 800; color: var(--text); }
.modal-subtitle { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.modal-x { color: var(--text-3); padding: 8px; border-radius: 8px; transition: all 0.2s; }
.modal-x:hover { background: var(--surface-2); color: var(--text); }

.modal-tabs { display: flex; gap: 4px; padding: 0 24px; border-bottom: 1px solid var(--border); background: var(--surface-2); }
.modal-tab { padding: 12px 20px; font-size: 13px; font-weight: 700; color: var(--text-3); border-bottom: 2px solid transparent; transition: all 0.2s; position: relative; top: 1px; }
.modal-tab:hover { color: var(--text-2); }
.modal-tab.active { color: var(--accent); border-bottom-color: var(--accent); }

.modal-body { padding: 24px; overflow-y: auto; display: none; }
.modal-body.active { display: block; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.form-row.single { grid-template-columns: 1fr; }
.field { display: flex; flex-direction: column; gap: 8px; }
.field label { font-size: 11px; font-weight: 800; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; }
.field input, .field select, .field textarea { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 13.5px; color: var(--text); transition: all 0.2s; width: 100%; }
.field input:focus { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-dim); background: var(--surface); }
.req { color: var(--red); }
.hint { font-size: 11px; color: var(--text-3); margin-top: 4px; }

.toggle-row { display: flex; align-items: center; gap: 16px; background: var(--bg); border: 1px solid var(--border); padding: 10px 14px; border-radius: 12px; }
.toggle-info { flex: 1; display: flex; flex-direction: column; }
.toggle-info strong { font-size: 12.5px; color: var(--text); }
.toggle-info span { font-size: 10.5px; color: var(--text-3); }
.toggle { position: relative; width: 38px; height: 20px; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-track { position: absolute; inset: 0; background: var(--surface-3); border-radius: 100px; cursor: pointer; transition: 0.2s; }
.toggle-thumb { position: absolute; top: 3px; left: 3px; width: 14px; height: 14px; background: #fff; border-radius: 50%; box-shadow: var(--shadow-sm); transition: 0.2s; }
.toggle input:checked + .toggle-track { background: var(--green); }
.toggle input:checked + .toggle-track .toggle-thumb { transform: translateX(18px); }

.section-head { display: flex; align-items: center; gap: 12px; margin: 24px 0 16px; }
.section-head span { font-size: 12px; font-weight: 850; color: var(--text); text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap; }
.section-line { height: 1px; background: var(--border); flex: 1; }

.modal-foot { padding: 16px 24px; border-top: 1px solid var(--border); background: var(--surface-2); display: flex; align-items: center; justify-content: space-between; }
.modal-foot-left { font-size: 11px; color: var(--text-3); }
.modal-foot-right { display: flex; gap: 10px; }

/* ── Media Upload & Previews ── */
.upload-zone {
    border: 2px dashed var(--border-2); border-radius: 16px; padding: 32px;
    text-align: center; background: var(--surface-2); transition: all 0.3s;
    position: relative; cursor: pointer; overflow: hidden;
}
.upload-zone:hover, .upload-zone.drag-over { border-color: var(--accent); background: var(--accent-dim); }
.upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2; }
.upload-ico { 
    width: 64px; height: 64px; border-radius: 20px; background: var(--bg); color: var(--accent);
    display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1); 
}
.upload-ico svg { width: 32px; height: 32px; }
.upload-zone h4 { font-size: 15px; font-weight: 800; color: var(--text); margin-bottom: 6px; }
.upload-zone p { font-size: 12px; color: var(--text-3); margin-bottom: 20px; }
.upload-tag { 
    display: inline-block; padding: 8px 20px; border-radius: 100px; background: var(--surface-3);
    color: var(--text-2); font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.05em; border: 1px solid var(--border); transition: all 0.2s;
}
.upload-zone:hover .upload-tag { background: var(--accent); color: #fff; border-color: var(--accent); }

.img-preview-grid { 
    display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); 
    gap: 12px; margin-top: 24px; 
}
.img-preview {
    aspect-ratio: 1; border-radius: 12px; overflow: hidden; position: relative;
    border: 1px solid var(--border); background: var(--surface-3);
    animation: zoomIn 0.3s cubic-bezier(0.16,1,0.3,1);
}
.img-preview.removing { transform: scale(0.8); opacity: 0; transition: 0.3s; }
.img-preview img { width: 100%; height: 100%; object-fit: cover; }
.img-preview.existing { border-color: var(--border-2); }
.img-preview .rm-img {
    position: absolute; top: 6px; right: 6px; width: 26px; height: 26px;
    border-radius: 8px; background: rgba(0,0,0,0.6); color: #fff;
    backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center;
    font-size: 12px; opacity: 0; transform: translateY(-5px); transition: all 0.2s;
    z-index: 5;
}
.img-preview:hover .rm-img { opacity: 1; transform: translateY(0); }
.img-preview .rm-img:hover { background: var(--red); transform: scale(1.1); }

@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.9) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

/* ── Delete Modal Specific ── */
.confirm-body { padding: 40px 32px; text-align: center; }
.danger-ico { width: 56px; height: 56px; border-radius: 16px; background: var(--red-dim); color: var(--red); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.danger-ico svg { width: 24px; height: 24px; }
.confirm-body h3 { font-size: 18px; font-weight: 850; color: var(--text); margin-bottom: 8px; }
.confirm-body p { font-size: 13.5px; color: var(--text-2); line-height: 1.6; margin-bottom: 24px; }
.confirm-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* ── Toast ── */

/* Pagination Override for Laravel Links */
.premium-pagination { 
    margin-top: 32px !important; 
    padding: 24px 20px 0 !important; 
    border-top: 1px solid var(--border) !important; 
    display: flex !important;
    justify-content: center !important;
    width: 100% !important;
}
.premium-pagination nav { 
    display: flex !important; 
    justify-content: center !important; 
    width: auto !important; 
    border: none !important; 
    box-shadow: none !important; 
    background: transparent !important; 
}
/* Aggressively hide the 'Showing X to Y results' part */
.premium-pagination p, 
.premium-pagination nav > div:first-child,
.premium-pagination nav > div:last-child > div:first-child { 
    display: none !important; 
}

.premium-pagination nav > div:last-child { 
    display: flex !important; 
    flex-direction: column !important; 
    align-items: center !important; 
    border: none !important; 
    margin: 0 !important; 
    padding: 0 !important; 
}

.premium-pagination nav > div:last-child > div:last-child { 
    display: flex !important; 
    gap: 8px !important; 
    flex-wrap: wrap !important; 
    justify-content: center !important;
    border: none !important;
    box-shadow: none !important;
}

.premium-pagination span, .premium-pagination a {
    min-width: 42px !important; 
    height: 42px !important; 
    display: flex !important; 
    align-items: center !important; 
    justify-content: center !important;
    border-radius: 12px !important; 
    border: 1px solid var(--border-1) !important;
    background: var(--surface-2) !important; 
    color: var(--text-2) !important;
    font-size: 14px !important; 
    font-weight: 750 !important; 
    text-decoration: none !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    padding: 0 !important;
    margin: 0 !important;
    cursor: pointer !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
}
.premium-pagination a:hover { 
    background: var(--accent-dim) !important; 
    color: var(--accent) !important; 
    border-color: var(--accent-mid) !important; 
    transform: translateY(-2px) !important; 
    box-shadow: 0 6px 15px var(--accent-glow) !important;
}
.premium-pagination [aria-current="page"] span, 
.premium-pagination .active span { 
    background: var(--accent) !important; 
    color: #fff !important; 
    border-color: var(--accent) !important; 
    box-shadow: 0 6px 20px var(--accent-glow) !important; 
    transform: scale(1.05) !important;
}
.premium-pagination .disabled span, 
.premium-pagination [aria-disabled="true"] span { 
    opacity: 0.3 !important; 
    cursor: not-allowed !important; 
}
</style>
@endpush

@section('content')

@php
    $isSuperAdmin = auth()->check() && method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin();
@endphp
<div class="page" data-superadmin="{{ $isSuperAdmin ? '1' : '0' }}">

    {{-- ── Topbar ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1>@lang('admin.products')</h1>
            <p>
                <span class="live-dot"></span>
                {{ $products->total() }} @lang('admin.total_products')
            </p>
        </div>
        <div class="page-header-right">
            <button class="btn-ghost" onclick="exportCSV()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                @lang('admin.export')
            </button>
            <button class="btn-primary" onclick="openProductModal('create')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                @lang('admin.add_product')
            </button>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row " style="margin: 20px 0;">
        <div class="stat-card">
            <div class="stat-ico blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.total')</div><div class="stat-val">{{ number_format($totalCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.active')</div><div class="stat-val">{{ number_format($activeCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico amber">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.out_of_stock')</div><div class="stat-val">{{ number_format($lowStockCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.inactive')</div><div class="stat-val">{{ number_format($inactiveCount) }}</div></div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar" style="margin: 20px 0;">
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="prod-search" placeholder="{{ __('admin.search_products') }}" autocomplete="off">
        </div>

        <select class="filter-select" onchange="filterCategory(this.value)">
            <option value="">@lang('admin.all_categories')</option>
            @foreach($categories as $cat)
                <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select class="filter-select" onchange="sortTable(this.value)">
            <option value="name-asc">@lang('admin.name_az')</option>
            <option value="name-desc">@lang('admin.name_za')</option>
            <option value="price-asc">@lang('admin.price_low')</option>
            <option value="price-desc">@lang('admin.price_high')</option>
        </select>
        
        <div class="toolbar-sep"></div>
        
        <div class="filter-tabs">
            <button class="filter-chip active" onclick="filterStatus(this,'all')">@lang('admin.all')</button>
            <button class="filter-chip" onclick="filterStatus(this,'active')">@lang('admin.active')</button>
            <button class="filter-chip" onclick="filterStatus(this,'inactive')">@lang('admin.inactive')</button>
        </div>
        
        <div class="toolbar-results" id="row-count"></div>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:36%">@lang('admin.product')</th>
                        <th style="width:16%">@lang('admin.categories')</th>
                        <th style="width:14%" class="sortable" data-col="price" onclick="toggleSort(this)">@lang('admin.price')</th>
                        <th style="width:12%">@lang('admin.stock')</th>
                        <th style="width:12%">@lang('admin.status')</th>
                        <th style="width:10%;text-align:right">@lang('admin.actions')</th>
                    </tr>
                </thead>
                <tbody id="prod-tbody">
                    @forelse($products as $product)
                    <tr id="row-{{ $product->id }}"
                        data-name="{{ strtolower($product->name) }}"
                        data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                        data-price="{{ $product->price }}"
                        data-categories="{{ strtolower($product->categories->pluck('name')->join(',')) }}">

                        <td>
                            <div class="prod-cell">
                                <div class="prod-thumb">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="prod-name">{{ $product->name }}</div>
                                    <div class="prod-sku">
                                        @if($product->brand && !empty($product->brand->name))
                                            <span style="color:var(--accent);font-weight:700;">{{ $product->brand->name }}</span> • 
                                        @endif
                                        {{ $product->sku }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="cat-pills">
                                @forelse($product->categories as $cat)
                                    <span class="cat-pill">{{ $cat->name }}</span>
                                @empty
                                    <span style="font-size:11px;color:var(--text-3)">—</span>
                                @endforelse
                            </div>
                        </td>

                        <td>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="price-was">${{ number_format($product->price, 2) }}</div>
                                <div class="price-sale">${{ number_format($product->sale_price, 2) }}</div>
                            @else
                                <div class="price-main">${{ number_format($product->price, 2) }}</div>
                            @endif
                        </td>

                        <td>
                            @php
                                $status = $product->stock_status ?? 'instock';
                                $sc = $status === 'instock' ? 'stock-in' : 'stock-out';
                                $sl = $status === 'instock' ? __('admin.in_stock') : __('admin.out_of_stock');
                            @endphp
                            <span class="stock-badge {{ $sc }}">{{ $sl }}</span>
                        </td>

                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </td>

                        <td>
                            <div class="action-group">
                                @if($isSuperAdmin)
                                <button class="btn-icon edit-prod-btn" data-id="{{ $product->id }}" title="{{ __('admin.edit') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button class="btn-icon delete delete-prod-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" title="{{ __('admin.delete') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @else
                                <button class="btn-icon edit-prod-btn" data-id="{{ $product->id }}" title="{{ __('admin.view_details') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <div class="empty-ico"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                            <h3>@lang('admin.no_products')</h3>
                            <p>@lang('admin.no_products_desc')</p>
                            <button class="btn btn-primary" onclick="openProductModal('create')" style="margin-top:4px">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                @lang('admin.add_first_product')
                            </button>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="premium-pagination" style="margin-bottom:20px">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>

<div class="modal-overlay" id="prod-modal" onclick="overlayClick(event)">
    <div class="modal-container">

        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon" id="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <div class="modal-title" id="modal-title">@lang('admin.add_product')</div>
                    <div class="modal-subtitle" id="modal-subtitle">@lang('admin.fill_product_details')</div>
                </div>
            </div>
            <button class="modal-x" onclick="closeModal('prod-modal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" onclick="switchTab(this,'tab-basic')">@lang('admin.basic_info')</button>
            <button class="modal-tab" onclick="switchTab(this,'tab-pricing')">@lang('admin.pricing')</button>
            <button class="modal-tab" onclick="switchTab(this,'tab-media')">@lang('admin.media')</button>
        </div>

        {{-- Basic Info --}}
        <div class="modal-body active" id="tab-basic">
            <input type="hidden" id="prod-id">
            <div class="form-row single">
                <div class="field">
                    <label>@lang('admin.product_name') <span class="req">*</span></label>
                    <input type="text" id="prod-name" placeholder="@lang('admin.product_name_placeholder')">
                </div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>@lang('admin.sku') <span class="req">*</span></label>
                    <input type="text" id="prod-sku" placeholder="SKU-001">
                </div>
                <div class="field">
                    <label>@lang('admin.brand')</label>
                    <select id="prod-brand">
                        <option value="">@lang('admin.all')</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row single">
                <div class="field">
                    <label>@lang('admin.categories')</label>
                    <select id="prod-categories" multiple>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <span class="hint">@lang('admin.hold_ctrl_multi')</span>
                </div>
            </div>
            <div class="form-row single">
                <div class="field">
                    <label>@lang('admin.description')</label>
                    <textarea id="prod-desc" rows="4" placeholder="@lang('admin.description_placeholder')"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>@lang('admin.stock_quantity')</label>
                    <input type="number" id="prod-stock" min="0" value="0">
                </div>
                <div class="field" style="justify-content:flex-end">
                    <label>&nbsp;</label>
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <strong>@lang('admin.active_listing')</strong>
                            <span>@lang('admin.visible_in_store')</span>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" id="prod-active" checked>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="modal-body" id="tab-pricing">
            <div class="section-head"><span>@lang('admin.pricing_details')</span><div class="section-line"></div></div>
            <div class="form-row">
                <div class="field">
                    <label>@lang('admin.regular_price') <span class="req">*</span></label>
                    <input type="number" id="prod-price" step="0.01" value="0.00" min="0">
                </div>
                <div class="field">
                    <label>@lang('admin.sale_price')</label>
                    <input type="number" id="prod-sale-price" step="0.01" min="0" placeholder="@lang('admin.optional')">
                    <span class="hint">@lang('admin.sale_price_hint')</span>
                </div>
            </div>
            <div id="discount-preview" style="display:none" class="discount-preview"></div>

            <div class="section-head" style="margin-top:22px;"><span>@lang('admin.cost_margin')</span><div class="section-line"></div></div>
            <div class="form-row">
                <div class="field">
                    <label>@lang('admin.cost_price')</label>
                    <input type="number" id="prod-cost" step="0.01" min="0" placeholder="@lang('admin.optional')">
                    <span class="hint">@lang('admin.cost_price_hint')</span>
                </div>
                <div class="field">
                    <label>@lang('admin.margin')</label>
                    <div class="margin-display"><span id="margin-val">—</span></div>
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div class="modal-body" id="tab-media">
            <div class="section-head"><span>@lang('admin.product_images')</span><div class="section-line"></div></div>
            <div class="upload-zone" id="drop-zone">
                <input type="file" id="prod-images" multiple accept="image/*" onchange="handleImages(event)">
                <div class="upload-ico">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <h4>@lang('admin.drag_drop_images')</h4>
                <p>PNG, JPG, WEBP — @lang('admin.max_size')</p>
                <span class="upload-tag">@lang('admin.browse_files')</span>
            </div>
            <div class="img-preview-grid" id="img-preview-grid"></div>

            <div class="field" style="margin-top:20px;">
                <label>Direct Image URLs (One per line)</label>
                <textarea id="prod-image-urls" rows="3" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.webp"></textarea>
                <span class="hint">Use this to add images from external websites.</span>
            </div>
        </div>

        <div class="modal-foot">
            <div class="modal-foot-left" id="modal-foot-hint">@lang('admin.required_fields_hint')</div>
            <div class="modal-foot-right">
                <button class="btn btn-ghost" onclick="closeModal('prod-modal')">@lang('admin.cancel')</button>
                <button class="btn btn-primary" id="save-btn" onclick="saveProduct()" style="gap:8px">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @lang('admin.save_product')
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ---
     DELETE MODAL
--- --}}
<div class="modal-overlay" id="delete-modal" onclick="overlayClick(event)">
    <div class="modal-container" style="max-width:400px;">
        <div class="confirm-body">
            <div class="danger-ico">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3>@lang('admin.delete_product')</h3>
            <p id="delete-msg">@lang('admin.delete_confirm_msg')</p>
            <div class="confirm-btns">
                <button class="btn btn-ghost" onclick="closeModal('delete-modal')">@lang('admin.cancel')</button>
                <button class="btn" id="confirm-delete-btn" style="background:var(--red);color:#fff;box-shadow:0 4px 14px rgba(240,71,71,0.25);">
                    @lang('admin.yes_delete')
                </button>
            </div>
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
/* ── State ── */
let isEditing = false, editingId = null;
let allRows = [], activeFilter = 'all', activeCat = '';
let selectedFiles = []; 
let deletedImages = []; // Track images deleted from gallery

document.addEventListener('DOMContentLoaded', () => {
    allRows = Array.from(document.querySelectorAll('#prod-tbody tr[id^="row-"]'));
    updateRowCount();
    initPricingWatchers();
    initDragDrop();
    document.getElementById('prod-search').addEventListener('input', applyFilters);

    // Event delegation
    document.addEventListener('click', e => {
        const editBtn = e.target.closest('.edit-prod-btn');
        if (editBtn) editProduct(editBtn.dataset.id);

        const delBtn = e.target.closest('.delete-prod-btn');
        if (delBtn) openDeleteModal(delBtn.dataset.id, delBtn.dataset.name);
    });
});

/* ── Modal ── */
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
function overlayClick(e) { if (e.target === e.currentTarget) closeModal(e.currentTarget.id); }

document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    ['delete-modal','prod-modal'].forEach(id => {
        if (document.getElementById(id).classList.contains('open')) closeModal(id);
    });
});

/* ── Tabs ── */
function switchTab(btn, tabId) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.modal-body').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

/* ── Product modal open ── */
function openProductModal(mode, data = null) {
    isEditing = mode === 'edit';
    // Reset tabs to first
    document.querySelectorAll('.modal-tab')[0].click();

    document.getElementById('modal-title').textContent    = isEditing ? '{{ __("admin.update_product") }}' : '{{ __("admin.add_product") }}';
    document.getElementById('modal-subtitle').textContent = isEditing ? '{{ __("admin.update_product_info") }}' : '{{ __("admin.fill_product_details") }}';

    const editIco = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';
    const addIco  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
    document.getElementById('modal-icon').innerHTML = isEditing ? editIco : addIco;

    const saveBtn = document.getElementById('save-btn');
    const isSuperAdmin = document.querySelector('.page').dataset.superadmin === '1';

    if (saveBtn) {
        saveBtn.innerHTML = isEditing
            ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>{{ __("admin.update_product") }}'
            : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>{{ __("admin.save_product") }}';
        saveBtn.disabled = false;
    }

    if (isEditing && data) {
        editingId = data.id;
        document.getElementById('prod-id').value         = data.id;
        document.getElementById('prod-name').value       = data.name || '';
        document.getElementById('prod-sku').value        = data.sku || '';
        document.getElementById('prod-desc').value       = data.description || '';
        document.getElementById('prod-price').value      = data.price || '0.00';
        document.getElementById('prod-sale-price').value = data.sale_price || '';
        document.getElementById('prod-stock').value      = data.stock || 0;
        document.getElementById('prod-active').checked   = !!data.is_active;
        const sel = document.getElementById('prod-categories');
        Array.from(sel.options).forEach(o => o.selected = (data.categories||[]).some(c => c.id == o.value));
        
        document.getElementById('prod-brand').value = data.brand_id || '';
        
        const grid = document.getElementById('img-preview-grid');
        grid.innerHTML = '';
        selectedFiles = []; // Reset new uploads but keep existing as previews
        (data.images || []).forEach(img => {
            const d = document.createElement('div');
            d.className = 'img-preview existing';
            d.innerHTML = `
                <img src="${img.image_url}" alt="">
                <button class="rm-img" onclick="deleteExistingImage(${img.id}, this)" title="Delete Permanently">✕</button>
            `;
            grid.appendChild(d);
        });
        updatePricingPreview();
    } else {
        editingId = null;
        ['prod-id','prod-name','prod-sku','prod-desc'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('prod-price').value      = '0.00';
        document.getElementById('prod-sale-price').value = '';
        document.getElementById('prod-cost').value       = '';
        document.getElementById('prod-stock').value      = '0';
        document.getElementById('prod-active').checked   = true;
        Array.from(document.getElementById('prod-categories').options).forEach(o => o.selected = false);
        document.getElementById('prod-brand').value = '';
        document.getElementById('img-preview-grid').innerHTML = '';
        selectedFiles = [];
        document.getElementById('discount-preview').style.display = 'none';
        document.getElementById('margin-val').textContent = '—';
        document.getElementById('prod-image-urls').value = '';
    }
    openModal('prod-modal');
    setTimeout(() => document.getElementById('prod-name').focus(), 230);
}

/* ── Edit product ── */
async function editProduct(id) {
    try {
        const res  = await fetch(`/admin/products/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('Failed');
        const data = await res.json();
        openProductModal('edit', data);
    } catch {
        showToast('{{ __("admin.failed_load_product") }}', 'error');
    }
}

/* ── Save ── */
async function saveProduct() {
    const name  = document.getElementById('prod-name').value.trim();
    const sku   = document.getElementById('prod-sku').value.trim();
    const price = parseFloat(document.getElementById('prod-price').value);

    if (!name || !sku || isNaN(price) || price < 0) {
        showToast('{{ __("admin.fill_required_fields") }}', 'error');
        document.querySelectorAll('.modal-tab')[0].click();
        return;
    }

    const saleVal   = document.getElementById('prod-sale-price').value;
    const saleParsed = saleVal ? parseFloat(saleVal) : null;

    const fd = new FormData();
    fd.append('name', name);
    fd.append('sku', sku);
    fd.append('price', price);
    fd.append('sale_price', saleParsed || '');
    fd.append('description', document.getElementById('prod-desc').value);
    fd.append('brand_id', document.getElementById('prod-brand').value);
    fd.append('stock', parseInt(document.getElementById('prod-stock').value) || 0);
    fd.append('is_active', document.getElementById('prod-active').checked ? 1 : 0);
    
    const cats = Array.from(document.getElementById('prod-categories').selectedOptions).map(o => o.value);
    cats.forEach(c => fd.append('categories[]', c));

    selectedFiles.forEach(item => fd.append('images[]', item.file));

    const urls = document.getElementById('prod-image-urls').value.split('\n').map(u => u.trim()).filter(u => u.length > 0);
    urls.forEach(u => fd.append('image_urls[]', u));

    if (isEditing) fd.append('_method', 'PUT');

    const url = isEditing ? `/admin/products/${editingId}` : '/admin/products';
    const btn = document.getElementById('save-btn');
    btn.disabled = true; btn.style.opacity = '0.6';

    try {
        const res  = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: fd,
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error');
        showToast(json.message || '{{ __("admin.saved_successfully") }}', 'success');
        closeModal('prod-modal');
        setTimeout(() => location.reload(), 900);
    } catch (e) {
        showToast(e.message || '{{ __("admin.save_failed") }}', 'error');
    } finally {
        btn.disabled = false; btn.style.opacity = '';
    }
}

/* ── Delete ── */
function openDeleteModal(id, name) {
    document.getElementById('delete-msg').textContent = `{{ __("admin.delete_confirm_prefix") }} "${name}"? {{ __("admin.delete_irreversible") }}`;
    document.getElementById('confirm-delete-btn').onclick = () => doDelete(id);
    openModal('delete-modal');
}

async function doDelete(id) {
    try {
        const res  = await fetch(`/admin/products/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message);
        closeModal('delete-modal');
        const row = document.getElementById(`row-${id}`);
        if (row) {
            row.style.transition = 'opacity .3s, transform .3s';
            row.style.opacity = '0'; row.style.transform = 'translateX(-8px)';
            setTimeout(() => {
                row.remove();
                allRows = allRows.filter(r => r.id !== `row-${id}`);
                updateRowCount();
            }, 320);
        }
        showToast(json.message || '{{ __("admin.deleted_successfully") }}', 'success');
    } catch (e) {
        showToast(e.message || '{{ __("admin.delete_failed") }}', 'error');
    }
}

/* ── Filters / Search / Sort ── */
function filterStatus(btn, status) {
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    activeFilter = status;
    applyFilters();
}
function filterCategory(val) { activeCat = val.toLowerCase(); applyFilters(); }
function applyFilters() {
    const term = document.getElementById('prod-search').value.toLowerCase();
    let visible = 0;
    allRows.forEach(row => {
        const show = row.dataset.name.includes(term)
            && (activeFilter === 'all' || row.dataset.status === activeFilter)
            && (!activeCat || row.dataset.categories.includes(activeCat));
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    updateRowCount(visible);
}
function updateRowCount(n) {
    const el = document.getElementById('row-count');
    if (el) el.textContent = `${n !== undefined ? n : allRows.length} {{ __('admin.results') }}`;
}

let sortState = {};
function toggleSort(th) {
    const col = th.dataset.col;
    sortState[col] = sortState[col] === 'asc' ? 'desc' : 'asc';
    document.querySelectorAll('th.sortable').forEach(t => t.classList.remove('sort-asc','sort-desc'));
    th.classList.add(sortState[col] === 'asc' ? 'sort-asc' : 'sort-desc');
    const tbody = document.getElementById('prod-tbody');
    Array.from(tbody.querySelectorAll('tr[id^="row-"]'))
        .sort((a, b) => {
            const av = parseFloat(a.dataset[col]) || 0, bv = parseFloat(b.dataset[col]) || 0;
            return sortState[col] === 'asc' ? av - bv : bv - av;
        })
        .forEach(r => tbody.appendChild(r));
}
function sortTable(val) {
    const [col, dir] = val.split('-');
    const tbody = document.getElementById('prod-tbody');
    Array.from(tbody.querySelectorAll('tr[id^="row-"]'))
        .sort((a, b) => {
            if (col === 'name') return dir === 'asc' ? a.dataset.name.localeCompare(b.dataset.name) : b.dataset.name.localeCompare(a.dataset.name);
            const av = parseFloat(a.dataset.price)||0, bv = parseFloat(b.dataset.price)||0;
            return dir === 'asc' ? av-bv : bv-av;
        })
        .forEach(r => tbody.appendChild(r));
}

/* ── Pricing live preview ── */
function initPricingWatchers() {
    ['prod-price','prod-sale-price','prod-cost'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', updatePricingPreview);
    });
}
function updatePricingPreview() {
    const price = parseFloat(document.getElementById('prod-price').value) || 0;
    const sale  = parseFloat(document.getElementById('prod-sale-price').value) || 0;
    const cost  = parseFloat(document.getElementById('prod-cost').value) || 0;
    const dp    = document.getElementById('discount-preview');
    if (sale > 0 && sale < price) {
        const pct = ((price - sale) / price * 100).toFixed(0);
        dp.style.display = '';
        dp.textContent = `💚 ${pct}% discount — customers save $${(price-sale).toFixed(2)}`;
    } else { dp.style.display = 'none'; }
    const activePrice = (sale > 0 && sale < price) ? sale : price;
    const mv = document.getElementById('margin-val');
    if (cost > 0 && activePrice > 0) {
        const m = ((activePrice - cost) / activePrice * 100).toFixed(1);
        mv.textContent = `${m}%`;
        mv.style.color = parseFloat(m) > 20 ? 'var(--green)' : parseFloat(m) > 0 ? 'var(--amber)' : 'var(--red)';
    } else { mv.textContent = '—'; mv.style.color = ''; }
}

/* ── Image drag & drop ── */
function initDragDrop() {
    const zone = document.getElementById('drop-zone');
    if (!zone) return;
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => { e.preventDefault(); zone.classList.remove('drag-over'); renderPreviews(e.dataTransfer.files); });
}
function handleImages(e) { 
    const files = Array.from(e.target.files);
    renderPreviews(files);
}
function renderPreviews(files) {
    const grid = document.getElementById('img-preview-grid');
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        
        // Add to our state array
        const fileId = Math.random().toString(36).substr(2, 9);
        selectedFiles.push({ id: fileId, file: file });

        const r = new FileReader();
        r.onload = ev => {
            const d = document.createElement('div');
            d.className = 'img-preview';
            d.id = `img-pv-${fileId}`;
            d.innerHTML = `
                <img src="${ev.target.result}" alt="">
                <button class="rm-img" onclick="removeSelectedFile('${fileId}')" title="Remove">✕</button>
            `;
            grid.appendChild(d);
        };
        r.readAsDataURL(file);
    });
}
async function deleteExistingImage(imgId, btn) {
    if (!confirm('{{ __("admin.confirm_delete_image") }}')) return;
    try {
        const res = await fetch(`/admin/products/images/${imgId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (res.ok) {
            btn.parentElement.classList.add('removing');
            setTimeout(() => btn.parentElement.remove(), 300);
            showToast('{{ __("admin.image_deleted") }}', 'success');
        } else {
            showToast('{{ __("admin.failed_delete_image") }}', 'error');
        }
    } catch {
        showToast('{{ __("admin.error_deleting_image") }}', 'error');
    }
}
function removeSelectedFile(id) {
    selectedFiles = selectedFiles.filter(item => item.id !== id);
    const el = document.getElementById(`img-pv-${id}`);
    if (el) {
        el.classList.add('removing');
        setTimeout(() => el.remove(), 300);
    }
}

/* ── CSV export ── */
function exportCSV() {
    const rows = [['Name','SKU','Price','Status']];
    allRows.forEach(r => {
        rows.push([
            r.querySelector('.prod-name')?.textContent.trim() || '',
            r.querySelector('.prod-sku')?.textContent.trim() || '',
            r.dataset.price || '',
            r.dataset.status || '',
        ]);
    });
    const csv  = rows.map(r => r.map(c => `"${c}"`).join(',')).join('\n');
    const url  = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
    const a    = Object.assign(document.createElement('a'), { href: url, download: 'products.csv' });
    a.click(); URL.revokeObjectURL(url);
    showToast('{{ __("admin.export_ready") }}', 'success');
}

/* ── Toast ── */
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