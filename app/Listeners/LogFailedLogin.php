<?php

namespace App\Listeners;

use App\Models\SecurityLog;
use App\Models\AuditTrail;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class LogFailedLogin
{
    protected $request;
    protected $threshold = 2; // Threshold untuk menandai sebagai critical

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Failed $event): void
    {
        $ipAddress = $this->request->ip();
        $userAgent = $this->request->userAgent();

        // Ambil email dari credentials
        $email = $event->credentials['email'] ?? 'unknown';

        // Hitung failed attempts dari IP ini di last 30 menit
        $recentFailedAttempts = SecurityLog::where('ip_address', $ipAddress)
            ->where('event_type', 'failed_login')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        // Increment failed attempts
        $failedAttemptCount = $recentFailedAttempts + 1;

        // Tentukan severity berdasarkan jumlah attempts
        $severity = match (true) {
            $failedAttemptCount >= 5 => 'critical',
            $failedAttemptCount >= $this->threshold => 'high',
            default => 'medium'
        };

        // Log failed login attempt
        SecurityLog::create([
            'user_id' => null, // User belum authenticated
            'event_type' => 'failed_login',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'severity' => $severity,
            'description' => "Failed login attempt untuk email: {$email} (Attempt #{$failedAttemptCount})",
            'resource_type' => 'User',
            'resource_id' => $email,
            'status' => 'failed',
            'metadata' => [
                'email' => $email,
                'attempt_number' => $failedAttemptCount,
                'threshold' => $this->threshold,
                'time_window' => '30 minutes',
            ],
        ]);

        // Jika sudah exceed threshold, log ke audit trail juga
        if ($failedAttemptCount >= $this->threshold) {
            AuditTrail::create([
                'user_id' => null,
                'action' => 'failed_login_attempt',
                'model_type' => 'Security\\FailedAttempt',
                'model_id' => 0,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'old_values' => null,
                'new_values' => [
                    'email' => $email,
                    'failed_attempts' => $failedAttemptCount,
                    'severity' => $severity,
                    'reason' => 'Multiple failed login attempts detected',
                ],
            ]);
        }
    }
}
