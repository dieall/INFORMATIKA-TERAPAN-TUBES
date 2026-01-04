<?php

namespace App\Listeners;

use App\Models\AuditTrail;
use App\Models\SecurityLog;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class LogLogout
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Logout $event): void
    {
        $user = $event->user;
        $ipAddress = $this->request->ip();
        $userAgent = $this->request->userAgent();

        // Log ke Audit Trail
        AuditTrail::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'model_type' => 'App\\Models\\User',
            'model_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'old_values' => null,
            'new_values' => [
                'email' => $user->email,
                'name' => $user->name,
                'logout_time' => now()->toDateTimeString(),
            ],
        ]);

        // Log ke Security Log
        SecurityLog::create([
            'user_id' => $user->id,
            'event_type' => 'user_logout',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'severity' => 'low',
            'description' => 'User ' . $user->email . ' berhasil logout',
            'resource_type' => 'User',
            'resource_id' => (string) $user->id,
            'status' => 'success',
            'metadata' => [
                'user_name' => $user->name,
                'department' => $user->department,
                'role' => $user->role?->name,
            ],
        ]);
    }
}
