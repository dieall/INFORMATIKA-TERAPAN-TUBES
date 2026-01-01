@extends('layouts.app')

@section('title', 'Detail Data Kesehatan')
@section('page-title', 'Detail Data Kesehatan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Data Pasien</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('health-data.edit', $healthData) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <a href="{{ route('health-data.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <!-- Patient Information -->
        <div class="col-md-8">
            <div class="table-container">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person me-2"></i>Informasi Pasien</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">ID Pasien</label>
                        <div class="fw-semibold">{{ $healthData->patient_id }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Pasien</label>
                        <div class="fw-semibold">{{ $healthData->patient_name }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Usia</label>
                        <div class="fw-semibold">{{ $healthData->age ?? '-' }} tahun</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Gender</label>
                        <div class="fw-semibold">
                            @if($healthData->gender == 'L')
                                Laki-laki
                            @elseif($healthData->gender == 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Tanggal Kunjungan</label>
                        <div class="fw-semibold">{{ $healthData->visit_date->format('d M Y') }}</div>
                    </div>
                </div>
                
                <h5 class="border-bottom pb-2 mb-3 mt-4"><i class="bi bi-clipboard2-pulse me-2"></i>Informasi Medis</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Diagnosis</label>
                        <div class="fw-semibold">{{ $healthData->diagnosis ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Treatment</label>
                        <div class="fw-semibold">{{ $healthData->treatment ?? '-' }}</div>
                    </div>
                </div>
                
                <h5 class="border-bottom pb-2 mb-3 mt-4"><i class="bi bi-heart-pulse me-2"></i>Tanda Vital</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="text-muted small">Tekanan Darah</label>
                        <div class="fw-semibold">
                            @if($healthData->blood_pressure_systolic && $healthData->blood_pressure_diastolic)
                                {{ $healthData->blood_pressure_systolic }}/{{ $healthData->blood_pressure_diastolic }} mmHg
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Detak Jantung</label>
                        <div class="fw-semibold">{{ $healthData->heart_rate ? $healthData->heart_rate . ' bpm' : '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Suhu Tubuh</label>
                        <div class="fw-semibold">{{ $healthData->temperature ? $healthData->temperature . ' °C' : '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Dibuat Oleh</label>
                        <div class="fw-semibold">{{ $healthData->creator->name ?? 'System' }}</div>
                    </div>
                </div>
                
                @if($healthData->notes)
                <h5 class="border-bottom pb-2 mb-3 mt-4"><i class="bi bi-journal-text me-2"></i>Catatan</h5>
                <p class="mb-0">{{ $healthData->notes }}</p>
                @endif
            </div>
        </div>
        
        <!-- Quality & Status -->
        <div class="col-md-4">
            <div class="table-container mb-3">
                <h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Kualitas Data</h5>
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold" style="color: {{ $healthData->quality_score >= 80 ? '#10B981' : '#F59E0B' }}">
                        {{ number_format($healthData->quality_score, 0) }}%
                    </div>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar {{ $healthData->quality_score >= 80 ? 'bg-success' : 'bg-warning' }}" 
                             style="width: {{ $healthData->quality_score }}%"></div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Status Data:</span>
                    @if($healthData->data_status == 'valid')
                        <span class="badge bg-success">Valid</span>
                    @elseif($healthData->data_status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-danger">Invalid</span>
                    @endif
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Kelengkapan:</span>
                    <span class="badge bg-{{ $healthData->is_complete ? 'success' : 'warning' }}">
                        {{ $healthData->is_complete ? 'Complete' : 'Incomplete' }}
                    </span>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>Akurasi:</span>
                    <span class="badge bg-{{ $healthData->is_accurate ? 'success' : 'danger' }}">
                        {{ $healthData->is_accurate ? 'Accurate' : 'Needs Review' }}
                    </span>
                </div>
            </div>
            
            <!-- Quality Logs -->
            @if($healthData->qualityLogs->count() > 0)
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Quality Logs</h5>
                <div class="list-group list-group-flush">
                    @foreach($healthData->qualityLogs as $log)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-secondary">{{ $log->check_type }}</span>
                                <div class="small text-muted mt-1">{{ $log->message }}</div>
                                <div class="small text-muted">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="badge bg-{{ $log->status == 'passed' ? 'success' : 'danger' }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

