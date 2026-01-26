@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manajemen Pernikahan (Admin)</h4>
</div>

<div class="card mb-4">
    <div class="card-header">Pengajuan Siap Publikasi</div>
    <div class="card-body">
        @if(isset($toPublish) && $toPublish->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        @foreach($toPublish as $i => $p)
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
                                    <a href="{{ route('admin.pelayanan.pernikahan.show', $p->id) }}" class="btn btn-info btn-sm">Lihat</a>

                                    <form action="{{ route('admin.pelayanan.pernikahan.publish', $p->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm ms-1" type="submit">Publish</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted">Tidak ada pengajuan untuk dipublikasikan.</div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">Data Pernikahan yang Dipublikasikan</div>
    <div class="card-body">
        @if(isset($published) && $published->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        @foreach($published as $i => $p)
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
                                    <a href="{{ route('admin.pelayanan.pernikahan.show', $p->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted">Belum ada data pernikahan yang dipublikasikan.</div>
        @endif
    </div>
</div>

@endsection