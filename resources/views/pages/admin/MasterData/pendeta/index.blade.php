@extends('layouts.main')
@section('title', 'Data Pendeta')

@section('content')


<div class="section">
    <!-- Statistik -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pendeta</h4>
                    </div>
                    <div class="card-body">
                        <strong id="totalPendetaCount">-- Orang</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- pending-stat card removed (handled via Tambah Pendeta button) -->
    </div>

    <div class="row mb-3">

        <div class="col text-right">
            <a href="{{ route('admin.pendeta.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pendeta
            </a>
        </div>
    </div>
</div>

<!-- Card Data Penatua -->
<div class="card">
    <div class="card-header">
        <h4>Daftar Pendeta</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pendeta</th>
                        <th>No. HP</th>
                        <th>Tanggal Ditahbis</th>
                        <th>Status</th>
                        <th>No. SK Tahbisan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendetas ?? [] as $pendeta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ data_get($pendeta, 'jemaat.nama') ?? data_get($pendeta, 'jemaat_nama') ?? data_get($pendeta, 'nama') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'jemaat.no_telp') ?? data_get($pendeta, 'jemaat_no_telp') ?? data_get($pendeta, 'no_telp') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'tanggal_tahbis') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'status') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'no_sk_tahbis') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'keterangan') ?? '-' }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <form action="#" method="post" style="display:inline-block"
                                onsubmit="return confirm('Hapus data pendeta ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data pendeta.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if(isset($pendetas) && method_exists($pendetas, 'links'))
            <div class="mt-3">
                {!! $pendetas->links() !!}
            </div>
            @endif
        </div>
    </div>
</div>







@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success') || session('message') || session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const msg = @json(session('success') ?? session('message') ?? session('status'));
        Swal.fire({
            title: 'Berhasil',
            text: msg || 'Data berhasil disimpan',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@endpush