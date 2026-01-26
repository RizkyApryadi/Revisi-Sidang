@extends('layouts.main')
@section('title', 'Edit Jemaat')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Jemaat</h4>
                    </div>
                    <div class="card-body bg-white">
                        <form action="{{ route('admin.jemaat.update', $jemaat->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <h4 class="mb-2">Formulir Jemaat Edit</h4>
                            <p>Kami yang bertanda tangan di bawah ini:</p>

                            <div class="form-group mb-3">
                                <label>Nomor KK</label>
                                <input type="text" name="nomor_kk" class="form-control" required value="{{ old('nomor_kk', optional($jemaat->keluarga)->nomor_kk) }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Suami</label>
                                @php
                                    $ayah = optional(optional($jemaat->keluarga)->jemaats)->firstWhere('hubungan_keluarga', 'suami');
                                @endphp
                                <input type="text" name="ayah_nama" id="ayah_nama" class="form-control" value="{{ old('ayah_nama', optional($ayah)->nama) }}">
                                <input type="hidden" name="ayah_jenis_kelamin" id="ayah_jenis_kelamin" value="{{ old('ayah_jenis_kelamin', optional($ayah)->jenis_kelamin) }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Istri</label>
                                @php
                                    $ibu = optional(optional($jemaat->keluarga)->jemaats)->firstWhere('hubungan_keluarga', 'istri');
                                @endphp
                                <input type="text" name="ibu_nama" id="ibu_nama" class="form-control" value="{{ old('ibu_nama', optional($ibu)->nama) }}">
                                <input type="hidden" name="ibu_jenis_kelamin" id="ibu_jenis_kelamin" value="{{ old('ibu_jenis_kelamin', optional($ibu)->jenis_kelamin) }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>No HP Ayah</label>
                                        <input type="text" name="ayah_no_hp" class="form-control" value="{{ old('ayah_no_hp', optional($ayah)->nomor_hp ?? '') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>No HP Ibu</label>
                                        <input type="text" name="ibu_no_hp" class="form-control" value="{{ old('ibu_no_hp', optional($ibu)->nomor_hp ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Email Ayah</label>
                                        <input type="email" name="ayah_email" class="form-control" value="{{ old('ayah_email', optional($ayah)->email ?? '') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Email Ibu</label>
                                        <input type="email" name="ibu_email" class="form-control" value="{{ old('ibu_email', optional($ibu)->email ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Wijk</label>
                                <select name="wijk_id" class="form-control">
                                    <option value="">-- Pilih Wijk --</option>
                                    @foreach($wijks as $wijk)
                                    <option value="{{ $wijk->id }}" {{ old('wijk_id', optional($jemaat->keluarga)->wijk_id ?? $jemaat->wijk_id) == $wijk->id ? 'selected' : '' }}>{{ $wijk->nama_wijk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', optional($jemaat->keluarga)->alamat ?? $jemaat->alamat ?? '') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Asal Gereja</label>
                                <input type="text" name="asal_gereja" class="form-control" value="{{ old('asal_gereja', $jemaat->asal_gereja ?? '') }}">
                            </div>




                            {{-- ================= DATA ANAK ================= --}}
                            <h5 class="mt-4 mb-3 border-bottom pb-2 d-flex justify-content-between">
                                Daftar Anggota Keluarga
                                <button type="button" class="btn btn-sm btn-success" onclick="addAnak()">+
                                    Anggota</button>
                            </h5>

                            <div id="anak-wrapper">
                                @php
                                    $anakItems = optional(optional($jemaat->keluarga)->jemaats)->where('hubungan_keluarga','anak') ?? collect();
                                @endphp

                                @if($anakItems->isNotEmpty())
                                    @foreach($anakItems as $i => $anakModel)
                                        <div class="anak-item border rounded p-3 mb-3" data-index="{{ $i }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="anak-number">{{ $i + 1 }}. Anggota Keluarga</strong>
                                                <div>
                                                    <label class="me-2">
                                                        <input type="checkbox" name="anak[{{ $i }}][is_baptis]" onchange="toggleBaptis(this)" {{ old("anak.$i.is_baptis", (!empty($anakModel->tanggal_baptis) || !empty($anakModel->akte_baptis)) ? 'on' : null) ? 'checked' : '' }}> Baptis
                                                    </label>
                                                    <label class="me-2">
                                                        <input type="checkbox" name="anak[{{ $i }}][is_sidi]" onchange="toggleSidi(this)" {{ old("anak.$i.is_sidi", (!empty($anakModel->tanggal_sidi) || !empty($anakModel->akte_sidi)) ? 'on' : null) ? 'checked' : '' }}> Sidi
                                                    </label>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeAnak(this)">Hapus</button>
                                                </div>
                                            </div>

                                            <input type="text" name="anak[{{ $i }}][nama]" class="form-control mt-2" placeholder="Nama Anak" value="{{ old("anak.$i.nama", $anakModel->nama) }}">

                                            <input type="text" name="anak[{{ $i }}][nomor_hp]" class="form-control mt-2" placeholder="Nomor HP Anak (opsional)" value="{{ old("anak.$i.nomor_hp", $anakModel->nomor_hp ?? '') }}">

                                            <input type="text" name="anak[{{ $i }}][email]" class="form-control mt-2" placeholder="Email Anak (opsional)" value="{{ old("anak.$i.email", $anakModel->email ?? '') }}">

                                            <input type="date" name="anak[{{ $i }}][tanggal_lahir]" class="form-control mt-2" value="{{ old("anak.$i.tanggal_lahir", optional($anakModel)->tanggal_lahir) }}">

                                            <select name="anak[{{ $i }}][jenis_kelamin]" class="form-control mt-2">
                                                <option value="">Jenis Kelamin</option>
                                                <option value="Laki-laki" {{ old("anak.$i.jenis_kelamin", $anakModel->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old("anak.$i.jenis_kelamin", $anakModel->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>

                                            <input type="text" name="anak[{{ $i }}][tempat_lahir]" class="form-control mt-2" placeholder="Tempat Lahir" value="{{ old("anak.$i.tempat_lahir", $anakModel->tempat_lahir ?? '') }}">

                                            <select name="anak[{{ $i }}][hubungan]" class="form-control mt-2">
                                                <option value="anak" {{ old("anak.$i.hubungan", $anakModel->hubungan_keluarga) == 'anak' ? 'selected' : '' }}>Anak</option>
                                                <option value="tanggungan" {{ old("anak.$i.hubungan", $anakModel->hubungan_keluarga) == 'tanggungan' ? 'selected' : '' }}>Tanggungan</option>
                                            </select>

                                            <div class="baptis-fields mt-3" style="display:{{ (!empty($anakModel->tanggal_baptis) || !empty($anakModel->akte_baptis)) ? 'block' : 'none' }}">
                                                <div class="form-group mb-2">
                                                    <label>Tanggal Baptis</label>
                                                    <input type="date" name="anak[{{ $i }}][tanggal_baptis]" class="form-control" value="{{ old("anak.$i.tanggal_baptis", optional($anakModel)->tanggal_baptis) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Surat Baptis (scan)</label>
                                                    <input type="file" name="anak[{{ $i }}][surat_baptis]" class="form-control">
                                                    @if(!empty($anakModel->akte_baptis))
                                                        <div class="mt-1"><a href="{{ asset('storage/'.$anakModel->akte_baptis) }}" target="_blank">Lihat file</a></div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="sidi-fields mt-3" style="display:{{ (!empty($anakModel->tanggal_sidi) || !empty($anakModel->akte_sidi)) ? 'block' : 'none' }}">
                                                <div class="form-group mb-2">
                                                    <label>Tanggal Sidi</label>
                                                    <input type="date" name="anak[{{ $i }}][tanggal_sidi]" class="form-control" value="{{ old("anak.$i.tanggal_sidi", optional($anakModel)->tanggal_sidi) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Surat Sidi (scan)</label>
                                                    <input type="file" name="anak[{{ $i }}][surat_sidi]" class="form-control">
                                                    @if(!empty($anakModel->akte_sidi))
                                                        <div class="mt-1"><a href="{{ asset('storage/'.$anakModel->akte_sidi) }}" target="_blank">Lihat file</a></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="anak-item border rounded p-3 mb-3" data-index="0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="anak-number">1. Anggota Keluarga</strong>
                                            <div>
                                                <label class="me-2">
                                                    <input type="checkbox" name="anak[0][is_baptis]" onchange="toggleBaptis(this)"> Baptis
                                                </label>
                                                <label class="me-2">
                                                    <input type="checkbox" name="anak[0][is_sidi]" onchange="toggleSidi(this)"> Sidi
                                                </label>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeAnak(this)">Hapus</button>
                                            </div>
                                        </div>

                                        <input type="text" name="anak[0][nama]" class="form-control mt-2" placeholder="Nama Anak">

                                        <input type="text" name="anak[0][nomor_hp]" class="form-control mt-2" placeholder="Nomor HP Anak (opsional)">

                                        <input type="text" name="anak[0][email]" class="form-control mt-2" placeholder="Email Anak (opsional)">

                                        <input type="date" name="anak[0][tanggal_lahir]" class="form-control mt-2">

                                        <select name="anak[0][jenis_kelamin]" class="form-control mt-2">
                                            <option value="">Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>

                                        <input type="text" name="anak[0][tempat_lahir]" class="form-control mt-2" placeholder="Tempat Lahir">

                                        <select name="anak[0][hubungan]" class="form-control mt-2">
                                            <option value="anak">Anak</option>
                                            <option value="tanggungan">Tanggungan</option>
                                        </select>

                                        <div class="baptis-fields mt-3" style="display:none">
                                            <div class="form-group mb-2">
                                                <label>Tanggal Baptis</label>
                                                <input type="date" name="anak[0][tanggal_baptis]" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surat Baptis (scan)</label>
                                                <input type="file" name="anak[0][surat_baptis]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="sidi-fields mt-3" style="display:none">
                                            <div class="form-group mb-2">
                                                <label>Tanggal Sidi</label>
                                                <input type="date" name="anak[0][tanggal_sidi]" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surat Sidi (scan)</label>
                                                <input type="file" name="anak[0][surat_sidi]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <h5 class="mt-4 mb-3 border-bottom pb-2">Kelengkapan Administrasi</h5>

                            <div class="form-group mb-3">
                                <label>Surat Keterangan Gereja (scan)</label>
                                <input type="file" name="surat_keterangan_gereja" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Akte Pernikahan (scan)</label>
                                <input type="file" name="akte_pernikahan" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Akte Baptis (scan)</label>
                                <input type="file" name="akte_baptis" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Akte Sidi (scan)</label>
                                <input type="file" name="akte_sidi" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Surat Pengantar Sintua Wijk (scan)</label>
                                <input type="file" name="surat_pengantar_sintua_wijk" class="form-control">
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Simpan Data
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= SCRIPT ================= --}}
<script>
    function toggleForm(role, status) {
    const form = document.getElementById('form-' + role);
    form.style.display = status === 'hidup' ? 'block' : 'none';
}

// Dynamic anak handlers
let anakIndex = 1; // next index (0 used by initial item)

function addAnak() {
    const wrapper = document.getElementById('anak-wrapper');
    const index = anakIndex++;

    const div = document.createElement('div');
    div.className = 'anak-item border rounded p-3 mb-3';
    div.setAttribute('data-index', index);

    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <strong class="anak-number">${index + 1}. anggota</strong>
            <div>
                <label class="me-2">
                    <input type="checkbox" name="anak[${index}][is_baptis]" onchange="toggleBaptis(this)"> Baptis
                </label>
                <label class="me-2">
                    <input type="checkbox" name="anak[${index}][is_sidi]" onchange="toggleSidi(this)"> Sidi
                </label>
                
                <button type="button" class="btn btn-sm btn-danger" onclick="removeAnak(this)">Hapus</button>
            </div>
        </div>

        <input type="text" name="anak[${index}][nama]" class="form-control mt-2" placeholder="Nama Anak">
        <input type="text" name="anak[${index}][nomor_hp]" class="form-control mt-2" placeholder="Nomor HP Anak (opsional)">
        <input type="text" name="anak[${index}][email]" class="form-control mt-2" placeholder="Email Anak (opsional)">
        <input type="date" name="anak[${index}][tanggal_lahir]" class="form-control mt-2">
        <select name="anak[${index}][jenis_kelamin]" class="form-control mt-2">
            <option value="">Jenis Kelamin</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        <input type="text" name="anak[${index}][tempat_lahir]" class="form-control mt-2" placeholder="Tempat Lahir">
        <select name="anak[${index}][hubungan]" class="form-control mt-2">
            <option value="anak">Anak</option>
            <option value="tanggungan">Tanggungan</option>
        </select>

        <div class="baptis-fields mt-3" style="display:none">
            <div class="form-group mb-2">
                <label>Tanggal Baptis</label>
                <input type="date" name="anak[${index}][tanggal_baptis]" class="form-control">
            </div>
            <div class="form-group">
                <label>Surat Baptis (scan)</label>
                <input type="file" name="anak[${index}][surat_baptis]" class="form-control">
            </div>
        </div>

        <div class="sidi-fields mt-3" style="display:none">
            <div class="form-group mb-2">
                <label>Tanggal Sidi</label>
                <input type="date" name="anak[${index}][tanggal_sidi]" class="form-control">
            </div>
            <div class="form-group">
                <label>Surat Sidi (scan)</label>
                <input type="file" name="anak[${index}][surat_sidi]" class="form-control">
            </div>
        </div>
    `;

    wrapper.appendChild(div);
    refreshAnakNumbers();
}

function removeAnak(button) {
    const item = button.closest('.anak-item');
    if (!item) return;
    item.remove();
    refreshAnakNumbers();
}

function refreshAnakNumbers() {
    const items = document.querySelectorAll('#anak-wrapper .anak-item');
    items.forEach((el, i) => {
        const num = el.querySelector('.anak-number');
        if (num) num.textContent = `${i + 1}. Anak`;
        // Also update radio values and input names to maintain request keys
        const idx = Array.from(items).indexOf(el);
        el.setAttribute('data-index', idx);
        // update radio
        const radio = el.querySelector('input[type="radio"][name="kepala_anak"]');
        if (radio) radio.value = idx;
        // Update all anak[...] names inside this el
        el.querySelectorAll('[name]').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/anak\[\d+\]/, `anak[${idx}]`);
            input.setAttribute('name', newName);
        });
    });
    // reset anakIndex to current length
    anakIndex = document.querySelectorAll('#anak-wrapper .anak-item').length;
    
}

// ensure state on load
document.addEventListener('DOMContentLoaded', () => {
    refreshAnakNumbers();
    // setup automatic gender for parents
    const ayahInput = document.getElementById('ayah_nama');
    const ibuInput = document.getElementById('ibu_nama');
    const ayahGender = document.getElementById('ayah_jenis_kelamin');
    const ibuGender = document.getElementById('ibu_jenis_kelamin');
    if (ayahInput) {
        ayahInput.addEventListener('input', () => {
            ayahGender.value = ayahInput.value.trim() ? 'Laki-laki' : '';
        });
    }
    if (ibuInput) {
        ibuInput.addEventListener('input', () => {
            ibuGender.value = ibuInput.value.trim() ? 'Perempuan' : '';
        });
    }
});

// Toggle display for Baptis fields inside an anak-item
function toggleBaptis(el) {
    const item = el.closest('.anak-item');
    if (!item) return;
    const fields = item.querySelector('.baptis-fields');
    if (!fields) return;
    const isChecked = (el.type === 'checkbox') ? el.checked : (el.value === 'sudah');
    fields.style.display = isChecked ? 'block' : 'none';
}

// Toggle display for Sidi fields inside an anak-item
function toggleSidi(el) {
    const item = el.closest('.anak-item');
    if (!item) return;
    const fields = item.querySelector('.sidi-fields');
    if (!fields) return;
    const isChecked = (el.type === 'checkbox') ? el.checked : (el.value === 'sudah');
    fields.style.display = isChecked ? 'block' : 'none';
}

</script>
@endsection