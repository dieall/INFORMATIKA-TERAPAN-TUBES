<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'age',
        'gender',
        'diagnosis',
        'treatment',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'temperature',
        'notes',
        'visit_date',
        'created_by',
        'data_status',
        'quality_score',
        'is_complete',
        'is_accurate',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'quality_score' => 'decimal:2',
        'is_complete' => 'boolean',
        'is_accurate' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function qualityLogs()
    {
        return $this->hasMany(DataQualityLog::class);
    }

    public function calculateQualityScore(): float
    {
        $score = 100;
        
        // Completeness check
        $requiredFields = ['patient_id', 'patient_name', 'age', 'gender', 'diagnosis', 'visit_date'];
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                $score -= 10;
            }
        }
        
        // Accuracy check for vital signs
        if ($this->blood_pressure_systolic && ($this->blood_pressure_systolic < 70 || $this->blood_pressure_systolic > 200)) {
            $score -= 5;
        }
        if ($this->heart_rate && ($this->heart_rate < 40 || $this->heart_rate > 200)) {
            $score -= 5;
        }
        if ($this->temperature && ($this->temperature < 35 || $this->temperature > 42)) {
            $score -= 5;
        }
        
        return max(0, $score);
    }
}
