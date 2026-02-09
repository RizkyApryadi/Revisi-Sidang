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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    @if($errors->any())
        let _errors = {!! json_encode($errors->getMessages()) !!};
        let html = '<ul style="text-align:left; margin:0; padding-left:18px;">';
        for (let field in _errors) {
            html += '<li><strong>'+field+'</strong><ul style="margin:0; padding-left:14px;">';
            _errors[field].forEach(function(msg){ html += '<li>'+msg+'</li>'; });
            html += '</ul></li>';
        }
        html += '</ul>';

        Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan perubahan',
            html: html,
            confirmButtonText: 'Tutup'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: {!! json_encode(session('error')) !!},
            confirmButtonText: 'Tutup'
        });
    @endif

    @if(session('success'))
        (function(){
            let title = 'Berhasil disimpan';
            let message = {!! json_encode(session('success')) !!};
            let html = '<div style="text-align:left;">'+message+'</div>';
            @if(session('uploaded_image'))
                let img = {!! json_encode(asset('storage/' . session('uploaded_image'))) !!};
                html += '<div style="margin-top:12px;text-align:center;"><img src="'+img+'" alt="uploaded" style="max-width:260px; height:auto; border:1px solid #ddd; padding:6px;"/></div>';
            @endif

            Swal.fire({
                icon: 'success',
                title: title,
                html: html,
                confirmButtonText: 'OK'
            });
        })();
    @endif

    // Client-side file size validation to catch oversized uploads before submit
    (function(){
        const form = document.querySelector('form[action*="berita"]');
        if (!form) return;
        const MAX_FOTO = 5 * 1024 * 1024; // 5 MB (matches server validation)
        const MAX_FILE = 10 * 1024 * 1024; // 10 MB

        form.addEventListener('submit', function(e){
            const fotoInput = form.querySelector('input[name="foto"]');
            const fileInput = form.querySelector('input[name="file"]');
            let problems = [];

            if (fotoInput && fotoInput.files && fotoInput.files[0]) {
                if (fotoInput.files[0].size > MAX_FOTO) {
                    problems.push('Foto terlalu besar (maks 5 MB).');
                }
            }
            if (fileInput && fileInput.files && fileInput.files[0]) {
                if (fileInput.files[0].size > MAX_FILE) {
                    problems.push('File terlalu besar (maks 10 MB).');
                }
            }

            if (problems.length) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran file tidak sesuai',
                    html: '<ul style="text-align:left; margin:0; padding-left:18px;">' + problems.map(p => '<li>'+p+'</li>').join('') + '</ul>',
                    confirmButtonText: 'Tutup'
                });
            }
        });
    })();

});
@endpush
