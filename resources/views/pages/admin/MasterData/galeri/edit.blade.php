@extends('layouts.main')
@section('title', 'Edit Galeri')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Galeri</h4>
            <a href="{{ route('admin.galeri') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
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
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', optional($galeri->tanggal)->format('Y-m-d')) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $galeri->judul) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="foto">Tambah Foto (bisa pilih beberapa)</label>
                    <input type="file" name="foto[]" id="foto" class="form-control" multiple accept="image/*">
                    <small class="text-muted">Upload untuk menambahkan foto baru (opsional).</small>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>

            <hr>
            <h5>Foto Saat Ini</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach(\App\Models\GaleriFoto::where('galeri_id', $galeri->id)->get() as $foto)
                <div style="position:relative;">
                    <img src="{{ asset(str_replace('public/', 'storage/', $foto->foto)) }}" style="width:120px;height:80px;object-fit:cover;margin:4px;border:1px solid #ddd;"/>
                    <form action="{{ route('admin.galeri.foto.destroy', $foto->id) }}" method="POST" style="position:absolute;top:2px;right:2px;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini?');"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</section>
@endsection
