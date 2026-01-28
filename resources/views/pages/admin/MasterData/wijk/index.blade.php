@extends('layouts.main')
@section('title', 'Dashboard WIJK')

@section('content')

{{-- ===== FIX BACKDROP MODAL (LEBIH GELAP, TIDAK MERUSAK MODAL) ===== --}}
<style>
    .modal-backdrop.show {
        opacity: 0.8 !important;
        /* default bootstrap: 0.5 */
    }
</style>

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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- data wijk --}}
                    @forelse($wijks as $index => $wijk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $wijk->nama_wijk ?? '-' }}</td>
                        <td>{{ $wijk->keterangan ?? '-' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-warning ms-1 btn-edit" title="Ubah"
                                data-id="{{ $wijk->id }}" data-nama="{{ $wijk->nama_wijk }}"
                                data-keterangan="{{ $wijk->keterangan }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button" class="btn btn-sm btn-danger ms-1 btn-delete" title="Hapus"
                                data-id="{{ $wijk->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data WIJK.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah WIJK -->
<div class="modal fade" id="modalTambahWIJK" tabindex="-1" aria-labelledby="modalTambahWIJKLabel" aria-hidden="true">
    <div class="modal-dialog" role="document"> {{-- JANGAN centered --}}
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahWIJKLabel">Tambah Data WIJK</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formTambahWijk" action="{{ route('admin.wijk.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama WIJK</label>
                        <input type="text" name="nama_wijk" class="form-control" placeholder="Contoh: WIJK V" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi WIJK</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                            placeholder="Keterangan wilayah pelayanan WIJK"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('formTambahWijk').submit();">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Edit WIJK -->
<div class="modal fade" id="modalEditWIJK" tabindex="-1" aria-labelledby="modalEditWIJKLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditWIJKLabel">Ubah Data WIJK</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formEditWijk" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama WIJK</label>
                        <input type="text" name="nama_wijk" class="form-control" placeholder="Contoh: WIJK V" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi WIJK</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                            placeholder="Keterangan wilayah pelayanan WIJK"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('formEditWijk').submit();">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="formDeleteWijk" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
            // Edit button opens modal and populates form
            document.querySelectorAll('.btn-edit').forEach(function(btn){
            btn.addEventListener('click', function(){
                const id = this.dataset.id;
                const nama = this.dataset.nama || '';
                const keterangan = this.dataset.keterangan || '';

                const form = document.getElementById('formEditWijk');
                form.action = '/admin/wijk/' + id;
                form.querySelector('input[name="nama_wijk"]').value = nama;
                form.querySelector('textarea[name="keterangan"]').value = keterangan;

                // remove any existing backdrops to avoid double overlay (makes modal too dark)
                document.querySelectorAll('.modal-backdrop').forEach(function(el){ el.parentNode && el.parentNode.removeChild(el); });

                const modalEl = document.getElementById('modalEditWIJK');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
            });

            // Delete button confirmation
            document.querySelectorAll('.btn-delete').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    if (!confirm('Yakin ingin menghapus WIJK ini?')) return;
                    const form = document.getElementById('formDeleteWijk');
                    form.action = '/admin/wijk/' + id;
                    form.submit();
                });
            });
        });
</script>
@endpush

@endsection