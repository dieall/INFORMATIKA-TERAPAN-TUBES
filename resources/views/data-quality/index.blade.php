@extends('layouts.app')

@section('title', 'Data Quality')
@section('page-title', 'Data Quality & Governance')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #10B981, #059669); color: white;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-card-title">Total Checks</div>
                <div class="stat-card-value">{{ $totalChecks }}</div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: white;">
                    <i class="bi bi-check2-square"></i>
                </div>
                <div class="stat-card-title">Passed</div>
                <div class="stat-card-value">{{ $passedChecks }}</div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-card-title">Failed</div>
                <div class="stat-card-value">{{ $failedChecks }}</div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-card-title">Avg Score</div>
                <div class="stat-card-value">{{ number_format($avgScore, 1) }}%</div>
            </div>
        </div>
    </div>
    
    <!-- Quality Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-clipboard-data me-2"></i>Completeness Rate</h5>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" style="width: {{ $completenessRate }}%">
                        {{ number_format($completenessRate, 1) }}%
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0">Persentase data yang lengkap</p>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-check-circle me-2"></i>Accuracy Rate</h5>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-info" style="width: {{ $accuracyRate }}%">
                        {{ number_format($accuracyRate, 1) }}%
                    </div>
                </div>
                <p class="text-muted mt-2 mb-0">Persentase data yang akurat</p>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2">
                <form action="{{ route('data-quality.validate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Validasi Semua Data
                    </button>
                </form>
                <a href="{{ route('data-quality.report') }}" class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan Lengkap
                </a>
            </div>
        </div>
    </div>
    
    <!-- Quality Logs Table -->
    <div class="table-container">
        <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Log Kualitas Data</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe Check</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Pesan</th>
                        <th>Checker</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qualityLogs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ $log->check_type }}</span></td>
                        <td>{{ $log->healthData->patient_name ?? 'N/A' }}</td>
                        <td>
                            @if($log->status == 'passed')
                                <span class="badge bg-success">Passed</span>
                            @elseif($log->status == 'warning')
                                <span class="badge bg-warning">Warning</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress" style="width: 60px; height: 8px;">
                                    <div class="progress-bar {{ $log->score >= 80 ? 'bg-success' : ($log->score >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                         style="width: {{ $log->score }}%"></div>
                                </div>
                                <span class="ms-2 small">{{ number_format($log->score, 0) }}%</span>
                            </div>
                        </td>
                        <td>{{ $log->message }}</td>
                        <td>{{ $log->checker->name ?? 'System' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada log kualitas data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $qualityLogs->links() }}
        </div>
    </div>
</div>
@endsection

