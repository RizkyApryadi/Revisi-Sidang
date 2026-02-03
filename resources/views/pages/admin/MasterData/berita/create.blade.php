@extends('layouts.main')
@section('title', 'Tambah Berita')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Tambah Berita</h4>
            <a href="{{ route('admin.berita') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                    <label for="foto">Foto Utama (opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="judul">Judul Berita</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="file">File (opsional)</label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="ringkasan">Ringkasan</label>
                    <textarea name="ringkasan" id="ringkasan" class="form-control tinymce"
                        rows="4">{!! old('ringkasan') !!}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</section>
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: {!! json_encode(session('success')) !!}
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: {!! json_encode(session('error')) !!}
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: {!! json_encode(session('info')) !!}
        });
    @endif

    @if($errors->any())
        var errs = {!! json_encode($errors->all()) !!};
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: errs.map(function(e){ return '<div>' + e + '</div>'; }).join('')
        });
    @endif
});
</script>
@endpush

@endsection