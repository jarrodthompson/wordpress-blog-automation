@extends('layouts.app')
@section('title', 'AI Image Generator')

@section('content')
<x-page-header title="AI Image Generator" badge="Visuals"
    subtitle="Generate on-brand hero and card imagery, then push it straight to the WordPress media library." />

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-card p-5 h-fit">
        <div class="flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/></svg>
            <h3 class="font-bold">New visual</h3>
        </div>
        <div class="mt-3 space-y-2">
            <textarea rows="3" placeholder="Describe the image… e.g. 'a hardback children's book beside a candle, warm nostalgic tones'" class="w-full px-3.5 py-2.5 rounded-xl text-slate-800 text-sm focus:outline-none"></textarea>
            <div class="grid grid-cols-2 gap-2">
                <select class="px-3 py-2 rounded-xl text-slate-800 text-sm"><option>Hero (16:9)</option><option>Card (1:1)</option><option>Banner (3:1)</option></select>
                <select class="px-3 py-2 rounded-xl text-slate-800 text-sm"><option>Warm & nostalgic</option><option>Bright & playful</option><option>Editorial</option></select>
            </div>
            <button class="w-full px-4 py-2.5 rounded-xl bg-white text-brand-700 text-sm font-bold hover:bg-white/90">Generate image →</button>
        </div>
        <p class="text-[11px] text-white/60 mt-3">Wire an image model key in Settings to enable live generation. Generated assets sync to the {{ $total }}-item media library.</p>
    </div>

    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900">Media Library</h3>
            <span class="text-xs text-slate-400">{{ number_format($total) }} assets</span>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
            @foreach($media as $m)
                <div class="aspect-square rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 grid place-items-center relative group overflow-hidden">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/></svg>
                    <span class="absolute bottom-0 inset-x-0 text-[8px] text-slate-500 bg-white/70 px-1 py-0.5 truncate opacity-0 group-hover:opacity-100">{{ $m->title }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
