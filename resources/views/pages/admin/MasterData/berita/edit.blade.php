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
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $berita->tanggal ? \Carbon\Carbon::parse($berita->tanggal)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="judul">Judul Berita</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $berita->judul) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="foto">Foto Utama (opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                    @if(!empty($berita->foto))
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $berita->foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" style="max-width:200px; height:auto; border:1px solid #ddd; padding:4px;" />
                        </a>
                        <div><small class="text-muted">Foto saat ini</small></div>
                    </div>
                    @endif
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
                    <textarea name="ringkasan" id="ringkasan" class="form-control tinymce" rows="6">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</section>
@endsection
