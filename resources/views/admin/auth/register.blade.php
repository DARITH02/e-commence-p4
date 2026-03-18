<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account — {{ config('app.name') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Instrument+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #07090e;
      --panel:     #0c0f17;
      --panel2:    #10131c;
      --border:    rgba(255,255,255,0.065);
      --border-hi: rgba(255,255,255,0.13);
      --accent:    #5b8af5;
      --accent2:   #8b5cf6;
      --gold:      #f0b429;
      --gold-dim:  rgba(240,180,41,0.09);
      --text:      #eceae4;
      --muted:     #4e5668;
      --muted2:    #7e8ba0;
      --error:     #f56565;
      --success:   #48bb78;
      --inp:       #080a10;
      --radius:    12px;
    }

    html { font-size: 16px; }
    html, body { height: 100%; }

    body {
      min-height: 100vh;
      background: var(--bg);
      display: flex;
      font-family: 'Instrument Sans', sans-serif;
      font-size: 0.9rem;
      line-height: 1.5;
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── Left panel ─────────────────────────────── */
    .side {
      display: none;
      width: 400px;
      min-height: 100vh;
      flex-shrink: 0;
      background: var(--panel2);
      border-right: 1px solid var(--border);
      position: sticky;
      top: 0;
      height: 100vh;
      overflow: hidden;
      flex-direction: column;
      justify-content: space-between;
      padding: 2.75rem 2.5rem;
    }
    @media(min-width:900px){ .side { display: flex; } }

    .side-grid {
      position: absolute; inset: 0;
      background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 44px 44px;
      mask-image: radial-gradient(ellipse at 25% 35%, black 15%, transparent 75%);
      animation: gridMove 25s linear infinite;
    }
    @keyframes gridMove {
      from { background-position: 0 0; }
      to   { background-position: 44px 44px; }
    }

    .side-glow {
      position: absolute;
      width: 340px; height: 340px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.14) 0%, transparent 70%);
      top: 10%; left: -80px;
      pointer-events: none;
    }
    .side-glow2 {
      position: absolute;
      width: 220px; height: 220px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
      bottom: 18%; right: -50px;
      pointer-events: none;
    }

    .side-top { position: relative; z-index: 1; }

    .wordmark {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.25rem;
      letter-spacing: -0.025em;
      display: flex;
      align-items: center;
      gap: 9px;
      color: var(--text);
    }
    .wordmark-dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: var(--accent);
      box-shadow: 0 0 10px rgba(91,138,245,0.8);
      flex-shrink: 0;
    }

    .side-hero {
      position: relative;
      z-index: 1;
      margin-top: 3.5rem;
    }
    .side-hero h2 {
      font-family: 'Syne', sans-serif;
      font-size: 2.1rem;
      font-weight: 800;
      line-height: 1.18;
      letter-spacing: -0.035em;
      margin-bottom: 1rem;
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
      font-size: 0.875rem;
      line-height: 1.65;
      font-weight: 300;
    }

    .feature-list {
      position: relative;
      z-index: 1;
      margin-top: 2.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
    }
    .feature-item {
      display: flex;
      align-items: center;
      gap: 11px;
      font-size: 0.825rem;
      color: var(--muted2);
      line-height: 1.5;
    }
    .feature-item .fi-icon {
      width: 30px; height: 30px;
      border-radius: 8px;
      background: rgba(91,138,245,0.08);
      border: 1px solid rgba(91,138,245,0.15);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      font-size: 13px;
    }

    .side-footer {
      position: relative;
      z-index: 1;
      font-size: 0.72rem;
      color: var(--muted);
      line-height: 1.5;
    }

    /* ── Right panel ────────────────────────────── */
    .main {
      flex: 1;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 3rem 1.5rem 4rem;
      position: relative;
      overflow: hidden;
      min-height: 100vh;
    }

    .main::before {
      content: '';
      position: fixed;
      top: -20%; right: -15%;
      width: 50vw; height: 50vw;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(91,138,245,0.035) 0%, transparent 70%);
      pointer-events: none;
    }

    .form-card {
      width: 100%;
      max-width: 460px;
      animation: riseIn 0.5s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes riseIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .form-header {
      margin-bottom: 2rem;
    }
    .form-header .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--gold-dim);
      border: 1px solid rgba(240,180,41,0.22);
      border-radius: 100px;
      padding: 3px 11px 3px 7px;
      font-size: 0.7rem;
      font-weight: 600;
      color: var(--gold);
      letter-spacing: 0.07em;
      text-transform: uppercase;
      margin-bottom: 0.9rem;
      line-height: 1.5;
    }
    .badge-dot {
      width: 5px; height: 5px;
      border-radius: 50%;
      background: var(--gold);
      box-shadow: 0 0 7px var(--gold);
      animation: pulse 2.2s ease infinite;
      flex-shrink: 0;
    }
    @keyframes pulse {
      0%,100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.55; transform: scale(0.75); }
    }

    .form-header h1 {
      font-family: 'Syne', sans-serif;
      font-size: 1.85rem;
      font-weight: 800;
      letter-spacing: -0.035em;
      line-height: 1.2;
      margin-bottom: 0.35rem;
      color: var(--text);
    }
    .form-header p {
      font-size: 0.845rem;
      color: var(--muted2);
      font-weight: 300;
      line-height: 1.55;
    }

    /* ── Role selector ─────────────────────────── */
    .role-selector {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 9px;
      margin-bottom: 1.5rem;
    }
    .role-label {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 4px;
      padding: 13px 14px;
      border-radius: var(--radius);
      border: 1.5px solid var(--border);
      background: var(--panel);
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s, transform 0.15s;
      position: relative;
      user-select: none;
    }
    .role-label:hover {
      border-color: var(--border-hi);
      transform: translateY(-1px);
    }
    .role-label .rl-icon {
      font-size: 1.25rem;
      line-height: 1;
      margin-bottom: 2px;
    }
    .role-label .rl-name {
      font-family: 'Syne', sans-serif;
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.01em;
      line-height: 1.3;
      color: var(--text);
    }
    .role-label .rl-desc {
      font-size: 0.7rem;
      color: var(--muted);
      line-height: 1.45;
    }
    .role-label .rl-check {
      position: absolute;
      top: 9px; right: 9px;
      width: 17px; height: 17px;
      border-radius: 50%;
      border: 1.5px solid var(--border-hi);
      display: flex; align-items: center; justify-content: center;
      font-size: 8px;
      color: transparent;
      transition: all 0.18s;
    }

    .role-label.selected-admin {
      border-color: var(--accent) !important;
      background: rgba(91,138,245,0.055) !important;
    }
    .role-label.selected-admin .rl-check {
      background: var(--accent) !important;
      border-color: var(--accent) !important;
      color: #fff !important;
    }
    .role-label.selected-super {
      border-color: var(--gold) !important;
      background: rgba(240,180,41,0.055) !important;
    }
    .role-label.selected-super .rl-check {
      background: var(--gold) !important;
      border-color: var(--gold) !important;
      color: #000 !important;
    }

    /* ── Super admin key ───────────────────────── */
    .super-key-group {
      display: none;
      margin-bottom: 1rem;
      animation: riseIn 0.25s ease both;
    }
    .super-key-group.visible { display: block; }

    /* ── Form fields ───────────────────────────── */
    .field-row {
      display: grid;
      gap: 10px;
      margin-bottom: 1rem;
    }
    .field-row.two { grid-template-columns: 1fr 1fr; }

    .fg { position: relative; }

    .fg label {
      display: block;
      font-size: 0.695rem;
      font-weight: 600;
      color: var(--muted2);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-bottom: 5px;
      line-height: 1.4;
    }
    .input-wrap { position: relative; }

    .input-wrap .ico {
      position: absolute;
      left: 12px; top: 50%;
      transform: translateY(-50%);
      width: 14px; height: 14px;
      color: var(--muted);
      pointer-events: none;
      transition: color 0.2s;
      z-index: 2;
      flex-shrink: 0;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.67rem 0.8rem 0.67rem 2.35rem;
      background: var(--inp);
      border: 1.5px solid var(--border);
      border-radius: 9px;
      color: var(--text);
      font-family: 'Instrument Sans', sans-serif;
      font-size: 0.855rem;
      line-height: 1.5;
      outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
      -webkit-appearance: none;
    }
    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91,138,245,0.1);
    }
    .input-wrap:focus-within .ico { color: var(--accent); }
    input.inp-gold:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(240,180,41,0.09);
    }
    .input-wrap:focus-within .ico-gold { color: var(--gold); }
    input::placeholder { color: #1e232e; }
    input.invalid { border-color: var(--error) !important; box-shadow: 0 0 0 3px rgba(245,101,101,0.09) !important; }

    .toggle-pw {
      position: absolute;
      right: 10px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: var(--muted); cursor: pointer;
      display: flex; align-items: center;
      padding: 4px;
      transition: color 0.18s;
      line-height: 0;
    }
    .toggle-pw:hover { color: var(--muted2); }

    .err {
      font-size: 0.7rem;
      color: var(--error);
      margin-top: 4px;
      line-height: 1.45;
      display: none;
    }
    .err.show { display: block; }

    /* ── Password strength ─────────────────────── */
    .pw-meta { margin-top: 5px; }
    .bars { display: flex; gap: 3px; }
    .bars span {
      flex: 1; height: 2px;
      border-radius: 2px;
      background: var(--border);
      transition: background 0.25s;
    }
    .pw-label {
      font-size: 0.67rem;
      color: var(--muted);
      margin-top: 3px;
      line-height: 1.4;
      letter-spacing: 0.02em;
    }

    /* ── Terms ─────────────────────────────────── */
    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      margin: 1.25rem 0 1.5rem;
    }
    .custom-cb {
      width: 17px; height: 17px;
      min-width: 17px;
      border: 1.5px solid var(--border-hi);
      border-radius: 5px;
      background: var(--inp);
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      position: relative;
      transition: all 0.18s;
      margin-top: 1px;
      flex-shrink: 0;
    }
    .custom-cb:checked {
      background: var(--accent);
      border-color: var(--accent);
    }
    .custom-cb:checked::after {
      content: '';
      position: absolute;
      left: 4px; top: 1.5px;
      width: 5px; height: 8px;
      border: 1.75px solid #fff;
      border-top: none; border-left: none;
      transform: rotate(45deg);
    }
    .terms-text {
      font-size: 0.8rem;
      color: var(--muted2);
      line-height: 1.6;
    }
    .terms-text a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
    }
    .terms-text a:hover { text-decoration: underline; }

    /* ── Submit ────────────────────────────────── */
    .submit-btn {
      width: 100%;
      padding: 0.82rem;
      border: none;
      border-radius: 9px;
      font-family: 'Syne', sans-serif;
      font-size: 0.875rem;
      font-weight: 700;
      letter-spacing: 0.03em;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
      color: #fff;
      box-shadow: 0 3px 18px rgba(91,138,245,0.28);
      line-height: 1.4;
    }
    .submit-btn.gold-mode {
      background: linear-gradient(135deg, #f0b429 0%, #e67e22 100%);
      box-shadow: 0 3px 18px rgba(240,180,41,0.3);
      color: #0a0802;
    }
    .submit-btn:hover { opacity: 0.9; transform: translateY(-1px); }
    .submit-btn:active { transform: translateY(0); opacity: 1; }
    .submit-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
    .submit-btn .sp {
      display: none;
      width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,0.25);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.55s linear infinite;
      position: absolute; left: 50%; top: 50%;
      margin: -8px 0 0 -8px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .submit-btn.loading > span { visibility: hidden; }
    .submit-btn.loading .sp { display: block; }

    /* ── Footer link ───────────────────────────── */
    .foot-link {
      text-align: center;
      font-size: 0.8rem;
      color: var(--muted);
      margin-top: 1.25rem;
      line-height: 1.5;
    }
    .foot-link a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
    }
    .foot-link a:hover { text-decoration: underline; }

    /* ── Divider ───────────────────────────────── */
    .divider {
      display: flex; align-items: center; gap: 10px;
      margin: 1.25rem 0;
      color: var(--muted); font-size: 0.7rem;
      letter-spacing: 0.05em;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1;
      height: 1px; background: var(--border);
    }

    /* ── Success screen ────────────────────────── */
    .success-screen {
      display: none;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 2.5rem 1.5rem;
      animation: riseIn 0.4s ease both;
    }
    .success-screen.show { display: flex; }
    .succ-ring {
      width: 68px; height: 68px;
      border-radius: 50%;
      border: 1.5px solid var(--success);
      display: flex; align-items: center; justify-content: center;
      font-size: 28px;
      color: var(--success);
      box-shadow: 0 0 28px rgba(72,187,120,0.2);
      margin-bottom: 1.1rem;
      animation: popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes popIn {
      from { transform: scale(0.5); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }
    .success-screen h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.5rem;
      font-weight: 800;
      letter-spacing: -0.025em;
      line-height: 1.25;
      margin-bottom: 0.45rem;
    }
    .success-screen p {
      color: var(--muted2);
      font-size: 0.845rem;
      line-height: 1.6;
    }
    .success-screen .role-tag {
      margin-top: 1rem;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 13px;
      border-radius: 100px;
      font-size: 0.72rem;
      font-weight: 600;
      line-height: 1.4;
      letter-spacing: 0.02em;
    }
    .role-tag.admin { background: rgba(91,138,245,0.08); color: var(--accent); border: 1px solid rgba(91,138,245,0.18); }
    .role-tag.super { background: rgba(240,180,41,0.08); color: var(--gold); border: 1px solid rgba(240,180,41,0.22); }

    /* ── Responsive ────────────────────────────── */
    @media (max-width: 520px) {
      .field-row.two { grid-template-columns: 1fr; }
      .role-selector { gap: 8px; }
      .main { padding: 2rem 1rem 3rem; }
    }
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

      <!-- Role selector -->
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
                <svg id="eyeOn" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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

  // ── Handle server-side errors ─────────────────
  @if($errors->any())
    document.getElementById('successScreen').style.display = 'none';
  @endif

})();
</script>
</body>
</html>