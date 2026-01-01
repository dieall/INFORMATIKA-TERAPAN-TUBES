<?php

namespace App\Http\Controllers;

use App\Models\HealthData;
use App\Models\DataQualityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $healthData = HealthData::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('health-data.index', compact('healthData'));
    }

    public function create()
    {
        return view('health-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:L,P',
            'diagnosis' => 'nullable|string|max:255',
            'treatment' => 'nullable|string|max:255',
            'blood_pressure_systolic' => 'nullable|numeric|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:0|max:200',
            'heart_rate' => 'nullable|numeric|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        $validated['created_by'] = Auth::id();
        $healthData = HealthData::create($validated);

        // Calculate quality score
        $qualityScore = $healthData->calculateQualityScore();
        $healthData->quality_score = $qualityScore;
        $healthData->data_status = $qualityScore >= 80 ? 'valid' : 'pending';
        $healthData->is_complete = $qualityScore == 100;
        $healthData->save();

        // Log quality check
        DataQualityLog::create([
            'health_data_id' => $healthData->id,
            'check_type' => 'completeness',
            'status' => $qualityScore >= 80 ? 'passed' : 'warning',
            'message' => "Data quality score: {$qualityScore}%",
            'score' => $qualityScore,
            'checked_by' => Auth::id(),
        ]);

        return redirect()->route('health-data.index')
            ->with('success', 'Data kesehatan berhasil ditambahkan.');
    }

    public function show(HealthData $healthData)
    {
        $healthData->load(['creator', 'qualityLogs.checker']);
        return view('health-data.show', compact('healthData'));
    }

    public function edit(HealthData $healthData)
    {
        return view('health-data.edit', compact('healthData'));
    }

    public function update(Request $request, HealthData $healthData)
    {
        $validated = $request->validate([
            'patient_id' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:L,P',
            'diagnosis' => 'nullable|string|max:255',
            'treatment' => 'nullable|string|max:255',
            'blood_pressure_systolic' => 'nullable|numeric|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:0|max:200',
            'heart_rate' => 'nullable|numeric|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        $healthData->update($validated);

        // Recalculate quality score
        $qualityScore = $healthData->calculateQualityScore();
        $healthData->quality_score = $qualityScore;
        $healthData->data_status = $qualityScore >= 80 ? 'valid' : 'pending';
        $healthData->is_complete = $qualityScore == 100;
        $healthData->save();

        return redirect()->route('health-data.index')
            ->with('success', 'Data kesehatan berhasil diperbarui.');
    }

    public function destroy(HealthData $healthData)
    {
        $healthData->delete();

        return redirect()->route('health-data.index')
            ->with('success', 'Data kesehatan berhasil dihapus.');
    }
}
