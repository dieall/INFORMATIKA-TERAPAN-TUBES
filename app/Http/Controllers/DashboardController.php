<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthData;
use App\Models\User;
use App\Models\DataQualityLog;
use App\Models\SecurityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // KPI Cards
        $totalPatients = HealthData::distinct('patient_id')->count();
        $totalRecords = HealthData::count();
        $avgQualityScore = HealthData::avg('quality_score');
        $validDataPercentage = HealthData::where('data_status', 'valid')->count() / max($totalRecords, 1) * 100;

        // Recent Health Data
        $recentHealthData = HealthData::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data Quality Trend (Last 7 days)
        $qualityTrend = HealthData::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COALESCE(AVG(quality_score), 0) as avg_score'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->whereNotNull('quality_score')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Jika tidak ada data, buat data dummy untuk chart
        if ($qualityTrend->isEmpty()) {
            $qualityTrend = collect([
                (object)['date' => Carbon::now()->format('Y-m-d'), 'avg_score' => 0, 'count' => 0]
            ]);
        }

        // Diagnosis Distribution
        $diagnosisDistribution = HealthData::select('diagnosis', DB::raw('COUNT(*) as count'))
            ->whereNotNull('diagnosis')
            ->where('diagnosis', '!=', '')
            ->groupBy('diagnosis')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        // Jika tidak ada data, buat data dummy
        if ($diagnosisDistribution->isEmpty()) {
            $diagnosisDistribution = collect([
                (object)['diagnosis' => 'No Data', 'count' => 1]
            ]);
        }

        // Security Events (Last 24 hours)
        $recentSecurityEvents = SecurityLog::with('user')
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data Quality Issues
        $qualityIssues = DataQualityLog::with('healthData')
            ->where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Active Users
        $activeUsers = User::where('is_active', true)->count();

        // Monthly Data Entry Trend
        $monthlyTrend = HealthData::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', compact(
            'totalPatients',
            'totalRecords',
            'avgQualityScore',
            'validDataPercentage',
            'recentHealthData',
            'qualityTrend',
            'diagnosisDistribution',
            'recentSecurityEvents',
            'qualityIssues',
            'activeUsers',
            'monthlyTrend'
        ));
    }

    public function analytics()
    {
        // Advanced analytics data
        return view('dashboard.analytics');
    }
}
