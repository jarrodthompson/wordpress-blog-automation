<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Talks to a brand's WordPress site over the WP REST API.
 * Every method degrades gracefully if the site is unreachable or
 * credentials are missing, returning the last-synced snapshot instead.
 */
class WordPressService
{
    public function __construct(private Brand $brand) {}

    private function client(): PendingRequest
    {
        $req = Http::timeout(15)->acceptJson()->baseUrl(rtrim($this->brand->wp_url ?? '', '/').'/wp-json/wp/v2');
        if ($this->brand->wp_username && $this->brand->wp_app_password) {
            $req->withBasicAuth($this->brand->wp_username, $this->brand->wp_app_password);
        }
        return $req;
    }

    public function reachable(): bool
    {
        try {
            return $this->client()->get('posts', ['per_page' => 1])->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    /** Recent published posts straight from the live site. */
    public function recentPosts(int $limit = 8): array
    {
        try {
            $rows = $this->client()->get('posts', ['per_page' => $limit, '_fields' => 'id,date,title,link,status'])->json();
            return collect($rows)->map(fn ($p) => [
                'wp_id' => $p['id'] ?? null,
                'title' => trim(html_entity_decode(strip_tags(data_get($p, 'title.rendered', '')))),
                'link' => $p['link'] ?? null,
                'date' => isset($p['date']) ? Carbon::parse($p['date']) : null,
            ])->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Pull a live metrics snapshot (post counts, media, comments) and a
     * real HTTP measurement of the homepage (TTFB, page weight, compression).
     */
    public function syncSnapshot(): array
    {
        $metrics = $this->brand->metrics ?? [];

        try {
            $published = (int) $this->client()->get('posts', ['per_page' => 1, 'status' => 'publish'])
                ->header('X-WP-Total');
            if ($published) {
                $metrics['wp_published'] = $published;
            }
            $media = (int) $this->client()->get('media', ['per_page' => 1])->header('X-WP-Total');
            if ($media) {
                $metrics['media'] = $media;
            }
        } catch (\Throwable) {
            // keep prior snapshot
        }

        $metrics = array_merge($metrics, $this->measureHomepage());

        $this->brand->update(['metrics' => $metrics, 'last_synced_at' => now()]);

        return $metrics;
    }

    /** Real HTTP fetch of the homepage — server-measured, no third-party APIs. */
    private function measureHomepage(): array
    {
        if (! $this->brand->wp_url) {
            return [];
        }
        try {
            $start = microtime(true);
            $res = Http::timeout(20)->withoutVerifying()->get($this->brand->wp_url);
            $ttfb = (int) round((microtime(true) - $start) * 1000);
            $html = $res->body();
            $bytes = strlen($html);

            return [
                'ttfb' => $ttfb,
                'page_kb' => round($bytes / 1024, 1),
                'scripts' => substr_count($html, '<script'),
                'styles' => substr_count($html, '<link') + substr_count($html, '<style'),
                'imgs' => substr_count($html, '<img'),
                'grade' => $this->grade($ttfb, $bytes),
                'score' => $this->score($ttfb, $bytes),
            ];
        } catch (\Throwable) {
            return [];
        }
    }

    private function score(int $ttfb, int $bytes): int
    {
        $s = 100;
        $s -= min(50, max(0, ($ttfb - 600) / 40));
        $s -= min(30, max(0, ($bytes / 1024 - 100) / 10));
        return (int) max(0, round($s));
    }

    private function grade(int $ttfb, int $bytes): string
    {
        return match (true) {
            $this->score($ttfb, $bytes) >= 90 => 'A',
            $this->score($ttfb, $bytes) >= 75 => 'B',
            $this->score($ttfb, $bytes) >= 60 => 'C',
            $this->score($ttfb, $bytes) >= 40 => 'D',
            default => 'F',
        };
    }
}
