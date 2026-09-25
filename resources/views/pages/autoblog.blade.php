@extends('layouts.app')
@section('title', 'AutoBlog Scheduler')

@section('content')
<x-page-header title="AutoBlog Scheduler" badge="Auto"
    subtitle="Queue drafts to generate and publish automatically on a cadence." />

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center gap-2 mb-4">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <h3 class="font-bold text-slate-900">Scheduled ({{ $scheduled->count() }})</h3>
        </div>
        <div class="space-y-2">
            @forelse($scheduled as $p)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100">
                    <div class="w-11 h-11 rounded-lg bg-brand-50 text-brand-700 grid place-items-center text-center leading-none">
                        <div><p class="text-sm font-extrabold">{{ $p->scheduled_at->format('j') }}</p><p class="text-[9px] uppercase">{{ $p->scheduled_at->format('M') }}</p></div>
                    </div>
                    <div class="flex-1 min-w-0"><p class="font-semibold text-slate-800 text-sm line-clamp-1">{{ $p->title }}</p><p class="text-[11px] text-slate-400">{{ $p->scheduled_at->format('g:i A') }} · {{ $p->focus_keyword }}</p></div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-100 text-amber-700">QUEUED</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-8 text-center">Nothing scheduled yet.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center gap-2 mb-4">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            <h3 class="font-bold text-slate-900">Ready to schedule ({{ $planned->count() }})</h3>
        </div>
        <div class="space-y-2">
            @forelse($planned as $p)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50">
                    <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                    <div class="flex-1 min-w-0"><p class="font-semibold text-slate-800 text-sm line-clamp-1">{{ $p->title }}</p><p class="text-[11px] text-slate-400">{{ $p->word_count }}w outline · {{ $p->focus_keyword }}</p></div>
                    <button class="text-xs font-semibold text-brand-600 hover:text-brand-700">Schedule →</button>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-8 text-center">All planned posts are scheduled.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
