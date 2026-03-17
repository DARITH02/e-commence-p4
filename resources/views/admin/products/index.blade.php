@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Product Catalog')

@push('styles')
<style>
/* ═══════════════════════════════════════
   TOKENS
═══════════════════════════════════════ */
:root {
    --bg:           #0c0e16;
    --surface:      #12141f;
    --surface-2:    #181b28;
    --surface-3:    #1e2133;
    --border:       rgba(255,255,255,0.06);
    --border-2:     rgba(255,255,255,0.10);
    --text:         #eceef5;
    --text-2:       #8b92b0;
    --text-3:       #4a5072;
    --accent:       #4f6ef7;
    --accent-dim:   rgba(79,110,247,0.10);
    --accent-mid:   rgba(79,110,247,0.20);
    --green:        #22c97a;
    --green-dim:    rgba(34,201,122,0.10);
    --red:          #f04747;
    --red-dim:      rgba(240,71,71,0.10);
    --amber:        #f59e0b;
    --amber-dim:    rgba(245,158,11,0.10);
    --radius:       14px;
    --radius-lg:    20px;
    --radius-xl:    28px;
    --shadow:       0 2px 8px rgba(0,0,0,0.4), 0 8px 32px rgba(0,0,0,0.3);
    --transition:   0.18s cubic-bezier(0.4,0,0.2,1);
}

html.light {
    --bg:           #f1f3fa;
    --surface:      #ffffff;
    --surface-2:    #f7f8fd;
    --surface-3:    #eef0f8;
    --border:       rgba(0,0,0,0.07);
    --border-2:     rgba(0,0,0,0.12);
    --text:         #0d1021;
    --text-2:       #4a5072;
    --text-3:       #9aa0b8;
    --accent:       #3558e8;
    --accent-dim:   rgba(53,88,232,0.08);
    --accent-mid:   rgba(53,88,232,0.16);
    --green:        #059669;
    --green-dim:    rgba(5,150,105,0.08);
    --red:          #dc2626;
    --red-dim:      rgba(220,38,38,0.08);
    --shadow:       0 1px 4px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
}

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg) !important; font-family: 'DM Sans', system-ui, sans-serif; color: var(--text); }
.content-inner { max-width: none !important; padding: 0 !important; }
button { font-family: inherit; cursor: pointer; border: none; background: none; }
input, select, textarea { font-family: inherit; outline: none; }
input::placeholder, textarea::placeholder { color: var(--text-3); }

/* ── Page ── */
.page {
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ═══════════════════════════════════════
   TOPBAR
═══════════════════════════════════════ */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.topbar-left h1 {
    font-size: 26px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.03em;
    line-height: 1;
}
.topbar-left p {
    font-size: 12px;
    color: var(--text-3);
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.live-dot {
    width: 6px; height: 6px;
    background: var(--green);
    border-radius: 50%;
    animation: pulse 2s ease infinite;
}
@keyframes pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(34,201,122,0.5); }
    50%      { box-shadow: 0 0 0 5px rgba(34,201,122,0); }
}

.topbar-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

/* Search */
.search-wrap { position: relative; }
.search-wrap svg {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    width: 16px; height: 16px;
    color: var(--text-3);
    pointer-events: none;
    transition: color var(--transition);
}
.search-wrap:focus-within svg { color: var(--accent); }
.search-input {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 10px 16px 10px 40px;
    color: var(--text);
    font-size: 13px;
    font-weight: 500;
    width: 260px;
    transition: border-color var(--transition), box-shadow var(--transition), width var(--transition);
}
.search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
    width: 300px;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 700;
    transition: all var(--transition);
    white-space: nowrap;
    text-decoration: none;
}
.btn svg { width: 15px; height: 15px; flex-shrink: 0; }

.btn-primary {
    background: var(--accent);
    color: #fff;
    box-shadow: 0 4px 14px rgba(79,110,247,0.25);
}
.btn-primary:hover {
    background: #5d7cf8;
    box-shadow: 0 6px 20px rgba(79,110,247,0.4);
    transform: translateY(-1px);
}
.btn-ghost {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-2);
}
.btn-ghost:hover { border-color: var(--border-2); color: var(--text); background: var(--surface-2); }

