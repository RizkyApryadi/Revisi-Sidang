<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Renungan</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900">

    @include('pages.guest.partialsGuest.navGuest')

    <section class="pt-12 pb-14 bg-slate-50">
        <div class="w-full px-8 lg:px-24">

            <h1 class="text-3xl md:text-4xl font-bold text-left mb-4 text-slate-800">
                Renungan Harian
            </h1>

            @if(!isset($renungans))
            @php $renungans = collect(); @endphp
            @endif

            {{-- ================= FILTER ================= --}}
            <div class="mb-8 flex justify-left">
                <div class="bg-white rounded-lg shadow-sm border p-4 w-fit">
                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Search --}}
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Cari</label>
                            <input type="text" id="searchInput" placeholder="Judul / isi..."
                                class="border rounded-md px-3 py-1.5 text-sm w-56 outline-none focus:ring-1 focus:ring-indigo-400">
                        </div>

                        {{-- Tahun --}}
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Tahun</label>
                            <select id="yearFilter"
                                class="border rounded-md px-3 py-1.5 text-sm w-32 outline-none focus:ring-1 focus:ring-indigo-400">
                                <option value="">Semua</option>
                                @foreach($renungans->groupBy(fn($r)=>\Carbon\Carbon::parse($r->tanggal)->year) as $year
                                => $items)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bulan --}}
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Bulan</label>
                            <select id="monthFilter"
                                class="border rounded-md px-3 py-1.5 text-sm w-40 outline-none focus:ring-1 focus:ring-indigo-400">
                                <option value="">Semua</option>
                                @for($m=1;$m<=12;$m++) <option value="{{ $m }}">
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                    @endfor
                            </select>
                        </div>

                        {{-- Reset --}}
                        <button onclick="resetFilter()"
                            class="text-sm px-3 py-1.5 rounded-md border hover:bg-gray-100 font-medium">
                            Reset
                        </button>

                    </div>
                </div>
            </div>

            {{-- ================= GRID LIST ================= --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($renungans as $r)
                <div class="renungan-item bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 border border-slate-200 flex flex-col"
                    data-title="{{ strtolower($r->judul) }}" data-content="{{ strtolower(strip_tags($r->konten)) }}"
                    data-year="{{ \Carbon\Carbon::parse($r->tanggal)->year }}"
                    data-month="{{ \Carbon\Carbon::parse($r->tanggal)->month }}">

                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="text-xl font-semibold text-slate-800 mb-2">
                            {{ $r->judul }}
                        </h2>

                        <div class="text-sm text-slate-500 mb-3">
                            {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->translatedFormat('j F Y') : '-' }}
                            • {{ optional($r->pendeta)->nama ?? '-' }}
                        </div>

                        <p class="text-slate-600 leading-relaxed mb-5 line-clamp-3">
                            {!! \Illuminate\Support\Str::limit(strip_tags($r->konten), 160, '...') !!}
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('guest.renungan.show', $r->id) }}"
                                class="w-full inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg transition">
                                Baca Renungan
                            </a>
                        </div>
                    </div>

                </div>
                @empty
                <div class="col-span-3 text-center text-slate-600 py-12">
                    Belum ada renungan yang dipublikasikan.
                </div>
                @endforelse

            </div>

        </div>
    </section>

    @include('pages.guest.partialsGuest.footer')

    {{-- ================= SCRIPT FILTER ================= --}}
    <script>
        const searchInput = document.getElementById('searchInput');
const yearFilter = document.getElementById('yearFilter');
const monthFilter = document.getElementById('monthFilter');

function applyFilter() {
    const keyword = searchInput.value.toLowerCase();
    const year = yearFilter.value;
    const month = monthFilter.value;

    document.querySelectorAll('.renungan-item').forEach(item => {
        const title = item.dataset.title;
        const content = item.dataset.content;
        const itemYear = item.dataset.year;
        const itemMonth = item.dataset.month;

        let show = true;

        if (keyword && !title.includes(keyword) && !content.includes(keyword)) {
            show = false;
        }

        if (year && itemYear !== year) show = false;
        if (month && itemMonth !== month) show = false;

        item.style.display = show ? '' : 'none';
    });
}

searchInput.addEventListener('input', applyFilter);
yearFilter.addEventListener('change', applyFilter);
monthFilter.addEventListener('change', applyFilter);

function resetFilter() {
    searchInput.value = '';
    yearFilter.value = '';
    monthFilter.value = '';
    applyFilter();
}
    </script>

</body>

</html>