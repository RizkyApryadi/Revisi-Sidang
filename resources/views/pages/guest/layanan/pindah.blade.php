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
        <div class="bg-white border border-green-500 rounded-md shadow-sm">
            <div class="bg-green-600 text-white text-center py-2 rounded-t-md font-semibold">
                Data Jemaat
            </div>

            <div class="p-4">
                {{-- DATA ORANG TUA --}}
                <form method="POST" action="{{ route('guest.layanan.pindah.store') }}">
                    @csrf

                    {{-- Alert --}}
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

                    <p class="mb-4 font-medium">
                        Kami yang bertanda tangan di bawah ini:
                    </p>

                    {{-- Nomor Keluarga --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">Nomor Keluarga</label>
                        <div class="w-full md:w-5/6">
                            <input type="text" name="nomor_kk" class="w-full border border-gray-300 rounded-md p-2"
                                value="{{ old('nomor_kk') }}">
                        </div>
                    </div>

                    {{-- Nama Suami --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">Nama Suami</label>
                        <div class="w-full md:w-5/6">
                            <input type="text" name="nama_suami" class="w-full border border-gray-300 rounded-md p-2"
                                value="{{ old('nama_suami') }}">
                        </div>
                    </div>

                    {{-- Nama Istri --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">Nama Istri</label>
                        <div class="w-full md:w-5/6">
                            <input type="text" name="nama_istri" class="w-full border border-gray-300 rounded-md p-2"
                                value="{{ old('nama_istri') }}">
                        </div>
                    </div>

                    {{-- HP --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">HP</label>
                        <div class="w-full md:w-5/6">
                            <input type="text" name="no_hp" class="w-full border border-gray-300 rounded-md p-2"
                                value="{{ old('no_hp') }}">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">Email</label>
                        <div class="w-full md:w-5/6">
                            <input type="email" name="email" class="w-full border border-gray-300 rounded-md p-2"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    {{-- Wijk --}}
                    <div class="flex flex-wrap mb-2 items-center">
                        <label class="w-full md:w-1/6 font-medium">Wijk</label>
                        <div class="w-full md:w-5/6">
                            <select name="wijk_id" class="w-full border border-gray-300 rounded-md p-2">
                                <option value="">- Pilih Wijk -</option>
                                @foreach($wijken as $wijk)
                                <option value="{{ $wijk->id }}" {{ old('wijk_id')==$wijk->id ? 'selected' : '' }}>
                                    {{ $wijk->nama_wijk }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="flex flex-wrap mb-3 items-start">
                        <label class="w-full md:w-1/6 font-medium">Alamat</label>
                        <div class="w-full md:w-5/6">
                            <textarea name="alamat" rows="3"
                                class="w-full border border-gray-300 rounded-md p-2">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="flex flex-wrap mb-4 items-start">
                        <label class="w-full md:w-1/6 font-medium">Keterangan</label>
                        <div class="w-full md:w-5/6">
                            <textarea name="keterangan" rows="3"
                                class="w-full border border-gray-300 rounded-md p-2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    {{-- ================= DATA ANAK ================= --}}
                    <h5 class="mt-4 mb-3 border-b pb-2 flex items-center justify-between">
                        <span class="font-medium">Daftar Anggota Keluarga</span>
                        <button type="button" class="ml-3 px-3 py-1 bg-green-600 text-white rounded-md text-sm"
                            onclick="addAnak()">+ Anak</button>
                    </h5>

                    <div id="anak-wrapper">

                        <div class="anak-item border rounded p-3 mb-3 bg-white text-sm" data-index="0">
                            <div class="flex items-center justify-between mb-2">
                                <strong class="anak-number text-base">1. Anak</strong>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-sm"
                                        onclick="removeAnak(this)">Hapus</button>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Anak</label>
                                    <input type="text" name="anak[0][nama]" class="w-full border rounded p-2"
                                        placeholder="Nama Anak">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                                    <input type="date" name="anak[0][tanggal_lahir]" class="w-full border rounded p-2">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                                    <select name="anak[0][jenis_kelamin]" class="w-full border rounded p-2">
                                        <option value="">Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
                                    <input type="text" name="anak[0][tempat_lahir]" class="w-full border rounded p-2"
                                        placeholder="Tempat Lahir">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Hubungan</label>
                                    <select name="anak[0][hubungan]" class="w-full border rounded p-2">
                                        <option value="anak">Anak</option>
                                        <option value="tanggungan">Tanggungan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- TOMBOL --}}
                    <div class="flex justify-end gap-3 mt-4">
                        <a href="#" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">KEMBALI</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">KIRIM</button>
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