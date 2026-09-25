@extends('layouts.app')
@section('title', 'Scoreboard & Orders')

@section('content')
<x-page-header title="Scoreboard & Orders" badge="Live"
    subtitle="Revenue and order flow across {{ $brand->name }}." />

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
    @php $stats = [
        ['Revenue', '£'.number_format($revenue, 2), 'emerald'],
        ['Orders', $orders->count(), 'brand'],
        ['Avg order', '£'.number_format($orders->count() ? $orders->avg('total') : 0, 2), 'slate'],
        ['Refunded', $orders->where('status','refunded')->count(), 'rose'],
    ]; @endphp
    @foreach($stats as [$label,$val,$col])
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <p class="text-3xl font-extrabold {{ ['emerald'=>'text-emerald-600','brand'=>'text-brand-600','slate'=>'text-slate-800','rose'=>'text-rose-600'][$col] }}">{{ $val }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mt-1">{{ $label }}</p>
        </div>
    @endforeach
</div>

<div class="rounded-2xl bg-white border border-slate-200 shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-bold text-slate-900">Recent orders</h3></div>
    <table class="w-full text-sm">
        <thead><tr class="text-left text-[11px] uppercase tracking-wide text-slate-400 border-b border-slate-100">
            <th class="px-5 py-3 font-semibold">Reference</th><th class="px-3 py-3 font-semibold">Customer</th>
            <th class="px-3 py-3 font-semibold">Total</th><th class="px-3 py-3 font-semibold">Placed</th><th class="px-5 py-3 font-semibold text-right">Status</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($orders as $o)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-5 py-3.5 font-mono text-slate-700">{{ $o->reference }}</td>
                    <td class="px-3 py-3.5 font-semibold text-slate-800">{{ $o->customer }}</td>
                    <td class="px-3 py-3.5 text-slate-700">£{{ number_format($o->total, 2) }}</td>
                    <td class="px-3 py-3.5 text-slate-400 whitespace-nowrap">{{ $o->placed_at?->format('j M Y') }}</td>
                    <td class="px-5 py-3.5 text-right">
                        @php $c = ['paid'=>'bg-emerald-100 text-emerald-700','fulfilled'=>'bg-blue-100 text-blue-700','refunded'=>'bg-rose-100 text-rose-700'][$o->status] ?? 'bg-slate-100 text-slate-600'; @endphp
                        <span class="text-[10px] font-bold px-2 py-1 rounded {{ $c }}">{{ strtoupper($o->status) }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
