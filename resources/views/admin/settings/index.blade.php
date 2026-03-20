@extends('layouts.admin')

@section('title', __('admin.nav_settings'))
@section('page_title', __('admin.nav_settings'))

@push('styles')
<style>
/* Settings page uses global design tokens from app.css */
body { background: var(--bg) !important; }
.content-inner { max-width: none !important; }

.settings-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: fadeUp .45s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─── Page header ─── */
.page-header {
    display: flex; align-items: center;
    justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.page-heading { display: flex; flex-direction: column; gap: 4px; }
.page-heading h1 {
    font-size: 22px; font-weight: 700; color: var(--text-1);
    letter-spacing: -0.02em; line-height: 1;
}
.page-heading p {
    font-family: 'DM Mono', monospace; font-size: 10px;
    color: var(--text-3); letter-spacing: 0.06em;
}

/* ─── Grid layout ─── */
.settings-grid {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    align-items: flex-start;
}
@media (max-width: 768px) { .settings-grid { grid-template-columns: 1fr; } }

/* ─── Sidebar nav ─── */
.settings-nav {
    background: var(--surface);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-lg);
    padding: 8px;
    box-shadow: var(--shadow-card);
    position: sticky;
    top: 28px;
}
.settings-nav-label {
    font-family: 'DM Mono', monospace; font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; color: var(--text-3);
    padding: 8px 12px 6px; margin-top: 4px;
}
.settings-nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 600; color: var(--text-2);
    cursor: pointer; transition: all var(--transition);
    border: 1px solid transparent; text-decoration: none;
    margin-bottom: 2px;
}
.settings-nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
.settings-nav-item:hover { background: var(--surface-2); color: var(--text-1); }
.settings-nav-item.active {
    background: var(--accent-dim); color: var(--accent);
    border-color: rgba(79,114,245,0.15);
}

/* ─── Content panels ─── */
.settings-panel {
    display: flex; flex-direction: column; gap: 20px;
}

/* ─── Card ─── */
.settings-card {
    background: var(--surface);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-card);
}
.settings-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-1);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
    background: var(--surface-2);
}
.settings-card-icon {
    width: 36px; height: 36px; border-radius: var(--radius-sm);
    background: var(--accent-dim); border: 1px solid rgba(79,114,245,0.15);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); flex-shrink: 0;
}
.settings-card-icon svg { width: 16px; height: 16px; }
.settings-card-title { font-size: 14px; font-weight: 700; color: var(--text-1); line-height: 1; }
.settings-card-sub  { font-size: 11.5px; color: var(--text-3); margin-top: 3px; }
.settings-card-body { padding: 24px; }

/* ─── Form ─── */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }
.col-span-2 { grid-column: span 2; }
@media (max-width: 640px) { .col-span-2 { grid-column: span 1; } }

.field { display: flex; flex-direction: column; gap: 7px; }
.field label {
    font-family: 'DM Mono', monospace; font-size: 9.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .09em; color: var(--text-3);
}
.field input, .field select, .field textarea {
    background: var(--surface-2);
    border: 1px solid var(--border-1);
    border-radius: var(--radius-md);
    padding: 10px 14px; color: var(--text-1);
    font-family: 'DM Sans', sans-serif;
    font-size: 13px; font-weight: 500;
    transition: border-color var(--transition), box-shadow var(--transition);
    width: 100%;
}
.field input::placeholder, .field textarea::placeholder { color: var(--text-3); }
.field input:focus, .field select:focus, .field textarea:focus {
    outline: none; border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-dim); background: var(--surface-3);
}
.field select { cursor: pointer; }
.field textarea { resize: vertical; min-height: 88px; line-height: 1.6; }
.field .hint { font-size: 11px; color: var(--text-3); line-height: 1.5; }

