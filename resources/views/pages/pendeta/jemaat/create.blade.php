@extends('layouts.main')
@section('title','Tambah Jemaat')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <div class="canvas-wrapper bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

        <div class="mb-8">
            <h1 class="page-header">Tambah Data Jemaat</h1>
            <p class="page-subtitle">Formulir pendaftaran dan data keluarga</p>
        </div>

        <form method="POST" action="{{ route('pendeta.jemaat.store') }}" enctype="multipart/form-data">
            @csrf

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
                                        value="{{ old('nomor_registrasi') }}">
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Registrasi <span class="required">*</span></label>
                                    <input name="tanggal_registrasi" type="date" class="input-soft"
                                        value="{{ old('tanggal_registrasi') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                                <textarea name="alamat" class="input-soft h-28"
                                    placeholder="Alamat Lengkap">{{ old('alamat') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="form-label">Wijk <span class="required">*</span></label>
                                    <select name="wijk_id" class="input-soft">
                                        <option value="">-- Pilih Wijk --</option>
                                        @foreach($wijks as $w)
                                        <option value="{{ $w->id }}" {{ old('wijk_id')==$w->id ? 'selected' : '' }}>{{
                                            $w->nama_wijk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Pernikahan</label>
                                    <input name="tanggal_pernikahan" type="date" class="input-soft"
                                        value="{{ old('tanggal_pernikahan') }}">
                                </div>
                                <div>
                                    <label class="form-label">Gereja Pemberkatan</label>
                                    <input name="gereja_pemberkatan" class="input-soft" placeholder="Gereja Pemberkatan"
                                        value="{{ old('gereja_pemberkatan') }}">
                                </div>
                                <div>
                                    <label class="form-label">Pendeta Pemberkatan</label>
                                    <input name="pendeta_pemberkatan" class="input-soft"
                                        placeholder="Pendeta Pemberkatan" value="{{ old('pendeta_pemberkatan') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label">Akte Pernikahan</label>
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
                                                value="{{ old('suami_nama') }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Jenis Kelamin <span
                                                    class="required">*</span></label>
                                            <select class="input-soft" name="suami_jenis_kelamin">
                                                <option value="">-- Pilih --</option>
                                                <option value="L" {{ old('suami_jenis_kelamin')=='L' ? 'selected' : ''
                                                    }}>Laki-laki</option>
                                                <option value="P" {{ old('suami_jenis_kelamin')=='P' ? 'selected' : ''
                                                    }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="form-label">Tempat Lahir <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="suami_tempat_lahir"
                                                placeholder="Tempat Lahir" value="{{ old('suami_tempat_lahir') }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir <span
                                                    class="required">*</span></label>
                                            <input type="date" class="input-soft" name="suami_tanggal_lahir"
                                                value="{{ old('suami_tanggal_lahir') }}">
                                        </div>

                                        <div>
                                            <label class="form-label">No. Telepon <span
                                                    class="required">*</span></label>
                                            <input class="input-soft" name="suami_no_telp" placeholder="No Telepon"
                                                value="{{ old('suami_no_telp') }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Hubungan Keluarga <span
                                                    class="required">*</span></label>
                                            <select class="input-soft" name="suami_hubungan">
                                                <option value="">-- Pilih --</option>
                                                <option value="Suami" {{ old('suami_hubungan')=='Suami' ? 'selected' : ''
                                                    }}>Suami</option>
                                                <option value="Istri" {{ old('suami_hubungan')=='Istri' ? 'selected' : ''
                                                    }}>Istri</option>
                                                <option value="Anak" {{ old('suami_hubungan')=='Anak' ? 'selected' : ''
                                                    }}>Anak</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Foto</label>
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
                                                    value="{{ old('suami_tanggal_sidi') }}">
                                            </div>
                                            <div>
                                                <label class="form-label">File Sidi</label>
                                                <input type="file" class="input-soft" name="suami_file_sidi">
                                            </div>

                                            <div>
                                                <label class="form-label">Tanggal Baptis <span
                                                        class="required">*</span></label>
                                                <input type="date" class="input-soft" name="suami_tanggal_baptis"
                                                    value="{{ old('suami_tanggal_baptis') }}">
                                            </div>
                                            <div>
                                                <label class="form-label">File Baptis</label>
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

                            <div id="anggota-wrapper"></div>

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

                            <div id="summary" class="mt-6 space-y-6">
                                <div>
                                    <h3 class="subtitle">Data Keluarga</h3>
                                    <div id="summary-keluarga" class="text-sm text-gray-700"></div>
                                </div>

                                <div>
                                    <h3 class="subtitle">Data Kepala Keluarga</h3>
                                    <div id="summary-kepala" class="text-sm text-gray-700"></div>
                                </div>

                                <div>
                                    <h3 class="subtitle">Anggota Keluarga</h3>
                                    <div id="summary-anggota" class="text-sm text-gray-700"></div>
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
    /* CARD WARNA SESUAI GAMBAR */
    .card-soft {
        background: radial-gradient(circle at top left, #dbeafe, #eff6ff, #ffffff);
        border-radius: 20px;
        padding: 28px;
        border: 1px solid rgba(59, 130, 246, .12);
        box-shadow: 0 18px 48px rgba(30, 64, 175, .12);
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

    /* Normalize specific input types to match other fields */
    input[type="file"].input-soft {
        padding: 8px 12px;
        height: 48px;
        min-height: 48px;
        line-height: 1.2;
    }

    input[type="date"].input-soft,
    input[type="text"].input-soft,
    input[type="tel"].input-soft,
    select.input-soft {
        min-height: 48px;
        height: 48px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    /* Allow textareas to keep their own height utility (h-28) */
    textarea.input-soft {
        min-height: 0;
    }

    .step {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .step.active {
        background: #2563eb;
        color: white;
        box-shadow: 0 8px 24px rgba(37, 99, 235, .4);
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

    /* Page header - luxury look */
    .page-header {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: #b5892a;
        letter-spacing: 0.6px;
        margin: 0 0 6px 0;
        text-shadow: 0 4px 12px rgba(181, 137, 42, 0.12);
    }

    .page-header:after {
        content: "";
        display: block;
        width: 90px;
        height: 6px;
        margin-top: 12px;
        background: linear-gradient(90deg, #ffd27a, #b5892a);
        border-radius: 6px;
        box-shadow: 0 8px 26px rgba(181, 137, 42, 0.18);
    }

    .page-subtitle {
        color: #6b7280;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 2px;
        margin-top: 4px;
    }
</style>

<style>
    /* Canvas wrapper fallback styles (match admin) */
    .canvas-wrapper {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(16, 24, 40, 0.05);
        border: 1px solid rgba(16, 24, 40, 0.03);
    }

    /* Make the previous/navigation secondary button text black */
    #prevBtn { color: #000; font-weight: 700; }

    /* Scrollable inner form for stacked sections */
    .scroll-form { max-height: 600px; overflow-y: auto; padding-right: 8px; }

    /* Animate wrapper height when switching steps so nav stays near active panel */
    #stepsWrapper { transition: height 300ms ease; overflow: hidden; }

    .subtitle { font-size: 1.05rem; font-weight: 800; color: #1f2937; margin-bottom: 10px; }
    .sub-section { padding-bottom: 18px; border-bottom: 1px solid rgba(15, 23, 42, 0.04) }

    .anggota-card { background: #fff; border-radius: 10px; padding: 10px; border: 1px solid rgba(15, 23, 42, 0.04) }
    .btn-remove { background: transparent; border: none; cursor: pointer }

    .summary-table { width:100%; border-collapse:collapse; margin-top:8px }
    .summary-table th { text-align:left; padding:8px 10px; background:#f3f4f6; color:#111827; font-weight:700; border:1px solid rgba(15,23,42,0.06); width:30%; vertical-align:top }
    .summary-table td { padding:8px 10px; border:1px solid rgba(15,23,42,0.04); color:#374151; vertical-align:top }
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
    document.querySelectorAll('.step').forEach((el,i)=>{ el.classList.toggle('active', i <= step); });
    if(progressEl) progressEl.style.width = (step/(total-1))*100 + '%';

    // adjust wrapper height to active panel so nav buttons stay close
    if(wrapperEl && panels && panels[step]){
        const active = panels[step];
        const content = active.querySelector('.card-soft') || active;
        window.requestAnimationFrame(()=>{ const h = content.offsetHeight || content.scrollHeight || active.scrollHeight; wrapperEl.style.height = h + 'px'; });
    }

    // nav buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if(prevBtn){ if(step === 0) prevBtn.classList.add('invisible'); else prevBtn.classList.remove('invisible'); }
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

    if(step === total-1){ if(typeof populateSummary === 'function') populateSummary(); }
}

function nextStep(){ if(step < total-1){ step++; setTimeout(update,0); } }
function prevStep(){ if(step > 0){ step--; update(); } }

function submitForm(){ const form = document.querySelector('form'); if(form){ form.submit(); } }

function populateSummary(){
    function text(n){ const el = document.querySelector(`[name="${n}"]`); if(!el) return ''; if(el.type === 'file') return (el.files && el.files[0])? el.files[0].name : '' ; return el.value || ''; }
    let wijkText = '-'; const wijkSelect = document.querySelector('[name="wijk_id"]'); if(wijkSelect){ const opt = wijkSelect.options[wijkSelect.selectedIndex]; if(opt) wijkText = opt.text; }
    const akte = document.querySelector('[name="akte_pernikahan"]');
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
    const anggotaJenis = Array.from(document.querySelectorAll('[name="anggota_jenis_kelamin[]"]')).map(i=>i.value);
    const anggotaHub = Array.from(document.querySelectorAll('[name="anggota_hubungan[]"]')).map(i=>i.value);
    const anggotaTempat = Array.from(document.querySelectorAll('[name="anggota_tempat_lahir[]"]')).map(i=>i.value);
    const anggotaTanggal = Array.from(document.querySelectorAll('[name="anggota_tanggal_lahir[]"]')).map(i=>i.value);
    const anggotaNo = Array.from(document.querySelectorAll('[name="anggota_no_telp[]"]')).map(i=>i.value);

    if(anggotaNames.length === 0){ document.getElementById('summary-anggota').innerHTML = '<div>- Tidak ada anggota -</div>'; }
    else {
        let rows = `<table class="summary-table summary-anggota-table"><thead><tr><th>No</th><th>Nama</th><th>Jenis</th><th>Hubungan</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>No. Telp</th></tr></thead><tbody>`;
        for(let i=0;i<anggotaNames.length;i++){
            rows += `<tr><td>${i+1}</td><td>${escapeHtml(anggotaNames[i]||'-')}</td><td>${escapeHtml(anggotaJenis[i]||'-')}</td><td>${escapeHtml(anggotaHub[i]||'-')}</td><td>${escapeHtml(anggotaTempat[i]||'-')}</td><td>${escapeHtml(anggotaTanggal[i]||'-')}</td><td>${escapeHtml(anggotaNo[i]||'-')}</td></tr>`;
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

// adjust on resize so wrapper height stays correct
window.addEventListener('resize', ()=>{ clearTimeout(window._stepResizeTimer); window._stepResizeTimer = setTimeout(()=>{ update(); }, 120); });

function escapeHtml(s){ if(s === null || s === undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

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
                        <option value="istri" ${d.hubungan == 'istri' ? 'selected' : ''}>Istri</option>
                        <option value="anak" ${d.hubungan == 'anak' ? 'selected' : ''}>Anak</option>
                        <option value="tanggungan" ${d.hubungan == 'tanggungan' ? 'selected' : ''}>Tanggungan</option>
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
    `;
    wrapper.insertAdjacentHTML('beforeend', html);
    setTimeout(update, 0);
}

// repopulate anggota cards from previous input after validation error
document.addEventListener('DOMContentLoaded', ()=>{
    const oldNama = {!! json_encode(old('anggota_nama', [])) !!};
    if(Array.isArray(oldNama) && oldNama.length){
        const oldJenis = {!! json_encode(old('anggota_jenis_kelamin', [])) !!};
        const oldHub = {!! json_encode(old('anggota_hubungan', [])) !!};
        const oldTempat = {!! json_encode(old('anggota_tempat_lahir', [])) !!};
        const oldTanggal = {!! json_encode(old('anggota_tanggal_lahir', [])) !!};
        const oldNo = {!! json_encode(old('anggota_no_telp', [])) !!};
        for(let i=0;i<oldNama.length;i++){
            tambahAnggotaWithData({
                nama: oldNama[i] || '',
                jenis: oldJenis[i] || '',
                hubungan: oldHub[i] || '',
                tempat: oldTempat[i] || '',
                tanggal: oldTanggal[i] || '',
                no_telp: oldNo[i] || ''
            });
        }
    }

    // live-update the summary when inputs change (helps show summary immediately)
    const formEl = document.querySelector('form');
    if(formEl){
        const onChange = ()=>{ if(step === total-1 && typeof populateSummary === 'function') populateSummary(); };
        formEl.addEventListener('input', onChange, true);
        formEl.addEventListener('change', onChange, true);
    }

    // if we somehow start on the final step, populate immediately
    if(step === total-1 && typeof populateSummary === 'function') populateSummary();
});

update();
</script>

@endsection