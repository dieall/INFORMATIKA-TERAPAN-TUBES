@extends('layouts.app')

@section('title', 'Risk Analysis')
@section('page-title', 'Security Risk Analysis - CIA Triad')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>CIA Triad Analysis:</strong> Analisis keamanan berdasarkan prinsip Confidentiality, Integrity, dan Availability
            </div>
        </div>
    </div>
    
    <!-- CIA Triad Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="table-container">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-card-icon me-3" style="background: linear-gradient(135deg, #4F46E5, #7C3AED); color: white; width: 60px; height: 60px; font-size: 1.75rem;">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Confidentiality</h5>
                        <small class="text-muted">Kerahasiaan Data</small>
                    </div>
                </div>
                
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold" style="color: {{ $ciaAnalysis['confidentiality']['score'] >= 80 ? '#10B981' : '#F59E0B' }}">
                        {{ $ciaAnalysis['confidentiality']['score'] }}%
                    </div>
                    <span class="badge bg-{{ $ciaAnalysis['confidentiality']['status'] == 'excellent' ? 'success' : 'warning' }} fs-6">
                        {{ ucfirst($ciaAnalysis['confidentiality']['status']) }}
                    </span>
                </div>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-primary" style="width: {{ $ciaAnalysis['confidentiality']['score'] }}%"></div>
                </div>
                
                <div class="border-top pt-3">
                    <small class="text-muted">Unauthorized Access Attempts (30 hari):</small>
                    <div class="fw-semibold fs-5">{{ $ciaAnalysis['confidentiality']['issues'] }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="table-container">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-card-icon me-3" style="background: linear-gradient(135deg, #10B981, #059669); color: white; width: 60px; height: 60px; font-size: 1.75rem;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Integrity</h5>
                        <small class="text-muted">Integritas Data</small>
                    </div>
                </div>
                
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold" style="color: {{ $ciaAnalysis['integrity']['score'] >= 80 ? '#10B981' : '#F59E0B' }}">
                        {{ $ciaAnalysis['integrity']['score'] }}%
                    </div>
                    <span class="badge bg-{{ $ciaAnalysis['integrity']['status'] == 'excellent' ? 'success' : 'warning' }} fs-6">
                        {{ ucfirst($ciaAnalysis['integrity']['status']) }}
                    </span>
                </div>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ $ciaAnalysis['integrity']['score'] }}%"></div>
                </div>
                
                <div class="border-top pt-3">
                    <small class="text-muted">Data Modifications (30 hari):</small>
                    <div class="fw-semibold fs-5">{{ $ciaAnalysis['integrity']['issues'] }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="table-container">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-card-icon me-3" style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; width: 60px; height: 60px; font-size: 1.75rem;">
                        <i class="bi bi-lightning-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Availability</h5>
                        <small class="text-muted">Ketersediaan Sistem</small>
                    </div>
                </div>
                
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold" style="color: {{ $ciaAnalysis['availability']['score'] >= 80 ? '#10B981' : '#F59E0B' }}">
                        {{ $ciaAnalysis['availability']['score'] }}%
                    </div>
                    <span class="badge bg-{{ $ciaAnalysis['availability']['status'] == 'excellent' ? 'success' : 'warning' }} fs-6">
                        {{ ucfirst($ciaAnalysis['availability']['status']) }}
                    </span>
                </div>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-info" style="width: {{ $ciaAnalysis['availability']['score'] }}%"></div>
                </div>
                
                <div class="border-top pt-3">
                    <small class="text-muted">Failed Requests (30 hari):</small>
                    <div class="fw-semibold fs-5">{{ $ciaAnalysis['availability']['issues'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overall CIA Score -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="table-container text-center">
                <h5 class="mb-3"><i class="bi bi-trophy me-2"></i>Overall Security Score (CIA Triad)</h5>
                @php
                    $overallCIA = ($ciaAnalysis['confidentiality']['score'] + $ciaAnalysis['integrity']['score'] + $ciaAnalysis['availability']['score']) / 3;
                @endphp
                <div class="display-1 fw-bold mb-3" style="color: {{ $overallCIA >= 80 ? '#10B981' : ($overallCIA >= 60 ? '#F59E0B' : '#EF4444') }}">
                    {{ number_format($overallCIA, 1) }}%
                </div>
                <p class="lead mb-0">
                    @if($overallCIA >= 80)
                        <span class="badge bg-success fs-6">Excellent Security Posture</span>
                    @elseif($overallCIA >= 60)
                        <span class="badge bg-warning fs-6">Good Security - Needs Monitoring</span>
                    @else
                        <span class="badge bg-danger fs-6">Critical - Immediate Action Required</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Critical Events -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Recent Critical Events</h5>
                <div class="list-group list-group-flush">
                    @forelse($criticalEvents as $event)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-circle-fill text-danger fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $event->event_type }}</strong>
                                    <span class="badge bg-{{ $event->severity == 'critical' ? 'danger' : 'warning' }}">
                                        {{ ucfirst($event->severity) }}
                                    </span>
                                </div>
                                <p class="mb-1 text-muted">{{ $event->description }}</p>
                                <small class="text-muted">
                                    {{ $event->user->name ?? 'System' }} - {{ $event->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success"></i>
                        Tidak ada critical events
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3"><i class="bi bi-people me-2"></i>Top Active Users (7 Hari)</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userActivity as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.875rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ $user->role->display_name ?? 'User' }}</span></td>
                                <td><span class="badge bg-primary rounded-pill">{{ $user->security_logs_count }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

