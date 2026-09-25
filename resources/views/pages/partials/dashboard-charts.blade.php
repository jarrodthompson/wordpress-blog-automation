{{-- Content Activity + Traffic --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Content activity line chart --}}
    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div>
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    <h3 class="font-bold text-slate-900">Content Activity</h3>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Posts published vs drafts edited per day, straight from each site's WP REST API.</p>
            </div>
            <div class="flex rounded-lg border border-slate-200 overflow-hidden text-xs font-semibold">
                <button class="px-3 py-1.5 text-slate-500 hover:bg-slate-50">7D</button>
                <button class="px-3 py-1.5 bg-slate-100 text-slate-800">14D</button>
                <button class="px-3 py-1.5 text-slate-500 hover:bg-slate-50">30D</button>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 mt-3">
            <span class="text-[11px] font-semibold px-2 py-1 rounded-md bg-brand-50 text-brand-700">{{ $activity['published'] }} published</span>
            <span class="text-[11px] font-semibold px-2 py-1 rounded-md bg-emerald-50 text-emerald-700">{{ $activity['edited'] }} drafts edited</span>
            <span class="text-[11px] font-semibold px-2 py-1 rounded-md bg-slate-100 text-slate-600">Best day: {{ $activity['best']['published'] ?? 0 }} on {{ $activity['best']['label'] ?? '—' }}</span>
        </div>
        <div class="mt-3 h-56"><canvas id="activityChart"></canvas></div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
            @php
                $trend = $activity['published']>0 ? 0 : -100;
                $mini = [
                    ['v'=>$activity['published'],'l'=>'Published · 14D','c'=>'bg-brand-50 text-brand-700'],
                    ['v'=>$activity['edited'],'l'=>'Drafts edited · 14D','c'=>'bg-emerald-50 text-emerald-700'],
                    ['v'=>$trend.'%','l'=>'Trend','c'=>'bg-rose-50 text-rose-600'],
                    ['v'=>'0d','l'=>'Active streak','c'=>'bg-slate-50 text-slate-600'],
                ];
            @endphp
            @foreach($mini as $m)
                <div class="rounded-xl px-3 py-2.5 {{ $m['c'] }}"><p class="text-xl font-extrabold">{{ $m['v'] }}</p><p class="text-[10px] font-semibold uppercase tracking-wide opacity-70 mt-0.5">{{ $m['l'] }}</p></div>
            @endforeach
        </div>
    </div>

    {{-- Traffic & engagement --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h3 class="font-bold text-slate-900">Traffic & Engagement</h3>
            <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">LIVE signals</span>
        </div>
        <p class="text-sm text-slate-500 mt-0.5">Server-measured audience signals pulled from each site.</p>
        <div class="grid grid-cols-2 gap-3 mt-4">
            @php $t=[['0','Total comments','slate'],[$brand->metric('wp_comments'),'New comments · 30D','blue'],['0','In moderation queue','amber'],[$brand->metric('posts_per_month'),'Posts / month · avg','emerald']]; @endphp
            @foreach($t as [$v,$l,$col])
                <div class="rounded-xl border border-slate-100 p-3">
                    <p class="text-2xl font-extrabold {{ ['slate'=>'text-slate-800','blue'=>'text-blue-600','amber'=>'text-amber-600','emerald'=>'text-emerald-600'][$col] }}">{{ $v }}</p>
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5">{{ $l }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-4 rounded-xl border border-slate-100 p-3">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg grid place-items-center text-white text-[10px] font-bold" style="background:{{ $brand->color }}">{{ $brand->initials }}</span>
                <div><p class="text-sm font-semibold text-slate-800">{{ $brand->name }}</p><p class="text-[11px] text-slate-400">{{ $brand->domain }}</p></div>
            </div>
            <div class="grid grid-cols-4 gap-2 mt-3 text-center">
                @foreach([['0','Comments'],['0','Last 30d'],['0','Mod queue'],[$brand->metric('posts_per_month'),'Posts/mo']] as [$v,$l])
                    <div class="rounded-lg bg-slate-50 py-2"><p class="text-sm font-bold text-slate-800">{{ $v }}</p><p class="text-[9px] text-slate-400 uppercase">{{ $l }}</p></div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Publishing cadence --}}
