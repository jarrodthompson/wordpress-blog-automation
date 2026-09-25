@extends('layouts.app')
@section('title', 'CRM & Automations')

@section('content')
<x-page-header title="CRM & Automations" badge="Sync"
    subtitle="Automations that fire off publishing, orders and subscriber events." />

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
    @php $stats = [
        ['Active automations', $automations->where('status','active')->count(), 'emerald'],
        ['Total runs', number_format($automations->sum('runs')), 'brand'],
        ['Paused', $automations->where('status','paused')->count(), 'amber'],
        ['Connected list', '1,204', 'slate'],
    ]; @endphp
    @foreach($stats as [$label,$val,$col])
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <p class="text-3xl font-extrabold {{ ['emerald'=>'text-emerald-600','brand'=>'text-brand-600','amber'=>'text-amber-600','slate'=>'text-slate-800'][$col] }}">{{ $val }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mt-1">{{ $label }}</p>
        </div>
    @endforeach
</div>

<div class="rounded-2xl bg-white border border-slate-200 shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-900">Automations</h3>
        <button class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> New automation
        </button>
    </div>
    <table class="w-full text-sm">
        <thead><tr class="text-left text-[11px] uppercase tracking-wide text-slate-400 border-b border-slate-100">
            <th class="px-5 py-3 font-semibold">Name</th><th class="px-3 py-3 font-semibold">Trigger</th>
            <th class="px-3 py-3 font-semibold">Action</th><th class="px-3 py-3 font-semibold">Runs</th><th class="px-5 py-3 font-semibold text-right">Status</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($automations as $a)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $a->name }}</td>
                    <td class="px-3 py-3.5 text-slate-500">{{ $a->trigger }}</td>
                    <td class="px-3 py-3.5 text-slate-500">{{ $a->action }}</td>
                    <td class="px-3 py-3.5 text-slate-600">{{ number_format($a->runs) }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <span class="text-[10px] font-bold px-2 py-1 rounded {{ $a->status==='active'?'bg-emerald-100 text-emerald-700':'bg-amber-100 text-amber-700' }}">{{ strtoupper($a->status) }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
