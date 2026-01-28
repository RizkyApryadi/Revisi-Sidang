@extends('layouts.main')
@section('title', 'Form Tambah Sintua')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <div class="bg-white rounded-xl shadow border-2 border-gray-200">

        <!-- HEADER -->
        <div class="px-8 py-5 border-b-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            <h1 class="text-lg font-bold text-gray-800">
                Form Tambah Sintua
            </h1>
        </div>

        <!-- BODY -->
        <div class="p-8 space-y-6">

            <!-- INFO BOX -->
            <div class="flex gap-3 p-4 rounded-lg bg-blue-50 border-2 border-blue-300">
                <svg class="w-5 h-5 text-blue-700 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                <div>
                    <p class="font-bold text-blue-800 text-sm">
                        Informasi:
                    </p>
                    <p class="text-sm font-semibold text-blue-700">
                        Setelah penatua ditambahkan, gelar <span class="font-bold">"Pt."</span>
                        akan otomatis ditambahkan ke nama anggota yang dipilih.
                    </p>
                </div>
            </div>

            <!-- ANGGOTA JEMAAT -->
            <form method="post" action="{{ route('admin.penatua.store') }}" class="space-y-5">
                @csrf

                <!-- ANGGOTA JEMAAT -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Anggota Jemaat <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <input id="jemaat_search" type="text" autocomplete="off" name="jemaat_nama" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
                       focus:border-indigo-600 focus:ring-0 font-medium"
                            placeholder="Ketik 1-2 huruf untuk mencari...">

                        <input type="hidden" name="jemaat_id" id="jemaat_id">

                        <div id="jemaat_selected_info" class="mt-2 text-sm text-gray-700 hidden">
                            <strong>Terpilih:</strong> ID <span id="jemaat_selected_id">-</span> —
                            <span id="jemaat_selected_name">-</span>
                        </div>

                        <div id="jemaat_suggestions"
                            class="absolute z-50 w-full bg-white border rounded mt-1 shadow max-h-56 overflow-auto hidden">
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-600 mt-1">
                        Pilih anggota jemaat yang akan menjadi sintua
                    </p>
                </div>

                <!-- WIJK -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Wijk <span class="text-red-600">*</span>
                    </label>
                    <select id="wijk_select" name="wijk_id" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
                   focus:border-indigo-600 focus:ring-0 font-medium">
                        <option value="">-- Pilih Wijk --</option>
                        @foreach($wijks as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_wijk }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Status <span class="text-red-600">*</span>
                    </label>
                    <select name="status" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
                   focus:border-indigo-600 focus:ring-0 font-medium">
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <!-- TANGGAL TAHBIS -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Tanggal Diteguhkan <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="tanggal_tahbis" max="{{ date('Y-m-d') }}" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
                   focus:border-indigo-600 focus:ring-0 font-medium">
                    <p class="text-xs font-semibold text-gray-600 mt-1">
                        Tanggal tidak boleh lebih dari hari ini
                    </p>
                </div>

                <!-- KETERANGAN -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" rows="3" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
                   focus:border-indigo-600 focus:ring-0 font-medium"
                        placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </form>


        </div>

        <!-- FOOTER -->
        <div class="px-8 py-5 border-t-2 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
            <a href="{{ route('admin.penatua') }}"
                class="px-5 py-2 rounded-lg bg-gray-600 text-white font-bold hover:bg-gray-700 transition flex items-center gap-2">
                ← Batal
            </a>
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-700 text-white font-bold hover:bg-indigo-800 transition flex items-center gap-2">
                ✓ Simpan
            </button>
        </div>
        </form>

    </div>
</div>
@endsection

