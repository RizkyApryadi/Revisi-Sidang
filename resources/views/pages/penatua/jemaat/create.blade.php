@extends('layouts.main')
@section('title', 'Add Jemaat')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Tambah Jemaat</h4>
                    </div>
                    <div class="card-body bg-white">
                        <form action="{{ route('penatua.jemaat.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

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

                            <h4 class="mb-2">Formulir Jemaat Baru</h4>
                            <p>Kami yang bertanda tangan di bawah ini:</p>

                            <div class="form-group mb-3">
                                <label>Nomor KK</label>
                                <input type="text" name="nomor_kk" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Suami</label>
                                <input type="text" name="ayah_nama" id="ayah_nama" class="form-control">
                                <input type="hidden" name="ayah_jenis_kelamin" id="ayah_jenis_kelamin" value="">
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Istri</label>
                                <input type="text" name="ibu_nama" id="ibu_nama" class="form-control">
                                <input type="hidden" name="ibu_jenis_kelamin" id="ibu_jenis_kelamin" value="">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>HP</label>
                                        <input type="text" name="hp" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Wijk</label>
                                @if(isset($penatuaWijk) && $penatuaWijk)
                                <input type="hidden" name="wijk_id" value="{{ $penatuaWijk->id }}">
                                <input type="text" class="form-control" value="{{ $penatuaWijk->nama_wijk }}" disabled>
                                @else
                                <select name="wijk_id" class="form-control">
                                    <option value="">-- Pilih Wijk --</option>
                                    @foreach($wijks as $wijk)
                                    <option value="{{ $wijk->id }}">{{ $wijk->nama_wijk }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Asal Gereja</label>
                                <input type="text" name="asal_gereja" class="form-control">
                            </div>




                            {{-- ================= DATA ANAK ================= --}}
                            <h5 class="mt-4 mb-3 border-bottom pb-2 d-flex justify-content-between">
                                Daftar Anggota Keluarga
                                <button type="button" class="btn btn-sm btn-success" onclick="addAnak()">+
                                    Anggota</button>
                            </h5>

                            <div id="anak-wrapper">

                                <div class="anak-item border rounded p-3 mb-3" data-index="0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="anak-number">1. Anggota Keluarga</strong>
                                        <div>
                                            <label class="me-2">
                                                <input type="checkbox" name="anak[0][is_baptis]"
                                                    onchange="toggleBaptis(this)"> Baptis
                                            </label>
                                            <label class="me-2">
                                                <input type="checkbox" name="anak[0][is_sidi]"
                                                    onchange="toggleSidi(this)"> Sidi
                                            </label>
                                            
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="removeAnak(this)">Hapus</button>
                                        </div>
                                    </div>

                                    <input type="text" name="anak[0][nama]" class="form-control mt-2"
                                        placeholder="Nama Anak">

                                    <input type="date" name="anak[0][tanggal_lahir]" class="form-control mt-2">

                                    <select name="anak[0][jenis_kelamin]" class="form-control mt-2">
                                        <option value="">Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>

                                    <input type="text" name="anak[0][tempat_lahir]" class="form-control mt-2"
                                        placeholder="Tempat Lahir">

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