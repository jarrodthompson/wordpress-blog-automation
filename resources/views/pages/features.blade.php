@extends('layouts.app')
@section('title', 'Feature Tracker')

@section('content')
<x-page-header title="Feature Tracker" badge="{{ $features->flatten()->count() }}"
    subtitle="Roadmap of what's planned, in progress and shipped." />

@php $cols = ['planned'=>['Planned','slate'],'in_progress'=>['In Progress','indigo'],'shipped'=>['Shipped','emerald']]; @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    @foreach($cols as $key => [$label, $col])
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-4">
            <div class="flex items-center gap-2 mb-3 px-1">
                <span class="w-2.5 h-2.5 rounded-full {{ ['slate'=>'bg-slate-400','indigo'=>'bg-indigo-500','emerald'=>'bg-emerald-500'][$col] }}"></span>
                <h3 class="font-bold text-slate-900 text-sm">{{ $label }}</h3>
                <span class="ml-auto text-xs text-slate-400">{{ ($features[$key] ?? collect())->count() }}</span>
            </div>
            <div class="space-y-2">
                @forelse($features[$key] ?? [] as $f)
                    <div class="rounded-xl border border-slate-100 p-3.5 hover:shadow-sm transition">
                        <p class="font-semibold text-slate-800 text-sm">{{ $f->title }}</p>
                        <p class="text-[12px] text-slate-500 mt-1 leading-snug">{{ $f->description }}</p>
                        <div class="flex items-center gap-1.5 mt-2.5 text-[11px] text-slate-400">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg>
                            {{ $f->votes }} votes
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Nothing here.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
