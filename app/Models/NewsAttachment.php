<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class NewsAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_item_id',
        'name',
        'path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    protected $appends = ['url'];

    public function newsItem(): BelongsTo
    {
        return $this->belongsTo(NewsItem::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
