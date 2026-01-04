@extends('layouts.app')

@section('title', 'Failed Login Attempts')
@section('page-title', 'Failed Login Attempts - Security Monitoring')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Failed Login Attempts</h4>
                    <p class="text-muted mb-0">Monitor percobaan login yang gagal dan identifikasi ancaman brute force</p>
                </div>
                <a href="{{ route('security.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Security Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Failed Attempts by Email Chart -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-bar-chart me-2"></i>Top Emails dengan Failed Attempts (7 hari)
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Email</th>
                                    <th>Failed Attempts</th>
                                    <th>Risk Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topFailedEmails as $email)
                                <tr>
                                    <td>
                                        <code class="bg-light p-2 rounded">{{ $email->resource_id }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $email->attempt_count }} attempts</span>
                                    </td>
                                    <td>
                                        @if($email->attempt_count >= 5)
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>CRITICAL
                                            </span>
                                        @elseif($email->attempt_count >= 3)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i>HIGH
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="bi bi-info-circle me-1"></i>MEDIUM
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Attempts Trends -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-graph-up me-2"></i>Failed Attempts Trend (7 hari)
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Total Attempts</th>
                                    <th>Visual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($failedTrends as $trend)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($trend->date)->format('d M Y') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $trend->count }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-danger" style="width: {{ min($trend->count * 5, 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('security.failed-attempts') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari IP Address</label>
                            <input type="text" name="ip_search" class="form-control" placeholder="e.g., 192.168.1.1" value="{{ request('ip_search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cari Email/Username</label>
                            <input type="text" name="email_search" class="form-control" placeholder="e.g., user@example.com" value="{{ request('email_search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Severity</label>
                            <select name="severity" class="form-select">
                                <option value="">Semua Severity</option>
                                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Attempts Details Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger bg-opacity-10 border-bottom-2">
                    <h6 class="mb-0 text-danger">
                        <i class="bi bi-list-check me-2"></i>Detail Failed Login Attempts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Email/Username</th>
                                    <th>IP Address</th>
                                    <th>Severity</th>
                                    <th>Attempt #</th>
                                    <th>Reason</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($failedAttempts as $attempt)
                                <tr class="align-middle">
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $attempt->created_at->format('d M Y') }}</span><br>
                                        <small class="text-muted">{{ $attempt->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <code class="bg-light p-2 rounded">{{ $attempt->resource_id }}</code>
                                    </td>
                                    <td>
                                        <code class="bg-danger bg-opacity-10 p-2 rounded text-danger">{{ $attempt->ip_address }}</code>
                                    </td>
                                    <td>
                                        @if($attempt->severity === 'critical')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>CRITICAL
                                            </span>
                                        @elseif($attempt->severity === 'high')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-circle me-1"></i>HIGH
                                            </span>
                                        @else
                                            <span class="badge bg-info">MEDIUM</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-danger">
                                            #{{ $attempt->metadata['attempt_number'] ?? 'N/A' }}
                                        </strong>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($attempt->description, 40) }}</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $attempt->id }}">
                                            <i class="bi bi-eye"></i> Lihat
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="detailModal{{ $attempt->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content border-danger border-3">
                                                    <div class="modal-header bg-danger bg-opacity-10 border-danger">
                                                        <h5 class="modal-title text-danger">
                                                            <i class="bi bi-lock-fill me-2"></i>Failed Login Attempt Details
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Email/Username:</strong> <code>{{ $attempt->resource_id }}</code><br>
                                                                <strong>IP Address:</strong> <code class="bg-danger bg-opacity-10">{{ $attempt->ip_address }}</code><br>
                                                                <strong>Severity:</strong> <span class="badge bg-{{ $attempt->severity === 'critical' ? 'danger' : 'warning' }}">{{ ucfirst($attempt->severity) }}</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Waktu:</strong> {{ $attempt->created_at->format('d M Y H:i:s') }}<br>
                                                                <strong>Status:</strong> <span class="badge bg-danger">{{ ucfirst($attempt->status) }}</span><br>
                                                                <strong>Event Type:</strong> {{ ucfirst($attempt->event_type) }}
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <h6 class="mb-3">
                                                            <i class="bi bi-info-circle me-2"></i>Deskripsi
                                                        </h6>
                                                        <p class="text-muted">{{ $attempt->description }}</p>

                                                        @if($attempt->metadata)
                                                            <h6 class="mb-3">
                                                                <i class="bi bi-code-square me-2"></i>Metadata & Tracking Info
                                                            </h6>
                                                            <div class="bg-light p-3 rounded">
                                                                <table class="table table-sm mb-0">
                                                                    <tr>
                                                                        <td><strong>Attempt Number:</strong></td>
                                                                        <td><span class="badge bg-danger">{{ $attempt->metadata['attempt_number'] ?? 'N/A' }}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Threshold:</strong></td>
                                                                        <td>{{ $attempt->metadata['threshold'] ?? 'N/A' }} attempts</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Time Window:</strong></td>
                                                                        <td>{{ $attempt->metadata['time_window'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        @endif

                                                        @if($attempt->user_agent)
                                                            <h6 class="mb-2 mt-3">
                                                                <i class="bi bi-hdd-network me-2"></i>User Agent / Browser
                                                            </h6>
                                                            <small class="text-muted d-block text-break">{{ $attempt->user_agent }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer border-top">
                                                        <a href="{{ route('security.failed-attempts', ['ip_search' => $attempt->ip_address]) }}" 
                                                           class="btn btn-outline-danger btn-sm">
                                                            <i class="bi bi-search me-1"></i>View All from this IP
                                                        </a>
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
                                        <span>Tidak ada failed login attempts</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Menampilkan <strong>{{ $failedAttempts->count() }}</strong> dari <strong>{{ $failedAttempts->total() }}</strong> attempts
                        </div>
                        <div>
                            {{ $failedAttempts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
