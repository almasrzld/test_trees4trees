<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LahanRawData extends Model
{
    protected $table = 'lahan_raw_data';

    protected $fillable = [
        'lahan_no',
        'source_data',
        'is_duplicate',
        'raw_payload',
    ];

    protected $casts = [
        'is_duplicate' => 'boolean',
        'raw_payload' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_no', 'lahan_no');
    }
}
