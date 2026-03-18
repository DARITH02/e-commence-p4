@extends('layouts.admin')

@section('title', __('admin.categories'))
@section('page_title', __('admin.categories'))

@push('styles')
<style>
/* Categories page uses global design tokens from app.css */
body { background: var(--bg) !important; font-family: 'DM Sans', sans-serif; }
.content-inner { max-width: none !important; padding: 0 !important; }

/* ─── Page animation ─── */
.categories-page {
    display: flex;
    flex-direction: column;
    gap: 24px;
    animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Stats ── */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
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
.stat-ico.purple { background: rgba(147,51,234,0.1); color: #9333ea; }
.stat-ico.red    { background: var(--red-dim); color: var(--red); }
.stat-label { font-size: 10px; font-weight: 750; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.1em; font-family: 'DM Mono', monospace; }
.stat-val   { font-size: 24px; font-weight: 850; color: var(--text); letter-spacing: -0.02em; margin-top: 1px; }

/* ─── Page header ─── */
.page-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 4px; }
.page-heading h1 { font-size: 28px; font-weight: 850; color: var(--text); letter-spacing: -0.04em; }
.page-heading p { font-size: 12.5px; color: var(--text-3); margin-top: 6px; display: flex; align-items: center; gap: 8px; font-weight: 500; }
.live-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--green);
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
.search-wrap input:focus { border-color:var(--accent); box-shadow:0 0 0 4px var(--accent-dim); background: var(--surface); }

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
tbody tr:hover { background:var(--surface-2); }
tbody td { padding:14px 20px; vertical-align:middle; }

/* Hierarchy Indicator */
.cat-identity { display:flex; align-items:center; gap:14px; }
.indent-1 { padding-left: 36px; border-left: 2px solid var(--border); margin-left: 20px; }
.tree-line { 
    display: flex; align-items: center; justify-content: center; width: 24px; height: 2px; background: var(--border); position: relative; left: -14px;
}

.cat-avatar {
    width:40px; height:40px; border-radius:12px; background:var(--accent-dim); border:1px solid var(--accent-mid);
    display:flex; align-items:center; justify-content:center; font-family:'DM Mono', monospace;
    font-size:14px; font-weight:850; color:var(--accent); flex-shrink:0; text-transform:uppercase;
}
.cat-name   { font-size:14.5px; font-weight:850; color:var(--text); line-height:1.1; white-space:nowrap; }
.cat-id-label { font-family:'DM Mono', monospace; font-size:10px; color:var(--text-3); margin-top: 3px; }

.slug-pill { font-family:'DM Mono', monospace; font-size:11px; color:var(--text-3); background: var(--surface-2); padding: 4px 10px; border-radius: 8px; border: 1px solid var(--border); }

.count-badge { display: inline-flex; align-items: center; gap: 6px; font-weight: 800; color: var(--text-2); font-size: 13.5px; }
.count-badge span { font-size: 10px; font-weight: 750; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; }

.status-badge { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:750; padding:5px 12px; border-radius:100px; border:1px solid; white-space:nowrap; }
.status-badge::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; }
.status-active   { background:var(--green-dim); color:var(--green); border-color:rgba(34,201,122,0.2); }
.status-inactive { background:var(--surface-3); color:var(--text-3); border-color:var(--border); }

.btn-icon {
    width:34px; height:34px; border-radius:10px; background:var(--surface-2); border:1px solid var(--border);
    display:flex; align-items:center; justify-content:center; color:var(--text-2); cursor:pointer;
    transition:all 0.2s;
}
.btn-icon:hover { background:var(--accent-dim); border-color:var(--accent-mid); color:var(--accent); transform:translateY(-2px); box-shadow: var(--shadow-sm); }
.btn-icon.delete:hover { background:var(--red-dim); border-color:rgba(232,69,69,0.25); color:var(--red); }

/* Pagination Override for Laravel Links */
.premium-pagination { 
    margin-top: 24px !important; padding: 24px 20px 0 !important; border-top: 1px solid var(--border) !important; 
    display: flex !important; justify-content: center !important; width: 100% !important;
}
.premium-pagination nav { display: flex !important; justify-content: center !important; width: auto !important; border: none !important; box-shadow: none !important; background: transparent !important; }
.premium-pagination p, 
.premium-pagination nav > div:first-child,
.premium-pagination nav > div:last-child > div:first-child { display: none !important; }

