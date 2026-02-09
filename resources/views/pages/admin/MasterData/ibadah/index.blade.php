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
                        @forelse($wartas ?? [] as $idx => $w)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $w->tanggal ? \Carbon\Carbon::parse($w->tanggal)->translatedFormat('j F Y') : '-' }}
                            </td>
                            <td>{{ $w->nama_minggu }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($w->pengumuman, 120) }}</td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <a href="#" class="btn btn-info btn-sm me-1" title="Lihat" data-bs-toggle="modal"
                                        data-bs-target="#showWartaModal-{{ $w->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.ibadah.warta.edit', $w->id) }}"
                                        class="btn btn-warning btn-sm me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.ibadah.warta.destroy', $w->id) }}" method="POST"
                                        style="display:inline-block; margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus"
                                            onclick="return confirm('Hapus warta ini?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- SHOW MODAL WARTA -->
                                <div class="modal fade show-warta-modal" id="showWartaModal-{{ $w->id }}" tabindex="-1"
                                    aria-labelledby="showWartaLabel-{{ $w->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <div>
                                                    <h5 class="modal-title fw-semibold"
                                                        id="showWartaLabel-{{ $w->id }}">Detail Warta</h5>
                                                    <small class="text-muted">{{ $w->nama_minggu ?? '-' }}</small>
                                                </div>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="row small">
                                                        <div class="col-12 mb-2">
                                                            <strong>Tanggal</strong><br>
                                                            {{ $w->tanggal ?
                                                            \Carbon\Carbon::parse($w->tanggal)->translatedFormat('j F
                                                            Y') : '-' }}
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <strong>Nama Minggu</strong><br>
                                                            {{ $w->nama_minggu ?? '-' }}
                                                        </div>
                                                        <div class="col-12">
                                                            <strong>Pengumuman</strong><br>
                                                            @php
                                                            $raw = $w->pengumuman ?? '';
                                                            $decoded = $raw ? html_entity_decode($raw) : '';
                                                            $hasHtml = $decoded && preg_match('/<\/?[a-z][\s\S]*>/i', $decoded);
                                                                @endphp

                                                                @if($hasHtml)
                                                                {{-- already HTML, if it contains a table render inside
                                                                an iframe to avoid CSS conflicts --}}
                                                                @php $containsTable = stripos($decoded, '<table')
                                                                    !==false; @endphp @if($containsTable) @php
                                                                    $dataUrl='data:text/html;base64,' .
                                                                    base64_encode($decoded); @endphp <div
                                                                    class="table-responsive">
                                                                    <iframe src="{{ $dataUrl }}"
                                                                        style="width:100%;min-height:360px;border:0;"
                                                                        sandbox="allow-same-origin"></iframe>
                                                        </div>
                                                        @else
                                                        {!! $decoded !!}
                                                        @endif
                                                        @elseif(trim($decoded) === '')
                                                        -
                                                        @else
                                                        @php
                                                        $lines = preg_split('/\r\n|\r|\n/', $decoded);
                                                        $useTab = false; $usePipe = false; $useMulti = false;
                                                        foreach($lines as $ln) {
                                                        if(strpos($ln, "\t") !== false) { $useTab = true; break; }
                                                        if(strpos($ln, '|') !== false) { $usePipe = true; break; }
                                                        }
                                                        if(!$useTab && !$usePipe) {
                                                        foreach($lines as $ln) { if(preg_match('/\s{2,}/', $ln)) {
                                                        $useMulti = true; break; } }
                                                        }
                                                        @endphp

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                @foreach($lines as $r)
                                                                @php $r = trim($r); if($r === '') continue; @endphp
                                                                @php
                                                                if($useTab) $cols = explode("\t", $r);
                                                                elseif($usePipe) $cols = explode('|', $r);
                                                                elseif($useMulti) $cols = preg_split('/\s{2,}/', $r);
                                                                else $cols = preg_split('/\s+/', $r);
                                                                @endphp
                                                                <tr>
                                                                    @foreach($cols as $c)
                                                                    <td>{!! nl2br(e(trim($c))) !!}</td>
                                                                    @endforeach
                                                                </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                        @endif
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
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada warta.</td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modal: PDF Text Viewer -->
    <div class="modal fade" id="modalPdfTextView" tabindex="-1" aria-labelledby="modalPdfTextViewLabel"
        aria-hidden="true">
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
            <button id="btnCreateIbadah" class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#modalCreateIbadah">
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
                            <th style="width:8%">Aksi</th>
                        </tr>
                    </thead>


                    </tbody>
                    <tbody>
                        @forelse($ibadahs ?? [] as $idx => $i)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $i->nama_minggu ?? '-' }}</td>
                            <td>{{ $i->pendeta_name ?? ('Pendeta #' . ($i->pendeta_id ?? '-')) }}</td>
                            <td>{{ $i->waktu ?? '-' }}</td>
                            <td>{{ $i->tema ?? '-' }}</td>
                            <td>{{ $i->ayat ?? '-' }}</td>
                            <td>
                                @if(!empty($i->tata_ibadah))
                                <a href="{{ asset('storage/' . $i->tata_ibadah) }}" target="_blank"
                                    class="btn btn-sm btn-secondary">Lihat</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-edit-ibadah" data-id="{{ $i->id }}"
                                    data-warta_id="{{ $i->warta_id }}" data-pendeta_id="{{ $i->pendeta_id }}"
                                    data-waktu="{{ $i->waktu }}" data-tema="{{ e($i->tema) }}"
                                    data-ayat="{{ e($i->ayat) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada jadwal ibadah.</td>
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
                                        @foreach($wartas ?? [] as $w)
                                        <option value="{{ $w->id }}">{{ $w->nama_minggu }} — {{ $w->tanggal ?
                                            \Carbon\Carbon::parse($w->tanggal)->format('j M Y') : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pendeta</label>
                                    <select name="pendeta_id" class="form-control" required>
                                        <option value="">-- Pilih Pendeta --</option>
                                        @if(!empty($pendetas) && count($pendetas))
                                        @foreach($pendetas as $p)
                                        <option value="{{ $p->id }}">{{ $p->name ?? $p->nama ?? 'Pendeta #'.$p->id }}
                                        </option>
                                        @endforeach
                                        @else
                                        @php
                                        $fallback = \App\Models\User::where('role', 'pendeta')->get();
                                        @endphp
                                        @foreach($fallback as $p)
                                        <option value="{{ $p->id }}">{{ $p->name ?? $p->email ?? 'Pendeta #'.$p->id }}
                                        </option>
                                        @endforeach
                                        @endif
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
                                    <input type="text" name="tema" class="form-control"
                                        placeholder="Contoh: Terang Kristus" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ayat Alkitab</label>
                                    <input type="text" name="ayat" class="form-control"
                                        placeholder="Contoh: Matius 2:1–12">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Liturgi (PDF)</label>
                                    <input type="file" name="file" id="ibadahFileInput" class="form-control"
                                        accept="application/pdf">
                                    <small class="text-muted">PDF (opsional). Jika PDF diupload, teks PDF akan otomatis
                                        terisi ke kolom bawah.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto (opsional)</label>
                                    <input type="file" name="photo" id="ibadahPhotoInput" class="form-control"
                                        accept="image/*">
                                    <small class="text-muted">Gambar (JPG/PNG). Opsional — akan ditampilkan pada halaman
                                        jadwal.</small>
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
    <div class="modal fade" id="modalEditIbadah" tabindex="-1" role="dialog" aria-labelledby="modalEditIbadahLabel"
        aria-hidden="true">
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

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Pendeta</label>
                                <select name="pendeta_id" id="edit_ibadah_pendeta_id" class="form-control" required>
                                    <option value="">-- Pilih Pendeta --</option>

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
                                <input type="file" name="file" id="edit_ibadah_file" class="form-control"
                                    accept="application/pdf">
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
    <div class="modal fade" id="modalEditWarta" tabindex="-1" role="dialog" aria-labelledby="modalEditWartaLabel"
        aria-hidden="true">
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
                            <textarea name="deskripsi" class="form-control" id="edit_warta_deskripsi"
                                rows="6"></textarea>
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

            // Move any per-row showWartaModal-* modals to document.body as well
            try {
                var showModals = document.querySelectorAll('[id^="showWartaModal-"]');
                showModals.forEach(function (m) {
                    if (m.parentNode !== document.body) document.body.appendChild(m);
                });
            } catch (e) {
                // silent
            }
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

        /* Warta show modal tweaks: make a bit taller and wider so long tables look balanced */
        .show-warta-modal .modal-dialog {
            max-width: 900px;
        }

        .show-warta-modal .modal-body {
            min-height: 320px;
            max-height: calc(100vh - 200px);
            overflow: auto;
        }

        .show-warta-modal iframe {
            min-height: 360px;
        }
    </style>




</section>
@endsection