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

        <!-- pending-stat card removed (handled via Tambah Penatua button) -->
    </div>

    <div class="row mb-3">
        <div class="col text-right">
            <button id="pendetaPendingBtn" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#pendetaPendingModal">
                <i class="fas fa-plus"></i> Tambah Pendeta <span id="pendetaPendingCount"
                    class="badge badge-light ml-2">{{ $pendingCount }}</span>
            </button>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendetas as $idx => $pendeta)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $pendeta->nama_lengkap }}</td>
                        <td>{{ $pendeta->no_hp }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- Edit button opens modal for this pendeta -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPendetaModal-{{ $pendeta->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Edit modal for this pendeta -->
                            <div class="modal fade" id="editPendetaModal-{{ $pendeta->id }}" tabindex="-1" aria-labelledby="editPendetaModalLabel-{{ $pendeta->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPendetaModalLabel-{{ $pendeta->id }}">Edit Data Pendeta</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.pendeta.update', $pendeta->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" name="nama_lengkap" class="form-control"
                                                            value="{{ old('nama_lengkap', $pendeta->nama_lengkap) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>No. HP</label>
                                                        <input type="text" name="no_hp" class="form-control"
                                                            value="{{ old('no_hp', $pendeta->no_hp) }}">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Jenis Kelamin</label>
                                                        <select name="jenis_kelamin" class="form-control">
                                                            <option value="Laki-laki" {{ (old('jenis_kelamin',
                                                                $pendeta->jenis_kelamin) == 'Laki-laki') ? 'selected' :
                                                                '' }}>Laki-laki</option>
                                                            <option value="Perempuan" {{ (old('jenis_kelamin',
                                                                $pendeta->jenis_kelamin) == 'Perempuan') ? 'selected' :
                                                                '' }}>Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" class="form-control"
                                                            value="{{ old('tempat_lahir', $pendeta->tempat_lahir) }}">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" class="form-control"
                                                            value="{{ old('tanggal_lahir', $pendeta->tanggal_lahir?->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Alamat</label>
                                                        <input type="text" name="alamat" class="form-control"
                                                            value="{{ old('alamat', $pendeta->alamat) }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Foto (opsional)</label>
                                                    <input type="file" name="foto" class="form-control-file">
                                                    @if($pendeta->foto)
                                                    <small class="form-text text-muted">Foto saat ini: <a
                                                            href="{{ asset('storage/'.$pendeta->foto) }}"
                                                            target="_blank">lihat</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.pendeta.destroy', $pendeta->id) }}" method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus data Pendeta ini?');">
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
                        <td colspan="4" class="text-center">Belum ada data Pendeta.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pending modal -->
<div class="modal fade" id="pendetaPendingModal" tabindex="-1" aria-labelledby="pendetaPendingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pendetaPendingModalLabel">Akun Pendeta yang Perlu Diisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($pendingPendetas->count() > 0)
            <div class="modal-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingPendetas as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('admin.pendeta.create') }}?user_id={{ $user->id }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="modal-body">
                <p class="text-center text-muted">Semua user dengan role Pendeta sudah memiliki data profil.</p>
            </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection