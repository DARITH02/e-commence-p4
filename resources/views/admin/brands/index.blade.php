@extends('layouts.admin')

@section('title', __('admin.brands'))
@section('page_title', __('admin.brands'))

@push('styles')
<style>
body { background: var(--bg) !important; font-family: 'DM Sans', sans-serif; }
.content-inner { max-width: none !important; padding: 0 !important; }

/* ─── Page animation ─── */
.brands-page {
    display: flex; flex-direction: column; gap: 24px;
    animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Stats ── */
.stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width: 800px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 480px) { .stats-row { grid-template-columns: 1fr; } }
.stat-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg);
    padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: var(--shadow);
    transition: all 0.2s;
}
.stat-card:hover { border-color: var(--border-2); transform: translateY(-2px); }
.stat-ico { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-ico svg { width: 20px; height: 20px; }
.stat-ico.blue   { background: var(--accent-dim); color: var(--accent); }
.stat-ico.green  { background: var(--green-dim); color: var(--green); }
.stat-ico.red    { background: var(--red-dim); color: var(--red); }
.stat-label { font-size: 10px; font-weight: 750; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.1em; font-family: 'DM Mono', monospace; }
.stat-val   { font-size: 24px; font-weight: 850; color: var(--text); letter-spacing: -0.02em; margin-top: 1px; }

/* ─── Page header ─── */
.page-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 4px; }
.page-heading h1 { font-size: 28px; font-weight: 850; color: var(--text); letter-spacing: -0.04em; }
.page-heading p { font-size: 12.5px; color: var(--text-3); margin-top: 6px; display: flex; align-items: center; gap: 8px; font-weight: 500; }
.live-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--green);
    animation: livePulse 2.2s ease infinite;
}
@keyframes livePulse {
    0%   { box-shadow: 0 0 0 0 rgba(31,186,114,0.5); }
    70%  { box-shadow: 0 0 0 6px rgba(31,186,114,0); }
    100% { box-shadow: 0 0 0 0 rgba(31,186,114,0); }
}

/* ─── Toolbar ─── */
.toolbar {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg);
    padding: 14px 16px; display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow);
}
.search-wrap { position:relative; flex:1; min-width:240px; }
.search-wrap svg { position:absolute; left:14px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:var(--text-3); pointer-events:none; }
.search-wrap input {
    width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:12px;
    padding:10px 14px 10px 42px; color:var(--text); font-family:inherit; font-size:13.5px; font-weight:600;
    transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
}
.search-wrap input:focus { border-color:var(--accent); box-shadow:0 0 0 4px var(--accent-dim); background: var(--surface); outline: none; }

.filter-select {
    background:var(--surface-2); border:1px solid var(--border); border-radius:12px;
    padding:10px 16px; color:var(--text-2); font-size:13px; font-weight:700;
    cursor:pointer; transition: all 0.2s; outline: none; appearance: none;
}
.filter-select:hover { border-color: var(--border-2); background: var(--surface-3); }
.filter-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); }

.toolbar-sep { width:1px; height:32px; background:var(--border); flex-shrink:0; }

.btn-add {
    display:inline-flex; align-items:center; gap:10px; background:var(--accent); color:#fff; border:none;
    border-radius:12px; padding:10px 22px; font-size:13.5px; font-weight:800; cursor:pointer;
    transition: all 0.2s; box-shadow: 0 4px 15px var(--accent-glow); white-space:nowrap;
}
.btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 20px var(--accent-glow); filter: brightness(1.05); }

/* ─── Table ─── */
.table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-xl); box-shadow: var(--shadow); overflow:hidden; }
table { width: 100%; border-collapse: collapse; text-align: left; }
thead th {
    padding:14px 20px; font-family:'DM Mono', monospace; font-size:10px; text-transform:uppercase;
    letter-spacing:0.12em; color:var(--text-3); font-weight:800; background:var(--surface-2);
    border-bottom:1px solid var(--border);
}
tbody tr { border-bottom:1px solid var(--border); transition:background var(--transition); }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background:var(--surface-2); }
tbody td { padding:14px 20px; vertical-align:middle; }

