@extends('layouts.main')
@section('title', 'Galeri')

@section('content')
<section class="section">

    <div class="section-header">
        <h1>Galeri</h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Daftar Galeri Kegiatan</h4>
            <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Galeri
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped galeri-table">
                    <thead class="text-center">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galeris as $galeri)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>

                            <td class="align-middle text-left">
                                {{ $galeri->judul }}
                            </td>

                            <td class="align-middle text-left">
                                {{ \Illuminate\Support\Str::limit($galeri->deskripsi, 120) }}
                            </td>

                            <td class="text-center align-middle">
                                {{ $galeri->tanggal ? $galeri->tanggal->format('d-m-Y') : '-' }}
                            </td>

                            <td class="text-center align-middle">
                                <a href="#">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="btn btn-warning btn-sm mx-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.galeri.destroy', $galeri->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus galeri ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada galeri.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</section>
@endsection

<style>
    /* Ensure headers and cells align center and vertically middle */
    .galeri-table th, .galeri-table td {
        text-align: center !important;
        vertical-align: middle !important;
        white-space: normal;
    }

    .galeri-table td {
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
    }

    .galeri-table img {
        display: inline-block;
        max-width: 120px;
        max-height: 80px;
        object-fit: cover;
    }
</style>