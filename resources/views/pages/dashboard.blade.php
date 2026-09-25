@extends('layouts.app')
@section('title', 'Dashboard')

@php
    $tint = [
        'blue' => ['bg'=>'bg-blue-50','tx'=>'text-blue-600','ring'=>'border-blue-100'],
        'indigo' => ['bg'=>'bg-indigo-50','tx'=>'text-indigo-600','ring'=>'border-indigo-100'],
        'amber' => ['bg'=>'bg-amber-50','tx'=>'text-amber-600','ring'=>'border-amber-100'],
        'emerald' => ['bg'=>'bg-emerald-50','tx'=>'text-emerald-600','ring'=>'border-emerald-100'],
    ];
    $stageIcons = [
        'bulb'=>'<path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.3 1 2.1V17h6v-.2c0-.8.4-1.6 1-2.1A7 7 0 0 0 12 2Z"/>',
        'pen'=>'<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'search'=>'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'rocket'=>'<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91 0Z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2Z"/>',
    ];
@endphp

@section('content')
<div class="space-y-5">

    {{-- Search / filter / sync row --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[240px] max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" placeholder="Search content..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
        </div>
        <div class="flex items-center gap-2 pl-3 pr-2.5 py-2.5 rounded-xl border border-slate-200 bg-white">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-sm font-medium text-slate-700">{{ Str::limit($brand->name, 16) }}</span>
            <span class="text-xs text-slate-400">{{ Str::limit($brand->domain, 18) }}</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><path d="m6 9 6 6 6-6"/></svg>
        </div>
        <div class="flex-1"></div>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Live · auto-refresh 60s
        </div>
        <form method="POST" action="{{ route('sync', ['brand'=>$brand->id]) }}">
            @csrf
            <button class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold text-slate-700">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                Sync Data
            </button>
        </form>
    </div>

    {{-- Hero stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Live published (dark green) --}}
        <div class="rounded-2xl bg-gradient-to-br from-forest-700 to-forest-900 text-white p-5 shadow-card relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-xl bg-white/10 grid place-items-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white/15">Live</span>
            </div>
            <p class="text-4xl font-extrabold mt-4">{{ $hero['published'] }}</p>
            <p class="text-sm font-semibold mt-1">Live Published Posts</p>
            <p class="text-xs text-emerald-100/70">Direct from WP REST API</p>
            <div class="mt-4 text-[11px] flex items-center gap-2 bg-white/10 rounded-lg px-2.5 py-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                {{ $hero['published'] }} posts in the last 12 months — building momentum
            </div>
        </div>

        @php
            $whiteCards = [
                ['icon'=>'<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 6h8M8 10h8M8 14h5"/>','tint'=>'blue','val'=>$hero['drafts'],'title'=>'Drafts Awaiting','sub'=>'On the live site','pill'=>$hero['drafts'].' draft ready to review & publish','pillIco'=>'amber'],
                ['icon'=>'<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>','tint'=>'indigo','val'=>$hero['comments'],'title'=>'Total Comments','sub'=>'User engagement','pill'=>'No recent engagement — consider a fresh post','pillIco'=>'amber'],
                ['icon'=>'<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/>','tint'=>'purple','val'=>$hero['media'],'title'=>'Media Library Items','sub'=>$brand->metric('pages').' pages · '.$brand->metric('categories').' categories · '.$brand->metric('users').' users','pill'=>$hero['media'].' assets across '.$brand->metric('pages').' pages','pillIco'=>'slate'],
            ];
        @endphp
        @foreach($whiteCards as $c)
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl grid place-items-center {{ ['blue'=>'bg-blue-50 text-blue-600','indigo'=>'bg-indigo-50 text-indigo-600','purple'=>'bg-purple-50 text-purple-600'][$c['tint']] }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">{!! $c['icon'] !!}</svg>
                    </div>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Live</span>
                </div>
                <p class="text-4xl font-extrabold mt-4 text-slate-900">{{ $c['val'] }}</p>
                <p class="text-sm font-semibold mt-1 text-slate-800">{{ $c['title'] }}</p>
                <p class="text-xs text-slate-400">{{ $c['sub'] }}</p>
                <div class="mt-4 text-[11px] flex items-center gap-2 rounded-lg px-2.5 py-1.5 {{ $c['pillIco']==='amber' ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $c['pillIco']==='amber' ? 'bg-amber-400' : 'bg-slate-400' }}"></span>
                    {{ $c['pill'] }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Workflow stages --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <h2 class="text-lg font-bold text-slate-900">Dashboard</h2>
        <p class="text-sm text-slate-500 mt-0.5">Where every post sits in the blog workflow — click a stage to filter.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
            @foreach($stages as $i => $s)
                <a href="{{ route('blog-manager', ['brand'=>$brand->id,'stage'=>$s['key']]) }}"
                   class="group relative rounded-xl border {{ $tint[$s['color']]['ring'] }} {{ $tint[$s['color']]['bg'] }} p-4 hover:shadow-sm transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white grid place-items-center {{ $tint[$s['color']]['tx'] }} shadow-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">{!! $stageIcons[$s['icon']] !!}</svg>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold {{ $tint[$s['color']]['tx'] }} leading-none">{{ $s['count'] }}</p>
                            <p class="text-[13px] font-semibold text-slate-700 mt-1">{{ $s['label'] }}</p>
                        </div>
                    </div>
                    @if($i < 3)
                        <svg class="hidden lg:block absolute -right-2.5 top-1/2 -translate-y-1/2 text-slate-300" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    @include('pages.partials.dashboard-insights')
    @include('pages.partials.dashboard-charts')
    @include('pages.partials.dashboard-lists')

</div>
@endsection
