<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityLog;
use App\Models\AuditTrail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $securityLogs = SecurityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Security Statistics
        $totalEvents = SecurityLog::count();
        $criticalEvents = SecurityLog::where('severity', 'critical')->count();
        $failedAttempts = SecurityLog::where('status', 'failed')->count();
        $todayEvents = SecurityLog::whereDate('created_at', Carbon::today())->count();

        // Event Type Distribution
        $eventDistribution = SecurityLog::select('event_type', DB::raw('COUNT(*) as count'))
            ->groupBy('event_type')
            ->orderBy('count', 'desc')
            ->get();

        return view('security.index', compact(
            'securityLogs',
            'totalEvents',
            'criticalEvents',
            'failedAttempts',
            'todayEvents',
            'eventDistribution'
        ));
    }

    public function auditTrail()
    {
        $auditTrails = AuditTrail::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('security.audit-trail', compact('auditTrails'));
    }

    public function riskAnalysis()
    {
        // CIA Triad Analysis
        $ciaAnalysis = [
            'confidentiality' => [
                'score' => 85,
                'status' => 'good',
                'issues' => SecurityLog::where('event_type', 'unauthorized_access')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->count(),
            ],
            'integrity' => [
                'score' => 92,
                'status' => 'excellent',
                'issues' => AuditTrail::where('action', 'update')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->count(),
            ],
            'availability' => [
                'score' => 88,
                'status' => 'good',
                'issues' => SecurityLog::where('status', 'failed')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->count(),
            ],
        ];

        // Recent Critical Events
        $criticalEvents = SecurityLog::where('severity', 'critical')
            ->orWhere('severity', 'high')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // User Activity Analysis
        $userActivity = User::withCount(['securityLogs' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }])
            ->orderBy('security_logs_count', 'desc')
            ->limit(10)
            ->get();

        return view('security.risk-analysis', compact(
            'ciaAnalysis',
            'criticalEvents',
            'userActivity'
        ));
    }

    public function users()
    {
        $users = User::with('role')
            ->withCount('securityLogs')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('security.users', compact('users'));
    }
}
