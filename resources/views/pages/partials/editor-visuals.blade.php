{{-- ===== VISUALS ===== --}}
<div x-show="tab==='visuals'" x-cloak class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Featured --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="flex items-center gap-2 font-bold text-slate-800"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/></svg> Featured Image</span>
            <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">Set ✓</span>
        </div>
        <div class="aspect-[16/10] rounded-xl overflow-hidden relative bg-gradient-to-br from-rose-200 via-amber-100 to-emerald-200">
            <div class="absolute inset-0 grid place-items-center text-center p-6">
                <div>
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="1.3" class="mx-auto"><path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>
                    <p class="text-[13px] font-semibold text-amber-900/80 mt-2">Christmas Eve box with hardback classics</p>
                    <p class="text-[11px] text-amber-900/50 mt-0.5">1536×960 · Nano Banana · fresh-green-classics</p>
                </div>
            </div>
        </div>
        <button class="mt-3 w-full px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-sm font-semibold">Generate Image Ideas</button>
    </div>

    {{-- Secondary --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="flex items-center gap-2 font-bold text-slate-800"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-4.5-4.5L5 22"/></svg> Secondary Image</span>
            <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">Set ✓</span>
        </div>
        <div class="aspect-[16/10] rounded-xl overflow-hidden relative bg-gradient-to-br from-emerald-200 via-rose-100 to-amber-200">
            <div class="absolute inset-0 grid place-items-center text-center p-6">
                <div>
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="1.3" class="mx-auto"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>
                    <p class="text-[13px] font-semibold text-emerald-900/80 mt-2">Child reading by the tree</p>
                    <p class="text-[11px] text-emerald-900/50 mt-0.5">In-body editorial image · Nano Banana</p>
                </div>
            </div>
        </div>
        <p class="text-[12px] text-slate-500 mt-3 leading-snug">In-body editorial image generated from the article topic + context with the paid Nano Banana model. It appears as a figure inside the published post.</p>
    </div>
</div>
