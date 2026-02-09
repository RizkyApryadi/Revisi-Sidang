@extends('layouts.main')
@section('title', 'Renungan Harian')

@section('content')
<section class="section">
    <div class="section-header">
<style>
    /* simple context menu styling */
    #renungan-status-menu { position: fixed; z-index: 2000; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 0; box-shadow: 0 4px 16px rgba(0,0,0,0.08); display: none; min-width: 160px; }
    #renungan-status-menu button { display: block; width: 100%; text-align: left; padding: 8px 12px; background: transparent; border: none; cursor: pointer; }
    #renungan-status-menu button:hover { background: #f3f4f6; }
</style>

<div id="renungan-status-menu">
    <button id="renungan-status-action"></button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menu = document.getElementById('renungan-status-menu');
    const actionBtn = document.getElementById('renungan-status-action');
    const baseToggleUrl = "{{ url('pendeta/renungan') }}"; // will append /{id}/toggle-status
    const csrfToken = '{{ csrf_token() }}';

    function hideMenu() { menu.style.display = 'none'; }

    document.addEventListener('click', function(e){ if(!menu.contains(e.target)){ hideMenu(); } });
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape') hideMenu(); });

    document.querySelectorAll('.status-cell').forEach(function(td){
        td.addEventListener('contextmenu', function(e){
            e.preventDefault();
            const id = td.getAttribute('data-id');
            const status = td.getAttribute('data-status');
            const want = status === 'publish' ? 'draft' : 'publish';
            actionBtn.textContent = status === 'publish' ? 'Set to Draft' : 'Set to Publish';

            // position menu using viewport coordinates (fixed) and keep it inside the window
            const clientX = e.clientX;
            const clientY = e.clientY;
            menu.style.display = 'block';
            // allow the browser to render and get size
            requestAnimationFrame(function(){
                const mw = menu.offsetWidth;
                const mh = menu.offsetHeight;
                let left = clientX;
                let top = clientY;
                if (left + mw > window.innerWidth) left = Math.max(8, window.innerWidth - mw - 8);
                if (top + mh > window.innerHeight) top = Math.max(8, window.innerHeight - mh - 8);
                menu.style.left = left + 'px';
                menu.style.top = top + 'px';
            });

            actionBtn.onclick = function(){
                menu.style.display = 'none';
                const newLabel = want === 'publish' ? 'Publish' : 'Draft';
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Anda yakin ingin mengubah status menjadi ${newLabel}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal'
                }).then(function(result){
                    if(!result.isConfirmed) return;

                    const url = baseToggleUrl + '/' + id + '/toggle-status';
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(function(res){ return res.json(); }).then(function(data){
                        if(data && data.success){
                            const newStatus = data.status;
                            td.setAttribute('data-status', newStatus);
                            const span = td.querySelector('span.badge');
                            if(span){
                                span.textContent = newStatus === 'publish' ? 'Publish' : 'Draft';
                                span.classList.remove('badge-success','badge-warning');
                                span.classList.add(newStatus === 'publish' ? 'badge-success' : 'badge-warning');
                            }
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: `Status diubah menjadi ${newStatus === 'publish' ? 'Publish' : 'Draft'}.`, timer: 1400, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: (data && data.message) ? data.message : 'Gagal mengubah status.' });
                        }
                    }).catch(function(err){
                        Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Gagal menghubungi server.' });
                    });
                });
            };
        });
    });
});
</script>
        <h1>Renungan Harian</h1>
        <div class="section-header-button">
            <a href="{{ route('pendeta.renungan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Renungan
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Daftar Renungan</h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Konten</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($renungans as $renungan)
                            <tr>
                                <td>{{ $loop->iteration + (($renungans->currentPage()-1) * $renungans->perPage()) }}
                                </td>
                                <td>{{ $renungan->judul }}</td>
                                <td>{{ optional($renungan->tanggal)->format('d M Y') }}</td>
                                <td class="status-cell" data-id="{{ $renungan->id }}" data-status="{{ $renungan->status }}">
                                    @if($renungan->status === 'publish')
                                    <span class="badge badge-success">Publish</span>
                                    @else
                                    <span class="badge badge-warning">Draft</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($renungan->konten, 100, '...') }}</td>
                                <td>
                                    <a href="{{ route('pendeta.renungan.show', $renungan->id) }}"
                                        class="btn btn-info btn-sm" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('pendeta.renungan.edit', $renungan->id) }}"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('pendeta.renungan.destroy', $renungan->id) }}" method="POST"
                                        style="display:inline-block" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada renungan harian
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $renungans->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var form = btn.closest('form');
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Hapus renungan ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush