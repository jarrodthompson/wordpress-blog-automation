<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $guarded = [];

    protected $casts = [
        'brand_dna' => 'array',
        'metrics' => 'array',
        'last_synced_at' => 'datetime',
    ];

    public function metric(string $key, $default = 0)
    {
        return data_get($this->metrics, $key, $default);
    }

    protected $hidden = ['wp_app_password', 'gemini_key'];

    public function posts(): HasMany { return $this->hasMany(Post::class); }
    public function media(): HasMany { return $this->hasMany(MediaItem::class); }
    public function comments(): HasMany { return $this->hasMany(Comment::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function activities(): HasMany { return $this->hasMany(Activity::class); }
    public function automations(): HasMany { return $this->hasMany(Automation::class); }
}
