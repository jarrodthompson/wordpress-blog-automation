@extends('layouts.app')
@section('title', 'Brand DNA & Vault')

@section('content')
<x-page-header title="Brand DNA & Vault"
    subtitle="The voice, audience and rules the AI uses to write for {{ $brand->name }}." />

@php $dna = $brand->brand_dna ?? []; @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <h3 class="font-bold text-slate-900 mb-2">Brand voice</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $brand->voice }}</p>
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach(($dna['tone'] ?? []) as $t)
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand-50 text-brand-700">{{ $t }}</span>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <h3 class="font-bold text-slate-900 mb-2">Content pillars</h3>
            <div class="grid grid-cols-2 gap-2">
                @foreach(($dna['pillars'] ?? []) as $p)
                    <div class="flex items-center gap-2 p-3 rounded-xl border border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-medium text-slate-700">{{ $p }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <h3 class="font-bold text-slate-900 mb-2">Target keywords</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(($dna['keywords'] ?? []) as $k)
                    <span class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-mono">{{ $k }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <div class="flex items-center gap-3">
                <span class="w-12 h-12 rounded-xl grid place-items-center text-white text-sm font-bold" style="background:{{ $brand->color }}">{{ $brand->initials }}</span>
                <div><p class="font-bold text-slate-900">{{ $brand->name }}</p><p class="text-xs text-slate-400">{{ $brand->domain }}</p></div>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Audience</span></div>
                <p class="text-slate-700">{{ $dna['audience'] ?? '—' }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-slate-900 text-white shadow-card p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                <h3 class="font-bold text-sm">Vault</h3>
            </div>
            <p class="text-xs text-white/60 leading-relaxed">Encrypted credentials — WP application password and API keys — are stored here and never rendered back to the client.</p>
            <div class="mt-3 space-y-1.5 text-xs">
                <div class="flex justify-between"><span class="text-white/50">WP REST API</span><span class="text-emerald-300">● Connected</span></div>
                <div class="flex justify-between"><span class="text-white/50">Gemini AI</span><span class="text-amber-300">● Key required</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
