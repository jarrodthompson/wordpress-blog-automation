@php $brand = $activeBrand ?? ($brands->first() ?? null); @endphp
<header class="h-16 shrink-0 bg-white/80 backdrop-blur border-b border-slate-200 sticky top-0 z-20 flex items-center gap-3 px-6">
    <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="flex items-center gap-2.5 pl-3 pr-2.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-sm font-semibold text-slate-900">{{ $brand->name ?? 'No brand' }}</span>
            <span class="text-xs text-slate-400">{{ $brand->domain ?? '' }}</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div x-show="open" @click.outside="open=false" x-cloak x-transition class="absolute mt-2 w-72 bg-white rounded-xl border border-slate-200 shadow-lg p-1.5 z-30">
            @foreach(($brands ?? []) as $b)
                <a href="{{ route('dashboard', ['brand'=>$b->id]) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50">
                    <span class="w-7 h-7 rounded-lg grid place-items-center text-white text-[11px] font-bold" style="background:{{ $b->color }}">{{ $b->initials }}</span>
                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-slate-800 truncate">{{ $b->name }}</p><p class="text-[11px] text-slate-400 truncate">{{ $b->domain }}</p></div>
                    @if($brand && $b->id===$brand->id)<span class="w-2 h-2 rounded-full bg-emerald-500"></span>@endif
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex-1"></div>

    <div class="hidden md:flex items-center gap-2 pl-3 pr-3.5 py-1.5 rounded-full border border-slate-200 bg-white">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20"/></svg>
        <span class="text-xs font-medium text-slate-600">{{ $brand->domain ?? '' }}</span>
    </div>
    <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> New Brand
    </button>
    <button class="w-9 h-9 grid place-items-center rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-500">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H2a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 3.6 8a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H8a1.65 1.65 0 0 0 1-1.51V2a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V8a1.65 1.65 0 0 0 1.51 1H22a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></svg>
    </button>
    <div class="flex items-center gap-1.5 pl-2.5 pr-3 py-1.5 rounded-full border border-slate-200 bg-white text-sm font-medium text-slate-700">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-500"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg> owner
    </div>
</header>
