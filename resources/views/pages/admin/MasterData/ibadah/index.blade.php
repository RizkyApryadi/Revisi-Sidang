@extends('layouts.main')

@section('title', 'Warta & Ibadah')

@section('content')
<section class="section">

    {{-- ================= WARTA JEMAAT ================= --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Warta Jemaat</h4>
            <a href="{{ route('admin.ibadah.warta.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Warta
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Tanggal</th>
                            <th>Nama Minggu</th>
                            <th>Pengumuman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wartas as $index => $w)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-center">{{ $w->tanggal ? $w->tanggal->format('d-m-Y') : '' }}</td>
                            <td>{{ $w->nama_minggu }}</td>
                            <td style="white-space: normal;">{{ $w->deskripsi }}</td>
                            <td class="text-center" style="width:110px">
                                <a href="{{ route('admin.ibadah.warta.show', $w->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                    <button type="button" class="btn btn-sm btn-outline-success btn-edit-warta" title="Edit"
                                        data-id="{{ $w->id }}"
                                        data-nama_minggu="{{ e($w->nama_minggu) }}"
                                        data-tanggal="{{ $w->tanggal ? $w->tanggal->format('Y-m-d') : '' }}"
                                        data-deskripsi='@json($w->deskripsi)'
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                <form action="{{ route('admin.ibadah.warta.destroy', $w->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus warta ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada warta jemaat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            <!-- Modal: PDF Text Viewer -->
            <div class="modal fade" id="modalPdfTextView" tabindex="-1" aria-labelledby="modalPdfTextViewLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPdfTextViewLabel">Teks PDF</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <pre id="pdfTextContent" style="white-space:pre-wrap; word-wrap:break-word;">-</pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

    {{-- ================= IBADAH ================= --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Jadwal Ibadah</h4>
            <button id="btnCreateIbadah" class="btn btn-primary btn-sm" type="button">
                <i class="fas fa-plus"></i> Tambah Ibadah
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Minggu</th>
                            <th>Pendeta</th>
                            <th>Waktu</th>
                            <th>Tema</th>
                            <th>Ayat</th>
                            <th>File</th>
                            <th>Teks</th>
                            <th style="width:8%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ibadahs as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($item->warta)->nama_minggu ?? '-' }}</td>
                            <td>{{ optional($item->pendeta)->nama_lengkap ?? '-' }}</td>
                            <td class="text-center">{{ $item->waktu }}</td>
                            <td>{{ $item->tema }}</td>
                            <td>{{ $item->ayat }}</td>
                            <td>
                                @if($item->file)
                                <a href="{{ route('admin.ibadah.file', $item->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat</a>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center" style="width:90px">
                                @if($item->pdf_text)
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-show-pdf-text" data-pdf='@json($item->pdf_text)'>Lihat Teks</button>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    @if($item->file)
                                        <a href="{{ route('admin.ibadah.file', $item->id) }}" class="btn btn-sm btn-outline-primary mx-1" title="Lihat" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.ibadah.edit', $item->id) }}" class="btn btn-sm btn-outline-primary mx-1" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-success btn-edit-ibadah mx-1" title="Edit"
                                        data-id="{{ $item->id }}"
                                        data-warta_id="{{ $item->warta_id }}"
                                        data-pendeta_id="{{ $item->pendeta_id }}"
                                        data-waktu="{{ $item->waktu }}"
                                        data-tema="{{ e($item->tema) }}"
                                        data-ayat="{{ e($item->ayat) }}"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                        <form action="{{ route('admin.ibadah.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus jadwal ibadah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger mx-1" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada jadwal ibadah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= MODAL CREATE IBADAH ================= --}}
    <div class="modal fade" id="modalCreateIbadah" tabindex="-1" role="dialog" aria-labelledby="modalCreateIbadahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateIbadahLabel">Tambah Jadwal Ibadah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.ibadah.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Warta / Nama Minggu</label>
                                    <select name="warta_id" class="form-control" required>
                                        <option value="">-- Pilih Warta --</option>
                                        @foreach($wartas as $w)
                                        <option value="{{ $w->id }}">{{ $w->tanggal ? $w->tanggal->format('d-m-Y') . ' - ' : '' }}{{ $w->nama_minggu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pendeta</label>
                                    <select name="pendeta_id" class="form-control" required>
                                        <option value="">-- Pilih Pendeta --</option>
                                        @foreach($pendetas as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Waktu Ibadah</label>
                                    <input type="time" name="waktu" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Tema</label>
                                    <input type="text" name="tema" class="form-control" placeholder="Contoh: Terang Kristus" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ayat Alkitab</label>
                                    <input type="text" name="ayat" class="form-control" placeholder="Contoh: Matius 2:1–12">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Liturgi (PDF)</label>
                                    <input type="file" name="file" id="ibadahFileInput" class="form-control" accept="application/pdf">
                                    <small class="text-muted">PDF (opsional). Jika PDF diupload, teks PDF akan otomatis terisi ke kolom bawah.</small>
                                </div>
                            </div>

                            

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- ================= MODAL EDIT IBADAH ================= --}}
    <div class="modal fade" id="modalEditIbadah" tabindex="-1" role="dialog" aria-labelledby="modalEditIbadahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditIbadahLabel">Edit Jadwal Ibadah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditIbadah" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Warta / Nama Minggu</label>
                                <select name="warta_id" id="edit_ibadah_warta_id" class="form-control" required>
                                    <option value="">-- Pilih Warta --</option>
                                    @foreach($wartas as $w)
                                    <option value="{{ $w->id }}">{{ $w->tanggal ? $w->tanggal->format('d-m-Y') . ' - ' : '' }}{{ $w->nama_minggu }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Pendeta</label>
                                <select name="pendeta_id" id="edit_ibadah_pendeta_id" class="form-control" required>
                                    <option value="">-- Pilih Pendeta --</option>
                                    @foreach($pendetas as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Waktu Ibadah</label>
                                <input type="time" name="waktu" id="edit_ibadah_waktu" class="form-control" required>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label>Tema</label>
                                <input type="text" name="tema" id="edit_ibadah_tema" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Ayat Alkitab</label>
                                <input type="text" name="ayat" id="edit_ibadah_ayat" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>File Liturgi (PDF)</label>
                                <input type="file" name="file" id="edit_ibadah_file" class="form-control" accept="application/pdf">
                                <small class="text-muted">Upload baru untuk mengganti file (opsional).</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- ================= MODAL EDIT WARTA ================= --}}
    <div class="modal fade" id="modalEditWarta" tabindex="-1" role="dialog" aria-labelledby="modalEditWartaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditWartaLabel">Edit Warta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditWarta" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="edit_warta_tanggal">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Minggu</label>
                            <input type="text" name="nama_minggu" class="form-control" id="edit_warta_nama_minggu">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi / Pengumuman</label>
                            <textarea name="deskripsi" class="form-control" id="edit_warta_deskripsi" rows="6"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Minimal adjustments: ensure modals are interactive and rely on Bootstrap for backdrop handling */
        /* Keep z-index near Bootstrap defaults to avoid global override issues */
        .modal {
            pointer-events: auto;
        }
    </style>

    <script>
        (function () {
            var btn = document.getElementById('btnCreateIbadah');
            var modalEl = document.getElementById('modalCreateIbadah');
            if (!btn || !modalEl) return;

            // Move modal to body to avoid stacking-context issues
            if (modalEl.parentNode !== document.body) {
                document.body.appendChild(modalEl);
            }

            var bsCreateModal = new bootstrap.Modal(modalEl);

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                bsCreateModal.show();
            });
        })();
    </script>

    <script>
        (function () {
            var modalEl = document.getElementById('modalPdfTextView');
            var pdfPre = document.getElementById('pdfTextContent');

            // Move modal to document.body to avoid stacking-context/backdrop issues
            if (modalEl && modalEl.parentNode !== document.body) {
                document.body.appendChild(modalEl);
            }

            var bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-show-pdf-text');
                if (!btn) return;
                var text = btn.getAttribute('data-pdf');
                try {
                    // data-pdf is JSON encoded in blade
                    text = JSON.parse(text);
                } catch (err) {
                    // leave as-is
                }
                if (!text) text = '-';
                if (pdfPre) pdfPre.textContent = text;
                if (bsModal) bsModal.show();
            });
        })();
    </script>

    <script>
        // Edit Warta modal population and show
        (function () {
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-edit-warta');
                if (!btn) return;

                e.preventDefault();
                var id = btn.getAttribute('data-id');
                var tanggal = btn.getAttribute('data-tanggal') || '';
                var nama = btn.getAttribute('data-nama_minggu') || '';
                var deskripsi = btn.getAttribute('data-deskripsi') || '';

                var modalEl = document.getElementById('modalEditWarta');
                var form = document.getElementById('formEditWarta');
                if (!modalEl || !form) return;

                form.action = '/admin/ibadah/warta/' + id;
                document.getElementById('edit_warta_tanggal').value = tanggal;
                document.getElementById('edit_warta_nama_minggu').value = nama;
                document.getElementById('edit_warta_deskripsi').value = deskripsi;

                var bs = new bootstrap.Modal(modalEl);
                bs.show();
            });
        })();
    </script>

    <script>
        // Edit Ibadah modal population and show
        (function () {
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.btn-edit-ibadah');
                if (!btn) return;

                e.preventDefault();
                var id = btn.getAttribute('data-id');
                var wartaId = btn.getAttribute('data-warta_id');
                var pendetaId = btn.getAttribute('data-pendeta_id');
                var waktu = btn.getAttribute('data-waktu') || '';
                var tema = btn.getAttribute('data-tema') || '';
                var ayat = btn.getAttribute('data-ayat') || '';

                var modalEl = document.getElementById('modalEditIbadah');
                var form = document.getElementById('formEditIbadah');
                if (!modalEl || !form) return;

                form.action = '/admin/ibadah/' + id;
                document.getElementById('edit_ibadah_warta_id').value = wartaId;
                document.getElementById('edit_ibadah_pendeta_id').value = pendetaId;
                document.getElementById('edit_ibadah_waktu').value = waktu;
                document.getElementById('edit_ibadah_tema').value = tema;
                document.getElementById('edit_ibadah_ayat').value = ayat;

                var bs = new bootstrap.Modal(modalEl);
                bs.show();
            });
        })();
    </script>

    <script>
        // Ensure edit modals are appended to document.body to avoid overlay/z-index issues
        (function () {
            ['modalEditIbadah', 'modalEditWarta'].forEach(function (id) {
                var el = document.getElementById(id);
                if (!el) return;
                if (el.parentNode !== document.body) document.body.appendChild(el);
            });
        })();
    </script>

    <style>
        /* Fallback: slightly increase modal z-index if backdrop overlaps */
        .modal.show {
            z-index: 1060;
        }
        .modal-backdrop.show {
            z-index: 1055;
        }
    </style>

    


</section>
@endsection