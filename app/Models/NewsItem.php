<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'date',
        'title_sk',
        'title_en',
        'summary_sk',
        'summary_en',
        'content_sk',
        'content_en',
        'pinned',
    ];

    protected $casts = [
        'date'   => 'date',
        'pinned' => 'boolean',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(NewsAttachment::class);
    }
}
