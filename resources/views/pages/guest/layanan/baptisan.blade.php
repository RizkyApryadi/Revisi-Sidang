<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Pindah jemaat</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="pt-24 bg-gray-50 text-gray-900"> {{-- pad top to avoid fixed nav overlap --}}
    @include('pages.guest.partialsGuest.navGuest')

    <div class="max-w-5xl mx-auto px-4">

        {{-- FORM UTAMA --}}
        <div class="max-w-5xl mx-auto bg-white border border-green-500 rounded-md shadow-sm">

            {{-- HEADER --}}
            <div class="bg-green-600 text-white text-center py-3 rounded-t-md font-semibold text-lg">
                Form Baptisan Kudus
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('guest.layanan.baptisan.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ALERT --}}
                    @if(session('success'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-50 border border-red-200">
                        <strong class="text-red-700">Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-red-600">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <p class="mb-6 font-medium">
                        Kami yang bertanda tangan di bawah ini:
                    </p>

                    {{-- ================= IDENTITAS KELUARGA ================= --}}
                    <div class="border rounded-md p-4 bg-gray-50 mb-6">
                        <h4 class="font-semibold text-green-700 mb-4 border-b pb-2">
                            Identitas Keluarga
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
                            <label class="md:col-span-1 font-medium">Nomor Keluarga</label>
                            <input type="text" name="nomor_kk" class="md:col-span-5 border rounded-md p-2"
                                value="{{ old('nomor_kk') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mt-3">
                            <label class="md:col-span-1 font-medium">Nama Suami</label>
                            <input type="text" name="nama_suami" class="md:col-span-5 border rounded-md p-2"
                                value="{{ old('nama_suami') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mt-3">
                            <label class="md:col-span-1 font-medium">Nama Istri</label>
                            <input type="text" name="nama_istri" class="md:col-span-5 border rounded-md p-2"
                                value="{{ old('nama_istri') }}">
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mt-3">
                            <label class="md:col-span-1 font-medium">No. HP</label>
                            <input type="text" name="no_hp" class="md:col-span-5 border rounded-md p-2"
                                value="{{ old('no_hp') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mt-3">
                            <label class="md:col-span-1 font-medium">Email</label>
                            <input type="email" name="email" class="md:col-span-5 border rounded-md p-2"
                                value="{{ old('email') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mt-3">
                            <label class="md:col-span-1 font-medium">Wijk</label>
                            <select name="wijk" class="md:col-span-5 border rounded-md p-2">
                                <option value="">- Pilih Wijk -</option>
                                @if(!empty($wijks) && $wijks->count())
                                    @foreach($wijks as $wijk)
                                        <option value="{{ $wijk->nama_wijk }}" {{ old('wijk') == $wijk->nama_wijk ? 'selected' : '' }}>{{ $wijk->nama_wijk }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-3">
                            <label class="md:col-span-1 font-medium">Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="md:col-span-5 border rounded-md p-2">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-3">
                            <label class="md:col-span-1 font-medium">Keterangan</label>
                            <textarea name="keterangan" rows="3"
                                class="md:col-span-5 border rounded-md p-2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    {{-- ================= INFORMASI BAPTISAN ================= --}}
                    <div class="border rounded-md p-4 bg-gray-50 mb-6">
                        <h4 class="font-semibold text-green-700 mb-4 border-b pb-2">
                            Informasi Baptisan
                        </h4>

                        <p class="text-sm mb-2">
                            Ingin membawa anak kami menerima Sakramen Baptisan Kudus pada Kebaktian Minggu di Gereja
                            HKBP
                        </p>

                        <label class="block text-sm font-medium mb-1">Tanggal Kebaktian</label>
                        <input type="date" name="tanggal_kebaktian" class="w-full border rounded-md p-2">
                    </div>

                    {{-- ================= DATA ANAK ================= --}}
                    <div class="border rounded-md p-4 bg-gray-50 mb-6">
                        <div class="flex items-center justify-between mb-4 border-b pb-2">
                            <h4 class="font-semibold text-green-700">
                                Daftar Anggota Keluarga
                            </h4>
                            <button type="button" class="px-3 py-1 bg-green-600 text-white rounded-md text-sm"
                                onclick="addAnak()">
                                + Anak
                            </button>
                        </div>

                        <div id="anak-wrapper">
                            <div class="anak-item border rounded p-4 mb-4 bg-white text-sm" data-index="0">
                                <div class="flex justify-between items-center mb-3">
                                    <strong class="text-base">1. Anak</strong>
                                    <button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-sm"
                                        onclick="removeAnak(this)">
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-medium mb-1">Nama Anak</label>
                                        <input type="text" name="anak[0][nama]" class="w-full border rounded p-2">
                                    </div>

                                    <div>
                                        <label class="block font-medium mb-1">Tanggal Lahir</label>
                                        <input type="date" name="anak[0][tanggal_lahir]"
                                            class="w-full border rounded p-2">
                                    </div>

                                    <div>
                                        <label class="block font-medium mb-1">Jenis Kelamin</label>
                                        <select name="anak[0][jenis_kelamin]" class="w-full border rounded p-2">
                                            <option value="">Pilih</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-medium mb-1">Tempat Lahir</label>
                                        <input type="text" name="anak[0][tempat_lahir]"
                                            class="w-full border rounded p-2">
                                    </div>

                                    <div>
                                        <label class="block font-medium mb-1">Hubungan</label>
                                        <select name="anak[0][hubungan]" class="w-full border rounded p-2">
                                            <option value="anak">Anak</option>
                                            <option value="tanggungan">Tanggungan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- ================= DATA ADMINISTRASI ================= --}}
                    <div class="border rounded-md p-4 bg-gray-50 mb-6">
                        <h4 class="font-semibold text-green-700 mb-4 border-b pb-2">
                            Data Administrasi
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mb-4">
                            <label class="md:col-span-2 font-medium">
                                Akte Pernikahan
                            </label>
                            <div class="md:col-span-4">
                                <input type="file" name="akte_pernikahan" class="w-full border rounded-md p-2 bg-white"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-gray-500">
                                    Format: PDF / JPG / PNG
                                </small>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mb-4">
                            <label class="md:col-span-2 font-medium">
                                Akte / Surat Pengantar Sintua
                            </label>
                            <div class="md:col-span-4">
                                <input type="file" name="surat_pengantar_sintua"
                                    class="w-full border rounded-md p-2 bg-white" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-gray-500">
                                    Format: PDF / JPG / PNG
                                </small>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
                            <label class="md:col-span-2 font-medium">
                                Surat Pengantar Jemaat Lain
                            </label>
                            <div class="md:col-span-4">
                                <input type="file" name="surat_pengantar_jemaat_lain"
                                    class="w-full border rounded-md p-2 bg-white" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-gray-500">
                                    Wajib diisi jika berasal dari jemaat lain
                                </small>
                            </div>
                        </div>
                    </div>


                    {{-- TOMBOL --}}
                    <div class="flex justify-end gap-3">
                        <a href="#" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
                            Kembali
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                            Kirim
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
    @include('pages.guest.partialsGuest.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function(){
    function elClosest(el, selector){ return el.closest ? el.closest(selector) : null }

    window.addAnak = function(){
        const wrapper = document.getElementById('anak-wrapper');
        const items = wrapper.querySelectorAll('.anak-item');
        const template = items[0];
        const clone = template.cloneNode(true);

        // Clear values in cloned inputs
        clone.querySelectorAll('input, select, textarea').forEach(function(inp){
            if(inp.type === 'checkbox' || inp.type === 'radio') inp.checked = false;
            else if(inp.type === 'file') inp.value = '';
            else inp.value = '';
        });

        wrapper.appendChild(clone);
        refreshIndices();
    }

    window.removeAnak = function(btn){
        const item = elClosest(btn, '.anak-item');
        if(!item) return;
        item.remove();
        refreshIndices();
    }

    // Baptis/Sidi removed — no toggle functions needed

    function refreshIndices(){
        const wrapper = document.getElementById('anak-wrapper');
        const items = Array.from(wrapper.querySelectorAll('.anak-item'));

        items.forEach(function(item, i){
            item.setAttribute('data-index', i);
            const num = item.querySelector('.anak-number');
            if(num) num.textContent = (i+1) + '. Anak';

            // Update names for inputs that belong to anak[index][...]
            item.querySelectorAll('[name]').forEach(function(el){
                const name = el.getAttribute('name');
                if(!name) return;
                if(/anak\[\d+\]/.test(name)){
                    const newName = name.replace(/anak\[\d+\]/, 'anak['+i+']');
                    el.setAttribute('name', newName);
                }
                // update kepala radio value if present
                if(el.type === 'radio' && el.name === 'kepala_anak'){
                    el.value = i;
                }
            });
        });

        // kepala radio removed — no visibility or selection handling
    }

    // initial setup
    refreshIndices();
});
    </script>

</body>

</html>




</div>