<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'severity',
        'description',
        'metadata',
        'resource_type',
        'resource_id',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
