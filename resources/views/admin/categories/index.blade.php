@extends('layouts.admin')

@section('title', __('admin.categories'))
@section('page_title', __('admin.categories'))

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS — matches dashboard exactly
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
    --shadow-card: 0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
    --shadow-hover:0 4px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(79,114,245,0.25);
    --transition:  0.18s cubic-bezier(0.4,0,0.2,1);
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
    --red:        #c92b2b;
    --red-dim:    rgba(201,43,43,0.08);
    --shadow-card:0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
}

body { background: var(--surface-0) !important; }
.content-inner { max-width: none !important; }

/* ─── Page animation ─── */
.categories-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: fadeUp 0.45s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─── Page header ─── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.page-heading { display:flex; flex-direction:column; gap:4px; }
.page-heading h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: -0.02em;
    line-height: 1;
}
.page-heading p {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    color: var(--text-3);
    letter-spacing: 0.06em;
    display: flex;
    align-items: center;
    gap: 6px;
}
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
    background: var(--surface-1);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-lg);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-card);
}
.search-wrap { position:relative; flex:1; min-width:220px; }
.search-wrap svg {
    position:absolute; left:13px; top:50%;
    transform:translateY(-50%);
    width:15px; height:15px;
    color:var(--text-3); pointer-events:none;
}
.search-wrap input {
    width:100%;
    background:var(--surface-2);
    border:1px solid var(--border-1);
    border-radius:var(--radius-md);
    padding:9px 14px 9px 38px;
    color:var(--text-1);
    font-family:'DM Sans', sans-serif;
    font-size:13px; font-weight:500;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.search-wrap input::placeholder { color:var(--text-3); }
.search-wrap input:focus {
    outline:none;
    border-color:var(--blue);
    box-shadow:0 0 0 3px var(--blue-dim);
    background:var(--surface-3);
}
.filter-select {
    background:var(--surface-2);
    border:1px solid var(--border-1);
    border-radius:var(--radius-md);
    padding:9px 14px;
    color:var(--text-2);
    font-family:'DM Sans', sans-serif;
    font-size:13px; font-weight:500;
    cursor:pointer;
    transition:border-color var(--transition);
}
.filter-select:focus { outline:none; border-color:var(--blue); }
.toolbar-sep { width:1px; height:28px; background:var(--border-1); flex-shrink:0; }
.toolbar-stats { display:flex; align-items:center; gap:8px; margin-left:auto; }
.stat-pill {
    display:flex; align-items:center; gap:5px;
    padding:5px 10px;
    border-radius:var(--radius-sm);
    font-family:'DM Mono', monospace;
    font-size:10px; font-weight:700;
    letter-spacing:0.04em;
    white-space:nowrap;
}
.stat-pill.total  { background:var(--blue-dim);  color:var(--blue); }
.stat-pill.active { background:var(--green-dim); color:var(--green); }
.stat-pill.off    { background:var(--red-dim);   color:var(--red); }
.stat-dot { width:5px; height:5px; border-radius:50%; background:currentColor; }

/* ─── Add button ─── */
.btn-add {
    display:inline-flex; align-items:center; gap:8px;
    background:var(--blue);
    color:#fff;
    border:none;
    border-radius:var(--radius-md);
    padding:9px 18px;
    font-family:'DM Sans', sans-serif;
    font-size:12px; font-weight:700;
    letter-spacing:0.02em;
    cursor:pointer;
    transition:background var(--transition), transform var(--transition), box-shadow var(--transition);
    box-shadow:0 4px 12px rgba(79,114,245,0.25);
    white-space:nowrap; flex-shrink:0;
}
.btn-add svg { width:14px; height:14px; flex-shrink:0; }
.btn-add:hover {
    background:#3d5de0;
    transform:translateY(-1px);
    box-shadow:0 6px 18px rgba(79,114,245,0.35);
}
.btn-add:active { transform:translateY(0); }

/* ─── Table card ─── */
.table-card {
    background:var(--surface-1);
    border:1px solid var(--border-1);
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow-card);
    overflow:hidden;
}
.table-scroll { overflow-x:auto; }
table { width:100%; border-collapse:collapse; }

thead th {
    padding:10px 20px;
    font-family:'DM Mono', monospace;
    font-size:9.5px; text-transform:uppercase;
    letter-spacing:0.1em; color:var(--text-3);
    font-weight:700;
    background:var(--surface-2);
    text-align:left; white-space:nowrap;
    border-bottom:1px solid var(--border-1);
    user-select:none;
}
thead th:last-child { text-align:right; }

