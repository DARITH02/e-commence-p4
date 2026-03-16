@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Product Catalog')

@push('styles')
<style>
    :root {
        --glass: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.07);
        --accent-glow: 0 0 20px rgba(79, 110, 247, 0.15);
    }

    /* Force full width */
    .content-inner { max-width: none !important; padding: 0; }

    .matrix-container {
        display: flex;
        flex-direction: column;
        gap: 32px;
        animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Header ── */
    .header-panel {
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 32px;
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        position: relative;
        overflow: hidden;
    }

    .header-panel::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
        pointer-events: none;
    }

    .header-info h1 {
        font-size: 48px;
        font-weight: 900;
        color: var(--text);
        letter-spacing: -0.04em;
        line-height: 1;
    }

    .header-info p {
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4em;
        color: var(--muted);
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-info .pulse-dot {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--accent);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.5); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* ── Controls ── */
    .controls-row {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .search-matrix {
        position: relative;
        flex: 1;
        min-width: 300px;
    }

    .search-matrix input {
        width: 100%;
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 16px 20px 16px 52px;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .search-matrix input:focus {
        border-color: var(--accent);
        box-shadow: var(--accent-glow);
        background: var(--ink-3);
    }

    .search-matrix svg {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--muted);
    }

    .btn-premium {
        background: var(--accent);
        color: #fff;
        padding: 16px 32px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 20px rgba(79, 110, 247, 0.2);
    }

    .btn-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(79, 110, 247, 0.4);
        background: #5d7cf8;
    }

    /* ── Table ── */
    .table-panel {
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 32px;
        overflow: hidden;
    }

    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    thead th {
        padding: 24px 32px;
        text-align: left;
        background: rgba(255,255,255,0.01);
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
    }

    tbody tr {
        transition: all 0.3s;
        cursor: pointer;
    }

    tbody tr:hover {
        background: rgba(255,255,255,0.02);
    }

    tbody td {
        padding: 24px 32px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    tbody tr:last-child td { border-bottom: none; }

    .prod-identity { display: flex; align-items: center; gap: 20px; }
    .prod-img-box {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.5s;
    }

    tr:hover .prod-img-box {
        border-color: var(--accent);
        transform: scale(1.1) rotate(-3deg);
    }

    .prod-img-box img { width: 100%; height: 100%; object-fit: cover; }
    .prod-img-box svg { width: 24px; height: 24px; color: var(--muted); opacity: 0.3; }

    .prod-name { font-size: 15px; font-weight: 800; color: var(--text); letter-spacing: -0.01em; }
    .prod-sku { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--muted); margin-top: 4px; text-transform: uppercase; }

    .price-tag { font-family: 'DM Mono', monospace; font-size: 14px; font-weight: 700; color: var(--text); }
    .sale-tag { color: var(--red); text-decoration: line-through; font-size: 11px; margin-top: 2px; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .status-active { background: rgba(34, 201, 122, 0.1); color: var(--green); border: 1px solid rgba(34, 201, 122, 0.2); }
    .status-inactive { background: rgba(90, 96, 118, 0.1); color: var(--muted); border: 1px solid rgba(90, 96, 118, 0.2); }

    .action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s;
    }
    tr:hover .action-group { opacity: 1; transform: translateX(0); }

    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        color: var(--muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-icon:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
    .btn-icon.delete:hover { background: var(--red); border-color: var(--red); }

    /* ── Modal ── */
    #matrix-modal {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
        background: rgba(9, 10, 15, 0.85);
        backdrop-filter: blur(20px);
        opacity: 0;
        pointer-events: none;
        transition: all 0.4s;
    }
    #matrix-modal.active { opacity: 1; pointer-events: auto; }

    .modal-shell {
        width: 100%;
        max-width: 900px;
        max-height: 90vh;
        background: #0d0f14;
        border: 1px solid var(--border-2);
        border-radius: 40px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transform: scale(0.95) translateY(20px);
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 40px 120px rgba(0,0,0,0.6);
    }
    #matrix-modal.active .modal-shell { transform: scale(1) translateY(0); }

    .modal-header {
        padding: 40px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255,255,255,0.01);
    }

    .modal-title { font-size: 24px; font-weight: 900; color: var(--text); letter-spacing: -0.02em; }
    .modal-close {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        color: var(--muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .modal-close:hover { background: var(--red); color: #fff; border-color: var(--red); transform: rotate(90deg); }

    .modal-body {
        padding: 40px;
        overflow-y: auto;
        flex: 1;
        scrollbar-width: thin;
    }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .full-row { grid-column: span 2; }

    .input-box { display: flex; flex-direction: column; gap: 10px; }
    .input-box label { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); }
    .input-box input, .input-box select, .input-box textarea {
        background: var(--ink-3);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px 20px;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .input-box input:focus, .input-box select:focus, .input-box textarea:focus {
        border-color: var(--accent);
        background: #141824;
        box-shadow: var(--accent-glow);
    }

    .modal-footer {
        padding: 32px 40px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 16px;
        background: rgba(255,255,255,0.01);
    }

    /* Multi-select styling */
    .cat-pill-box {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        min-height: 56px;
        padding: 12px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        border-radius: 16px;
    }
    .cat-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--accent-glow);
        color: var(--accent);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(79, 110, 247, 0.2);
    }
    .cat-pill button { color: var(--accent); opacity: 0.6; transition: 0.2s; }
    .cat-pill button:hover { opacity: 1; color: var(--red); }
</style>
@endpush

@section('content')
<div class="matrix-container">
    
    <!-- ── Workspace Header ── -->
    <div class="header-panel">
        <div class="header-info">
            <h1>@lang('admin.inventory_core')</h1>
            <p><span class="pulse-dot"></span> @lang('admin.global_lifecycle')</p>
        </div>
        <div class="controls-row">
            <div class="search-matrix">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" stroke-linecap="round"/></svg>
                <input type="text" id="matrix-search" placeholder="@lang('admin.search')">
            </div>
            <button onclick="openModal('create')" class="btn-premium">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                @lang('admin.catalog_new_item')
            </button>
        </div>
    </div>

    <!-- ── Data Table ── -->
    <div class="table-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%">@lang('admin.entity_analysis')</th>
                        <th style="width: 15%">@lang('admin.mapping')</th>
                        <th style="width: 15%">@lang('admin.valuation')</th>
                        <th style="width: 15%">@lang('admin.state')</th>
                        <th style="width: 15%; text-align: right">@lang('admin.terminal')</th>
                    </tr>
                </thead>
                <tbody id="matrix-table-body">
                    @forelse($products as $product)
                    <tr id="row-{{ $product->id }}">
                        <td>
                            <div class="prod-identity">
                                <div class="prod-img-box">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="prod-name">{{ $product->name }}</div>
                                    <div class="prod-sku">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($product->categories as $cat)
                                    <span class="px-2 py-1 rounded-lg bg-white/5 border border-white/5 text-[9px] font-bold text-muted uppercase tracking-wider">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="price-tag">${{ number_format($product->price, 2) }}</div>
                            @if($product->sale_price)
                                <div class="sale-tag">${{ number_format($product->sale_price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? __('admin.online') : __('admin.halted') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button onclick="editProduct({{ $product->id }})" class="btn-icon">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <button onclick="confirmDelete({{ $product->id }})" class="btn-icon delete">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 100px; text-align: center;">
                            <div style="opacity: 0.2; font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 800; letter-spacing: 0.4em; text-transform: uppercase;">
                                No Active Entities Found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div style="padding: 24px 32px; border-top: 1px solid var(--border); background: rgba(255,255,255,0.01);">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ── Matrix Modal ── -->
<div id="matrix-modal">
    <div class="modal-shell">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="modal-title">@lang('admin.catalog_new_item')</div>
                <div style="font-family: 'DM Mono', monospace; font-size: 9px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.3em; margin-top: 8px;">Unit Configuration Protocol</div>
            </div>
            <button onclick="closeModal()" class="modal-close">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <div class="modal-body custom-scrollbar">
            <form id="matrix-form" class="form-grid">
                <input type="hidden" id="prod-id">
                
                <div class="full-row input-box">
                    <label>@lang('admin.market_identity')</label>
                    <input type="text" id="prod-name" placeholder="ENT_NAME_PROTOCAL" required>
                </div>

                <div class="input-box">
                    <label>@lang('admin.sku_label')</label>
                    <input type="text" id="prod-sku" placeholder="SKU_CORE_99" required>
                </div>

                <div class="input-box">
                    <label>@lang('admin.category_cluster')</label>
                    <select id="prod-categories" multiple style="min-height: 120px;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-box">
                    <label>@lang('admin.valuation_label')</label>
                    <input type="number" id="prod-price" step="0.01" value="0.00" required>
                </div>

                <div class="input-box">
                    <label>@lang('admin.sale_offset')</label>
                    <input type="number" id="prod-sale-price" step="0.01" value="0.00">
                </div>

                <div class="full-row input-box">
                    <label>@lang('admin.logic_params')</label>
                    <textarea id="prod-desc" rows="4" placeholder="ENTITY_DESCRIPTION_STRING..."></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <input type="checkbox" id="prod-active" checked class="w-5 h-5 accent-primary">
                    <label for="prod-active" style="font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text);">@lang('admin.online')</label>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button onclick="closeModal()" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-muted hover:text-text transition-all">@lang('admin.abort')</button>
            <button onclick="saveProduct()" class="btn-premium">@lang('admin.execute_sync')</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('matrix-modal');
    const form = document.getElementById('matrix-form');
    let isEditing = false;

    function openModal(mode, data = null) {
        isEditing = mode === 'edit';
        document.getElementById('modal-title').innerText = isEditing ? 'Edit Entity' : 'New Entity';
        
        if (isEditing && data) {
            document.getElementById('prod-id').value = data.id;
            document.getElementById('prod-name').value = data.name;
            document.getElementById('prod-sku').value = data.sku;
            document.getElementById('prod-desc').value = data.description || '';
            document.getElementById('prod-price').value = data.price;
            document.getElementById('prod-sale-price').value = data.sale_price || '0.00';
            document.getElementById('prod-active').checked = data.is_active;
            
            // Multi-select
            const catSelect = document.getElementById('prod-categories');
            Array.from(catSelect.options).forEach(opt => {
                opt.selected = data.categories.some(c => c.id == opt.value);
            });
        } else {
            form.reset();
            document.getElementById('prod-id').value = '';
        }

        modal.classList.add('active');
    }

    function closeModal() {
        modal.classList.remove('active');
    }

    async function editProduct(id) {
        try {
            const product = await AJAX.fetch(`/admin/products/${id}`);
            openModal('edit', product);
        } catch (error) {}
    }

    async function saveProduct() {
        const id = document.getElementById('prod-id').value;
        const catSelect = document.getElementById('prod-categories');
        const selectedCats = Array.from(catSelect.selectedOptions).map(opt => opt.value);

        const data = {
            name: document.getElementById('prod-name').value,
            sku: document.getElementById('prod-sku').value,
            price: document.getElementById('prod-price').value,
            sale_price: document.getElementById('prod-sale-price').value || null,
            description: document.getElementById('prod-desc').value,
            is_active: document.getElementById('prod-active').checked,
            categories: selectedCats
        };

        const url = isEditing ? `/admin/products/${id}` : '/admin/products';
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const res = await AJAX.fetch(url, {
                method: method,
                body: JSON.stringify(data)
            });

            AJAX.notify(res.message);
            closeModal();
            location.reload();
        } catch (error) {}
    }

    function confirmDelete(id) {
        if (confirm('Initiate entity decommission protocol?')) {
            deleteProduct(id);
        }
    }

    async function deleteProduct(id) {
        try {
            const res = await AJAX.fetch(`/admin/products/${id}`, { method: 'DELETE' });
            AJAX.notify(res.message);
            document.getElementById(`row-${id}`).remove();
        } catch (error) {}
    }

    // Live search
    document.getElementById('matrix-search').oninput = function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#matrix-table-body tr');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    };
</script>
@endpush
