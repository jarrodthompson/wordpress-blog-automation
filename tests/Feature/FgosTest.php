<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\FgosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FgosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(FgosSeeder::class);
    }

    public function test_dashboard_renders_with_seeded_metrics(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Live Published Posts')
            ->assertSee('Pipeline Insights')
            ->assertSee('Fresh Green Classics');
    }

    public function test_module_pages_render(): void
    {
        $routes = [
            '/blog-manager', '/editor', '/ai-image', '/crm', '/scoreboard',
            '/products', '/autoblog', '/activity', '/features', '/brand-dna', '/settings',
        ];
        foreach ($routes as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_editor_generates_a_draft(): void
    {
        $before = Post::count();

        $this->post('/editor/generate', [
            'topic' => 'Testing the Gemini fallback draft',
            'keyword' => 'test keyword',
        ])->assertRedirect();

        $this->assertSame($before + 1, Post::count());
        $this->assertDatabaseHas('posts', ['status' => 'review']);
    }

    public function test_blog_manager_filters_by_stage(): void
    {
        $this->get('/blog-manager?stage=published')
            ->assertOk()
            ->assertSee('PUBLISHED');
    }
}