tbody tr {
    border-top:1px solid var(--border-1);
    transition:background var(--transition);
    cursor:pointer;
}
tbody tr:hover { background:var(--surface-2); }
tbody td { padding:13px 20px; vertical-align:middle; }

/* Identity cell */
.cat-identity { display:flex; align-items:center; gap:12px; }
.cat-avatar {
    width:38px; height:38px;
    border-radius:var(--radius-md);
    background:var(--blue-dim);
    border:1px solid rgba(79,114,245,0.2);
    display:flex; align-items:center; justify-content:center;
    font-family:'DM Mono', monospace;
    font-size:13px; font-weight:700;
    color:var(--blue);
    flex-shrink:0;
    text-transform:uppercase;
    transition:all var(--transition);
}
tbody tr:hover .cat-avatar {
    background:var(--blue-mid);
    border-color:rgba(79,114,245,0.4);
}
.cat-name {
    font-size:13px; font-weight:700; color:var(--text-1);
    line-height:1; white-space:nowrap;
    overflow:hidden; text-overflow:ellipsis; max-width:200px;
}
.cat-id-label {
    font-family:'DM Mono', monospace;
    font-size:9.5px; color:var(--text-3);
    margin-top:3px; letter-spacing:0.04em;
}

/* Slug pill */
.slug-pill {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px;
    background:var(--surface-3);
    border:1px solid var(--border-1);
    border-radius:var(--radius-sm);
    font-family:'DM Mono', monospace;
    font-size:10px; font-weight:600; color:var(--text-3);
    letter-spacing:0.04em;
    white-space:nowrap; max-width:160px;
    overflow:hidden; text-overflow:ellipsis;
}

/* Parent badge */
.parent-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:600; color:var(--text-2);
}
.parent-badge svg { width:11px; height:11px; color:var(--text-3); flex-shrink:0; }
.root-label {
    font-family:'DM Mono', monospace;
    font-size:9px; font-weight:700;
    color:var(--text-3); letter-spacing:0.08em;
    text-transform:uppercase;
    padding:3px 8px;
    background:var(--surface-3);
    border-radius:var(--radius-sm);
}

/* Product count */
.count-badge {
    display:inline-flex; align-items:center; gap:6px;
    font-family:'DM Mono', monospace;
    font-size:11px; font-weight:700; color:var(--text-1);
}
.count-badge span {
    font-size:9px; color:var(--text-3);
    letter-spacing:0.06em; text-transform:uppercase;
}

/* Status */
.status-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:100px;
    font-size:11px; font-weight:600;
    border:1px solid transparent; white-space:nowrap;
}
.status-badge::before {
    content:''; width:5px; height:5px;
    border-radius:50%; background:currentColor; flex-shrink:0;
}
.status-active   { background:var(--green-dim); color:var(--green); border-color:rgba(31,186,114,0.2); }
.status-inactive { background:var(--red-dim);   color:var(--red);   border-color:rgba(232,69,69,0.2); }

/* Actions */
.action-group {
    display:flex; align-items:center; justify-content:flex-end; gap:6px;
}
.btn-icon {
    width:32px; height:32px;
    border-radius:var(--radius-sm);
    background:var(--surface-2);
    border:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:center;
    color:var(--text-2); cursor:pointer;
    transition:all var(--transition); flex-shrink:0;
}
.btn-icon:hover {
    background:var(--blue-dim);
    border-color:rgba(79,114,245,0.25);
    color:var(--blue); transform:scale(1.05);
}
.btn-icon.delete:hover {
    background:var(--red-dim);
    border-color:rgba(232,69,69,0.25);
    color:var(--red);
}
.btn-icon svg { width:14px; height:14px; }

/* Empty state */
.empty-state {
    padding:80px 32px; text-align:center;
    display:flex; flex-direction:column; align-items:center; gap:12px;
}
.empty-icon {
    width:52px; height:52px;
    border-radius:var(--radius-lg);
    background:var(--surface-2); border:1px solid var(--border-1);
    display:flex; align-items:center; justify-content:center;
    color:var(--text-3); margin-bottom:4px;
}
.empty-icon svg { width:22px; height:22px; }
.empty-title { font-size:14px; font-weight:700; color:var(--text-2); }
.empty-sub   { font-family:'DM Mono', monospace; font-size:10px; color:var(--text-3); letter-spacing:0.04em; }

