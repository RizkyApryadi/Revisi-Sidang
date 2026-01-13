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
                            <th>Tanggal Pernikahan</th>
                            <th>Tempat</th>
                            <th>Dipimpin Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $index => $pendaftaran)
                        @php
                        $mempelais = $pendaftaran->mempelais;
                        $pria = $mempelais->where('jenis_kelamin', 'L')->first();
                        $wanita = $mempelais->where('jenis_kelamin', 'P')->first();
                        $namaPria = $pria ? $pria->nama : '-';
                        $namaWanita = $wanita ? $wanita->nama : '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $namaPria }}</td>
                            <td>{{ $namaWanita }}</td>
                            <td>{{ $pendaftaran->tanggal_pemberkatan ?
                                \Carbon\Carbon::parse($pendaftaran->tanggal_pemberkatan)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $pendaftaran->keterangan_pemberkatan ?? '-' }}</td>
                            <td>{{ $pendaftaran->nama_pendeta ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data pernikahan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</section>

@endsection