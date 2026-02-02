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
                        <td>{{ data_get($pendeta, 'jemaat.nama') ?? data_get($pendeta, 'jemaat_nama') ??
                            data_get($pendeta, 'nama') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'jemaat.no_telp') ?? data_get($pendeta, 'jemaat_no_telp') ??
                            data_get($pendeta, 'no_telp') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'tanggal_tahbis') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'status') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'no_sk_tahbis') ?? '-' }}</td>
                        <td>{{ data_get($pendeta, 'keterangan') ?? '-' }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-info btn-sm" title="Lihat" data-bs-toggle="modal"
                                data-bs-target="#showPendetaModal-{{ $pendeta->id }}">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- ================= SHOW MODAL PENDETA ================= -->
                            <div class="modal fade" id="showPendetaModal-{{ $pendeta->id }}" tabindex="-1"
                                aria-labelledby="showPendetaLabel-{{ $pendeta->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <div>
                                                <h5 class="modal-title fw-semibold"
                                                    id="showPendetaLabel-{{ $pendeta->id }}">Detail Pendeta</h5>
                                                <small class="text-muted">{{ $pendeta->nama ?? data_get($pendeta, 'jemaat.nama') ?? '-' }}</small>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">

                                                    <!-- LEFT: FOTO & SINGKAT -->
                                                    <div class="col-md-4 border-end">
                                                        <div class="text-center mb-3">
                                                            @if(!empty($pendeta->foto ?? data_get($pendeta, 'jemaat.foto')))
                                                            <img src="{{ asset('storage/'. ($pendeta->foto ?? data_get($pendeta, 'jemaat.foto')) ) }}"
                                                                class="img-fluid rounded shadow mb-3"
                                                                style="max-height:220px; object-fit:cover;">
                                                            @else
                                                            <div class="border rounded d-flex align-items-center justify-content-center"
                                                                style="height:220px;">
                                                                <span class="text-muted">Tidak ada foto</span>
                                                            </div>
                                                            @endif

                                                            <h5 class="fw-bold mb-0">
                                                                {{ $pendeta->nama ?? data_get($pendeta, 'jemaat.nama') ?? '-' }}
                                                            </h5>

                                                        </div>

                                                        <hr>

                                                        <div class="small">
                                                            <p class="mb-2"><strong>No. HP</strong><br>
                                                                {{ $pendeta->no_telp ?? data_get($pendeta, 'jemaat.no_telp') ?? '-' }}
                                                            </p>

                                                            <p class="mb-2"><strong>Jenis Kelamin</strong><br>
                                                                {{ $pendeta->jenis_kelamin ?? data_get($pendeta, 'jemaat.jenis_kelamin') ?? '-' }}
                                                            </p>

                                                            <p class="mb-2"><strong>Status</strong><br>
                                                                <span class="badge bg-success">
                                                                    {{ ucfirst(data_get($pendeta, 'status') ?? '-') }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- RIGHT: DETAIL -->
                                                    <div class="col-md-8">

                                                        <div class="mb-4">
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Data Pribadi
                                                            </h6>
                                                            <div class="row small">
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tempat Lahir</strong><br>
                                                                    {{ $pendeta->tempat_lahir ?? data_get($pendeta, 'jemaat.tempat_lahir') ?? '-' }}
                                                                </div>
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tanggal Lahir</strong><br>
                                                                    @php $tgl = $pendeta->tanggal_lahir ?? data_get($pendeta, 'jemaat.tanggal_lahir'); @endphp
                                                                    {{ $tgl ? \Carbon\Carbon::parse($tgl)->format('d M Y') : '-' }}
                                                                </div>
                                                                <div class="col-12">
                                                                    <strong>Alamat</strong><br>
                                                                    {{ $pendeta->alamat ?? data_get($pendeta, 'jemaat.alamat') ?? '-' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Informasi
                                                                Penugasan</h6>
                                                            <div class="row small">
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tanggal Tahbis</strong><br>
                                                                    {{ optional(data_get($pendeta, 'tanggal_tahbis') ?
                                                                    \Carbon\Carbon::parse(data_get($pendeta,
                                                                    'tanggal_tahbis')) : null)->format('d M Y') ?? '-'
                                                                    }}
                                                                </div>
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>No. SK Tahbisan</strong><br>
                                                                    {{ data_get($pendeta, 'no_sk_tahbis') ?? '-' }}
                                                                </div>
                                                                <div class="col-12">
                                                                    <strong>Keterangan</strong><br>
                                                                    {{ data_get($pendeta, 'keterangan') ?? '-' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Akun Terkait
                                                            </h6>
                                                            <p class="small mb-0">
                                                                {{ data_get($pendeta, 'user.name') ??
                                                                (data_get($pendeta, 'user_id') ? 'User ID:
                                                                '.data_get($pendeta, 'user_id') : '-') }}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('admin.pendeta.edit', $pendeta->id) }}" class="btn btn-warning btn-sm"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.pendeta.destroy', $pendeta->id) }}" method="post"
                                style="display:inline-block" onsubmit="return confirm('Hapus data pendeta ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
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