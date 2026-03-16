@extends('layouts.admin')

@section('title', 'Transactions')
@section('page_title', 'Order Management')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-white tracking-tighter leading-none">Transaction <span class="text-primary-500">History</span></h1>
            <p class="text-brand-muted font-bold text-[10px] uppercase tracking-[0.5em] mt-3 flex items-center">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-3 shadow-[0_0_10px_rgba(124,58,237,0.5)]"></span>
                Real-time commerce verification
            </p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="glass-panel rounded-[2.5rem] p-8">
            <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.3em]">Total Throughput</p>
            <h3 class="text-3xl font-black text-white mt-2 tracking-tighter">1,284</h3>
        </div>
        <div class="glass-panel rounded-[2.5rem] p-8 border-emerald-500/10">
            <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.3em]">Success Rate</p>
            <h3 class="text-3xl font-black text-emerald-400 mt-2 tracking-tighter">98.2%</h3>
        </div>
        <div class="glass-panel rounded-[2.5rem] p-8 border-orange-500/10">
            <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.3em]">Pending Sync</p>
            <h3 class="text-3xl font-black text-orange-400 mt-2 tracking-tighter">12</h3>
        </div>
        <div class="glass-panel rounded-[2.5rem] p-8 border-rose-500/10">
            <p class="text-[9px] font-black text-brand-muted uppercase tracking-[0.3em]">Failed Logic</p>
            <h3 class="text-3xl font-black text-rose-400 mt-2 tracking-tighter">2</h3>
        </div>
    </div>

    <!-- Empty Table Placeholder -->
    <div class="glass-panel rounded-[3.5rem] overflow-hidden">
        <div class="px-12 py-32 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 bg-white/5 rounded-[3rem] flex items-center justify-center mx-auto mb-10 border border-white/5">
                    <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">No Transaction Data</h3>
                <p class="text-[10px] font-black text-brand-muted uppercase tracking-[0.3em] mt-4 leading-relaxed">System is awaiting initial commerce events. All nodes are ready for synchronization.</p>
            </div>
        </div>
    </div>
</div>
@endsection
