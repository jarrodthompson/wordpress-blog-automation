<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'blocks' => 'array',
        'humanised' => 'boolean',
        'grammar_checked' => 'boolean',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public const STAGES = ['planned' => 'Plan & Research', 'writing' => 'In Writing', 'review' => 'Review & Optimise', 'published' => 'Published Live'];

    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }

    public function stageIndex(): int
    {
        return array_search($this->status, array_keys(self::STAGES)) ?: 0;
    }

    public function statusLabel(): string
    {
        return self::STAGES[$this->status] ?? ucfirst($this->status);
    }
}
