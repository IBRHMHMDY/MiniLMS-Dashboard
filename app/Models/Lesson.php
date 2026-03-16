<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'content',
        'video_url',
        'duration_in_minutes',
        'sort_order',
        'is_published',
        'is_free_preview',
        'attachments',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free_preview' => 'boolean',
        'attachments' => 'json'
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}