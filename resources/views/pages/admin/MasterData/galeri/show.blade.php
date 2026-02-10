@extends('layouts.main')
@section('title', 'Detail Galeri')

@section('content')
<section class="section">
    <div class="container-fluid">

        <div class="card shadow-sm border-0">
            {{-- Header --}}
            <div class="card-header bg-white border-0 pb-0 position-relative">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 fw-bold">Detail Galeri</h4>
                        <small class="text-muted">Informasi lengkap data galeri</small>
                    </div>

                    <div class="d-flex gap-2 align-items-center position-absolute" style="right:16px;top:16px;">
                        <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                        <a href="{{ route('admin.galeri') }}" class="btn btn-outline-secondary btn-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body pt-3 position-relative">

                {{-- Judul --}}
                <h3 class="fw-semibold mb-2">{{ $galeri->judul }}</h3>

                {{-- Meta Info --}}
                <div class="mb-3 d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border">
                        📅 {{ $galeri->tanggal
                        ? \Carbon\Carbon::parse($galeri->tanggal)->format('d M Y')
                        : '-' }}
                    </span>

                    <span class="badge bg-light text-dark border">
                        👤 {{ optional($galeri->user)->name ?? '-' }}
                    </span>
                </div>

                {{-- Deskripsi --}}
                @if(!empty($galeri->deskripsi))
                <div class="mb-4 p-3 bg-light rounded border">
                    {!! nl2br(e($galeri->deskripsi)) !!}
                </div>
                @endif


                {{-- Divider --}}
                <hr class="my-4">

                {{-- Grid Foto --}}
                <h5 class="mb-3 fw-semibold">Foto Galeri</h5>

                @if($galeri->fotos && $galeri->fotos->count())
                <div class="row g-3">
                    @foreach($galeri->fotos as $foto)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="ratio ratio-4x3">
                                <img src="{{ asset('storage/' . ltrim($foto->foto, 'public/')) }}"
                                    class="img-fluid rounded view-photo" style="object-fit: cover;cursor:pointer;" alt="Foto Galeri">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4 border rounded bg-light">
                    Belum ada foto untuk galeri ini.
                </div>
                @endif

                <!-- Image Modal Viewer (full-screen overlay) -->
                <div id="imageModal" style="display:none;position:fixed;inset:0;z-index:2200;align-items:center;justify-content:center;background:rgba(0,0,0,0.8);">
                    <div style="position:relative;max-width:95vw;max-height:95vh;padding:12px;">
                        <button id="imageModalClose" class="btn btn-light btn-sm" style="position:absolute;right:-8px;top:-8px;z-index:2300;">✕</button>
                        <img id="imageModalImg" src="" alt="Preview" style="display:block;max-width:100%;max-height:80vh;border-radius:6px;" />
                        <div id="imageModalCaption" style="color:#fff;margin-top:8px;text-align:center;font-size:14px;"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

@push('script')
<script>
    (function(){
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('imageModalImg');
        var modalCap = document.getElementById('imageModalCaption');
        var closeBtn = document.getElementById('imageModalClose');

        // Ensure modal is attached to document.body so it overlays entire viewport
        try{
            if(modal && modal.parentNode !== document.body){
                document.body.appendChild(modal);
            }
        }catch(e){ /* ignore */ }

        // enforce full-screen fixed overlay and high z-index
        if(modal){
            modal.style.position = 'fixed';
            modal.style.inset = '0';
            modal.style.zIndex = '999999';
            modal.style.display = 'none';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.background = 'rgba(0,0,0,0.85)';
        }

        function openModal(src, alt){
            if(!modal) return;
            modalImg.src = src;
            modalCap.textContent = alt || '';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(){
            if(!modal) return;
            modal.style.display = 'none';
            modalImg.src = '';
            modalCap.textContent = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('click', function(e){
            var target = e.target;
            if(target && target.classList && target.classList.contains('view-photo')){
                openModal(target.src, target.alt || 'Foto Galeri');
            }
            if(target === modal) closeModal();
        });

        if(closeBtn) closeBtn.addEventListener('click', closeModal);
        document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeModal(); });
    })();
</script>
@endpush

@endsection