@push('script')
<!-- Load SweetAlert2 to ensure `Swal` is available for error modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('jemaat_search');
        const suggestions = document.getElementById('jemaat_suggestions');
        const hiddenId = document.getElementById('jemaat_id');
        let timer = null;

        function hideSuggestions() {
            suggestions.classList.add('hidden');
            suggestions.innerHTML = '';
        }

        function renderList(items) {
            if (!items || items.length === 0) {
                hideSuggestions();
                return;
            }
            suggestions.innerHTML = '';
            items.forEach(i => {
                const div = document.createElement('div');
                div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
                div.textContent = (i.id ? ('J' + i.id.toString().padStart(3,'0') + ' - ') : '') + i.nama + (i.no_telp ? (' ('+i.no_telp+')') : '');
                div.dataset.id = i.id;
                div.dataset.nama = i.nama;
                div.dataset.wijkId = i.wijk_id || '';
                div.dataset.wijkName = i.wijk_name || '';
                div.addEventListener('click', function () {
                    input.value = this.dataset.nama;
                    hiddenId.value = this.dataset.id;
                    // set wijk select default if available
                    const wijkSelect = document.getElementById('wijk_select');
                    if (wijkSelect) {
                        if (this.dataset.wijkId) {
                            wijkSelect.value = this.dataset.wijkId;
                            wijkSelect.required = true;
                        } else {
                            wijkSelect.value = '';
                            wijkSelect.required = false;
                        }
                    }
                        // update visible selected info
                        const selInfo = document.getElementById('jemaat_selected_info');
                        const selId = document.getElementById('jemaat_selected_id');
                        const selName = document.getElementById('jemaat_selected_name');
                        if (selInfo && selId && selName) {
                            selId.textContent = this.dataset.id || '-';
                            selName.textContent = this.dataset.nama || '-';
                            selInfo.classList.remove('hidden');
                        }
                        hideSuggestions();
                });
                suggestions.appendChild(div);
            });
            suggestions.classList.remove('hidden');
        }

        function fetchResults(q) {
            fetch('/admin/jemaat/search?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => renderList(data))
            .catch(() => hideSuggestions());
        }

        input.addEventListener('input', function (e) {
            const v = this.value.trim();
            hiddenId.value = '';
            if (timer) clearTimeout(timer);
            if (v.length < 1) {
                hideSuggestions();
                return;
            }
            timer = setTimeout(() => fetchResults(v), 250);
        });

        document.addEventListener('click', function (e) {
            if (!suggestions.contains(e.target) && e.target !== input) {
                hideSuggestions();
            }
        });

        // Prefill from query params (when redirected from jemaat list)
        try {
            const params = new URLSearchParams(window.location.search);
            const jid = params.get('jemaat_id');
            const jname = params.get('nama');
            if (jid) {
                // set basic values
                input.value = jname || '';
                hiddenId.value = jid;
                    // reflect in visible selected info
                    try {
                        const selInfo = document.getElementById('jemaat_selected_info');
                        const selId = document.getElementById('jemaat_selected_id');
                        const selName = document.getElementById('jemaat_selected_name');
                        if (selInfo && selId && selName) {
                            selId.textContent = jid;
                            selName.textContent = jname || '-';
                            selInfo.classList.remove('hidden');
                        }
                    } catch(e) {}
                // attempt to fetch more data (wijk) via search endpoint
                if (jname && jname.length > 0) {
                    fetchResults(jname);
                    // after fetchResults renders suggestions, try to auto-select matching id
                    // wait briefly for suggestions to populate
                    setTimeout(function(){
                        const items = Array.from(document.querySelectorAll('#jemaat_suggestions > div'));
                        const match = items.find(it => it.dataset.id === String(jid));
                        if (match) {
                            // trigger the click handler to set wijk select
                            match.click();
                        }
                    }, 400);
                }
            }
        } catch (e) {}

            // Show server-side validation errors (if any) using SweetAlert
            @if ($errors->any())
                try {
                    const serverErrors = {!! json_encode($errors->all()) !!};
                    if (Array.isArray(serverErrors) && serverErrors.length) {
                        const html = '<ul style="text-align:left;margin:0;padding-left:1.2em;">' + serverErrors.map(function(e){ return '<li>' + e + '</li>'; }).join('') + '</ul>';
                        Swal.fire({ icon: 'error', title: 'Validasi gagal', html: html, confirmButtonText: 'Tutup' });
                    }
                } catch (e) {}
            @endif
            @if (session('error'))
                try {
                    Swal.fire({ icon: 'error', title: 'Error', text: {!! json_encode(session('error')) !!}, confirmButtonText: 'Tutup' });
                } catch (e) {}
            @endif

            @if (session('success'))
                try {
                    Swal.fire({ icon: 'success', title: 'Sukses', text: {!! json_encode(session('success')) !!}, timer: 1800, showConfirmButton: false });
                } catch (e) {}
            @endif
    });
</script>
@endpush