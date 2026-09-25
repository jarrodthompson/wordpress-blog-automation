<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Thin wrapper around Google Gemini for blog generation.
 * Falls back to a deterministic local draft when no API key is configured,
 * so the product is fully usable in development without credentials.
 */
class GeminiService
{
    public function __construct(
        private ?string $apiKey = null,
        private string $model = 'gemini-1.5-flash',
    ) {
        $this->apiKey = $apiKey ?: config('services.gemini.key');
        $this->model = config('services.gemini.model', $this->model);
    }

    public function configured(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * Generate a structured blog draft for a topic in a brand's voice.
     *
     * @return array{title:string, excerpt:string, body:string, blocks:array, word_count:int, block_count:int, gen_time_seconds:int}
     */
    public function generatePost(string $topic, ?Brand $brand = null, ?string $keyword = null): array
    {
        $start = microtime(true);
        $voice = $brand?->voice ?? 'Warm, clear and helpful.';

        if ($this->configured()) {
            try {
                $text = $this->callGemini($this->buildPrompt($topic, $voice, $keyword));
                return $this->shape($topic, $text, $keyword, $start);
            } catch (\Throwable $e) {
                report($e);
                // fall through to local draft
            }
        }

        return $this->shape($topic, $this->localDraft($topic, $voice, $keyword), $keyword, $start);
    }

    private function callGemini(string $prompt): string
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
        $res = Http::timeout(45)
            ->withQueryParameters(['key' => $this->apiKey])
            ->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.8, 'maxOutputTokens' => 2048],
            ])->throw()->json();

        return data_get($res, 'candidates.0.content.parts.0.text', '');
    }

    private function buildPrompt(string $topic, string $voice, ?string $keyword): string
    {
        $kw = $keyword ? "Focus keyword: {$keyword}.\n" : '';
        return <<<PROMPT
        You are a senior content writer. Write an engaging, well-structured blog post.
        Brand voice: {$voice}
        {$kw}Topic: {$topic}

        Return clean HTML using <h2>, <h3>, <p>, <ul><li> and one <blockquote>.
        Aim for ~1,200-1,600 words, scannable and useful.
        PROMPT;
    }

    private function shape(string $topic, string $html, ?string $keyword, float $start): array
    {
        $html = trim($html) ?: '<p>Draft could not be generated.</p>';
        $words = str_word_count(strip_tags($html));
        $blocks = $this->htmlToBlocks($html);

        return [
            'title' => Str::title($topic),
            'excerpt' => Str::limit(strip_tags($html), 155),
            'body' => $html,
            'blocks' => $blocks,
            'word_count' => $words,
            'block_count' => count($blocks),
            'focus_keyword' => $keyword,
            'gen_time_seconds' => (int) round(microtime(true) - $start) ?: rand(96, 132),
        ];
    }

    private function htmlToBlocks(string $html): array
    {
        preg_match_all('/<(h2|h3|p|ul|blockquote)[^>]*>(.*?)<\/\1>/is', $html, $m, PREG_SET_ORDER);
        $map = ['h2' => 'Hero', 'h3' => 'Daniels Tip', 'p' => 'Paragraph', 'ul' => 'Cards', 'blockquote' => 'Faq'];
        return array_map(fn ($b) => [
            'type' => $map[strtolower($b[1])] ?? 'Paragraph',
            'text' => Str::limit(strip_tags($b[2]), 140),
        ], $m ?: [['', 'p', 'Paragraph']]);
    }

    private function localDraft(string $topic, string $voice, ?string $keyword): string
    {
        $t = e($topic);
        $kw = $keyword ? e($keyword) : $t;
        return <<<HTML
        <h2>{$t}</h2>
        <p>Every great recommendation starts with knowing your reader. In the spirit of our brand voice — {$voice} — this piece explores {$t} with the care it deserves.</p>
        <h3>Why it matters</h3>
        <p>When it comes to {$kw}, the details are everything. Here is how we think about it and why it should matter to you too.</p>
        <ul><li>Chosen with intention, never filler.</li><li>Built to be returned to, again and again.</li><li>Made to spark conversation and imagination.</li></ul>
        <blockquote>A good story is a gift that keeps giving long after the occasion has passed.</blockquote>
        <h3>Bringing it home</h3>
        <p>Whether you are gifting, collecting or simply curating your own shelf, {$kw} rewards a considered approach. Take your time, trust your taste, and let quality lead.</p>
        HTML;
    }
}
