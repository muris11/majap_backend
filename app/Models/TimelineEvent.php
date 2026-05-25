<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEvent extends Model
{
    protected $fillable = [
        'batch_id',
        'year',
        'title',
        'description',
        'icon',
        'image',
        'location',
        'event_date',
        'order',
        'is_published',
    ];

    protected $casts = [
        'year' => 'integer',
        'order' => 'integer',
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('event_date', 'desc')->orderBy('order');
    }
}
