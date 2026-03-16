@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Product Catalog')

@@section('content')
<div class="space-y-12">
    <!-- Workspace Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-white tracking-tighter leading-none">@lang('admin.inventory_core')</h1>
            <p class="text-brand-muted font-bold text-[10px] uppercase tracking-[0.5em] mt-3 flex items-center">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-3 shadow-[0_0_10px_rgba(124,58,237,0.5)]"></span>
                @lang('admin.global_lifecycle')
            </p>
        </div>
        <button onclick="openModal('create')" class="bg-primary-600 hover:bg-primary-500 text-white px-10 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(124,58,237,0.2)] transition-all flex items-center group">
            <div class="bg-white/20 p-2 rounded-xl mr-4 group-hover:rotate-90 transition-transform shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </div>
            @lang('admin.catalog_new_item')
        </button>
    </div>

    <!-- Data Table Container -->
    <div class="glass-panel rounded-[3.5rem] overflow-hidden relative">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="bg-white/[0.01] text-[9px] font-black text-brand-muted uppercase tracking-[0.4em]">
                    <tr>
                        <th class="px-12 py-8">@lang('admin.entity_analysis')</th>
                        <th class="px-12 py-8">@lang('admin.access_key')</th>
                        <th class="px-12 py-8">@lang('admin.mapping')</th>
                        <th class="px-12 py-8">@lang('admin.valuation')</th>
                        <th class="px-12 py-8">@lang('admin.state')</th>
                        <th class="px-12 py-8 text-right">@lang('admin.terminal')</th>
                    </tr>
                </thead>
                <tbody id="product-table-body" class="divide-y divide-white/5">
                    @forelse($products as $product)
                    <tr id="product-row-{{ $product->id }}" class="hover:bg-white/[0.02] transition-colors group cursor-crosshair">
                        <td class="px-12 py-8">
                            <div class="flex items-center space-x-6">
                                <div class="w-16 h-16 rounded-[1.5rem] bg-brand-obsidian flex items-center justify-center overflow-hidden border border-white/10 group-hover:border-primary-500/50 transition-all shadow-2xl relative">
                                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/10 to-transparent pointer-events-none"></div>
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <svg class="w-8 h-8 text-brand-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-white tracking-tight group-hover:text-primary-400 transition-colors uppercase">{{ $product->name }}</p>
                                    <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.2em]">@lang('admin.variant_count', ['count' => count($product->variants)])</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <span class="text-[9px] font-black font-mono text-brand-muted bg-white/5 px-4 py-2 rounded-xl border border-white/5 group-hover:border-white/10 transition-all tracking-widest uppercase">{{ $product->sku }}</span>
                        </td>
                        <td class="px-12 py-8">
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->categories as $category)
                                    <span class="bg-primary-500/10 text-primary-400 text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border border-primary-500/20 shadow-lg">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <div class="flex flex-col space-y-1">
                                <span class="text-sm font-black text-white tracking-tighter tabular-nums">${{ number_format($product->price, 2) }}</span>
                                @if($product->sale_price)
                                    <span class="text-[10px] text-rose-500 font-bold line-through tracking-tighter tabular-nums">${{ number_format($product->sale_price, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            @php
                                $statusClass = $product->is_active 
                                    ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-emerald-500/5' 
                                    : 'bg-rose-500/10 text-rose-400 border-rose-500/20 shadow-rose-500/5';
                            @endphp
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border {{ $statusClass }} shadow-xl">
                                {{ $product->is_active ? __('admin.live_telemetry') : __('admin.static_mode') }}
                            </span>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                <button onclick="editProduct({{ $product->id }})" class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-2xl border border-white/10 transition-all shadow-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button onclick="confirmDelete({{ $product->id }})" class="p-3 bg-white/5 hover:bg-rose-500/20 text-rose-500 rounded-2xl border border-white/10 transition-all shadow-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="6" class="px-12 py-32 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="w-32 h-32 bg-white/5 rounded-[3rem] flex items-center justify-center mx-auto mb-10 border border-white/5 group overflow-hidden relative">
                                    <div class="absolute inset-0 bg-primary-500/10 blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                                    <svg class="w-12 h-12 text-white/20 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">@lang('admin.zero_modules')</h3>
                                <p class="text-[10px] font-black text-brand-muted uppercase tracking-[0.3em] mt-4 leading-relaxed">@lang('admin.inventory_offline')</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-12 py-8 border-t border-white/5 bg-white/[0.01]">
            <div class="flex items-center justify-between">
                <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.4em]">@lang('admin.matrix_stream_page'): {{ $products->currentPage() }}</p>
                <div class="dark-pagination">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="product-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-brand-obsidian/40 backdrop-blur-2xl hidden overflow-y-auto">
    <div class="glass-panel w-full max-w-4xl rounded-[4rem] shadow-[0_40px_100px_rgba(0,0,0,0.5)] border-white/10 overflow-hidden animate-in fade-in zoom-in duration-500 my-12">
        <div class="px-12 pt-12 pb-8 flex items-center justify-between bg-white/[0.02] border-b border-white/5">
            <div>
                <h4 id="modal-title" class="text-3xl font-black text-white tracking-tighter">@lang('admin.product_spec')</h4>
                <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.4em] mt-2">@lang('admin.logic_override')</p>
            </div>
            <button onclick="closeModal()" class="p-4 bg-white/5 hover:bg-white/10 text-white rounded-3xl border border-white/10 transition-all shadow-xl group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="product-form" class="px-12 py-12 space-y-10 max-h-[70vh] overflow-y-auto custom-scrollbar">
            <input type="hidden" id="product-id">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="lg:col-span-2 space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.market_identity')</label>
                    <input type="text" id="product-name" required class="w-full bg-white/5 border border-white/10 rounded-[2.5rem] px-8 py-6 text-sm font-black text-white placeholder-white/20 focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner uppercase" placeholder="DEPLOY_IDENTIFIER">
                </div>
                
                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.sku_label')</label>
                    <input type="text" id="product-sku" required class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white font-mono tracking-widest focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner" placeholder="SKU_PRTCL_99">
                </div>

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.category_cluster')</label>
                    <div class="relative">
                        <select id="product-categories" multiple class="w-full bg-white/5 border border-white/10 rounded-[2.5rem] px-8 py-6 text-sm font-black text-white focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner min-h-[160px] custom-scrollbar">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="bg-brand-obsidian py-2">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.valuation_label')</label>
                    <input type="number" id="product-price" step="0.01" required class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner tabular-nums">
                </div>

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.sale_offset')</label>
                    <input type="number" id="product-sale-price" step="0.01" class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner tabular-nums">
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.logic_params')</label>
                <textarea id="product-description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-[2.5rem] px-8 py-6 text-sm font-black text-white placeholder-white/20 focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner resize-none" placeholder="Define product specifications and attributes..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-center justify-between bg-white/[0.02] p-8 rounded-[2.5rem] border border-white/5 shadow-inner">
                    <div class="flex items-center space-x-6">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="product-active" checked class="sr-only peer">
                            <div class="w-14 h-8 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600 shadow-inner"></div>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-white uppercase tracking-widest block">@lang('admin.live_status')</span>
                            <span class="text-[9px] font-black text-brand-muted uppercase tracking-widest mt-1 block">@lang('admin.active_frontlayer')</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-white/[0.02] p-8 rounded-[2.5rem] border border-white/5 shadow-inner border-primary-500/10">
                    <div class="flex items-center space-x-6">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="product-featured" class="sr-only peer">
                            <div class="w-14 h-8 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600 shadow-inner"></div>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-white uppercase tracking-widest block">@lang('admin.featured')</span>
                            <span class="text-[9px] font-black text-brand-muted uppercase tracking-widest mt-1 block">@lang('admin.priority_level')</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-6 pt-10 sticky bottom-0 bg-brand-obsidian/80 backdrop-blur-xl border-t border-white/5 -mx-12 px-12 py-8 mt-12">
                <button type="button" onclick="closeModal()" class="px-10 py-5 rounded-[2rem] text-[10px] font-black text-brand-muted uppercase tracking-widest hover:text-white hover:bg-white/5 transition-all">@lang('admin.abort')</button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-14 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(124,58,237,0.2)] transition-all">@lang('admin.execute_sync')</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('product-modal');
    const form = document.getElementById('product-form');
    let isEditing = false;

    function openModal(mode, data = null) {
        isEditing = mode === 'edit';
        document.getElementById('modal-title').innerText = isEditing ? '{{ __("admin.edit_product") }}' : '{{ __("admin.add_product") }}';
        
        if (isEditing && data) {
            document.getElementById('product-id').value = data.id;
            document.getElementById('product-name').value = data.name;
            document.getElementById('product-sku').value = data.sku;
            document.getElementById('product-description').value = data.description || '';
            document.getElementById('product-price').value = data.price;
            document.getElementById('product-sale-price').value = data.sale_price || '';
            document.getElementById('product-active').checked = data.is_active;
            document.getElementById('product-featured').checked = data.is_featured;
            
            // Set multi-select
            const categoriesSelect = document.getElementById('product-categories');
            Array.from(categoriesSelect.options).forEach(option => {
                option.selected = data.categories.some(c => c.id == option.value);
            });
        } else {
            form.reset();
            document.getElementById('product-id').value = '';
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    async function editProduct(id) {
        try {
            const product = await AJAX.fetch(`/admin/products/${id}`);
            openModal('edit', product);
        } catch (error) { }
    }

    form.onsubmit = async (e) => {
        e.preventDefault();
        
        const id = document.getElementById('product-id').value;
        const categoriesSelect = document.getElementById('product-categories');
        const selectedCategories = Array.from(categoriesSelect.selectedOptions).map(option => option.value);

        const data = {
            name: document.getElementById('product-name').value,
            sku: document.getElementById('product-sku').value,
            price: document.getElementById('product-price').value,
            sale_price: document.getElementById('product-sale-price').value || null,
            description: document.getElementById('product-description').value,
            is_active: document.getElementById('product-active').checked,
            is_featured: document.getElementById('product-featured').checked,
            categories: selectedCategories
        };

        const url = isEditing ? `/admin/products/${id}` : '/admin/products';
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const response = await AJAX.fetch(url, {
                method: method,
                body: JSON.stringify(data)
            });

            AJAX.notify(response.message);
            closeModal();
            location.reload();
        } catch (error) { }
    };

    function confirmDelete(id) {
        if (confirm('{{ __("admin.confirm_delete_msg") }}')) {
            deleteProduct(id);
        }
    }

    async function deleteProduct(id) {
        try {
            const response = await AJAX.fetch(`/admin/products/${id}`, { method: 'DELETE' });
            AJAX.notify(response.message);
            document.getElementById(`product-row-${id}`).remove();
        } catch (error) { }
    }
</script>
@endpush
