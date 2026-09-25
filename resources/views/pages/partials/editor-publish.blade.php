{{-- ===== PUBLISH ===== --}}
@php $isLive = $post->status === 'published'; $slug = $post->slug; @endphp
<div x-show="tab==='publish'" x-cloak class="rounded-2xl bg-white border border-slate-200 shadow-card p-6">
    <div class="flex items-center gap-2 mb-1">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2Z"/><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91 0Z"/></svg>
        <h2 class="text-lg font-bold text-slate-900">Launch to WordPress</h2>
    </div>
    <p class="text-sm text-slate-500">Confirm the pre-flight checklist, then sync to the live site.</p>

    @if($isLive)
    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5"></span>
            <div>
                <p class="font-bold text-emerald-800 flex items-center gap-1.5"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg> LIVE on the site — visitors can see this post</p>
                <p class="text-[12px] text-emerald-700/80 mt-1">Post #{{ $post->wp_post_id }} · {{ Str::limit($slug, 52) }} · <a href="#" class="underline font-semibold">view live post ↗</a> · synced {{ $post->wp_synced_at?->format('n/j/Y, g:i:s A') }}</p>
            </div>
        </div>
        <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-emerald-200 bg-white text-emerald-700 text-sm font-semibold shrink-0"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8"/></svg> Refresh status</button>
    </div>
    @endif

    <div class="mt-4 space-y-2">
        @foreach($checklist as $c)
            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100">
                <span class="text-sm font-medium text-slate-700">{{ $c['label'] }}</span>
                @if($c['ready'])
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Ready</span>
                @else
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">Needs work</span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-5 flex items-center gap-3">
        <button class="flex items-center gap-2 px-5 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8"/></svg>
            {{ $isLive ? 'Re-sync to WordPress' : 'Publish to WordPress' }}
        </button>
        <button class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Save as draft</button>
    </div>
</div>
