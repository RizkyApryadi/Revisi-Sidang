@extends('layouts.main')
@section('title', 'Detail Pendaftaran Sidi')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Detail Pendaftaran Sidi</h4>
            <div>
                <a href="{{ route('penatua.pelayanan.katekisasi.show', $p->katekisasi_id) }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>Nama:</strong> {{ $p->nama }}</div>
                <div class="col-md-6"><strong>Jenis Kelamin:</strong> {{ $p->jenis_kelamin }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal Lahir:</strong> {{ $p->tanggal_lahir }}</div>
                <div class="col-md-4"><strong>No HP:</strong> {{ $p->no_hp }}</div>
                <div class="col-md-4"><strong>Email:</strong> {{ $p->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Nama Ayah:</strong> {{ $p->nama_ayah }}</div>
                <div class="col-md-6"><strong>Nama Ibu:</strong> {{ $p->nama_ibu }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12"><strong>Alamat:</strong> {{ $p->alamat }}</div>
            </div>

            <h5>Berkas</h5>
            <ul>
                @if($p->foto_4x6)
                    <li><a href="{{ asset('storage/'.$p->foto_4x6) }}" target="_blank">Foto 4x6</a></li>
                @endif
                @if($p->foto_3x4)
                    <li><a href="{{ asset('storage/'.$p->foto_3x4) }}" target="_blank">Foto 3x4</a></li>
                @endif
                @if($p->surat_baptis)
                    <li><a href="{{ asset('storage/'.$p->surat_baptis) }}" target="_blank">Surat Baptis</a></li>
                @endif
                @if($p->kartu_keluarga)
                    <li><a href="{{ asset('storage/'.$p->kartu_keluarga) }}" target="_blank">Kartu Keluarga</a></li>
                @endif
                @if($p->surat_pengantar_sintua)
                    <li><a href="{{ asset('storage/'.$p->surat_pengantar_sintua) }}" target="_blank">Surat Pengantar Sintua</a></li>
                @endif
            </ul>

            <h5 class="mt-4">Status</h5>
            <p><strong>Status:</strong> {{ ucfirst($p->status_pengajuan ?? 'pending') }}</p>
            @if($p->catatan_admin)
                <p><strong>Catatan:</strong> {{ $p->catatan_admin }}</p>
            @endif
        </div>
    </div>
</section>
@endsection