.brand-identity { display:flex; align-items:center; gap:14px; }
.brand-logo-md {
    width:42px; height:42px; border-radius:12px; background:var(--surface-2); border:1px solid var(--border);
    display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0;
}
.brand-logo-md img { width:100%; height:100%; object-fit:contain; padding:4px; }
.brand-logo-md svg { width:20px; height:20px; color:var(--text-3); }

.brand-name { font-size:14.5px; font-weight:850; color:var(--text); line-height:1.1; }
.brand-slug { font-family:'DM Mono', monospace; font-size:10px; color:var(--text-3); margin-top: 3px; }

.website-link {
    display: inline-flex; align-items: center; gap: 5px; font-weight: 700; color: var(--accent);
    font-size: 12.5px; text-decoration: none; transition: all 0.15s;
    padding: 4px 10px; background: var(--accent-dim); border-radius: 8px; border: 1px solid var(--accent-mid);
}
.website-link:hover { background: var(--accent); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 10px var(--accent-glow); }
.website-link svg { width: 11px; height: 11px; flex-shrink: 0; }

.sort-chip {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; height: 24px; padding: 0 8px;
    font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 800;
    color: var(--text-2); background: var(--surface-2); border: 1px solid var(--border);
    border-radius: 8px;
}

.status-badge { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:750; padding:5px 12px; border-radius:100px; border:1px solid; white-space:nowrap; }
.status-badge::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; }
.status-active   { background:var(--green-dim); color:var(--green); border-color:rgba(34,201,122,0.2); }
.status-inactive { background:var(--surface-3); color:var(--text-3); border-color:var(--border); }

/* ═══════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════ */
.action-group {
    display: flex; align-items: center; justify-content: flex-end; gap: 6px;
}

.btn-action-edit {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: 10px;
    border: 1px solid var(--border); background: var(--surface-2);
    color: var(--text-2); font-size: 12px; font-weight: 700;
    cursor: pointer; transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    white-space: nowrap; font-family: inherit;
}
.btn-action-edit svg { width: 13px; height: 13px; flex-shrink: 0; transition: transform 0.18s; }
.btn-action-edit:hover {
    background: var(--accent-dim); border-color: var(--accent-mid); color: var(--accent);
    transform: translateY(-1px); box-shadow: 0 4px 12px var(--accent-glow);
}
.btn-action-edit:hover svg { transform: rotate(-8deg); }

.action-sep { width: 1px; height: 20px; background: var(--border); flex-shrink: 0; }

.btn-action-del {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 10px;
    border: 1px solid var(--border); background: var(--surface-2);
    color: var(--text-3); cursor: pointer;
    transition: all 0.18s cubic-bezier(0.4,0,0.2,1); flex-shrink: 0;
}
.btn-action-del svg { width: 13px; height: 13px; }
.btn-action-del:hover {
    background: var(--red-dim); border-color: rgba(232,69,69,0.3); color: var(--red);
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(232,69,69,0.15);
}

/* ═══════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════ */
.pagination-wrap {
    padding: 20px 24px; border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; background: var(--surface-2);
}
.pagination-info {
    font-size: 12px; font-weight: 600; color: var(--text-3);
    font-family: 'DM Mono', monospace; white-space: nowrap;
}
.pagination-info strong { color: var(--text-2); font-weight: 800; }

.pagination-nav { display: flex; align-items: center; gap: 4px; }

.pagination-wrap nav > div:first-child { display: none !important; }
.pagination-wrap nav > div:last-child > div:first-child { display: none !important; }
.pagination-wrap nav > div:last-child { display: flex !important; align-items: center !important; gap: 4px !important; }
.pagination-wrap nav > div:last-child > div:last-child { display: flex !important; align-items: center !important; gap: 4px !important; }

