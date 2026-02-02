
@extends('layouts.main')
@section('title', 'Form Edit Pendeta')

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
				Form Edit Pendeta
			</h1>
		</div>

		<!-- BODY -->
		<div class="p-8 space-y-6">

			<!-- INFO -->
			<div class="flex gap-3 p-4 rounded-lg bg-blue-50 border-2 border-blue-300">
				<svg class="w-5 h-5 text-blue-700 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
					viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round"
						d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
				</svg>
				<p class="text-sm font-semibold text-blue-700">
					Edit data pendeta. Isian dengan tanda <span class="text-red-600">*</span> wajib diisi.
				</p>
			</div>

			<!-- FORM -->
			<form method="post" action="{{ route('admin.pendeta.update', $pendeta->id) }}" class="space-y-5">
				@csrf
				@method('PUT')

				<!-- JEMAAT -->
				<div>
					<label class="block text-sm font-bold text-gray-800 mb-2">
						Anggota Jemaat <span class="text-red-600">*</span>
					</label>
					<div class="relative">
						<input id="jemaat_search" type="text" autocomplete="off" name="jemaat_nama" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
					   focus:border-indigo-600 focus:ring-0 font-medium"
							placeholder="Ketik 1-2 huruf untuk mencari..."
							value="{{ data_get($pendeta, 'jemaat.nama') ?? data_get($pendeta, 'jemaat_nama') ?? data_get($pendeta, 'nama') ?? '' }}">

						<input type="hidden" name="jemaat_id" id="jemaat_id" value="{{ data_get($pendeta, 'jemaat.id') ?? $pendeta->jemaat_id ?? '' }}">

						<div id="jemaat_selected_info" class="mt-2 text-sm text-gray-700 {{ (data_get($pendeta, 'jemaat.id') || $pendeta->jemaat_id) ? '' : 'hidden' }}">
							<strong>Terpilih:</strong> ID <span id="jemaat_selected_id">{{ data_get($pendeta, 'jemaat.id') ?? $pendeta->jemaat_id ?? '-' }}</span> —
							<span id="jemaat_selected_name">{{ data_get($pendeta, 'jemaat.nama') ?? data_get($pendeta, 'jemaat_nama') ?? data_get($pendeta, 'nama') ?? '-' }}</span>
						</div>

						<div id="jemaat_suggestions"
							class="absolute z-50 w-full bg-white border rounded mt-1 shadow max-h-56 overflow-auto hidden">
						</div>
					</div>
					<p class="text-xs font-semibold text-gray-600 mt-1">
						Pilih anggota jemaat yang akan menjadi pendeta
					</p>
				</div>

				<!-- TANGGAL TAHBIS -->
				<div>
					<label class="block text-sm font-bold text-gray-800 mb-2">
						Tanggal Ditahbis <span class="text-red-600">*</span>
					</label>
					<input type="date" name="tanggal_tahbis" max="{{ date('Y-m-d') }}" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
			focus:border-indigo-600 focus:ring-0 font-medium" value="{{ isset($pendeta->tanggal_tahbis) ? \Carbon\Carbon::parse($pendeta->tanggal_tahbis)->format('Y-m-d') : '' }}">
				</div>

				<!-- STATUS -->
				<div>
					<label class="block text-sm font-bold text-gray-800 mb-2">
						Status Pelayanan <span class="text-red-600">*</span>
					</label>
					<select name="status" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
			focus:border-indigo-600 focus:ring-0 font-medium">
						<option value="">-- Pilih Status --</option>
						<option value="aktif" {{ (data_get($pendeta, 'status') == 'aktif') ? 'selected' : '' }}>Aktif</option>
						<option value="nonaktif" {{ (data_get($pendeta, 'status') == 'nonaktif') ? 'selected' : '' }}>Nonaktif</option>
						<option value="selesai" {{ (data_get($pendeta, 'status') == 'selesai') ? 'selected' : '' }}>Selesai</option>
					</select>
				</div>

				<!-- NO SK -->
				<div>
					<label class="block text-sm font-bold text-gray-800 mb-2">
						No. SK Tahbisan
					</label>
						<input type="text" name="no_sk_tahbis" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
					focus:border-indigo-600 focus:ring-0 font-medium" placeholder="Contoh: SK-012/PGI/2024" value="{{ data_get($pendeta, 'no_sk_tahbis') ?? '' }}">
				</div>

				<!-- KETERANGAN -->
				<div>
					<label class="block text-sm font-bold text-gray-800 mb-2">
						Keterangan
					</label>
					<textarea name="keterangan" rows="3" class="w-full rounded-lg border-2 border-gray-300 px-3 py-2.5
			focus:border-indigo-600 focus:ring-0 font-medium" placeholder="Keterangan tambahan (opsional)">{{ data_get($pendeta, 'keterangan') ?? '' }}</textarea>
				</div>

		<!-- FOOTER buttons inside form -->
		<div class="px-8 py-5 border-t-2 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
			<a href="{{ route('admin.pendeta') }}" class="px-5 py-2 rounded-lg bg-gray-600 text-white font-bold">
				← Batal
			</a>
			<button type="submit" class="px-6 py-2 rounded-lg bg-indigo-700 text-white font-bold">
				✓ Simpan Perubahan
			</button>
		</div>

		</form>

		</div>

	</div>
</div>
@endsection

@push('script')
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

	});
</script>
@endpush