.premium-pagination nav > div:last-child { display: flex !important; flex-direction: column !important; align-items: center !important; border: none !important; margin: 0 !important; padding: 0 !important; }

.premium-pagination nav > div:last-child > div:last-child { display: flex !important; gap: 8px !important; flex-wrap: wrap !important; justify-content: center !important; border: none !important; }

.premium-pagination span, .premium-pagination a {
    min-width: 42px !important; height: 42px !important; display: flex !important; align-items: center !important; justify-content: center !important;
    border-radius: 12px !important; border: 1px solid var(--border-1) !important;
    background: var(--surface-2) !important; color: var(--text-2) !important;
    font-size: 14px !important; font-weight: 750 !important; text-decoration: none !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important; padding: 0 !important; margin: 0 !important; cursor: pointer !important;
}
.premium-pagination a:hover { background: var(--accent-dim) !important; color: var(--accent) !important; border-color: var(--accent-mid) !important; transform: translateY(-2px) !important; box-shadow: 0 6px 15px var(--accent-glow) !important; }
.premium-pagination [aria-current="page"] span, .premium-pagination .active span { background: var(--accent) !important; color: #fff !important; border-color: var(--accent) !important; box-shadow: 0 6px 20px var(--accent-glow) !important; transform: scale(1.05) !important; }
.premium-pagination .disabled span, .premium-pagination [aria-disabled="true"] span { opacity: 0.3 !important; cursor: not-allowed !important; background: var(--surface-1) !important; }

/* ═══════════════════════════════════════════
   MODAL
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
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-xl); width: 100%; max-width: 600px;
    max-height: 90vh; display: flex; flex-direction: column;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
    overflow: hidden;
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
.field input, .field select, .field textarea { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 13.5px; color: var(--text); transition: all 0.2s; width: 100%; }
.field input:focus, .field select:focus, .field textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-dim); background: var(--surface); outline: none; }
.req { color: var(--red); }

.slug-preview {
    display:flex; align-items:center; gap:8px;
    padding:8px 12px; background:var(--surface-3);
    border:1px solid var(--border); border-radius:var(--radius-md);
    margin-top: 4px;
}
.slug-preview-label { font-size:9.5px; color:var(--text-3); font-weight:700; text-transform:uppercase; }
.slug-preview-val { font-family:'DM Mono', monospace; font-size:11px; color:var(--accent); font-weight:700; }

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

/* Delete Modal */
.confirm-body { padding: 40px 32px; text-align: center; }
.danger-ico { width: 56px; height: 56px; border-radius: 16px; background: var(--red-dim); color: var(--red); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.danger-ico svg { width: 24px; height: 24px; }
.confirm-body h3 { font-size: 18px; font-weight: 850; color: var(--text); margin-bottom: 8px; }
.confirm-body p { font-size: 13.5px; color: var(--text-2); line-height: 1.6; margin-bottom: 24px; }
.confirm-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.btn-del-confirm { background: var(--red); color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(240,71,71,0.25); transition: 0.2s; }
.btn-del-cancel { background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border); padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.btn-del-confirm:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-del-cancel:hover { background: var(--surface-3); border-color: var(--border-2); }

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


@media(max-width:700px) { thead th:nth-child(3), tbody td:nth-child(3) { display:none; } }
@media(max-width:520px) {
    thead th:nth-child(2), tbody td:nth-child(2) { display:none; }
    .page-header { flex-direction:column; align-items:flex-start; }
}
</style>
@endpush

@section('content')
<div class="categories-page" data-superadmin="{{ Auth::user()->isSuperAdmin() ? '1' : '0' }}">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>{{ __('admin.categories') }}</h1>
            <p>
                <span class="live-dot"></span>
                <span>{{ $totalCount }} {{ __('admin.total_categories') }}</span>
            </p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-ico blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
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
            <div class="stat-ico purple">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.root_levels')</div><div class="stat-val">{{ number_format($rootCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.inactive')</div><div class="stat-val">{{ number_format($inactiveCount) }}</div></div>
        </div>
    </div>

{{-- ── Toolbar ── --}}
<div class="toolbar">
<div class="search-wrap">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <input type="text" id="cat-search" placeholder="{{ __('admin.search_categories') }}" autocomplete="off">
</div>

<select class="filter-select" id="status-filter">
    <option value="">{{ __('admin.all_statuses') }}</option>
    <option value="active">{{ __('admin.active') }}</option>
    <option value="inactive">{{ __('admin.inactive') }}</option>
</select>

<select class="filter-select" id="parent-filter">
    <option value="">{{ __('admin.all_levels') }}</option>
    <option value="root">{{ __('admin.root_only') }}</option>
    <option value="child">{{ __('admin.subcategories') }}</option>
</select>

<div class="toolbar-sep"></div>

<button onclick="openModal('create')" class="btn-add">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    {{ __('admin.add_category') }}
</button>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width: 32%">@lang('admin.category')</th>
                        <th style="width: 18%">@lang('admin.slug')</th>
                        <th style="width: 16%">@lang('admin.parent_category')</th>
                        <th style="width: 12%">@lang('admin.products')</th>
                        <th style="width: 10%">@lang('admin.status')</th>
                        <th style="text-align:right; width: 12%">@lang('admin.actions')</th>
                    </tr>
                </thead>
                <tbody id="cats-tbody">
                    @forelse($categories as $category)
                    <tr id="cat-row-{{ $category->id }}"
                        data-status="{{ $category->is_active ? 'active' : 'inactive' }}"
                        data-level="{{ $category->parent_id ? 'child' : 'root' }}">
                        <td>
                            <div class="cat-identity {{ $category->parent_id ? 'indent-1' : '' }}">
                                @if($category->parent_id)
                                    <div class="tree-line"></div>
                                @endif
                                <div class="cat-avatar">{{ substr($category->name, 0, 1) }}</div>
                                <div style="min-width:0">
                                    <div class="cat-name">{{ $category->name }}</div>
                                    <div class="cat-id-label">#{{ $category->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="slug-pill">{{ $category->slug }}</span>
                        </td>
                        <td>
                            @if($category->parent)
                                <div class="parent-badge">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    {{ $category->parent->name }}
                                </div>
                            @else
                                <span class="root-label">@lang('admin.root')</span>
                            @endif
                        </td>
                        <td>
                            <div class="count-badge">
                                {{ $category->products_count }}
                                <span>@lang('admin.items')</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $category->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $category->is_active ? __('admin.active_status') : __('admin.inactive_status') }}
                            </span>
                        </td>
                        <td colspan="2">
    <div class="action-group" style="justify-content: flex-end;display: flex;align-items: center;gap: 10px;">
        @if(Auth::user()->isSuperAdmin())
        <button class="btn-icon edit-cat-btn" data-id="{{ $category->id }}" title="{{ __('admin.edit') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </button>
        <button class="btn-icon delete delete-cat-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="{{ __('admin.delete') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
        @else
        <button class="btn-icon edit-cat-btn" data-id="{{ $category->id }}" title="{{ __('admin.view_details') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </button>
        @endif
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="empty-title">@lang('admin.no_categories_found')</div>
                                <div class="empty-sub">@lang('admin.add_first_category')</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="premium-pagination">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ---
     CATEGORY MODAL
--- --}}
<div id="cat-modal" class="modal-overlay" onclick="handleOverlayClick(event)">
    <div class="modal-container">

        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon" id="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <div class="modal-title" id="modal-title">{{ __('admin.add_category') }}</div>
                    <div class="modal-subtitle" id="modal-subtitle">{{ __('admin.fill_category_details') }}</div>
                </div>
            </div>
            <button class="modal-x" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" onclick="switchTab(this,'tab-general')">@lang('admin.general_info')</button>
        </div>

        <div class="modal-body active" id="tab-general">
            <form id="cat-form" onsubmit="return false;">
                <input type="hidden" id="f-id">

                <div class="form-row single">
                    <div class="field">
                        <label>{{ __('admin.category_name') }} <span class="req">*</span></label>
                        <input type="text" id="f-name" placeholder="{{ __('admin.category_name_placeholder') }}" required>
                        <div class="slug-preview">
                            <span class="slug-preview-label">{{ __('admin.slug') }}:</span>
                            <span class="slug-preview-val" id="slug-preview">—</span>
                        </div>
                    </div>
                </div>

                <div class="form-row single">
                    <div class="field">
                        <label>{{ __('admin.parent_category') }}</label>
                        <select id="f-parent">
                            <option value="">— {{ __('admin.root_category') }} —</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row single">
                    <div class="field">
                        <label>{{ __('admin.description') }}</label>
                        <textarea id="f-desc" rows="3" placeholder="{{ __('admin.description_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="form-row single">
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <strong>{{ __('admin.active_status') }}</strong>
                            <span>{{ __('admin.visible_to_customers') }}</span>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" id="f-active" checked>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-foot">
            <button class="btn-del-cancel" style="padding: 10px 20px;" onclick="closeModal()">{{ __('admin.cancel') }}</button>
            <button class="btn-del-confirm" style="padding: 10px 24px; background: var(--accent); box-shadow: 0 4px 12px var(--accent-glow);" id="save-btn" onclick="saveCategory()">
                @if(Auth::user()->isSuperAdmin())
                {{ __('admin.save_category') }}
                @else
                {{ __('admin.view_only') }}
                @endif
            </button>
        </div>

    </div>
