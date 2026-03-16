@extends('layouts.admin')

@section('title', 'Entities')
@section('page_title', 'Customer Matrix')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-white tracking-tighter leading-none">Customer <span class="text-primary-500">Entities</span></h1>
            <p class="text-brand-muted font-bold text-[10px] uppercase tracking-[0.5em] mt-3 flex items-center">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-3 shadow-[0_0_10px_rgba(124,58,237,0.5)]"></span>
                Global Identity Management
            </p>
        </div>
    </div>

    <!-- Empty Table Placeholder -->
    <div class="glass-panel rounded-[3.5rem] overflow-hidden">
        <div class="px-12 py-32 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 bg-white/5 rounded-[3rem] flex items-center justify-center mx-auto mb-10 border border-white/5">
                    <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">No Entity Definitions</h3>
                <p class="text-[10px] font-black text-brand-muted uppercase tracking-[0.3em] mt-4 leading-relaxed">Identity vault is currently empty. Deployment ready for first user registration event.</p>
            </div>
        </div>
    </div>
</div>
@endsection
