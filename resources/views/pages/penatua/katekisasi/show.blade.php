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
                            <td>{{ ucfirst($p->status_pengajuan ?? 'pending') }}</td>
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