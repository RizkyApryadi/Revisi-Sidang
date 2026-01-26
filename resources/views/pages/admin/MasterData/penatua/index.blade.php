@extends('layouts.main')
@section('title', 'Data Penatua')

@section('content')

<style>
    /* Compact modal spacing for edit/pending modals in this view */
    .compact-modal .form-group {
        margin-bottom: 6px;
    }

    .compact-modal .form-row {
        margin-bottom: 6px;
    }

    .compact-modal .form-control,
    .compact-modal .form-control-file,
    .compact-modal textarea {
        padding: .35rem .5rem;
        font-size: .95rem;
    }

    .compact-modal .modal-footer {
        padding: .5rem .75rem;
    }

    .compact-modal .modal-header {
        padding: .5rem .75rem;
    }
</style>

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
            <button id="penatuaPendingBtn" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#penatuaPendingModal">
                <i class="fas fa-plus"></i> Tambah Penatua <span id="penatuaPendingCount"
                    class="badge badge-light ml-2">{{ $pendingCount }}</span>
            </button>
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
                    @forelse($penatuas as $idx => $penatua)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $penatua->nama_lengkap }}</td>
                        <td>{{ $penatua->wijk->nama_wijk ?? '-' }}</td>
                        <td>{{ $penatua->no_hp }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>

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

                            <!-- Edit modal for this penatua -->
                            <div class="modal fade" id="editPenatuaModal-{{ $penatua->id }}" tabindex="-1" aria-labelledby="editPenatuaModalLabel-{{ $penatua->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content compact-modal">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPenatuaModalLabel-{{ $penatua->id }}">Edit Data Penatua</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.penatua.update', $penatua->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" name="nama_lengkap" class="form-control"
                                                            value="{{ old('nama_lengkap', $penatua->nama_lengkap) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Wijk</label>
                                                        <select name="wijk_id" class="form-control">
                                                            <option value="">- Pilih Wijk -</option>
                                                            @foreach($wijks as $wijk)
                                                            <option value="{{ $wijk->id }}" {{ (old('wijk_id',
                                                                $penatua->wijk_id) == $wijk->id) ? 'selected' : '' }}>{{
                                                                $wijk->nama_wijk }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Jenis Kelamin</label>
                                                        <select name="jenis_kelamin" class="form-control">
                                                            <option value="Laki-laki" {{ (old('jenis_kelamin',
                                                                $penatua->jenis_kelamin) == 'Laki-laki') ? 'selected' :
                                                                '' }}>Laki-laki</option>
                                                            <option value="Perempuan" {{ (old('jenis_kelamin',
                                                                $penatua->jenis_kelamin) == 'Perempuan') ? 'selected' :
                                                                '' }}>Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>No. HP</label>
                                                        <input type="text" name="no_hp" class="form-control"
                                                            value="{{ old('no_hp', $penatua->no_hp) }}">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" class="form-control"
                                                            value="{{ old('tempat_lahir', $penatua->tempat_lahir) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" class="form-control"
                                                            value="{{ old('tanggal_lahir', $penatua->tanggal_lahir?->format('Y-m-d')) }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea name="alamat"
                                                        class="form-control">{{ old('alamat', $penatua->alamat) }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>Foto (opsional)</label>
                                                    <input type="file" name="foto" class="form-control-file">
                                                    @if($penatua->foto)
                                                    <small class="form-text text-muted">Foto saat ini: <a
                                                            href="{{ asset('storage/'.$penatua->foto) }}"
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

<!-- Pending modal -->
<div class="modal fade" id="penatuaPendingModal" tabindex="-1" aria-labelledby="penatuaPendingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content compact-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="penatuaPendingModalLabel">Akun Penatua yang Perlu Diisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($pendingPenatuas->count() > 0)
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
                        @foreach($pendingPenatuas as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('admin.penatua.create') }}?user_id={{ $user->id }}"
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
                <p class="text-center text-muted">Semua user dengan role Penatua sudah memiliki data profil.</p>
            </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
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