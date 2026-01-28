@extends('layouts.main')
@section('title', 'Data Penatua')

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
                        <h4>Total Penatua</h4>
                    </div>
                    <div class="card-body">
                        <strong id="totalPenatuaCount">-- Orang</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- pending-stat card removed (handled via Tambah Penatua button) -->
    </div>

    <div class="row mb-3">
       
        <div class="col text-right">
            <a href="{{ route('admin.penatua.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penatua
            </a>
        </div>
    </div>
</div>

<!-- Card Data Penatua -->
<div class="card">
    <div class="card-header">
        <h4>Daftar Penatua</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Penatua</th>
                        <th>Nama WIJK</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($penatuas ?? []) as $idx => $penatua)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $penatua->nama_lengkap }}</td>
                        <td>{{ $penatua->wijk->nama_wijk ?? '-' }}</td>
                        <td>{{ $penatua->no_hp }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm" title="Lihat" data-bs-toggle="modal"
                                data-bs-target="#showPenatuaModal-{{ $penatua->id }}">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- ================= SHOW MODAL PENATUA ================= -->
                            <div class="modal fade" id="showPenatuaModal-{{ $penatua->id }}" tabindex="-1"
                                aria-labelledby="showPenatuaLabel-{{ $penatua->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <div>
                                                <h5 class="modal-title fw-semibold"
                                                    id="showPenatuaLabel-{{ $penatua->id }}">Detail Penatua</h5>
                                                <small class="text-muted">{{ $penatua->nama_lengkap ??
                                                    ($penatua->jemaat->nama ?? '-') }}</small>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">

                                                    <!-- ================= LEFT : FOTO & INFO SINGKAT ================= -->
                                                    <div class="col-md-4 border-end">
                                                        <div class="text-center mb-3">
                                                            @if(!empty($penatua->jemaat->foto))
                                                            <img src="{{ asset('storage/'.$penatua->jemaat->foto) }}"
                                                                class="img-fluid rounded shadow mb-3"
                                                                style="max-height:220px; object-fit:cover;">
                                                            @else
                                                            <div class="border rounded d-flex align-items-center justify-content-center"
                                                                style="height:220px;">
                                                                <span class="text-muted">Tidak ada foto</span>
                                                            </div>
                                                            @endif

                                                            <h5 class="fw-bold mb-0">
                                                                {{ $penatua->nama_lengkap ?? ($penatua->jemaat->nama ??
                                                                '-') }}
                                                            </h5>

                                                        </div>

                                                        <hr>

                                                        <div class="small">
                                                            <p class="mb-2"><strong>No. HP</strong><br>
                                                                {{ $penatua->no_hp ?? ($penatua->jemaat->no_telp ?? '-')
                                                                }}
                                                            </p>

                                                            <p class="mb-2"><strong>Jenis Kelamin</strong><br>
                                                                {{ $penatua->jemaat->jenis_kelamin ?? '-' }}
                                                            </p>

                                                            <p class="mb-2"><strong>Status</strong><br>
                                                                <span class="badge bg-success">
                                                                    {{ ucfirst($penatua->status ?? '-') }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- ================= RIGHT : DATA DETAIL ================= -->
                                                    <div class="col-md-8">

                                                        <!-- ===== DATA PRIBADI ===== -->
                                                        <div class="mb-4">
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Data Pribadi
                                                            </h6>
                                                            <div class="row small">
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tempat Lahir</strong><br>
                                                                    {{ $penatua->jemaat->tempat_lahir ?? '-' }}
                                                                </div>
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tanggal Lahir</strong><br>
                                                                    {{
                                                                    optional($penatua->jemaat->tanggal_lahir)->format('d
                                                                    M Y') ?? '-' }}
                                                                </div>
                                                                <div class="col-12">
                                                                    <strong>Alamat</strong><br>
                                                                    {{ $penatua->jemaat->alamat ?? '-' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- ===== INFORMASI PENUGASAN ===== -->
                                                        <div class="mb-4">
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Informasi
                                                                Penugasan</h6>
                                                            <div class="row small">
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Wijk</strong><br>
                                                                    {{ $penatua->wijk->nama_wijk ?? '-' }}
                                                                </div>
                                                                <div class="col-sm-6 mb-2">
                                                                    <strong>Tanggal Tahbis</strong><br>
                                                                    {{ optional($penatua->tanggal_tahbis)->format('d M
                                                                    Y') ?? '-' }}
                                                                </div>
                                                                <div class="col-12">
                                                                    <strong>Keterangan</strong><br>
                                                                    {{ $penatua->keterangan ?? '-' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- ===== AKUN USER ===== -->
                                                        <div>
                                                            <h6 class="fw-semibold border-bottom pb-2 mb-3">Akun Terkait
                                                            </h6>
                                                            <p class="small mb-0">
                                                                {{ $penatua->user->name ?? ($penatua->user_id ? 'User
                                                                ID: '.$penatua->user_id : '-') }}
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

                            <!-- Edit button opens modal for this penatua -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPenatuaModal-{{ $penatua->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.penatua.destroy', $penatua->id) }}" method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus data Penatua ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <!-- ================= EDIT MODAL PENATUA ================= -->
                            <div class="modal fade" id="editPenatuaModal-{{ $penatua->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">

                                        <!-- HEADER -->
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Pt {{ $penatua->nama_lengkap }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <form action="{{ route('admin.penatua.update', $penatua->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- BODY -->
                                            <div class="modal-body">

                                                <!-- WIJK -->
                                                <div class="row mb-3 align-items-center">
                                                    <label class="col-md-4 col-form-label">
                                                        Wijk <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <select name="wijk_id" class="form-select" required>
                                                            <option value="">Pilih Wijk</option>
                                                            @foreach($wijks as $wijk)
                                                            <option value="{{ $wijk->id }}" {{ old('wijk_id', $penatua->
                                                                wijk_id) == $wijk->id ? 'selected' : '' }}>
                                                                {{ $wijk->nama_wijk }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- TANGGAL TAHBIS -->
                                                <div class="row mb-3 align-items-center">
                                                    <label class="col-md-4 col-form-label">
                                                        Tanggal Tahbis
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="date" name="tanggal_tahbis" class="form-control"
                                                            value="{{ old('tanggal_tahbis', optional($penatua->tanggal_tahbis)->format('Y-m-d')) }}">
                                                    </div>
                                                </div>

                                                <!-- STATUS -->
                                                <div class="row mb-3 align-items-center">
                                                    <label class="col-md-4 col-form-label">
                                                        Status <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <select name="status" class="form-select" required>
                                                            <option value="aktif" {{ $penatua->status == 'aktif' ?
                                                                'selected' : '' }}>Aktif</option>
                                                            <option value="nonaktif" {{ $penatua->status == 'nonaktif' ?
                                                                'selected' : '' }}>Nonaktif</option>
                                                            <option value="selesai" {{ $penatua->status == 'selesai' ?
                                                                'selected' : '' }}>Selesai</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- KETERANGAN -->
                                                <div class="row align-items-start">
                                                    <label class="col-md-4 col-form-label">
                                                        Keterangan
                                                    </label>
                                                    <div class="col-md-8">
                                                        <textarea name="keterangan" rows="3" class="form-control"
                                                            placeholder="Catatan tambahan...">{{ old('keterangan', $penatua->keterangan) }}</textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- FOOTER -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    Save
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>



                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data Penatua.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update total penatua count
        var totalCount = document.getElementById('totalPenatuaCount');
        if(totalCount) {
            totalCount.textContent = '{{ isset($penatuas) ? $penatuas->count() : 0 }} Orang';
        }
    });
</script>
@endpush



@endsection