/* ═══════════════════════════════════════
   STATS ROW
═══════════════════════════════════════ */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
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
.stat-ico {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-ico svg { width: 18px; height: 18px; }
.stat-ico.blue   { background: var(--accent-dim); color: var(--accent); }
.stat-ico.green  { background: var(--green-dim);  color: var(--green);  }
.stat-ico.amber  { background: var(--amber-dim);  color: var(--amber);  }
.stat-ico.red    { background: var(--red-dim);    color: var(--red);    }
.stat-label { font-size: 10px; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.08em; font-family: 'DM Mono', monospace; }
.stat-val   { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; line-height: 1.1; }

/* ═══════════════════════════════════════
   FILTERS BAR
═══════════════════════════════════════ */
.filters-bar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.filter-label {
    font-size: 10px; font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-family: 'DM Mono', monospace;
    margin-right: 4px;
    flex-shrink: 0;
}
.filter-chip {
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid var(--border);
    background: var(--surface-2);
    color: var(--text-2);
    cursor: pointer;
    transition: all var(--transition);
}
.filter-chip:hover { border-color: var(--border-2); color: var(--text); }
.filter-chip.active {
    background: var(--accent-dim);
    border-color: rgba(79,110,247,0.3);
    color: var(--accent);
}
.filter-divider { width: 1px; height: 20px; background: var(--border); margin: 0 4px; flex-shrink: 0; }
.filter-select {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 6px 32px 6px 12px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-2);
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%234a5072' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    transition: all var(--transition);
}
.filter-select:focus { border-color: var(--accent); color: var(--text); outline: none; }
.filter-count {
    margin-left: auto;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-3);
    font-family: 'DM Mono', monospace;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════
   TABLE CARD
═══════════════════════════════════════ */
.table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }

thead th {
    padding: 12px 20px;
    text-align: left;
    font-family: 'DM Mono', monospace;
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--text-3);
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
thead th:last-child { text-align: right; }
th.sortable { cursor: pointer; user-select: none; }
th.sortable:hover { color: var(--text-2); }
th.sortable::after { content: ' ↕'; opacity: 0.4; font-size: 8px; }
th.sort-asc::after  { content: ' ↑'; opacity: 1; color: var(--accent); }
th.sort-desc::after { content: ' ↓'; opacity: 1; color: var(--accent); }

tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background var(--transition);
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--surface-2); }
tbody td { padding: 14px 20px; vertical-align: middle; }

/* Product cell */
.prod-cell { display: flex; align-items: center; gap: 12px; }
.prod-thumb {
    width: 44px; height: 44px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--surface-3);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
.prod-thumb svg { width: 18px; height: 18px; color: var(--text-3); }
.prod-name { font-size: 13px; font-weight: 700; color: var(--text); line-height: 1; }
.prod-sku  { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--text-3); margin-top: 3px; }

/* Category pills */
.cat-pills { display: flex; flex-wrap: wrap; gap: 5px; }
.cat-pill {
    padding: 3px 9px;
    border-radius: 100px;
    font-size: 10px;
    font-weight: 700;
    background: var(--surface-3);
    border: 1px solid var(--border);
    color: var(--text-2);
    white-space: nowrap;
}

/* Price */
.price-main { font-size: 14px; font-weight: 800; color: var(--text); }
.price-sale {
    font-size: 11px; font-weight: 700;
    color: var(--green);
    background: var(--green-dim);
    padding: 2px 7px;
    border-radius: 100px;
    display: inline-block;
    margin-top: 3px;
}
.price-was { font-size: 10px; color: var(--text-3); text-decoration: line-through; }

/* Status badge */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700;
    padding: 4px 10px; border-radius: 100px;
    border: 1px solid transparent; white-space: nowrap;
}
.status-badge::before {
    content: ''; width: 5px; height: 5px;
    border-radius: 50%; background: currentColor; flex-shrink: 0;
}
.status-active   { background: var(--green-dim); color: var(--green); border-color: rgba(34,201,122,0.2); }
.status-inactive { background: var(--surface-3); color: var(--text-3); border-color: var(--border); }

/* Stock */
.stock-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700;
    padding: 4px 10px; border-radius: 100px;
    border: 1px solid transparent;
}
.stock-badge::before {
    content: ''; width: 5px; height: 5px;
    border-radius: 50%; background: currentColor; flex-shrink: 0;
}
.stock-in  { background: var(--green-dim); color: var(--green); border-color: rgba(34,201,122,0.2); }
.stock-low { background: var(--amber-dim); color: var(--amber); border-color: rgba(245,158,11,0.2); }
.stock-out { background: var(--red-dim);   color: var(--red);   border-color: rgba(240,71,71,0.2); }

