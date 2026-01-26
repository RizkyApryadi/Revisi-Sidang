@extends('layouts.main')
@section('title', 'Edit Berita')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Berita</h4>
            <a href="{{ route('admin.berita') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group mb-3">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', optional($berita->tanggal)->format('Y-m-d')) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="judul">Judul Berita</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $berita->judul) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="file">File (opsional)</label>
                    <input type="file" name="file" id="file" class="form-control">
                    @if($berita->file)
                    <small class="text-muted">File sekarang: <a href="{{ asset('storage/' . $berita->file) }}" target="_blank">Lihat</a></small>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="ringkasan">Ringkasan</label>
                    <textarea name="ringkasan" id="ringkasan" class="form-control" rows="4">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</section>
@endsection
