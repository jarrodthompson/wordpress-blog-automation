{{-- ===== SEO ===== --}}
<div x-show="tab==='seo'" x-cloak class="rounded-2xl bg-white border border-slate-200 shadow-card p-6">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Optimise &amp; Refine</h2>
            <p class="text-sm text-slate-500">Live on-page audit against Google's current guidelines, with one-click fixes.</p>
        </div>
        <span class="text-sm font-bold px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 border border-amber-100">Score: {{ $seoAudit['score'] }}</span>
    </div>

    <div class="mt-5 flex items-center gap-5">
        @php $score = $seoAudit['score']; $col = $score>=75?'#f59e0b':($score>=60?'#f59e0b':'#ef4444'); @endphp
        <div class="relative w-20 h-20 shrink-0">
            <div class="w-20 h-20 rounded-full" style="background:conic-gradient({{ $col }} 0% {{ $score }}%, #f1f5f9 {{ $score }}% 100%)"></div>
            <div class="absolute inset-[7px] bg-white rounded-full grid place-items-center"><span class="text-2xl font-extrabold text-slate-800">{{ $score }}</span></div>
        </div>
        <div class="flex-1">
            <p class="font-bold text-slate-900">{{ $seoAudit['verdict'] }}</p>
            <p class="text-sm text-slate-500 mt-0.5">
                <span class="text-emerald-600 font-semibold">{{ $seoAudit['passing'] }} passing</span> ·
                <span class="text-amber-600 font-semibold">{{ $seoAudit['improve'] }} to improve</span> ·
                <span class="text-rose-600 font-semibold">{{ $seoAudit['fix'] }} to fix</span> ·
                <span class="text-slate-400">{{ $seoAudit['na'] }} n/a</span>
            </p>
        </div>
        <button class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8"/></svg> Re-run</button>
    </div>

    <div class="mt-5 rounded-xl border border-slate-100 p-4">
        <div class="flex items-center justify-between mb-3">
            <span class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wide text-slate-400"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg> Advanced Refine</span>
            <button class="text-[11px] text-slate-400 flex items-center gap-1">Options <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></button>
        </div>
        <div class="flex flex-wrap gap-2" x-data="{ action: 'Fix issues' }">
            @foreach(['Fix issues','Shorten','Simplify','Expand','Add FAQ','Add links','E-E-A-T','Custom...'] as $a)
                <button @click="action='{{ $a }}'" :class="action==='{{ $a }}' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition">{{ $a }}</button>
            @endforeach
            <button class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold flex items-center justify-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg>
                Run <span x-text="action"></span>
            </button>
        </div>
        <div class="flex flex-wrap gap-2 mt-3">
            @foreach($seoAudit['categories'] as $c)
                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md {{ $c['count']>0 ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-500' }}">{{ $c['label'] }}@if($c['count']>0) · {{ $c['count'] }}@endif</span>
            @endforeach
        </div>
    </div>

    <div class="mt-4 rounded-xl bg-brand-50/50 border border-brand-100 p-4 flex items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-white grid place-items-center text-brand-600 shrink-0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg></div>
            <div>
                <p class="font-bold text-slate-800 text-sm">Fix with AI + humanise</p>
                <p class="text-[12px] text-slate-500 mt-0.5 leading-snug max-w-2xl">Audits the article against every noted issue, fixes them using best practices (keyphrase, meta title, description, slug, content), humanises the result, then re-tests and shows the new score — ready to publish to live or draft.</p>
            </div>
        </div>
        <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold shrink-0">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg> Fix now
        </button>
    </div>
</div>
