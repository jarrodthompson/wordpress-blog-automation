{{-- ===== SOCIAL ===== --}}
<div x-show="tab==='social'" x-cloak class="rounded-2xl bg-white border border-slate-200 shadow-card p-6">
    <div class="flex items-center gap-2 mb-1">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" class="text-brand-600"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5 8.6 10.5"/></svg>
        <h2 class="text-lg font-bold text-slate-900">Social Snippets</h2>
    </div>
    <p class="text-sm text-slate-500">Auto-generated captions from this article, ready to schedule alongside the post.</p>

    @php
        $snips = [
            ['X / Twitter', 'sky', '🎄 Some stories belong in the Christmas Eve box. Here\'s why *Jack and the Beanstalk* earns its place among your family\'s heirloom classics this festive season. 📚✨ #ChristmasEveBox #ChildrensBooks'],
            ['LinkedIn', 'blue', 'Why do certain children\'s books become family heirlooms? Our latest piece explores the ritual of the Christmas Eve box and the case for hardback classics that get read aloud for generations.'],
            ['Facebook', 'indigo', 'This Christmas, give a story that lasts. 🎁 Our new guide makes the case for adding *Jack and the Beanstalk* to your Christmas Eve box traditions — a gift that gets unwrapped again and again.'],
            ['Instagram', 'rose', 'A candle, a hardback classic, and the night before Christmas. 🕯️📖 Tap the link in bio for our full guide to Christmas Eve box books. #FreshGreenClassics #HeirloomGifts'],
        ];
        $dot = ['sky'=>'bg-sky-500','blue'=>'bg-blue-600','indigo'=>'bg-indigo-500','rose'=>'bg-rose-500'];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
        @foreach($snips as [$platform, $col, $text])
            <div class="rounded-xl border border-slate-100 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="flex items-center gap-2 text-sm font-semibold text-slate-700"><span class="w-2 h-2 rounded-full {{ $dot[$col] }}"></span>{{ $platform }}</span>
                    <button class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">Copy</button>
                </div>
                <p class="text-[13px] text-slate-600 leading-relaxed">{{ $text }}</p>
            </div>
        @endforeach
    </div>
    <button class="mt-4 flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8"/></svg> Regenerate all
    </button>
</div>
