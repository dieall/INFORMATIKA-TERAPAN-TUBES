@extends('layouts.app')

@section('title', 'Security Logs')
@section('page-title', 'Security & User Management')

@section('content')
    <div class="container-fluid">
        <!-- Quick Access Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="{{ route('security.critical-events') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm bg-danger bg-opacity-10 border-start border-danger border-5"
                        style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-danger mb-1">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Critical Events
                                    </h6>
                                    <p class="text-muted mb-0 small">Monitor aktivitas kritis dan ancaman sistem</p>
                                </div>
                                <div class="text-danger" style="font-size: 2rem;">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('security.failed-attempts') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm bg-warning bg-opacity-10 border-start border-warning border-5"
                        style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-warning mb-1">
                                        <i class="bi bi-shield-lock me-2"></i>Failed Login Attempts
                                    </h6>
                                    <p class="text-muted mb-0 small">Track percobaan login gagal dan brute force attacks</p>
                                </div>
                                <div class="text-warning" style="font-size: 2rem;">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon"
                        style="background: linear-gradient(135deg, #4F46E5, #7C3AED); color: white;">
                        <i class="bi bi-shield-fill"></i>
                    </div>
                    <div class="stat-card-title">Total Events</div>
                    <div class="stat-card-value">{{ $totalEvents }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon"
                        style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="stat-card-title">Critical Events</div>
                    <div class="stat-card-value">{{ $criticalEvents }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon"
                        style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="stat-card-title">Failed Attempts</div>
                    <div class="stat-card-value">{{ $failedAttempts }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon"
                        style="background: linear-gradient(135deg, #10B981, #059669); color: white;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-card-title">Today's Events</div>
                    <div class="stat-card-value">{{ $todayEvents }}</div>
                </div>
            </div>
        </div>

        <!-- Event Distribution -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="table-container">
                    <h5 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Event Type Distribution</h5>
                    <canvas id="eventDistributionChart" height="200"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="table-container">
                    <h5 class="mb-3"><i class="bi bi-list-ul me-2"></i>Quick Stats</h5>
                    <div class="list-group list-group-flush">
                        @foreach ($eventDistribution as $event)
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center">
                                <span>{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $event->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Logs Table -->
        <div class="table-container">
            <h5 class="mb-3"><i class="bi bi-shield-lock me-2"></i>Security Event Logs</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Event Type</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td><span class="badge bg-secondary">{{ $log->event_type }}</span></td>
                                <td>
                                    @if ($log->severity == 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif($log->severity == 'high')
                                        <span class="badge bg-warning">High</span>
                                    @elseif($log->severity == 'medium')
                                        <span class="badge bg-info">Medium</span>
                                    @else
                                        <span class="badge bg-secondary">Low</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->status == 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($log->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-warning">Blocked</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->ip_address }}</code></td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada security logs</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $securityLogs->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Event Distribution Chart
            const eventCtx = document.getElementById('eventDistributionChart').getContext('2d');
            const eventData = {!! json_encode($eventDistribution) !!};

            new Chart(eventCtx, {
                type: 'doughnut',
                data: {
                    labels: eventData.map(d => d.event_type.replace(/_/g, ' ').toUpperCase()),
                    datasets: [{
                        data: eventData.map(d => d.count),
                        backgroundColor: [
                            '#4F46E5',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#3B82F6',
                            '#8B5CF6'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
