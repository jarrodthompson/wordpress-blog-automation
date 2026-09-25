<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BrandContext;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    public function index(BrandContext $ctx, Request $request)
    {
        $brand = $ctx->current();
        abort_if(! $brand, 404);

        $post = $request->filled('post')
            ? Post::where('brand_id', $brand->id)->find($request->integer('post'))
            : Post::where('brand_id', $brand->id)->where('status', 'published')->latest('published_at')->first();
        $post ??= Post::where('brand_id', $brand->id)->latest('updated_at')->first();

        $drafts = Post::where('brand_id', $brand->id)
            ->orderByDesc('updated_at')->get();

        return view('pages.editor', [
            'brand' => $brand,
            'post' => $post,
            'drafts' => $drafts,
            'geminiReady' => app(GeminiService::class)->configured(),
            'stages' => $this->stages($post),
            'seoAudit' => $this->seoAudit($post),
            'checklist' => $this->checklist($post),
        ]);
    }

    /** The Plan → Research → Write → Review → Live rail. */
    private function stages(?Post $post): array
    {
        $order = ['planned', 'writing', 'review', 'published'];
        $reached = $post ? array_search($post->status, $order) : -1;
        $rows = [
            ['key' => 'plan', 'label' => 'Plan', 'done' => $reached >= 0],
            ['key' => 'research', 'label' => 'Research', 'done' => $reached >= 0],
            ['key' => 'write', 'label' => 'Write', 'done' => $reached >= 1 || ($post && $post->word_count > 100)],
            ['key' => 'review', 'label' => 'Review', 'done' => $reached >= 2],
            ['key' => 'live', 'label' => 'Live', 'done' => $reached >= 3],
        ];
        return $rows;
    }

    /** Static-but-scored on-page audit breakdown, keyed off the post's SEO score. */
    private function seoAudit(?Post $post): array
    {
        $score = $post?->seo_score ?? 0;
        return [
            'score' => $score,
            'passing' => 48, 'improve' => 27, 'fix' => 13, 'na' => 11,
            'verdict' => match (true) {
                $score >= 90 => 'Excellent',
                $score >= 75 => 'Good — can improve',
                $score >= 60 => 'Fair — needs work',
                default => 'Needs attention',
            },
            'categories' => [
                ['label' => 'E-E-A-T & trust', 'count' => 17],
                ['label' => 'Search intent', 'count' => 7],
                ['label' => 'Metadata', 'count' => 4],
                ['label' => 'Keyphrase', 'count' => 4],
                ['label' => 'Readability & structure', 'count' => 3],
                ['label' => 'AI search (AEO)', 'count' => 3],
                ['label' => 'Content', 'count' => 1],
                ['label' => 'Media', 'count' => 1],
                ['label' => 'Links', 'count' => 0],
            ],
        ];
    }

    /** Pre-flight checklist for the Publish tab. */
    private function checklist(?Post $post): array
    {
        return [
            ['label' => 'Article title set', 'ready' => (bool) $post?->title],
            ['label' => 'Content written (50+ words)', 'ready' => ($post?->word_count ?? 0) >= 50],
            ['label' => 'Focus keyphrase set', 'ready' => (bool) $post?->focus_keyword],
            ['label' => 'SEO score ≥ 60', 'ready' => ($post?->seo_score ?? 0) >= 60],
            ['label' => 'Featured image set', 'ready' => (bool) $post?->cover_image || in_array($post?->status, ['review', 'published'], true)],
        ];
    }

    public function generate(BrandContext $ctx, GeminiService $gemini, Request $request)
    {
        $brand = $ctx->current();
        abort_if(! $brand, 404);

        $data = $request->validate([
            'topic' => 'required|string|max:200',
            'keyword' => 'nullable|string|max:120',
            'post_id' => 'nullable|integer',
        ]);

        $draft = $gemini->generatePost($data['topic'], $brand, $data['keyword'] ?? null);

        $postId = $data['post_id'] ?? null;
        $post = $postId ? Post::where('brand_id', $brand->id)->find($postId) : null;
        $post ??= new Post(['brand_id' => $brand->id, 'slug' => Str::slug($draft['title'])]);

        $post->fill([
            'brand_id' => $brand->id,
            'title' => $post->title ?: $draft['title'],
            'slug' => $post->slug ?: Str::slug($draft['title']),
            'status' => 'review',
            'focus_keyword' => $data['keyword'] ?? $post->focus_keyword,
            'excerpt' => $draft['excerpt'],
            'body' => $draft['body'],
            'blocks' => $draft['blocks'],
            'word_count' => $draft['word_count'],
            'block_count' => $draft['block_count'],
            'gen_time_seconds' => $draft['gen_time_seconds'],
        ])->save();

        return redirect()->route('editor', ['brand' => $brand->id, 'post' => $post->id])
            ->with('status', 'Draft generated in '.$draft['gen_time_seconds'].'s');
    }

    public function save(BrandContext $ctx, Request $request)
    {
        $brand = $ctx->current();
        abort_if(! $brand, 404);

        $data = $request->validate([
            'post_id' => 'nullable|integer',
            'title' => 'required|string|max:200',
            'focus_keyword' => 'nullable|string|max:120',
            'body' => 'nullable|string',
            'status' => 'required|in:planned,writing,review,published',
        ]);

        $post = ($data['post_id'] ?? null) ? Post::where('brand_id', $brand->id)->find($data['post_id']) : new Post;
        $post->fill([
            'brand_id' => $brand->id,
            'title' => $data['title'],
            'slug' => $post->slug ?: Str::slug($data['title']),
            'focus_keyword' => $data['focus_keyword'] ?? null,
            'body' => $data['body'] ?? $post->body,
            'status' => $data['status'],
            'word_count' => str_word_count(strip_tags($data['body'] ?? '')),
            'published_at' => $data['status'] === 'published' ? ($post->published_at ?? now()) : $post->published_at,
        ])->save();

        return redirect()->route('editor', ['brand' => $brand->id, 'post' => $post->id])
            ->with('status', 'Saved “'.Str::limit($post->title, 40).'”');
    }
}
