@extends('layouts.main')
@section('title', 'Detail Katekisasi')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Detail Katekisasi</h4>
            <a href="{{ route('penatua.pelayanan.katekisasi') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Periode Ajaran</dt>
                <dd class="col-sm-9">{{ $k->periode_ajaran }}</dd>

                <dt class="col-sm-3">Pendeta Pembina</dt>
                <dd class="col-sm-9">{{ optional($k->pendeta)->nama_lengkap ?? '-' }}</dd>

                <dt class="col-sm-3">Tanggal Mulai</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d M Y') }}</dd>

                <dt class="col-sm-3">Tanggal Selesai</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') }}</dd>

                <dt class="col-sm-3">Pendaftaran Ditutup</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($k->tanggal_pendaftaran_tutup)->format('d M Y') }}</dd>

                <dt class="col-sm-3">Deskripsi</dt>
                <dd class="col-sm-9">{{ $k->deskripsi }}</dd>

                <dt class="col-sm-3">Jumlah Peserta</dt>
                <dd class="col-sm-9">{{ optional($k->pendaftaranSidis)->count() ?? 0 }}</dd>
            </dl>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('penatua.pelayanan.katekisasi.create') }}?katekisasi_id={{ $k->id }}"
                    class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Peserta
                </a>
            </div>

            {{-- Daftar Pendaftar Katekisasi (hanya wijk penatua) --}}
            <hr>
            <h5 class="text-primary mt-4 mb-3">
                <i class="fas fa-list"></i> Daftar Pendaftar (Wijk: {{ $wijkName ?? '—' }})
            </h5>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>No HP</th>
                            <th>Jenis Pendaftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaranSidis ?? [] as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->jenis_kelamin }}</td>
                            <td>{{ $p->tanggal_lahir }}</td>
                            <td>{{ $p->no_hp }}</td>
                            <td>{{ ucfirst($p->jenis_pendaftar ?? '') }}</td>
                            <td>
                                @if(($p->status_pengajuan ?? 'pending') === 'pending')
                                    <form action="{{ url('/penatua/pelayanan/katekisasi/pendaftaran/'.$p->id.'/approve') }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Terima</button>
                                    </form>

                                    <button class="btn btn-danger btn-sm" onclick="openRejectModal({{ $p->id }})">Tolak</button>
                                @else
                                    @if($p->status_pengajuan === 'disetujui')
                                        <span class="text-success">Disetujui</span>
                                    @else
                                        <span class="text-danger">Ditolak</span>
                                    @endif
                                    @if($p->catatan_admin)
                                        <div class="small text-muted">Alasan: {{ $p->catatan_admin }}</div>
                                    @endif
                                @endif
                            </td>

                            <td>
                                <a href="{{ url('/penatua/pelayanan/katekisasi/pendaftaran/'.$p->id) }}" class="btn btn-info btn-sm">Show</a>
                                <form action="{{ url('/penatua/pelayanan/katekisasi/pendaftaran/'.$p->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pendaftaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pendaftar untuk wijk ini.</td>
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
    function openRejectModal(id) {
        var reason = prompt('Masukkan alasan penolakan:');
        if (reason === null) return;
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/penatua/pelayanan/katekisasi/pendaftaran/' + id + '/reject';
        var token = document.createElement('input'); token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
        var input = document.createElement('input'); input.type = 'hidden'; input.name = 'catatan'; input.value = reason;
        form.appendChild(token);
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection