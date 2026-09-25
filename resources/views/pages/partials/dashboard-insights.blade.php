{{-- Pipeline Insights --}}
<div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
    <div class="flex items-center gap-2">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="7"/><rect x="12" y="6" width="3" height="11"/><rect x="17" y="13" width="3" height="4"/></svg>
        <h2 class="text-lg font-bold text-slate-900">Pipeline Insights</h2>
    </div>
    <p class="text-sm text-slate-500 mt-0.5">Quality signals from grammar rules, generation, humanisation, blocks and scheduling — across {{ $posts->count() }} items in scope.</p>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mt-4">
        @php
            $insightTiles = [
                ['label'=>'Grammar Rules','big'=>$insights['grammar_ready'],'suffix'=>'/'.$insights['grammar_rules'],'note'=>$insights['grammar_rules'].' brand need rules','tint'=>'amber','ico'=>'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>'],
                ['label'=>'Avg Gen Time','big'=>$insights['avg_gen_time'],'suffix'=>'s','note'=>'across '.$insights['generated_count'].' generated items','tint'=>'blue','ico'=>'<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
                ['label'=>'Humanised','big'=>$insights['humanised'],'suffix'=>'/'.$insights['draft_count'],'note'=>$insights['humanised']==0?'no drafts humanised yet':'drafts humanised','tint'=>'purple','ico'=>'<path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/>'],
                ['label'=>'Content Structure','big'=>$insights['rich_structure'],'suffix'=>' rich','note'=>'avg '.number_format($insights['avg_words']).' words per draft','tint'=>'emerald','ico'=>'<path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3M3 8h18M3 8v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8"/>'],
                ['label'=>'Scheduled','big'=>$insights['scheduled'],'suffix'=>'','note'=>$insights['scheduled']==0?'none upcoming':'upcoming in queue','tint'=>'rose','ico'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
            ];
            $tintMap = ['amber'=>'bg-amber-50 text-amber-600','blue'=>'bg-blue-50 text-blue-600','purple'=>'bg-purple-50 text-purple-600','emerald'=>'bg-emerald-50 text-emerald-600','rose'=>'bg-rose-50 text-rose-600'];
        @endphp
        @foreach($insightTiles as $t)
            <div class="rounded-xl border border-slate-100 p-4 bg-slate-50/40">
                <div class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide {{ explode(' ',$tintMap[$t['tint']])[1] }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $t['ico'] !!}</svg>
                    {{ $t['label'] }}
                </div>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $t['big'] }}<span class="text-base font-semibold text-slate-400">{{ $t['suffix'] }}</span></p>
                <p class="text-[11px] text-slate-400 mt-1">{{ $t['note'] }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- Block Mix / Next 7 days / Publishing Health --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Block mix donut --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-center gap-2 mb-4">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            <h3 class="font-bold text-slate-900">Block Mix</h3>
            <span class="ml-auto text-xs text-slate-400">{{ $blockTotal }} total</span>
        </div>
        @php $palette = ['#6d5efc','#22c55e','#f59e0b','#ef4444','#0ea5e9','#a855f7']; @endphp
        <div class="flex items-center gap-5">
            <div class="relative w-32 h-32 shrink-0">
                @php
                    $cum = 0; $seg = '';
                    foreach($blockMix as $idx=>$b){ $start=$cum; $cum += $b['pct']; $seg .= $palette[$idx%6]." ".$start."% ".$cum."%,"; }
                    $seg = rtrim($seg, ',');
                @endphp
                <div class="w-32 h-32 rounded-full" style="background:conic-gradient({{ $seg }})"></div>
                <div class="absolute inset-[18px] bg-white rounded-full grid place-items-center">
                    <div class="text-center"><p class="text-2xl font-extrabold text-slate-900 leading-none">{{ count($blockMix) }}</p><p class="text-[10px] text-slate-400 uppercase tracking-wide">Types</p></div>
                </div>
            </div>
            <div class="flex-1 space-y-1.5">
                @foreach($blockMix as $idx=>$b)
                    <div class="flex items-center gap-2 text-[13px]">
                        <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $palette[$idx%6] }}"></span>
                        <span class="text-slate-600 flex-1">{{ $b['type'] }}</span>
                        <span class="text-slate-800 font-semibold">{{ $b['count'] }} <span class="text-slate-400 font-normal">({{ $b['pct'] }}%)</span></span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Next 7 days --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-center gap-2 mb-4">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <h3 class="font-bold text-slate-900">Next 7 Days</h3>
        </div>
        <div class="grid grid-cols-7 gap-1.5">
            @php $start = \Illuminate\Support\Carbon::create(2026,9,25); @endphp
            @for($i=0;$i<7;$i++)
                @php $d = $start->copy()->addDays($i); $sched = $posts->filter(fn($p)=>$p->scheduled_at && $p->scheduled_at->isSameDay($d))->count(); @endphp
                <div class="text-center">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase">{{ $d->format('D') }}</p>
                    <div class="mt-1 aspect-square rounded-lg grid place-items-center text-sm font-bold {{ $sched>0 ? 'bg-brand-50 text-brand-700' : 'bg-slate-50 text-slate-300' }}">{{ $sched }}</div>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $d->format('j') }}</p>
                </div>
            @endfor
        </div>
    </div>

    {{-- Publishing health --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-center gap-2 mb-4">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            <h3 class="font-bold text-slate-900">Publishing Health</h3>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100">
            <span class="w-8 h-8 rounded-lg grid place-items-center text-white text-[11px] font-bold" style="background:{{ $brand->color }}">{{ $brand->initials }}</span>
            <span class="text-sm font-semibold text-slate-700 flex-1">{{ $brand->name }}</span>
            <span class="text-xs text-slate-400">{{ $brand->last_synced_at?->diffForHumans(null, true) }} ago</span>
        </div>
    </div>
</div>

{{-- Up next banner --}}
@if($upNext)
<div class="rounded-2xl bg-slate-900 text-white p-5 flex flex-wrap items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 grid place-items-center shrink-0">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg>
    </div>
    <div class="flex-1 min-w-[260px]">
        <p class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Up next in your workflow</p>
        <p class="font-bold mt-0.5">{{ $upNext->title }}</p>
        <p class="text-xs text-slate-400 mt-0.5">Currently: <span class="text-slate-200">Planned</span> · Updated {{ $upNext->updated_at->format('n/j/Y') }}</p>
    </div>
    <a href="{{ route('editor', ['brand'=>$brand->id,'post'=>$upNext->id]) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 font-semibold text-sm transition">
        Continue in Editor
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
</div>
@endif
