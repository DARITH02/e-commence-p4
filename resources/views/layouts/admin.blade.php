<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ $admin_settings['store_name'] ?? __('admin.app_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function() {
            if (localStorage.getItem('ecomm-theme') === 'light') {
                document.documentElement.classList.add('light');
            }
        })();
    </script>

    <style>
        /* Mode toggle button */
        .mode-toggle {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--ink);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted-2);
            transition: background 0.15s, color 0.15s;
        }

        .mode-toggle:hover {
            background: var(--ink-2);
            color: var(--text);
            border-color: var(--border-2);
        }

        .mode-toggle svg {
            width: 16px;
            height: 16px;
        }

        html.light .icon-moon {
            display: none;
        }

        html:not(.light) .icon-sun {
            display: none;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

      

        /* ── Layout shell ── */
        .shell {
            display: flex;
            height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background: #090a0f;
            /* Deeper, more premium dark */
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 40;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.light .sidebar {
            background: #ffffff;
        }

        .sidebar-logo {
            padding: 32px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-mark {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(79, 110, 247, 0.3);
        }

        .logo-mark svg {
            width: 20px;
            height: 20px;
            color: #fff;
        }

        .logo-text {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--text);
            line-height: 1.1;
        }

        .logo-text span {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.15em;
            color: var(--accent);
            display: block;
            margin-top: 2px;
            text-transform: uppercase;
            opacity: 0.8;
        }

        /* Nav */
        .nav-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px 14px;
            scrollbar-width: none;
        }

        .nav-body::-webkit-scrollbar {
            display: none;
        }

        .nav-section-label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 0 12px 12px;
            margin-top: 12px;
            opacity: 0.6;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--muted-2);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 4px;
            position: relative;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text);
            transform: translateX(4px);
        }

        html.light .nav-item:hover {
            background: rgba(0, 0, 0, 0.03);
        }

        .nav-item.active {
            background: var(--accent-glow);
            color: var(--accent);
            border-color: rgba(79, 110, 247, 0.15);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            right: 14px;
            width: 5px;
            height: 5px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent);
        }

        .nav-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            flex-shrink: 0;
            transition: all 0.25s;
            color: var(--muted);
        }

        html.light .nav-icon {
            background: rgba(0, 0, 0, 0.03);
        }

        .nav-item:hover .nav-icon {
            background: var(--ink-3);
            color: var(--accent);
        }

        .nav-item.active .nav-icon {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 10px rgba(79, 110, 247, 0.3);
        }

        .nav-item svg {
            width: 16px;
            height: 16px;
        }

        .nav-badge {
            margin-left: auto;
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 8px;
            background: var(--red-bg);
            color: var(--red);
            border: 1px solid rgba(240, 82, 82, 0.2);
        }

        /* Sidebar footer */
        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 16px 12px;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            object-fit: cover;
            border: 1px solid var(--border-2);
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            line-height: 1;
        }

        .user-role {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            color: var(--accent);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .logout-btn {
            margin-left: auto;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            flex-shrink: 0;
        }

        .logout-btn:hover {
            background: rgba(240, 82, 82, 0.1);
            color: var(--red);
            border-color: rgba(240, 82, 82, 0.3);
        }

        .logout-btn svg {
            width: 14px;
            height: 14px;
        }

        /* ── Main ── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        /* Topbar */
        .topbar {
            height: 64px;
            flex-shrink: 0;
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            gap: 20px;
            background: var(--ink-2);
            position: relative;
            z-index: 10;
        }

        .topbar-title {
            flex: 1;
        }

        .page-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }

        .page-breadcrumb {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .page-breadcrumb .sep {
            color: var(--accent);
            margin: 0 5px;
        }

        /* Search */
        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: var(--muted);
            pointer-events: none;
        }

        .search-input {
            background: var(--ink);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 9px 14px 9px 36px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            outline: none;
            width: 240px;
            transition: border-color 0.2s, width 0.3s;
        }

        .search-input::placeholder {
            color: var(--muted);
        }

        .search-input:focus {
            border-color: rgba(79, 110, 247, 0.5);
            width: 300px;
        }

        /* Action buttons */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--ink);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted-2);
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            position: relative;
        }

        .icon-btn:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
            border-color: var(--border-2);
        }

        .icon-btn svg {
            width: 16px;
            height: 16px;
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--ink-2);
        }

        .mobile-menu-btn {
            display: none;
        }

        /* Divider */
        .topbar-divider {
            width: 1px;
            height: 24px;
            background: var(--border);
            flex-shrink: 0;
        }

        /* ── Content ── */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 28px 28px 60px;
            scrollbar-width: thin;
            scrollbar-color: var(--ink-3) transparent;
            background: var(--bg);
        }

        .content-area::-webkit-scrollbar {
            width: 5px;
        }

        .content-area::-webkit-scrollbar-thumb {
            background: var(--ink-3);
            border-radius: 10px;
        }

        .content-inner {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ── Footer ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        footer p,
        footer a {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
        }

        footer a:hover {
            color: var(--accent);
        }

        footer .footer-links {
            display: flex;
            gap: 24px;
        }

        /* ── Toast ── */
        #toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--ink-2);
            border: 1px solid var(--border-2);
            border-radius: 12px;
            padding: 14px 16px;
            min-width: 280px;
            max-width: 360px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            transform: translateX(20px);
            opacity: 0;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
            pointer-events: auto;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-icon svg {
            width: 14px;
            height: 14px;
        }

        .toast.success .toast-icon {
            background: rgba(34, 201, 122, 0.12);
            color: var(--green);
        }

        .toast.error .toast-icon {
            background: rgba(240, 82, 82, 0.12);
            color: var(--red);
        }

        .toast-type {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .toast.success .toast-type {
            color: var(--green);
        }

        .toast.error .toast-type {
            color: var(--red);
        }

        .toast-msg {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            line-height: 1.4;
        }

        /* ── Loading ── */
        #loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(13, 15, 20, 0.85);
            backdrop-filter: blur(8px);
            z-index: 500;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #loading-overlay.hidden {
            display: none;
        }

        .spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .spin-ring {
            width: 40px;
            height: 40px;
            border: 2px solid var(--border-2);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .spin-label {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--muted);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Sidebar overlay (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 39;
            backdrop-filter: blur(2px);
        }

        /* ── Responsive ── */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                inset-y: 0;
                left: 0;
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .search-wrap {
                display: none;
            }
        }

        @media (min-width: 1024px) {
            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="shell">

        <!-- ── Sidebar overlay (mobile tap-away) ── -->
        <div class="sidebar-overlay hidden" id="sidebar-overlay" onclick="closeSidebar()"></div>

        <!-- ── Sidebar ── -->
        <aside class="sidebar" id="sidebar">
            <!-- Logo -->
            <div class="sidebar-logo">
                <div class="logo-mark">
                    @if(isset($admin_settings['store_logo']))
                        <img src="{{ asset('storage/' . $admin_settings['store_logo']) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    @endif
                </div>
                <div class="logo-text">
                    {{ $admin_settings['store_name'] ?? config('app.name', 'ECOMM PRO') }}
                    <span>@lang('admin.nav_admin_console')</span>
                </div>
            </div>

            <div class="px-6 py-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl bg-accent/10 border border-accent/20 group">
                    <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center shadow-lg shadow-accent/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-accent uppercase tracking-wider">@lang('admin.nav_administrator')</div>
                        <div class="text-xs font-bold text-text truncate max-w-[120px]">{{ auth()->user()->name }}</div>
                    </div>
                </a>
            </div>

            <!-- Nav -->
            <nav class="nav-body">
                <!-- Overview -->
                <div class="nav-section-label">@lang('admin.nav_dashboard')</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    @lang('admin.nav_dashboard')
                </a>

                <!-- Catalog -->
                <div class="nav-section-label" style="margin-top:20px">@lang('admin.nav_products')</div>

                <a href="{{ route('admin.products') }}"
                    class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    @lang('admin.nav_products')
                </a>

                <a href="{{ route('admin.categories') }}"
                    class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    @lang('admin.nav_categories')
                </a>

                <!-- Commerce -->
                <div class="nav-section-label" style="margin-top:20px">@lang('admin.nav_orders')</div>

                <a href="{{ route('admin.orders') }}"
                    class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @lang('admin.nav_orders')
                    <span class="nav-badge">12</span>
                </a>

                <a href="{{ route('admin.customers') }}"
                    class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    @lang('admin.nav_customers')
                </a>

                <div class="nav-section-label" style="margin-top:20px">@lang('admin.nav_settings')</div>

                <a href="{{ route('admin.settings') }}"
                    class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @lang('admin.nav_settings')
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.admins') }}"
                    class="nav-item {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    @lang('admin.nav_admins')
                </a>
                @endif
            </nav>

            <!-- User footer -->
            <div class="sidebar-footer">
                <div class="user-card">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f6ef7&color=fff&bold=true"
                        class="user-avatar" alt="">
                    <div style="min-width:0">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">@lang('admin.nav_administrator')</div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn" title="@lang('admin.nav_sign_out')">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ── Main ── -->
        <main class="main">

            <!-- Topbar -->
            <header class="topbar">
                <!-- Mobile menu -->
                <button class="icon-btn mobile-menu-btn" onclick="openSidebar()" aria-label="Open menu">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Title -->
                <div class="topbar-title">
                    <div class="page-title">@yield('    ', __('admin.dashboard'))</div>
                    <div class="page-breadcrumb">
                        {{ config('app.name', 'ECOMM PRO') }} <span class="sep">·</span> @yield('page_title', __('admin.dashboard'))
                    </div>
                </div>

                <!-- Search -->
                <div class="search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input class="search-input" type="text" placeholder="@lang('admin.nav_search')">
                </div>

                <div class="topbar-divider"></div>

                <div class="topbar-actions">
                    <!-- Language Switcher -->
                    <!-- <div class="flex items-center gap-1  w-20 bg-blue-500 rounded-xl border border-border">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() == 'en' ? 'bg-accent text-white shadow-lg' : 'text-muted hover:text-text' }}">
                            EN
                        </a>
                        <a href="{{ route('lang.switch', 'km') }}"
                            class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() == 'km' ? 'bg-accent text-white shadow-lg' : 'text-muted hover:text-text' }}">
                            KM
                        </a>
                    </div> -->
                    <!-- Language Switcher -->
                    <div style="display:flex;align-items:center;gap:2px;background:var(--ink-3);border:1px solid var(--border);border-radius:10px;padding:3px;">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="lang-switch-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('lang.switch', 'km') }}"
                            class="lang-switch-btn {{ App::getLocale() == 'km' ? 'active' : '' }}">KM</a>
                    </div>

                    <style>
                    .lang-switch-btn { padding:4px 10px; border-radius:8px; font-size:10px; font-weight:800; letter-spacing:0.08em; text-decoration:none; transition:all 0.15s; color:var(--muted); }
                                        .lang-switch-btn.active { background:var(--accent); color:#fff; }
                    
                    /* Notification Styles */
                    .notif-dropdown { 
                        position:absolute; right:0; top:calc(100% + 12px); width:340px; 
                        background:rgba(23, 23, 28, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
                        border:1px solid rgba(255, 255, 255, 0.08); border-radius:22px; 
                        box-shadow:0 24px 64px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05); 
                        opacity:0; transform:translateY(12px); pointer-events:none; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index:100; 
                    }
                    .notif-dropdown.show { opacity:1; transform:translateY(0); pointer-events:all; }
                    .notif-header { padding:18px 20px; border-bottom:1px solid rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:space-between; }
                    .notif-title { font-size:15px; font-weight:800; color:#fff; letter-spacing:-0.01em; }
                    .notif-badge { font-family:'DM Mono',monospace; font-size:10px; font-weight:800; letter-spacing:0.05em; color:var(--accent); background:rgba(var(--accent-rgb), 0.15); padding:4px 10px; border-radius:100px; border:1px solid rgba(var(--accent-rgb), 0.2); }
                    .notif-list { max-height:360px; overflow-y:auto; scrollbar-width: none; }
                    .notif-list::-webkit-scrollbar { display: none; }
                    .notif-item { padding:16px 20px; display:flex; gap:14px; border-bottom:1px solid rgba(255,255,255,0.04); cursor:pointer; transition:all 0.2s; position:relative; overflow:hidden; }
                    .notif-item:hover { background:rgba(255,255,255,0.04); }
                    .notif-item:active { background:rgba(255,255,255,0.06); transform: scale(0.98); }
                    .notif-icon { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px; }
                    .notif-icon.order { background:rgba(16, 185, 129, 0.15); color:#10b981; border:1px solid rgba(16, 185, 129, 0.1); }
                    .notif-icon.admin { background:rgba(99, 102, 241, 0.15); color:#818cf8; border:1px solid rgba(99, 102, 241, 0.1); }
                    .notif-icon.auth { background:rgba(245, 158, 11, 0.15); color:#f59e0b; border:1px solid rgba(245, 158, 11, 0.1); }
                    .notif-icon.security { background:rgba(239, 68, 68, 0.15); color:#ef4444; border:1px solid rgba(239, 68, 68, 0.1); }
                    .notif-content { display:flex; flex-direction:column; gap:2px; }
                    .notif-msg { font-size:13px; font-weight:600; color:rgba(255,255,255,0.9); line-height:1.4; }
                    .notif-time { font-family:'DM Mono',monospace; font-size:10px; color:rgba(255,255,255,0.4); margin-top:2px; font-weight:500; }
                    .notif-empty { padding:60px 20px; text-align:center; color:rgba(255,255,255,0.3); font-size:13px; font-weight:500; }
                    .notif-footer { padding:14px; text-align:center; border-top:1px solid rgba(255,255,255,0.06); }
                    .notif-view-all { font-family:'DM Mono',monospace; font-size:11px; font-weight:800; color:var(--accent); text-decoration:none; letter-spacing:0.08em; text-transform:uppercase; transition: color 0.2s; }
                    .notif-view-all:hover { color:#fff; }
                    </style>

                    <div class="topbar-divider"></div>

                    <!-- Notifications -->
                    <div class="relative" id="notif-wrap">
                        <button class="icon-btn" title="@lang('admin.nav_notifications')" onclick="toggleNotif()" id="notif-btn">
                          
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                            @if(isset($unread_notifications_count) && $unread_notifications_count > 0)
                                <span class="notif-dot"></span>
                            @endif
                        </button>
                        <!-- Notifications Dropdown -->
                        <div class="notif-dropdown">
                            <div class="notif-header">
                                <span class="notif-title">@lang('admin.nav_notifications')</span>
                                @if(isset($unread_notifications_count) && $unread_notifications_count > 0)
                                    <span class="notif-badge">{{ $unread_notifications_count }} NEW</span>
                                @endif
                            </div>
                            <div class="notif-list" id="notif-list">
                                @if(isset($admin_notifications) && count($admin_notifications) > 0)
                                    @foreach($admin_notifications as $notif)
                                        @php
                                            $type = $notif->data['type'] ?? 'default';
                                            $iconClass = 'admin';
                                            $svg = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>';
                                            
                                            if ($type === 'new_order') {
                                                $iconClass = 'order';
                                                $svg = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
                                            } elseif ($type === 'new_admin') {
                                                $iconClass = 'admin';
                                                $svg = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>';
                                            } elseif ($type === 'admin_login') {
                                                $iconClass = 'auth';
                                                $svg = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>';
                                            } elseif ($type === 'admin_logout') {
                                                $iconClass = 'auth';
                                                $svg = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>';
                                            }
                                        @endphp
                                        <div class="notif-item">
                                            <div class="notif-icon {{ $iconClass }}">
                                                {!! $svg !!}
                                            </div>
                                            <div class="notif-content">
                                                <p class="notif-msg">{{ $notif->data['message'] }}</p>
                                                <p class="notif-time">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="notif-empty">
                                        No new notifications
                                    </div>
                                @endif
                            </div>
                            <div class="notif-footer">
                                <a href="#" class="notif-view-all">View all</a>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}" class="icon-btn" title="@lang('admin.nav_settings')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>

                    <!-- Dark / Light toggle -->
                    <button class="mode-toggle" onclick="toggleMode()" title="@lang('admin.toggle_theme')">
                        <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Content -->
            <div class="content-area">
                <div class="content-inner">
                    @yield('content')
                </div>

                <footer>
                    <p>&copy; {{ date('Y') }} {{ $admin_settings['store_name'] ?? __('admin.app_name') }} &nbsp;·&nbsp; @lang('admin.version') 4.0.2</p>
                    <div class="footer-links">
                        <a href="#">@lang('admin.docs')</a>
                        <a href="#">@lang('admin.api_docs')</a>
                        <a href="#">@lang('admin.security')</a>
                    </div>
                </footer>
            </div>

        </main>
    </div>

    <!-- Toast container -->
    <div id="toast-container"></div>

    <!-- Loading overlay -->
    <div id="loading-overlay" class="hidden">
        <div class="spinner">
            <div class="spin-ring"></div>
            <div class="spin-label">@lang('admin.loading')</div>
        </div>
    </div>

    <script>
        /* ── Dark / Light mode (global) ── */
        function toggleMode() {
            const isLight = document.documentElement.classList.toggle('light');
            localStorage.setItem('ecomm-theme', isLight ? 'light' : 'dark');
            document.dispatchEvent(new CustomEvent('themechange', { detail: { light: isLight } }));
        }

        /* ── Notification dropdown ── */
        function toggleNotif() {
            const dd = document.querySelector('.notif-dropdown');
            if (!dd) return;
            dd.classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('notif-wrap');
            if (wrap && !wrap.contains(e.target)) {
                const dd = document.querySelector('.notif-dropdown');
                if (dd) dd.classList.remove('show');
            }
        });

        /* ── Sidebar (mobile) ── */
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.remove('hidden');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }

        /* ── AJAX helper ── */
        const AJAX = {
            async fetch(url, options = {}) {
                document.getElementById('loading-overlay').classList.remove('hidden');
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const res = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        ...options
                    });
                    const data = await res.json();
                    if (!res.ok) throw {
                        status: res.status,
                        ...data
                    };
                    return data;
                } catch (err) {
                    this.notify(err.message || 'Something went wrong', 'error');
                    throw err;
                } finally {
                    document.getElementById('loading-overlay').classList.add('hidden');
                }
            },

            notify(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;

                const iconPath = type === 'success' ?
                    'M5 13l4 4L19 7' :
                    'M6 18L18 6M6 6l12 12';

                toast.innerHTML = `
                <div class="toast-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="${iconPath}"/>
                    </svg>
                </div>
                <div>
                    <div class="toast-type">${type}</div>
                    <div class="toast-msg">${message}</div>
                </div>
            `;

                container.appendChild(toast);
                requestAnimationFrame(() => toast.classList.add('show'));

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 4500);
            }
        };
    </script>

    @stack('scripts')
</body>

</html>