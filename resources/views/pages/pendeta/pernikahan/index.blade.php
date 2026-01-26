@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    <!-- Card Pernikahan -->
    @php
        try{
            $totalPairs = \Illuminate\Support\Facades\DB::table('pendaftaran_pernikahans')->count();
        }catch(\Exception $e){
            $totalPairs = isset($pendaftarans) ? collect($pendaftarans)->count() : 0;
        }
    @endphp
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-ring"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Pernikahan</h4>
                </div>
                <div class="card-body">
                    {{ $totalPairs }} Pasangan
                </div>
            </div>
        </div>
    </a>

    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Pernikahan</h4>
                <div class="d-flex">
                    <a href="{{ route('pendeta.pelayanan.pernikahan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>

                    <button type="button" class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#statusPengajuanModal">
                        <i class="fas fa-list"></i> Status Pengajuan
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Suami</th>
                            <th>Nama Istri</th>
                            <th>Tanggal Perjanjian</th>
                            <th>Tanggal Pemberkatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $published = collect($pendaftarans)->filter(function($p){ return (($p->status ?? '') === 'published'); });
                        @endphp

                        @forelse($published as $index => $pendaftaran)
                        @php
                        $pria = $pendaftaran->pria ?? null;
                        $wanita = $pendaftaran->wanita ?? null;
                        $namaPria = $pria ? ($pria->nama_lengkap ?? $pria->nama ?? '-') : '-';
                        $namaWanita = $wanita ? ($wanita->nama_lengkap ?? $wanita->nama ?? '-') : '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $namaPria }}</td>
                            <td>{{ $namaWanita }}</td>
                            <td>{{ $pendaftaran->tanggal_perjanjian ? \Carbon\Carbon::parse($pendaftaran->tanggal_perjanjian)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $pendaftaran->tanggal_pemberkatan ? \Carbon\Carbon::parse($pendaftaran->tanggal_pemberkatan)->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <span class="text-success">Dipublikasikan</span>
                                </td>

                                <td>
                                    <a href="{{ route('pendeta.pelayanan.pernikahan.show', $pendaftaran->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <form action="{{ route('pendeta.pelayanan.pernikahan.destroy', $pendaftaran->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pendaftaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data pernikahan yang dipublikasikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</section>

<!-- Status Pengajuan Modal -->
<div class="modal fade" id="statusPengajuanModal" tabindex="-1" aria-labelledby="statusPengajuanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusPengajuanModalLabel">Status Pengajuan - Butuh Persetujuan Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $pendingList = collect($pendaftarans)->filter(function($p){ return ($p->status ?? 'pending') === 'pending'; });
                @endphp

                @if($pendingList->isEmpty())
                    <div class="text-center text-muted">Tidak ada pengajuan yang menunggu persetujuan.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Suami</th>
                                    <th>Nama Istri</th>
                                    <th>Tanggal Perjanjian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingList as $i => $p)
                                    @php
                                        $pria = $p->pria ?? null;
                                        $wanita = $p->wanita ?? null;
                                        $namaPria = $pria ? ($pria->nama_lengkap ?? $pria->nama ?? '-') : '-';
                                        $namaWanita = $wanita ? ($wanita->nama_lengkap ?? $wanita->nama ?? '-') : '-';
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $namaPria }}</td>
                                        <td>{{ $namaWanita }}</td>
                                        <td>{{ $p->tanggal_perjanjian ? \Carbon\Carbon::parse($p->tanggal_perjanjian)->format('d-m-Y') : '-' }}</td>
                                        <td>
                                            <form action="{{ route('pendeta.pelayanan.pernikahan.approve', $p->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <input type="hidden" name="notify_admin" value="1" />
                                                <button class="btn btn-success btn-sm" type="submit">Setujui (kirim notifikasi ke admin)</button>
                                            </form>

                                            <form id="modal-reject-form-{{ $p->id }}" action="{{ route('pendeta.pelayanan.pernikahan.reject', $p->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <input type="hidden" name="reason" value="" />
                                                <button type="button" class="btn btn-danger btn-sm" onclick="promptReject({{ $p->id }}, 'modal-reject-form-')">Tolak</button>
                                            </form>

                                            <a href="{{ route('pendeta.pelayanan.pernikahan.show', $p->id) }}" class="btn btn-info btn-sm ms-1">Lihat</a>
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

@endsection

@section('scripts')
<script>
    function promptReject(id, prefix) {
        var reason = prompt('Masukkan alasan penolakan:');
        if (reason === null) return; // cancelled
        var formId = (prefix ? prefix : 'reject-form-') + id;
        var form = document.getElementById(formId);
        if (!form) {
            alert('Form penolakan tidak ditemukan.');
            return;
        }
        var input = form.querySelector('input[name="reason"]');
        if (!input) {
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'reason';
            form.appendChild(hidden);
            input = hidden;
        }
        input.value = reason;
        form.submit();
    }
</script>
@endsection