/* Actions */
.actions { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
.icon-btn {
    width: 32px; height: 32px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--border);
    background: var(--surface-2);
    color: var(--text-2);
    transition: all var(--transition);
    cursor: pointer;
}
.icon-btn:hover { background: var(--surface-3); color: var(--text); border-color: var(--border-2); }
.icon-btn.danger:hover { background: var(--red-dim); color: var(--red); border-color: rgba(240,71,71,0.3); }
.icon-btn svg { width: 14px; height: 14px; }

/* Empty state */
.empty-state {
    padding: 80px 40px;
    text-align: center;
    display: flex; flex-direction: column;
    align-items: center; gap: 14px;
}
.empty-ico {
    width: 56px; height: 56px; border-radius: 16px;
    background: var(--surface-2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-3);
}
.empty-ico svg { width: 24px; height: 24px; }
.empty-state h3 { font-size: 15px; font-weight: 700; color: var(--text-2); }
.empty-state p  { font-size: 12px; color: var(--text-3); max-width: 280px; line-height: 1.6; }

/* Pagination */
.pager {
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    background: var(--surface-2);
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.pager-info { font-size: 11px; color: var(--text-3); font-family: 'DM Mono', monospace; }

/* ═══════════════════════════════════════
   MODAL
═══════════════════════════════════════ */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; pointer-events: none;
    transition: opacity 0.22s ease;
}
.modal-overlay.open { opacity: 1; pointer-events: all; }

.modal-shell {
    background: var(--surface);
    border: 1px solid var(--border-2);
    border-radius: var(--radius-xl);
    width: 100%; max-width: 680px; max-height: 90vh;
    display: flex; flex-direction: column;
    box-shadow: 0 24px 80px rgba(0,0,0,0.5);
    transform: translateY(14px) scale(0.98);
    transition: transform 0.22s cubic-bezier(0.16,1,0.3,1);
    overflow: hidden;
}
.modal-overlay.open .modal-shell { transform: translateY(0) scale(1); }

.modal-head {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center;
    justify-content: space-between; gap: 16px;
    flex-shrink: 0;
}
.modal-head-left { display: flex; align-items: center; gap: 12px; }
.modal-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--accent-dim);
    border: 1px solid rgba(79,110,247,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); flex-shrink: 0;
}
.modal-icon svg { width: 18px; height: 18px; }
.modal-title    { font-size: 17px; font-weight: 800; color: var(--text); letter-spacing: -0.01em; }
.modal-subtitle { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.modal-x {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--surface-2); border: 1px solid var(--border);
    color: var(--text-2);
    display: flex; align-items: center; justify-content: center;
    transition: all var(--transition); cursor: pointer; flex-shrink: 0;
}
.modal-x:hover { background: var(--red-dim); color: var(--red); border-color: rgba(240,71,71,0.3); }
.modal-x svg { width: 16px; height: 16px; }

/* Tabs */
.modal-tabs {
    display: flex; gap: 2px;
    padding: 0 26px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
    flex-shrink: 0;
}
.modal-tab {
    padding: 11px 15px;
    font-size: 12px; font-weight: 700;
    color: var(--text-3);
    border-bottom: 2px solid transparent;
    transition: all var(--transition);
    cursor: pointer; white-space: nowrap; background: none; border-left: none; border-right: none; border-top: none;
}
.modal-tab:hover { color: var(--text-2); }
.modal-tab.active { color: var(--accent); border-bottom-color: var(--accent); }

.modal-body {
    padding: 22px 26px;
    overflow-y: auto; flex: 1; display: none;
}
.modal-body.active { display: block; }
.modal-body::-webkit-scrollbar { width: 4px; }
.modal-body::-webkit-scrollbar-track { background: transparent; }
.modal-body::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 2px; }

.modal-foot {
    padding: 14px 26px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px;
    background: var(--surface-2); flex-shrink: 0;
}
.modal-foot-left { font-size: 11px; color: var(--text-3); }
.modal-foot-right { display: flex; gap: 10px; }

/* Form */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.form-row.single { grid-template-columns: 1fr; }

.field { display: flex; flex-direction: column; gap: 7px; }
.field label {
    font-size: 10.5px; font-weight: 700;
    color: var(--text-2);
    text-transform: uppercase; letter-spacing: 0.07em;
    font-family: 'DM Mono', monospace;
    display: flex; align-items: center; gap: 5px;
}
.field label .req { color: var(--red); }
.field input, .field select, .field textarea {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 10px 14px;
    color: var(--text);
    font-size: 13px; font-weight: 500;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
}
.field input:focus, .field select:focus, .field textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
    background: var(--surface);
    outline: none;
}
.field textarea { resize: vertical; min-height: 96px; line-height: 1.6; }
.field select[multiple] { min-height: 108px; }
.field .hint { font-size: 11px; color: var(--text-3); line-height: 1.5; }

