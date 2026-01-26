@extends('layouts.main')
@section('title', 'Dashboard WIJK')

@section('content')

<div class="row">
    <!-- Total WIJK -->
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-church"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total WIJK</h4>
                </div>
                <div class="card-body">
                    {{ isset($wijks) ? $wijks->count() : 0 }} WIJK
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Tabel WIJK -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Data WIJK HKBP Soposurung</h4>

        <!-- Button Tambah -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahWIJK">
            <i class="fas fa-plus"></i> Tambah WIJK
        </button>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama WIJK</th>
                        <th>Keterangan</th>
                        <th>Penatua</th>
                        <th>Jumlah KK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wijks as $index => $wijk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $wijk->nama_wijk }}</td>
                        <td>{{ $wijk->keterangan ?? '-' }}</td>
                        {{-- <td>{{ $wijk->penatuas->first()->nama_lengkap ?? '-' }}</td> --}}
                        <td>-</td>
                        <td>-</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Edit button -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editWijkModal-{{ $wijk->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete form -->
                                <form action="{{ route('admin.wijk.destroy', $wijk->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus WIJK ini?');" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal for Wijk -->
                    <div class="modal fade" id="editWijkModal-{{ $wijk->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Data WIJK</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <form id="formEditWijk-{{ $wijk->id }}"
                                    action="{{ route('admin.wijk.update', $wijk->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Nama WIJK</label>
                                            <input type="text" name="nama_wijk" class="form-control"
                                                value="{{ old('nama_wijk', $wijk->nama_wijk) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Deskripsi WIJK</label>
                                            <textarea name="keterangan" class="form-control"
                                                rows="3">{{ old('keterangan', $wijk->keterangan) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                            Simpan</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data WIJK.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah WIJK -->
<div class="modal fade" id="modalTambahWIJK" tabindex="-1" role="dialog" aria-labelledby="modalTambahWIJKLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahWIJKLabel">Tambah Data WIJK</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="formTambahWijk" action="{{ route('admin.wijk.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama WIJK</label>
                        <input type="text" name="nama_wijk" class="form-control" placeholder="Contoh: WIJK V"
                            value="{{ old('nama_wijk') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi WIJK</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                            placeholder="Keterangan wilayah pelayanan WIJK">{{ old('keterangan') }}</textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('formTambahWijk').submit();">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>


@endsection