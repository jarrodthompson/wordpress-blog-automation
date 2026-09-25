@php
    $nav = [
        ['section' => 'Overview', 'items' => [
            ['label'=>'Dashboard','route'=>'dashboard','icon'=>'grid','badge'=>'61','badgeClass'=>'bg-brand-50 text-brand-700'],
        ]],
        ['section' => 'Production', 'items' => [
            ['label'=>'Blog Editor','route'=>'editor','icon'=>'pen'],
            ['label'=>'Blog Manager','route'=>'blog-manager','icon'=>'list','badge'=>(string)($postCount ?? 92),'badgeClass'=>'bg-slate-100 text-slate-600'],
            ['label'=>'AI Image Generator','route'=>'ai-image','icon'=>'sparkles','badge'=>'Visuals','badgeClass'=>'bg-amber-50 text-amber-600'],
        ]],
        ['section' => 'Distribution', 'items' => [
            ['label'=>'CRM & Automations','route'=>'crm','icon'=>'mail','badge'=>'Sync','badgeClass'=>'bg-slate-100 text-slate-600'],
            ['label'=>'Scoreboard & Orders','route'=>'scoreboard','icon'=>'chart','badge'=>'Live','badgeClass'=>'bg-emerald-50 text-emerald-600'],
            ['label'=>'Product Manager','route'=>'products','icon'=>'box','badge'=>'CRUD','badgeClass'=>'bg-orange-50 text-orange-600'],
            ['label'=>'AutoBlog Scheduler','route'=>'autoblog','icon'=>'calendar','badge'=>'Auto','badgeClass'=>'bg-purple-50 text-purple-600'],
        ]],
        ['section' => 'Administration', 'items' => [
            ['label'=>'Activity Log','route'=>'activity','icon'=>'activity'],
            ['label'=>'Feature Tracker','route'=>'features','icon'=>'check','badge'=>'6','badgeClass'=>'bg-slate-100 text-slate-600'],
            ['label'=>'Brand DNA & Vault','route'=>'brand-dna','icon'=>'shield'],
            ['label'=>'Settings & Configs','route'=>'settings','icon'=>'cog'],
        ]],
    ];
    $icons = [
        'grid'=>'<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'pen'=>'<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'list'=>'<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'sparkles'=>'<path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/><path d="M19 14l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8Z"/>',
        'mail'=>'<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'chart'=>'<path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="7"/><rect x="12" y="6" width="3" height="11"/><rect x="17" y="13" width="3" height="4"/>',
        'box'=>'<path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
        'calendar'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'activity'=>'<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'check'=>'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'shield'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>',
        'cog'=>'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H2a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 3.6 8a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H8a1.65 1.65 0 0 0 1-1.51V2a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V8a1.65 1.65 0 0 0 1.51 1H22a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/>',
    ];
@endphp
<aside class="w-[248px] shrink-0 bg-white border-r border-slate-200 flex flex-col h-full sticky top-0" x-data="{}">
    <div class="px-5 pt-5 pb-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 grid place-items-center shadow-sm">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 1.9 4.5L18 9.4l-4.1 1.9L12 16l-1.9-4.7L6 9.4l4.1-1.9Z"/></svg>
        </div>
        <span class="text-xl font-extrabold tracking-tight text-slate-900">FGOS</span>
    </div>
    <nav class="flex-1 overflow-y-auto px-3 pb-4 space-y-5">
        @foreach($nav as $group)
            <div>
                <p class="px-3 mb-1.5 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">{{ $group['section'] }}</p>
                <div class="space-y-0.5">
                    @foreach($group['items'] as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-lg text-[14px] font-medium transition {{ $active ? 'nav-active' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="nav-ico shrink-0 {{ $active ? '' : 'text-slate-400' }}" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$item['icon']] !!}</svg>
                            <span class="flex-1 truncate">{{ $item['label'] }}</span>
                            @if(!empty($item['badge']))
                                <span class="text-[10.5px] font-bold px-1.5 py-0.5 rounded-md {{ $item['badgeClass'] }}">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
    <div class="p-3">
        <div class="rounded-xl bg-gradient-to-br from-slate-900 to-slate-800 text-white p-4 relative overflow-hidden">
            <p class="text-[13px] font-semibold">cPanel Stack</p>
            <p class="text-[11.5px] text-slate-300 mt-1 leading-relaxed">Laravel 11 + MySQL + Gemini AI + WP REST API.</p>
            <div class="mt-3 h-1.5 rounded-full bg-white/15 overflow-hidden"><div class="h-full w-3/4 bg-brand-500"></div></div>
        </div>
    </div>
</aside>
