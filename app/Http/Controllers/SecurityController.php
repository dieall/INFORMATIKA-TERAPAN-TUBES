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

    public function auditTrail(Request $request)
    {
        $query = AuditTrail::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by action (login, logout, create, update, delete)
        if ($request->filled('action_filter')) {
            $query->where('action', $request->action_filter);
        }

        // Filter by user
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }

        // Search by IP address
        if ($request->filled('ip_search')) {
            $query->where('ip_address', 'like', '%' . $request->ip_search . '%');
        }

        $auditTrails = $query->paginate(25);

        // Get all users for filter dropdown
        $users = User::orderBy('name')->get();

        return view('security.audit-trail', compact('auditTrails', 'users'));
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

    /**
     * Show critical security events and failed login attempts
     */
    public function criticalEvents(Request $request)
    {
        $query = SecurityLog::orderBy('created_at', 'desc');

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        } else {
            // Default: show critical dan high
            $query->whereIn('severity', ['critical', 'high']);
        }

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $criticalEvents = $query->paginate(30);

        // Statistics
        $stats = [
            'critical_count' => SecurityLog::where('severity', 'critical')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'high_count' => SecurityLog::where('severity', 'high')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'failed_logins' => SecurityLog::where('event_type', 'failed_login')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'today_critical' => SecurityLog::where('severity', 'critical')
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];

        // Top IPs dengan failed attempts
        $topFailedIPs = SecurityLog::where('event_type', 'failed_login')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select('ip_address', DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('ip_address')
            ->orderBy('attempt_count', 'desc')
            ->limit(10)
            ->get();

        return view('security.critical-events', compact(
            'criticalEvents',
            'stats',
            'topFailedIPs'
        ));
    }

    /**
     * Show failed login attempts with detailed tracking
     */
    public function failedAttempts(Request $request)
    {
        $query = SecurityLog::where('event_type', 'failed_login')
            ->orderBy('created_at', 'desc');

        // Filter by IP address
        if ($request->filled('ip_search')) {
            $query->where('ip_address', 'like', '%' . $request->ip_search . '%');
        }

        // Filter by email/resource
        if ($request->filled('email_search')) {
            $query->where('resource_id', 'like', '%' . $request->email_search . '%');
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $failedAttempts = $query->paginate(30);

        // Get failed attempt trends
        $failedTrends = SecurityLog::where('event_type', 'failed_login')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

        // Top emails with most failed attempts
        $topFailedEmails = SecurityLog::where('event_type', 'failed_login')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select('resource_id', DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('resource_id')
            ->orderBy('attempt_count', 'desc')
            ->limit(10)
            ->get();

        return view('security.failed-attempts', compact(
            'failedAttempts',
            'failedTrends',
            'topFailedEmails'
        ));
    }
}
