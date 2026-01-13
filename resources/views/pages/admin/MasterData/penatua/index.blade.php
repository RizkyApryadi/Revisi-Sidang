@extends('layouts.main')
@section('title', 'Data Penatua')

@section('content')

<style>
    /* Compact modal spacing for edit/pending modals in this view */
    .compact-modal .form-group { margin-bottom: 6px; }
    .compact-modal .form-row { margin-bottom: 6px; }
    .compact-modal .form-control,
    .compact-modal .form-control-file,
    .compact-modal textarea { padding: .35rem .5rem; font-size: .95rem; }
    .compact-modal .modal-footer { padding: .5rem .75rem; }
    .compact-modal .modal-header { padding: .5rem .75rem; }
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
            <button id="penatuaPendingBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penatua <span id="penatuaPendingCount"
                    class="badge badge-light ml-2">0</span>
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
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPenatuaModal-{{ $penatua->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.penatua.destroy', $penatua->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus data Penatua ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <!-- Edit modal for this penatua -->
                            <div class="modal fade" id="editPenatuaModal-{{ $penatua->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content compact-modal">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Penatua</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.penatua.update', $penatua->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $penatua->nama_lengkap) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Wijk</label>
                                                        <select name="wijk_id" class="form-control">
                                                            <option value="">- Pilih Wijk -</option>
                                                            @foreach($wijks as $wijk)
                                                            <option value="{{ $wijk->id }}" {{ (old('wijk_id', $penatua->wijk_id) == $wijk->id) ? 'selected' : '' }}>{{ $wijk->nama_wijk }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Jenis Kelamin</label>
                                                        <select name="jenis_kelamin" class="form-control">
                                                            <option value="Laki-laki" {{ (old('jenis_kelamin', $penatua->jenis_kelamin) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                                            <option value="Perempuan" {{ (old('jenis_kelamin', $penatua->jenis_kelamin) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>No. HP</label>
                                                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $penatua->no_hp) }}">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $penatua->tempat_lahir) }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $penatua->tanggal_lahir) }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea name="alamat" class="form-control">{{ old('alamat', $penatua->alamat) }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>Foto (opsional)</label>
                                                    <input type="file" name="foto" class="form-control-file">
                                                    @if($penatua->foto)
                                                    <small class="form-text text-muted">Foto saat ini: <a href="{{ asset('storage/'.$penatua->foto) }}" target="_blank">lihat</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
<div class="modal fade" id="penatuaPendingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content compact-modal">
            <div class="modal-header">
                <h5 class="modal-title">Data Akun Penatua"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="(function(){var m=document.getElementById('penatuaPendingModal'); if(m) m.style.display='none'; document.querySelectorAll('.modal-backdrop').forEach(function(b){b.remove()}); document.body.classList.remove('modal-open');})();">
                    <span aria-hidden="true"
                        onclick="(function(e){e.stopPropagation(); var m=document.getElementById('penatuaPendingModal'); if(m) m.style.display='none'; document.querySelectorAll('.modal-backdrop').forEach(function(b){b.remove()}); document.body.classList.remove('modal-open'); })(event)">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="penatuaPendingModalBody">
                Memuat...
            </div>

        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var countUrl = '{{ route('admin.penatua.pending.count') }}';
    var listUrl = '{{ route('admin.penatua.pending') }}';
    var btn = document.getElementById('penatuaPendingBtn');
    var modal = document.getElementById('penatuaPendingModal');
    var modalBody = document.getElementById('penatuaPendingModalBody');

    function createBackdrop(){
        if (!document.getElementById('penatuaPendingBackdrop')){
            var backdrop = document.createElement('div');
            backdrop.id = 'penatuaPendingBackdrop';
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
        document.body.classList.add('modal-open');
    }

    function removeBackdrop(){
        var byId = document.getElementById('penatuaPendingBackdrop');
        if (byId && byId.parentNode) byId.parentNode.removeChild(byId);
        document.querySelectorAll('.modal-backdrop').forEach(function(b){ if(b.parentNode) b.parentNode.removeChild(b); });
        document.body.classList.remove('modal-open');
    }

    function renderUsersTable(users){
        if(!users || users.length === 0) return '<div class="alert alert-info">Tidak ada akun penatua yang perlu diisi.</div>';
        var html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Aksi</th></tr></thead><tbody>';
        users.forEach(function(u, i){
            html += '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + (u.name || '') + '</td>' +
                '<td>' + (u.email || '') + '</td>' +
                '<td><a href="{{ route('admin.penatua.create') }}?user_id=' + u.id + '" class="btn btn-primary btn-sm">Isi Data</a></td>' +
                '</tr>';
        });
        html += '</tbody></table></div>';
        return html;
    }

    function showModal(){
        if(modalBody) modalBody.innerHTML = 'Memuat...';
        fetch(listUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(res){ return res.json(); })
            .then(function(data){
                var users = data.users || [];
                if(modalBody) modalBody.innerHTML = renderUsersTable(users);
                if (window.jQuery && typeof jQuery(modal).modal === 'function'){
                    jQuery(modal).modal('show');
                } else {
                    modal.style.display = 'block';
                    createBackdrop();
                }
            })
            .catch(function(err){ if(modalBody) modalBody.innerHTML = '<div class="alert alert-danger">Gagal memuat daftar pending.</div>'; console.error(err); });
    }

    function hideModal(){
        if (window.jQuery && typeof jQuery(modal).modal === 'function'){
            jQuery(modal).modal('hide');
        } else {
            modal.style.display = 'none';
            removeBackdrop();
        }
    }

    // expose for inline use if needed
    window.showPenatuaModal = showModal;
    window.hidePenatuaModal = hideModal;

    // wire button
    if(btn) btn.addEventListener('click', function(e){ e.preventDefault(); showModal(); });

    // wire close controls
    if(modal){
        modal.querySelectorAll('[data-dismiss="modal"], .close, .close span').forEach(function(el){
            el.addEventListener('click', function(e){ e.preventDefault(); hideModal(); });
        });
        modal.addEventListener('click', function(ev){ if(ev.target === modal) hideModal(); });
    }

    // initial counts
    fetch(countUrl).then(function(res){ return res.json(); }).then(function(data){
        if(data.total !== undefined) document.getElementById('totalPenatuaCount').textContent = data.total + ' Orang';
        if(data.pending !== undefined && data.pending > 0){
            document.getElementById('penatuaPendingCount').textContent = data.pending;
            if(btn) btn.style.display = 'inline-block';
        }
    }).catch(function(err){ console.error(err); });
});
</script>
@endpush



@endsection