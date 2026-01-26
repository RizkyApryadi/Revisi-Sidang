@extends('layouts.main')
@section('title', 'Detail Pendaftaran Pernikahan')

@section('content')

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Detail Pendaftaran</h4>
            <div>
                <a href="{{ route('penatua.pernikahan') }}" class="btn btn-secondary btn-sm">Kembali</a>
                <form action="{{ route('penatua.pelayanan.pernikahan.destroy', $pendaftaran->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pendaftaran ini?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <h5>Informasi Acara</h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal Perjanjian:</strong> {{ $pendaftaran->tanggal_perjanjian ? $pendaftaran->tanggal_perjanjian->format('d-m-Y') : '-' }}</div>
                <div class="col-md-4"><strong>Jam Perjanjian:</strong> {{ $pendaftaran->jam_perjanjian ?? '-' }}</div>
                <div class="col-md-4"><strong>Keterangan Perjanjian:</strong> {{ $pendaftaran->keterangan_perjanjian ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal Pemberkatan:</strong> {{ $pendaftaran->tanggal_pemberkatan ? $pendaftaran->tanggal_pemberkatan->format('d-m-Y') : '-' }}</div>
                <div class="col-md-4"><strong>Jam Pemberkatan:</strong> {{ $pendaftaran->jam_pemberkatan ?? '-' }}</div>
                <div class="col-md-4"><strong>Keterangan Pemberkatan:</strong> {{ $pendaftaran->keterangan_pemberkatan ?? '-' }}</div>
            </div>

            <h5 class="mt-4">Mempelai</h5>
            <div class="row">
                @php
                    $mempelais = $pendaftaran->mempelais;
                @endphp

                @foreach($mempelais as $i => $m)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Pasangan {{ $i+1 }}</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Nama:</strong> {{ $m->nama ?? '-' }}</p>
                            <p><strong>No HP:</strong> {{ $m->no_hp ?? '-' }}</p>
                            <p><strong>Email:</strong> {{ $m->email ?? '-' }}</p>
                            <p><strong>Tempat, Tanggal Lahir:</strong> {{ $m->tempat_lahir ?? '-' }}, {{ $m->tanggal_lahir ? \Carbon\Carbon::parse($m->tanggal_lahir)->format('d-m-Y') : '-' }}</p>
                            <p><strong>Tanggal Baptis:</strong> {{ $m->tanggal_baptis ? \Carbon\Carbon::parse($m->tanggal_baptis)->format('d-m-Y') : '-' }}</p>
                            <p><strong>Tanggal Sidi:</strong> {{ $m->tanggal_sidi ? \Carbon\Carbon::parse($m->tanggal_sidi)->format('d-m-Y') : '-' }}</p>
                            <p><strong>Nama Ayah:</strong> {{ $m->nama_ayah ?? '-' }}</p>
                            <p><strong>Nama Ibu:</strong> {{ $m->nama_ibu ?? '-' }}</p>
                            <p><strong>Asal Gereja:</strong> {{ $m->asal_gereja ?? '-' }}</p>
                            <p><strong>Wijk:</strong> {{ $m->wijk ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $m->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <h5 class="mt-4">Berkas</h5>
            <ul>
                @if($pendaftaran->surat_keterangan_gereja_asal)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_keterangan_gereja_asal) }}" target="_blank">Surat Keterangan Gereja Asal</a></li>
                @endif
                @if($pendaftaran->surat_baptis_pria)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_baptis_pria) }}" target="_blank">Surat Baptis (Pria)</a></li>
                @endif
                @if($pendaftaran->surat_baptis_wanita)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_baptis_wanita) }}" target="_blank">Surat Baptis (Wanita)</a></li>
                @endif
                @if($pendaftaran->surat_sidi_pria)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_sidi_pria) }}" target="_blank">Surat Sidi (Pria)</a></li>
                @endif
                @if($pendaftaran->surat_sidi_wanita)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_sidi_wanita) }}" target="_blank">Surat Sidi (Wanita)</a></li>
                @endif
                @if($pendaftaran->foto)
                    <li><a href="{{ asset('storage/' . $pendaftaran->foto) }}" target="_blank">Foto Bersama</a></li>
                @endif
                @if($pendaftaran->surat_pengantar)
                    <li><a href="{{ asset('storage/' . $pendaftaran->surat_pengantar) }}" target="_blank">Surat Pengantar</a></li>
                @endif
            </ul>

            <h5 class="mt-4">Status & Review</h5>
            <p><strong>Status:</strong> {{ ucfirst($pendaftaran->status ?? 'pending') }}</p>
            @if($pendaftaran->review_reason)
                <p><strong>Alasan Review:</strong> {{ $pendaftaran->review_reason }}</p>
            @endif
            @if(isset($reviewer))
                <p><strong>Di-review oleh:</strong> {{ $reviewer->name }} pada {{ $pendaftaran->reviewed_at ? $pendaftaran->reviewed_at->format('d-m-Y H:i') : '-' }}</p>
            @endif

        </div>
    </div>
</section>

@endsection