</div>

{{-- ---
     DELETE MODAL
--- --}}
<div id="del-modal" class="modal-overlay">
    <div class="modal-container" style="max-width:400px;">
        <div class="confirm-body">
            <div class="danger-ico">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3>@lang('admin.delete_category')</h3>
            <p id="del-msg">@lang('admin.delete_confirm_message')</p>
            <div class="confirm-btns">
                <button class="btn-del-cancel" onclick="closeDeleteModal()">@lang('admin.cancel')</button>
                <button class="btn-del-confirm" id="del-btn" onclick="executeDelete()">@lang('admin.delete')</button>
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
document.addEventListener('DOMContentLoaded', () => {
    // Event delegation for action buttons
    document.addEventListener('click', e => {
        const editBtn = e.target.closest('.edit-cat-btn');
        if (editBtn) editCategory(editBtn.dataset.id);

        const delBtn = e.target.closest('.delete-cat-btn');
        if (delBtn) promptDelete(delBtn.dataset.id, delBtn.dataset.name);
    });
});

/* ---
   STATE
--- */
let isEditing    = false;
let deleteTarget = null;

/* ═══════════════════════════════════════
   MODAL OPEN / CLOSE
═══════════════════════════════════════ */
const modal    = document.getElementById('cat-modal');
const delModal = document.getElementById('del-modal');

