<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AISuggestion extends Model
{
    use HasFactory;

    protected $table = 'ai_suggestions';

    protected $fillable = [
        'suggestion_type',
        'product_id',
        'title',
        'description',
        'action_recommendation',
        'metadata',
        'priority',
        'is_active',
        'is_dismissed',
        'dismissed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_dismissed' => 'boolean',
        'dismissed_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
