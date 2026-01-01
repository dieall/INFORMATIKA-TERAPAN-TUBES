@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail User</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <!-- User Information -->
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person me-2"></i>Informasi User</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Lengkap</label>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <div class="fw-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Role</label>
                        <div>
                            <span class="badge bg-primary">{{ $user->role->display_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Department</label>
                        <div class="fw-semibold">{{ $user->department ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Phone</label>
                        <div class="fw-semibold">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status</label>
                        <div>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Last Login</label>
                        <div class="fw-semibold">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('d M Y H:i') }}
                                <small class="text-muted d-block">{{ $user->last_login_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Last Login IP</label>
                        <div class="fw-semibold">
                            @if($user->last_login_ip)
                                <code>{{ $user->last_login_ip }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Registered</label>
                        <div class="fw-semibold">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Last Updated</label>
                        <div class="fw-semibold">{{ $user->updated_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Role Permissions -->
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-shield-lock me-2"></i>Role Permissions</h5>
                @if($user->role && $user->role->permissions)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($user->role->permissions as $permission)
                            <span class="badge bg-secondary">{{ $permission }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No permissions assigned</p>
                @endif
            </div>
            
            <div class="table-container mt-3">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-activity me-2"></i>Activity Summary</h5>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="display-6 fw-bold text-primary">{{ $user->securityLogs->count() }}</div>
                        <small class="text-muted">Security Logs</small>
                    </div>
                    <div class="col-6">
                        <div class="display-6 fw-bold text-success">{{ $user->healthData->count() }}</div>
                        <small class="text-muted">Data Created</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Security Logs -->
        @if($user->securityLogs->count() > 0)
        <div class="col-12">
            <div class="table-container">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-clock-history me-2"></i>Recent Security Logs (10 Terakhir)</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Event Type</th>
                                <th>Severity</th>
                                <th>IP Address</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->securityLogs as $log)
                            <tr>
                                <td><small>{{ $log->created_at->format('d M H:i') }}</small></td>
                                <td><span class="badge bg-secondary">{{ $log->event_type }}</span></td>
                                <td>
                                    @if($log->severity == 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif($log->severity == 'high')
                                        <span class="badge bg-warning">High</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($log->severity) }}</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->ip_address }}</code></td>
                                <td><small>{{ $log->description }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

