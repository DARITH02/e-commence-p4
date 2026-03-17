<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account — {{ config('app.name') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Instrument+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #080a0f;
      --panel:     #0e1117;
      --panel2:    #12151d;
      --border:    rgba(255,255,255,0.07);
      --border-hi: rgba(255,255,255,0.14);
      --accent:    #5b8af5;
      --accent2:   #8b5cf6;
      --gold:      #f0b429;
      --gold-dim:  rgba(240,180,41,0.1);
      --text:      #f0eee8;
      --muted:     #5a6070;
      --muted2:    #8891a4;
      --error:     #f56565;
      --success:   #48bb78;
      --inp:       #080a0f;
      --radius:    14px;
      --shadow:    0 32px 100px rgba(0,0,0,0.7);
    }

    html, body { height: 100%; }

    body {
      min-height: 100vh;
      background: var(--bg);
      display: flex;
      font-family: 'Instrument Sans', sans-serif;
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── Left panel ─────────────────────────────── */
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
    @media(min-width:900px){ .side { display: flex; } }

    /* animated grid lines */
    .side-grid {
      position: absolute; inset: 0;
      background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 48px 48px;
      mask-image: radial-gradient(ellipse at 30% 40%, black 20%, transparent 80%);
      animation: gridMove 20s linear infinite;
    }
    @keyframes gridMove {
      from { background-position: 0 0; }
      to   { background-position: 48px 48px; }
    }

    .side-glow {
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.18) 0%, transparent 70%);
      top: 15%; left: -60px;
      pointer-events: none;
    }
    .side-glow2 {
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%);
      bottom: 20%; right: -40px;
      pointer-events: none;
    }

    .side-top { position: relative; z-index: 1; }

    .wordmark {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.4rem;
      letter-spacing: -0.02em;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .wordmark-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--accent);
      box-shadow: 0 0 12px var(--accent);
    }

    .side-hero {
      position: relative;
      z-index: 1;
      margin-top: 4rem;
    }
    .side-hero h2 {
      font-family: 'Syne', sans-serif;
      font-size: 2.4rem;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -0.03em;
      margin-bottom: 1.2rem;
    }
    .side-hero h2 em {
      font-style: normal;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .side-hero p {
      color: var(--muted2);
      font-size: 0.92rem;
      line-height: 1.7;
    }

    .feature-list {
      position: relative;
      z-index: 1;
      margin-top: 3rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 0.85rem;
      color: var(--muted2);
    }
    .feature-item .fi-icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: rgba(91,138,245,0.1);
      border: 1px solid rgba(91,138,245,0.2);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      font-size: 14px;
    }

    .side-footer {
      position: relative;
      z-index: 1;
      font-size: 0.75rem;
      color: var(--muted);
    }

    /* ── Right panel ────────────────────────────── */
    .main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.2rem;
      position: relative;
      overflow: hidden;
    }

    .main::before {
      content: '';
      position: fixed;
      top: -20%; right: -15%;
      width: 50vw; height: 50vw;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.04) 0%, transparent 70%);
      pointer-events: none;
    }

    .form-card {
      width: 100%;
      max-width: 480px;
      animation: riseIn 0.55s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes riseIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .form-header {
      margin-bottom: 2.4rem;
    }
    .form-header .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--gold-dim);
      border: 1px solid rgba(240,180,41,0.25);
      border-radius: 100px;
      padding: 4px 12px 4px 8px;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--gold);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 1rem;
    }
    .badge-dot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--gold);
      box-shadow: 0 0 8px var(--gold);
      animation: pulse 2s ease infinite;
    }
    @keyframes pulse {
      0%,100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(0.8); }
    }

    .form-header h1 {
      font-family: 'Syne', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -0.035em;
      line-height: 1.15;
      margin-bottom: 0.4rem;
    }
    .form-header p {
      font-size: 0.875rem;
      color: var(--muted2);
      font-weight: 300;
    }

    /* Role selector */
    .role-selector {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 1.6rem;
    }
    .role-option { display: none; }
    .role-label {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
      padding: 14px 16px;
      border-radius: var(--radius);
      border: 1.5px solid var(--border);
      background: var(--panel);
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s, transform 0.15s;
      position: relative;
      overflow: hidden;
    }
    .role-label:hover {
      border-color: var(--border-hi);
      transform: translateY(-1px);
    }
    .role-label .rl-icon {
      font-size: 1.4rem;
      line-height: 1;
    }
    .role-label .rl-name {
      font-family: 'Syne', sans-serif;
      font-size: 0.82rem;
      font-weight: 700;
      letter-spacing: 0.01em;
    }
    .role-label .rl-desc {
      font-size: 0.72rem;
      color: var(--muted);
      line-height: 1.4;
    }
    .role-label .rl-check {
      position: absolute;
      top: 10px; right: 10px;
      width: 18px; height: 18px;
      border-radius: 50%;
      border: 1.5px solid var(--border-hi);
      display: flex; align-items: center; justify-content: center;
      font-size: 9px;
      transition: all 0.2s;
    }

    /* Admin option */
    #role_admin:checked ~ .role-selector .role-label[for="role_admin"],
    .role-option:checked + .role-label {
      border-color: var(--accent);
      background: rgba(91,138,245,0.06);
    }
    #role_admin:checked ~ .role-selector .role-label[for="role_admin"] .rl-check {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff;
    }
    /* Super Admin option */
    #role_super:checked ~ .role-selector .role-label[for="role_super"] {
      border-color: var(--gold);
      background: rgba(240,180,41,0.06);
    }
    #role_super:checked ~ .role-selector .role-label[for="role_super"] .rl-check {
      background: var(--gold);
      border-color: var(--gold);
      color: #000;
    }

    /* Use JS for role selection instead of pure CSS */
    .role-label.selected-admin {
      border-color: var(--accent) !important;
      background: rgba(91,138,245,0.06) !important;
    }
    .role-label.selected-admin .rl-check {
      background: var(--accent) !important;
      border-color: var(--accent) !important;
      color: #fff !important;
    }
    .role-label.selected-super {
      border-color: var(--gold) !important;
      background: rgba(240,180,41,0.06) !important;
    }
    .role-label.selected-super .rl-check {
      background: var(--gold) !important;
      border-color: var(--gold) !important;
      color: #000 !important;
    }

    /* Super admin key field */
    .super-key-group {
      display: none;
      margin-bottom: 1.1rem;
      animation: riseIn 0.3s ease both;
    }
    .super-key-group.visible { display: block; }

    /* Form fields */
    .field-row {
      display: grid;
      gap: 12px;
      margin-bottom: 1.1rem;
    }
    .field-row.two { grid-template-columns: 1fr 1fr; }

    .fg { position: relative; }

    .fg label {
      display: block;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--muted2);
      letter-spacing: 0.07em;
      text-transform: uppercase;
      margin-bottom: 6px;
    }
    .input-wrap { position: relative; }

    .input-wrap .ico {
      position: absolute;
      left: 13px; top: 50%;
      transform: translateY(-50%);
      width: 15px; height: 15px;
      color: var(--muted);
      pointer-events: none;
      transition: color 0.2s;
      z-index: 2;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.72rem 0.85rem 0.72rem 2.5rem;
      background: var(--inp);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      color: var(--text);
      font-family: 'Instrument Sans', sans-serif;
      font-size: 0.88rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      -webkit-appearance: none;
    }
    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91,138,245,0.12);
    }
    .input-wrap:focus-within .ico { color: var(--accent); }
    input.inp-gold:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(240,180,41,0.1);
    }
    .input-wrap:focus-within .ico-gold { color: var(--gold); }
    input::placeholder { color: #2a2f3a; }
    input.invalid { border-color: var(--error) !important; }

    .toggle-pw {
      position: absolute;
      right: 11px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: var(--muted); cursor: pointer;
      display: flex; align-items: center;
      padding: 4px;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--text); }

    .err {
      font-size: 0.72rem;
      color: var(--error);
      margin-top: 5px;
      display: none;
    }
    .err.show { display: block; }

    /* Password strength */
    .pw-meta { margin-top: 6px; }
    .bars { display: flex; gap: 3px; }
    .bars span {
      flex: 1; height: 2.5px;
      border-radius: 2px;
      background: var(--border);
      transition: background 0.3s;
    }
    .pw-label {
      font-size: 0.7rem;
      color: var(--muted);
      margin-top: 4px;
    }

    /* Terms */
    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin: 1.4rem 0 1.8rem;
    }
    .custom-cb {
      width: 18px; height: 18px;
      min-width: 18px;
      border: 1.5px solid var(--border-hi);
      border-radius: 5px;
      background: var(--inp);
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      position: relative;
      transition: all 0.2s;
      margin-top: 1px;
    }
    .custom-cb:checked {
      background: var(--accent);
      border-color: var(--accent);
    }
    .custom-cb:checked::after {
      content: '';
      position: absolute;
      left: 4px; top: 1px;
      width: 5px; height: 9px;
      border: 2px solid #fff;
      border-top: none; border-left: none;
      transform: rotate(45deg);
    }
    .terms-text {
      font-size: 0.81rem;
      color: var(--muted2);
      line-height: 1.55;
    }
    .terms-text a { color: var(--accent); text-decoration: none; }
    .terms-text a:hover { text-decoration: underline; }

    /* Submit */
    .submit-btn {
      width: 100%;
      padding: 0.9rem;
      border: none;
      border-radius: 10px;
      font-family: 'Syne', sans-serif;
      font-size: 0.9rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
      color: #fff;
      box-shadow: 0 4px 20px rgba(91,138,245,0.3);
    }
    .submit-btn.gold-mode {
      background: linear-gradient(135deg, #f0b429 0%, #e67e22 100%);
      box-shadow: 0 4px 20px rgba(240,180,41,0.35);
      color: #0a0a0a;
    }
    .submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
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

    .foot-link {
      text-align: center;
      font-size: 0.83rem;
      color: var(--muted);
      margin-top: 1.5rem;
    }
    .foot-link a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .foot-link a:hover { text-decoration: underline; }

    /* Divider */
    .divider {
      display: flex; align-items: center; gap: 10px;
      margin: 1.5rem 0;
      color: var(--muted); font-size: 0.72rem;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1;
      height: 1px; background: var(--border);
    }

    /* Success */
    .success-screen {
      display: none;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 2rem;
      animation: riseIn 0.4s ease both;
    }
    .success-screen.show { display: flex; }
    .succ-ring {
      width: 72px; height: 72px;
      border-radius: 50%;
      border: 2px solid var(--success);
      display: flex; align-items: center; justify-content: center;
      font-size: 30px;
      color: var(--success);
      box-shadow: 0 0 30px rgba(72,187,120,0.25);
      margin-bottom: 1.2rem;
      animation: popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes popIn {
      from { transform: scale(0.5); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }
    .success-screen h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 0.5rem;
    }
    .success-screen p { color: var(--muted2); font-size: 0.875rem; }
    .success-screen .role-tag {
      margin-top: 1rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 100px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .role-tag.admin { background: rgba(91,138,245,0.1); color: var(--accent); border: 1px solid rgba(91,138,245,0.2); }
    .role-tag.super { background: rgba(240,180,41,0.1); color: var(--gold); border: 1px solid rgba(240,180,41,0.25); }
  </style>
</head>
<body>

<!-- Left decorative panel -->
<aside class="side">
  <div class="side-grid"></div>
  <div class="side-glow"></div>
  <div class="side-glow2"></div>

  <div class="side-top">
    <div class="wordmark">
      <div class="wordmark-dot"></div>
      {{ config('app.name', 'Arcadia') }}
    </div>
    <div class="side-hero">
      <h2>Command your<br><em>platform</em><br>with confidence.</h2>
      <p>Secure, role-based access control for teams that mean business.</p>
    </div>
    <div class="feature-list">
      <div class="feature-item">
        <div class="fi-icon">🔐</div>
        <span>Role-based permissions — Admin &amp; Super Admin</span>
      </div>
      <div class="feature-item">
        <div class="fi-icon">📊</div>
        <span>Full audit trail on every action</span>
      </div>
      <div class="feature-item">
        <div class="fi-icon">⚡</div>
        <span>Instant access, zero setup friction</span>
      </div>
    </div>
  </div>

  <div class="side-footer">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
</aside>

<!-- Main form -->
<main class="main">
  <div class="form-card">

    <!-- Success screen -->
    <div class="success-screen" id="successScreen">
      <div class="succ-ring">✓</div>
      <h2>@lang('admin.welcome_msg')</h2>
      <p>@lang('admin.account_created')</p>
      <div class="role-tag admin" id="successRoleTag">⚙ Admin Access Granted</div>
    </div>

    <!-- Form -->
    <div id="formWrap">
      <div class="form-header">
        <div class="badge"><span class="badge-dot"></span> @lang('admin.secure_registration')</div>
        <h1>@lang('admin.create_account')</h1>
        <p>@lang('admin.join_us')</p>
      </div>

      <!-- Role selector (visual, not real radio) -->
      <div class="role-selector" id="roleSelector">
        <div class="role-label selected-admin" data-role="admin" id="roleAdminCard">
          <div class="rl-check">✓</div>
          <span class="rl-icon">⚙️</span>
          <span class="rl-name">Admin</span>
          <span class="rl-desc">Manage users &amp; content</span>
        </div>
        <div class="role-label" data-role="super" id="roleSuperCard">
          <div class="rl-check"></div>
          <span class="rl-icon">👑</span>
          <span class="rl-name">Super Admin</span>
          <span class="rl-desc">Full system control</span>
        </div>
      </div>

      <form id="registerForm" action="{{ route('admin.register.post') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="role" id="roleInput" value="admin"/>

        <!-- Name + Email row -->
        <div class="field-row two">
          <div class="fg">
            <label for="name">@lang('admin.full_name')</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
              <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Full name" autocomplete="name"/>
            </div>
            <div class="err @error('name') show @enderror" id="nameErr">@error('name'){{ $message }}@else Min. 2 characters.@enderror</div>
          </div>
          <div class="fg">
            <label for="email">@lang('admin.email_address')</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
              <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@domain.com" autocomplete="email"/>
            </div>
            <div class="err @error('email') show @enderror" id="emailErr">@error('email'){{ $message }}@else Valid email required.@enderror</div>
          </div>
        </div>

        <!-- Super Admin key -->
        <div class="super-key-group" id="superKeyGroup">
          <div class="fg">
            <label for="super_key">👑 Super Admin Access Key</label>
            <div class="input-wrap">
              <svg class="ico ico-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
              <input type="password" id="super_key" name="super_admin_key" class="inp-gold" placeholder="Enter authorization key" autocomplete="off"/>
            </div>
            <div class="err" id="superKeyErr">Invalid authorization key.</div>
          </div>
        </div>

        <!-- Password row -->
        <div class="field-row two">
          <div class="fg">
            <label for="password">@lang('admin.password')</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
              <input type="password" id="password" name="password" placeholder="Min. 8 chars" autocomplete="new-password"/>
              <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password">
                <svg id="eyeOn" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </button>
            </div>
            <div class="pw-meta">
              <div class="bars"><span id="b1"></span><span id="b2"></span><span id="b3"></span><span id="b4"></span></div>
              <div class="pw-label" id="pwLabel"></div>
            </div>
            <div class="err @error('password') show @enderror" id="passwordErr">@error('password'){{ $message }}@else Min. 8 characters.@enderror</div>
          </div>
          <div class="fg">
            <label for="confirm">@lang('admin.confirm_password')</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
              <input type="password" id="confirm" name="password_confirmation" placeholder="Repeat password" autocomplete="new-password"/>
            </div>
            <div class="err" id="confirmErr">Passwords don't match.</div>
          </div>
        </div>

        <!-- Terms -->
        <div class="terms-row">
          <input type="checkbox" class="custom-cb" id="terms" name="terms" required/>
          <label for="terms" class="terms-text">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="submit-btn" id="submitBtn">
          <span id="btnLabel">@lang('admin.create_account')</span>
          <div class="sp"></div>
        </button>
      </form>

      <div class="divider">or</div>
      <p class="foot-link">@lang('admin.already_have_account') <a href="{{ route('admin.login') }}">@lang('admin.sign_in_link')</a></p>
    </div>

  </div>
</main>

<script>
(function(){
  // ── Role selector ──────────────────────────────
  const adminCard  = document.getElementById('roleAdminCard');
  const superCard  = document.getElementById('roleSuperCard');
  const roleInput  = document.getElementById('roleInput');
  const superGroup = document.getElementById('superKeyGroup');
  const submitBtn  = document.getElementById('submitBtn');
  const btnLabel   = document.getElementById('btnLabel');
  const successTag = document.getElementById('successRoleTag');

  function selectRole(role) {
    roleInput.value = role;

    adminCard.classList.remove('selected-admin');
    superCard.classList.remove('selected-super');
    adminCard.querySelector('.rl-check').textContent = '';
    superCard.querySelector('.rl-check').textContent = '';

    if (role === 'admin') {
      adminCard.classList.add('selected-admin');
      adminCard.querySelector('.rl-check').textContent = '✓';
      superGroup.classList.remove('visible');
      submitBtn.classList.remove('gold-mode');
    } else {
      superCard.classList.add('selected-super');
      superCard.querySelector('.rl-check').textContent = '✓';
      superGroup.classList.add('visible');
      submitBtn.classList.add('gold-mode');
    }
  }

  adminCard.addEventListener('click', () => selectRole('admin'));
  superCard.addEventListener('click', () => selectRole('super'));

  // ── Password strength ──────────────────────────
  const pw   = document.getElementById('password');
  const bars = [1,2,3,4].map(i => document.getElementById('b'+i));
  const plbl = document.getElementById('pwLabel');

  pw.addEventListener('input', () => {
    const v = pw.value;
    let s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const cols = ['#f56565','#ed8936','#ecc94b','#48bb78'];
    const lbls = ['Weak','Fair','Good','Strong'];
    bars.forEach((b,i) => b.style.background = i < s ? cols[s-1] : 'var(--border)');
    plbl.textContent = v.length ? lbls[s-1] : '';
    plbl.style.color = v.length ? cols[s-1] : 'var(--muted)';
  });

  // ── Toggle password visibility ─────────────────
  const confirm = document.getElementById('confirm');
  document.getElementById('togglePw').addEventListener('click', function() {
    const t = pw.type === 'password' ? 'text' : 'password';
    pw.type = confirm.type = t;
  });

  // ── Validation helpers ─────────────────────────
  function showErr(id, on) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('show', on);
  }
  function markInvalid(el, on) {
    el.classList.toggle('invalid', on);
  }

  // ── Form submit ────────────────────────────────
  document.getElementById('registerForm').addEventListener('submit', function(e) {
    let ok = true;
    const name  = document.getElementById('name');
    const email = document.getElementById('email');
    const terms = document.getElementById('terms');
    const role  = roleInput.value;
    const skey  = document.getElementById('super_key');


    const nameOk = name.value.trim().length >= 2;
    showErr('nameErr', !nameOk); markInvalid(name, !nameOk);
    if (!nameOk) ok = false;

    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    showErr('emailErr', !emailOk); markInvalid(email, !emailOk);
    if (!emailOk) ok = false;

    if (role === 'super') {
      const skOk = skey.value.trim().length >= 4;
      showErr('superKeyErr', !skOk); markInvalid(skey, !skOk);
      if (!skOk) ok = false;
    }

    const pwOk = pw.value.length >= 8;
    showErr('passwordErr', !pwOk); markInvalid(pw, !pwOk);
    if (!pwOk) ok = false;

    const confOk = confirm.value === pw.value && confirm.value.length > 0;
    showErr('confirmErr', !confOk); markInvalid(confirm, !confOk);
    if (!confOk) ok = false;

    if (!terms.checked) ok = false;

    if (!ok) { e.preventDefault(); return; }

    // Show spinner
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
  });

  // ── Clear errors on input ──────────────────────
  ['name','email','password','confirm'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', () => {
      el.classList.remove('invalid');
      const errId = { confirm:'confirmErr' }[id] || id+'Err';
      showErr(errId, false);
    });
  });

  // ── Handle server-side errors (show form) ─────
  @if($errors->any())
    document.getElementById('successScreen').style.display = 'none';
  @endif

})();
</script>
</body>
</html>