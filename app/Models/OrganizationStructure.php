<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationStructure extends Model
{
    protected $fillable = [
        'batch_id',
        'position',
        'name',
        'photo',
        'description',
        'order',
        'level',
    ];

    protected $casts = [
        'order' => 'integer',
        'level' => 'integer',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('level')->orderBy('order');
    }
}