function openModal(mode, data = null) {
    isEditing = mode === 'edit';

    document.getElementById('modal-title').textContent    = isEditing
        ? '{{ __("admin.edit_category") }}'
        : '{{ __("admin.add_category") }}';
    document.getElementById('modal-subtitle').textContent = isEditing
        ? '{{ __("admin.edit_category_subtitle") }}'
        : '{{ __("admin.add_category_subtitle") }}';

    const editIco = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';
    const addIco  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
    document.getElementById('modal-icon').innerHTML = isEditing ? editIco : addIco;

    // Reset tabs to first
    document.querySelectorAll('.modal-tab')[0].click();

    /* Reset */
    document.getElementById('cat-form').reset();
    document.getElementById('f-id').value = '';
    document.getElementById('slug-preview').textContent = '—';

    if (isEditing && data) {
        document.getElementById('f-id').value    = data.id;
        document.getElementById('f-name').value  = data.name;
        document.getElementById('f-parent').value = data.parent_id || '';
        document.getElementById('f-desc').value  = data.description || '';
        document.getElementById('f-active').checked = !!data.is_active;
        document.getElementById('slug-preview').textContent = data.slug || slugify(data.name);
    }

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    setTimeout(() => document.getElementById('f-name').focus(), 230);
}

function switchTab(btn, tabId) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.modal-body').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function closeModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target === modal) closeModal();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal(); closeDeleteModal(); }
});

/* ═══════════════════════════════════════
   SLUG PREVIEW — live as user types
═══════════════════════════════════════ */
function slugify(str) {
    return str.toLowerCase()
              .trim()
              .replace(/[^\w\s-]/g, '')
              .replace(/[\s_-]+/g, '-')
              .replace(/^-+|-+$/g, '');
}

