@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Category Management')

@section('content')
<div class="space-y-12">
    <!-- Workspace Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-white tracking-tighter leading-none">@lang('admin.category_vault')</h1>
            <p class="text-brand-muted font-bold text-[10px] uppercase tracking-[0.5em] mt-3 flex items-center">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-3 shadow-[0_0_10px_rgba(124,58,237,0.5)]"></span>
                @lang('admin.hierarchy_logic')
            </p>
        </div>
        <button onclick="openModal('create')" class="bg-primary-600 hover:bg-primary-500 text-white px-10 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(124,58,237,0.2)] transition-all flex items-center group">
            <div class="bg-white/20 p-2 rounded-xl mr-4 group-hover:rotate-90 transition-transform shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </div>
            @lang('admin.init_node')
        </button>
    </div>

    <!-- Data Table Container -->
    <div class="glass-panel rounded-[3.5rem] overflow-hidden relative">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="bg-white/[0.01] text-[9px] font-black text-brand-muted uppercase tracking-[0.4em]">
                    <tr>
                        <th class="px-12 py-8">@lang('admin.genetic_identity')</th>
                        <th class="px-12 py-8">@lang('admin.reference_key')</th>
                        <th class="px-12 py-8">@lang('admin.parent_chain')</th>
                        <th class="px-12 py-8">@lang('admin.entity_count')</th>
                        <th class="px-12 py-8">@lang('admin.state')</th>
                        <th class="px-12 py-8 text-right">@lang('admin.terminal')</th>
                    </tr>
                </thead>
                <tbody id="category-table-body" class="divide-y divide-white/5">
                    @forelse($categories as $category)
                    <tr id="category-row-{{ $category->id }}" class="hover:bg-white/[0.02] transition-colors group cursor-crosshair">
                        <td class="px-12 py-8">
                            <div class="flex items-center space-x-5">
                                <div class="w-12 h-12 bg-gradient-to-tr from-brand-background to-white/5 rounded-2xl flex items-center justify-center font-black text-white text-sm border border-white/10 group-hover:border-primary-500/50 transition-all uppercase shadow-2xl">
                                    {{ substr($category->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-black text-white tracking-tight group-hover:text-primary-400 transition-colors">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <span class="text-[9px] font-black font-mono text-brand-muted bg-white/5 px-4 py-2 rounded-xl border border-white/5 group-hover:border-white/10 transition-all tracking-widest uppercase">{{ $category->slug }}</span>
                        </td>
                        <td class="px-12 py-8">
                            @if($category->parent)
                                <div class="flex items-center text-[10px] font-black text-white uppercase tracking-widest group-hover:text-primary-400 transition-all">
                                    <svg class="w-3 h-3 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    {{ $category->parent->name }}
                                </div>
                            @else
                                <span class="text-[9px] font-black text-brand-muted/40 uppercase tracking-[0.3em]">@lang('admin.root_domain')</span>
                            @endif
                        </td>
                        <td class="px-12 py-8">
                            <div class="flex items-center space-x-4">
                                <div class="w-9 h-9 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-[10px] font-black text-white group-hover:border-primary-500/50 transition-all shadow-inner">{{ $category->products_count }}</div>
                                <span class="text-[9px] font-black text-brand-muted uppercase tracking-[0.2em] group-hover:text-white transition-colors">@lang('admin.linked_units')</span>
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            @php
                                $statusClass = $category->is_active 
                                    ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' 
                                    : 'bg-rose-500/10 text-rose-400 border-rose-500/20';
                            @endphp
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border {{ $statusClass }} shadow-lg shadow-black/20">
                                {{ $category->is_active ? __('admin.online') : __('admin.halted') }}
                            </span>
                        </td>
                        <td class="px-12 py-8 text-right">
                            <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                <button onclick="editCategory({{ $category->id }})" class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-2xl border border-white/10 transition-all shadow-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button onclick="confirmDelete({{ $category->id }})" class="p-3 bg-white/5 hover:bg-rose-500/20 text-rose-500 rounded-2xl border border-white/10 transition-all shadow-xl">
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
                                    <svg class="w-12 h-12 text-white/20 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
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
    </div>
</div>

<!-- Category Modal -->
<div id="category-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-brand-obsidian/40 backdrop-blur-2xl hidden">
    <div class="glass-panel w-full max-w-2xl rounded-[4rem] shadow-[0_40px_100px_rgba(0,0,0,0.5)] border-white/10 overflow-hidden animate-in fade-in zoom-in duration-500">
        <div class="px-12 pt-12 pb-8 flex items-center justify-between bg-white/[0.02] border-b border-white/5">
            <div>
                <h4 id="modal-title" class="text-3xl font-black text-white tracking-tighter">@lang('admin.category_config')</h4>
                <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.4em] mt-2">@lang('admin.structural_protocol')</p>
            </div>
            <button onclick="closeModal()" class="p-4 bg-white/5 hover:bg-white/10 text-white rounded-3xl border border-white/10 transition-all shadow-xl group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="category-form" class="px-12 py-12 space-y-10">
            <input type="hidden" id="category-id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.node_nomenclature')</label>
                    <input type="text" id="category-name" required class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white placeholder-white/20 focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner" placeholder="IDENTIFIER">
                </div>

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.parent_linkage')</label>
                    <div class="relative">
                        <select id="category-parent" class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white appearance-none focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner cursor-pointer">
                            <option value="" class="bg-brand-obsidian">NULL (@lang('admin.root_domain'))</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" class="bg-brand-obsidian">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none text-brand-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2">@lang('admin.logic_desc')</label>
                <textarea id="category-description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white placeholder-white/20 focus:outline-none focus:border-primary-500 focus:bg-white/10 transition-all shadow-inner resize-none" placeholder="..."></textarea>
            </div>

            <div class="flex items-center justify-between bg-white/[0.02] p-8 rounded-[2.5rem] border border-white/5">
                <div class="flex items-center space-x-6">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="category-active" checked class="sr-only peer">
                        <div class="w-14 h-8 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-600 shadow-inner"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest block">@lang('admin.operational_presence')</span>
                        <span class="text-[9px] font-black text-brand-muted uppercase tracking-widest mt-1 block">@lang('admin.toggle_visibility')</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-6 pt-6">
                <button type="button" onclick="closeModal()" class="px-10 py-5 rounded-[2rem] text-[10px] font-black text-brand-muted uppercase tracking-widest hover:text-white hover:bg-white/5 transition-all">@lang('admin.abort')</button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-12 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(124,58,237,0.2)] transition-all">@lang('admin.deploy_changes')</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('category-modal');
    const form = document.getElementById('category-form');
    let isEditing = false;

    function openModal(mode, data = null) {
        isEditing = mode === 'edit';
        document.getElementById('modal-title').innerText = isEditing ? '{{ __("admin.edit_category") }}' : '{{ __("admin.add_category") }}';
        
        if (isEditing && data) {
            document.getElementById('category-id').value = data.id;
            document.getElementById('category-name').value = data.name;
            document.getElementById('category-parent').value = data.parent_id || '';
            document.getElementById('category-description').value = data.description || '';
            document.getElementById('category-active').checked = data.is_active;
        } else {
            form.reset();
            document.getElementById('category-id').value = '';
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    async function editCategory(id) {
        try {
            const category = await AJAX.fetch(`/admin/categories/${id}`);
            openModal('edit', category);
        } catch (error) {
            console.error(error);
        }
    }

    form.onsubmit = async (e) => {
        e.preventDefault();
        
        const id = document.getElementById('category-id').value;
        const data = {
            name: document.getElementById('category-name').value,
            parent_id: document.getElementById('category-parent').value || null,
            description: document.getElementById('category-description').value,
            is_active: document.getElementById('category-active').checked,
        };

        const url = isEditing ? `/admin/categories/${id}` : '/admin/categories';
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const response = await AJAX.fetch(url, {
                method: method,
                body: JSON.stringify(data)
            });

            AJAX.notify(response.message);
            closeModal();
            location.reload(); // Simple for now, can be updated to update table via DOM
        } catch (error) {
            // Error already notified by AJAX util
        }
    };

    function confirmDelete(id) {
        if (confirm('{{ __("admin.confirm_delete_cat") }}')) {
            deleteCategory(id);
        }
    }

    async function deleteCategory(id) {
        try {
            const response = await AJAX.fetch(`/admin/categories/${id}`, { method: 'DELETE' });
            AJAX.notify(response.message);
            document.getElementById(`category-row-${id}`).remove();
        } catch (error) {
            // Error notified
        }
    }
</script>
@endpush
