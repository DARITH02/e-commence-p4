<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@lang('admin.create_account') — {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #080a10;
      --panel:     #0e111a;
      --panel2:    #121622;
      --border:    rgba(255,255,255,0.07);
      --border-hi: rgba(255,255,255,0.12);
      --accent:    #5b8af5;
      --accent2:   #8b5cf6;
      --gold:      #f0b429;
      --text:      #f2f4f8;
      --muted:     #64748b;
      --muted2:    #94a3b8;
      --error:     #f43f5e;
      --inp:       #060812;
      --theme-toggle-bg: rgba(255,255,255,0.05);
    }

    body.light-mode {
      --bg:        #f8fafc;
      --panel:     #ffffff;
      --panel2:    #f1f5f9;
      --border:    rgba(0,0,0,0.05);
      --border-hi: rgba(0,0,0,0.08);
      --accent:    #2563eb;
      --accent2:   #7c3aed;
      --gold:      #d97706;
      --text:      #0f172a;
      --muted:     #64748b;
      --muted2:    #475569;
      --error:     #e11d48;
      --inp:       #f9fbff;
      --theme-toggle-bg: rgba(0,0,0,0.03);
    }

    html, body { height: 100%; transition: background-color 0.3s, color 0.3s; }

    body {
      font-family: 'Inter', 'Kantumruy Pro', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      color: var(--text);
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* Ambient orbs */
    .bg-orbs { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
    .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.35; animation: orbFloat 18s ease-in-out infinite; }
    .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(91,138,245,0.22) 0%, transparent 70%); top: -10%; right: -8%; animation-delay: 0s; }
    .orb-2 { width: 350px; height: 350px; background: radial-gradient(circle, rgba(139,92,246,0.16) 0%, transparent 70%); bottom: 5%; left: 28%; animation-delay: -6s; }
    .orb-3 { width: 240px; height: 240px; background: radial-gradient(circle, rgba(91,138,245,0.1) 0%, transparent 70%); top: 50%; left: 8%; animation-delay: -12s; }
    @keyframes orbFloat {
      0%,100% { transform: translate(0,0) scale(1); }
      33%      { transform: translate(30px,-20px) scale(1.05); }
      66%      { transform: translate(-15px,25px) scale(0.97); }
    }

    /* Left panel */
    .side {
      display: none;
      width: 400px; min-height: 100vh; flex-shrink: 0;
      background: var(--panel2);
      border-right: 1px solid var(--border);
      position: relative; overflow: hidden;
      flex-direction: column; justify-content: space-between;
      padding: 2.8rem 2.6rem; z-index: 1;
    }
    @media(min-width: 960px) { .side { display: flex; } }

    .side-grid {
      position: absolute; inset: 0;
      background-image: linear-gradient(var(--border) 1px, transparent 1px), linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 44px 44px;
      mask-image: radial-gradient(ellipse at 35% 45%, black 15%, transparent 70%);
      animation: gridDrift 30s linear infinite;
    }
    @keyframes gridDrift { from { background-position: 0 0; } to { background-position: 44px 44px; } }
    .side-glow  { position: absolute; width: 360px; height: 360px; border-radius: 50%; background: radial-gradient(circle, rgba(91,138,245,0.12) 0%, transparent 70%); top: 5%; left: -100px; pointer-events: none; }
    .side-glow2 { position: absolute; width: 240px; height: 240px; border-radius: 50%; background: radial-gradient(circle, rgba(139,92,246,0.08) 0%, transparent 70%); bottom: 10%; right: -60px; pointer-events: none; }

    .side-top { position: relative; z-index: 1; }
    .wordmark { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.3rem; letter-spacing: -0.02em; display: flex; align-items: center; gap: 10px; }
    .wordmark img { width: 32px; height: 32px; object-fit: contain; border-radius: 8px; }
    .wordmark-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 14px var(--accent); }

    .side-hero { position: relative; z-index: 1; margin-top: 4rem; }
    .side-hero h2 { font-family: 'Outfit', sans-serif; font-size: 2.3rem; font-weight: 800; line-height: 1.25; letter-spacing: -0.03em; margin-bottom: 1rem; }
    .side-hero h2 em { font-style: normal; background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .side-hero p { color: var(--muted2); font-size: 0.875rem; line-height: 1.7; }

    .feature-list { position: relative; z-index: 1; margin-top: 2.8rem; display: flex; flex-direction: column; gap: 1.2rem; }
    .feature-item { display: flex; align-items: center; gap: 12px; font-size: 0.88rem; color: var(--muted2); }
    .feature-item .fi-icon { width: 32px; height: 32px; border-radius: 10px; background: rgba(91,138,245,0.08); border: 1px solid rgba(91,138,245,0.15); display: flex; align-items: center; justify-content: center; font-size: 14px; }

    .side-footer { position: relative; z-index: 1; font-size: 0.7rem; color: var(--muted); }

    /* Main */
    .main { flex: 1; display: flex; align-items: flex-start; justify-content: center; padding: 5rem 1.4rem 4rem; position: relative; z-index: 1; }

    .form-card { width: 100%; max-width: 440px; animation: riseIn 0.55s cubic-bezier(0.22,1,0.36,1) both; }
    @keyframes riseIn { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

    /* Lang & Theme Switcher */
    .lang-switcher {
      position: fixed; top: 1.4rem; right: 1.4rem;
      display: flex; align-items: center; gap: 3px;
      z-index: 100;
      background: rgba(14,17,23,0.88);
      backdrop-filter: blur(18px);
      padding: 4px; border-radius: 11px;
      border: 1px solid var(--border);
      box-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }
    .lang-btn {
      font-size: 0.6rem; font-weight: 800;
      font-family: 'Outfit', sans-serif;
      text-decoration: none;
      color: var(--muted2); letter-spacing: 0.06em;
      padding: 5px 9px; border-radius: 7px;
      border: none; background: transparent; cursor: pointer;
      transition: all 0.18s; position: relative;
      display: flex; align-items: center; justify-content: center;
    }
    .lang-btn:hover { color: var(--text); }
    .lang-btn.active { color: #fff !important; background: var(--accent); box-shadow: 0 2px 10px rgba(91,138,245,0.3); }
    .lang-tooltip {
      position: absolute; top: calc(100% + 8px); left: 50%;
      transform: translateX(-50%);
      background: var(--panel2); border: 1px solid var(--border-hi);
      border-radius: 6px; padding: 4px 8px;
      font-size: 0.62rem; color: var(--muted2); white-space: nowrap;
      pointer-events: none; opacity: 0; transition: opacity 0.15s; z-index: 200;
    }
    .lang-btn:hover .lang-tooltip { opacity: 1; }

    /* Form head */
    .form-head { margin-bottom: 2.2rem; }
    .avatar-ring {
      width: 56px; height: 56px; border-radius: 15px;
      background: linear-gradient(135deg, rgba(91,138,245,0.14), rgba(139,92,246,0.14));
      border: 1.5px solid rgba(91,138,245,0.28);
      display: flex; align-items: center; justify-content: center;
      font-size: 24px; margin-bottom: 1.3rem;
      box-shadow: 0 0 28px rgba(91,138,245,0.1), 0 0 0 6px rgba(91,138,245,0.04);
      animation: floatIcon 4s ease-in-out infinite;
    }
    @keyframes floatIcon { 0%,100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-5px) rotate(2deg); } }
    .form-head h1 { font-family: 'Outfit', sans-serif; font-size: 1.85rem; font-weight: 800; letter-spacing: -0.035em; line-height: 1.3; margin-bottom: 0.35rem; }
    .form-head p { font-size: 0.85rem; color: var(--muted2); font-weight: 400; line-height: 1.6; }

    /* Role tabs */
    .role-tabs { display: flex; gap: 5px; background: var(--theme-toggle-bg); border: 1px solid var(--border); border-radius: 11px; padding: 4px; margin-bottom: 1.8rem; }
    .role-tab { flex: 1; padding: 8px 12px; border-radius: 8px; border: none; background: transparent; color: var(--muted); cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 0.76rem; font-weight: 700; letter-spacing: 0.03em; transition: all 0.22s; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .role-tab.active-admin { background: rgba(91,138,245,0.12); color: var(--accent); box-shadow: 0 0 0 1px rgba(91,138,245,0.22); }
    .role-tab.active-super { background: rgba(240,180,41,0.1); color: var(--gold); box-shadow: 0 0 0 1px rgba(240,180,41,0.22); }
    .role-tab:hover:not(.active-admin):not(.active-super) { color: var(--muted2); background: rgba(255,255,255,0.03); }

    /* Fields */
    .field-row { display: grid; gap: 12px; grid-template-columns: 1fr 1fr; margin-bottom: 1.1rem; }
    @media (max-width: 520px) { .field-row { grid-template-columns: 1fr; } }
    .fg { margin-bottom: 1.1rem; }
    .fg-full { grid-column: 1 / -1; }
    .fg label { display: flex; align-items: center; justify-content: space-between; font-size: 0.69rem; font-weight: 600; color: var(--muted2); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 6px; }
    .input-wrap { position: relative; }
    .input-wrap .ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--muted); pointer-events: none; transition: color 0.2s; z-index: 2; }
    input[type="email"], input[type="password"], input[type="text"] {
      width: 100%; padding: 0.72rem 0.9rem 0.72rem 2.4rem;
      background: var(--inp); border: 1.5px solid var(--border); border-radius: 10px;
      color: var(--text); font-family: 'Inter', sans-serif; font-size: 0.875rem;
      outline: none; transition: border-color 0.2s, box-shadow 0.2s; -webkit-appearance: none;
    }
    input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(91,138,245,0.1); }
    .input-wrap:focus-within .ico { color: var(--accent); }
    input::placeholder { color: #22263060; }
    input.invalid { border-color: var(--error) !important; box-shadow: 0 0 0 3px rgba(245,101,101,0.09) !important; animation: shakeInput 0.3s ease; }
    @keyframes shakeInput { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-4px); } 75% { transform: translateX(4px); } }

    .input-wrap-gold input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(240,180,41,0.1); }
    .input-wrap-gold:focus-within .ico { color: var(--gold); }

    .toggle-pw { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer; display: flex; align-items: center; padding: 4px; transition: color 0.2s; border-radius: 5px; }
    .toggle-pw:hover { color: var(--text); }

    /* Strength */
    .pw-strength { margin-top: 6px; }
    .strength-bar { height: 3px; border-radius: 2px; background: var(--border); overflow: hidden; margin-bottom: 4px; }
    .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s ease, background 0.3s ease; width: 0%; }
    .strength-text { font-size: 0.65rem; color: var(--muted); }

    /* Terms */
    .terms-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 1.8rem; }
    .custom-cb { width: 17px; height: 17px; border: 1.5px solid var(--border-hi); border-radius: 5px; background: var(--inp); cursor: pointer; appearance: none; -webkit-appearance: none; position: relative; transition: all 0.18s; flex-shrink: 0; margin-top: 2px; }
    .custom-cb:checked { background: var(--accent); border-color: var(--accent); }
    .custom-cb:checked::after { content: ''; position: absolute; left: 4px; top: 1px; width: 5px; height: 9px; border: 2px solid #fff; border-top: none; border-left: none; transform: rotate(45deg); }
    .terms-label { font-size: 0.8rem; color: var(--muted2); line-height: 1.6; }
    .terms-label a { color: var(--accent); text-decoration: none; font-weight: 500; }

    /* Error list */
    .error-box { background: rgba(245,101,101,0.06); border: 1px solid rgba(245,101,101,0.18); border-radius: 10px; padding: 11px 14px; margin-bottom: 1.1rem; display: flex; align-items: flex-start; gap: 9px; animation: riseIn 0.3s ease; }
    .error-box p { font-size: 0.78rem; color: #f87171; line-height: 1.55; }

    /* Submit */
    .submit-btn {
      width: 100%; padding: 0.88rem;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border: none; border-radius: 10px;
      font-family: 'Outfit', sans-serif; font-size: 0.88rem; font-weight: 700;
      letter-spacing: 0.02em; color: #fff; cursor: pointer;
      position: relative; overflow: hidden;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 22px rgba(91,138,245,0.28);
    }
    .submit-btn.gold-mode { background: linear-gradient(135deg, #f0b429, #e67e22); box-shadow: 0 4px 22px rgba(240,180,41,0.32); color: #0a0a0a; }
    .submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
    .submit-btn:disabled { opacity: 0.42; cursor: not-allowed; }
    .submit-btn .sp { display: none; width: 17px; height: 17px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.55s linear infinite; position: absolute; left: 50%; top: 50%; margin: -8.5px 0 0 -8.5px; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .submit-btn.loading > span { visibility: hidden; }
    .submit-btn.loading .sp { display: block; }

    .divider { display: flex; align-items: center; gap: 10px; margin: 1.3rem 0; color: var(--muted); font-size: 0.7rem; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .form-footer { text-align: center; margin-top: 1.4rem; font-size: 0.8rem; color: var(--muted); }
    .form-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }

    /* Success Screen */
    .success-screen { display: none; flex-direction: column; align-items: center; text-align: center; }
    .success-screen.show { display: flex; animation: riseIn 0.5s both; }
    .succ-icon { width: 64px; height: 64px; border-radius: 50%; border: 2px solid var(--accent); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 1.5rem; background: rgba(91,138,245,0.05); }

    @media (max-width: 480px) {
      .main { padding-top: 5.5rem; }
    }
  </style>
</head>
<body>

<div class="bg-orbs">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
</div>

<!-- Left panel -->
<aside class="side">
  <div class="side-grid"></div>
  <div class="side-glow"></div>
  <div class="side-glow2"></div>
  <div class="side-top">
    <div class="wordmark">
        @if(!empty($admin_settings['store_logo']))
            @php
                 $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dnrblpkal');
                 $version   = config('cloudinary.asset_version');
                 $prefix    = config('cloudinary.upload_folder');
                 $logo_url  = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}" . (!empty($prefix) ? "/{$prefix}" : "") . "/{$admin_settings['store_logo']}";
            @endphp
            <img src="{{ $logo_url }}" alt="Logo">
        @else
            <div class="wordmark-dot"></div>
        @endif
        {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}
    </div>
    <div class="side-hero">
      <h2>Join the<br><em>exclusive</em><br>team.</h2>
      <p>Create your secure administrative account and start managing your marketplace today.</p>
    </div>
    <div class="feature-list">
      <div class="feature-item"><div class="fi-icon">🛡️</div><span>Enterprise-grade security</span></div>
      <div class="feature-item"><div class="fi-icon">⚡</div><span>Instant role-based access</span></div>
      <div class="feature-item"><div class="fi-icon">🌍</div><span>Multi-language support</span></div>
    </div>
  </div>
  <div class="side-footer">© {{ date('Y') }} {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}</div>
</aside>

<!-- Language & Theme Switcher -->
<div class="lang-switcher">
    <button class="lang-btn" id="themeToggle" style="margin-right: 4px;">
        <span class="dark-icon">🌙</span>
        <span class="light-icon" style="display:none">☀️</span>
        <span class="lang-tooltip">Toggle Theme</span>
    </button>
    <div style="width: 1px; height: 16px; background: var(--border); margin: 0 4px;"></div>
    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">
        EN<span class="lang-tooltip">English</span>
    </a>
    <a href="{{ route('lang.switch', 'km') }}" class="lang-btn {{ App::getLocale() == 'km' ? 'active' : '' }}">
        KM<span class="lang-tooltip">ខ្មែរ</span>
    </a>
</div>

<!-- Main form -->
<main class="main">
  <div class="form-card">

    <div class="success-screen" id="successScreen">
        <div class="succ-icon">✓</div>
        <h1 style="font-family: 'Outfit', sans-serif; font-weight:800; margin-bottom: 0.5rem;">Account Created!</h1>
        <p style="color: var(--muted2); font-size: 0.9rem; margin-bottom: 2rem;">Welcome to the team. Your admin access has been granted.</p>
        <a href="{{ route('admin.login') }}" class="submit-btn" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
            <span>Go to Login</span>
        </a>
    </div>

    <div id="formWrap">
        <div class="form-head">
          <div class="avatar-ring">✨</div>
          <h1>@lang('admin.create_account')</h1>
          <p>@lang('admin.join_us')</p>
        </div>

        <div class="role-tabs">
          <button type="button" class="role-tab active-admin" data-role="admin" id="tabAdmin">
            ⚙️ @lang('admin.admin')
          </button>
          <button type="button" class="role-tab" data-role="super" id="tabSuper">
            👑 @lang('admin.super_admin')
          </button>
        </div>

        @if ($errors->any())
          <div class="error-box">
            <p>{{ $errors->first() }}</p>
          </div>
        @endif

        <form action="{{ route('admin.register.post') }}" method="POST" id="registerForm" novalidate>
          @csrf
          <input type="hidden" name="role" id="roleInput" value="admin"/>

          <div class="field-row">
              <div class="fg">
                <label for="name">@lang('admin.full_name')</label>
                <div class="input-wrap">
                  <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                  </svg>
                  <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required/>
                </div>
              </div>

              <div class="fg">
                <label for="email">@lang('admin.email_address')</label>
                <div class="input-wrap">
                  <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                  </svg>
                  <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required/>
                </div>
              </div>
          </div>

          <div class="fg" id="superKeyRow" style="display: none;">
            <label for="super_key">👑 Super Admin Authorization Key</label>
            <div class="input-wrap input-wrap-gold">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
              </svg>
              <input type="password" id="super_key" name="super_admin_key" placeholder="Enter key for Super Admin roles"/>
            </div>
          </div>

          <div class="field-row">
              <div class="fg">
                <label for="password">@lang('admin.password')</label>
                <div class="input-wrap">
                  <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                  </svg>
                  <input type="password" id="password" name="password" placeholder="••••••••" required/>
                  <button type="button" class="toggle-pw" onclick="togglePass('password')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="15" height="15">
                      <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </button>
                </div>
                <div class="pw-strength" id="pwStrength">
                  <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                  <span class="strength-text" id="strengthText"></span>
                </div>
              </div>

              <div class="fg">
                <label for="confirm">@lang('admin.confirm_password')</label>
                <div class="input-wrap">
                  <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                  </svg>
                  <input type="password" id="confirm" name="password_confirmation" placeholder="••••••••" required/>
                </div>
              </div>
          </div>

          <div class="terms-row">
            <input type="checkbox" class="custom-cb" id="terms" name="terms" required/>
            <label for="terms" class="terms-label">
              I agree to the <a href="#">Terms of Service</a> & <a href="#">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="submit-btn" id="submitBtn">
            <span>@lang('admin.create_account')</span>
            <div class="sp"></div>
          </button>
        </form>

        <div class="divider">@lang('admin.or')</div>

        <div class="form-footer">
          @lang('admin.already_have_account')
          <a href="{{ route('admin.login') }}"> @lang('admin.sign_in')</a>
        </div>
    </div>

  </div>
</main>

<script>
function togglePass(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

(function(){
  /* ── Theme Toggle ── */
  const themeToggle = document.getElementById('themeToggle');
  const darkIcon = themeToggle.querySelector('.dark-icon');
  const lightIcon = themeToggle.querySelector('.light-icon');
  function updateThemeIcons(isLight) {
    darkIcon.style.display = isLight ? 'none' : 'block';
    lightIcon.style.display = isLight ? 'block' : 'none';
  }
  const currentTheme = localStorage.getItem('theme') || 'dark';
  if (currentTheme === 'light') { document.body.classList.add('light-mode'); updateThemeIcons(true); }
  themeToggle.addEventListener('click', () => {
    const isLight = document.body.classList.toggle('light-mode');
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
    updateThemeIcons(isLight);
  });

  /* ── Role Switching ── */
  const submitBtn = document.getElementById('submitBtn');
  const superKeyRow = document.getElementById('superKeyRow');
  function setRole(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('tabAdmin').className = 'role-tab';
    document.getElementById('tabSuper').className = 'role-tab';
    if (role === 'admin') {
      document.getElementById('tabAdmin').classList.add('active-admin');
      submitBtn.classList.remove('gold-mode');
      superKeyRow.style.display = 'none';
    } else {
      document.getElementById('tabSuper').classList.add('active-super');
      submitBtn.classList.add('gold-mode');
      superKeyRow.style.display = 'block';
    }
  }
  document.getElementById('tabAdmin').addEventListener('click', () => setRole('admin'));
  document.getElementById('tabSuper').addEventListener('click', () => setRole('super'));

  /* ── Password Strength ── */
  const pwEl = document.getElementById('password');
  pwEl.addEventListener('input', () => {
    const v = pwEl.value;
    let s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const fill = document.getElementById('strengthFill');
    const txt = document.getElementById('strengthText');
    const lvls = [
      { p:'25%', c:'#f43f5e', t:'Weak' },
      { p:'50%', c:'#f0b429', t:'Fair' },
      { p:'75%', c:'#48bb78', t:'Good' },
      { p:'100%',c:'#10b981', t:'Strong' }
    ];
    if (!v) { fill.style.width='0'; txt.textContent=''; return; }
    const res = lvls[Math.max(0, s-1)];
    fill.style.width = res.p;
    fill.style.background = res.c;
    txt.textContent = res.t;
    txt.style.color = res.c;
  });

  /* ── Form Submit ── */
  document.getElementById('registerForm').addEventListener('submit', function(e) {
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
  });

})();
</script>
</body>
</html>