@extends('layouts.main')
@section('title', 'Detail Berita')

@section('content')
<section class="section">
    <div class="card shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detail Berita</h4>
            <div>
                <a href="{{ route('admin.berita') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- JUDUL --}}
            <h2 class="fw-bold mb-1">{{ $berita->judul }}</h2>

            {{-- TANGGAL --}}
            <p class="text-muted mb-4">
                <i class="far fa-calendar-alt"></i>
                {{ $berita->tanggal ? \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('j F Y') : '-' }}
            </p>

            {{-- FOTO --}}
            @if(!empty($berita->foto))
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $berita->foto) }}"
                         class="img-fluid rounded shadow-sm"
                         style="max-height:400px; object-fit:cover;"
                         alt="Foto Berita">
                </div>
            @endif

            {{-- RINGKASAN / ISI --}}
            <div class="mb-4">
                <h5 class="fw-semibold mb-2">Ringkasan</h5>
                <div class="border rounded p-3 bg-light">
                    {!! $berita->ringkasan !!}
                </div>
            </div>

            {{-- FILE --}}
            <div class="mb-3">
                <h5 class="fw-semibold mb-2">Lampiran</h5>
                @if(!empty($berita->file))
                    <a href="{{ asset('storage/' . $berita->file) }}"
                       target="_blank"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download"></i> Unduh File
                    </a>
                @else
                    <p class="text-muted mb-0">Tidak ada file lampiran</p>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
