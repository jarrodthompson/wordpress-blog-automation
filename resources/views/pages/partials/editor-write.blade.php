{{-- ===== WRITE ===== --}}
<div x-show="tab==='write'" x-cloak class="space-y-4">
    {{-- Generate / Enhance toggle --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-1.5 grid grid-cols-2 gap-1.5">
        <button @click="mode='generate'" :class="mode==='generate' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50'" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg> Generate New
        </button>
        <button @click="mode='enhance'" :class="mode==='enhance' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50'" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Enhance Existing
        </button>
    </div>

    {{-- Controls --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center gap-1.5 mb-4">
            <button @click="view='blocks'" :class="view==='blocks' ? 'bg-slate-100 text-slate-800' : 'text-slate-400 hover:bg-slate-50'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Blocks
            </button>
            <button @click="view='html'" :class="view==='html' ? 'bg-slate-100 text-slate-800' : 'text-slate-400 hover:bg-slate-50'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/></svg> HTML
            </button>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <select class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white"><option>Gemini Flash 3.5</option><option>Gemini Pro 3.5</option></select>
            <select class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white"><option>Length: SEO Recommended · 900</option><option>Length: Long · 1500</option><option>Length: Short · 500</option></select>
            <select class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white"><option>Blocks: Auto</option><option>Blocks: Manual</option></select>
            <button class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 0 0-5 5v6a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5Z"/></svg> Humanise draft</button>
            <button class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8"/></svg> Rebuild styled article</button>
            <button class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/></svg> Regenerate social</button>
            <label class="flex items-center gap-1.5 px-2 text-sm text-slate-600"><input type="checkbox" checked class="rounded text-brand-600"> Auto social</label>
            <form method="POST" action="{{ route('editor.generate', ['brand'=>$brand->id]) }}" class="ml-auto">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="hidden" name="topic" value="{{ $post->title }}">
                <input type="hidden" name="keyword" value="{{ $post->focus_keyword }}">
                <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg> Auto-Write
                </button>
            </form>
        </div>
    </div>

    {{-- Word count + article --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-card p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <span class="flex items-center gap-2 text-sm font-semibold text-slate-600"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Word count</span>
            <span class="text-sm font-bold text-slate-800">{{ number_format($post->word_count) }} words</span>
        </div>

        {{-- Blocks view --}}
        <div x-show="view==='blocks'" class="mt-4 space-y-2">
            @forelse($post->blocks ?? [] as $b)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200">
                    <span class="text-[10px] font-bold px-2 py-1 rounded bg-slate-100 text-slate-500 w-24 text-center shrink-0">{{ strtoupper($b['type'] ?? 'Paragraph') }}</span>
                    <p class="text-sm text-slate-600 truncate">{{ $b['text'] ?? '—' }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-6 text-center">No blocks yet — run Auto-Write to generate the article.</p>
            @endforelse
        </div>

        {{-- HTML view --}}
        <div x-show="view==='html'" x-cloak class="mt-4">
            <textarea rows="16" class="w-full px-3.5 py-3 rounded-xl border border-slate-200 text-xs font-mono leading-relaxed focus:outline-none focus:ring-2 focus:ring-brand-500/30">{{ $post->body }}</textarea>
        </div>
    </div>
</div>
