@extends('layouts.admin')

@section('title', 'Kernel')
@section('page_title', 'System Settings')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-white tracking-tighter leading-none">System <span class="text-primary-500">Kernel</span></h1>
            <p class="text-brand-muted font-bold text-[10px] uppercase tracking-[0.5em] mt-3 flex items-center">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-3 shadow-[0_0_10px_rgba(124,58,237,0.5)]"></span>
                Core Environment Configuration
            </p>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
        
        <!-- General Identity -->
        <div class="glass-panel rounded-[3.5rem] p-10 space-y-8">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-primary-500/10 rounded-2xl flex items-center justify-center text-primary-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-2xl font-black text-white tracking-tighter uppercase">Platform Identity</h4>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2 mb-3">Store Identifier</label>
                    <input type="text" value="ECOMM_PRO_V4" class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white focus:outline-none focus:border-primary-500 transition-all shadow-inner">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-brand-muted uppercase tracking-[0.3em] ml-2 mb-3">Support Channel</label>
                    <input type="email" value="ops@ecommerce-pro.io" class="w-full bg-white/5 border border-white/10 rounded-[2rem] px-8 py-5 text-sm font-black text-white focus:outline-none focus:border-primary-500 transition-all shadow-inner">
                </div>
            </div>
        </div>

        <!-- Security Layer -->
        <div class="glass-panel rounded-[3.5rem] p-10 space-y-8">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h4 class="text-2xl font-black text-white tracking-tighter uppercase">Security Protocol</h4>
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between bg-white/[0.02] p-6 rounded-[2rem] border border-white/5">
                    <div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest block font-bold">Two-Factor Encryption</span>
                        <span class="text-[8px] font-black text-brand-muted uppercase tracking-widest mt-1 block">Hardware token required for withdrawals</span>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-12 h-7 bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between bg-white/[0.02] p-6 rounded-[2rem] border border-white/5">
                    <div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest block font-bold">Maintenance Lock</span>
                        <span class="text-[8px] font-black text-brand-muted uppercase tracking-widest mt-1 block">Disconnect frontlayer API nodes</span>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-12 h-7 bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex items-center justify-end">
        <button class="bg-primary-600 hover:bg-primary-500 text-white px-14 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(124,58,237,0.2)] transition-all">
            Commit Changes
        </button>
    </div>
</div>
@endsection
