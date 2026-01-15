<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lahan extends Model
{
    protected $fillable = [
        'lahan_no',
        'document_no',
        'farmer_no',
        'land_area',
        'planting_area',
        'coordinate',
        'latitude',
        'longitude',
        'village',
        'kecamatan',
        'city',
        'province',
        'created_time',
        'source_data',
        'is_duplicate',
    ];

    protected $casts = [
        'is_duplicate' => 'boolean',
        'created_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rawData(): HasOne
    {
        return $this->hasOne(\App\Models\LahanRawData::class, 'lahan_no', 'lahan_no');
    }
}
