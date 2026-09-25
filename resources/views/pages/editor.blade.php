@extends('layouts.app')
@section('title', 'Blog Editor')

@php $gen = $post?->generation_history[0] ?? null; @endphp

@section('content')
@if(! $post)
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-10 text-center">
        <p class="text-slate-500">No posts yet. <a href="{{ route('blog-manager', ['brand'=>$brand->id]) }}" class="text-brand-600 font-semibold">Create one in Blog Manager →</a></p>
    </div>
@else
<div x-data="{ tab: '{{ request('tab', 'brief') }}', mode: 'generate', view: 'blocks' }" class="space-y-4">

    {{-- ===== Header card ===== --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[10px] font-bold tracking-wide text-slate-400">POST</span>
                    <span class="text-[10px] font-bold px-2 py-1 rounded bg-emerald-50 text-emerald-700 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ strtoupper($brand->name) }}</span>
                    <span class="text-[11px] text-slate-400">{{ number_format($post->word_count) }} words</span>
                </div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold text-slate-900 leading-tight truncate">{{ $post->title }}</h1>
                @if($post->initial_prompt)
                    <p class="text-sm text-slate-400 mt-2"><span class="text-slate-500">Initial prompt:</span> <span class="italic">{{ $post->initial_prompt }}</span></p>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="#" class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-brand-50 text-brand-700 text-sm font-semibold hover:bg-brand-100">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg> Preview
                </a>
                <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg> Saved
                </button>
            </div>
        </div>

        {{-- Stage rail --}}
        <div class="mt-6 grid grid-cols-5 gap-0 items-start">
            @foreach($stages as $i => $s)
                <div class="relative flex flex-col items-center">
                    @if($i > 0)
                        <span class="absolute top-4 right-1/2 w-full h-0.5 {{ $s['done'] ? 'bg-emerald-500' : 'bg-slate-200' }}"></span>
                    @endif
                    <span class="relative z-10 w-8 h-8 rounded-full grid place-items-center {{ $s['done'] ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }} {{ $loop->last && $s['done'] ? 'ring-4 ring-emerald-100' : '' }}">
                        @if($loop->last)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91 0Z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2Z"/></svg>
                        @elseif($s['done'])
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                        @else
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        @endif
                    </span>
                    <span class="mt-2 text-[13px] font-semibold {{ $s['done'] ? 'text-emerald-700' : 'text-slate-400' }}">{{ $s['label'] }}</span>
                </div>
            @endforeach
        </div>

        @php $isLive = $post->status === 'published'; @endphp
        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
            @if($isLive)
                <p class="text-sm text-emerald-700 flex items-center gap-2">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <span><span class="font-bold">This post is live</span> — use Publish to WP to re-sync changes</span>
                    <span class="text-slate-400">· first published {{ $post->published_at?->format('n/j/Y') }}</span>
                </p>
                <span class="text-xs text-slate-400">Synced to WordPress</span>
            @else
                <p class="text-sm text-slate-500">Draft · currently in <span class="font-semibold text-slate-700">{{ $post->statusLabel() }}</span></p>
                <span class="text-xs text-slate-400">Not yet published</span>
            @endif
        </div>
    </div>

    {{-- ===== Tab bar ===== --}}
    @php
        $tabs = [
            ['id'=>'brief','label'=>'Brief','icon'=>'<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/>','meta'=>null],
            ['id'=>'write','label'=>'Write','icon'=>'<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>','meta'=>number_format($post->word_count)],
            ['id'=>'seo','label'=>'SEO','icon'=>'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>','meta'=>$post->seo_score],
            ['id'=>'visuals','label'=>'Visuals','icon'=>'<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/>','dot'=>true],
            ['id'=>'publish','label'=>'Publish','icon'=>'<path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2Z"/><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2Z"/>','meta'=>$isLive?'Live':null],
            ['id'=>'social','label'=>'Social','icon'=>'<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5 8.6 10.5"/>','dot'=>true],
        ];
    @endphp
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-1.5 flex flex-wrap gap-1">
        @foreach($tabs as $t)
            <button @click="tab='{{ $t['id'] }}'"
                :class="tab==='{{ $t['id'] }}' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition flex-1 justify-center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">{!! $t['icon'] !!}</svg>
                {{ $t['label'] }}
                @if(!empty($t['meta']))
                    <span :class="tab==='{{ $t['id'] }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'" class="text-[11px] font-bold px-1.5 py-0.5 rounded">{{ $t['meta'] }}</span>
                @endif
                @if(!empty($t['dot']))<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>@endif
            </button>
        @endforeach
    </div>

    {{-- ===== Generation history (persistent right-aligned) ===== --}}
    @if($gen)
    <div class="flex justify-end">
        <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 shadow-card p-4">
            <div class="flex items-center gap-2 mb-3">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>
                <h3 class="font-bold text-slate-800 text-sm">Generation history</h3>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-brand-50 text-brand-700">{{ count($post->generation_history) }}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto text-slate-300"><path d="m18 15-6-6-6 6"/></svg>
            </div>
            @foreach($post->generation_history as $h)
                <div class="flex items-start gap-2.5 text-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5"></span>
                    <div>
                        <p class="text-slate-500 text-[13px]">{{ \Illuminate\Support\Carbon::parse($h['at'])->format('M j g:i:s A') }} <span class="font-semibold text-slate-700">{{ $h['label'] }}</span></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-brand-50 text-brand-700">{{ $h['model'] }}</span>
                            <span class="text-[11px] text-slate-400">{{ number_format($h['words']) }} words</span>
                            <span class="text-[11px] text-slate-400">{{ number_format($h['seconds'], 1) }}s</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== Panels ===== --}}
    @include('pages.partials.editor-brief')
    @include('pages.partials.editor-write')
    @include('pages.partials.editor-seo')
    @include('pages.partials.editor-visuals')
    @include('pages.partials.editor-publish')
    @include('pages.partials.editor-social')
</div>
@endif
@endsection