/* Toggle */
.toggle-row {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius); gap: 12px;
}
.toggle-info { display: flex; flex-direction: column; gap: 2px; }
.toggle-info strong { font-size: 13px; font-weight: 700; color: var(--text); }
.toggle-info span   { font-size: 11px; color: var(--text-3); }
.toggle { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-track {
    position: absolute; inset: 0;
    background: var(--surface-3); border: 1px solid var(--border);
    border-radius: 100px; cursor: pointer;
    transition: background var(--transition), border-color var(--transition);
}
.toggle input:checked ~ .toggle-track { background: var(--accent); border-color: var(--accent); }
.toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 16px; height: 16px;
    background: var(--text-3); border-radius: 50%;
    pointer-events: none;
    transition: transform var(--transition), background var(--transition);
}
.toggle input:checked ~ .toggle-track .toggle-thumb { transform: translateX(18px); background: #fff; }

/* Image upload */
.upload-zone {
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    padding: 28px 20px;
    text-align: center; cursor: pointer;
    transition: all var(--transition); position: relative;
}
.upload-zone:hover, .upload-zone.drag-over { border-color: var(--accent); background: var(--accent-dim); }
.upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-ico {
    width: 44px; height: 44px; border-radius: 13px;
    background: var(--surface-2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px; color: var(--text-3);
}
.upload-ico svg { width: 20px; height: 20px; }
.upload-zone h4 { font-size: 13px; font-weight: 700; color: var(--text-2); }
.upload-zone p  { font-size: 11px; color: var(--text-3); margin-top: 4px; line-height: 1.5; }
.upload-tag {
    display: inline-block; margin-top: 10px;
    padding: 4px 12px; background: var(--accent-dim);
    color: var(--accent); border-radius: 100px;
    font-size: 11px; font-weight: 700;
    border: 1px solid rgba(79,110,247,0.2);
}
.img-preview-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-top: 14px; }
.img-preview {
    aspect-ratio: 1; border-radius: 12px;
    overflow: hidden; border: 1px solid var(--border);
    position: relative; background: var(--surface-3);
}
.img-preview img { width: 100%; height: 100%; object-fit: cover; }
.img-preview .rm-img {
    position: absolute; top: 4px; right: 4px;
    width: 22px; height: 22px; border-radius: 6px;
    background: rgba(0,0,0,0.7); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; opacity: 0; transition: opacity var(--transition);
    cursor: pointer; border: none;
}
.img-preview:hover .rm-img { opacity: 1; }

/* Section head */
.section-head { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.section-head span { font-size: 11px; font-weight: 800; color: var(--text-2); text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
.section-line { flex: 1; height: 1px; background: var(--border); }

/* Discount preview */
.discount-preview {
    margin-top: 4px; padding: 11px 14px;
    background: var(--green-dim);
    border: 1px solid rgba(34,201,122,0.2);
    border-radius: var(--radius);
    font-size: 12px; color: var(--green); font-weight: 700;
}
.margin-display {
    padding: 10px 14px;
    background: var(--surface-3); border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 13px; font-weight: 700; color: var(--text-2);
}

/* Delete confirm */
.confirm-body {
    padding: 36px 32px;
    text-align: center;
    display: flex; flex-direction: column;
    align-items: center; gap: 14px;
}
.danger-ico {
    width: 58px; height: 58px; border-radius: 17px;
    background: var(--red-dim); border: 1px solid rgba(240,71,71,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--red);
}
.danger-ico svg { width: 26px; height: 26px; }
.confirm-body h3 { font-size: 18px; font-weight: 800; color: var(--text); }
.confirm-body p  { font-size: 13px; color: var(--text-2); line-height: 1.6; max-width: 300px; }
.confirm-btns { display: flex; gap: 10px; }

/* Toast */
.toast-wrap {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    display: flex; flex-direction: column; gap: 8px; pointer-events: none;
}
.toast {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px;
    background: var(--surface); border: 1px solid var(--border-2);
    border-radius: var(--radius-lg);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    font-size: 13px; font-weight: 600; color: var(--text);
    animation: toastIn 0.3s cubic-bezier(0.16,1,0.3,1);
    pointer-events: all; min-width: 220px; max-width: 340px;
}
.toast.removing { animation: toastOut 0.25s ease forwards; }
.toast-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.toast.success .toast-dot { background: var(--green); }
.toast.error   .toast-dot { background: var(--red); }
@keyframes toastIn  { from { opacity:0; transform:translateX(16px) scale(0.96); } to { opacity:1; transform:translateX(0) scale(1); } }
@keyframes toastOut { to   { opacity:0; transform:translateX(16px) scale(0.96); } }

/* Responsive */
@media (max-width: 640px) {
    .modal-overlay { align-items: flex-end; padding: 0; }
    .modal-shell { max-width: 100%; border-radius: var(--radius-lg) var(--radius-lg) 0 0; align-self: flex-end; }
    .form-row { grid-template-columns: 1fr; }
    .img-preview-grid { grid-template-columns: repeat(3,1fr); }
}
</style>
@endpush

@section('content')
<div class="page">

    {{-- ── Topbar ── --}}
    <div class="topbar">
        <div class="topbar-left">
            <h1>@lang('admin.products')</h1>
            <p><span class="live-dot"></span>{{ $products->total() }} @lang('admin.total_products')</p>
        </div>
        <div class="topbar-right">
            <div class="search-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input class="search-input" id="prod-search" type="text" placeholder="@lang('admin.search_products')…">
            </div>
            <button class="btn btn-ghost" onclick="exportCSV()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                @lang('admin.export')
            </button>
            <button class="btn btn-primary" onclick="openProductModal('create')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                @lang('admin.add_product')
            </button>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row">
        @php
            $totalCount    = $products->total();
            $activeCount   = $products->getCollection()->where('is_active', true)->count();
            $inactiveCount = $products->getCollection()->where('is_active', false)->count();
            $lowStockCount = $products->getCollection()->where('stock', '<', 10)->count();
        @endphp
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
            <div><div class="stat-label">@lang('admin.low_stock')</div><div class="stat-val">{{ number_format($lowStockCount) }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div><div class="stat-label">@lang('admin.inactive')</div><div class="stat-val">{{ number_format($inactiveCount) }}</div></div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <div class="filters-bar">
        <span class="filter-label">@lang('admin.filter')</span>
        <button class="filter-chip active" onclick="filterStatus(this,'all')">@lang('admin.all')</button>
        <button class="filter-chip" onclick="filterStatus(this,'active')">@lang('admin.active')</button>
        <button class="filter-chip" onclick="filterStatus(this,'inactive')">@lang('admin.inactive')</button>
        <div class="filter-divider"></div>
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
        <span class="filter-count" id="row-count"></span>
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
                                    <div class="prod-sku">{{ $product->sku }}</div>
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
                                $stock = $product->stock ?? 0;
                                $sc = $stock > 10 ? 'stock-in' : ($stock > 0 ? 'stock-low' : 'stock-out');
                                $sl = $stock > 10 ? __('admin.in_stock') : ($stock > 0 ? __('admin.low_stock') : __('admin.out_of_stock'));
                            @endphp
                            <span class="stock-badge {{ $sc }}">{{ $sl }}</span>
                            @if($stock > 0)
                                <div style="font-size:10px;color:var(--text-3);margin-top:3px;font-family:'DM Mono',monospace;">{{ $stock }} @lang('admin.units')</div>
                            @endif
                        </td>

                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </td>

                        <td>
                            <div class="actions">
                                <button class="icon-btn" onclick="editProduct({{ $product->id }})" title="@lang('admin.edit')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button class="icon-btn danger" onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" title="@lang('admin.delete')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
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
        <div class="pager">
            <span class="pager-info">{{ $products->firstItem() }}–{{ $products->lastItem() }} @lang('admin.of') {{ $products->total() }}</span>
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>{{-- /page --}}

{{-- ══════════════════════════
     PRODUCT MODAL
══════════════════════════ --}}
<div class="modal-overlay" id="prod-modal" onclick="overlayClick(event)">
    <div class="modal-shell">

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
        </div>

        <div class="modal-foot">
            <div class="modal-foot-left" id="modal-foot-hint">@lang('admin.required_fields_hint')</div>
            <div class="modal-foot-right">
                <button class="btn btn-ghost" onclick="closeModal('prod-modal')">@lang('admin.cancel')</button>
                <button class="btn btn-primary" id="save-btn" onclick="saveProduct()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @lang('admin.save_product')
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Delete modal ── --}}
<div class="modal-overlay" id="delete-modal" onclick="overlayClick(event)">
    <div class="modal-shell" style="max-width:400px;">
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

<div class="toast-wrap" id="toast-wrap"></div>

@endsection

@push('scripts')
<script>
/* ── State ── */
let isEditing = false, editingId = null;
let allRows = [], activeFilter = 'all', activeCat = '';

document.addEventListener('DOMContentLoaded', () => {
    allRows = Array.from(document.querySelectorAll('#prod-tbody tr[id^="row-"]'));
    updateRowCount();
    initPricingWatchers();
    initDragDrop();
    document.getElementById('prod-search').addEventListener('input', applyFilters);
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

    document.getElementById('modal-title').textContent    = isEditing ? '@lang("admin.edit_product")' : '@lang("admin.add_product")';
    document.getElementById('modal-subtitle').textContent = isEditing ? '@lang("admin.update_product_info")' : '@lang("admin.fill_product_details")';

    const editIco = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';
    const addIco  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
    document.getElementById('modal-icon').innerHTML = isEditing ? editIco : addIco;

    const saveBtn = document.getElementById('save-btn');
    saveBtn.innerHTML = isEditing
        ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>@lang("admin.update_product")'
        : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>@lang("admin.save_product")';

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
        document.getElementById('img-preview-grid').innerHTML = '';
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
        document.getElementById('img-preview-grid').innerHTML = '';
        document.getElementById('discount-preview').style.display = 'none';
        document.getElementById('margin-val').textContent = '—';
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
        showToast('@lang("admin.failed_load_product")', 'error');
    }
}

/* ── Save ── */
async function saveProduct() {
    const name  = document.getElementById('prod-name').value.trim();
    const sku   = document.getElementById('prod-sku').value.trim();
    const price = parseFloat(document.getElementById('prod-price').value);

    if (!name || !sku || isNaN(price) || price < 0) {
        showToast('@lang("admin.fill_required_fields")', 'error');
        document.querySelectorAll('.modal-tab')[0].click();
        return;
    }

    const saleVal   = document.getElementById('prod-sale-price').value;
    const saleParsed = saleVal ? parseFloat(saleVal) : null;

    const payload = {
        name, sku, price,
        sale_price: saleParsed,
        description: document.getElementById('prod-desc').value,
        stock: parseInt(document.getElementById('prod-stock').value) || 0,
        is_active: document.getElementById('prod-active').checked,
        categories: Array.from(document.getElementById('prod-categories').selectedOptions).map(o => o.value),
    };

    const url    = isEditing ? `/admin/products/${editingId}` : '/admin/products';
    const method = isEditing ? 'PUT' : 'POST';
    const btn    = document.getElementById('save-btn');
    btn.disabled = true; btn.style.opacity = '0.6';

    try {
        const res  = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error');
        showToast(json.message || '@lang("admin.saved_successfully")', 'success');
        closeModal('prod-modal');
        setTimeout(() => location.reload(), 900);
    } catch (e) {
        showToast(e.message || '@lang("admin.save_failed")', 'error');
    } finally {
        btn.disabled = false; btn.style.opacity = '';
    }
}

/* ── Delete ── */
function openDeleteModal(id, name) {
    document.getElementById('delete-msg').textContent = `@lang("admin.delete_confirm_prefix") "${name}"? @lang("admin.delete_irreversible")`;
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
        showToast(json.message || '@lang("admin.deleted_successfully")', 'success');
    } catch (e) {
        showToast(e.message || '@lang("admin.delete_failed")', 'error');
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
    if (el) el.textContent = `${n !== undefined ? n : allRows.length} @lang("admin.results")`;
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
function handleImages(e) { renderPreviews(e.target.files); }
function renderPreviews(files) {
    const grid = document.getElementById('img-preview-grid');
    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const r = new FileReader();
        r.onload = ev => {
            const d = document.createElement('div');
            d.className = 'img-preview';
            d.innerHTML = `<img src="${ev.target.result}" alt=""><button class="rm-img" onclick="this.parentElement.remove()" title="Remove">✕</button>`;
            grid.appendChild(d);
        };
        r.readAsDataURL(file);
    });
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
    showToast('@lang("admin.export_ready")', 'success');
}

/* ── Toast ── */
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toast-wrap');
    const el   = document.createElement('div');
    el.className = `toast ${type}`;
    el.innerHTML = `<div class="toast-dot"></div><span>${msg}</span>`;
    wrap.appendChild(el);
    setTimeout(() => { el.classList.add('removing'); setTimeout(() => el.remove(), 280); }, 3200);
}
</script>
@endpush