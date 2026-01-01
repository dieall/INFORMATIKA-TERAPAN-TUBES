@extends('layouts.app')

@section('title', 'Quality Report')
@section('page-title', 'Laporan Kualitas Data')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Comprehensive Quality Report</h4>
                    <p class="text-muted mb-0">Analisis mendalam kualitas data kesehatan</p>
                </div>
                <a href="{{ route('data-quality.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    
    <!-- Quality Dimensions -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="table-container text-center">
                <h5 class="mb-3"><i class="bi bi-clipboard-data text-success me-2"></i>Completeness</h5>
                <div class="display-4 fw-bold text-success mb-2">{{ number_format($qualityMetrics['completeness']['score'], 1) }}%</div>
                <p class="text-muted mb-3">Kelengkapan Data</p>
                <div class="row text-start">
                    <div class="col-6">
                        <small class="text-muted">Total Data:</small>
                        <div class="fw-semibold">{{ $qualityMetrics['completeness']['total'] }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Complete:</small>
                        <div class="fw-semibold text-success">{{ $qualityMetrics['completeness']['complete'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="table-container text-center">
                <h5 class="mb-3"><i class="bi bi-check-circle text-info me-2"></i>Accuracy</h5>
                <div class="display-4 fw-bold text-info mb-2">{{ number_format($qualityMetrics['accuracy']['score'], 1) }}%</div>
                <p class="text-muted mb-3">Akurasi Data</p>
                <div class="row text-start">
                    <div class="col-6">
                        <small class="text-muted">Total Data:</small>
                        <div class="fw-semibold">{{ $qualityMetrics['accuracy']['total'] }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Accurate:</small>
                        <div class="fw-semibold text-info">{{ $qualityMetrics['accuracy']['accurate'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="table-container text-center">
                <h5 class="mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Validity</h5>
                <div class="display-4 fw-bold text-primary mb-2">{{ number_format($qualityMetrics['validity']['score'], 1) }}%</div>
                <p class="text-muted mb-3">Validitas Data</p>
                <div class="row text-start">
                    <div class="col-6">
                        <small class="text-muted">Total Data:</small>
                        <div class="fw-semibold">{{ $qualityMetrics['validity']['total'] }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Valid:</small>
                        <div class="fw-semibold text-primary">{{ $qualityMetrics['validity']['valid'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overall Score -->
    <div class="row g-3">
        <div class="col-12">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-trophy me-2"></i>Overall Data Quality Score</h5>
                @php
                    $overallScore = ($qualityMetrics['completeness']['score'] + $qualityMetrics['accuracy']['score'] + $qualityMetrics['validity']['score']) / 3;
                @endphp
                <div class="text-center py-4">
                    <div class="display-1 fw-bold mb-3" style="color: {{ $overallScore >= 80 ? '#10B981' : ($overallScore >= 60 ? '#F59E0B' : '#EF4444') }}">
                        {{ number_format($overallScore, 1) }}%
                    </div>
                    <p class="lead mb-0">
                        @if($overallScore >= 80)
                            <span class="badge bg-success fs-6">Excellent Quality</span>
                        @elseif($overallScore >= 60)
                            <span class="badge bg-warning fs-6">Good Quality</span>
                        @else
                            <span class="badge bg-danger fs-6">Needs Improvement</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