/* Hierarchy indent for sub-categories */
.indent-1 { padding-left:36px; }
.tree-line {
    display:inline-flex; align-items:center; gap:6px;
    color:var(--text-3); font-size:11px;
}
.tree-line::before {
    content:''; display:inline-block;
    width:12px; height:1px;
    background:var(--border-2);
    flex-shrink:0;
}

/* Pagination */
.pagination-wrap {
    padding:16px 20px;
    border-top:1px solid var(--border-1);
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
    font-family:'DM Mono', monospace; font-size:11px; font-weight:700;
    color:var(--text-2); background:var(--surface-1);
    border:1px solid var(--border-1); text-decoration:none;
    transition:all var(--transition);
}
.pagination-wrap .pagination li a:hover {
    background:var(--blue-dim);
    border-color:rgba(79,114,245,0.25); color:var(--blue);
}
.pagination-wrap .pagination li.active span {
    background:var(--blue); border-color:var(--blue); color:#fff;
}
.pagination-wrap .pagination li.disabled span { opacity:0.3; cursor:not-allowed; }

/* ═══════════════════════════════════════════
   MODAL
═══════════════════════════════════════════ */
.modal-overlay {
    position:fixed; inset:0;
    background:rgba(0,0,0,0.65);
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);
    z-index:1000;
    display:flex; align-items:center; justify-content:center;
    padding:24px;
    opacity:0; pointer-events:none;
    transition:opacity 0.22s ease;
}
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal-shell {
    background:var(--surface-1);
    border:1px solid var(--border-2);
    border-radius:var(--radius-xl);
    width:100%; max-width:600px;
    max-height:90vh;
    display:flex; flex-direction:column;
    overflow:hidden;
    box-shadow:0 24px 60px rgba(0,0,0,0.5);
    transform:scale(0.97) translateY(8px);
    transition:transform 0.22s cubic-bezier(0.16,1,0.3,1);
}
.modal-overlay.open .modal-shell { transform:scale(1) translateY(0); }

.modal-header {
    padding:24px 28px;
    border-bottom:1px solid var(--border-1);
    display:flex; align-items:center;
    justify-content:space-between; gap:16px;
    flex-shrink:0;
}
.modal-title {
    font-size:17px; font-weight:700; color:var(--text-1);
    letter-spacing:-0.01em; line-height:1;
}
.modal-subtitle {
    font-family:'DM Mono', monospace; font-size:9.5px;
    color:var(--text-3); letter-spacing:0.08em;
    text-transform:uppercase; margin-top:4px;
}
.modal-close {
    width:34px; height:34px;
    border-radius:var(--radius-sm);
    background:var(--surface-2); border:1px solid var(--border-1);
    color:var(--text-2);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:all var(--transition); flex-shrink:0;
}
.modal-close:hover {
    background:var(--red-dim);
    border-color:rgba(232,69,69,0.25); color:var(--red);
    transform:rotate(90deg);
}
.modal-close svg { width:15px; height:15px; }

.modal-body {
    padding:28px; overflow-y:auto; flex:1;
    scrollbar-width:thin; scrollbar-color:var(--border-2) transparent;
}
.form-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
.col-span-2 { grid-column:span 2; }
@media(max-width:520px){ .form-grid{grid-template-columns:1fr;} .col-span-2{grid-column:span 1;} }

.field { display:flex; flex-direction:column; gap:7px; }
.field label {
    font-family:'DM Mono', monospace; font-size:9.5px;
    font-weight:700; text-transform:uppercase;
    letter-spacing:0.09em; color:var(--text-3);
}
.field label .req { color:var(--red); margin-left:2px; }
.field input, .field select, .field textarea {
    background:var(--surface-2);
    border:1px solid var(--border-1);
    border-radius:var(--radius-md);
    padding:10px 14px; color:var(--text-1);
    font-family:'DM Sans', sans-serif;
    font-size:13px; font-weight:500;
    transition:border-color var(--transition), box-shadow var(--transition), background var(--transition);
    width:100%;
}
.field input::placeholder, .field textarea::placeholder { color:var(--text-3); }
.field input:focus, .field select:focus, .field textarea:focus {
    outline:none; border-color:var(--blue);
    background:var(--surface-3);
    box-shadow:0 0 0 3px var(--blue-dim);
}
.field select { cursor:pointer; }
.field textarea { resize:vertical; min-height:80px; line-height:1.6; }

