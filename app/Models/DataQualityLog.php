<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataQualityLog extends Model
{
    protected $fillable = [
        'health_data_id',
        'check_type',
        'status',
        'message',
        'details',
        'score',
        'checked_by',
    ];

    protected $casts = [
        'details' => 'array',
        'score' => 'decimal:2',
    ];

    public function healthData()
    {
        return $this->belongsTo(HealthData::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
