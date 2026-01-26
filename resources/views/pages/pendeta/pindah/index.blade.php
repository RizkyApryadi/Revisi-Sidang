@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    @php
        $publishedList = collect($published ?? collect());
        $totalPublished = $publishedList->count();
        $names = $publishedList->map(function($p){
            $j = $p->jemaat ?? null;
            if(!$j) return '-';
            return $j->nama_lengkap ?? $j->nama ?? '-';
        })->unique()->values();
        $preview = $names->slice(0,5)->all();
        $more = max(0, $names->count() - count($preview));
    @endphp

    <!-- Card Pindah Jemaat -->
    <a href="#" style="text-decoration: none; color: inherit;">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Data Pindah Jemaat</h4>
                </div>
                <div class="card-body">
                    {{ $totalPublished }} Jemaat
                    @if($totalPublished > 0)
                        <div class="small text-muted mt-1">
                            {{ implode(', ', $preview) }}@if($more) and {{ $more }} more @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </a>

    {{-- Tabel Pindah Jemaat --}}
    <div class="card mt-4">
        <div class="card-header">
                <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Pindah</h4>
                <div class="d-flex">
                    <a href="" class="btn btn-primary">
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
                            <th>Nama Jemaat</th>
                            <th>Tanggal Publish</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($published ?? collect() as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->jemaat ? $p->jemaat->nama_lengkap : '-' }}</td>
                            <td>{{ $p->updated_at ? $p->updated_at->format('d-m-Y') : ($p->created_at ? $p->created_at->format('d-m-Y') : '-') }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('pendeta.pelayanan.pindah.show', $p->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada permohonan pindah yang dipublikasikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

</section>

@endsection

@section('scripts')
<script>
    function promptReject(id, prefix) {
        var reason = prompt('Masukkan alasan penolakan:');
        if (reason === null) return; // cancelled
        var formId = (prefix ? prefix : 'modal-reject-form-') + id;
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

<!-- Status Pengajuan Modal -->
<div class="modal fade" id="statusPengajuanModal" tabindex="-1" aria-labelledby="statusPengajuanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusPengajuanModalLabel">Status Pengajuan - Disetujui Penatua</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $pendingList = isset($pendingList) ? $pendingList : collect();
                @endphp

                @if($pendingList->isEmpty())
                    <div class="text-center text-muted">Tidak ada pengajuan yang menunggu persetujuan.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jemaat</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingList as $i => $p)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $p->jemaat ? $p->jemaat->nama_lengkap : '-' }}</td>
                                        <td>{{ $p->created_at ? $p->created_at->format('d-m-Y') : '-' }}</td>
                                        <td>
                                            <form action="{{ route('pendeta.pelayanan.pindah.approve', $p->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm" type="submit">Setujui (kirim ke admin)</button>
                                            </form>

                                            <form id="modal-reject-form-{{ $p->id }}" action="{{ route('pendeta.pelayanan.pindah.reject', $p->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                <input type="hidden" name="reason" value="" />
                                                <button type="button" class="btn btn-danger btn-sm" onclick="promptReject({{ $p->id }}, 'modal-reject-form-')">Tolak</button>
                                            </form>

                                            <a href="{{ route('pendeta.pelayanan.pindah.show', $p->id) }}" class="btn btn-info btn-sm ms-1">Lihat</a>
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