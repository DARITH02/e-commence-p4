<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@lang('admin.login_title') — {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Instrument+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #080a0f;
      --panel:     #0e1117;
      --panel2:    #12151d;
      --border:    rgba(255,255,255,0.07);
      --border-hi: rgba(255,255,255,0.13);
      --accent:    #5b8af5;
      --accent2:   #8b5cf6;
      --gold:      #f0b429;
      --text:      #f0eee8;
      --muted:     #5a6070;
      --muted2:    #8891a4;
      --error:     #f56565;
      --inp:       #080a0f;
    }

    html, body { height: 100%; }

    body {
      font-family: 'Instrument Sans', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── Left decorative panel ───────────────── */
    .side {
      display: none;
      width: 420px;
      min-height: 100vh;
      flex-shrink: 0;
      background: var(--panel2);
      border-right: 1px solid var(--border);
      position: relative;
      overflow: hidden;
      flex-direction: column;
      justify-content: space-between;
      padding: 3rem 2.8rem;
    }
    @media(min-width: 900px) { .side { display: flex; } }

    .side-grid {
      position: absolute; inset: 0;
      background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 48px 48px;
      mask-image: radial-gradient(ellipse at 30% 50%, black 20%, transparent 75%);
      animation: gridDrift 24s linear infinite;
    }
    @keyframes gridDrift {
      from { background-position: 0 0; }
      to   { background-position: 48px 48px; }
    }

    .side-glow {
      position: absolute;
      width: 340px; height: 340px; border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.16) 0%, transparent 70%);
      top: 10%; left: -80px; pointer-events: none;
    }
    .side-glow2 {
      position: absolute;
      width: 220px; height: 220px; border-radius: 50%;
      background: radial-gradient(circle, rgba(139,92,246,0.10) 0%, transparent 70%);
      bottom: 15%; right: -50px; pointer-events: none;
    }

    .side-top { position: relative; z-index: 1; }

    .wordmark {
      font-family: 'Syne', sans-serif;
      font-weight: 800; font-size: 1.4rem;
      letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 10px;
    }
    .wordmark-dot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--accent);
      box-shadow: 0 0 12px var(--accent);
    }

    .side-hero { position: relative; z-index: 1; margin-top: 4.5rem; }
    .side-hero h2 {
      font-family: 'Syne', sans-serif;
      font-size: 2.5rem; font-weight: 800;
      line-height: 1.15; letter-spacing: -0.03em;
      margin-bottom: 1.2rem;
    }
    .side-hero h2 em {
      font-style: normal;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .side-hero p { color: var(--muted2); font-size: 0.9rem; line-height: 1.75; }

    /* Stat cards */
    .stat-grid {
      position: relative; z-index: 1;
      margin-top: 3rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    .stat-card {
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 16px;
      transition: border-color 0.2s;
    }
    .stat-card:hover { border-color: var(--border-hi); }
    .stat-val {
      font-family: 'Syne', sans-serif;
      font-size: 1.5rem; font-weight: 800;
      letter-spacing: -0.02em;
      background: linear-gradient(135deg, var(--text), var(--muted2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .stat-lbl {
      font-size: 0.72rem; color: var(--muted);
      margin-top: 4px; line-height: 1.4;
    }

    /* Recent logins */
    .recent-logins {
      position: relative; z-index: 1;
      margin-top: 2rem;
    }
    .recent-logins h3 {
      font-size: 0.72rem; font-weight: 600;
      color: var(--muted); letter-spacing: 0.08em;
      text-transform: uppercase; margin-bottom: 12px;
    }
    .login-avatar-row { display: flex; align-items: center; gap: -6px; }
    .la {
      width: 32px; height: 32px; border-radius: 50%;
      border: 2px solid var(--panel2);
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 700;
      margin-right: -8px;
      flex-shrink: 0;
    }
    .la-a { background: linear-gradient(135deg,#5b8af5,#8b5cf6); }
    .la-b { background: linear-gradient(135deg,#f0b429,#e67e22); }
    .la-c { background: linear-gradient(135deg,#48bb78,#38a169); }
    .la-d { background: linear-gradient(135deg,#f56565,#c53030); }
    .la-more {
      background: rgba(255,255,255,0.06);
      border: 2px solid var(--border);
      color: var(--muted2);
      font-size: 10px;
      margin-left: 12px;
    }
    .recent-text { font-size: 0.78rem; color: var(--muted2); margin-left: 16px; }

    .side-footer {
      position: relative; z-index: 1;
      font-size: 0.72rem; color: var(--muted);
    }

    /* ── Main / Form ─────────────────────────── */
    .main {
      flex: 1;
      display: flex; align-items: center; justify-content: center;
      padding: 2rem 1.2rem;
      position: relative;
    }
    .main::before {
      content: '';
      position: fixed; top: -20%; right: -15%;
      width: 50vw; height: 50vw; border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.04) 0%, transparent 70%);
      pointer-events: none;
    }

    .form-card {
      width: 100%; max-width: 420px;
      animation: riseIn 0.5s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes riseIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .form-head { margin-bottom: 2.4rem; }

    .avatar-ring {
      width: 60px; height: 60px; border-radius: 16px;
      background: linear-gradient(135deg, rgba(91,138,245,0.15), rgba(139,92,246,0.15));
      border: 1.5px solid rgba(91,138,245,0.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 26px; margin-bottom: 1.4rem;
      box-shadow: 0 0 30px rgba(91,138,245,0.12);
      animation: floatIcon 4s ease-in-out infinite;
    }
    @keyframes floatIcon {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-4px); }
    }

    .form-head h1 {
      font-family: 'Syne', sans-serif;
      font-size: 2rem; font-weight: 800;
      letter-spacing: -0.035em; line-height: 1.1;
      margin-bottom: 0.4rem;
    }
    .form-head p { font-size: 0.875rem; color: var(--muted2); font-weight: 300; }

    /* Role indicator tabs */
    .role-tabs {
      display: flex; gap: 6px;
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 4px;
      margin-bottom: 2rem;
    }
    .role-tab {
      flex: 1; padding: 8px 12px;
      border-radius: 7px; border: none;
      background: transparent;
      color: var(--muted); cursor: pointer;
      font-family: 'Syne', sans-serif;
      font-size: 0.78rem; font-weight: 700;
      letter-spacing: 0.03em;
      transition: all 0.2s;
      display: flex; align-items: center;
      justify-content: center; gap: 6px;
    }
    .role-tab.active-admin {
      background: rgba(91,138,245,0.12);
      color: var(--accent);
      box-shadow: 0 0 0 1px rgba(91,138,245,0.25);
    }
    .role-tab.active-super {
      background: rgba(240,180,41,0.1);
      color: var(--gold);
      box-shadow: 0 0 0 1px rgba(240,180,41,0.25);
    }
    .role-tab:hover:not(.active-admin):not(.active-super) { color: var(--muted2); }

    /* Fields */
    .fg { margin-bottom: 1.1rem; }
    .fg label {
      display: flex; align-items: center;
      justify-content: space-between;
      font-size: 0.72rem; font-weight: 600;
      color: var(--muted2); letter-spacing: 0.07em;
      text-transform: uppercase; margin-bottom: 7px;
    }
    .fg label a {
      color: var(--accent); text-decoration: none;
      font-size: 0.72rem; text-transform: none;
      letter-spacing: 0; font-weight: 500;
    }
    .fg label a:hover { text-decoration: underline; }

    .input-wrap { position: relative; }
    .input-wrap .ico {
      position: absolute; left: 13px; top: 50%;
      transform: translateY(-50%);
      width: 15px; height: 15px;
      color: var(--muted); pointer-events: none;
      transition: color 0.2s; z-index: 2;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 0.75rem 0.9rem 0.75rem 2.5rem;
      background: var(--inp);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      color: var(--text);
      font-family: 'Instrument Sans', sans-serif;
      font-size: 0.88rem; outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      -webkit-appearance: none;
    }
    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91,138,245,0.12);
    }
    .input-wrap:focus-within .ico { color: var(--accent); }
    input::placeholder { color: #2a2f3a; }
    input.invalid { border-color: var(--error) !important; box-shadow: 0 0 0 3px rgba(245,101,101,0.1) !important; }

    /* Toggle password */
    .toggle-pw {
      position: absolute; right: 11px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: var(--muted); cursor: pointer;
      display: flex; align-items: center; padding: 4px;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--text); }

    /* Remember me */
    .remember-row {
      display: flex; align-items: center;
      justify-content: space-between;
      margin-bottom: 1.6rem;
    }
    .remember-left { display: flex; align-items: center; gap: 9px; }
    .custom-cb {
      width: 17px; height: 17px;
      border: 1.5px solid var(--border-hi);
      border-radius: 5px; background: var(--inp);
      cursor: pointer; appearance: none;
      -webkit-appearance: none; position: relative;
      transition: all 0.2s; flex-shrink: 0;
    }
    .custom-cb:checked { background: var(--accent); border-color: var(--accent); }
    .custom-cb:checked::after {
      content: '';
      position: absolute; left: 4px; top: 1px;
      width: 5px; height: 9px;
      border: 2px solid #fff;
      border-top: none; border-left: none;
      transform: rotate(45deg);
    }
    .remember-label { font-size: 0.82rem; color: var(--muted2); cursor: pointer; }
    .forgot-link { font-size: 0.82rem; color: var(--accent); text-decoration: none; }
    .forgot-link:hover { text-decoration: underline; }

    /* Error box */
    .error-box {
      background: rgba(245,101,101,0.07);
      border: 1px solid rgba(245,101,101,0.2);
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 1.2rem;
      display: flex; align-items: flex-start; gap: 10px;
    }
    .error-box .err-icon { font-size: 14px; flex-shrink: 0; margin-top: 1px; }
    .error-box p { font-size: 0.8rem; color: #f87171; line-height: 1.5; }

    /* Submit */
    .submit-btn {
      width: 100%; padding: 0.9rem;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border: none; border-radius: 10px;
      font-family: 'Syne', sans-serif;
      font-size: 0.9rem; font-weight: 700;
      letter-spacing: 0.02em; color: #fff; cursor: pointer;
      position: relative; overflow: hidden;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px rgba(91,138,245,0.3);
    }
    .submit-btn.gold-mode {
      background: linear-gradient(135deg, #f0b429, #e67e22);
      box-shadow: 0 4px 20px rgba(240,180,41,0.35);
      color: #0a0a0a;
    }
    .submit-btn:hover { opacity: 0.9; transform: translateY(-1px); }
    .submit-btn:active { transform: translateY(0); }
    .submit-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .submit-btn .sp {
      display: none;
      width: 17px; height: 17px;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      position: absolute; left: 50%; top: 50%;
      margin: -8.5px 0 0 -8.5px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .submit-btn.loading > span { visibility: hidden; }
    .submit-btn.loading .sp { display: block; }

    /* Divider */
    .divider {
      display: flex; align-items: center; gap: 10px;
      margin: 1.4rem 0; color: var(--muted); font-size: 0.72rem;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: var(--border);
    }

    /* Footer */
    .form-footer {
      text-align: center; margin-top: 1.5rem;
      font-size: 0.82rem; color: var(--muted);
    }
    .form-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .form-footer a:hover { text-decoration: underline; }

    .version-tag {
      display: flex; align-items: center; justify-content: center;
      gap: 8px; margin-top: 2rem;
      font-size: 0.7rem; color: var(--muted);
    }
    .version-tag .vdot {
      width: 4px; height: 4px; border-radius: 50%;
      background: var(--muted);
    }

    /* ── Language Switcher ────────────────────── */
    .lang-switcher {
      position: absolute;
      top: 2rem;
      right: 2rem;
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 50;
      background: rgba(255,255,255,0.03);
      backdrop-filter: blur(10px);
      padding: 4px;
      border-radius: 10px;
      border: 1px solid var(--border);
    }
    .lang-switcher a {
      font-size: 0.65rem;
      font-weight: 800;
      text-decoration: none;
      color: var(--muted2);
      letter-spacing: 0.08em;
      padding: 6px 12px;
      border-radius: 7px;
      transition: all 0.2s;
    }
    .lang-switcher a.active {
      color: #fff;
      background: var(--accent);
      box-shadow: 0 2px 10px rgba(91,138,245,0.25);
    }
  </style>
</head>
<body>

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
            $folder    = config('cloudinary.upload_folder');
            $logo_url  = "https://res.cloudinary.com/{$cloudName}/image/upload/{$version}" . (!empty($folder) ? "/{$folder}" : "") . "/{$admin_settings['store_logo']}";
        @endphp
        <img src="{{ $logo_url }}" alt="Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 8px;">
      @else
        <div class="wordmark-dot"></div>
      @endif
      {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}
    </div>

    <div class="side-hero">
      <h2>Your admin<br><em>command</em><br>center.</h2>
      <p>Everything you need to manage your platform — orders, users, content — all in one place.</p>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-val">2.4k</div>
        <div class="stat-lbl">Active users today</div>
      </div>
      <div class="stat-card">
        <div class="stat-val">98.9%</div>
        <div class="stat-lbl">Uptime this month</div>
      </div>
      <div class="stat-card">
        <div class="stat-val">142</div>
        <div class="stat-lbl">Orders pending</div>
      </div>
      <div class="stat-card">
        <div class="stat-val">$48k</div>
        <div class="stat-lbl">Revenue this week</div>
      </div>
    </div>

    <div class="recent-logins">
      <h3>Recently Active</h3>
      <div style="display:flex;align-items:center;">
        <div class="login-avatar-row">
          <div class="la la-a">S</div>
          <div class="la la-b">J</div>
          <div class="la la-c">M</div>
          <div class="la la-d">R</div>
          <div class="la la-more">+9</div>
        </div>
        <span class="recent-text">13 admins online</span>
      </div>
    </div>
  </div>

  <div class="side-footer">© {{ date('Y') }} {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}. E-Commerce Pro v4.0.2</div>
</aside>

<!-- Main form -->
<main class="main">
  <!-- Language Switcher -->
  <div class="lang-switcher">
    <a href="{{ route('lang.switch', 'en') }}" class="{{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
    <a href="{{ route('lang.switch', 'km') }}" class="{{ App::getLocale() == 'km' ? 'active' : '' }}">KM</a>
  </div>

  <div class="form-card">

    <!-- Icon + heading -->
    <div class="form-head">
      <div class="avatar-ring">🔑</div>
      <h1>@lang('admin.welcome_back')</h1>
      <p>@lang('admin.enter_credentials')</p>
    </div>

    <!-- Role selector tabs -->
    <div class="role-tabs" id="roleTabs">
      <button type="button" class="role-tab active-admin" data-role="admin" id="tabAdmin">
        ⚙️ Admin
      </button>
      <button type="button" class="role-tab" data-role="super" id="tabSuper">
        👑 Super Admin
      </button>
    </div>

    <!-- Error -->
    @if ($errors->any())
      <div class="error-box">
        <span class="err-icon">⚠️</span>
        <p>{{ $errors->first() }}</p>
      </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST" id="loginForm" novalidate>
      @csrf
      <input type="hidden" name="role" id="roleInput" value="admin"/>

      <!-- Email -->
      <div class="fg">
        <label for="email">@lang('admin.email_address')</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
          </svg>
          <input type="email" id="email" name="email" value="{{ old('email') }}"
            placeholder="{{ __('admin.email_address') }}" autocomplete="email" required/>
        </div>
      </div>

      <!-- Password -->
      <div class="fg">
        <label for="password">
          @lang('admin.password')
          <a href="{{ route('admin.forgot-password') ?? '#' }}">@lang('admin.forgot_password')</a>
        </label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
          </svg>
          <input type="password" id="password" name="password"
            placeholder="{{ __('admin.password') }}" autocomplete="current-password" required/>
          <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="15" height="15">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Remember me -->
      <div class="remember-row">
        <div class="remember-left">
          <input type="checkbox" class="custom-cb" id="remember" name="remember"/>
          <label for="remember" class="remember-label">@lang('admin.remember_me')</label>
        </div>
      </div>

      <button type="submit" class="submit-btn" id="submitBtn">
        <span id="btnLabel">@lang('admin.sign_in')</span>
        <div class="sp"></div>
      </button>
    </form>

    <div class="divider">or</div>

    <div class="form-footer">
      @lang('admin.no_account') <a href="{{ route('admin.register') }}">@lang('admin.sign_up')</a>
    </div>

    <div class="version-tag">
      <span>E-Commerce Pro</span>
      <span class="vdot"></span>
      <span>v4.0.2</span>
      <span class="vdot"></span>
      <span>© {{ date('Y') }} {{ $admin_settings['store_name'] ?? config('app.name', 'Arcadia') }}</span>
    </div>

  </div>
</main>

<script>
(function(){
  // ── Role tabs ──────────────────────────────────
  const tabAdmin  = document.getElementById('tabAdmin');
  const tabSuper  = document.getElementById('tabSuper');
  const roleInput = document.getElementById('roleInput');
  const submitBtn = document.getElementById('submitBtn');

  function setRole(role) {
    roleInput.value = role;
    tabAdmin.className = 'role-tab';
    tabSuper.className = 'role-tab';
    if (role === 'admin') {
      tabAdmin.classList.add('active-admin');
      submitBtn.classList.remove('gold-mode');
    } else {
      tabSuper.classList.add('active-super');
      submitBtn.classList.add('gold-mode');
    }
  }

  tabAdmin.addEventListener('click', () => setRole('admin'));
  tabSuper.addEventListener('click', () => setRole('super'));

  // ── Toggle password ────────────────────────────
  const pw = document.getElementById('password');
  document.getElementById('togglePw').addEventListener('click', () => {
    pw.type = pw.type === 'password' ? 'text' : 'password';
  });

  // ── Submit spinner ─────────────────────────────
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email');
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    const pwOk = pw.value.length >= 1;

    if (!emailOk) { email.classList.add('invalid'); e.preventDefault(); return; }
    if (!pwOk)    { pw.classList.add('invalid');    e.preventDefault(); return; }

    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
  });

  // Clear invalid on input
  ['email','password'].forEach(id => {
    document.getElementById(id).addEventListener('input', function(){
      this.classList.remove('invalid');
    });
  });

})();
</script>
</body>
</html>