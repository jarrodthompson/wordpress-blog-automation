{{-- ===== BRIEF ===== --}}
<div x-show="tab==='brief'" x-cloak class="rounded-2xl bg-white border border-slate-200 shadow-card p-6">
    <div class="flex items-start justify-between mb-5">
        <div class="flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Plan the Post</h2>
                <p class="text-sm text-slate-500">Set the angle, keywords and target length before writing.</p>
            </div>
        </div>
        <button @click="tab='write'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">
            Start Writing <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="text-sm font-semibold text-slate-700">Content Type</label>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-[11px] font-bold px-2.5 py-2 rounded-lg bg-brand-50 text-brand-700">BLOG POST</span>
                <select class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    <option>default</option><option>listicle</option><option>how-to guide</option><option>product round-up</option>
                </select>
            </div>
        </div>
        <div>
            <div class="flex items-center justify-between">
                <label class="text-sm font-semibold text-slate-700">Focus Keyphrase</label>
                <button class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg> Suggest Keywords
                </button>
            </div>
            <input value="{{ $post->focus_keyword }}" class="mt-2 w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
        </div>
    </div>

    <div class="mt-6">
        <label class="text-sm font-semibold text-slate-700">Secondary Keywords</label>
        <div class="mt-2 flex gap-2">
            <input placeholder="Add keyword..." class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
            <button class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> Add
            </button>
        </div>
        <p class="text-[11px] text-slate-400 mt-1.5">Press Enter to add. These are used for SEO density checks and refinement.</p>
        @if($post->secondary_keywords)
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach($post->secondary_keywords as $kw)
                    <span class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-600">{{ $kw }}
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-6">
        <label class="text-sm font-semibold text-slate-700">SEO Brief</label>
        <textarea rows="4" class="mt-2 w-full px-3.5 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30" placeholder="Angle, audience, search intent, must-cover points…">Target parents and grandparents searching for {{ $post->focus_keyword }}. Lead with the emotional hook of a Christmas Eve tradition, cover why hardback classics make heirloom gifts, and close with a soft CTA to the shop. Warm, nostalgic, literary tone.</textarea>
    </div>
</div>
