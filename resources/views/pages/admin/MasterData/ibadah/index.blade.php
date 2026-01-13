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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wartas as $index => $w)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-center">{{ $w->tanggal ? $w->tanggal->format('d-m-Y') : '' }}</td>
                            <td>{{ $w->nama_minggu }}</td>
                            <td style="white-space: normal;">{{ $w->pengumuman }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada warta jemaat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada jadwal ibadah.</td>
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
                                    <input type="text" name="ayat" class="form-control" placeholder="Contoh: Matius 2:1–12" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Liturgi</label>
                                    <input type="file" name="file" class="form-control">
                                    <small class="text-muted">PDF/DOC (opsional)</small>
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

        <style>
            /* Ensure modal sits above backdrop and is interactive */
            /* Very high z-index to overcome stacking-context issues */
            #modalCreateIbadah.modal.show {
                position: fixed !important;
                z-index: 99999 !important;
                top: 0; left: 0; right: 0; bottom: 0;
            }
            /* Backdrop slightly lower than modal */
            .modal-backdrop.show {
                z-index: 99998 !important;
            }
            /* Ensure dialog and its contents accept pointer events */
            #modalCreateIbadah .modal-dialog,
            #modalCreateIbadah .modal-content,
            #modalCreateIbadah .modal-body {
                pointer-events: auto !important;
            }
        </style>

        <script>
            (function () {
                var btn = document.getElementById('btnCreateIbadah');
                var modalEl = document.getElementById('modalCreateIbadah');
                var backdrop = null;

                function createBackdrop() {
                    var d = document.createElement('div');
                    d.className = 'modal-backdrop fade show';
                    d.style.zIndex = 99998;
                    return d;
                }

                function openModal() {
                    if (!modalEl) return;
                    // ensure modal is moved to document.body to avoid stacking-context issues
                    if (modalEl.parentNode !== document.body) {
                        document.body.appendChild(modalEl);
                    }

                    // create and append backdrop (below modal)
                    backdrop = createBackdrop();
                    document.body.appendChild(backdrop);

                    // raise modal above backdrop and make it interactive
                    modalEl.style.zIndex = 100000;
                    modalEl.style.display = 'block';
                    modalEl.classList.add('show');
                    modalEl.setAttribute('aria-modal', 'true');
                    modalEl.removeAttribute('aria-hidden');
                    document.body.classList.add('modal-open');

                    // prevent clicks inside modal from bubbling to backdrop
                    modalEl.addEventListener('click', function (ev) { ev.stopPropagation(); }, { capture: true });
                    // focus first input
                    var focusable = modalEl.querySelector('input,button,select,textarea,[tabindex]:not([tabindex="-1"])');
                    if (focusable) focusable.focus();
                }

                function closeModal() {
                    if (!modalEl) return;
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    modalEl.setAttribute('aria-hidden', 'true');
                    modalEl.removeAttribute('aria-modal');
                    document.body.classList.remove('modal-open');
                    if (backdrop && backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);
                    backdrop = null;
                }

                // open on button click
                btn && btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal();
                });

                // close on elements with data-bs-dismiss or .btn-close
                document.addEventListener('click', function (e) {
                    var tgt = e.target;
                    if (!tgt) return;
                    if (tgt.closest('[data-bs-dismiss]') || tgt.classList.contains('btn-close')) {
                        e.preventDefault();
                        closeModal();
                    }
                    // click on backdrop should close
                    if (backdrop && (tgt === backdrop || tgt.closest('.modal-backdrop'))) {
                        closeModal();
                    }
                });

                // close on Escape
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && backdrop) {
                        closeModal();
                    }
                });
            })();
        </script>


</section>
@endsection