@extends('layouts.app')

@section('title', 'Critical Security Events')
@section('page-title', 'Critical Events - Security Monitoring')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Critical Security Events</h4>
                    <p class="text-muted mb-0">Monitor aktivitas kritis dan ancaman keamanan sistem</p>
                </div>
                <a href="{{ route('security.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Security Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">Critical Events (30 hari)</p>
                            <h3 class="text-danger">{{ $stats['critical_count'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">High Severity (30 hari)</p>
                            <h3 class="text-warning">{{ $stats['high_count'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-circle text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">Failed Logins (30 hari)</p>
                            <h3 class="text-info">{{ $stats['failed_logins'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-shield-lock text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">Today Critical Events</p>
                            <h3 class="text-danger">{{ $stats['today_critical'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock-history text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Failed IPs Alert -->
    @if($topFailedIPs->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-start border-danger border-5">
                <div class="card-body">
                    <h5 class="card-title text-danger mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>Top IP Addresses dengan Failed Attempts (7 hari terakhir)
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Failed Attempts</th>
                                    <th>Risk Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topFailedIPs as $index => $ipData)
                                <tr>
                                    <td>
                                        <code class="bg-light p-2 rounded">{{ $ipData->ip_address }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $ipData->attempt_count }} attempts</span>
                                    </td>
                                    <td>
                                        @if($ipData->attempt_count >= 5)
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>CRITICAL
                                            </span>
                                        @elseif($ipData->attempt_count >= 3)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i>HIGH
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="bi bi-info-circle me-1"></i>MEDIUM
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('security.failed-attempts', ['ip_search' => $ipData->ip_address]) }}" 
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-search me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('security.critical-events') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Severity</label>
                            <select name="severity" class="form-select" onchange="this.form.submit()">
                                <option value="">Critical & High</option>
                                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>
                                    <i class="bi bi-exclamation-triangle"></i> Critical Only
                                </option>
                                <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>
                                    <i class="bi bi-exclamation-circle"></i> High Only
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Event Type</label>
                            <select name="event_type" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Event Types</option>
                                <option value="failed_login" {{ request('event_type') === 'failed_login' ? 'selected' : '' }}>Failed Login</option>
                                <option value="unauthorized_access" {{ request('event_type') === 'unauthorized_access' ? 'selected' : '' }}>Unauthorized Access</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Events Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Event Type</th>
                                    <th>Severity</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($criticalEvents as $event)
                                <tr class="align-middle">
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $event->created_at->format('d M Y') }}</span><br>
                                        <small class="text-muted">{{ $event->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        @if($event->event_type === 'failed_login')
                                            <span class="badge bg-danger-light text-danger">
                                                <i class="bi bi-lock-fill me-1"></i>Failed Login
                                            </span>
                                        @elseif($event->event_type === 'unauthorized_access')
                                            <span class="badge bg-warning-light text-warning">
                                                <i class="bi bi-shield-exclamation me-1"></i>Unauthorized Access
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($event->event_type) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->severity === 'critical')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>CRITICAL
                                            </span>
                                        @elseif($event->severity === 'high')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i>HIGH
                                            </span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($event->severity) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($event->description, 60) }}</small>
                                    </td>
                                    <td>
                                        <code class="bg-light p-1 rounded text-danger">{{ $event->ip_address }}</code>
                                    </td>
                                    <td>
                                        @if($event->status === 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @elseif($event->status === 'success')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($event->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $event->id }}">
                                            <i class="bi bi-eye"></i> Lihat
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="detailModal{{ $event->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content border-danger border-3">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>Event Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Event Type:</strong> {{ ucfirst($event->event_type) }}<br>
                                                                <strong>Severity:</strong> {{ ucfirst($event->severity) }}<br>
                                                                <strong>Status:</strong> {{ ucfirst($event->status) }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>IP Address:</strong> {{ $event->ip_address }}<br>
                                                                <strong>Waktu:</strong> {{ $event->created_at->format('d M Y H:i:s') }}<br>
                                                                <strong>Resource:</strong> {{ $event->resource_type }} (ID: {{ $event->resource_id }})
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <h6 class="mb-3">
                                                            <i class="bi bi-info-circle me-2"></i>Deskripsi
                                                        </h6>
                                                        <p class="text-muted">{{ $event->description }}</p>

                                                        @if($event->metadata)
                                                            <h6 class="mb-3">
                                                                <i class="bi bi-code-square me-2"></i>Metadata
                                                            </h6>
                                                            <pre class="bg-light p-3 rounded overflow-auto" style="max-height: 300px;">{{ json_encode($event->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        @endif

                                                        @if($event->user_agent)
                                                            <h6 class="mb-2">
                                                                <i class="bi bi-hdd-network me-2"></i>User Agent
                                                            </h6>
                                                            <small class="text-muted d-block">{{ $event->user_agent }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i><br>
                                        <span>Tidak ada critical events dalam periode ini</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Menampilkan <strong>{{ $criticalEvents->count() }}</strong> dari <strong>{{ $criticalEvents->total() }}</strong> events
                        </div>
                        <div>
                            {{ $criticalEvents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

.text-danger-light {
    color: #dc3545;
}

.text-warning-light {
    color: #ffc107;
}
</style>
