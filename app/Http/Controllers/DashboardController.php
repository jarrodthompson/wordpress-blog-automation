<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Post;
use App\Services\BrandContext;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(BrandContext $ctx)
    {
        $brand = $ctx->current();
        abort_if(! $brand, 404, 'No brand configured. Run the seeder.');

        $posts = Post::where('brand_id', $brand->id)->get();

        // ---- Pipeline stages ----
        $stages = [
            ['key' => 'planned',   'label' => 'Plan & Research',   'count' => $posts->where('status', 'planned')->count(),   'color' => 'blue',    'icon' => 'bulb'],
            ['key' => 'writing',   'label' => 'In Writing',        'count' => $posts->where('status', 'writing')->count(),   'color' => 'indigo',  'icon' => 'pen'],
            ['key' => 'review',    'label' => 'Review & Optimise', 'count' => $posts->where('status', 'review')->count(),    'color' => 'amber',   'icon' => 'search'],
            ['key' => 'published', 'label' => 'Published Live',    'count' => $posts->where('status', 'published')->count(), 'color' => 'emerald', 'icon' => 'rocket'],
        ];

        // ---- Hero stat cards (WP-synced snapshot) ----
        $hero = [
            'published' => (int) $brand->metric('wp_published'),
            'drafts' => (int) $brand->metric('wp_drafts', $posts->whereIn('status', ['review', 'writing'])->count()),
            'comments' => (int) $brand->metric('wp_comments'),
            'media' => (int) $brand->metric('media'),
        ];

        // ---- Pipeline insights ----
        $generated = $posts->whereNotNull('gen_time_seconds');
        $drafts = $posts->whereIn('status', ['writing', 'review', 'published']);
        $insights = [
            'grammar_ready' => (int) $brand->metric('grammar_ready'),
            'grammar_rules' => (int) $brand->metric('grammar_rules', 1),
            'avg_gen_time' => (int) round($generated->avg('gen_time_seconds') ?: 0),
            'generated_count' => $generated->count(),
            'humanised' => $drafts->where('humanised', true)->whereIn('status', ['review', 'writing'])->count(),
            'draft_count' => $posts->whereIn('status', ['review', 'writing'])->count() ?: 5,
            'rich_structure' => (int) round($posts->where('block_count', '>', 0)->avg('block_count') ?: 0),
            'avg_words' => (int) round($posts->where('word_count', '>', 100)->avg('word_count') ?: 0),
            'scheduled' => $posts->whereNotNull('scheduled_at')->count(),
        ];

        // ---- Block mix donut ----
        $blockCounts = [];
        foreach ($posts as $p) {
            foreach (($p->blocks ?? []) as $b) {
                $type = $b['type'] ?? 'Paragraph';
                $blockCounts[$type] = ($blockCounts[$type] ?? 0) + 1;
            }
        }
        arsort($blockCounts);
        $blockTotal = array_sum($blockCounts) ?: 1;
        $blockMix = collect($blockCounts)->take(6)->map(fn ($n, $type) => [
            'type' => $type, 'count' => $n, 'pct' => round($n / $blockTotal * 100),
        ])->values()->all();

        // ---- Content activity (last 14 days) ----
        $activity = $this->contentActivity($brand->id);

        // ---- Publishing cadence (12 months) ----
        $cadence = $this->publishingCadence($posts);

        // ---- Feeds ----
        $feed = Activity::where('brand_id', $brand->id)->orderByDesc('occurred_at')->limit(9)->get();
        $livePosts = $posts->where('status', 'published')->sortByDesc('published_at')->take(8)->values();
        $pipeline = $posts->sortByDesc('updated_at')->values();
        $upNext = $posts->where('status', 'planned')->sortByDesc('updated_at')->first();

        return view('pages.dashboard', compact(
            'brand', 'posts', 'stages', 'hero', 'insights', 'blockMix', 'blockTotal',
            'activity', 'cadence', 'feed', 'livePosts', 'pipeline', 'upNext'
        ));
    }

    private function contentActivity(int $brandId): array
    {
        $days = collect(range(13, 0))->map(function ($d) use ($brandId) {
            $date = Carbon::create(2026, 9, 25)->subDays($d);
            return [
                'label' => $date->format('D'),
                'date' => $date->format('j'),
                'published' => Activity::where('brand_id', $brandId)->where('type', 'published')
                    ->whereDate('occurred_at', $date)->count(),
                'edited' => Activity::where('brand_id', $brandId)->where('type', 'edited')
                    ->whereDate('occurred_at', $date)->count(),
            ];
        });

        return [
            'days' => $days->all(),
            'published' => $days->sum('published'),
            'edited' => $days->sum('edited'),
            'best' => $days->sortByDesc('published')->first(),
        ];
    }

    private function publishingCadence($posts): array
    {
        $months = collect(range(11, 0))->map(function ($m) use ($posts) {
            $date = Carbon::create(2026, 9, 1)->subMonths($m);
            $count = $posts->filter(fn ($p) => $p->published_at && $p->published_at->isSameMonth($date))->count();
            return ['label' => $date->format('M'), 'count' => $count];
        });

        $active = $months->where('count', '>', 0);

        return [
            'months' => $months->all(),
            'avg' => round($months->avg('count'), 1),
            'best' => $months->sortByDesc('count')->first(),
            'active_count' => $active->count(),
            'max' => max($months->max('count'), 1),
        ];
    }
}
