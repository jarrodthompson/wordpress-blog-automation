<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('domain')->nullable();
            $t->string('wp_url')->nullable();
            $t->string('wp_username')->nullable();
            $t->string('wp_app_password')->nullable();
            $t->string('gemini_key')->nullable();
            $t->string('color')->default('#16a34a');
            $t->string('initials', 4)->default('BR');
            $t->string('status')->default('live');
            $t->text('voice')->nullable();
            $t->json('brand_dna')->nullable();
            $t->json('metrics')->nullable(); // WP REST snapshot: published, drafts, media, ttfb...
            $t->timestamp('last_synced_at')->nullable();
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('slug');
            $t->string('status')->default('planned'); // planned, writing, review, published
            $t->string('focus_keyword')->nullable();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->unsignedInteger('word_count')->default(0);
            $t->unsignedInteger('block_count')->default(0);
            $t->json('blocks')->nullable();
            $t->boolean('humanised')->default(false);
            $t->boolean('grammar_checked')->default(false);
            $t->unsignedInteger('gen_time_seconds')->nullable();
            $t->string('cover_image')->nullable();
            $t->unsignedBigInteger('wp_post_id')->nullable();
            $t->timestamp('scheduled_at')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        Schema::create('media_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('url')->nullable();
            $t->string('type')->default('image');
            $t->string('page')->nullable();
            $t->timestamps();
        });

        Schema::create('comments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $t->string('author');
            $t->text('body');
            $t->string('status')->default('pending');
            $t->timestamps();
        });

        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('sku')->nullable();
            $t->decimal('price', 10, 2)->default(0);
            $t->unsignedInteger('stock')->default(0);
            $t->string('status')->default('active');
            $t->timestamps();
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('reference')->unique();
            $t->string('customer');
            $t->string('email')->nullable();
            $t->decimal('total', 10, 2)->default(0);
            $t->string('status')->default('paid');
            $t->timestamp('placed_at')->nullable();
            $t->timestamps();
        });

        Schema::create('activities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('type')->default('published'); // published, edited, comment
            $t->string('title');
            $t->string('description')->nullable();
            $t->timestamp('occurred_at')->nullable();
            $t->timestamps();
        });

        Schema::create('features', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('status')->default('planned'); // planned, in_progress, shipped
            $t->unsignedInteger('votes')->default(0);
            $t->timestamps();
        });

        Schema::create('automations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('trigger');
            $t->string('action');
            $t->string('status')->default('active');
            $t->unsignedInteger('runs')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['automations','features','activities','orders','products','comments','media_items','posts','brands'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
