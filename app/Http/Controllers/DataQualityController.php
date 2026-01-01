<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthData;
use App\Models\DataQualityLog;
use Illuminate\Support\Facades\DB;

class DataQualityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $qualityLogs = DataQualityLog::with(['healthData', 'checker'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Quality Statistics
        $totalChecks = DataQualityLog::count();
        $passedChecks = DataQualityLog::where('status', 'passed')->count();
        $failedChecks = DataQualityLog::where('status', 'failed')->count();
        $avgScore = DataQualityLog::avg('score');

        // Data Completeness
        $completenessRate = HealthData::where('is_complete', true)->count() / max(HealthData::count(), 1) * 100;

        // Data Accuracy
        $accuracyRate = HealthData::where('is_accurate', true)->count() / max(HealthData::count(), 1) * 100;

        return view('data-quality.index', compact(
            'qualityLogs',
            'totalChecks',
            'passedChecks',
            'failedChecks',
            'avgScore',
            'completenessRate',
            'accuracyRate'
        ));
    }

    public function runValidation()
    {
        // Get all health data that needs validation
        $healthDataList = HealthData::where('data_status', 'pending')
            ->orWhere('quality_score', '<', 80)
            ->get();

        $validatedCount = 0;

        foreach ($healthDataList as $healthData) {
            $qualityScore = $healthData->calculateQualityScore();
            $healthData->quality_score = $qualityScore;
            $healthData->data_status = $qualityScore >= 80 ? 'valid' : 'invalid';
            $healthData->is_complete = $qualityScore == 100;
            
            // Check accuracy
            $isAccurate = true;
            if ($healthData->blood_pressure_systolic && ($healthData->blood_pressure_systolic < 70 || $healthData->blood_pressure_systolic > 200)) {
                $isAccurate = false;
            }
            if ($healthData->heart_rate && ($healthData->heart_rate < 40 || $healthData->heart_rate > 200)) {
                $isAccurate = false;
            }
            if ($healthData->temperature && ($healthData->temperature < 35 || $healthData->temperature > 42)) {
                $isAccurate = false;
            }
            
            $healthData->is_accurate = $isAccurate;
            $healthData->save();

            // Log the validation
            DataQualityLog::create([
                'health_data_id' => $healthData->id,
                'check_type' => 'validation',
                'status' => $qualityScore >= 80 && $isAccurate ? 'passed' : 'failed',
                'message' => $qualityScore >= 80 && $isAccurate ? 'Data valid' : 'Data memerlukan perbaikan',
                'score' => $qualityScore,
                'checked_by' => auth()->id(),
                'details' => [
                    'completeness' => $qualityScore,
                    'accuracy' => $isAccurate ? 100 : 50,
                ],
            ]);

            $validatedCount++;
        }

        return redirect()->route('data-quality.index')
            ->with('success', "{$validatedCount} data berhasil divalidasi.");
    }

    public function report()
    {
        // Comprehensive quality report
        $qualityMetrics = [
            'completeness' => [
                'score' => HealthData::where('is_complete', true)->count() / max(HealthData::count(), 1) * 100,
                'total' => HealthData::count(),
                'complete' => HealthData::where('is_complete', true)->count(),
            ],
            'accuracy' => [
                'score' => HealthData::where('is_accurate', true)->count() / max(HealthData::count(), 1) * 100,
                'total' => HealthData::count(),
                'accurate' => HealthData::where('is_accurate', true)->count(),
            ],
            'validity' => [
                'score' => HealthData::where('data_status', 'valid')->count() / max(HealthData::count(), 1) * 100,
                'total' => HealthData::count(),
                'valid' => HealthData::where('data_status', 'valid')->count(),
            ],
        ];

        return view('data-quality.report', compact('qualityMetrics'));
    }
}