.pagination-wrap span,
.pagination-wrap a {
    display: inline-flex !important; align-items: center !important; justify-content: center !important;
    min-width: 36px !important; height: 36px !important; padding: 0 10px !important;
    border-radius: 10px !important; border: 1px solid var(--border) !important;
    background: var(--surface) !important; color: var(--text-3) !important;
    font-size: 13px !important; font-weight: 750 !important; text-decoration: none !important;
    transition: all 0.18s cubic-bezier(0.4,0,0.2,1) !important; cursor: pointer !important;
    font-family: 'DM Mono', monospace !important; margin: 0 !important; box-sizing: border-box !important;
}
.pagination-wrap a:hover {
    background: var(--accent-dim) !important; border-color: var(--accent-mid) !important;
    color: var(--accent) !important; transform: translateY(-1px) !important;
    box-shadow: 0 4px 10px var(--accent-glow) !important;
}
.pagination-wrap [aria-current="page"] span,
.pagination-wrap span[aria-current="page"] {
    background: var(--accent) !important; color: #fff !important;
    border-color: var(--accent) !important; box-shadow: 0 4px 14px var(--accent-glow) !important;
    font-weight: 850 !important;
}
.pagination-wrap span.disabled,
.pagination-wrap [aria-disabled="true"] span {
    opacity: 0.35 !important; cursor: not-allowed !important;
    pointer-events: none !important; background: var(--surface-2) !important; box-shadow: none !important;
}
.pagination-wrap a[rel="prev"],
.pagination-wrap a[rel="next"] { gap: 4px !important; padding: 0 14px !important; color: var(--text-2) !important; }

/* ─── MODAL ─── */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.65);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 1000; display: none; align-items: center; justify-content: center;
    padding: 24px; opacity: 0; transition: opacity 0.3s;
}
.modal-overlay.open { display: flex; opacity: 1; }
.modal-container {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-xl); width: 100%; max-width: 600px;
    max-height: 90vh; display: flex; flex-direction: column;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1); overflow: hidden;
}
.modal-overlay.open .modal-container { transform: translateY(0); }

.modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-head-left { display: flex; align-items: center; gap: 14px; }
.modal-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--accent-dim); color: var(--accent); display: flex; align-items: center; justify-content: center; }
.modal-icon svg { width: 20px; height: 20px; }
.modal-title { font-size: 16px; font-weight: 800; color: var(--text); }
.modal-subtitle { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.modal-x { color: var(--text-3); padding: 8px; border-radius: 8px; transition: all 0.2s; background: none; border: none; cursor: pointer; }
.modal-x:hover { background: var(--surface-2); color: var(--text); }

.modal-tabs { display: flex; gap: 4px; padding: 0 24px; border-bottom: 1px solid var(--border); background: var(--surface-2); }
.modal-tab { padding: 12px 20px; font-size: 13px; font-weight: 700; color: var(--text-3); border-bottom: 2px solid transparent; transition: all 0.2s; position: relative; top: 1px; background: none; border: none; cursor: pointer; }
.modal-tab:hover { color: var(--text-2); }
.modal-tab.active { color: var(--accent); border-bottom-color: var(--accent); }

.modal-body { padding: 24px; overflow-y: auto; display: none; }
.modal-body.active { display: block; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.form-row.single { grid-template-columns: 1fr; }
.field { display: flex; flex-direction: column; gap: 8px; }
.field label { font-size: 11px; font-weight: 800; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; }
.field input, .field select, .field textarea { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 13.5px; color: var(--text); transition: all 0.2s; width: 100%; font-family: inherit; }
.field input:focus, .field select:focus, .field textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-dim); background: var(--surface); outline: none; }
.req { color: var(--red); }

