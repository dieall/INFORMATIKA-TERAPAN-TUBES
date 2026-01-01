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
                    <p class="text-muted mb-0">Log semua aktivitas perubahan data dalam sistem</p>
                </div>
                <a href="{{ route('security.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Model ID</th>
                        <th>IP Address</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditTrails as $trail)
                    <tr>
                        <td>{{ $trail->created_at->format('d M Y H:i:s') }}</td>
                        <td>{{ $trail->user->name ?? 'System' }}</td>
                        <td>
                            @if($trail->action == 'create')
                                <span class="badge bg-success">Create</span>
                            @elseif($trail->action == 'update')
                                <span class="badge bg-warning">Update</span>
                            @elseif($trail->action == 'delete')
                                <span class="badge bg-danger">Delete</span>
                            @else
                                <span class="badge bg-info">{{ ucfirst($trail->action) }}</span>
                            @endif
                        </td>
                        <td><code>{{ class_basename($trail->model_type) }}</code></td>
                        <td><strong>#{{ $trail->model_id }}</strong></td>
                        <td><code>{{ $trail->ip_address }}</code></td>
                        <td>
                            @if($trail->old_values || $trail->new_values)
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $trail->id }}">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="detailModal{{ $trail->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Perubahan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($trail->old_values)
                                                    <h6>Nilai Lama:</h6>
                                                    <pre class="bg-light p-3 rounded">{{ json_encode($trail->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                @endif
                                                
                                                @if($trail->new_values)
                                                    <h6>Nilai Baru:</h6>
                                                    <pre class="bg-light p-3 rounded">{{ json_encode($trail->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada audit trail</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $auditTrails->links() }}
        </div>
    </div>
</div>
@endsection

