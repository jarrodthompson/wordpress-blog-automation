@extends('layouts.app')
@section('title', 'Blog Editor')

@section('content')
<x-page-header title="Blog Editor" badge="Gemini AI"
    subtitle="Generate a draft in your brand voice, refine it, then publish to WordPress." />

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Editor --}}
    <form method="POST" action="{{ route('editor.save', ['brand'=>$brand->id]) }}" class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 shadow-card p-5 space-y-4">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id ?? '' }}">
        <div>
            <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Title</label>
            <input name="title" value="{{ old('title', $post->title ?? '') }}" required placeholder="Post title…"
                   class="mt-1 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-lg font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Focus keyword</label>
                <input name="focus_keyword" value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}" placeholder="e.g. Christmas eve box books"
                       class="mt-1 w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
            </div>
            <div>
                <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Stage</label>
                <select name="status" class="mt-1 w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    @foreach(['planned'=>'Plan & Research','writing'=>'In Writing','review'=>'Review & Optimise','published'=>'Published Live'] as $k=>$v)
                        <option value="{{ $k }}" @selected(($post->status ?? 'planned')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Body (HTML)</label>
            <textarea name="body" rows="18" placeholder="Write, or generate with AI →"
                      class="mt-1 w-full px-3.5 py-3 rounded-xl border border-slate-200 text-sm font-mono leading-relaxed focus:outline-none focus:ring-2 focus:ring-brand-500/30">{{ old('body', $post->body ?? '') }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <p class="text-xs text-slate-400">{{ $post ? number_format($post->word_count).' words · '.$post->block_count.' blocks' : 'New draft' }}</p>
            <button class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold">Save draft</button>
        </div>
    </form>

    {{-- Right rail --}}
    <div class="space-y-4">
        {{-- AI generate --}}
        <div class="rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-card p-5">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg>
                <h3 class="font-bold">Generate with Gemini</h3>
            </div>
            <p class="text-xs text-white/70 mt-1">
                {{ $geminiReady ? 'Connected — live generation enabled.' : 'No API key set — uses a local draft template. Add GEMINI_API_KEY to go live.' }}
            </p>
            <form method="POST" action="{{ route('editor.generate', ['brand'=>$brand->id]) }}" class="mt-3 space-y-2">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id ?? '' }}">
                <input name="topic" required value="{{ $post->title ?? '' }}" placeholder="Topic or working title…"
                       class="w-full px-3.5 py-2.5 rounded-xl text-slate-800 text-sm focus:outline-none">
                <input name="keyword" value="{{ $post->focus_keyword ?? '' }}" placeholder="Focus keyword (optional)"
                       class="w-full px-3.5 py-2.5 rounded-xl text-slate-800 text-sm focus:outline-none">
                <button class="w-full px-4 py-2.5 rounded-xl bg-white text-brand-700 text-sm font-bold hover:bg-white/90">Generate draft →</button>
            </form>
        </div>

        {{-- SEO / meta --}}
        @if($post)
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <h3 class="font-bold text-slate-900 text-sm mb-3">Post signals</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Words</span><span class="font-semibold text-slate-800">{{ number_format($post->word_count) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Blocks</span><span class="font-semibold text-slate-800">{{ $post->block_count }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Humanised</span><span class="font-semibold {{ $post->humanised?'text-emerald-600':'text-slate-400' }}">{{ $post->humanised?'Yes':'No' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Grammar checked</span><span class="font-semibold {{ $post->grammar_checked?'text-emerald-600':'text-slate-400' }}">{{ $post->grammar_checked?'Yes':'No' }}</span></div>
                @if($post->gen_time_seconds)<div class="flex justify-between"><span class="text-slate-500">Gen time</span><span class="font-semibold text-slate-800">{{ $post->gen_time_seconds }}s</span></div>@endif
            </div>
        </div>
        @endif

        {{-- Drafts --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <h3 class="font-bold text-slate-900 text-sm mb-3">Open drafts <span class="text-slate-400 font-normal">({{ $drafts->count() }})</span></h3>
            <div class="space-y-1 max-h-72 overflow-y-auto">
                @foreach($drafts as $d)
                    <a href="{{ route('editor', ['brand'=>$brand->id,'post'=>$d->id]) }}"
                       class="block px-3 py-2 rounded-lg text-sm hover:bg-slate-50 {{ ($post->id ?? null)===$d->id ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600' }}">
                        <span class="line-clamp-1">{{ $d->title }}</span>
                        <span class="text-[10px] uppercase tracking-wide text-slate-400">{{ $d->statusLabel() }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
