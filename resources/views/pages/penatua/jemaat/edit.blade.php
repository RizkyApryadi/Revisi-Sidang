@extends('layouts.main')
@section('title','Edit Jemaat')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <div class="canvas-wrapper bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

        <div class="mb-8">
            <h1 class="page-header">Edit Data Jemaat</h1>
            <p class="page-subtitle">Formulir pendaftaran dan data keluarga (ubah)</p>
        </div>

        <form method="POST" action="{{ route('penatua.jemaat.update', $jemaat->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="mb-6">
                <div class="text-red-600 font-semibold">Terjadi kesalahan:</div>
                <ul class="list-disc ml-6 text-red-600">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 text-red-600 font-semibold">{{ session('error') }}</div>
            @endif

            @if(session('success'))
            <div class="mb-6 text-green-600 font-semibold">{{ session('success') }}</div>
            @endif

            <!-- ================= STEPPER ================= -->
            <div class="relative mb-12">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 rounded transform -translate-y-1/2"></div>
                <div id="progress"
                    class="absolute top-1/2 left-0 h-1 bg-blue-600 rounded transition-all transform -translate-y-1/2">
                </div>

                <div
                    class="absolute top-1/2 left-0 w-full transform -translate-y-1/2 z-10 flex justify-between items-center">
                    <div class="step active">1</div>
                    <div class="step">2</div>
                    <div class="step">3</div>
                    <div class="step">✔</div>
                </div>


            </div>

            <!-- ================= SLIDER ================= -->
            <div id="stepsWrapper" class="overflow-hidden">
                <div id="steps" class="flex transition-all duration-700 ease-in-out">

                    <!-- ===== STEP 1 ===== -->
                    <div class="step-panel">
                        <div class="card-soft">
                            <h2 class="title">Data Keluarga</h2>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label">Nomor Registrasi <span class="required">*</span></label>
                                    <input name="nomor_registrasi" class="input-soft" placeholder="Nomor Registrasi"
                                        value="{{ old('nomor_registrasi', optional($keluarga)->nomor_registrasi) }}">
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Registrasi <span class="required">*</span></label>
                                    <input name="tanggal_registrasi" type="date" class="input-soft"
                                        value="{{ old('tanggal_registrasi', optional($keluarga)->tanggal_registrasi ? \Illuminate\Support\Carbon::parse(optional($keluarga)->tanggal_registrasi)->format('Y-m-d') : '') }}">
                                </div>
                            </div>



                            <div class="mt-4">
                                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                                <textarea name="alamat" class="input-soft h-28"
                                    placeholder="Alamat Lengkap">{{ old('alamat', optional($keluarga)->alamat) }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="form-label">Wijk</label>
                                    @php
                                    $displayWijkName = optional($penatuaWijk)->nama_wijk ??
                                    optional($keluarga->wijk)->nama_wijk ?? '';
                                    $displayWijkId = optional($penatuaWijk)->id ?? optional($keluarga)->wijk_id ?? '';
                                    @endphp
                                    <input id="wijk_display" type="text" class="input-soft"
                                        value="{{ $displayWijkName }}" readonly>
                                    <input type="hidden" name="wijk_id" value="{{ old('wijk_id', $displayWijkId) }}">
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Pernikahan</label>
                                    <input name="tanggal_pernikahan" type="date" class="input-soft"
                                        value="{{ old('tanggal_pernikahan', optional($keluarga)->tanggal_pernikahan ? \Illuminate\Support\Carbon::parse(optional($keluarga)->tanggal_pernikahan)->format('Y-m-d') : '') }}">
                                </div>
                                <div>
                                    <label class="form-label">Gereja Pemberkatan</label>
                                    <input name="gereja_pemberkatan" class="input-soft" placeholder="Gereja Pemberkatan"
                                        value="{{ old('gereja_pemberkatan', optional($keluarga)->gereja_pemberkatan) }}">
                                </div>
                                <div>
                                    <label class="form-label">Pendeta Pemberkatan</label>
                                    <input name="pendeta_pemberkatan" class="input-soft"
                                        placeholder="Pendeta Pemberkatan"
                                        value="{{ old('pendeta_pemberkatan', optional($keluarga)->pendeta_pemberkatan) }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label">Akte Pernikahan</label>
                                @if(optional($keluarga)->akte_pernikahan)
                                <div class="mb-2"><a href="{{ asset('storage/'.optional($keluarga)->akte_pernikahan) }}"
                                        target="_blank" class="text-blue-600">Lihat akte saat ini</a></div>
                                @endif
                                <input type="file" name="akte_pernikahan" class="input-soft">
                            </div>
                        </div>
                    </div>

                    <!-- ===== STEP 2 ===== -->
                    <div class="step-panel">
                        <div class="card-soft">
                            <h2 class="title">Data Kepala Keluarga</h2>

                            <div class="scroll-form">
                                <!-- Suami -->
                                <div class="sub-section mb-6">
                                    <h3 class="subtitle">Kepala Keluarga</h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="form-label">Nama <span class="required">*</span></label>
                                            <input class="input-soft" name="suami_nama" placeholder="Nama Lengkap"
                                                value="{{ old('suami_nama', optional($kepala)->nama ?? $jemaat->nama) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Jenis Kelamin <span
                                                    class="required">*</span></label>
                                            <select class="input-soft" name="suami_jenis_kelamin">
                                                <option value="">-- Pilih --</option>
                                                <option value="L" {{ old('suami_jenis_kelamin', optional($kepala)->
                                                    jenis_kelamin ?? $jemaat->jenis_kelamin)=='L' ? 'selected' : ''
                                                    }}>Laki-laki</option>
                                                <option value="P" {{ old('suami_jenis_kelamin', optional($kepala)->
                                                    jenis_kelamin ?? $jemaat->jenis_kelamin)=='P' ? 'selected' : ''
                                                    }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="form-label">Tempat Lahir <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="suami_tempat_lahir"
                                                placeholder="Tempat Lahir"
                                                value="{{ old('suami_tempat_lahir', optional($kepala)->tempat_lahir ?? $jemaat->tempat_lahir) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir <span
                                                    class="required">*</span></label>
                                            <input type="date" class="input-soft" name="suami_tanggal_lahir"
                                                value="{{ old('suami_tanggal_lahir', optional($kepala)->tanggal_lahir ? \Illuminate\Support\Carbon::parse(optional($kepala)->tanggal_lahir)->format('Y-m-d') : ($jemaat->tanggal_lahir ? \Illuminate\Support\Carbon::parse($jemaat->tanggal_lahir)->format('Y-m-d') : '')) }}">
                                        </div>

                                        <div>
                                            <label class="form-label">No. Telepon <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="suami_no_telp" placeholder="No Telepon"
                                                value="{{ old('suami_no_telp', optional($kepala)->no_telp ?? $jemaat->no_telp) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Hubungan Keluarga <span
                                                    class="required">*</span></label>
                                            @php
                                                $suamiDefault = old('suami_hubungan');
                                                if (!$suamiDefault) {
                                                    $hubungan = optional($kepala)->hubungan_keluarga ?? $jemaat->hubungan_keluarga ?? null;
                                                    if ($hubungan === 'Kepala Keluarga') {
                                                        $gender = optional($kepala)->jenis_kelamin ?? $jemaat->jenis_kelamin ?? null;
                                                        $suamiDefault = ($gender === 'P') ? 'Istri' : 'Suami';
                                                    } else {
                                                        $suamiDefault = $hubungan;
                                                    }
                                                }
                                            @endphp
                                            <select class="input-soft" name="suami_hubungan">
                                                <option value="">-- Pilih --</option>
                                                <option value="Suami" {{ $suamiDefault=='Suami' ? 'selected' : '' }}>Suami</option>
                                                <option value="Istri" {{ $suamiDefault=='Istri' ? 'selected' : '' }}>Istri</option>
                                                <option value="Anak" {{ $suamiDefault=='Anak' ? 'selected' : '' }}>Anak</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Foto</label>
                                            @if(optional($kepala)->foto || $jemaat->foto)
                                            <div class="mb-2"><img
                                                    src="{{ asset('storage/'.(optional($kepala)->foto ?? $jemaat->foto)) }}"
                                                    alt="foto" class="w-28 h-28 object-cover rounded"></div>
                                            @endif
                                            <input type="file" class="input-soft" name="suami_foto">
                                        </div>

                                        <div class="col-span-2">
                                            <h3 class="subtitle">Data Administrasi</h3>
                                        </div>

                                        <div class="col-span-2 grid grid-cols-2 gap-6">
                                            <div>
                                                <label class="form-label">Tanggal Sidi <span
                                                        class="required">*</span></label>
                                                <input type="date" class="input-soft" name="suami_tanggal_sidi"
                                                    value="{{ old('suami_tanggal_sidi', optional($kepala)->tanggal_sidi ? \Illuminate\Support\Carbon::parse(optional($kepala)->tanggal_sidi)->format('Y-m-d') : ($jemaat->tanggal_sidi ? \Illuminate\Support\Carbon::parse($jemaat->tanggal_sidi)->format('Y-m-d') : '')) }}">
                                            </div>
                                            <div>
                                                <label class="form-label">File Sidi</label>
                                                @if(optional($kepala)->file_sidi || $jemaat->file_sidi)
                                                <div class="mb-2"><a
                                                        href="{{ asset('storage/'.(optional($kepala)->file_sidi ?? $jemaat->file_sidi)) }}"
                                                        target="_blank" class="text-blue-600">Lihat file sidi saat
                                                        ini</a></div>
                                                @endif
                                                <input type="file" class="input-soft" name="suami_file_sidi">
                                            </div>

                                            <div>
                                                <label class="form-label">Tanggal Baptis <span
                                                        class="required">*</span></label>
                                                <input type="date" class="input-soft" name="suami_tanggal_baptis"
                                                    value="{{ old('suami_tanggal_baptis', optional($kepala)->tanggal_baptis ? \Illuminate\Support\Carbon::parse(optional($kepala)->tanggal_baptis)->format('Y-m-d') : ($jemaat->tanggal_baptis ? \Illuminate\Support\Carbon::parse($jemaat->tanggal_baptis)->format('Y-m-d') : '')) }}">
                                            </div>
                                            <div>
                                                <label class="form-label">File Baptis</label>
                                                @if(optional($kepala)->file_baptis || $jemaat->file_baptis)
                                                <div class="mb-2"><a
                                                        href="{{ asset('storage/'.(optional($kepala)->file_baptis ?? $jemaat->file_baptis)) }}"
                                                        target="_blank" class="text-blue-600">Lihat file baptis saat
                                                        ini</a></div>
                                                @endif
                                                <input type="file" class="input-soft" name="suami_file_baptis">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- ===== STEP 3 ===== -->
                    <div class="step-panel">
                        <div class="card-soft">
                            <h2 class="title">Anggota Keluarga</h2>

                            <div id="anggota-wrapper">
                                @unless(old('anggota_nama'))
                                @foreach($anggota as $a)
                                <div class="anggota-card mb-4 p-4 rounded-lg border">
                                    <input type="hidden" name="anggota_id[]" value="{{ $a->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold">Anggota</h4>
                                        <button type="button" class="btn-remove text-sm text-red-600"
                                            onclick="this.closest('.anggota-card').remove(); setTimeout(update,0)">Hapus</button>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label">Nama Anggota <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="anggota_nama[]" placeholder="Nama Anggota"
                                                value="{{ old('anggota_nama[]', $a->nama) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Jenis Kelamin <span
                                                    class="required">*</span></label>
                                            <select class="input-soft" name="anggota_jenis_kelamin[]">
                                                <option value="">-- Pilih --</option>
                                                <option value="L" {{ ($a->jenis_kelamin=='L') ? 'selected' : ''
                                                    }}>Laki-laki</option>
                                                <option value="P" {{ ($a->jenis_kelamin=='P') ? 'selected' : ''
                                                    }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Hubungan Keluarga <span
                                                    class="required">*</span></label>
                                            <select class="input-soft" name="anggota_hubungan[]">
                                                <option value="">-- Pilih --</option>
                                                <option value="Istri" {{ ($a->hubungan_keluarga=='Istri') ? 'selected' :
                                                    '' }}>
                                                    Istri</option>
                                                <option value="Anak" {{ ($a->hubungan_keluarga=='Anak') ? 'selected' :
                                                    '' }}>
                                                    Anak</option>
                                                <option value="Tanggungan" {{ ($a->hubungan_keluarga=='Tanggungan') ?
                                                    'selected' : '' }}>
                                                    Tanggungan</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="form-label">Tempat Lahir <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="anggota_tempat_lahir[]"
                                                placeholder="Tempat Lahir"
                                                value="{{ old('anggota_tempat_lahir[]', $a->tempat_lahir) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir <span
                                                    class="required">*</span></label>
                                            <input type="date" class="input-soft" name="anggota_tanggal_lahir[]"
                                                value="{{ old('anggota_tanggal_lahir[]', $a->tanggal_lahir ? \Illuminate\Support\Carbon::parse($a->tanggal_lahir)->format('Y-m-d') : '') }}">
                                        </div>

                                        <div>
                                            <label class="form-label">No. Telepon </label>
                                            <input class="input-soft" name="anggota_no_telp[]" placeholder="No Telepon"
                                                value="{{ old('anggota_no_telp[]', $a->no_telp) }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Foto</label>
                                            @if($a->foto)
                                            <div class="mb-2"><img src="{{ asset('storage/'.$a->foto) }}" alt="foto"
                                                    class="w-28 h-28 object-cover rounded"></div>
                                            @endif
                                            <input type="file" class="input-soft" name="anggota_foto[]">
                                        </div>

                                        <div>
                                            <label class="form-label">Tanggal Sidi</label>
                                            <input type="date" class="input-soft" name="anggota_tanggal_sidi[]"
                                                value="{{ old('anggota_tanggal_sidi[]', $a->tanggal_sidi ? \Illuminate\Support\Carbon::parse($a->tanggal_sidi)->format('Y-m-d') : '') }}">
                                        </div>
                                        <div>
                                            <label class="form-label">File Sidi</label>
                                            @if($a->file_sidi)
                                            <div class="mb-2"><a href="{{ asset('storage/'.$a->file_sidi) }}"
                                                    target="_blank" class="text-blue-600">Lihat file sidi</a></div>
                                            @endif
                                            <input type="file" class="input-soft" name="anggota_file_sidi[]">
                                        </div>

                                        <div>
                                            <label class="form-label">Tanggal Baptis</label>
                                            <input type="date" class="input-soft" name="anggota_tanggal_baptis[]"
                                                value="{{ old('anggota_tanggal_baptis[]', $a->tanggal_baptis ? \Illuminate\Support\Carbon::parse($a->tanggal_baptis)->format('Y-m-d') : '') }}">
                                        </div>
                                        <div>
                                            <label class="form-label">File Baptis</label>
                                            @if($a->file_baptis)
                                            <div class="mb-2"><a href="{{ asset('storage/'.$a->file_baptis) }}"
                                                    target="_blank" class="text-blue-600">Lihat file baptis</a></div>
                                            @endif
                                            <input type="file" class="input-soft" name="anggota_file_baptis[]">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endunless
                            </div>

                            <button type="button" onclick="tambahAnggota()" class="btn-add mt-4">
                                + Tambah Anggota
                            </button>
                        </div>
                    </div>

                    <!-- ===== STEP 4 ===== -->
                    <div class="step-panel">
                        <div class="card-soft text-left">
                            <h2 class="title">Konfirmasi</h2>
                            <p class="text-gray-600">Pastikan semua data sudah benar</p>

                            <div id="summary" class="mt-6 grid gap-6">
                                <div class="summary-card p-4 bg-white rounded border">
                                    <h3 class="subtitle">Data Keluarga</h3>
                                    <div id="summary-keluarga" class="text-sm text-gray-700 mt-2"></div>
                                </div>

                                <div class="summary-card p-4 bg-white rounded border">
                                    <h3 class="subtitle">Data Kepala Keluarga</h3>
                                    <div id="summary-kepala" class="text-sm text-gray-700 mt-2"></div>
                                </div>

                                <div class="summary-card p-4 bg-white rounded border">
                                    <h3 class="subtitle">Anggota Keluarga</h3>
                                    <div id="summary-anggota" class="text-sm text-gray-700 mt-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- ================= NAV ================= -->
            <div class="flex justify-between mt-10">
                <button id="prevBtn" type="button" onclick="prevStep()" class="btn-secondary">Kembali</button>
                <button id="nextBtn" type="button" onclick="nextStep()" class="btn-primary">Selanjutnya</button>
            </div>

        </form>

    </div>
</div>

<style>
    /* reuse create styles (trimmed to essential) */
    .card-soft {
        background: radial-gradient(circle at top left, #dbeafe, #eff6ff, #ffffff);
        border-radius: 28px;
        padding: 48px;
        border: 1px solid rgba(59, 130, 246, .18);
        box-shadow: 0 30px 80px rgba(30, 64, 175, .18);
    }

    .step-panel {
        width: 100%;
        flex-shrink: 0;
        padding: 4px;
    }

    .title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 20px;
    }

    .input-soft {
        width: 100%;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, .35);
        background: #fff;
        margin-bottom: 14px;
        box-shadow: 0 6px 18px rgba(30, 64, 175, .1);
        min-height: 48px;
        display: block;
    }

    .input-soft:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .15);
    }

    .page-header {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 2.25rem;
        font-weight: 800;
        color: #b5892a;
        letter-spacing: 1px;
        margin: 0 0 6px 0;
        text-shadow: 0 6px 18px rgba(181, 137, 42, 0.18);
    }

    .page-subtitle {
        color: #6b7280;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 2px;
        margin-top: 4px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }

    .required {
        color: #dc2626;
        margin-left: 8px;
        font-weight: 800;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 700;
    }

    .btn-secondary {
        background: #e5e7eb;
        padding: 12px 24px;
        border-radius: 16px;
    }

    .btn-add {
        background: #1e40af;
        color: white;
        padding: 10px 18px;
        border-radius: 14px;
    }

    /* Summary styles for Step 4 */
    .summary-card {
        box-shadow: none;
        border-color: rgba(148, 163, 184, .12);
        background: #fff;
    }

    .summary-card h3 {
        margin: 0 0 8px 0;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .95rem;
    }

    .summary-table th {
        text-align: left;
        padding: 8px 10px;
        color: #374151;
        width: 220px;
        vertical-align: top;
        background: transparent;
        font-weight: 700
    }

    .summary-table td {
        padding: 8px 10px;
        color: #374151;
    }

    .summary-anggota-wrap {
        overflow: auto;
    }

    .summary-anggota-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .95rem;
        min-width: 720px;
    }

    .summary-anggota-table thead th {
        background: #f8fafc;
        padding: 8px 10px;
        text-align: left;
        color: #374151;
        border-bottom: 1px solid rgba(148, 163, 184, .08);
    }

    .summary-anggota-table tbody td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(148, 163, 184, .06);
        color: #374151
    }

    .summary-anggota-table th.no-col,
    .summary-anggota-table td.no-col {
        width: 48px;
        text-align: center
    }
