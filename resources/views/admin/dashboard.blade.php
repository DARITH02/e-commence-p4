@extends('layouts.admin')

@section('title', __('admin.dashboard'))
@section('page_title', __('admin.intelligence_core'))

@push('styles')
<style>
    :root {
        --glass: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.07);
        --accent-glow: 0 0 20px rgba(79, 110, 247, 0.2);
    }

    /* Force full width by overriding the layout's .content-inner constraint */
    .content-inner { max-width: none !important; padding: 0; }

    .dash-container {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Main Column ── */
    .main-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }

    /* ── Top Summary ── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .kpi-card {
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kpi-card:hover {
        border-color: var(--accent);
        box-shadow: var(--accent-glow);
        transform: translateY(-4px);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(79, 110, 247, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        color: var(--accent);
    }

    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.02em;
        line-height: 1;
        margin-bottom: 8px;
    }

    .kpi-label {
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--muted);
        margin-bottom: 12px;
    }

    .kpi-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .trend-up { color: var(--green); }
    .trend-down { color: var(--red); }

    /* ── Charts & Analytics ── */
    .analytics-row {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 24px;
    }

    .card-shell {
        background: var(--ink-2);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 28px;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title .dot {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--accent);
    }

    /* ── Activity & Side Column ── */
    .side-col { display: flex; flex-direction: column; gap: 24px; }

    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .activity-item {
        display: flex;
        gap: 14px;
        padding: 12px;
        border-radius: 12px;
        background: var(--ink-3);
        border: 1px solid var(--border);
        transition: border-color 0.2s;
    }

    .activity-item:hover { border-color: var(--border-2); }

    .activity-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .activity-info { min-width: 0; }
    .activity-user { font-size: 13px; font-weight: 600; color: var(--text); }
    .activity-action { font-size: 12px; color: var(--muted-2); margin-top: 2px; }
    .activity-time { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--muted); margin-top: 4px; }

    /* ── Table ── */
    .table-container {
        overflow-x: auto;
        margin: 0 -28px;
        padding: 0 28px;
    }

    table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    thead th {
        padding: 12px 16px;
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        text-transform: uppercase;
        color: var(--muted);
        text-align: left;
    }

    tbody tr {
        background: var(--ink-3);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    tbody tr:hover {
        transform: scale(1.005);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    tbody td {
        padding: 16px;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    tbody td:first-child { border-left: 1px solid var(--border); border-radius: 12px 0 0 12px; }
    tbody td:last-child { border-right: 1px solid var(--border); border-radius: 0 12px 12px 0; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending    { background: rgba(245, 158, 11, 0.1); color: var(--amber); }
    .status-processing { background: rgba(79, 110, 247, 0.1); color: var(--accent); }
    .status-completed  { background: rgba(34, 201, 122, 0.1); color: var(--green); }
    .status-cancelled  { background: rgba(240, 82, 82, 0.1);  color: var(--red); }

    /* ── Responsive ── */
    @media (max-width: 1280px) {
        .dash-container { grid-template-columns: 1fr; }
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .summary-grid { grid-template-columns: 1fr; }
        .analytics-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="dash-container">
    
    <div class="main-col">
        
        <!-- ── KPI Summary ── -->
        <div class="summary-grid">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="kpi-label">@lang('admin.valuation_index')</div>
                <div class="kpi-value">${{ number_format($totalRevenue, 0) }}</div>
                <div class="kpi-trend trend-up">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['revenue_change'] }}% @lang('admin.vs_cycle')
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div class="kpi-label">@lang('admin.total_throughput')</div>
                <div class="kpi-value">{{ number_format($ordersCount) }}</div>
                <div class="kpi-trend trend-up">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['orders_change'] }}% @lang('admin.flow_state')
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="kpi-label">@lang('admin.total_entities')</div>
                <div class="kpi-value">{{ number_format($customersCount) }}</div>
                <div class="kpi-trend trend-down">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    {{ abs($stats['customers_change']) }}% @lang('admin.base_layer')
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="kpi-label">@lang('admin.efficiency_ratio')</div>
                <div class="kpi-value">3.2%</div>
                <div class="kpi-trend trend-up">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['conversion_change'] }}% @lang('admin.kernel_task')
                </div>
            </div>
        </div>

        <!-- ── Revenue Analytics ── -->
        <div class="analytics-row">
            <div class="card-shell">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> @lang('admin.revenue_trajectory')</div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-accent"></span>
                        <span class="text-[10px] uppercase font-bold text-muted">@lang('admin.net_logic')</span>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="card-shell">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> @lang('admin.top_categories')</div>
                </div>
                <div class="space-y-6">
                    @foreach($trendingCategories as $cat)
                        @php $pct = \App\Models\Product::count() > 0 ? ($cat->products_count / \App\Models\Product::count()) * 100 : 0; @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                <span class="text-text-2">{{ $cat->name }}</span>
                                <span class="text-accent">{{ round($pct) }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-ink-3 rounded-full overflow-hidden border border-border">
                                <div class="h-full bg-accent rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.categories') }}" class="mt-8 block w-full py-3 text-center border border-border rounded-xl text-[10px] uppercase font-black tracking-widest text-muted hover:text-text hover:border-accent transition-all">
                    @lang('admin.access_archive')
                </a>
            </div>
        </div>

        <!-- ── Recent Orders ── -->
        <div class="card-shell">
            <div class="card-header">
                <div class="card-title"><span class="dot"></span> @lang('admin.audit_interface')</div>
                <a href="{{ route('admin.orders') }}" class="text-[10px] font-black uppercase tracking-widest text-accent hover:underline">@lang('admin.view_all') →</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>@lang('admin.identity')</th>
                            <th>@lang('admin.flow_source')</th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.timestamp')</th>
                            <th style="text-align: right">@lang('admin.valuation')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestOrders as $order)
                        <tr>
                            <td><span class="font-mono text-xs font-bold text-text">#{{ $order->order_number }}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'G') }}&background=4f6ef7&color=fff&bold=true" class="w-8 h-8 rounded-lg">
                                    <div class="min-width-0">
                                        <div class="text-sm font-bold text-text truncate">{{ $order->user->name ?? 'Guest' }}</div>
                                        <div class="text-[10px] text-muted truncate">{{ $order->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-pill status-{{ $order->status }}">
                                    @lang('admin.status_' . $order->status)
                                </span>
                            </td>
                            <td>
                                <div class="text-xs font-medium text-text-2">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] font-mono text-muted">{{ $order->created_at->format('H:i:s') }}</div>
                            </td>
                            <td style="text-align: right">
                                <span class="text-sm font-black text-text">${{ number_format($order->total_amount, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Sidebar ── -->
    <div class="side-col">
        
        <!-- Activity -->
        <div class="card-shell">
            <div class="card-header">
                <div class="card-title"><span class="dot" style="background: var(--green); box-shadow: 0 0 10px var(--green);"></span> @lang('admin.operational_stream')</div>
            </div>
            <div class="activity-feed">
                @forelse($recentActivity as $act)
                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($act->user->name) }}&background=222635&color=4f6ef7" class="activity-avatar">
                        <div class="activity-info">
                            <div class="activity-user">{{ $act->user->name }}</div>
                            <div class="activity-action">{{ $act->description }}</div>
                            <div class="activity-time">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-[10px] uppercase font-bold text-muted">@lang('admin.zero_modules')</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="card-shell" style="border-color: var(--red-bg);">
            <div class="card-header">
                <div class="card-title"><span class="dot" style="background: var(--red); box-shadow: 0 0 10px var(--red);"></span> @lang('admin.risk_detection')</div>
            </div>
            <div class="p-4 rounded-2xl bg-red-500/5 border border-red-500/10 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                    <span class="text-xs font-bold text-red-500 uppercase tracking-widest">@lang('admin.critical_level')</span>
                </div>
                <div class="mt-2 text-xs text-red-500/80 leading-relaxed">
                    5 inventory nodes report critical depletion. System synchronization recommended.
                </div>
            </div>
            <a href="{{ route('admin.products') }}" class="block w-full py-3 text-center bg-ink-3 rounded-xl text-[10px] uppercase font-black tracking-widest text-text hover:bg-red-500 hover:text-white transition-all">
                @lang('admin.decrypt_diagnostics')
            </a>
        </div>

        <!-- Top Products -->
        <div class="card-shell">
            <div class="card-header">
                <div class="card-title"><span class="dot" style="background: var(--amber); box-shadow: 0 0 10px var(--amber);"></span> Top Entities</div>
            </div>
            <div class="space-y-4">
                @foreach($topProducts as $prod)
                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-ink-3 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-ink-3 border border-border flex items-center justify-center text-accent">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="min-width-0">
                        <div class="text-sm font-bold text-text truncate">{{ $prod->name }}</div>
                        <div class="text-[10px] font-mono text-muted uppercase">{{ $prod->order_items_count }} throughputs</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('mainChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 110, 247, 0.2)');
        gradient.addColorStop(1, 'rgba(79, 110, 247, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'],
                datasets: [{
                    label: 'CORE_VALUATION',
                    data: [12400, 15600, 14200, 21000, 18900, 24500, 22100],
                    borderColor: '#4f6ef7',
                    borderWidth: 3,
                    pointBackgroundColor: '#4f6ef7',
                    pointBorderColor: 'rgba(255,255,255,0.1)',
                    pointHoverRadius: 6,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#181b23',
                        titleFont: { family: 'DM Mono', size: 10 },
                        bodyFont: { family: 'DM Mono', size: 12 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                        ticks: { color: '#5a6076', font: { family: 'DM Mono', size: 9 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#5a6076', font: { family: 'DM Mono', size: 9 } }
                    }
                }
            }
        });
    });
</script>
@endpush