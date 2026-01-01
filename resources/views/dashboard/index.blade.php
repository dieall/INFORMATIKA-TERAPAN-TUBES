@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Monitoring Kesehatan')

@section('content')
<div class="container-fluid">
    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #4F46E5, #7C3AED); color: white;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-card-title">Total Pasien</div>
                <div class="stat-card-value">{{ $totalPatients }}</div>
                <div class="stat-card-change positive">
                    <i class="bi bi-arrow-up"></i> Pasien Unik
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #10B981, #059669); color: white;">
                    <i class="bi bi-file-medical-fill"></i>
                </div>
                <div class="stat-card-title">Total Records</div>
                <div class="stat-card-value">{{ $totalRecords }}</div>
                <div class="stat-card-change positive">
                    <i class="bi bi-arrow-up"></i> Data Kesehatan
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-card-title">Kualitas Data</div>
                <div class="stat-card-value">{{ number_format($avgQualityScore, 1) }}%</div>
                <div class="stat-card-change {{ $avgQualityScore >= 80 ? 'positive' : 'negative' }}">
                    <i class="bi bi-{{ $avgQualityScore >= 80 ? 'arrow-up' : 'arrow-down' }}"></i> Rata-rata Score
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: white;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-card-title">Data Valid</div>
                <div class="stat-card-value">{{ number_format($validDataPercentage, 1) }}%</div>
                <div class="stat-card-change positive">
                    <i class="bi bi-arrow-up"></i> Validitas
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Tren Kualitas Data (7 Hari Terakhir)</h5>
                <canvas id="qualityTrendChart" height="80"></canvas>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Distribusi Diagnosis</h5>
                <canvas id="diagnosisChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Data & Security -->
    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Data Kesehatan Terbaru</h5>
                    <a href="{{ route('health-data.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Pasien</th>
                                <th>Nama</th>
                                <th>Diagnosis</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentHealthData as $data)
                            <tr>
                                <td><strong>{{ $data->patient_id }}</strong></td>
                                <td>{{ $data->patient_name }}</td>
                                <td>{{ $data->diagnosis ?? '-' }}</td>
                                <td>{{ $data->visit_date->format('d M Y') }}</td>
                                <td>
                                    @if($data->data_status == 'valid')
                                        <span class="badge bg-success">Valid</span>
                                    @elseif($data->data_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Invalid</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Security Events (24 Jam)</h5>
                    <a href="{{ route('security.index') }}" class="btn btn-sm btn-outline-primary">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentSecurityEvents->take(5) as $event)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($event->severity == 'critical')
                                    <i class="bi bi-exclamation-circle-fill text-danger fs-4"></i>
                                @elseif($event->severity == 'high')
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                                @else
                                    <i class="bi bi-info-circle-fill text-info fs-4"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $event->event_type }}</div>
                                <small class="text-muted">{{ $event->user->name ?? 'System' }} - {{ $event->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Tidak ada event</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quality Issues -->
    @if($qualityIssues->count() > 0)
    <div class="row g-3">
        <div class="col-12">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Masalah Kualitas Data</h5>
                    <a href="{{ route('data-quality.index') }}" class="btn btn-sm btn-warning">
                        Kelola <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipe Check</th>
                                <th>Data</th>
                                <th>Pesan</th>
                                <th>Score</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($qualityIssues as $issue)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $issue->check_type }}</span></td>
                                <td>{{ $issue->healthData->patient_name ?? 'N/A' }}</td>
                                <td>{{ $issue->message }}</td>
                                <td><span class="badge bg-danger">{{ $issue->score }}%</span></td>
                                <td>{{ $issue->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Ready - Initializing Charts...');
    
    // Check if Chart.js loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded!');
        return;
    }
    console.log('Chart.js loaded successfully');
    
    // Quality Trend Chart
    const qualityTrendCtx = document.getElementById('qualityTrendChart');
    console.log('Quality Trend Canvas:', qualityTrendCtx);
    
    if (qualityTrendCtx) {
        const qualityTrendData = {!! json_encode($qualityTrend) !!};
        console.log('Quality Trend Data:', qualityTrendData);
        
        const labels = qualityTrendData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        console.log('Labels:', labels);
        
        const scores = qualityTrendData.map(d => parseFloat(d.avg_score) || 0);
        console.log('Scores:', scores);
        
        try {
            const chart = new Chart(qualityTrendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kualitas Data (%)',
                        data: scores,
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Score: ' + context.parsed.y.toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                display: true
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            console.log('Quality Trend Chart created successfully:', chart);
        } catch (error) {
            console.error('Error creating Quality Trend Chart:', error);
        }
    } else {
        console.error('Quality Trend Canvas not found!');
    }

    // Diagnosis Distribution Chart
    const diagnosisCtx = document.getElementById('diagnosisChart');
    console.log('Diagnosis Canvas:', diagnosisCtx);
    
    if (diagnosisCtx) {
        const diagnosisData = {!! json_encode($diagnosisDistribution) !!};
        console.log('Diagnosis Data:', diagnosisData);
        
        const diagLabels = diagnosisData.map(d => d.diagnosis || 'Unknown');
        const diagCounts = diagnosisData.map(d => parseInt(d.count) || 0);
        console.log('Diagnosis Labels:', diagLabels);
        console.log('Diagnosis Counts:', diagCounts);
        
        try {
            const diagChart = new Chart(diagnosisCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: diagLabels,
                    datasets: [{
                        data: diagCounts,
                        backgroundColor: [
                            '#4F46E5',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#3B82F6',
                            '#8B5CF6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return label + ': ' + value + ' pasien';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Diagnosis Chart created successfully:', diagChart);
        } catch (error) {
            console.error('Error creating Diagnosis Chart:', error);
        }
    } else {
        console.error('Diagnosis Canvas not found!');
    }
    
    console.log('Charts initialization complete');
});
</script>
@endpush
@endsection

