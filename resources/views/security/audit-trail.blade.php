@extends('layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail - Activity Log')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Audit Trail</h4>
                    <p class="text-muted mb-0">Log semua aktivitas login, logout, dan perubahan data dalam sistem</p>
                </div>
                <a href="{{ route('security.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('security.audit-trail') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Aktivitas</label>
                            <select name="action_filter" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Aktivitas</option>
                                <option value="login" {{ request('action_filter') === 'login' ? 'selected' : '' }}>
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </option>
                                <option value="logout" {{ request('action_filter') === 'logout' ? 'selected' : '' }}>
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </option>
                                <option value="create" {{ request('action_filter') === 'create' ? 'selected' : '' }}>
                                    <i class="bi bi-plus-circle"></i> Create
                                </option>
                                <option value="update" {{ request('action_filter') === 'update' ? 'selected' : '' }}>
                                    <i class="bi bi-pencil-circle"></i> Update
                                </option>
                                <option value="delete" {{ request('action_filter') === 'delete' ? 'selected' : '' }}>
                                    <i class="bi bi-trash"></i> Delete
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter User</label>
                            <select name="user_filter" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_filter') === (string)$user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cari IP Address</label>
                            <input type="text" name="ip_search" class="form-control" placeholder="Cari IP address..." value="{{ request('ip_search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model/Resource</th>
                        <th>IP Address</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditTrails as $trail)
                    <tr class="align-middle">
                        <td>
                            <span class="badge bg-light text-dark">{{ $trail->created_at->format('d M Y') }}</span><br>
                            <small class="text-muted">{{ $trail->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2 bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($trail->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $trail->user->name ?? 'System' }}</div>
                                    <small class="text-muted">{{ $trail->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($trail->action == 'login')
                                <span class="badge bg-success-light text-success">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                </span>
                            @elseif($trail->action == 'logout')
                                <span class="badge bg-warning-light text-warning">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </span>
                            @elseif($trail->action == 'create')
                                <span class="badge bg-success">
                                    <i class="bi bi-plus-circle me-1"></i>Create
                                </span>
                            @elseif($trail->action == 'update')
                                <span class="badge bg-warning">
                                    <i class="bi bi-pencil-circle me-1"></i>Update
                                </span>
                            @elseif($trail->action == 'delete')
                                <span class="badge bg-danger">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </span>
                            @else
                                <span class="badge bg-info">{{ ucfirst($trail->action) }}</span>
                            @endif
                        </td>
                        <td>
                            <code class="bg-light p-2 rounded d-block">
                                {{ class_basename($trail->model_type) }} #{{ $trail->model_id }}
                            </code>
                        </td>
                        <td>
                            <small class="text-monospace" title="{{ $trail->ip_address }}">
                                <i class="bi bi-hdd-network"></i> {{ $trail->ip_address }}
                            </small><br>
                            @if($trail->user_agent)
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;" title="{{ $trail->user_agent }}">
                                    {{ Str::limit($trail->user_agent, 50) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($trail->old_values || $trail->new_values)
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $trail->id }}">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                
                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $trail->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-info-circle me-2"></i>Detail Aktivitas
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>User:</strong> {{ $trail->user->name ?? 'System' }}<br>
                                                        <strong>Email:</strong> {{ $trail->user->email ?? '-' }}<br>
                                                        <strong>IP Address:</strong> {{ $trail->ip_address }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Waktu:</strong> {{ $trail->created_at->format('d M Y H:i:s') }}<br>
                                                        <strong>Action:</strong> {{ ucfirst($trail->action) }}<br>
                                                        <strong>Model:</strong> {{ class_basename($trail->model_type) }}
                                                    </div>
                                                </div>

                                                <hr>

                                                @if($trail->old_values)
                                                    <h6 class="mb-3">
                                                        <i class="bi bi-arrow-left-circle text-danger me-2"></i>Nilai Lama
                                                    </h6>
                                                    <pre class="bg-light p-3 rounded mb-3 overflow-auto" style="max-height: 300px;">{{ json_encode($trail->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @endif
                                                
                                                @if($trail->new_values)
                                                    <h6 class="mb-3">
                                                        <i class="bi bi-arrow-right-circle text-success me-2"></i>Nilai Baru
                                                    </h6>
                                                    <pre class="bg-light p-3 rounded overflow-auto" style="max-height: 300px;">{{ json_encode($trail->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i><br>
                            <span>Belum ada audit trail</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan <strong>{{ $auditTrails->count() }}</strong> dari <strong>{{ $auditTrails->total() }}</strong> records
            </div>
            <div>
                {{ $auditTrails->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