</style>

<script>
    let step = 0;
const total = 4;

function update(){
    const stepsEl = document.getElementById('steps');
    const progressEl = document.getElementById('progress');
    const wrapperEl = document.getElementById('stepsWrapper');
    const panels = document.querySelectorAll('.step-panel');
    if(stepsEl) stepsEl.style.transform = `translateX(-${step*100}%)`;
    document.querySelectorAll('.step').forEach((el,i)=>{
        el.classList.toggle('active', i <= step);
    });
    if(progressEl) progressEl.style.width = (step/(total-1))*100 + '%';

    if(wrapperEl && panels && panels[step]){
        const active = panels[step];
        const content = active.querySelector('.card-soft') || active;
        window.requestAnimationFrame(()=>{
            const h = content.offsetHeight || content.scrollHeight || active.scrollHeight;
            wrapperEl.style.height = h + 'px';
        });
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if(prevBtn){
        if(step === 0) prevBtn.classList.add('invisible');
        else prevBtn.classList.remove('invisible');
    }
    if(nextBtn){
            if(step === total-1){
                nextBtn.textContent = 'Simpan Data Jemaat';
                nextBtn.setAttribute('type','submit');
                nextBtn.removeAttribute('onclick');
            } else {
                nextBtn.textContent = 'Selanjutnya';
                nextBtn.setAttribute('type','button');
                nextBtn.setAttribute('onclick','nextStep()');
            }
    }

    if(step === total-1){
        if(typeof populateSummary === 'function') populateSummary();
    }
}

function nextStep(){ if(step < total-1){ step++; setTimeout(update,0); } }
function prevStep(){ if(step > 0){ step--; update(); }}

function populateSummary(){
    function text(n){ const el = document.querySelector(`[name="${n}"]`); if(!el) return ''; if(el.type === 'file') return (el.files && el.files[0])? el.files[0].name : '' ; return el.value || ''; }
    const akte = document.querySelector('[name="akte_pernikahan"]');
    const wijkSelect = document.querySelector('[name="wijk_id"]');
    const wijkText = (wijkSelect && wijkSelect.options && wijkSelect.selectedIndex >= 0) ? wijkSelect.options[wijkSelect.selectedIndex].text : '';
    const keluargaHtml = `
            <table class="summary-table">
                <tr><th>Nomor Registrasi</th><td>${escapeHtml(text('nomor_registrasi')) || '-'}</td></tr>
                <tr><th>Tanggal Registrasi</th><td>${escapeHtml(text('tanggal_registrasi')) || '-'}</td></tr>
                <tr><th>Alamat Lengkap</th><td>${escapeHtml(text('alamat')) || '-'}</td></tr>
                <tr><th>Wijk</th><td>${escapeHtml(wijkText) || '-'}</td></tr>
                <tr><th>Tanggal Pernikahan</th><td>${escapeHtml(text('tanggal_pernikahan')) || '-'}</td></tr>
                <tr><th>Gereja Pemberkatan</th><td>${escapeHtml(text('gereja_pemberkatan')) || '-'}</td></tr>
                <tr><th>Pendeta Pemberkatan</th><td>${escapeHtml(text('pendeta_pemberkatan')) || '-'}</td></tr>
                <tr><th>Akte Pernikahan</th><td>${akte && akte.files && akte.files[0] ? escapeHtml(akte.files[0].name) : '-'}</td></tr>
            </table>`;
    document.getElementById('summary-keluarga').innerHTML = keluargaHtml;

    const suamiFoto = document.querySelector('[name="suami_foto"]');
    const kepalaHtml = `
            <table class="summary-table">
                <tr><th>Nama</th><td>${escapeHtml(text('suami_nama')) || '-'}</td></tr>
                <tr><th>Jenis Kelamin</th><td>${escapeHtml(text('suami_jenis_kelamin')) || '-'}</td></tr>
                <tr><th>Tempat Lahir</th><td>${escapeHtml(text('suami_tempat_lahir')) || '-'}</td></tr>
                <tr><th>Tanggal Lahir</th><td>${escapeHtml(text('suami_tanggal_lahir')) || '-'}</td></tr>
                <tr><th>No. Telepon</th><td>${escapeHtml(text('suami_no_telp')) || '-'}</td></tr>
                <tr><th>Hubungan</th><td>${escapeHtml(text('suami_hubungan')) || '-'}</td></tr>
                <tr><th>Foto</th><td>${suamiFoto && suamiFoto.files && suamiFoto.files[0] ? escapeHtml(suamiFoto.files[0].name) : '-'}</td></tr>
                <tr><th>Tanggal Sidi</th><td>${escapeHtml(text('suami_tanggal_sidi')) || '-'}</td></tr>
                <tr><th>File Sidi</th><td>${(document.querySelector('[name="suami_file_sidi"]') && document.querySelector('[name="suami_file_sidi"]').files[0]) ? escapeHtml(document.querySelector('[name="suami_file_sidi"]').files[0].name) : '-'}</td></tr>
                <tr><th>Tanggal Baptis</th><td>${escapeHtml(text('suami_tanggal_baptis')) || '-'}</td></tr>
                <tr><th>File Baptis</th><td>${(document.querySelector('[name="suami_file_baptis"]') && document.querySelector('[name="suami_file_baptis"]').files[0]) ? escapeHtml(document.querySelector('[name="suami_file_baptis"]').files[0].name) : '-'}</td></tr>
            </table>`;
    document.getElementById('summary-kepala').innerHTML = kepalaHtml;

    const anggotaNames = Array.from(document.querySelectorAll('[name="anggota_nama[]"]')).map(i=>i.value);
    if(anggotaNames.length === 0){
        document.getElementById('summary-anggota').innerHTML = '<div>- Tidak ada anggota -</div>';
    } else {
        let rows = `<table class="summary-table summary-anggota-table"><thead><tr><th>No</th><th>Nama</th><th>Jenis</th><th>Hubungan</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>No. Telp</th></tr></thead><tbody>`;
        for(let i=0;i<anggotaNames.length;i++){
            const nama = escapeHtml(anggotaNames[i]||'-');
            const jenis = escapeHtml((document.querySelectorAll('[name="anggota_jenis_kelamin[]"]')[i]||{}).value||'-');
            const hub = escapeHtml((document.querySelectorAll('[name="anggota_hubungan[]"]')[i]||{}).value||'-');
            const tempat = escapeHtml((document.querySelectorAll('[name="anggota_tempat_lahir[]"]')[i]||{}).value||'-');
            const tanggal = escapeHtml((document.querySelectorAll('[name="anggota_tanggal_lahir[]"]')[i]||{}).value||'-');
            const notelp = escapeHtml((document.querySelectorAll('[name="anggota_no_telp[]"]')[i]||{}).value||'-');
            rows += `<tr><td>${i+1}</td><td>${nama}</td><td>${jenis}</td><td>${hub}</td><td>${tempat}</td><td>${tanggal}</td><td>${notelp}</td></tr>`;
        }
        rows += `</tbody></table>`;
        document.getElementById('summary-anggota').innerHTML = rows;
    }
}

function tambahAnggota(){
    const wrapper = document.getElementById('anggota-wrapper');
    const idx = wrapper.querySelectorAll('.anggota-card').length;
    wrapper.insertAdjacentHTML('beforeend', `
        <div class="anggota-card mb-4 p-4 rounded-lg border">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold">Anggota #${idx+1}</h4>
                <button type="button" class="btn-remove text-sm text-red-600" onclick="this.closest('.anggota-card').remove(); setTimeout(update,0)">Hapus</button>
            </div>

                <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Anggota <span class="required">*</span></label>
                    <input class="input-soft" name="anggota_nama[]" placeholder="Nama Anggota">
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select class="input-soft" name="anggota_jenis_kelamin[]">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Hubungan Keluarga <span class="required">*</span></label>
                    <select class="input-soft" name="anggota_hubungan[]">
                        <option value="">-- Pilih --</option>
                        <option value="istri">Istri</option>
                        <option value="anak">Anak</option>
                        <option value="tanggungan">Tanggungan</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                    <input class="input-soft" name="anggota_tempat_lahir[]" placeholder="Tempat Lahir">
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" class="input-soft" name="anggota_tanggal_lahir[]">
                </div>

                <div>
                    <label class="form-label">No. Telepon </label>
                    <input class="input-soft" name="anggota_no_telp[]" placeholder="No Telepon">
                </div>
                <div>
                    <label class="form-label">Foto</label>
                    <input type="file" class="input-soft" name="anggota_foto[]">
                </div>

                <div>
                    <label class="form-label">Tanggal Sidi</label>
                    <input type="date" class="input-soft" name="anggota_tanggal_sidi[]">
                </div>
                <div>
                    <label class="form-label">File Sidi</label>
                    <input type="file" class="input-soft" name="anggota_file_sidi[]">
                </div>

                <div>
                    <label class="form-label">Tanggal Baptis</label>
                    <input type="date" class="input-soft" name="anggota_tanggal_baptis[]">
                </div>
                <div>
                    <label class="form-label">File Baptis</label>
                    <input type="file" class="input-soft" name="anggota_file_baptis[]">
                </div>
            </div>
        </div>
    `);
    setTimeout(update, 0);
}

function tambahAnggotaWithData(d){
    const wrapper = document.getElementById('anggota-wrapper');
    const idx = wrapper.querySelectorAll('.anggota-card').length;
    const html = `
        <div class="anggota-card mb-4 p-4 rounded-lg border">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold">Anggota #${idx+1}</h4>
                <button type="button" class="btn-remove text-sm text-red-600" onclick="this.closest('.anggota-card').remove(); setTimeout(update,0)">Hapus</button>
            </div>

                <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Anggota <span class="required">*</span></label>
                    <input class="input-soft" name="anggota_nama[]" placeholder="Nama Anggota" value="${escapeHtml(d.nama)}">
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select class="input-soft" name="anggota_jenis_kelamin[]">
                        <option value="">-- Pilih --</option>
                        <option value="L" ${d.jenis == 'L' ? 'selected' : ''}>Laki-laki</option>
                        <option value="P" ${d.jenis == 'P' ? 'selected' : ''}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Hubungan Keluarga <span class="required">*</span></label>
                    <select class="input-soft" name="anggota_hubungan[]">
                        <option value="">-- Pilih --</option>
                        <option value="istri" ${d.hubungan == 'Istri' || d.hubungan == 'istri' ? 'selected' : ''}>Istri</option>
                        <option value="anak" ${d.hubungan == 'Anak' || d.hubungan == 'anak' ? 'selected' : ''}>Anak</option>
                        <option value="tanggungan" ${d.hubungan == 'Tanggungan' || d.hubungan == 'tanggungan' ? 'selected' : ''}>Tanggungan</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                    <input class="input-soft" name="anggota_tempat_lahir[]" placeholder="Tempat Lahir" value="${escapeHtml(d.tempat)}">
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" class="input-soft" name="anggota_tanggal_lahir[]" value="${escapeHtml(d.tanggal)}">
                </div>

                <div>
                    <label class="form-label">No. Telepon </label>
                    <input class="input-soft" name="anggota_no_telp[]" placeholder="No Telepon" value="${escapeHtml(d.no_telp)}">
                </div>
                <div>
                    <label class="form-label">Foto</label>
                    <input type="file" class="input-soft" name="anggota_foto[]">
                </div>

                <div>
                    <label class="form-label">Tanggal Sidi</label>
                    <input type="date" class="input-soft" name="anggota_tanggal_sidi[]" value="${escapeHtml(d.tanggal_sidi)}">
                </div>
                <div>
                    <label class="form-label">File Sidi</label>
                    <input type="file" class="input-soft" name="anggota_file_sidi[]">
                </div>

                <div>
                    <label class="form-label">Tanggal Baptis</label>
                    <input type="date" class="input-soft" name="anggota_tanggal_baptis[]" value="${escapeHtml(d.tanggal_baptis)}">
                </div>
                <div>
                    <label class="form-label">File Baptis</label>
                    <input type="file" class="input-soft" name="anggota_file_baptis[]">
                </div>
            </div>
        </div>
    `;
    wrapper.insertAdjacentHTML('beforeend', html);
    setTimeout(update, 0);
}

document.addEventListener('DOMContentLoaded', ()=>{
    // populate existing anggota from server-side data
    const existing = {!! json_encode($anggota->map(function($a){ return [
        'nama' => $a->nama,
        'jenis' => $a->jenis_kelamin,
        'hubungan' => $a->hubungan_keluarga,
        'tempat' => $a->tempat_lahir,
        'tanggal' => $a->tanggal_lahir ? \Illuminate\Support\Carbon::parse($a->tanggal_lahir)->format('Y-m-d') : null,
        'no_telp' => $a->no_telp,
        'tanggal_sidi' => $a->tanggal_sidi ? \Illuminate\Support\Carbon::parse($a->tanggal_sidi)->format('Y-m-d') : null,
        'tanggal_baptis' => $a->tanggal_baptis ? \Illuminate\Support\Carbon::parse($a->tanggal_baptis)->format('Y-m-d') : null,
    ]; })) !!};
    if(Array.isArray(existing) && existing.length){
        existing.forEach(e=>{ tambahAnggotaWithData(e); });
    }

    // repopulate old input if validation failed (merge behavior)
    const oldNama = {!! json_encode(old('anggota_nama', [])) !!};
    if(Array.isArray(oldNama) && oldNama.length){
        // if we also loaded existing, prefer old values by clearing and using old
        document.getElementById('anggota-wrapper').innerHTML = '';
        const oldJenis = {!! json_encode(old('anggota_jenis_kelamin', [])) !!};
        const oldHub = {!! json_encode(old('anggota_hubungan', [])) !!};
        const oldTempat = {!! json_encode(old('anggota_tempat_lahir', [])) !!};
        const oldTanggal = {!! json_encode(old('anggota_tanggal_lahir', [])) !!};
        const oldNo = {!! json_encode(old('anggota_no_telp', [])) !!};
        for(let i=0;i<oldNama.length;i++){
            tambahAnggotaWithData({ nama: oldNama[i] || '', jenis: oldJenis[i] || '', hubungan: oldHub[i] || '', tempat: oldTempat[i] || '', tanggal: oldTanggal[i] || '', no_telp: oldNo[i] || '', tanggal_sidi:'', tanggal_baptis: '' });
        }
    }

    const formEl = document.querySelector('form');
    if(formEl){
        const onChange = ()=>{ if(step === total-1 && typeof populateSummary === 'function') populateSummary(); };
        formEl.addEventListener('input', onChange, true);
        formEl.addEventListener('change', onChange, true);
    }

    if(step === total-1 && typeof populateSummary === 'function') populateSummary();
});

function escapeHtml(s){ if(s === null || s === undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

update();
</script>

@endsection