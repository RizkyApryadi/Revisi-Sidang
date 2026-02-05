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
    if (typeof tinymce === 'undefined') {
        console.warn('TinyMCE not loaded from layout. Make sure tinymce is available.');
    } else {
        // remove existing instances to avoid duplicate-init conflicts
        try { tinymce.remove(); } catch (e) { /* ignore */ }
        tinymce.init({
        selector: 'textarea.tinymce',
        plugins: 'image link media code lists advlist',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | image link media | code',
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.setAttribute('multiple', 'multiple');
                input.onchange = function () {
                    var files = input.files;
                    var formData = new FormData();
                    for (var i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch("{{ route('admin.berita.uploadImage') }}", {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data && data.location) {
                            callback(data.location);
                        } else if (data && data.locations && data.locations.length) {
                            // Insert all images inside a single paragraph so they appear side-by-side
                            var imgs = '';
                            for (var j = 0; j < data.locations.length; j++) {
                                imgs += '<img src="' + data.locations[j] + '" style="display:inline-block; width:48%; margin-right:8px; vertical-align:middle;" />';
                            }
                            var html = '<p>' + imgs + '</p>';
                            tinymce.activeEditor.execCommand('mceInsertContent', false, html);
                        } else {
                            alert('Gagal mengunggah gambar');
                        }
                    }).catch(function (err) { alert('HTTP Error: ' + err.message); });
                };
                input.click();
            }
        },
        images_upload_handler: function (blobInfo, success, failure) {
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');

            fetch("{{ route('admin.berita.uploadImage') }}", {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.location) {
                    success(data.location);
                } else if (data && data.locations && data.locations.length) {
                    // Insert all images into a single paragraph so they appear side-by-side
                    var imgs = '';
                    for (var k = 0; k < data.locations.length; k++) {
                        imgs += '<img src="' + data.locations[k] + '" style="display:inline-block; width:48%; margin-right:8px; vertical-align:middle;" />';
                    }
                    var html = '<p>' + imgs + '</p>';
                    tinymce.activeEditor.execCommand('mceInsertContent', false, html);
                } else {
                    failure('Invalid JSON response');
                }
            }).catch(function (err) { failure('HTTP Error: ' + err.message); });
        },
        height: 300
    });

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