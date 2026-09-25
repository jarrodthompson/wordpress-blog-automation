@extends('layouts.app')
@section('title', 'Product Manager')

@section('content')
<x-page-header title="Product Manager" badge="CRUD"
    subtitle="Manage the {{ $brand->name }} catalogue synced with WooCommerce.">
    <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> Add product
    </button>
</x-page-header>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @foreach($products as $p)
        <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 grid place-items-center">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.7"><path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/></svg>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded bg-emerald-100 text-emerald-700">{{ strtoupper($p->status) }}</span>
            </div>
            <p class="font-bold text-slate-800 mt-3 leading-snug">{{ $p->name }}</p>
            <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $p->sku }}</p>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                <div><p class="text-[10px] uppercase text-slate-400">Price</p><p class="font-extrabold text-slate-900">£{{ number_format($p->price, 2) }}</p></div>
                <div class="text-right"><p class="text-[10px] uppercase text-slate-400">Stock</p><p class="font-extrabold {{ $p->stock < 15 ? 'text-amber-600':'text-slate-900' }}">{{ $p->stock }}</p></div>
            </div>
        </div>
    @endforeach
</div>
@endsection