/* Slug preview */
.slug-preview {
    display:flex; align-items:center; gap:8px;
    padding:8px 12px;
    background:var(--surface-3);
    border:1px solid var(--border-1);
    border-radius:var(--radius-md);
    margin-top:-4px;
}
.slug-preview-label {
    font-family:'DM Mono', monospace;
    font-size:9px; color:var(--text-3);
    text-transform:uppercase; letter-spacing:0.08em;
    flex-shrink:0;
}
.slug-preview-val {
    font-family:'DM Mono', monospace;
    font-size:10px; color:var(--blue);
    font-weight:600; letter-spacing:0.02em;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}

/* Section divider */
.form-section {
    grid-column:span 2;
    display:flex; align-items:center; gap:12px; padding-top:4px;
}
.form-section-line { flex:1; height:1px; background:var(--border-1); }
.form-section-label {
    font-family:'DM Mono', monospace; font-size:9px;
    font-weight:700; text-transform:uppercase;
    letter-spacing:0.1em; color:var(--text-3); white-space:nowrap;
}

/* Toggle */
.toggle-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 14px;
    background:var(--surface-2); border:1px solid var(--border-1);
    border-radius:var(--radius-md);
}
.toggle-label { font-size:13px; font-weight:600; color:var(--text-1); flex:1; }
.toggle-sub   { font-size:10.5px; color:var(--text-3); margin-top:1px; }
.toggle-switch { position:relative; width:40px; height:22px; flex-shrink:0; }
.toggle-switch input { opacity:0; width:0; height:0; position:absolute; }
.toggle-track {
    position:absolute; inset:0;
    background:var(--surface-3); border:1px solid var(--border-2);
    border-radius:100px; cursor:pointer;
    transition:background var(--transition), border-color var(--transition);
}
.toggle-track::after {
    content:''; position:absolute;
    top:2px; left:2px; width:16px; height:16px;
    border-radius:50%; background:var(--text-3);
    transition:transform var(--transition), background var(--transition);
}
.toggle-switch input:checked + .toggle-track {
    background:var(--green-dim); border-color:rgba(31,186,114,0.3);
}
.toggle-switch input:checked + .toggle-track::after {
    transform:translateX(18px); background:var(--green);
}

