<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Automation;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\Feature;
use App\Models\MediaItem;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FgosSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::create(2026, 9, 25, 9, 36);

        $brand = Brand::updateOrCreate(['slug' => 'fresh-green-classics'], [
            'name' => 'Fresh Green Classics',
            'domain' => 'freshgreenclassics.co.uk',
            'wp_url' => 'https://freshgreenclassics.co.uk',
            'wp_username' => 'editor',
            'color' => '#16a34a',
            'initials' => 'FR',
            'status' => 'live',
            'voice' => 'Warm, literary and nostalgic — a curated children\'s bookshop that treats every title as an heirloom.',
            'brand_dna' => [
                'tone' => ['Warm', 'Nostalgic', 'Considered', 'Literary'],
                'audience' => 'Parents & grandparents buying meaningful gifts for young readers',
                'pillars' => ['Curation', 'Heirloom quality', 'Bravery & imagination', 'Quiet reading'],
                'keywords' => ['classic children\'s books', 'Christmas eve box books', 'hardback gift books'],
            ],
            'metrics' => [
                'wp_published' => 8, 'wp_drafts' => 1, 'wp_comments' => 0,
                'media' => 237, 'pages' => 14, 'categories' => 3, 'users' => 2,
                'posts_per_month' => 2.7, 'grammar_rules' => 1, 'grammar_ready' => 0,
                'ttfb' => 2036, 'wp_api' => 907, 'page_kb' => 145.3, 'transfer_kb' => 24.6,
                'gzip' => 83, 'score' => 40, 'grade' => 'D',
                'scripts' => 63, 'styles' => 40, 'imgs' => 21, 'lazy' => 14, 'fonts' => 7,
                'cdn' => 'Hostinger CDN', 'server' => 'hcdn', 'analytics' => false,
            ],
            'last_synced_at' => $now->copy()->subDays(8),
        ]);

        // ---- Posts: 7 published, 1 review, 5 planned (13 in pipeline) ----
        $published = [
            ['Wonder and Wonderment: Why Jack and the Beanstalk Belongs Among Your Christmas Eve Box Books', 1619, 12, 'Christmas eve box books', 18],
            ['The Magic of Nostalgic Christmas Reading: Sharing Jack and the Beanstalk Across Generations', 1625, 12, 'nostalgic Christmas reading', 15],
            ['Finding Quietude: Our Curated Guide to the Best Books for a Calm Evening', 1420, 10, 'best calm evening books', 14],
            ['Characters Children Never Forget', 1180, 9, 'memorable book characters', 32],
            ['Why Humour Matters in Children\'s Books', 1240, 9, 'humour in children\'s books', 32],
            ['Why Children Still Need Extraordinary Adventures', 1310, 10, 'children\'s adventure stories', 32],
            ['A Sanctuary for Stories: How to Build a Reading Nook Children Love', 1275, 11, 'children\'s reading nook', 32],
        ];
        foreach ($published as $i => [$title, $words, $blocks, $kw, $daysAgo]) {
            $gen = [96, 108, 120, 132, 101][$i % 5];
            $publishedAt = $now->copy()->subDays($daysAgo);
            $this->post($brand, $title, 'published', $words, $blocks, $kw, $now, [
                'published_at' => $publishedAt,
                'wp_synced_at' => $publishedAt->copy()->addMinutes(37),
                'humanised' => true, 'grammar_checked' => true,
                'seo_score' => [76, 82, 79, 71, 88, 74, 80][$i] ?? 76,
                'gen_time_seconds' => $gen,
                'wp_post_id' => 1600 + $i,
                'blocks' => $this->blockMix($blocks),
                'secondary_keywords' => $this->secondaryKeywords($kw),
                'generation_history' => [[
                    'label' => 'Auto-Write', 'model' => 'gemini/gemini-3.5-flash',
                    'words' => $words - 1, 'seconds' => 150.0,
                    'at' => $publishedAt->copy()->setTime(11, 23, 51)->toIso8601String(),
                ]],
            ]);
        }

        // Match the editor screenshots exactly for the flagship post.
        Post::where('brand_id', $brand->id)->where('slug', Str::slug($published[0][0]))->update([
            'initial_prompt' => "The Magic of Adding *Jack and the Beanstalk* to Your Family's Christmas Eve Box Traditions",
            'seo_score' => 76,
            'wp_post_id' => 1620,
            'published_at' => Carbon::create(2026, 9, 17, 12, 0, 34),
            'wp_synced_at' => Carbon::create(2026, 9, 17, 12, 0, 34),
            'generation_history' => [[
                'label' => 'Auto-Write', 'model' => 'gemini/gemini-3.5-flash',
                'words' => 1618, 'seconds' => 150.0,
                'at' => Carbon::create(2026, 9, 17, 11, 23, 51)->toIso8601String(),
            ]],
        ]);

        $this->post($brand, 'The Art of Literary Selection: Why We Curate Every Book With Care for Young Minds', 'review', 1490, 11, 'curated children\'s books', $now, [
            'humanised' => true, 'grammar_checked' => false, 'gen_time_seconds' => 115,
            'blocks' => $this->blockMix(11),
        ]);

        $planned = [
            ['Building a Classic Home Library: Why Our Hardback Books Make Heirloom Holiday Presents', 21, 'Christmas present ideas', 17],
            ['Escape Into the Root Realms: Why Jack and the Great Underground Trial Beats Toys This Festive Season', 25, 'buy children\'s story books', null],
            ['Give the Gift of Bravery This Christmas with Miss Mousey and Arthur\'s Brave Smile', 22, 'traditional children\'s gifts', null],
            ['Wrap Up an Adventure: Why Jack and the Great Underground Trial is the Perfect Stocking Filler', 24, 'bookish stocking fillers', null],
            ['A Sanctuary for Stories: How Independent Bookshops Keep Christmas Magic Alive', 19, 'independent bookshop Christmas', null],
        ];
        foreach ($planned as [$title, $words, $kw, $schedDay]) {
            $this->post($brand, $title, 'planned', $words, 0, $kw, $now, [
                'scheduled_at' => $schedDay ? $now->copy()->setDay($schedDay) : null,
            ]);
        }

        // ---- Media (237 assets) ----
        for ($i = 1; $i <= 237; $i++) {
            MediaItem::create([
                'brand_id' => $brand->id,
                'title' => 'asset-'.Str::padLeft((string)$i, 3, '0').'.webp',
                'url' => $brand->wp_url.'/wp-content/uploads/2026/asset-'.$i.'.webp',
                'type' => 'image',
                'page' => 'Page '.(($i % 14) + 1),
            ]);
        }

        // ---- Activity feed (matches the screenshot) ----
        $feed = [
            ['published', 'Wonder and Wonderment: Why Jack and the Beanstalk Belongs Among Your Christmas Eve Box Books', 8],
            ['published', 'The Magic of Nostalgic Christmas Reading: Sharing Jack and the Beanstalk Across Generations', 10],
            ['edited', 'The Art of Literary Selection: Why We Curate Every Book With Care for Young Minds', 10],
            ['published', 'Finding Quietude: Our Curated Guide to the Best Books for a Calm Evening', 11],
            ['published', 'Characters Children Never Forget', 32],
            ['published', 'Why Humour Matters in Children\'s Books', 32],
            ['published', 'Why Children Still Need Extraordinary Adventures', 32],
            ['published', 'A Sanctuary for Stories: How to Build a Reading Nook Children Love', 32],
            ['edited', 'Building a Classic Home Library: Why Our Hardback Books Make Heirloom Holiday Presents', 10],
        ];
        foreach ($feed as [$type, $title, $daysAgo]) {
            Activity::create([
                'brand_id' => $brand->id, 'type' => $type,
                'title' => $title, 'description' => ucfirst($type).' on the live site',
                'occurred_at' => $now->copy()->subDays($daysAgo),
            ]);
        }

        // ---- Products & orders (Scoreboard / Product Manager) ----
        $books = [
            ['Jack and the Beanstalk — Hardback', 'FGC-JB-01', 18.99, 42],
            ['Jack and the Great Underground Trial', 'FGC-JG-02', 21.50, 30],
            ['Miss Mousey and Arthur\'s Brave Smile', 'FGC-MM-03', 14.99, 55],
            ['The Christmas Eve Box Book Bundle', 'FGC-XB-04', 49.00, 18],
            ['Classic Home Library — Starter Set', 'FGC-HL-05', 89.00, 9],
        ];
        foreach ($books as [$name, $sku, $price, $stock]) {
            Product::create(compact('sku', 'price', 'stock') + ['brand_id' => $brand->id, 'name' => $name, 'status' => 'active']);
        }
        $customers = ['Amelia Clarke', 'Tom Whitfield', 'Priya Nair', 'George Adams', 'Sofia Rossi', 'Daniel Cole'];
        foreach (range(1, 12) as $i) {
            Order::create([
                'brand_id' => $brand->id,
                'reference' => 'FGC-'.(1000 + $i),
                'customer' => $customers[array_rand($customers)],
                'email' => 'customer'.$i.'@example.com',
                'total' => [18.99, 49.00, 36.49, 89.00, 21.50][$i % 5],
                'status' => ['paid', 'paid', 'paid', 'fulfilled', 'refunded'][$i % 5],
                'placed_at' => $now->copy()->subDays($i * 2),
            ]);
        }

        // ---- Automations (CRM) ----
        $autos = [
            ['Welcome new subscriber', 'New CRM contact', 'Send welcome email', 'active', 214],
            ['Publish → Newsletter', 'Post published in WP', 'Queue broadcast', 'active', 8],
            ['Abandoned basket', 'Order not completed 1h', 'Send reminder', 'paused', 63],
            ['Weekly digest', 'Every Monday 9am', 'Send digest to list', 'active', 37],
        ];
        foreach ($autos as [$name, $trigger, $action, $status, $runs]) {
            Automation::create(compact('name', 'trigger', 'action', 'status', 'runs') + ['brand_id' => $brand->id]);
        }

        // ---- Feature tracker (badge = 6) ----
        $features = [
            ['Bulk AI image regeneration', 'Regenerate hero + card images for a whole post in one pass.', 'in_progress', 41],
            ['Multi-site cross-posting', 'Push a single draft to several connected WordPress sites.', 'planned', 33],
            ['Humaniser v2 (tone sliders)', 'Fine-grained control over warmth, formality and pace.', 'in_progress', 28],
            ['Scheduled social snippets', 'Auto-generate social captions when a post goes live.', 'planned', 19],
            ['Grammar rules per brand', 'Custom brand grammar & style rulebook enforced on generation.', 'shipped', 52],
            ['Order → CRM sync', 'Two-way sync between WooCommerce orders and CRM contacts.', 'planned', 14],
        ];
        foreach ($features as [$title, $desc, $status, $votes]) {
            Feature::create(['title' => $title, 'description' => $desc, 'status' => $status, 'votes' => $votes]);
        }
    }

    private function post(Brand $brand, string $title, string $status, int $words, int $blocks, string $kw, Carbon $now, array $extra = []): void
    {
        Post::create(array_merge([
            'brand_id' => $brand->id,
            'title' => $title,
            'initial_prompt' => 'The Magic of '.Str::of($title)->after(':')->trim()->limit(70),
            'content_type' => 'BLOG POST',
            'slug' => Str::slug($title),
            'status' => $status,
            'focus_keyword' => $kw,
            'excerpt' => Str::limit(strip_tags($title), 120),
            'body' => $this->sampleBody($title),
            'word_count' => $words,
            'block_count' => $blocks,
            'updated_at' => $now->copy()->subDays(rand(1, 30)),
        ], $extra));
    }

    private function secondaryKeywords(string $kw): array
    {
        $pool = ['classic children\'s books', 'hardback gift books', 'heirloom presents', 'family reading traditions', 'nostalgic gifts', 'bedtime stories'];
        return array_slice(array_values(array_unique(array_merge([$kw.' gift'], $pool))), 0, 4);
    }

    private function blockMix(int $total): array
    {
        // Weighted so the aggregate donut reads Paragraph ~38%, Cards ~18%, Hero/Banner/Tip ~8%, Faq ~7%
        $weighted = array_merge(
            array_fill(0, 38, 'Paragraph'),
            array_fill(0, 18, 'Cards'),
            array_fill(0, 8, 'Hero'),
            array_fill(0, 8, 'Image Banner'),
            array_fill(0, 8, 'Daniels Tip'),
            array_fill(0, 7, 'Faq'),
            array_fill(0, 13, 'Quote'),
        );
        $out = [];
        for ($i = 0; $i < $total; $i++) {
            $out[] = ['type' => $weighted[($i * 7) % count($weighted)], 'text' => 'Block '.($i + 1)];
        }
        return $out;
    }

    private function sampleBody(string $title): string
    {
        return "<h2>{$title}</h2>\n<p>There is something quietly magical about placing the right book into a child's hands at exactly the right moment. At Fresh Green Classics we believe every title should feel like an heirloom — chosen with care, built to last, and ready to be read aloud on the sofa long after the wrapping paper has been cleared away.</p>\n<p>This guide walks through why these stories endure, how to choose them, and the small rituals that turn a book into a treasured family memory.</p>";
    }
}
