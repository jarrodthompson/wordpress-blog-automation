<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Pipeline breakdown (main) --}}
    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h3 class="font-bold text-slate-900">Pipeline Breakdown</h3>
                <p class="text-sm text-slate-500 mt-0.5">Real-time snapshot of where posts sit in the workflow — {{ $pipeline->count() }} items.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 text-xs text-slate-500 px-3 py-1.5 rounded-lg border border-slate-200">Last updated
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></span>
                <a href="{{ route('blog-manager', ['brand'=>$brand->id]) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">View All ({{ $pipeline->count() }})
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
            @foreach($pipeline->take(6) as $p)
                @php
                    $badge = ['planned'=>['PLANNED','bg-slate-100 text-slate-500'],'writing'=>['WRITING','bg-indigo-100 text-indigo-600'],'review'=>['REVIEW','bg-amber-100 text-amber-600'],'published'=>['PUBLISHED','bg-emerald-100 text-emerald-600']][$p->status];
                    $stageNum = $p->stageIndex()+1;
                @endphp
                <div class="rounded-xl border border-slate-200 p-4 hover:shadow-sm transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold tracking-wide text-slate-400 uppercase">{{ Str::limit($brand->name,22) }}</span>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $badge[1] }}">{{ $badge[0] }}</span>
                    </div>
                    <p class="text-[13px] font-bold text-slate-800 leading-snug line-clamp-2 min-h-[38px]">{{ $p->title }}</p>
                    <div class="grid grid-cols-4 gap-1 mt-3">
                        @foreach(['PLAN','WRITE','REVIEW','LIVE'] as $si=>$label)
                            <div>
                                <div class="h-1 rounded-full {{ $si < $stageNum ? 'bg-brand-500' : 'bg-slate-100' }}"></div>
                                <p class="text-[8px] text-slate-400 mt-1 {{ $si==0?'text-left':($si==3?'text-right':'text-center') }}">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3 mt-3 text-[11px] text-slate-500">
                        <span class="flex items-center gap-1"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h8M8 16h4"/></svg>{{ number_format($p->word_count) }}w</span>
                        @if($p->block_count)<span class="flex items-center gap-1"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>{{ $p->block_count }}b</span>@endif
                        @if($p->scheduled_at)<span class="flex items-center gap-1 text-rose-500"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>{{ $p->scheduled_at->format('j M') }}</span>@endif
                        @if($p->status==='published')<span class="flex items-center gap-1 text-emerald-600"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>Live</span>@endif
                    </div>
                    @if($p->focus_keyword)
                        <div class="mt-2 text-[11px] text-slate-500 border border-slate-100 rounded-md px-2 py-1 bg-slate-50 truncate">{{ $p->focus_keyword }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right column: activity feed + live posts --}}
    <div class="space-y-4">
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
            <div class="flex items-center gap-2">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <h3 class="font-bold text-slate-900">Activity Feed</h3>
                <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">{{ $feed->count() }} events</span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Latest comments and post changes from each connected site.</p>
            <div class="mt-3 space-y-3 max-h-80 overflow-y-auto pr-1">
                @foreach($feed as $ev)
                    <div class="flex gap-3">
                        <div class="w-7 h-7 shrink-0 rounded-lg grid place-items-center {{ $ev->type==='edited' ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500' }}">
                            @if($ev->type==='edited')
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] leading-snug"><span class="font-semibold text-slate-700">{{ $ev->type==='edited'?'Edited draft':'Published' }}</span> <span class="text-slate-600">{{ Str::limit($ev->title, 70) }}</span></p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $brand->name }} · {{ $ev->occurred_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-card">
            <div class="flex items-center gap-2">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20"/></svg>
                <h3 class="font-bold text-slate-900">Live WordPress Posts</h3>
                <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">{{ $livePosts->count() }} live</span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Recent posts pulled straight from each connected site. Click a post to import it into the editor.</p>
            <div class="mt-3 space-y-2 max-h-80 overflow-y-auto pr-1">
                @foreach($livePosts as $lp)
                    <a href="{{ route('editor', ['brand'=>$brand->id,'post'=>$lp->id]) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50">
                        <span class="w-8 h-8 shrink-0 rounded-lg grid place-items-center text-white text-[10px] font-bold" style="background:{{ $brand->color }}">{{ $brand->initials }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-semibold text-slate-700 truncate">{{ $lp->title }}</p>
                            <p class="text-[11px] text-slate-400">{{ $brand->name }} · {{ $lp->published_at?->format('n/j/Y') }}</p>
                        </div>
                        <span class="text-[10px] text-brand-600 font-semibold shrink-0">view live ↗</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