document.getElementById('f-name').addEventListener('input', function () {
    const slug = slugify(this.value);
    document.getElementById('slug-preview').textContent = slug || '—';
});

/* ═══════════════════════════════════════
   EDIT CATEGORY
═══════════════════════════════════════ */
async function editCategory(id) {
    try {
        const res = await fetch(`/admin/categories/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error();
        const data = await res.json();
        openModal('edit', data);
    } catch {
        showToast('{{ __("admin.error_loading") }}', 'error');
    }
}

/* ═══════════════════════════════════════
   SAVE CATEGORY
═══════════════════════════════════════ */
async function saveCategory() {
    const btn  = document.getElementById('save-btn');
    const id   = document.getElementById('f-id').value;
    const name = document.getElementById('f-name').value.trim();

    if (!name) {
        showToast('{{ __("admin.fill_required_fields") }}', 'error');
        return;
    }

    const payload = {
        name,
        parent_id:   document.getElementById('f-parent').value   || null,
        description: document.getElementById('f-desc').value,
        is_active:   document.getElementById('f-active').checked,
    };

    btn.disabled = true;
    btn.innerHTML = '{{ __("admin.saving") }}...';

    try {
        const url    = isEditing ? `/admin/categories/${id}` : '/admin/categories';
        const method = isEditing ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Error');

        showToast(data.message || '{{ __("admin.saved_successfully") }}', 'success');
        closeModal();
        setTimeout(() => location.reload(), 600);
    } catch (err) {
        showToast(err.message || '{{ __("admin.error_saving") }}', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = isEditing ? '{{ __("admin.save_category") }}' : '{{ __("admin.add_category") }}';
    }
}

/* ═══════════════════════════════════════
   DELETE
═══════════════════════════════════════ */
function promptDelete(id, name) {
    deleteTarget = id;
    document.getElementById('del-msg').textContent =
        `{{ __("admin.delete_confirm_prefix") }} "${name}" {{ __("admin.delete_confirm_suffix") }}`;
    delModal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    delModal.classList.remove('open');
    deleteTarget = null;
    document.body.style.overflow = '';
}

async function executeDelete() {
    if (!deleteTarget) return;
    const btn = document.getElementById('del-btn');
    btn.textContent = '{{ __("admin.deleting") }}...';
    btn.disabled    = true;

    try {
        const res = await fetch(`/admin/categories/${deleteTarget}`, {
            method: 'DELETE',
            headers: {
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Error');

        showToast(data.message || '{{ __("admin.deleted_successfully") }}', 'success');

        const row = document.getElementById(`cat-row-${deleteTarget}`);
        if (row) {
            row.style.transition = 'opacity 0.3s, transform 0.3s';
            row.style.opacity    = '0';
            row.style.transform  = 'translateX(8px)';
            setTimeout(() => row.remove(), 300);
        }
        closeDeleteModal();
    } catch (err) {
        showToast(err.message || '{{ __("admin.error_deleting") }}', 'error');
        btn.textContent = '{{ __("admin.delete") }}';
        btn.disabled    = false;
    }
}

/* ═══════════════════════════════════════
   LIVE SEARCH + FILTERS
═══════════════════════════════════════ */
function filterTable() {
    const term   = document.getElementById('cat-search').value.toLowerCase();
    const status = document.getElementById('status-filter').value;
    const level  = document.getElementById('parent-filter').value;

    document.querySelectorAll('#cats-tbody tr[id^="cat-row-"]').forEach(row => {
        const text      = row.innerText.toLowerCase();
        const rowStatus = row.dataset.status || '';
        const rowLevel  = row.dataset.level  || '';

        const okTxt    = !term   || text.includes(term);
        const okStatus = !status || rowStatus === status;
        const okLevel  = !level  || rowLevel  === level;

        row.style.display = (okTxt && okStatus && okLevel) ? '' : 'none';
    });
}

document.getElementById('cat-search').addEventListener('input', filterTable);
document.getElementById('status-filter').addEventListener('change', filterTable);
document.getElementById('parent-filter').addEventListener('change', filterTable);

/* ---
   TOAST
--- */
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