/* ─── Toggle row ─── */
.toggle-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    background: var(--surface-2); border: 1px solid var(--border-1);
    border-radius: var(--radius-md);
}
.toggle-info { flex: 1; }
.toggle-info strong { font-size: 13px; font-weight: 700; color: var(--text-1); display: block; }
.toggle-info span   { font-size: 11.5px; color: var(--text-3); margin-top: 1px; display: block; }
.toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.toggle-track {
    position: absolute; inset: 0;
    background: var(--surface-3); border: 1px solid var(--border-2);
    border-radius: 100px; cursor: pointer;
    transition: background var(--transition), border-color var(--transition);
}
.toggle-track::after {
    content: ''; position: absolute;
    top: 2px; left: 2px; width: 16px; height: 16px;
    border-radius: 50%; background: var(--text-3);
    transition: transform var(--transition), background var(--transition);
}
.toggle input:checked + .toggle-track { background: var(--accent); border-color: var(--accent); }
.toggle input:checked + .toggle-track::after { transform: translateX(18px); background: #fff; }

/* ─── Form footer ─── */
.form-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border-1);
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    background: var(--surface-2);
}
.form-footer-hint {
    font-family: 'DM Mono', monospace; font-size: 9.5px; color: var(--text-3);
    letter-spacing: .04em;
}
.btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--accent); color: #fff;
    padding: 9px 20px; border-radius: var(--radius-md);
    font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 700;
    border: none; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,114,245,0.25);
    transition: all var(--transition);
}
.btn-save svg { width: 13px; height: 13px; }
.btn-save:hover { background: var(--accent-2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,114,245,0.35); }

/* ─── Coming soon badge ─── */
.coming-soon {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 3px 9px; border-radius: 100px;
    font-family: 'DM Mono', monospace; font-size: 9px; font-weight: 700;
    background: var(--amber-dim); color: var(--amber);
    border: 1px solid rgba(232,160,0,0.2); letter-spacing: .04em;
}

/* Toast */
.toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 2000;
    display: flex; align-items: center; gap: 10px; padding: 12px 18px;
    background: var(--surface); border: 1px solid var(--border-2);
    border-radius: var(--radius-md); box-shadow: 0 8px 24px rgba(0,0,0,.4);
    font-size: 13px; font-weight: 600; color: var(--text-1);
    transform: translateY(12px); opacity: 0;
    transition: all .25s cubic-bezier(0.16,1,0.3,1);
    pointer-events: none; max-width: 320px;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.toast.success .toast-dot { background: var(--green); }
.toast.error   .toast-dot { background: var(--red); }
</style>
@endpush

