@extends('layouts.main')
@section('title', 'Pelayanan Ibadah')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Pelayanan Ibadah</h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Daftar Pelayanan</h4>
            <a href="{{ route('admin.pelayan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pelayanan
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Nama Ibadah</th>
                            <th>Jam Ibadah</th>
                            <th style="width:12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $items = $ibadahs ?? collect(); @endphp
                        @forelse($items as $ibadah)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($ibadah->warta)->id ? optional($ibadah->warta)->id . ' - ' . optional($ibadah->warta)->nama_minggu : '-' }}</td>
                                <td>{{ $ibadah->waktu ? \Carbon\Carbon::parse($ibadah->waktu)->format('H:i') : '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pelayan.show', $ibadah->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data ibadah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</section>
@endsection