<div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
    <div class="flex items-center gap-2">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-emerald-600"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
        <h3 class="font-bold text-slate-900">Publishing Cadence</h3>
        <span class="ml-2 text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">{{ $brand->metric('wp_published') }} posts in the last 12 months</span>
    </div>
    <p class="text-sm text-slate-500 mt-0.5">Real posts published per month, straight from each site's WP post dates.</p>
    <div class="flex items-end gap-2 h-48 mt-5">
        @foreach($cadence['months'] as $mo)
            <div class="flex-1 flex flex-col items-center gap-1.5 group">
                <div class="w-full flex items-end justify-center" style="height:150px">
                    <div class="w-full max-w-[34px] rounded-t-md bg-emerald-500/90 group-hover:bg-emerald-500 transition-all relative"
                         style="height:{{ $mo['count']>0 ? max(8, $mo['count']/$cadence['max']*100) : 2 }}%">
                        @if($mo['count']>0)<span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-bold text-slate-500">{{ $mo['count'] }}</span>@endif
                    </div>
                </div>
                <span class="text-[10px] text-slate-400">{{ $mo['label'] }}</span>
            </div>
        @endforeach
    </div>
    <div class="grid grid-cols-3 gap-3 mt-4">
        <div class="rounded-xl bg-amber-50 px-4 py-3"><p class="text-2xl font-extrabold text-amber-600">{{ $cadence['avg'] }}</p><p class="text-[10px] font-semibold uppercase tracking-wide text-amber-700/70">Avg / month</p></div>
        <div class="rounded-xl bg-indigo-50 px-4 py-3"><p class="text-2xl font-extrabold text-indigo-600">{{ $cadence['best']['count'] ?? 0 }} <span class="text-xs font-semibold">in {{ $cadence['best']['label'] ?? '—' }}</span></p><p class="text-[10px] font-semibold uppercase tracking-wide text-indigo-700/70">Best month</p></div>
        <div class="rounded-xl bg-rose-50 px-4 py-3"><p class="text-2xl font-extrabold text-rose-600">{{ $cadence['active_count'] }}/12</p><p class="text-[10px] font-semibold uppercase tracking-wide text-rose-700/70">Active months</p></div>
    </div>
</div>

