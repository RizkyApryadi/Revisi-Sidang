@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<section class="section">

    <!-- Card Pernikahan -->
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
                    {{ isset($totalCount) ? $totalCount : (isset($pendaftarans) ? $pendaftarans->count() : 0) }}
                    Pasangan
                </div>
            </div>
        </div>
    </a>

    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Data Pernikahan</h4>
                <a href="{{ route('penatua.pelayanan.pernikahan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
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
                        @forelse($pendaftarans as $index => $pendaftaran)
                        @php
                        // Use explicit relations `pria` and `wanita` defined on the model.
                        $pria = $pendaftaran->pria ?? null;
                        $wanita = $pendaftaran->wanita ?? null;
                        // Jemaat uses `nama_lengkap`; fall back to `nama` if present for compatibility
                        $namaPria = $pria ? ($pria->nama_lengkap ?? $pria->nama ?? '-') : '-';
                        $namaWanita = $wanita ? ($wanita->nama_lengkap ?? $wanita->nama ?? '-') : '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $namaPria }}</td>
                            <td>{{ $namaWanita }}</td>
                            <td>{{ $pendaftaran->tanggal_perjanjian ?
                                \Carbon\Carbon::parse($pendaftaran->tanggal_perjanjian)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $pendaftaran->tanggal_pemberkatan ?
                                \Carbon\Carbon::parse($pendaftaran->tanggal_pemberkatan)->format('d-m-Y') : '-' }}</td>
                            <td>
                                @php $status = $pendaftaran->status ?? 'pending'; @endphp

                                @if($status === 'pending')
                                    <form action="{{ route('penatua.pelayanan.pernikahan.approve', $pendaftaran->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm" type="submit">Terima</button>
                                    </form>

                                    <form id="reject-form-{{ $pendaftaran->id }}" action="{{ route('penatua.pelayanan.pernikahan.reject', $pendaftaran->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="reason" value="" />
                                        <button type="button" class="btn btn-danger btn-sm" onclick="promptReject({{ $pendaftaran->id }})">Tolak</button>
                                    </form>

                                @elseif($status === 'awaiting_publish')
                                    <span class="badge bg-warning text-dark">Menunggu Publikasi (Disetujui oleh Pendeta)</span>

                                @elseif($status === 'published')
                                    <span class="text-success">Dipublikasikan</span>

                                @elseif($status === 'approved')
                                    <span class="text-success">Disetujui</span>

                                @elseif($status === 'rejected')
                                    <span class="text-danger">Ditolak</span>

                                @else
                                    <span class="text-muted">{{ ucfirst($status) }}</span>
                                @endif

                                @if(!empty($pendaftaran->review_reason))
                                    <div class="small text-muted">Alasan: {{ $pendaftaran->review_reason }}</div>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('penatua.pelayanan.pernikahan.show', $pendaftaran->id) }}"
                                    class="btn btn-info btn-sm">Show</a>
                                <form action="{{ route('penatua.pelayanan.pernikahan.destroy', $pendaftaran->id) }}"
                                    method="POST" style="display:inline"
                                    onsubmit="return confirm('Hapus pendaftaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data pernikahan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</section>

@endsection

@section('scripts')
<script>
    function promptReject(id) {
        var reason = prompt('Masukkan alasan penolakan:');
        if (reason === null) return; // cancelled
        var form = document.getElementById('reject-form-' + id);
        form.querySelector('input[name="reason"]').value = reason;
        form.submit();
    }
</script>
@endsection