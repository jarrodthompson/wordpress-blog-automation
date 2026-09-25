@extends('layouts.app')
@section('title', 'Activity Log')

@section('content')
<x-page-header title="Activity Log"
    subtitle="Every publish, edit and comment across {{ $brand->name }}, newest first." />

<div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
    <div class="space-y-1">
        @foreach($activities as $ev)
            <div class="flex gap-3 p-3 rounded-xl hover:bg-slate-50">
                <div class="w-8 h-8 shrink-0 rounded-lg grid place-items-center {{ $ev->type==='edited' ? 'bg-blue-50 text-blue-500':'bg-emerald-50 text-emerald-500' }}">
                    @if($ev->type==='edited')
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                    @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm"><span class="font-semibold text-slate-700">{{ $ev->type==='edited'?'Edited draft':'Published' }}</span> <span class="text-slate-600">{{ $ev->title }}</span></p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $brand->name }} · {{ $ev->occurred_at?->format('j M Y, g:i A') }} · {{ $ev->occurred_at?->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $activities->links() }}</div>
</div>
@endsection
