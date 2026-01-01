@extends('layouts.app')

@section('title', 'Edit Data Kesehatan')
@section('page-title', 'Edit Data Kesehatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="table-container">
                <form action="{{ route('health-data.update', $healthData) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Patient Information -->
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person me-2"></i>Informasi Pasien</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">ID Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="patient_id" class="form-control @error('patient_id') is-invalid @enderror" 
                                   value="{{ old('patient_id', $healthData->patient_id) }}" required>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control @error('patient_name') is-invalid @enderror" 
                                   value="{{ old('patient_name', $healthData->patient_name) }}" required>
                            @error('patient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Usia</label>
                            <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                                   value="{{ old('age', $healthData->age) }}" min="0" max="150">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Pilih Gender</option>
                                <option value="L" {{ old('gender', $healthData->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $healthData->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                            <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" 
                                   value="{{ old('visit_date', $healthData->visit_date->format('Y-m-d')) }}" required>
                            @error('visit_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Medical Information -->
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-clipboard2-pulse me-2"></i>Informasi Medis</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Diagnosis</label>
                            <input type="text" name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" 
                                   value="{{ old('diagnosis', $healthData->diagnosis) }}">
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Treatment/Pengobatan</label>
                            <input type="text" name="treatment" class="form-control @error('treatment') is-invalid @enderror" 
                                   value="{{ old('treatment', $healthData->treatment) }}">
                            @error('treatment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Vital Signs -->
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-heart-pulse me-2"></i>Tanda Vital</h5>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Tekanan Darah Sistolik</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="blood_pressure_systolic" 
                                       class="form-control @error('blood_pressure_systolic') is-invalid @enderror" 
                                       value="{{ old('blood_pressure_systolic', $healthData->blood_pressure_systolic) }}">
                                <span class="input-group-text">mmHg</span>
                                @error('blood_pressure_systolic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Tekanan Darah Diastolik</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="blood_pressure_diastolic" 
                                       class="form-control @error('blood_pressure_diastolic') is-invalid @enderror" 
                                       value="{{ old('blood_pressure_diastolic', $healthData->blood_pressure_diastolic) }}">
                                <span class="input-group-text">mmHg</span>
                                @error('blood_pressure_diastolic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Detak Jantung</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="heart_rate" 
                                       class="form-control @error('heart_rate') is-invalid @enderror" 
                                       value="{{ old('heart_rate', $healthData->heart_rate) }}">
                                <span class="input-group-text">bpm</span>
                                @error('heart_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Suhu Tubuh</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="temperature" 
                                       class="form-control @error('temperature') is-invalid @enderror" 
                                       value="{{ old('temperature', $healthData->temperature) }}">
                                <span class="input-group-text">°C</span>
                                @error('temperature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="col-12 mt-4">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $healthData->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Update Data
                                </button>
                                <a href="{{ route('health-data.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

