<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Automation extends Model
{
    protected $guarded = [];
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
