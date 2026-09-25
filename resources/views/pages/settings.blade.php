@extends('layouts.app')
@section('title', 'Settings & Configs')

@section('content')
<x-page-header title="Settings & Configs"
    subtitle="Connect {{ $brand->name }} to WordPress and the AI providers." />

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <h3 class="font-bold text-slate-900 mb-4">WordPress connection</h3>
        <div class="space-y-3">
            @foreach([['Site URL', $brand->wp_url, 'https://…'],['Username', $brand->wp_username, 'wp-user'],['Application password', str_repeat('•', 16), '••••••••']] as [$label,$val,$ph])
                <div>
                    <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</label>
                    <input value="{{ $val }}" placeholder="{{ $ph }}" class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                </div>
            @endforeach
            <div class="flex items-center gap-2 text-xs text-emerald-600 pt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Reachable via WP REST API
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <h3 class="font-bold text-slate-900 mb-4">AI providers</h3>
        <div class="space-y-3">
            <div>
                <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Gemini API key</label>
                <input type="password" placeholder="{{ config('services.gemini.key') ? '•••• configured' : 'Add GEMINI_API_KEY' }}" class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
            </div>
            <div>
                <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Model</label>
                <select class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                    <option>gemini-1.5-flash</option><option>gemini-1.5-pro</option>
                </select>
            </div>
            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-xs text-slate-500">
                Stack: <span class="font-semibold text-slate-700">Laravel 11 · MySQL · Gemini AI · WP REST API</span>. Keys are stored in the encrypted brand vault, never exposed to the browser.
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <h3 class="font-bold text-slate-900 mb-4">Brand</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div><label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Name</label><input value="{{ $brand->name }}" class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"></div>
            <div><label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Domain</label><input value="{{ $brand->domain }}" class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"></div>
            <div><label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Accent colour</label>
                <div class="mt-1 flex items-center gap-2"><span class="w-9 h-9 rounded-lg" style="background:{{ $brand->color }}"></span><input value="{{ $brand->color }}" class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono"></div>
            </div>
        </div>
        <button class="mt-4 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold">Save settings</button>
    </div>
</div>
@endsection