.upload-zone {
    border: 2px dashed var(--border); border-radius: 12px; padding: 24px;
    text-align: center; background: var(--bg); cursor: pointer; transition: 0.2s;
    position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.upload-zone:hover { border-color: var(--accent); background: var(--accent-dim); }
.upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.upload-ico { width: 44px; height: 44px; border-radius: 12px; background: var(--surface-2); color: var(--text-3); display: flex; align-items: center; justify-content: center; }
.upload-ico svg { width: 24px; height: 24px; }
.upload-zone p { font-size: 12px; font-weight: 700; color: var(--text-2); margin: 0; }
.upload-zone span { font-size: 10px; color: var(--text-3); }

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

.modal-foot { padding: 16px 24px; border-top: 1px solid var(--border); background: var(--surface-2); display: flex; align-items: center; justify-content: flex-end; gap: 12px; }
.btn-cancel { background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border); padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 13.5px; font-family: inherit; }
.btn-cancel:hover { background: var(--surface-3); border-color: var(--border-2); }
.btn-primary { background: var(--accent); color: #fff; border: none; padding: 10px 24px; border-radius: 10px; font-weight: 800; cursor: pointer; transition: 0.2s; font-size: 13.5px; font-family: inherit; box-shadow: 0 4px 12px var(--accent-glow); }
.btn-primary:hover { filter: brightness(1.06); transform: translateY(-1px); box-shadow: 0 6px 18px var(--accent-glow); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

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

@media(max-width:700px) { thead th:nth-child(2), tbody td:nth-child(2) { display:none; } }
@media(max-width:520px) {
    .page-header { flex-direction:column; align-items:flex-start; }
    .pagination-wrap { flex-direction: column; align-items: center; gap: 12px; }
    .pagination-info { display: none; }
}
</style>
@endpush

@section('content')
<div class="brands-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>{{ __('admin.brands') }}</h1>
            <p>
                <span class="live-dot"></span>
                <span>{{ $totalCount }} {{ __('admin.total_brands') }}</span>
            </p>
        </div>
        <button onclick="openBrandModal('create')" class="btn-add">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_brand') }}
        </button>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-ico blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.total')</div><div class="stat-val">{{ number_format($totalCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.active_status')</div><div class="stat-val">{{ number_format($activeCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.inactive_status')</div><div class="stat-val">{{ number_format($inactiveCount) }}</div></div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="brand-search" placeholder="{{ __('admin.search_brands') }}" autocomplete="off">
        </div>
        <div class="toolbar-sep"></div>
        <select class="filter-select" id="status-filter">
            <option value="">{{ __('admin.all_statuses') }}</option>
            <option value="active">{{ __('admin.active_status') }}</option>
            <option value="inactive">{{ __('admin.inactive_status') }}</option>
        </select>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width: 32%">@lang('admin.brand')</th>
                        <th style="width: 25%">@lang('admin.website')</th>
                        <th style="width: 15%">@lang('admin.sort_order')</th>
                        <th style="width: 13%">@lang('admin.status')</th>
                        <th style="text-align:right; width: 15%">@lang('admin.actions')</th>
                    </tr>
                </thead>
                <tbody id="brand-tbody">
                    @forelse($brands as $brand)
                    <tr id="row-{{ $brand->id }}"
                        data-status="{{ $brand->is_active ? 'active' : 'inactive' }}"
                        data-name="{{ strtolower($brand->name) }}">
                        <td>
                            <div class="brand-identity">
                                <div class="brand-logo-md">
                                    @if($brand->logo_url)
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div style="min-width:0">
                                    <div class="brand-name">{{ $brand->name }}</div>
                                    <div class="brand-slug">{{ $brand->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($brand->website)
                                <a href="{{ $brand->website }}" target="_blank" class="website-link">
                                    {{ str_replace(['http://', 'https://'], '', rtrim($brand->website, '/')) }}
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span style="color:var(--text-3); font-size:13px;">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="sort-chip">{{ $brand->sort_order }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $brand->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $brand->is_active ? __('admin.active_status') : __('admin.inactive_status') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="btn-action-edit"
                                        onclick="openBrandModal('edit', {{ $brand->id }})"
                                        title="{{ __('admin.edit') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    {{ __('admin.edit') }}
                                </button>

                                <div class="action-sep"></div>

                                <button class="btn-action-del"
                                        onclick="confirmDeleteBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}')"
                                        title="{{ __('admin.delete') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="empty-title">@lang('admin.no_brands_found')</div>
                                <div class="empty-sub">@lang('admin.add_first_brand')</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if(method_exists($brands, 'hasPages') && $brands->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                {{ __('admin.showing') }}
                <strong>{{ $brands->firstItem() }}–{{ $brands->lastItem() }}</strong>
                {{ __('admin.of') }}
                <strong>{{ $brands->total() }}</strong>
                {{ __('admin.results') }}
            </div>
            <div class="pagination-nav">
                {{ $brands->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

{{-- ── BRAND MODAL ── --}}
<div id="brand-modal" class="modal-overlay" onclick="handleOverlayClick(event)">
    <div class="modal-container">
        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon" id="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <div class="modal-title" id="modal-title">@lang('admin.add_brand')</div>
                    <div class="modal-subtitle" id="modal-subtitle">@lang('admin.fill_brand_details')</div>
                </div>
            </div>
            <button class="modal-x" onclick="closeModal('brand-modal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" onclick="switchTab(this,'tab-brand-general')">@lang('admin.general_info')</button>
        </div>

        <div class="modal-body active" id="tab-brand-general">
            <form id="brand-form" onsubmit="saveBrand(event)">
                <input type="hidden" id="brand-id">

                <div class="form-row">
                    <div class="field">
                        <label>@lang('admin.brand_name') <span class="req">*</span></label>
                        <input type="text" id="brand-name" name="name" required placeholder="@lang('admin.brand_name_placeholder')">
                    </div>
                    <div class="field">
                        <label>@lang('admin.slug')</label>
                        <input type="text" id="brand-slug" name="slug" placeholder="brand-name-slug">
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>@lang('admin.website')</label>
                        <input type="url" id="brand-website" name="website" placeholder="https://example.com">
                    </div>
                    <div class="field">
                        <label>@lang('admin.sort_order')</label>
                        <input type="number" id="brand-sort-order" name="sort_order" value="0">
                    </div>
                </div>

                <div class="form-row single">
                    <div class="field">
                        <label>@lang('admin.description')</label>
                        <textarea id="brand-description" name="description" rows="3" placeholder="@lang('admin.description_placeholder')"></textarea>
                    </div>
                </div>

                <div class="form-row single">
                    <div class="field">
                        <label>Logo URL (Alternative to upload)</label>
                        <input type="text" id="brand-logo-url" name="logo" placeholder="https://example.com/logo.webp">
                    </div>
                </div>

                <div class="form-row single" style="margin-bottom: 24px;">
                    <div class="field">
                        <label>@lang('admin.logo') (Upload)</label>
                        <div class="upload-zone" id="logo-drop-zone">
                            <input type="file" id="brand-logo-file" name="logo" accept="image/*" onchange="previewLogo(this)">
                            <div id="logo-preview-container">
                                <div class="upload-ico">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p>@lang('admin.drag_drop_images')</p>
                                <span>JPG, PNG, WEBP — max 2 MB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row single">
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <strong>@lang('admin.active_status')</strong>
                            <span>@lang('admin.visible_to_customers')</span>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" id="brand-active" name="is_active" checked>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-foot">
            <button class="btn-cancel" onclick="closeModal('brand-modal')">@lang('admin.cancel')</button>
            <button class="btn-primary" id="btn-save" onclick="document.getElementById('brand-form').requestSubmit()">
                @lang('admin.save_changes')
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
let currentMode = 'create';

/* ─── MODAL ─── */
function openBrandModal(mode, id = null) {
    currentMode = mode;
    const modal = document.getElementById('brand-modal');
    const form  = document.getElementById('brand-form');

    form.reset();
    document.getElementById('brand-id').value = '';
    resetLogoPreview();

    const addIco  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>';
    const editIco = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';

    if (mode === 'edit') {
        document.getElementById('modal-title').innerText    = "@lang('admin.edit') @lang('admin.brand')";
        document.getElementById('modal-subtitle').innerText = "@lang('admin.edit_brand_subtitle')";
        document.getElementById('modal-icon').innerHTML     = editIco;

        fetch(`{{ url('admin/brands') }}/${id}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(b => {
                document.getElementById('brand-id').value          = b.id;
                document.getElementById('brand-name').value        = b.name;
                document.getElementById('brand-slug').value        = b.slug;
                document.getElementById('brand-website').value     = b.website || '';
                document.getElementById('brand-sort-order').value  = b.sort_order;
                document.getElementById('brand-description').value = b.description || '';
                document.getElementById('brand-active').checked    = !!b.is_active;
                
                if (b.logo && b.logo.startsWith('http')) {
                    document.getElementById('brand-logo-url').value = b.logo;
                }

                if (b.logo_url) {
                    document.getElementById('logo-preview-container').innerHTML = `
                        <img src="${b.logo_url}" style="max-height:110px; border-radius:12px; border:1px solid var(--border);">
                        <button type="button" onclick="resetLogoPreview()" style="margin-top:8px; padding:5px 12px; font-size:11px; font-weight:700; border-radius:8px; border:1px solid var(--border); background:var(--surface-2); color:var(--text-2); cursor:pointer;">@lang('admin.remove')</button>
                    `;
                }
            });
    } else {
        document.getElementById('modal-title').innerText    = "@lang('admin.add_brand')";
        document.getElementById('modal-subtitle').innerText = "@lang('admin.fill_brand_details')";
        document.getElementById('modal-icon').innerHTML     = addIco;
    }

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target.classList.contains('modal-overlay')) closeModal(e.target.id);
}

function switchTab(btn, tabId) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.modal-body').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

/* ─── LOGO ─── */
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('logo-preview-container').innerHTML = `
                <img src="${e.target.result}" style="max-height:110px; border-radius:12px; border:1px solid var(--border);">
                <button type="button" onclick="resetLogoPreview()" style="margin-top:8px; padding:5px 12px; font-size:11px; font-weight:700; border-radius:8px; border:1px solid var(--border); background:var(--surface-2); color:var(--text-2); cursor:pointer;">@lang('admin.change')</button>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function resetLogoPreview() {
    document.getElementById('brand-logo-file').value = '';
    document.getElementById('brand-logo-url').value = '';
    document.getElementById('logo-preview-container').innerHTML = `
        <div class="upload-ico"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
        <p>@lang('admin.drag_drop_images')</p>
        <span>JPG, PNG, WEBP — max 2 MB</span>
    `;
}

/* ─── SAVE ─── */
function saveBrand(e) {
    e.preventDefault();
    const btn      = document.getElementById('btn-save');
    const id       = document.getElementById('brand-id').value;
    const formData = new FormData(e.target);

    formData.set('is_active', document.getElementById('brand-active').checked ? '1' : '0');
    if (id) formData.append('_method', 'PUT');

    btn.disabled  = true;
    btn.innerHTML = '@lang("admin.saving")...';

    fetch(id ? `{{ url("admin/brands") }}/${id}` : `{{ url("admin/brands") }}`, {
        method:  'POST',
        body:    formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            showToast(data.message || '@lang("admin.saved_successfully")', 'success');
            setTimeout(() => location.reload(), 700);
        } else {
            showToast(data.message || '@lang("admin.error_saving")', 'error');
            btn.disabled  = false;
            btn.innerText = '@lang("admin.save_changes")';
        }
    })
    .catch(() => {
        showToast('@lang("admin.network_error")', 'error');
        btn.disabled  = false;
        btn.innerText = '@lang("admin.save_changes")';
    });
}

/* ─── DELETE ─── */
function confirmDeleteBrand(id, name) {
    if (!confirm(`@lang('admin.delete_brand_confirm')`)) return;

    fetch(`{{ url('admin/brands') }}/${id}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        showToast(data.message || '@lang("admin.deleted_successfully")', 'success');
        const row = document.getElementById(`row-${id}`);
        if (row) {
            row.style.transition = 'opacity 0.3s, transform 0.3s';
            row.style.opacity    = '0';
            row.style.transform  = 'translateX(10px)';
            setTimeout(() => row.remove(), 300);
        }
    })
    .catch(() => showToast('@lang("admin.error_deleting")', 'error'));
}

/* ─── FILTERS ─── */
function applyFilters() {
    const term   = document.getElementById('brand-search').value.toLowerCase();
    const status = document.getElementById('status-filter').value;

    document.querySelectorAll('#brand-tbody tr[id^="row-"]').forEach(row => {
        const okTerm   = !term   || (row.getAttribute('data-name') || '').includes(term);
        const okStatus = !status || (row.getAttribute('data-status') || '') === status;
        row.style.display = (okTerm && okStatus) ? '' : 'none';
    });
}

document.getElementById('brand-search').addEventListener('input', applyFilters);
document.getElementById('status-filter').addEventListener('change', applyFilters);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal('brand-modal'); });

/* ─── TOAST ─── */
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.className = `toast show ${type}`;
    document.getElementById('toast-msg').innerText = msg;
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.remove('show'), 3200);
}
</script>
@endpush