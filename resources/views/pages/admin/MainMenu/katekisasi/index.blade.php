@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    <!-- Card Katekisasi -->
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-book-bible"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Katekisasi</h4>
                </div>
                <div class="card-body">
                    3 Peserta
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Katekisasi --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Katekisasi</h4>
                <div class="d-flex gap-2">
                    {{-- @if(!empty($pendingCount) && $pendingCount > 0)
                    <button id="pendingBtn" class="btn btn-warning">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    </button>
                    @endif --}}

                    <a href="{{ route('admin.pelayanan.katekisasi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Periode Katekisasi</th>
                            <th>Pembina</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Peserta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($katekisasis ?? [] as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->periode_ajaran }}</td>
                            <td>{{ optional($k->pendeta)->nama_lengkap ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') }}</td>
                            <td>{{ $k->pendaftaranSidis->count() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data katekisasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pending modal --}}
        <div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pendingModalLabel">Pendaftar Menunggu Persetujuan ({{ $pendingCount
                            ?? 0 }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if(empty($pendingCount) || $pendingCount == 0)
                        <p>Tidak ada pendaftar pending.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Wijk</th>
                                        <th>Tgl Daftar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRecent ?? [] as $idx => $p)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->wijk }}</td>
                                        <td>{{ optional($p->created_at)->format('d M Y H:i') }}</td>
                                        <td class="text-end">
                                            <form
                                                action="{{ route('admin.pelayanan.katekisasi.pendaftaran.reject', ['id' => $p->id]) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                <input type="hidden" name="catatan_admin" value="Ditolak oleh admin">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Tolak pendaftar ini?')">Tolak</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.pelayanan.katekisasi.pendaftaran.approve', ['id' => $p->id]) }}"
                                                method="POST" style="display:inline-block; margin-left:6px;">
                                                @csrf
                                                <input type="hidden" name="catatan_admin" value="Disetujui oleh admin">
                                                <button type="submit" class="btn btn-sm btn-success">Setuju</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Login --}}
    <div class="mt-3">
        @auth
        <p>Login sebagai: <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->role }})</p>
        @else
        <p>Belum login</p>
        @endauth
    </div>

    @push('script')
    <style>
        /* Ensure modal and backdrop appear above other overlays (very high to beat custom dropdowns) */
        .modal-backdrop {
            z-index: 99998 !important;
        }

        .modal {
            z-index: 99999 !important;
            position: fixed !important;
        }

        .modal.show {
            display: block !important;
        }
    </style>
    <script>
        (function initPendingModal(){
        function setup() {
            var btn = document.getElementById('pendingBtn');
            var modalEl = document.getElementById('pendingModal');
            if (!btn || !modalEl) return;

            // Use Bootstrap's Modal API to show modal programmatically
            var modalInstance = null;
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
            } catch (e) {
                console.warn('Bootstrap modal not available:', e);
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (modalInstance) {
                    try {
                        // move modal element to body to avoid ancestor stacking contexts
                        if (modalEl.parentNode !== document.body) {
                            document.body.appendChild(modalEl);
                        }
                        modalInstance.show();

                        // after bootstrap created backdrop, ensure z-index ordering
                        setTimeout(function(){
                            var backdrops = document.querySelectorAll('.modal-backdrop');
                            backdrops.forEach(function(b){ b.style.zIndex = '99998'; b.style.pointerEvents = 'auto'; });
                            modalEl.style.zIndex = '99999';
                            modalEl.style.pointerEvents = 'auto';
                        }, 50);
                    } catch(e){
                        console.warn('modal show error', e);
                    }
                    return;
                }

                // Fallback: toggle class and show backdrop
                if (modalEl.classList.contains('show')) return;
                // move modal to body to escape stacking contexts
                if (modalEl.parentNode !== document.body) {
                    document.body.appendChild(modalEl);
                }
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.style.zIndex = '99999';
                modalEl.style.pointerEvents = 'auto';
                document.body.classList.add('modal-open');
                // create backdrop if none
                if (!document.querySelector('.modal-backdrop')) {
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.style.zIndex = '99998';
                    backdrop.style.pointerEvents = 'auto';
                    document.body.appendChild(backdrop);
                } else {
                    // bump existing backdrop z-index
                    var existing = document.querySelectorAll('.modal-backdrop');
                    existing.forEach(function(b){ b.style.zIndex = '99998'; b.style.pointerEvents = 'auto'; });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setup);
        } else {
            // DOMContentLoaded already fired
            setup();
        }
    })();
    </script>
    @endpush

</section>

@endsection