.modal-footer {
    padding:20px 28px;
    border-top:1px solid var(--border-1);
    display:flex; align-items:center;
    justify-content:flex-end; gap:10px;
    flex-shrink:0; background:var(--surface-2);
}
.btn-cancel {
    padding:9px 18px; border-radius:var(--radius-md);
    font-size:12px; font-weight:700; color:var(--text-2);
    background:transparent; border:1px solid var(--border-1);
    cursor:pointer; transition:all var(--transition);
    font-family:'DM Sans', sans-serif;
}
.btn-cancel:hover { background:var(--surface-3); color:var(--text-1); border-color:var(--border-2); }
.btn-save {
    display:inline-flex; align-items:center; gap:8px;
    padding:9px 20px; border-radius:var(--radius-md);
    font-size:12px; font-weight:700; color:#fff;
    background:var(--blue); border:none; cursor:pointer;
    transition:all var(--transition);
    box-shadow:0 4px 12px rgba(79,114,245,0.25);
    font-family:'DM Sans', sans-serif;
}
.btn-save svg { width:13px; height:13px; }
.btn-save:hover { background:#3d5de0; box-shadow:0 6px 18px rgba(79,114,245,0.35); transform:translateY(-1px); }
.btn-save:active { transform:translateY(0); }
.btn-save:disabled { opacity:0.5; pointer-events:none; }
.spinner {
    width:13px; height:13px;
    border:2px solid rgba(255,255,255,0.3);
    border-top-color:#fff; border-radius:50%;
    animation:spin 0.6s linear infinite; flex-shrink:0;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* ─── Delete modal ─── */
.delete-modal {
    position:fixed; inset:0;
    background:rgba(0,0,0,0.65);
    backdrop-filter:blur(6px);
    z-index:1100;
    display:flex; align-items:center; justify-content:center;
    padding:24px; opacity:0; pointer-events:none; transition:opacity 0.2s;
}
.delete-modal.open { opacity:1; pointer-events:all; }
.delete-shell {
    background:var(--surface-1);
    border:1px solid var(--border-2);
    border-radius:var(--radius-xl);
    padding:32px; max-width:400px; width:100%;
    text-align:center;
    box-shadow:0 24px 60px rgba(0,0,0,0.5);
    transform:scale(0.96);
    transition:transform 0.2s cubic-bezier(0.16,1,0.3,1);
}
.delete-modal.open .delete-shell { transform:scale(1); }
.delete-icon {
    width:52px; height:52px; border-radius:var(--radius-lg);
    background:var(--red-dim); border:1px solid rgba(232,69,69,0.2);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; color:var(--red);
}
.delete-icon svg { width:22px; height:22px; }
.delete-title { font-size:16px; font-weight:700; color:var(--text-1); margin-bottom:8px; letter-spacing:-0.01em; }
.delete-sub   { font-size:12px; color:var(--text-2); line-height:1.6; margin-bottom:24px; }
.delete-actions { display:flex; gap:10px; }
.btn-del-cancel {
    flex:1; padding:10px; border-radius:var(--radius-md);
    background:var(--surface-2); border:1px solid var(--border-1);
    color:var(--text-2); font-size:12px; font-weight:700;
    cursor:pointer; transition:all var(--transition);
    font-family:'DM Sans', sans-serif;
}
.btn-del-cancel:hover { background:var(--surface-3); color:var(--text-1); }
.btn-del-confirm {
    flex:1; padding:10px; border-radius:var(--radius-md);
    background:var(--red); border:none; color:#fff;
    font-size:12px; font-weight:700; cursor:pointer;
    transition:all var(--transition); font-family:'DM Sans', sans-serif;
    box-shadow:0 4px 12px rgba(232,69,69,0.25);
}
.btn-del-confirm:hover { background:#c93838; transform:translateY(-1px); }
.btn-del-confirm:disabled { opacity:0.5; pointer-events:none; }

/* ─── Toast ─── */
.toast {
    position:fixed; bottom:24px; right:24px; z-index:2000;
    display:flex; align-items:center; gap:10px;
    padding:12px 18px;
    background:var(--surface-1); border:1px solid var(--border-2);
    border-radius:var(--radius-md);
    box-shadow:0 8px 24px rgba(0,0,0,0.4);
    font-size:13px; font-weight:600; color:var(--text-1);
    transform:translateY(12px); opacity:0;
    transition:all 0.25s cubic-bezier(0.16,1,0.3,1);
    pointer-events:none; max-width:320px;
}
.toast.show { transform:translateY(0); opacity:1; }
.toast-dot  { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.toast.success .toast-dot { background:var(--green); }
.toast.error   .toast-dot { background:var(--red); }

/* ─── Responsive ─── */
@media(max-width:700px) {
    thead th:nth-child(3), tbody td:nth-child(3) { display:none; }
}
@media(max-width:520px) {
    thead th:nth-child(4), tbody td:nth-child(4) { display:none; }
    .page-header { flex-direction:column; align-items:flex-start; }
}
</style>
@endpush

@section('content')
<div class="categories-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>@lang('admin.categories')</h1>
            <p>
                <span class="live-dot"></span>
                {{ method_exists($categories, 'total') ? $categories->total() : $categories->count() }} @lang('admin.total_categories')
                &nbsp;·&nbsp; @lang('admin.updated_just_now')
            </p>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <div class="search-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="cat-search" placeholder="@lang('admin.search_categories')" autocomplete="off">
        </div>

        <select class="filter-select" id="status-filter">
            <option value="">@lang('admin.all_statuses')</option>
            <option value="active">@lang('admin.active')</option>
            <option value="inactive">@lang('admin.inactive')</option>
        </select>

        <select class="filter-select" id="parent-filter">
            <option value="">@lang('admin.all_levels')</option>
            <option value="root">@lang('admin.root_only')</option>
            <option value="child">@lang('admin.subcategories')</option>
        </select>

        <div class="toolbar-sep"></div>

        {{-- Stats --}}
        <div class="toolbar-stats">
            @php
                $cats        = $categories instanceof \Illuminate\Pagination\LengthAwarePaginator ? $categories->getCollection() : collect($categories);
                $activeCount = $cats->where('is_active', true)->count();
                $offCount    = $cats->where('is_active', false)->count();
                $rootCount   = $cats->whereNull('parent_id')->count();
            @endphp
            <div class="stat-pill total">
                <span class="stat-dot"></span>
                {{ $cats->count() }} @lang('admin.total')
            </div>
            <div class="stat-pill active">
                <span class="stat-dot"></span>
                {{ $activeCount }} @lang('admin.active')
            </div>
            <div class="stat-pill off">
                <span class="stat-dot"></span>
                {{ $offCount }} @lang('admin.inactive')
            </div>
        </div>

        <div class="toolbar-sep"></div>

        <button onclick="openModal('create')" class="btn-add">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            @lang('admin.add_category')
        </button>
    </div>

    {{-- ── Table ── --}}
    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>@lang('admin.category')</th>
                        <th>@lang('admin.slug')</th>
                        <th>@lang('admin.parent')</th>
                        <th>@lang('admin.products')</th>
                        <th>@lang('admin.status')</th>
                        <th style="text-align:right">@lang('admin.actions')</th>
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
                                {{ $category->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button onclick="editCategory({{ $category->id }})" class="btn-icon" title="@lang('admin.edit')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button onclick="promptDelete({{ $category->id }}, '{{ addslashes($category->name) }}')" class="btn-icon delete" title="@lang('admin.delete')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
        <div class="pagination-wrap">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════
     CATEGORY MODAL
══════════════════════════════════════ --}}
<div id="cat-modal" class="modal-overlay" onclick="handleOverlayClick(event)">
    <div class="modal-shell">

        <div class="modal-header">
            <div>
                <div class="modal-title" id="modal-title">@lang('admin.add_category')</div>
                <div class="modal-subtitle" id="modal-subtitle">@lang('admin.fill_category_details')</div>
            </div>
            <button class="modal-close" onclick="closeModal()" title="@lang('admin.close')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <form id="cat-form" class="form-grid" onsubmit="return false;">
                <input type="hidden" id="f-id">

                {{-- Name --}}
                <div class="field col-span-2">
                    <label>@lang('admin.category_name') <span class="req">*</span></label>
                    <input type="text" id="f-name" placeholder="@lang('admin.category_name_placeholder')" required>
                    <div class="slug-preview">
                        <span class="slug-preview-label">@lang('admin.slug'):</span>
                        <span class="slug-preview-val" id="slug-preview">—</span>
                    </div>
                </div>

                {{-- Parent --}}
                <div class="field col-span-2">
                    <label>@lang('admin.parent_category')</label>
                    <select id="f-parent">
                        <option value="">— @lang('admin.root_category') —</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="field col-span-2">
                    <label>@lang('admin.description')</label>
                    <textarea id="f-desc" placeholder="@lang('admin.description_placeholder')"></textarea>
                </div>

                {{-- Divider --}}
                <div class="form-section">
                    <div class="form-section-line"></div>
                    <span class="form-section-label">@lang('admin.settings')</span>
                    <div class="form-section-line"></div>
                </div>

                {{-- Active toggle --}}
                <div class="field col-span-2">
                    <div class="toggle-row">
                        <div style="flex:1">
                            <div class="toggle-label">@lang('admin.active')</div>
                            <div class="toggle-sub">@lang('admin.active_description')</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="f-active" checked>
                            <span class="toggle-track"></span>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">@lang('admin.cancel')</button>
            <button class="btn-save" id="save-btn" onclick="saveCategory()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                @lang('admin.save')
            </button>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════
     DELETE MODAL
══════════════════════════════════════ --}}
<div id="del-modal" class="delete-modal">
    <div class="delete-shell">
        <div class="delete-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <div class="delete-title">@lang('admin.delete_category')</div>
        <div class="delete-sub" id="del-msg">@lang('admin.delete_confirm_message')</div>
        <div class="delete-actions">
            <button class="btn-del-cancel" onclick="closeDeleteModal()">@lang('admin.cancel')</button>
            <button class="btn-del-confirm" id="del-btn" onclick="executeDelete()">@lang('admin.delete')</button>
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
/* ═══════════════════════════════════════
   STATE
═══════════════════════════════════════ */
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
    setTimeout(() => document.getElementById('f-name').focus(), 60);
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
    btn.innerHTML = '<span class="spinner"></span> {{ __("admin.saving") }}';

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
        btn.innerHTML = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="width:13px;height:13px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg> {{ __("admin.save") }}`;
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