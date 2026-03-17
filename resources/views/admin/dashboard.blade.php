@extends('layouts.admin')

@push('styles')
<style>
/* ── Layout ── */
.dash {
    display: flex;
    flex-direction: column;
    gap: 32px;
    animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both;
}
.dash-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 296px;
    gap: 32px;
}
@media (max-width: 1200px) {
    .dash-main-grid { grid-template-columns: 1fr; }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ── KPI Row ── */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}
@media (max-width: 1023px) { .kpi-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 639px) { .kpi-row { grid-template-columns: 1fr; } }

.kpi {
    background: var(--ink-2);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 24px;
    position: relative;
    overflow: hidden;
}
.kpi-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.kpi-ico { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
.kpi-ico svg { width: 22px; height: 22px; }
.kpi-ico.c-blue { background: rgba(79,114,245,0.1); color: #4f72f5; }
.kpi-ico.c-amber { background: rgba(232,160,0,0.1); color: #e8a000; }
.kpi-ico.c-green { background: rgba(31,186,114,0.1); color: #1fba72; }
.kpi-ico.c-violet { background: rgba(139,92,246,0.1); color: #8b5cf6; }

.kpi-chip {
    padding: 4px 8px; border-radius: 8px; font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700;
    display: flex; align-items: center; gap: 4px; border: 1px solid transparent;
}
.kpi-chip.chip-up { background: rgba(31,186,114,0.1); color: #1fba72; border-color: rgba(31,186,114,0.1); }
.kpi-chip.chip-down { background: rgba(240,82,82,0.1); color: #f05252; border-color: rgba(240,82,82,0.1); }
.kpi-chip svg { width: 10px; height: 10px; }

.kpi-body { position: relative; z-index: 1; }
.kpi-label { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: var(--muted); margin-bottom: 6px; }
.kpi-val { font-size: 26px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; line-height: 1; margin-bottom: 6px; }
.kpi-sub { font-size: 12px; font-weight: 500; color: var(--muted-2); }
.kpi-spark-wrap { position: absolute; right: 0; bottom: -10px; width: 100px; height: 40px; opacity: 0.8; pointer-events: none; }

/* ── Card common ── */
.card { background: var(--ink-2); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; }
.card-head { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.card-title { font-size: 16px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
.card-sub { font-size: 12px; color: var(--muted-2); margin-top: 2px; }
.card-link { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700; color: var(--accent); text-transform: uppercase; text-decoration: none; padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border); transition: 0.2s; }
.card-link:hover { background: var(--accent-glow); color: var(--accent); border-color: rgba(79,114,245,0.2); }

/* ── Chart panel ── */
.chart-card { padding: 0; }
.chart-wrap { padding: 24px; height: 320px; position: relative; }
.chart-canvas-wrap { position: absolute; inset: 24px; }
.chart-legend { display: flex; gap: 20px; font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 600; color: var(--muted); }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-swatch { width: 12px; height: 3px; border-radius: 2px; }
.legend-dashed { border-top: 2px dashed rgba(100,110,140,0.5); height: 0; border-radius: 0; }
.live-badge { background: var(--amber-bg); color: var(--amber); font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 6px; border: 1px solid rgba(245,158,11,0.2); text-transform: uppercase; }

/* ── Orders table ── */
.table-scroll { overflow-x: auto; scrollbar-width: thin; }
table { width: 100%; border-collapse: collapse; white-space: nowrap; }
th { padding: 14px 24px; background: rgba(0,0,0,0.1); border-bottom: 1px solid var(--border); text-align: left; font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; }
td { padding: 16px 24px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(255,255,255,0.015); }

.oid { font-family: 'DM Mono', monospace; font-weight: 600; color: var(--text); }
.cust-row { display: flex; align-items: center; gap: 12px; }
.cust-av { width: 34px; height: 34px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-2); }
.cust-nm { font-weight: 700; color: var(--text); font-size: 13px; line-height: 1.2; }
.cust-em { font-size: 11px; color: var(--muted-2); margin-top: 1px; }

.s-pill { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid transparent; }
.s-pending { background: var(--amber-bg); color: var(--amber); border-color: rgba(245,158,11,0.1); }
.s-processing { background: var(--accent-bg); color: var(--accent); border-color: rgba(79,110,247,0.1); }
.s-shipped { background: var(--violet-bg); color: var(--violet); border-color: rgba(139,92,246,0.1); }
.s-completed { background: var(--green-bg); color: var(--green); border-color: rgba(34,201,122,0.1); }
.s-cancelled { background: var(--red-bg); color: var(--red); border-color: rgba(240,82,82,0.1); }

.order-ts { font-weight: 600; font-size: 12px; color: var(--text); }
.order-ts2 { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--muted); margin-top: 2px; }
.order-amt { font-family: 'DM Mono', monospace; font-weight: 700; color: var(--text); font-size: 13px; display: block; text-align: right; }

/* ── Sidebar widgets ── */
.qa-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 24px; }
.qa-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
    background: var(--ink); border: 1px solid var(--border); border-radius: 16px; padding: 16px 12px;
    text-decoration: none; color: var(--muted-2); transition: 0.2s; font-size: 12px; font-weight: 600; text-align: center;
}
.qa-btn:hover { background: var(--ink-3); color: var(--text); border-color: var(--accent); }
.qa-btn svg { width: 18px; height: 18px; color: var(--accent); }

.stock-alert { margin: 24px; padding: 14px 16px; border-radius: 14px; background: var(--red-bg); display: flex; align-items: center; gap: 14px; border: 1px solid rgba(240,82,82,0.1); }
.alert-pulse { width: 8px; height: 8px; border-radius: 50%; background: var(--red); box-shadow: 0 0 10px var(--red); animation: pulse 1.5s infinite; }
.alert-label { flex: 1; font-size: 12px; font-weight: 700; color: var(--red); }
.alert-count { font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 800; color: var(--red); opacity: 0.8; }
@keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }

.cat-wrap { padding: 24px; display: flex; flex-direction: column; gap: 18px; }
.cat-row2 { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 6px; }
.cat-nm2 { font-size: 12px; font-weight: 700; color: var(--text); }
.cat-pct2 { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--muted); }
.bar-bg { height: 4px; border-radius: 2px; background: var(--ink); overflow: hidden; }
.bar-fill { height: 100%; border-radius: 2px; background: var(--accent); }

.prod-list { padding: 0 12px 12px; }
.prod-item { display: flex; align-items: center; gap: 14px; padding: 12px; border-radius: 12px; transition: 0.2s; cursor: default; }
.prod-item:hover { background: rgba(255,255,255,0.02); }
.prod-rank { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 800; color: var(--muted); width: 16px; }
.prod-ico { width: 34px; height: 34px; background: var(--ink); border: 1px solid var(--border); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); }
.prod-ico svg { width: 16px; height: 16px; }
.prod-nm { font-size: 12px; font-weight: 700; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.prod-cnt { font-family: 'DM Mono', monospace; font-size: 9px; color: var(--muted-2); margin-top: 1px; }

.activity { padding: 0 24px 24px; position: relative; }
.activity::before { content: ''; position: absolute; left: 35px; top: 0; bottom: 40px; width: 1px; background: var(--border); }
.act-item { display: flex; gap: 20px; position: relative; padding-bottom: 24px; }
.act-item:last-child { padding-bottom: 0; }
.act-marker { width: 22px; height: 22px; border-radius: 50%; background: var(--ink-2); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; z-index: 1; flex-shrink: 0; }
.act-marker svg { width: 10px; height: 10px; color: var(--accent); }
.act-user { font-size: 13px; font-weight: 700; color: var(--text); }
.act-desc { font-size: 12px; color: var(--muted-2); margin: 3px 0 5px; line-height: 1.4; }
.act-time { font-family: 'DM Mono', monospace; font-size: 9px; color: var(--muted); text-transform: uppercase; }

@media (max-width: 680px) {
    thead th:nth-child(4), tbody td:nth-child(4) { display: none; }
    .kpi-spark { display: none; }
}
</style>
@endpush

@section('content')
<div class="dash">
    
    <div class="dash-greeting">
        <h2 class="text-2xl font-bold text-text">@lang('admin.good_morning'), {{ Auth::user()->name }} 👋</h2>
        <p class="text-sm text-muted mt-1 flex items-center gap-2">
            <span class="live-dot"></span>
            @lang('admin.systems_operational') &nbsp;·&nbsp; @lang('admin.updated_just_now')
        </p>
    </div>

    <!-- ── KPI Row ── -->
    <div class="kpi-row">
        <!-- Revenue -->
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['revenue_change'] ?? '12.5' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.total_revenue')</div>
                <div class="kpi-val">${{ number_format($totalRevenue, 0) }}</div>
                <div class="kpi-sub">@lang('admin.vs_previous_period')</div>
            </div>
            <div class="kpi-spark-wrap">
                <canvas class="kpi-spark" id="spark0" aria-hidden="true"></canvas>
            </div>
        </div>

        <!-- Orders -->
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['orders_change'] ?? '8.2' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.total_orders')</div>
                <div class="kpi-val">{{ number_format($ordersCount) }}</div>
                <div class="kpi-sub">@lang('admin.orders_placed')</div>
            </div>
            <div class="kpi-spark-wrap">
                <canvas class="kpi-spark" id="spark1" aria-hidden="true"></canvas>
            </div>
        </div>

        <!-- Customers -->
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-up">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['customers_change'] ?? '5.1' }}%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.customers')</div>
                <div class="kpi-val">{{ number_format($customersCount) }}</div>
                <div class="kpi-sub">@lang('admin.registered_accounts')</div>
            </div>
            <div class="kpi-spark-wrap">
                <canvas class="kpi-spark" id="spark2" aria-hidden="true"></canvas>
            </div>
        </div>

        <!-- Conversion -->
        <div class="kpi">
            <div class="kpi-head">
                <div class="kpi-ico c-violet">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="kpi-chip chip-down">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    0.3%
                </span>
            </div>
            <div class="kpi-body">
                <div class="kpi-label">@lang('admin.conversion_rate')</div>
                <div class="kpi-val">3.2%</div>
                <div class="kpi-sub">@lang('admin.visitors_to_buyers')</div>
            </div>
            <div class="kpi-spark-wrap">
                <canvas class="kpi-spark" id="spark3" aria-hidden="true"></canvas>
            </div>
        </div>
    </div>

    <div class="dash-main-grid">
        <div class="flex flex-col gap-8">
            <!-- Revenue Chart -->
            <div class="card chart-card">
                <div class="card-head">
                    <div>
                        <div class="card-title">
                            @lang('admin.revenue_over_time')
                            <span class="live-badge">@lang('admin.live')</span>
                        </div>
                        <div class="card-sub">@lang('admin.monthly_gross_revenue')</div>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-swatch" style="background:var(--accent)"></span>
                            @lang('admin.this_year')
                        </div>
                        <div class="legend-item">
                            <span class="legend-swatch legend-dashed"></span>
                            @lang('admin.last_year')
                        </div>
                    </div>
                </div>
                <div class="chart-wrap">
                    <div class="chart-canvas-wrap">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card orders-card">
                <div class="card-head">
                    <div>
                        <div class="card-title">@lang('admin.recent_orders')</div>
                        <div class="card-sub">@lang('admin.last') {{ count($latestOrders) }} @lang('admin.transactions')</div>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="card-link">@lang('admin.view_all') →</a>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>@lang('admin.order_number')</th>
                                <th>@lang('admin.customer')</th>
                                <th>@lang('admin.status')</th>
                                <th>@lang('admin.date')</th>
                                <th style="text-align:right">@lang('admin.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestOrders as $order)
                            <tr>
                                <td><span class="oid">#{{ $order->order_number }}</span></td>
                                <td>
                                    <div class="cust-row">
                                        <img class="cust-av"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'G') }}&background=4f6ef7&color=fff&bold=true&size=60"
                                             alt="">
                                        <div style="min-width:0">
                                            <div class="cust-nm">{{ $order->user->name ?? __('admin.guest') }}</div>
                                            <div class="cust-em">{{ $order->user->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $smap = ['pending'=>'s-pending','processing'=>'s-processing','shipped'=>'s-shipped','completed'=>'s-completed','cancelled'=>'s-cancelled'];
                                        $sc   = $smap[$order->status] ?? 's-pending';
                                    @endphp
                                    <span class="s-pill {{ $sc }}">@lang('admin.status_' . $order->status)</span>
                                </td>
                                <td>
                                    <div class="order-ts">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="order-ts2">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td><span class="order-amt">${{ number_format($order->total_amount, 2) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <aside class="dash-sidebar flex flex-col gap-8">
            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">@lang('admin.quick_actions')</div>
                </div>
                <div class="qa-grid">
                    <a href="{{ route('admin.products') }}" class="qa-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        @lang('admin.add_product')
                    </a>
                    <a href="{{ route('admin.orders') }}" class="qa-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        @lang('admin.nav_orders')
                    </a>
                    <a href="{{ route('admin.customers') }}" class="qa-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        @lang('admin.nav_customers')
                    </a>
                    <a href="{{ route('admin.categories') }}" class="qa-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        @lang('admin.nav_categories')
                    </a>
                </div>
            </div>

            {{-- Stock Alerts --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">@lang('admin.stock_alerts')</div>
                </div>
                <div class="stock-alert">
                    <span class="alert-pulse"></span>
                    <span class="alert-label">@lang('admin.low_stock_products')</span>
                    <span class="alert-count">5 @lang('admin.items')</span>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('admin.products') }}" class="card-link w-full text-center block">
                        @lang('admin.view_all_products')
                    </a>
                </div>
            </div>

            {{-- Top Categories --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">@lang('admin.top_categories')</div>
                </div>
                <div class="cat-wrap">
                    @php $totalProducts = \App\Models\Product::count(); @endphp
                    @foreach($trendingCategories as $cat)
                        @php $pct = $totalProducts > 0 ? ($cat->products_count / $totalProducts) * 100 : 0; @endphp
                        <div>
                            <div class="cat-row2">
                                <span class="cat-nm2">{{ $cat->name }}</span>
                                <span class="cat-pct2">{{ round($pct) }}%</span>
                            </div>
                            <div class="bar-bg">
                                <div class="bar-fill" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            @if(isset($recentActivity) && $recentActivity->count())
            <div class="card">
                <div class="card-head">
                    <div class="card-title">@lang('admin.activity')</div>
                </div>
                <div class="activity mt-6">
                    @foreach($recentActivity as $act)
                    <div class="act-item">
                        <div class="act-marker">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div style="min-width:0">
                            <div class="act-user">{{ $act->user->name }}</div>
                            <div class="act-desc">{{ $act->description }}</div>
                            <div class="act-time">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ── Theme management (local dashboard override for charts) ── */
document.addEventListener('themechange', () => {
    rebuildMainChart();
    buildSparklines();
});

function tc() {
    const l = document.documentElement.classList.contains('light');
    return {
        blue:     l ? '#3b55d9' : '#4f6ef7',
        blueDim:  l ? 'rgba(59,85,217,0.08)' : 'rgba(79,110,247,0.1)',
        blueFade: l ? 'rgba(59,85,217,0)'    : 'rgba(79,110,247,0)',
        prevLine: l ? 'rgba(0,0,0,0.2)'      : 'rgba(255,255,255,0.15)',
        prevFill: l ? 'rgba(0,0,0,0.02)'     : 'rgba(255,255,255,0.02)',
        grid:     l ? 'rgba(0,0,0,0.04)'     : 'rgba(255,255,255,0.04)',
        tick:     l ? '#9ca3af'              : '#5a6076',
        ttBg:     l ? '#ffffff'              : '#181b23',
        ttTitle:  l ? '#111827'              : '#e8eaf0',
        ttBody:   l ? '#4b5563'              : '#a0a8be',
        ttBorder: l ? 'rgba(0,0,0,0.1)'      : 'rgba(255,255,255,0.1)',
    };
}

/* ── Sparklines ── */
function buildSparklines() {
    const sparkData = [
        [3,5,4,7,6,8,9,8,11,10,12,13],
        [5,4,6,5,7,8,6,9,8,10,9,11],
        [2,3,4,3,5,4,6,5,7,8,7,9],
        [4,3,5,4,3,5,4,3,4,3,4,3],
    ];
    const sparkColors = ['#4f6ef7', '#f59e0b', '#22c97a', '#8b5cf6'];

    sparkData.forEach((data, i) => {
        const canvas = document.getElementById('spark' + i);
        if (!canvas) return;
        const existing = Chart.getChart(canvas);
        if (existing) existing.destroy();

        const ctx  = canvas.getContext('2d');
        const clr  = sparkColors[i];
        const grad = ctx.createLinearGradient(0, 0, 0, 36);
        grad.addColorStop(0, clr + '33');
        grad.addColorStop(1, clr + '00');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(() => ''),
                datasets: [{
                    data,
                    borderColor: clr,
                    borderWidth: 1.5,
                    pointRadius: 0,
                    backgroundColor: grad,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false } }
            }
        });
    });
}

/* ── Main Chart ── */
let mainChart;
function rebuildMainChart() {
    if (mainChart) mainChart.destroy();
    const canvas = document.getElementById('mainChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const c = tc();
    
    const g1 = ctx.createLinearGradient(0, 0, 0, 260);
    g1.addColorStop(0, c.blueDim);
    g1.addColorStop(1, c.blueFade);

    const labels = {!! json_encode($chartData['labels'] ?? []) !!};
    const current = {!! json_encode($chartData['current'] ?? []) !!};
    const previous = {!! json_encode($chartData['previous'] ?? []) !!};

    mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: "@lang('admin.this_year')",
                    data: current,
                    borderColor: c.blue,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    backgroundColor: g1,
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: "@lang('admin.last_year')",
                    data: previous,
                    borderColor: c.prevLine,
                    borderWidth: 1.5,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: c.ttBg,
                    titleColor: c.ttTitle,
                    bodyColor: c.ttBody,
                    borderColor: c.ttBorder,
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 12,
                }
            },
            scales: {
                y: { grid: { color: c.grid, drawBorder: false }, ticks: { color: c.tick, callback: v => '$' + (v/1000) + 'K' } },
                x: { grid: { display: false }, ticks: { color: c.tick } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    buildSparklines();
    rebuildMainChart();
});
</script>
@endpush