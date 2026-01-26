@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    {{-- Kartu Header --}}
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-water"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Data Baptisan</h4>
                    </div>
                    <div class="card-body">
                        3 Data
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Baptisan --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Baptisan</h4>
                <div>
                    <button type="button" class="btn btn-secondary mr-2" id="btn-pengajuan" onclick="showPengajuanModal()">
                        <i class="fas fa-inbox"></i> Pengajuan ({{ $pending ?? 0 }})
                    </button>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Baptisan
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
                            <th>Nama Jemaat</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Baptisan</th>
                            <th>Tempat Baptisan</th>
                            <th>Nama Pendeta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($pendaftarans) && $pendaftarans->count())
                        @foreach($pendaftarans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($p->jemaat)->nama ?? '-' }}</td>
                            <td>{{ '-' }}</td>
                            <td>{{ $p->tanggal_kebaktian ? \Carbon\Carbon::parse($p->tanggal_kebaktian)->format('d-m-Y')
                                : '-' }}</td>
                            <td>{{ '-' }}</td>
                            <td>{{ optional($p->pendeta)->nama ?? '-' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data baptisan.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   
</section>

@endsection

<!-- Modal: Pengajuan Pendaftaran Baptisan -->
<div class="modal fade" id="pengajuanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengajuan Pendaftaran Baptisan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <h6>Daftar Pengajuan</h6>
                        <div class="list-group" style="max-height:420px;overflow:auto;">
                            @php $pendingList = isset($pendaftarans) ? $pendaftarans->where('status','pending') : collect(); @endphp
                            @if($pendingList && $pendingList->count())
                                @foreach($pendingList as $it)
                                    <a href="#" class="list-group-item list-group-item-action" onclick="openPengajuan({{ $it->id }}); return false;">
                                        {{ optional($it->jemaat)->nama ?? '—' }}
                                        <div class="small text-muted">{{ $it->created_at ? $it->created_at->format('d-m-Y') : '' }}</div>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-muted">Tidak ada pengajuan.</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6>Detail Pengajuan</h6>
                        <div id="pengajuan-detail">
                            <p><strong>Nama:</strong> <span id="detail-nama">—</span></p>
                            <p><strong>Tanggal Kebaktian:</strong> <span id="detail-tanggal">—</span></p>
                            <p><strong>Status:</strong> <span id="detail-status">—</span></p>
                            <div id="detail-files"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="approve-form" method="POST" action="#" class="mr-2">
                    @csrf
                    <button type="submit" class="btn btn-success">Setujui</button>
                </form>
                <form id="reject-form" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="reason" id="reject-reason" value="">
                    <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#reject-reason-area">Tolak</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
            <div class="collapse p-3" id="reject-reason-area">
                <div class="form-group mb-2">
                    <label>Alasan Penolakan (opsional)</label>
                    <textarea id="reject-text" class="form-control" rows="3"></textarea>
                </div>
                <div class="text-right">
                    <button class="btn btn-danger" id="submit-reject">Kirim Penolakan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
function showPengajuanModal(){
    if(window.bootstrap && typeof bootstrap.Modal === 'function'){
        const el = document.getElementById('pengajuanModal');
        const modal = bootstrap.Modal.getOrCreateInstance ? bootstrap.Modal.getOrCreateInstance(el) : new bootstrap.Modal(el);
        modal.show();
        return;
    }
    if(window.jQuery){
        $('#pengajuanModal').modal('show');
        return;
    }
    const m = document.getElementById('pengajuanModal');
    if(m){ m.style.display = 'block'; m.classList.add('show'); }
}

function hidePengajuanModal(){
    if(window.bootstrap && typeof bootstrap.Modal === 'function'){
        const inst = bootstrap.Modal.getInstance(document.getElementById('pengajuanModal'));
        if(inst) inst.hide();
        return;
    }
    if(window.jQuery){ $('#pengajuanModal').modal('hide'); return; }
    const m = document.getElementById('pengajuanModal'); if(m){ m.classList.remove('show'); m.style.display = 'none'; }
}

function openPengajuan(id){
    const detail = document.getElementById('pengajuan-detail');
    detail.innerHTML = '<p class="text-muted">Memuat...</p>';
    fetch('/pendeta/pelayanan/baptisan/' + id, { headers: { 'Accept': 'application/json' } })
        .then(response => {
            if(!response.ok){
                return response.text().then(t => { throw new Error('HTTP '+response.status+': '+t); });
            }
            return response.json();
        })
        .then(data => {
            // ensure modal is visible first so elements exist
            try{ showPengajuanModal(); }catch(e){ /* ignore */ }

            const modalEl = document.getElementById('pengajuanModal');
            const nameEl = modalEl ? modalEl.querySelector('#detail-nama') : document.getElementById('detail-nama');
            const tanggalEl = modalEl ? modalEl.querySelector('#detail-tanggal') : document.getElementById('detail-tanggal');
            const statusEl = modalEl ? modalEl.querySelector('#detail-status') : document.getElementById('detail-status');
            const filesDiv = modalEl ? modalEl.querySelector('#detail-files') : document.getElementById('detail-files');

            if(nameEl) nameEl.textContent = data.jemaat ? data.jemaat.nama : '—';
            if(tanggalEl) tanggalEl.textContent = data.tanggal_kebaktian ? new Date(data.tanggal_kebaktian).toLocaleDateString() : '-';
            if(statusEl) statusEl.textContent = data.status || '-';
            if(filesDiv){
                filesDiv.innerHTML = '';
                if(data.akte_pernikahan){ filesDiv.innerHTML += '<p><a href="/storage/'+data.akte_pernikahan+'" target="_blank">Akte Pernikahan</a></p>'; }
                if(data.surat_pengantar_sintua){ filesDiv.innerHTML += '<p><a href="/storage/'+data.surat_pengantar_sintua+'" target="_blank">Surat Pengantar Sintua</a></p>'; }
                if(data.surat_pengantar_jemaat_lain){ filesDiv.innerHTML += '<p><a href="/storage/'+data.surat_pengantar_jemaat_lain+'" target="_blank">Surat Pengantar Jemaat Lain</a></p>'; }
            }

            const approveForm = document.getElementById('approve-form');
            const rejectForm = document.getElementById('reject-form');
            if(approveForm) approveForm.action = '/pendeta/pelayanan/baptisan/' + id + '/approve';
            if(rejectForm) rejectForm.action = '/pendeta/pelayanan/baptisan/' + id + '/reject';
        })
        .catch(err => {
            console.error('openPengajuan error:', err);
            let msg = 'Gagal memuat data.';
            if(err && err.message) msg += ' (' + err.message + ')';
            detail.innerHTML = '<div class="text-danger">'+msg+'</div>';
        });
}

document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'submit-reject'){
        e.preventDefault();
        const reason = document.getElementById('reject-text').value || '';
        document.getElementById('reject-reason').value = reason;
        document.getElementById('reject-form').submit();
    }
});
</script>
@endpush
