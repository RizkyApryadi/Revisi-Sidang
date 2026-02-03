@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')
<div class="row mb-3">
    <!-- Kepala Keluarga -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-success mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">
                        Kepala Keluarga
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Jemaat -->
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card card-statistic-1 text-center" style="padding:8px;">
            <div class="card-icon bg-warning mx-auto" style="width:44px;height:44px;line-height:44px;font-size:18px;">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="card-wrap mt-2">
                <div class="card-header" style="padding:0;">
                    <h6 style="font-size:12px;margin:0;font-weight:600;">
                        Total Jemaat
                    </h6>
                </div>
                {{-- <div class="card-body" style="padding:0;font-size:15px;font-weight:700;">
                    {{ number_format($totalJemaat) }} Orang
                </div> --}}
            </div>
        </div>
    </div>
</div>


<!-- Keluarga section -->
<div class="keluarga-section mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Keluarga</h4>
            <div>

                <a href="{{ route('admin.jemaat.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jemaat
                </a>
            </div>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive-wrapper">
                <div class="table-responsive-wrapper">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="thead-dark">
                            <tr class="text-center align-middle">
                                <th style="width:4%">No</th>
                                <th style="width:16%">No Registrasi</th>
                                <th style="width:28%"> Kepala Keluarga</th>
                                <th style="width:34%">Alamat</th>
                                <th style="width:10%">Wijk</th>
                                <th style="width:9%; white-space:nowrap;">Anggota</th>
                                <th style="width:12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @foreach(($keluargas ?? []) as $idx => $kel)
                            @php
                            $kepala = $kel->jemaats->firstWhere('hubungan_keluarga', 'Kepala Keluarga');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="text-center">{{ $kel->nomor_registrasi }}</td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kepala ? $kepala->nama :
                                    '-' }}
                                </td>
                                <td style="white-space: normal; word-break: break-word;">{{ $kel->alamat }}</td>
                                <td class="text-center">{{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                                <td class="text-center">{{ $kel->jemaats_count ?? $kel->jemaats->count() }}</td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-info btn-sm mr-1 btn-view-keluarga"
                                        data-id="{{ $kel->id }}" title="Lihat"><i class="fas fa-eye"></i></button>
                                    @php $firstJemaat = $kel->jemaats->first(); @endphp
                                    @if($firstJemaat)
                                    <a href="{{ url('/admin/jemaat/'.$firstJemaat->id.'/edit') }}"
                                        class="btn btn-warning btn-sm mr-1" title="Edit Jemaat"><i
                                            class="fas fa-edit"></i></a>
                                    @else
                                    <button class="btn btn-warning btn-sm mr-1" disabled
                                        title="Tidak ada anggota untuk diedit"><i class="fas fa-edit"></i></button>
                                    @endif
                                    <form action="{{ url('/admin/keluarga/'.$kel->id) }}" method="POST"
                                        style="display:inline;" class="confirm-delete-keluarga">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jemaat flat list -->
<div class="jemaat-section mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Jemaat</h4>

        </div>

        <div class="card-body pt-2">
            @php
            $allJemaats = [];
            if(!empty($keluargas)){
                // Define explicit ordering; Kepala first then Suami/Istri/Anak
                $order = ['Kepala Keluarga' => 0, 'Suami' => 1, 'Istri' => 2, 'Anak' => 3];
                foreach($keluargas as $kel){
                    foreach($kel->jemaats as $j){
                        $j->keluarga_ref = $kel;
                        $allJemaats[] = $j;
                    }
                }

                // Sort by keluarga id then by hubungan order (Kepala, Suami, Istri, Anak)
                usort($allJemaats, function($a, $b) use ($order){
                    $ka = $a->keluarga_ref->id <=> $b->keluarga_ref->id;
                    if($ka !== 0) return $ka;
                    $oa = $order[$a->hubungan_keluarga ?? ''] ?? 99;
                    $ob = $order[$b->hubungan_keluarga ?? ''] ?? 99;
                    return $oa <=> $ob;
                });
            }
            @endphp

            <div class="table-responsive-wrapper">
                <table class="table table-bordered table-striped w-100">
                    <thead class="thead-light">
                        <tr class="text-center align-middle">
                            <th style="width:3%">No</th>
                            <th style="width:18%">Nama</th>
                            <th style="width:9%">Hubungan</th>
                            <th style="width:6%">JK</th>
                            <th style="width:20%">Tempat, Tgl Lahir</th>
                            <th style="width:7%">No. Telp</th>
                            <th style="width:6%">Sidi</th>
                            <th style="width:6%">Baptis</th>
                            <th style="width:9%">No Registrasi</th>
                            <th style="width:12%">Alamat</th>
                            <th style="width:4%">Wijk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allJemaats as $i => $jm)
                        <tr class="jemaat-row" data-jemaat-id="{{ $jm->id }}">
                            @php
                            $kepala = ($jm->keluarga_ref && $jm->keluarga_ref->jemaats) ?
                                $jm->keluarga_ref->jemaats->firstWhere('hubungan_keluarga', 'Kepala Keluarga') : null;
                            $isKepala = $kepala && ($kepala->id == $jm->id);
                            $displayHubungan = $isKepala ? 'Kepala Keluarga' : ($jm->hubungan_keluarga ?? ($jm->hubungan ?? '-'));
                            @endphp
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $jm->nama ?? '-' }}</td>
                            <td class="text-center">{{ $displayHubungan }}</td>
                            <td class="text-center">{{ $jm->jenis_kelamin ? strtoupper(substr($jm->jenis_kelamin,0,1)) : '-' }}</td>
                            <td style="white-space: normal;">{{ $jm->tempat_lahir ?? '-' }},
                                {{ $jm->tanggal_lahir ? \Carbon\Carbon::parse($jm->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">{{ $jm->no_telp ?? '-' }}</td>
                            <td class="text-center">
                                @if($jm->tanggal_sidi)
                                    <i class="fas fa-check-circle text-success" title="{{ \Carbon\Carbon::parse($jm->tanggal_sidi)->format('d-m-Y') }}"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger" title="Belum Sidi"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($jm->tanggal_baptis)
                                    <i class="fas fa-check-circle text-success" title="{{ \Carbon\Carbon::parse($jm->tanggal_baptis)->format('d-m-Y') }}"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger" title="Belum Baptis"></i>
                                @endif
                            </td>
                            <td style="white-space: normal;">{{ $jm->keluarga_ref->nomor_registrasi ?? '-' }}</td>
                            <td style="white-space: normal;">{{ $jm->keluarga_ref->alamat ?? '-' }}</td>
                            <td class="text-center">{{ optional(optional($jm->keluarga_ref)->wijk)->nama_wijk ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Belum ada jemaat terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Context menu for jemaat rows -->
            <div id="jemaat-context-menu" style="display:none; position:absolute; z-index:99999; background:#fff; border:1px solid rgba(0,0,0,0.12); box-shadow:0 6px 18px rgba(0,0,0,0.12); border-radius:6px; min-width:180px;">
                <ul style="list-style:none;margin:0;padding:6px 0;">
                    <li data-role="penatua" style="padding:8px 12px;cursor:pointer;border-bottom:1px solid rgba(0,0,0,0.03);">Jadikan Penatua</li>
                    <li data-role="pendeta" style="padding:8px 12px;cursor:pointer;">Jadikan Pendeta</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .jemaat-compact {
        font-size: 12px;
    }

    .jemaat-compact .card-header h4 {
        font-size: 14px;
        margin-bottom: 0;
    }

    .jemaat-compact .card-body {
        padding: .5rem;
    }

    .jemaat-compact table.table th,
    .jemaat-compact table.table td {
        padding: .35rem .5rem;
        vertical-align: middle;
    }

    .jemaat-compact .btn {
        padding: .25rem .45rem;
        font-size: .75rem;
    }

    .jemaat-compact .btn .fas {
        font-size: .75rem;
    }

    .jemaat-compact img.rounded-circle {
        width: 32px;
        height: 32px;
        object-fit: cover;
    }

    .jemaat-compact .text-truncate {
        max-width: 100%;
        display: block;
        white-space: normal;
        word-break: break-word;
        overflow: visible;
    }

    .jemaat-compact .card {
        padding-bottom: 1rem;
    }

    .jemaat-compact .card-body {
        padding-bottom: 1.25rem;
    }

    .jemaat-compact table {
        margin-bottom: 0;
    }

    /* Center and tidy all table content in keluarga and jemaat sections */
    .keluarga-section .card-body table th,
    .keluarga-section .card-body table td,
    .jemaat-section .card-body table th,
    .jemaat-section .card-body table td {
        text-align: center;
        vertical-align: middle;
        white-space: normal;
        /* prefer normal word-break and allow wrapping at word boundaries */
        word-break: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: .55rem .6rem;
        /* tighter padding for cleaner header */
        font-size: .95rem;
        /* slightly smaller text for compact header */
    }

    /* Ensure long addresses wrap nicely */
    .keluarga-section .card-body table td,
    .jemaat-section .card-body table td {
        max-width: 480px;
        /* allow more horizontal space for addresses */
    }

    /* Make headers slightly bolder and centered */
    .keluarga-section .card-header h4,
    .jemaat-section .card-header h4 {
        text-align: center;
    }

    /* Ensure table headers are fully visible and styled blue/white */
    .keluarga-section .card-body table,
    .jemaat-section .card-body table,
    .kk-table {
        table-layout: auto;
        /* allow headers to size naturally and wrap */
    }

    .keluarga-section .card-body table thead th,
    .jemaat-section .card-body table thead th {
        background: #2563eb !important;
        color: #ffffff !important;
        white-space: normal !important;
        /* allow wrapping instead of truncation; prefer word boundaries */
        word-break: normal !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        overflow: visible !important;
        text-overflow: clip !important;
        padding: .22rem .4rem !important;
        /* tighter header cells */
        border-color: rgba(255, 255, 255, 0.06) !important;
        font-size: .72rem !important;
        /* smaller header text */
        font-weight: 600 !important;
        letter-spacing: .08px !important;
        text-transform: none !important;
        line-height: 1.1 !important;
        hyphens: auto !important;
    }

    /* Ensure header text wraps to multiple lines instead of being cut */
    .keluarga-section .card-body table thead th span,
    .jemaat-section .card-body table thead th span {
        display: inline-block;
        max-width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Prevent action buttons from being clipped; ensure header and cell reserve space */
    .keluarga-section .card-body table thead th:last-child,
    .keluarga-section .card-body table td:last-child,
    .jemaat-section .card-body table thead th:last-child,
    .jemaat-section .card-body table td:last-child {
        white-space: nowrap;
        min-width: 180px;
    }

    /* Use flex inside the action cell so multiple small buttons don't wrap awkwardly */
    .keluarga-section .card-body table td:last-child,
    .jemaat-section .card-body table td:last-child {
        display: flex !important;
        gap: .35rem;
        justify-content: center;
        align-items: center;
    }

    /* Make buttons smaller and inline-flex to avoid overflow */
    .keluarga-section .card-body table .btn,
    .jemaat-section .card-body table .btn,
    .kk-table .btn {
        padding: .18rem .32rem !important;
        font-size: .72rem !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    /* Ensure header text never gets truncated */
    .keluarga-section .card-body table thead th,
    .jemaat-section .card-body table thead th {
        overflow: visible !important;
        text-overflow: clip !important;
        max-width: none !important;
    }

    /* Remove any accidental text-overflow on headers */
    .keluarga-section .card-body table thead th *,
    .jemaat-section .card-body table thead th * {
        text-overflow: clip !important;
        overflow: visible !important;
    }
</style>

<style>
    /* Responsive table wrapper to allow horizontal scroll on small screens */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Ensure table keeps readable columns but can scroll horizontally */
    .table-responsive-wrapper table {
        width: 100%;
        /* increase min-width so columns keep readable widths before scrolling */
        min-width: 1100px;
        /* allow scrolling on small screens */
    }

    /* Reduce padding and font-size on narrower screens */
    @media (max-width: 1200px) {

        .keluarga-section .card-body table th,
        .keluarga-section .card-body table td,
        .jemaat-section .card-body table th,
        .jemaat-section .card-body table td {
            padding: .6rem .5rem;
            font-size: .95rem;
        }
    }

    @media (max-width: 768px) {
        .table-responsive-wrapper table {
            min-width: 680px;
        }

        .keluarga-section .card-body table th,
        .keluarga-section .card-body table td,
        .jemaat-section .card-body table th,
        .jemaat-section .card-body table td {
            font-size: .82rem;
            padding: .35rem .35rem;
        }
    }
</style>

<!-- SweetAlert2 -->
<style>
    /* Ensure SweetAlert overlay is above any fixed footer or other high-z elements */
    .swal2-container,
    .swal2-backdrop,
    .swal2-container .swal2-modal {
        z-index: 9999999 !important;
    }

    /* Make SweetAlert backdrop less dark (more transparent) */
    .swal2-container,
    .swal2-container .swal2-backdrop,
    .swal2-backdrop,
    .swal2-container::before {
        background-color: rgba(0, 0, 0, 0.7) !important;
        opacity: 1 !important;
        transition: none !important;
        -webkit-transition: none !important;
        backdrop-filter: none !important;
        filter: none !important;
    }

    /* Ensure container covers page and blocks interaction underneath */
    .swal2-container {
        pointer-events: auto !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function(){
        function initJemaatContextMenu(){
            @if(session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: {!! json_encode(session('success')) !!},
                    timer: 2200,
                    showConfirmButton: false
                });
            @endif

            @if(session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: {!! json_encode(session('error')) !!},
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session()->has('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: {!! json_encode(session('warning')) !!},
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            // Konfirmasi hapus keluarga dengan SweetAlert
            document.querySelectorAll('.confirm-delete-keluarga').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Menghapus data keluarga akan otomatis menghapus anggota keluarga',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(function(result){
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Konfirmasi hapus jemaat dengan SweetAlert
            document.querySelectorAll('.confirm-delete-jemaat').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus jemaat ini?',
                        text: 'Data jemaat akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(function(result){
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Right-click context menu for jemaat list (delegated)
            const cm = document.getElementById('jemaat-context-menu');
            let currentJemaatId = null;
            let currentJemaatRow = null;

            // Debug helpers: log initialization and capture global events to diagnose why right-click isn't detected
            try{
                console.log('initJemaatContextMenu: initializing context menu handlers');
            }catch(e){}

            // Global listeners for debugging (will still run in production harmlessly)
            document.addEventListener('contextmenu', function(ev){
                try{ console.log('global contextmenu on', ev.target && ev.target.tagName, ev.target); }catch(e){}
            });
            document.addEventListener('mousedown', function(ev){
                try{ if(ev.button === 2) console.log('global mousedown (right) on', ev.target && ev.target.tagName, ev.target); }catch(e){}
            });

            function hideContextMenu(){ if(cm){ cm.style.display='none'; currentJemaatId = null; } }

            document.addEventListener('click', function(e){
                if (cm && !cm.contains(e.target)) hideContextMenu();
            });

            // Use delegation on the table body so dynamic rows are handled as well
            const tbody = document.querySelector('.jemaat-section .card-body table tbody');
            if (tbody) {
                tbody.addEventListener('contextmenu', function(ev){
                    const row = ev.target.closest('tr.jemaat-row');
                    if(!row) return; // not on a jemaat row
                    ev.preventDefault();
                    currentJemaatId = row.getAttribute('data-jemaat-id');
                    currentJemaatRow = row;
                    if(!cm) return;
                    // Use fixed positioning and client coordinates so menu aligns with cursor
                    cm.style.position = 'fixed';
                    cm.style.display = 'block';
                    // measure menu size
                    const menuRect = cm.getBoundingClientRect();
                    const menuWidth = menuRect.width || 180;
                    const menuHeight = menuRect.height || 80;
                    // client coordinates (relative to viewport)
                    const cx = ev.clientX + 4;
                    const cy = ev.clientY + 4;
                    let left = cx;
                    let top = cy;
                    // clamp to viewport
                    if (left + menuWidth > window.innerWidth) {
                        left = Math.max(8, window.innerWidth - menuWidth - 8);
                    }
                    if (top + menuHeight > window.innerHeight) {
                        top = Math.max(8, cy - menuHeight - 8);
                    }
                    cm.style.left = left + 'px';
                    cm.style.top = top + 'px';
                });
            }

            if (cm) {
                cm.querySelectorAll('li[data-role]').forEach(function(opt){
                    opt.addEventListener('click', function(){
                        const role = this.getAttribute('data-role');
                        if(!currentJemaatId) return hideContextMenu();
                        // confirm
                        Swal.fire({
                            title: 'Yakin?',
                            text: 'Jadikan jemaat ini sebagai ' + (role === 'penatua' ? 'Penatua' : 'Pendeta') + '?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then(function(res){
                            if(!res.isConfirmed) return hideContextMenu();

                            // If Penatua or Pendeta: redirect to respective create form with jemaat id and name prefilled
                            if (role === 'penatua' || role === 'pendeta') {
                                // perform quick AJAX check to ensure jemaat isn't already registered in the other role
                                const checkUrl = '/admin/jemaat/' + encodeURIComponent(currentJemaatId) + '/check-role';
                                fetch(checkUrl, { headers: { 'Accept': 'application/json' } })
                                    .then(function(r){ return r.json(); })
                                    .then(function(info){
                                        if (!info) {
                                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memeriksa status jemaat.' });
                                            return hideContextMenu();
                                        }
                                        if (role === 'penatua') {
                                            if (info.is_pendeta) {
                                                Swal.fire({ icon: 'error', title: 'Dilarang', text: 'Jemaat sudah terdaftar sebagai Pendeta, tidak bisa menjadi Penatua.' });
                                                return hideContextMenu();
                                            }
                                            if (info.is_penatua) {
                                                Swal.fire({ icon: 'info', title: 'Info', text: 'Jemaat sudah terdaftar sebagai Penatua.' });
                                                return hideContextMenu();
                                            }
                                        }
                                        if (role === 'pendeta') {
                                            if (info.is_penatua) {
                                                Swal.fire({ icon: 'error', title: 'Dilarang', text: 'Jemaat sudah terdaftar sebagai Penatua, tidak bisa menjadi Pendeta.' });
                                                return hideContextMenu();
                                            }
                                            if (info.is_pendeta) {
                                                Swal.fire({ icon: 'info', title: 'Info', text: 'Jemaat sudah terdaftar sebagai Pendeta.' });
                                                return hideContextMenu();
                                            }
                                        }

                                        // allowed: redirect to create form with prefilled jemaat_id and nama
                                        try {
                                            const name = currentJemaatRow ? (currentJemaatRow.querySelector('td:nth-child(2)') && currentJemaatRow.querySelector('td:nth-child(2)').innerText.trim()) : '';
                                            const base = role === 'penatua' ? '/admin/penatua/create' : '/admin/pendeta/create';
                                            let url = base + '?jemaat_id=' + encodeURIComponent(currentJemaatId);
                                            if (name) url += '&nama=' + encodeURIComponent(name);
                                            hideContextMenu();
                                            window.location.href = url;
                                        } catch (e) {
                                            hideContextMenu();
                                        }
                                    }).catch(function(err){
                                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memeriksa status jemaat.' });
                                        hideContextMenu();
                                    });
                                return;
                            }
                            fetch('/admin/jemaat/' + encodeURIComponent(currentJemaatId) + '/assign-role', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': {!! json_encode(csrf_token()) !!},
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ role: role })
                            }).then(function(resp){
                                return resp.json();
                            }).then(function(data){
                                if(data && data.success){
                                    Swal.fire({ icon: 'success', title: 'Sukses', text: data.message || 'Berhasil.' , timer:1800, showConfirmButton:false });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan' });
                                }
                            }).catch(function(err){
                                Swal.fire({ icon: 'error', title: 'Error', text: err && err.message ? err.message : 'Terjadi kesalahan jaringan' });
                            }).finally(hideContextMenu);
                        });
                    });
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initJemaatContextMenu);
        } else {
            initJemaatContextMenu();
        }
    })();
</script>

<!-- Modal containers for keluarga detail (Kartu Keluarga) -->
@foreach($keluargas ?? [] as $kel)
<!-- Modal containers for keluarga detail (Kartu Keluarga) -->
<div id="modal-keluarga-{{ $kel->id }}" class="modal-custom" style="display:none;">
    <div class="modal-overlay"></div>

    <div class="modal-box">
        <!-- HEADER -->
        <div class="modal-header">
            <div>
                <h3 style="margin:0;">Informasi Keluarga</h3>
            </div>
            <button class="modal-close btn btn-sm btn-secondary">Tutup</button>
        </div>

        <div class="modal-body">

            <!-- ================= DETAIL KELUARGA ================= -->
            <div style="margin-top:10px;">


                <table class="kk-info-table">
                    <tr>
                        <td>Nomor Registrasi</td>
                        <td>: {{ $kel->nomor_registrasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Wijk</td>
                        <td>: {{ optional($kel->wijk)->nama_wijk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $kel->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Registrasi</td>
                        <td>: {{ $kel->tanggal_registrasi
                            ? \Carbon\Carbon::parse($kel->tanggal_registrasi)->format('d-m-Y')
                            : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pernikahan</td>
                        <td>: {{ $kel->tanggal_pernikahan
                            ? \Carbon\Carbon::parse($kel->tanggal_pernikahan)->format('d-m-Y')
                            : '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- ================= ANGGOTA ================= -->
            <div style="margin-top:24px;">
                <div style="
                    font-weight:600;
                    margin-bottom:8px;
                    border-bottom:1px solid #e5e7eb;
                    padding-bottom:4px;
                ">
                    Daftar Anggota Keluarga
                </div>

                <div class="table-responsive-wrapper">
                    <table class="kk-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Hubungan</th>
                                <th>JK</th>
                                <th>Tempat, Tgl Lahir</th>
                                <th>No. Telp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kel->jemaats as $i => $j)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $j->nama ?? '-' }}</td>
                                <td>{{ $j->hubungan_keluarga ?? '-' }}</td>
                                <td>{{ $j->jenis_kelamin ? strtoupper(substr($j->jenis_kelamin,0,1)) : '-' }}</td>
                                <td>
                                    {{ $j->tempat_lahir ?? '-' }},
                                    {{ $j->tanggal_lahir
                                    ? \Carbon\Carbon::parse($j->tanggal_lahir)->format('d-m-Y')
                                    : '-' }}
                                </td>
                                <td>{{ $j->no_telp ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; color:#64748b;">
                                    Tidak ada anggota keluarga
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach



<style>
    /* Modal / Kartu Keluarga styles */
    .modal-custom {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-box {
        position: relative;
        background: #fff;
        border-radius: 14px;
        max-width: 1200px;
        width: 96%;
        max-height: 92vh;
        overflow: auto;
        padding: 28px;
        box-shadow: 0 40px 140px rgba(2, 6, 23, .45);
        z-index: 2;
        font-size: 15px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 12px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        letter-spacing: .6px;
    }

    .modal-body {
        padding-top: 8px;
    }

    .kk-grid {
        display: flex;
        gap: 22px;
        flex-wrap: wrap;
    }

    .kk-left {
        flex: 1 1 360px;
        min-width: 320px;
    }

    .kk-right {
        flex: 2 1 640px;
        min-width: 420px;
    }

    .kk-anggota-wrap {
        overflow: auto;
    }

    .kk-table thead th {
        background: #f8fafc;
    }

    .kk-table td,
    .kk-table th {
        padding: .5rem .75rem;
    }

    .kk-member-card {
        padding: 16px;
        border-radius: 10px;
    }

    .kk-member-card img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px
    }

    .kk-member-card .name {
        font-weight: 800;
        font-size: 1rem;
    }

    .kk-member-card .meta {
        font-size: 0.95rem;
        color: #374151
    }

    .modal-close {
        margin-left: 8px
    }

    .modal-custom {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .55);
    }

    .modal-box {
        background: #fff;
        width: 95%;
        max-width: 1000px;
        max-height: 90vh;
        overflow: auto;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
        z-index: 2;
        font-family: "Segoe UI", sans-serif;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .kk-top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin: 20px 0;
    }

    .kk-info,
    .kk-kepala {
        border: 1px solid #000;
        padding: 14px;
    }

    .kk-info-table td {
        padding: 4px 6px;
        font-size: 14px;
    }

    .kepala-wrap {
        display: flex;
        gap: 12px;
    }

    .kepala-wrap img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .kk-anggota h4 {
        text-align: center;
        margin: 20px 0 10px;
    }

    .kk-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .kk-table th,
    .kk-table td {
        border: 1px solid #000;
        padding: 6px;
    }

    .kk-table th {
        background: #f3f4f6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.btn-view-keluarga').forEach(function(btn){
            btn.addEventListener('click', function(){
                const id = this.getAttribute('data-id');
                const modal = document.getElementById('modal-keluarga-' + id);
                if(modal){ modal.style.display = 'flex'; modal.setAttribute('aria-hidden','false'); }
            });
        });
        document.querySelectorAll('.modal-custom .modal-close, .modal-custom .modal-overlay').forEach(function(el){
            el.addEventListener('click', function(e){
                const modal = this.closest('.modal-custom');
                if(modal){ modal.style.display = 'none'; modal.setAttribute('aria-hidden','true'); }
            });
        });
    });
</script>

@endsection