@extends('layouts.main')
@section('title', 'Edit Galeri')

@section('content')
<section class="section">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Galeri</h4>
            <a href="{{ route('admin.galeri') }}" class="btn btn-secondary btn-sm">
                ← Kembali
            </a>
        </div>

        <div class="card-body">

            {{-- ERROR --}}
            @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- FORM --}}
            <form id="galeriForm" action="{{ route('admin.galeri.update', $galeri->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Tanggal --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               class="form-control"
                               value="{{ old('tanggal', $galeri->tanggal ? \Carbon\Carbon::parse($galeri->tanggal)->format('Y-m-d') : '') }}">
                    </div>

                    {{-- Judul --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Judul</label>
                        <input type="text"
                               name="judul"
                               class="form-control"
                               placeholder="Masukkan judul galeri"
                               value="{{ old('judul', $galeri->judul) }}">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Tuliskan deskripsi kegiatan">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                    </div>

                    {{-- Upload Foto --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Tambah Foto</label>
                        <input type="file"
                               name="foto[]"
                               class="form-control"
                               multiple
                               accept="image/*">
                        <small class="text-muted">
                            Bisa pilih beberapa foto sekaligus (opsional).
                        </small>
                    </div>

                </div>

                {{-- BUTTON --}}

            </form>

            {{-- ================= FOTO SAAT INI ================= --}}
            <hr class="my-4">

            <h5 class="mb-3">Foto Saat Ini</h5>

            @php
                $fotos = \App\Models\GaleriFoto::where('galeri_id', $galeri->id)->get();
            @endphp

            @if($fotos->isEmpty())
                <div class="alert alert-light border text-muted">
                    Belum ada foto untuk galeri ini.
                </div>
            @else
                <div class="row">
                    @foreach($fotos as $foto)
                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                        <div class="card shadow-sm border-0">

                            <img src="{{ Storage::url($foto->foto) }}"
                                 class="card-img-top"
                                 style="height:140px; object-fit:cover;">

                            <div class="card-body p-2 text-center">
                                <form action="{{ route('admin.galeri.foto.destroy', $foto->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger w-100"
                                            onclick="return confirm('Hapus foto ini?')">
                                        🗑 Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button id="saveBelowPhotosBtn" type="button" class="btn btn-primary px-4">
                        💾 Simpan Perubahan
                    </button>
                </div>
            @endif

        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    (function(){
        var btn = document.getElementById('saveBelowPhotosBtn');
        if(!btn) return;
        btn.addEventListener('click', function(){
            var form = document.getElementById('galeriForm');
            if(!form) return;
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
            form.submit();
        });
    })();
</script>
@endpush
