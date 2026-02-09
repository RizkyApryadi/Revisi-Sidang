@extends('layouts.main')
@section('title', 'Detail Renungan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Renungan</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <h3>{{ $renungan->judul }}</h3>
                <p class="text-muted">{{ optional($renungan->tanggal)->format('d M Y') }}
                    @if($renungan->pendeta)
                        - {{ $renungan->pendeta->nama ?? '' }}
                    @endif
                </p>

                <div class="mb-3">
                    @if($renungan->status === 'publish')
                        <span class="badge badge-success">Publish</span>
                    @else
                        <span class="badge badge-warning">Draft</span>
                    @endif
                </div>

                <div class="content">
                    {!! $renungan->konten !!}
                </div>

                <div class="mt-3">
                    <a href="{{ route('pendeta.renungan.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('pendeta.renungan.edit', $renungan->id) }}" class="btn btn-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
