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
                        @forelse($beritas ?? [] as $idx => $b)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $b->tanggal ? \Carbon\Carbon::parse($b->tanggal)->translatedFormat('j F Y') : '-' }}</td>
                                <td>{{ $b->judul }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($b->ringkasan, 120) }}</td>
                                <td>
                                    @if(!empty($b->file))
                                        <a href="{{ asset('storage/' . $b->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Unduh</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">-</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection