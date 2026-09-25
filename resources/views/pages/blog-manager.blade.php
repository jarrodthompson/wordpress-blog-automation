@extends('layouts.app')
@section('title', 'Blog Manager')

@section('content')
<x-page-header title="Blog Manager" badge="{{ $posts->count() }} posts"
    subtitle="Every post across the {{ $brand->name }} pipeline — filter by workflow stage.">
    <a href="{{ route('editor', ['brand'=>$brand->id]) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> New Post
    </a>
</x-page-header>

@php
    $tabs = ['' => 'All', 'planned' => 'Plan & Research', 'writing' => 'In Writing', 'review' => 'Review', 'published' => 'Published'];
    $badgeMap = ['planned'=>'bg-slate-100 text-slate-600','writing'=>'bg-indigo-100 text-indigo-600','review'=>'bg-amber-100 text-amber-700','published'=>'bg-emerald-100 text-emerald-700'];
@endphp
<div class="flex flex-wrap gap-2 mb-4">
    @foreach($tabs as $key => $label)
        @php $n = $key==='' ? $posts->count() : ($counts[$key] ?? 0); @endphp
        <a href="{{ route('blog-manager', array_filter(['brand'=>$brand->id,'stage'=>$key])) }}"
           class="px-3.5 py-2 rounded-lg text-sm font-medium border transition {{ ($activeStage ?? '')===$key ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            {{ $label }} <span class="opacity-60">{{ $n }}</span>
        </a>
    @endforeach
</div>

<div class="rounded-2xl bg-white border border-slate-200 shadow-card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-[11px] uppercase tracking-wide text-slate-400 border-b border-slate-100">
                <th class="px-5 py-3 font-semibold">Title</th>
                <th class="px-3 py-3 font-semibold">Stage</th>
                <th class="px-3 py-3 font-semibold">Words</th>
                <th class="px-3 py-3 font-semibold">Blocks</th>
                <th class="px-3 py-3 font-semibold">Focus keyword</th>
                <th class="px-3 py-3 font-semibold">Updated</th>
                <th class="px-5 py-3 font-semibold text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($posts as $p)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-5 py-3.5 max-w-md">
                        <p class="font-semibold text-slate-800 line-clamp-2">{{ $p->title }}</p>
                    </td>
                    <td class="px-3 py-3.5"><span class="text-[10px] font-bold px-2 py-1 rounded {{ $badgeMap[$p->status] }}">{{ strtoupper($p->statusLabel()) }}</span></td>
                    <td class="px-3 py-3.5 text-slate-600">{{ number_format($p->word_count) }}</td>
                    <td class="px-3 py-3.5 text-slate-600">{{ $p->block_count }}</td>
                    <td class="px-3 py-3.5 text-slate-500">{{ $p->focus_keyword ?: '—' }}</td>
                    <td class="px-3 py-3.5 text-slate-400 whitespace-nowrap">{{ $p->updated_at->format('j M Y') }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('editor', ['brand'=>$brand->id,'post'=>$p->id]) }}" class="text-brand-600 font-semibold hover:text-brand-700">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No posts in this stage yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
