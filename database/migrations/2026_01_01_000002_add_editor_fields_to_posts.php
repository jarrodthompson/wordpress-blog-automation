<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $t) {
            $t->text('initial_prompt')->nullable()->after('title');
            $t->string('content_type')->default('BLOG POST')->after('status');
            $t->unsignedTinyInteger('seo_score')->nullable()->after('grammar_checked');
            $t->json('secondary_keywords')->nullable()->after('focus_keyword');
            $t->json('generation_history')->nullable()->after('blocks');
            $t->string('secondary_image')->nullable()->after('cover_image');
            $t->timestamp('wp_synced_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $t) {
            $t->dropColumn(['initial_prompt', 'content_type', 'seo_score', 'secondary_keywords', 'generation_history', 'secondary_image', 'wp_synced_at']);
        });
    }
};
