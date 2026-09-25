@props(['title', 'subtitle' => null, 'badge' => null])
<div class="flex items-start justify-between flex-wrap gap-3 mb-5">
    <div>
        <div class="flex items-center gap-2.5">
            <h1 class="text-2xl font-extrabold text-slate-900">{{ $title }}</h1>
            @if($badge)<span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-brand-50 text-brand-700">{{ $badge }}</span>@endif
        </div>
        @if($subtitle)<p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>@endif
    </div>
    {{ $slot ?? '' }}
</div>
@if(session('status'))
<div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
    {{ session('status') }}
</div>
@endif