{{-- Site performance --}}
@php $m = $brand->metrics ?? []; $ttfb = $brand->metric('ttfb'); @endphp
<div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
    <div class="flex items-center gap-2">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-slate-500"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        <h3 class="font-bold text-slate-900">Site Performance & Speed</h3>
    </div>
    <p class="text-sm text-slate-500 mt-0.5">Real HTTP measurements of each homepage — response time, page weight, compression — with a server-measured A–F grade.</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
        <div class="rounded-xl border border-slate-100 p-4"><p class="text-2xl font-extrabold text-rose-600">{{ $ttfb }}ms</p><p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5">Avg TTFB</p></div>
        <div class="rounded-xl border border-slate-100 p-4"><p class="text-2xl font-extrabold text-emerald-600">{{ $ttfb }}ms</p><p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5">Fastest · {{ Str::limit($brand->name,20) }}</p></div>
        <div class="rounded-xl border border-slate-100 p-4"><p class="text-2xl font-extrabold text-rose-600">{{ $ttfb }}ms</p><p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5">Slowest · {{ Str::limit($brand->name,20) }}</p></div>
        <div class="rounded-xl border border-slate-100 p-4"><p class="text-2xl font-extrabold text-slate-800">1<span class="text-slate-400 text-lg">/1</span></p><p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5">Sites live</p></div>
    </div>

    <div class="mt-4 rounded-xl border border-slate-100 p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg grid place-items-center text-white text-[10px] font-bold" style="background:{{ $brand->color }}">{{ $brand->initials }}</span>
                <div><p class="text-sm font-semibold text-slate-800">{{ $brand->name }}</p><p class="text-[11px] text-slate-400">{{ $brand->domain }}</p></div>
            </div>
            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> LIVE</span>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="relative w-16 h-16 shrink-0">
                <div class="w-16 h-16 rounded-full" style="background:conic-gradient(#ef4444 0% {{ $brand->metric('score') }}%, #f1f5f9 {{ $brand->metric('score') }}% 100%)"></div>
                <div class="absolute inset-[6px] bg-white rounded-full grid place-items-center text-center">
                    <div><p class="text-lg font-extrabold text-rose-600 leading-none">{{ $brand->metric('grade') }}</p><p class="text-[8px] text-slate-400">{{ $brand->metric('score') }}/100</p></div>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 flex-1 min-w-[260px]">
                @foreach([[$ttfb.'ms','TTFB','text-rose-600'],[$brand->metric('wp_api').'ms','WP API','text-emerald-600'],[$brand->metric('page_kb').' KB','Page','text-slate-800'],[$brand->metric('wp_published'),'Posts','text-slate-800']] as [$v,$l,$c])
                    <div class="text-center rounded-lg bg-slate-50 py-2.5"><p class="text-sm font-bold {{ $c }}">{{ $v }}</p><p class="text-[9px] text-slate-400 uppercase">{{ $l }}</p></div>
                @endforeach
            </div>
        </div>
        <div class="mt-4 space-y-2">
            <div>
                <div class="flex justify-between text-[11px] mb-1"><span class="text-slate-500">Server response (TTFB)</span><span class="text-rose-600 font-semibold">{{ $ttfb }}ms / 600ms target</span></div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-rose-500 rounded-full" style="width:100%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-[11px] mb-1"><span class="text-slate-500">Page weight</span><span class="text-slate-500">{{ $brand->metric('page_kb') }} KB · {{ $brand->metric('transfer_kb') }} KB transfer · −{{ $brand->metric('gzip') }}% gzip</span></div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-brand-500 rounded-full" style="width:30%"></div></div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-4 text-[11px]">
            <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600">☁ {{ $brand->metric('cdn') }}</span>
            <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600">Server · {{ $brand->metric('server') }}</span>
            <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-600">Compression · −{{ $brand->metric('gzip') }}%</span>
            <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600">No analytics detected</span>
            <span class="ml-auto text-slate-400">{{ $brand->metric('scripts') }} scripts · {{ $brand->metric('styles') }} styles · {{ $brand->metric('imgs') }} imgs ({{ $brand->metric('lazy') }} lazy) · {{ $brand->metric('fonts') }} fonts</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function initActivityChart(){
    const el = document.getElementById('activityChart');
    if(!el || typeof Chart === 'undefined') return;
    const days = @json(collect($activity['days'])->pluck('label'));
    const published = @json(collect($activity['days'])->pluck('published'));
    const edited = @json(collect($activity['days'])->pluck('edited'));
    new Chart(el, {
        type:'line',
        data:{ labels:days, datasets:[
            {label:'Published', data:published, borderColor:'#6d5efc', backgroundColor:'rgba(109,94,252,.08)', tension:.4, fill:true, pointRadius:3, pointBackgroundColor:'#fff', pointBorderColor:'#6d5efc', pointBorderWidth:2},
            {label:'Drafts edited', data:edited, borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,.06)', tension:.4, fill:true, pointRadius:3, pointBackgroundColor:'#fff', pointBorderColor:'#22c55e', pointBorderWidth:2},
        ]},
        options:{ responsive:true, maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{ y:{beginAtZero:true, ticks:{stepSize:1, color:'#94a3b8'}, grid:{color:'#f1f5f9'}}, x:{ticks:{color:'#94a3b8'}, grid:{display:false}} } }
    });
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initActivityChart);
} else {
    initActivityChart();
}
</script>
@endpush
