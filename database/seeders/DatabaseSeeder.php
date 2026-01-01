<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\HealthData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full access to all features',
            'permissions' => [
                'dashboard.view',
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'health_data.view', 'health_data.create', 'health_data.edit', 'health_data.delete',
                'data_quality.view', 'data_quality.manage',
                'security.view', 'security.manage',
                'reports.view', 'reports.export',
            ],
            'is_active' => true,
        ]);

        $managementRole = Role::create([
            'name' => 'management',
            'display_name' => 'Management',
            'description' => 'Access to dashboard and reports',
            'permissions' => [
                'dashboard.view',
                'health_data.view',
                'data_quality.view',
                'security.view',
                'reports.view', 'reports.export',
            ],
            'is_active' => true,
        ]);

        $staffRole = Role::create([
            'name' => 'staff',
            'display_name' => 'Staff',
            'description' => 'Basic access to health data',
            'permissions' => [
                'dashboard.view',
                'health_data.view', 'health_data.create', 'health_data.edit',
            ],
            'is_active' => true,
        ]);

        // Create Users
        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin System',
            'email' => 'admin@healthdashboard.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'department' => 'IT',
            'is_active' => true,
        ]);

        $manager = User::create([
            'role_id' => $managementRole->id,
            'name' => 'Manager Kesehatan',
            'email' => 'manager@healthdashboard.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'department' => 'Management',
            'is_active' => true,
        ]);

        $staff = User::create([
            'role_id' => $staffRole->id,
            'name' => 'Staff Medis',
            'email' => 'staff@healthdashboard.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'department' => 'Medical',
            'is_active' => true,
        ]);

        // Create Sample Health Data
        $healthDataSamples = [
            [
                'patient_id' => 'P001',
                'patient_name' => 'Budi Santoso',
                'age' => 45,
                'gender' => 'L',
                'diagnosis' => 'Hipertensi',
                'treatment' => 'Amlodipine 10mg',
                'blood_pressure_systolic' => 140,
                'blood_pressure_diastolic' => 90,
                'heart_rate' => 78,
                'temperature' => 36.5,
                'notes' => 'Pasien rutin kontrol',
                'visit_date' => now()->subDays(5),
                'created_by' => $staff->id,
                'data_status' => 'valid',
                'is_complete' => true,
                'is_accurate' => true,
            ],
            [
                'patient_id' => 'P002',
                'patient_name' => 'Siti Rahayu',
                'age' => 32,
                'gender' => 'P',
                'diagnosis' => 'Diabetes Mellitus Tipe 2',
                'treatment' => 'Metformin 500mg',
                'blood_pressure_systolic' => 120,
                'blood_pressure_diastolic' => 80,
                'heart_rate' => 72,
                'temperature' => 36.7,
                'notes' => 'Gula darah terkontrol',
                'visit_date' => now()->subDays(3),
                'created_by' => $staff->id,
                'data_status' => 'valid',
                'is_complete' => true,
                'is_accurate' => true,
            ],
            [
                'patient_id' => 'P003',
                'patient_name' => 'Ahmad Wijaya',
                'age' => 28,
                'gender' => 'L',
                'diagnosis' => 'ISPA',
                'treatment' => 'Amoxicillin 500mg',
                'blood_pressure_systolic' => 110,
                'blood_pressure_diastolic' => 70,
                'heart_rate' => 68,
                'temperature' => 37.2,
                'notes' => 'Batuk pilek',
                'visit_date' => now()->subDays(1),
                'created_by' => $staff->id,
                'data_status' => 'valid',
                'is_complete' => true,
                'is_accurate' => true,
            ],
            [
                'patient_id' => 'P004',
                'patient_name' => 'Dewi Lestari',
                'age' => 55,
                'gender' => 'P',
                'diagnosis' => 'Osteoarthritis',
                'treatment' => 'Ibuprofen 400mg',
                'blood_pressure_systolic' => 130,
                'blood_pressure_diastolic' => 85,
                'heart_rate' => 75,
                'temperature' => 36.6,
                'notes' => 'Nyeri sendi lutut',
                'visit_date' => now()->subDays(7),
                'created_by' => $staff->id,
                'data_status' => 'valid',
                'is_complete' => true,
                'is_accurate' => true,
            ],
            [
                'patient_id' => 'P005',
                'patient_name' => 'Rudi Hartono',
                'age' => 38,
                'gender' => 'L',
                'diagnosis' => 'Gastritis',
                'treatment' => 'Omeprazole 20mg',
                'blood_pressure_systolic' => 115,
                'blood_pressure_diastolic' => 75,
                'heart_rate' => 70,
                'temperature' => 36.8,
                'notes' => 'Maag kronis',
                'visit_date' => now(),
                'created_by' => $staff->id,
                'data_status' => 'valid',
                'is_complete' => true,
                'is_accurate' => true,
            ],
        ];

        foreach ($healthDataSamples as $data) {
            $healthData = HealthData::create($data);
            $healthData->quality_score = $healthData->calculateQualityScore();
            $healthData->save();
        }
    }
}
