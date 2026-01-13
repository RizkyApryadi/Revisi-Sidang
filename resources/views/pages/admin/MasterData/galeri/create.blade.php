@extends('layouts.main')
@section('title', 'Tambah Galeri')

@section('content')

<section class="section">

    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Galeri</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
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

                {{-- Judul --}}
                <div class="form-group">
                    <label>Judul Kegiatan</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                </div>

                {{-- Tanggal --}}
                <div class="form-group">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                {{-- Upload Foto --}}
                <div class="form-group">
                    <label>Foto Kegiatan</label>

                    {{-- Preview --}}
                    <div id="preview-wrapper" class="mb-2 d-flex flex-wrap"></div>

                    {{-- Upload Box --}}
                    <div class="upload-box" id="uploadBox">
                        <input type="file" name="foto[]" id="fotoInput" class="upload-input" accept="image/*" multiple
                            required>

                        <div class="upload-text">
                            <i class="fas fa-images fa-2x mb-2"></i>
                            <p class="mb-1"><strong>Drag & Drop foto di sini</strong></p>
                            <span>atau klik untuk memilih foto</span>
                        </div>
                    </div>

                    <small class="text-muted">
                        Format: JPG, PNG | Maksimal 2MB
                    </small>
                </div>

                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</section>

<style>
    .upload-box {
        position: relative;
        width: 100%;
        height: 220px;
        border: 2px dashed #dcdcdc;
        border-radius: 12px;
        background-color: #fafafa;
    }

    .upload-box.dragover {
        border-color: #6777ef;
        background-color: #eef1ff;
    }

    .upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    .preview-item {
        margin: 5px;
    }

    .preview-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>

<script>
    const uploadBox = document.getElementById('uploadBox');
const fotoInput = document.getElementById('fotoInput');
const previewWrapper = document.getElementById('preview-wrapper');

let selectedFiles = [];

// Drag over
uploadBox.addEventListener('dragover', e => {
    e.preventDefault();
    uploadBox.classList.add('dragover');
});

// Drag leave
uploadBox.addEventListener('dragleave', () => {
    uploadBox.classList.remove('dragover');
});

// Drop
uploadBox.addEventListener('drop', e => {
    e.preventDefault();
    uploadBox.classList.remove('dragover');
    addFiles(e.dataTransfer.files);
});

// Choose file
fotoInput.addEventListener('change', e => {
    addFiles(e.target.files);
});

// Tambah file (append)
function addFiles(files) {
    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return;

        // Cegah duplikat (nama + size)
        if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            return;
        }

        selectedFiles.push(file);
    });

    updateInputFiles();
    renderPreview();
}

// Sinkronkan ke input file
function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fotoInput.files = dataTransfer.files;
}

// Tampilkan preview
function renderPreview() {
    previewWrapper.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.classList.add('preview-item');
            div.innerHTML = `<img src="${e.target.result}">`;
            previewWrapper.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>

@endsection