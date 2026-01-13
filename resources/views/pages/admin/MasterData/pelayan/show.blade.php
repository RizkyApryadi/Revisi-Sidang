@extends('layouts.main')
@section('title', 'Detail Pelayanan Ibadah')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Detail Pelayanan Ibadah</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Informasi Ibadah</h4>
        </div>

        <div class="card-body">
            <p><strong>Nama Minggu:</strong> {{ optional($ibadah->warta)->nama_minggu ?? '-' }}</p>
            <p><strong>Jam Ibadah:</strong> {{ $ibadah->waktu ? \Carbon\Carbon::parse($ibadah->waktu)->format('H:i') : '-' }}</p>

            <h5 class="mt-4">Daftar Pelayanan</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Jenis Pelayanan</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelayan as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->jenis_pelayanan }}</td>
                                <td>{{ $p->petugas ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data pelayanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.pelayan') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

</section>
@endsection