@section('content')
<div class="settings-page">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-heading">
            <h1>@lang('admin.nav_settings')</h1>
            <p>@lang('admin.settings_description', ['app' => config('app.name', 'ECOMM PRO')])</p>
        </div>
    </div>

    <div class="settings-grid">

        {{-- ── Sidebar Navigation ── --}}
        <div class="settings-nav">
            <div class="settings-nav-label">Configuration</div>
            <div class="settings-nav-item active" onclick="showPanel('general')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                @lang('admin.general_settings')
            </div>
            <div class="settings-nav-item" onclick="showPanel('payments')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                @lang('admin.payments')
            </div>
            <div class="settings-nav-item" onclick="showPanel('security')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                @lang('admin.security')
            </div>
            <div class="settings-nav-label">Alerts</div>
            <div class="settings-nav-item" onclick="showPanel('notifications')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @lang('admin.notification_settings')
            </div>
        </div>

        {{-- ── Main panels ── --}}
        <div class="settings-panel">

            {{-- General Settings --}}
            <div class="settings-card" id="panel-general">
                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="settings-card-title">@lang('admin.general_settings')</div>
                        <div class="settings-card-sub">@lang('admin.general_settings_desc')</div>
                    </div>
                </div>
                <div class="settings-card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" id="general-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <div class="field col-span-2">
                                <label>@lang('admin.store_logo')</label>
                                <div style="display: flex; align-items: center; gap: 20px; background: var(--surface-2); padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--border-1);">
                                    <div style="width: 80px; height: 80px; border-radius: var(--radius-sm); background: var(--surface-3); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border-2);">
                                        @if(!empty($settings['store_logo']))
                                            @php
                                                 $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dnrblpkal');
                                                 $version   = config('cloudinary.asset_version');
                                                 $prefix    = config('cloudinary.upload_folder');
                                                $logoPreviewUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}" . (!empty($prefix) ? "/{$prefix}" : "") . "/{$settings['store_logo']}";
                                            @endphp
                                            <img src="{{ $logoPreviewUrl }}" id="logo-preview" style="width: 100%; height: 100%; object-fit: contain;">
                                        @else
                                            <svg id="logo-placeholder" style="width: 32px; height: 32px; color: var(--text-3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <img src="" id="logo-preview" style="width: 100%; height: 100%; object-fit: contain; display: none;">
                                        @endif
                                    </div>
                                    <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                                        <label for="logo-input" class="btn-save" style="width: fit-content; cursor: pointer; background: var(--surface-3); color: var(--text-1); box-shadow: none; border: 1px solid var(--border-2);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            @lang('admin.upload_logo')
                                        </label>
                                        <input type="file" id="logo-input" name="logo" hidden accept="image/*" onchange="previewLogo(this)">
                                        <input type="text" name="logo_url" placeholder="Or enter Logo URL..." 
                                               value="{{ (isset($settings['store_logo']) && filter_var($settings['store_logo'], FILTER_VALIDATE_URL)) ? $settings['store_logo'] : '' }}"
                                               style="margin-top: 8px; font-size: 11px; padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-2); background: var(--surface-3); width: 100%;"
                                               oninput="previewLogoUrl(this.value)">
                                        <span class="hint">Recommended: 200x200px, PNG or SVG. Max 2MB.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label>@lang('admin.store_name') <span style="color:var(--red)">*</span></label>
                                <input type="text" name="store_name" value="{{ $settings['store_name'] ?? config('app.name', 'ECOMM PRO') }}" placeholder="Enter store name">
                            </div>
                            <div class="field">
                                <label>@lang('admin.store_email') <span style="color:var(--red)">*</span></label>
                                <input type="email" name="store_email" value="{{ $settings['store_email'] ?? 'contact@ecommpro.com' }}" placeholder="Enter store email">
                            </div>
                            <div class="field">
                                <label>@lang('admin.currency')</label>
                                <select name="currency">
                                    <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($) — US Dollar</option>
                                    <option value="KHR" {{ ($settings['currency'] ?? '') == 'KHR' ? 'selected' : '' }}>KHR (៛) — Cambodian Riel</option>
                                    <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€) — Euro</option>
                                    <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£) — British Pound</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>@lang('admin.language')</label>
                                <select name="language">
                                    <option value="en" {{ ($settings['language'] ?? app()->getLocale()) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="km" {{ ($settings['language'] ?? '') == 'km' ? 'selected' : '' }}>ខ្មែរ (Khmer)</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>@lang('admin.timezone')</label>
                                <select name="timezone">
                                    <option value="Asia/Phnom_Penh" {{ ($settings['timezone'] ?? 'Asia/Phnom_Penh') == 'Asia/Phnom_Penh' ? 'selected' : '' }}>Asia/Phnom_Penh (UTC+7)</option>
                                    <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                    <option value="Europe/London" {{ ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>@lang('admin.date_format')</label>
                                <select name="date_format">
                                    <option value="d M Y" {{ ($settings['date_format'] ?? 'd M Y') == 'd M Y' ? 'selected' : '' }}>01 Jan 2026</option>
                                    <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>2026-01-01</option>
                                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>01/01/2026</option>
                                    <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>01/01/2026 (EU)</option>
                                </select>
                            </div>
                            <div class="field col-span-2">
                                <label>@lang('admin.store_description')</label>
                                <textarea name="store_description" placeholder="Brief description of your store">{{ $settings['store_description'] ?? 'The most advanced e-commerce admin platform with real-time analytics and global distribution.' }}</textarea>
                                <span class="hint">Shown in search engines and meta tags. Keep under 160 characters.</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-footer">
                    <span class="form-footer-hint">* required fields</span>
                    @if(Auth::user()->isSuperAdmin())
                    <button type="button" class="btn-save" id="save-btn" onclick="saveSettings('general')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="btn-text">@lang('admin.save_changes')</span>
                    </button>
                    @else
                    <button type="button" class="btn-save" style="opacity:0.5;cursor:not-allowed;" disabled title="Only Super Admin can change global store settings">Super Admin Only</button>
                    @endif
                </div>
            </div>

            {{-- Payments --}}
            <div class="settings-card" id="panel-payments" style="display:none;">
                <div class="settings-card-header">
                    <div class="settings-card-icon" style="background:var(--green-dim);border-color:rgba(31,186,114,0.15);color:var(--green);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="settings-card-title">@lang('admin.payments')</div>
                        <div class="settings-card-sub">Configure how you receive money from customers.</div>
                    </div>
                </div>
                <div class="settings-card-body">
                    <div class="form-grid">
                        <div class="field col-span-2">
                            <label>Payment Providers</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div class="toggle-row">
                                    <div class="toggle-info"><strong>Stripe</strong><span>Global credit/debit card payments</span></div>
                                    <label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div></label>
                                </div>
                                <div class="toggle-row">
                                    <div class="toggle-info"><strong>PayPal</strong><span>Standard digital wallet checkout</span></div>
                                    <label class="toggle"><input type="checkbox"><div class="toggle-track"></div></label>
                                </div>
                                <div class="toggle-row">
                                    <div class="toggle-info"><strong>ABA KHQR</strong><span>Local bank transfers via QR code</span></div>
                                    <label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div></label>
                                </div>
                                <div class="toggle-row">
                                    <div class="toggle-info"><strong>Cash on Delivery</strong><span>Allow pay after receiving items</span></div>
                                    <label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <span class="form-footer-hint">* configuration updates in real-time</span>
                    @if(Auth::user()->isSuperAdmin())
                    <button type="button" class="btn-save" onclick="saveSettings('payments')">@lang('admin.save_changes')</button>
                    @else
                    <button type="button" class="btn-save" style="opacity:0.5;cursor:not-allowed;" disabled title="Only Super Admin can change payment settings">Super Admin Only</button>
                    @endif
                </div>
            </div>

            {{-- Security --}}
            <div class="settings-card" id="panel-security" style="display:none;">
                <div class="settings-card-header">
                    <div class="settings-card-icon" style="background:var(--red-dim);border-color:rgba(232,69,69,0.15);color:var(--red);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="settings-card-title">@lang('admin.security')</div>
                        <div class="settings-card-sub">Manage your account protection and permissions.</div>
                    </div>
                </div>
                <div class="settings-card-body">
                    <div class="form-grid">
                        <div class="field col-span-2">
                             <div class="toggle-row">
                                <div class="toggle-info">
                                    <strong>Two-Factor Authentication (2FA)</strong>
                                    <span>Add an extra layer of security to your account.</span>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox">
                                    <div class="toggle-track"></div>
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Password Requirements</label>
                            <select>
                                <option>Minimum 8 chars, 1 digit</option>
                                <option>Minimum 12 chars, strong</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Session Timeout</label>
                            <select>
                                <option>15 Minutes</option>
                                <option selected>30 Minutes</option>
                                <option>1 Hour</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <span class="form-footer-hint">last updated @lang('admin.updated_just_now')</span>
                     @if(Auth::user()->isSuperAdmin())
                    <button type="button" class="btn-save" onclick="saveSettings('security')">@lang('admin.save_changes')</button>
                    @else
                    <button type="button" class="btn-save" style="opacity:0.5;cursor:not-allowed;" disabled title="Only Super Admin can change security settings">Super Admin Only</button>
                    @endif
                </div>
            </div>

            {{-- Notifications --}}
            <div class="settings-card" id="panel-notifications" style="display:none;">
                <div class="settings-card-header">
                    <div class="settings-card-icon" style="background:var(--blue-dim);border-color:var(--blue-mid);color:var(--blue);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <div class="settings-card-title">@lang('admin.notification_settings')</div>
                        <div class="settings-card-sub">Choose how and when you want to be notified.</div>
                    </div>
                </div>
                <div class="settings-card-body">
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div class="toggle-row">
                            <div class="toggle-info"><strong>New Order Notifications</strong><span>Get notified immediately when a sale is made.</span></div>
                            <label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div></label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info"><strong>Low Stock Alerts</strong><span>Receive warnings when product units are below threshold.</span></div>
                            <label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div></label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info"><strong>System Updates</strong><span>Stay informed about new platform versions.</span></div>
                            <label class="toggle"><input type="checkbox"><div class="toggle-track"></div></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast" id="toast">
    <span class="toast-dot"></span>
    <span id="toast-msg"></span>
</div>
@endsection

@push('scripts')
<script>
/* ── Panel switcher ── */
function showPanel(name) {
    document.querySelectorAll('.settings-nav-item').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.settings-card').forEach(card => card.style.display = 'none');
    
    // Find the item that was clicked (if called via event)
    if(event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    } else {
        // Default to finding the item by text or data
        const items = document.querySelectorAll('.settings-nav-item');
        items.forEach(item => {
            if(item.textContent.toLowerCase().includes(name)) item.classList.add('active');
        });
    }

    const panel = document.getElementById('panel-' + name);
    if(panel) {
        panel.style.display = 'flex';
        panel.style.animation = 'fadeUp .3s ease both';
    }
}

/* ── Dark mode toggle sync ── */
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('dark-mode-toggle');
    if (toggle) {
        const isLight = document.documentElement.classList.contains('light');
        toggle.checked = !isLight; // checked = dark mode active
    }
});
function handleThemeToggle(input) {
    const isDark = input.checked;
    const isLight = !isDark;
    if (isLight) {
        document.documentElement.classList.add('light');
    } else {
        document.documentElement.classList.remove('light');
    }
    localStorage.setItem('ecomm-theme', isLight ? 'light' : 'dark');
    document.dispatchEvent(new CustomEvent('themechange', { detail: { light: isLight } }));
}

/* ── Save handler ── */
function saveSettings(panel) {
    if (panel !== 'general') {
        showToast('Only General settings are functional in this demo.', 'error');
        return;
    }

    const form = document.getElementById('general-form');
    const formData = new FormData(form);
    const saveBtn = document.getElementById('save-btn');
    const btnText = saveBtn.querySelector('.btn-text');
    const originalText = btnText.textContent;

    // Loading state
    saveBtn.disabled = true;
    saveBtn.style.opacity = '0.7';
    btnText.textContent = 'Saving...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message || 'Settings saved successfully.', 'success');
        if (data.message.includes('successfully')) {
            // Optional: refresh page or update UI elements like store name if changed
            // window.location.reload(); 
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to save settings. Please try again.', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.style.opacity = '1';
        btnText.textContent = originalText;
    });
}

function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-msg');
    
    toast.className = 'toast ' + type;
    toastMsg.textContent = msg;
    toast.classList.add('show');
    
    setTimeout(() => toast.classList.remove('show'), 3500);
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            updateLogoPreview(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewLogoUrl(url) {
    if (url && url.startsWith('http')) {
        updateLogoPreview(url);
    }
}

function updateLogoPreview(src) {
    const preview = document.getElementById('logo-preview');
    const placeholder = document.getElementById('logo-placeholder');
    
    preview.src = src;
    preview.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
}
</script>
@endpush
