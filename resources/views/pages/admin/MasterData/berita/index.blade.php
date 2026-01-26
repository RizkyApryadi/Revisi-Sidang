@extends('layouts.main')
@section('title', 'Data Berita')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Daftar Berita</h4>
            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Berita
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:5%">No</th>
                            <th>Tanggal</th>
                            <th>Judul Berita</th>
                            <th>Ringkasan</th>
                            <th>File</th>
                            <th style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beritas as $index => $berita)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $berita->tanggal ? $berita->tanggal->format('d-m-Y') : '-' }}</td>
                            <td>{{ $berita->judul }}</td>
                            <td>{{ Str::limit($berita->ringkasan, 100) }}</td>
                            <td>
                                @if($berita->file)
                                <a href="{{ asset('storage/' . $berita->file) }}" target="_blank">Lihat</a>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="{{ route('admin.berita.show', $berita->id) }}"
                                        class="btn btn-info btn-sm mx-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.berita.edit', $berita->id) }}"
                                        class="btn btn-warning btn-sm mx-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mx-1" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data berita.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection