@extends('layouts.admin')

@section('title', __('admin.nav_settings'))
@section('page_title', __('admin.nav_settings'))

@push('styles')
<style>
    .settings-page {
        animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .settings-grid {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 32px;
    }
    @media (max-width: 768px) {
        .settings-grid { grid-template-columns: 1fr; }
    }
    .settings-nav {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .settings-nav-item {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted-2);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .settings-nav-item:hover {
        background: var(--ink-2);
        color: var(--text);
    }
    .settings-nav-item.active {
        background: var(--accent-glow);
        color: var(--accent);
        border-color: rgba(79, 110, 247, 0.15);
    }
    .settings-card {
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .settings-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .settings-section-desc {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 24px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-input {
        width: 100%;
        background: var(--ink);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 16px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--text);
        transition: all 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px var(--accent-glow);
    }
    .btn-save {
        background: var(--accent);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(79, 110, 247, 0.3);
    }
    .btn-save:hover {
        background: var(--accent-2);
        transform: translateY(-1px);
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="settings-page">
    <div class="settings-grid">
        <!-- Sidebar Nav -->
        <div class="settings-nav">
            <div class="settings-nav-item active">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
                @lang('admin.general_settings')
            </div>
            <div class="settings-nav-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                @lang('admin.payments')
            </div>
            <div class="settings-nav-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                @lang('admin.security')
            </div>
            <div class="settings-nav-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @lang('admin.notification_settings')
            </div>
        </div>

        <!-- Main Content -->
        <div class="settings-content">
            <div class="settings-card">
                <h2 class="settings-section-title">@lang('admin.general_settings')</h2>
                <p class="settings-section-desc">Manage your store's basic information and preferences.</p>

                <form action="#" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="form-group">
                            <label class="form-label">@lang('admin.store_name')</label>
                            <input type="text" class="form-input" value="ECOMM PRO" placeholder="Enter store name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('admin.store_email')</label>
                            <input type="email" class="form-input" value="contact@ecommpro.com" placeholder="Enter store email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('admin.currency')</label>
                            <select class="form-input">
                                <option value="USD">USD ($)</option>
                                <option value="KHR">KHR (៛)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('admin.language')</label>
                            <select class="form-input">
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                <option value="km" {{ app()->getLocale() == 'km' ? 'selected' : '' }}>Khmer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mt-6">
                        <label class="form-label">@lang('admin.store_description')</label>
                        <textarea class="form-input h-32 resize-none" placeholder="Brief description of your store">The most advanced e-commerce admin platform with real-time analytics and global distribution.</textarea>
                    </div>

                    <div class="pt-6 border-t border-border flex justify-end">
                        <button type="button" class="btn-save">@lang('admin.save_changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
