@extends('layouts.app')

@section('title', 'Data Kesehatan')
@section('page-title', 'Manajemen Data Kesehatan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Data Kesehatan Pasien</h4>
                    <p class="text-muted mb-0">Kelola dan monitor data kesehatan pasien</p>
                </div>
                <a href="{{ route('health-data.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Data Baru
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Usia</th>
                        <th>Gender</th>
                        <th>Diagnosis</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Kualitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($healthData as $data)
                    <tr>
                        <td><strong>{{ $data->patient_id }}</strong></td>
                        <td>{{ $data->patient_name }}</td>
                        <td>{{ $data->age ?? '-' }}</td>
                        <td>
                            @if($data->gender == 'L')
                                <span class="badge bg-info">Laki-laki</span>
                            @elseif($data->gender == 'P')
                                <span class="badge bg-pink" style="background: #EC4899;">Perempuan</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $data->diagnosis ?? '-' }}</td>
                        <td>{{ $data->visit_date->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress" style="width: 60px; height: 8px;">
                                    <div class="progress-bar {{ $data->quality_score >= 80 ? 'bg-success' : ($data->quality_score >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                         style="width: {{ $data->quality_score }}%"></div>
                                </div>
                                <span class="ms-2 small">{{ number_format($data->quality_score, 0) }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($data->data_status == 'valid')
                                <span class="badge bg-success">Valid</span>
                            @elseif($data->data_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Invalid</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('health-data.show', $data) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('health-data.edit', $data) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('health-data.destroy', $data) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada data kesehatan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $healthData->links() }}
        </div>
    </div>
</div>
@endsection

