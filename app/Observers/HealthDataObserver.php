<?php

namespace App\Observers;

use App\Models\HealthData;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class HealthDataObserver
{
    /**
     * Handle the HealthData "created" event.
     */
    public function created(HealthData $healthData): void
    {
        if (Auth::check()) {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'create',
                'model_type' => HealthData::class,
                'model_id' => $healthData->id,
                'old_values' => null,
                'new_values' => $healthData->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Handle the HealthData "updated" event.
     */
    public function updated(HealthData $healthData): void
    {
        if (Auth::check()) {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model_type' => HealthData::class,
                'model_id' => $healthData->id,
                'old_values' => $healthData->getOriginal(),
                'new_values' => $healthData->getChanges(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Handle the HealthData "deleted" event.
     */
    public function deleted(HealthData $healthData): void
    {
        if (Auth::check()) {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'model_type' => HealthData::class,
                'model_id' => $healthData->id,
                'old_values' => $healthData->toArray(),
                'new_values' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Handle the HealthData "restored" event.
     */
    public function restored(HealthData $healthData): void
    {
        if (Auth::check()) {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'restore',
                'model_type' => HealthData::class,
                'model_id' => $healthData->id,
                'old_values' => null,
                'new_values' => $healthData->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
