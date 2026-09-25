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
            : null;

        $drafts = Post::where('brand_id', $brand->id)
            ->whereIn('status', ['planned', 'writing', 'review'])
            ->orderByDesc('updated_at')->get();

        return view('pages.editor', compact('brand', 'post', 'drafts'))
            ->with('geminiReady', app(GeminiService::class)->configured());
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
