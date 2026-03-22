@extends('layouts.admin')

@section('title', __('admin.manage_admins'))
@section('page_title', __('admin.manage_admins'))

@push('styles')
<style>
    .admins-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
        animation: fadeUp .45s cubic-bezier(0.16,1,0.3,1) both;
    }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(12px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .page-header {
        display: flex; align-items: center; justify-content: space-between; gap: 16px;
    }
    .page-heading h1 { font-size: 22px; font-weight: 700; color: var(--text-1); letter-spacing: -0.02em; }
    .page-heading p { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; }

    .btn-primary {
        background: var(--accent); color: #fff; padding: 10px 18px; border-radius: var(--radius-md);
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
        transition: all var(--transition); border: none; cursor: pointer;
    }
    .btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }

    .admin-card {
        background: var(--surface); border: 1px solid var(--border-1); border-radius: var(--radius-lg);
        overflow: hidden; box-shadow: var(--shadow-card);
    }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th {
        text-align: left; padding: 14px 20px; font-family: 'DM Mono', monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-3); border-bottom: 1px solid var(--border-1);
        background: var(--surface-2);
    }
    .admin-table td { padding: 16px 20px; border-bottom: 1px solid var(--border-1); font-size: 14px; color: var(--text-2); }
    .admin-table tr:last-child td { border-bottom: none; }
    
    .admin-info { display: flex; align-items: center; gap: 12px; }
    .admin-avatar {
        width: 32px; height: 32px; border-radius: 50%; background: var(--accent-dim);
        display: flex; align-items: center; justify-content: center; color: var(--accent);
        font-weight: 700; font-size: 12px;
    }
    .admin-details { display: flex; flex-direction: column; gap: 2px; }
    .admin-name { font-weight: 600; color: var(--text-1); }
    .admin-email { font-size: 12px; color: var(--text-3); }

    .badge {
        display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;
    }
    .badge-super { background: rgba(255,107,0,0.1); color: #ff6b00; border: 1px solid rgba(255,107,0,0.2); }
    .badge-admin { background: rgba(79,114,245,0.1); color: var(--accent); border: 1px solid rgba(79,114,245,0.2); }

    .actions { display: flex; align-items: center; gap: 8px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: var(--radius-md); display: flex; align-items: center;
        justify-content: center; border: 1px solid var(--border-1); background: var(--surface);
        color: var(--text-3); transition: all var(--transition); cursor: pointer;
    }
    .btn-icon:hover { background: var(--surface-2); color: var(--text-1); border-color: var(--text-3); }
    .btn-icon.delete:hover { background: #fff1f1; color: #ff4d4d; border-color: #ffcccc; }

    /* Modal Styles */
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 1000;
        animation: fadeIn 0.2s ease-out;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .modal-card {
        background: var(--surface); width: 100%; max-width: 450px; border-radius: var(--radius-xl);
        box-shadow: 0 20px 50px rgba(0,0,0,0.3); border: 1px solid var(--border-1); overflow: hidden;
        animation: slideIn 0.3s cubic-bezier(0.16,1,0.3,1);
    }
    @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border-1); display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--text-1); }
    .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border-1); background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px; }
    
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 12px; font-weight: 600; color: var(--text-2); }
    .form-control {
        background: var(--surface-2); border: 1px solid var(--border-1); border-radius: var(--radius-md);
        padding: 10px 14px; color: var(--text-1); font-size: 14px; transition: all var(--transition);
    }
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); }
</style>
@endpush

@section('content')
<div class="admins-page">
    <div class="page-header">
        <div class="page-heading">
            <h1>{{ __('admin.admins_management') }}</h1>
            <p>{{ __('admin.manage_your_team') }}</p>
        </div>
        @if(auth()->user()->isSuperAdmin())
        <button class="btn-primary" onclick="openModal()">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ __('admin.add_admin') }}
        </button>
        @endif
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('admin.administrator') }}</th>
                    <th>{{ __('admin.role') }}</th>
                    <th>{{ __('admin.joined_date') }}</th>
                    <th style="text-align: right;">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr id="admin-row-{{ $admin->id }}">
                    <td>
                        <div class="admin-info">
                            <div class="admin-avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                            <div class="admin-details">
                                <span class="admin-name">{{ $admin->name }}</span>
                                <span class="admin-email">{{ $admin->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @foreach($admin->roles as $role)
                        <span class="badge {{ $role->slug === 'super_admin' ? 'badge-super' : 'badge-admin' }}">
                            {{ $role->name }}
                        </span>
                        @endforeach
                    </td>
                    <td>@km($admin->created_at->translatedFormat('M d, Y'))</td>
                    <td style="text-align: right;">
                        <div class="actions" style="justify-content: flex-end;">
                            <button class="btn-icon" onclick="editAdmin({{ json_encode($admin) }}, {{ json_encode($admin->roles->pluck('slug')) }})">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            @if($admin->id !== Auth::id())
                            <button class="btn-icon delete" onclick="deleteAdmin({{ $admin->id }})">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-backdrop" id="adminModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modalTitle">{{ __('admin.add_admin') }}</h3>
            <button class="btn-icon" onclick="closeModal()">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="adminForm" onsubmit="saveAdmin(event)">
            @csrf
            <input type="hidden" id="admin_id" name="admin_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('admin.full_name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Ex: John Doe">
                </div>
                <div class="form-group">
                    <label>{{ __('admin.email_address') }}</label>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label>{{ __('admin.role') }}</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('admin.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Min 8 characters">
                    <small style="font-size: 10px; color: var(--text-3);" id="passwordHint"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="form-control" style="cursor:pointer;" onclick="closeModal()">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn-primary" id="saveBtn">{{ __('admin.save_admin') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('adminModal');
    const form = document.getElementById('adminForm');
    
    function openModal() {
        form.reset();
        document.getElementById('admin_id').value = '';
        document.getElementById('modalTitle').innerText = "{{ __('admin.add_admin') }}";
        document.getElementById('password').required = true;
        document.getElementById('passwordHint').innerText = "";
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    function editAdmin(admin, roles) {
        openModal();
        document.getElementById('modalTitle').innerText = "{{ __('admin.edit_admin') }}";
        document.getElementById('admin_id').value = admin.id;
        document.getElementById('name').value = admin.name;
        document.getElementById('email').value = admin.email;
        document.getElementById('role').value = roles[0]; // Assuming single role for now
        document.getElementById('password').required = false;
        document.getElementById('passwordHint').innerText = "Leave blank to keep current password";
    }

    async function saveAdmin(e) {
        e.preventDefault();
        const btn = document.getElementById('saveBtn');
        const id = document.getElementById('admin_id').value;
        const formData = new FormData(form);
        
        btn.disabled = true;
        btn.innerText = "{{ __('admin.saving') }}...";

        const url = id ? `/admin/admins/${id}` : '/admin/admins';
        const method = id ? 'PUT' : 'POST';

        // Custom fetch for PUT as FormData doesn't support it directly in some cases, 
        // but Laravel handles _method field.
        if (id) formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                showToast(result.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Error occurred', 'error');
                if(result.errors) {
                    // Handle validation errors if needed
                    console.error(result.errors);
                }
            }
        } catch (error) {
            showToast('Connection error', 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = "{{ __('admin.save_admin') }}";
        }
    }

    async function deleteAdmin(id) {
        if (!confirm("{{ __('admin.confirm_delete_admin') }}")) return;

        try {
            const response = await fetch(`/admin/admins/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                showToast(result.message, 'success');
                document.getElementById(`admin-row-${id}`).remove();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('Connection error', 'error');
        }
    }
</script>
@endpush
