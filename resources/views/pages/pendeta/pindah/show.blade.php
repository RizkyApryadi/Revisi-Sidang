@extends('layouts.main')
@section('title', 'Detail Permohonan Pindah')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Detail Permohonan Pindah</h4>
        </div>
        <div class="card-body">
            <p><strong>Nama Jemaat:</strong> {{ $pendaftaran->jemaat ? $pendaftaran->jemaat->nama_lengkap : '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $pendaftaran->created_at ? $pendaftaran->created_at->format('d-m-Y H:i') : '-' }}</p>
            <p><strong>Keterangan:</strong> {{ $pendaftaran->keterangan ?? '-' }}</p>
            <a href="{{ route('pendeta.pindah') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</section>
@endsection
