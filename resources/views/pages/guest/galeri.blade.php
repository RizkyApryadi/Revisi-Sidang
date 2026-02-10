<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Galeri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900">

    @include('pages.guest.partialsGuest.navGuest')

    <section class="pt-12 pb-14 bg-slate-50">
        <div class="w-full px-8 lg:px-24">

            <h1 class="text-3xl md:text-4xl font-bold text-left mb-4 text-slate-800">
                Galeri
            </h1>

            @if(!isset($galeris))
            @php $galeris = collect(); @endphp
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
                                @foreach($galeris->groupBy(fn($g)=>\Carbon\Carbon::parse($g->tanggal)->year) as $year => $items)
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

            {{-- ================= GRID GALERI ================= --}}
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @forelse($galeris as $g)
                <div class="galeri-item bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 border border-slate-200 overflow-hidden"
                    data-title="{{ strtolower($g->judul) }}" data-desc="{{ strtolower(strip_tags($g->deskripsi)) }}"
                    data-year="{{ \Carbon\Carbon::parse($g->tanggal)->year }}"
                    data-month="{{ \Carbon\Carbon::parse($g->tanggal)->month }}">

                    <div class="w-full h-44 bg-gray-100 overflow-hidden">
                        @php $thumb = optional($g->fotos->first())->foto; @endphp
                        @if($thumb)
                        <img src="{{ asset('storage/' . $thumb) }}" alt="{{ $g->judul }}" class="w-full h-full object-cover cursor-pointer"
                            onclick="openLightbox('{{ asset('storage/' . $thumb) }}')">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-sm text-gray-400">No Image</div>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col">
                        <h3 class="text-md font-semibold text-slate-800 mb-1">{{ $g->judul }}</h3>
                        <div class="text-xs text-slate-500 mb-2">{{ $g->tanggal ? \Carbon\Carbon::parse($g->tanggal)->translatedFormat('j F Y') : '-' }}</div>
                        <p class="text-slate-600 text-sm line-clamp-3 mb-3">{!! \Illuminate\Support\Str::limit(strip_tags($g->deskripsi), 120, '...') !!}</p>
                        <div class="mt-3">
                            <a href="{{ route('guest.galeri.show', $g->id) }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition">Lihat</a>
                        </div>
                    </div>

                </div>
                @empty
                <div class="col-span-4 text-center text-slate-600 py-12">
                    Belum ada galeri yang dipublikasikan.
                </div>
                @endforelse

            </div>

        </div>
    </section>
    @include('pages.guest.partialsGuest.footer')

    {{-- ================= LIGHTBOX ================= --}}
    <div id="lightbox" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50"
        onclick="closeLightbox()">
        <img id="lightboxImg" class="max-h-[90%] max-w-[90%] rounded-xl shadow-2xl">
    </div>

    {{-- ================= SCRIPT FILTER ================= --}}
    <script>
        const searchInput = document.getElementById('searchInput');
const yearFilter = document.getElementById('yearFilter');
const monthFilter = document.getElementById('monthFilter');

function applyFilter() {
    const keyword = searchInput.value.toLowerCase();
    const year = yearFilter.value;
    const month = monthFilter.value;

    document.querySelectorAll('.galeri-item').forEach(item => {
        const title = item.dataset.title;
        const desc = item.dataset.desc;
        const itemYear = item.dataset.year;
        const itemMonth = item.dataset.month;

        let show = true;

        if (keyword && !(title.includes(keyword) || desc.includes(keyword)))
            show = false;

        if (year && itemYear !== year)
            show = false;

        if (month && itemMonth !== month)
            show = false;

        item.style.display = show ? 'block' : 'none';
    });
}

function resetFilter(){
    searchInput.value = "";
    yearFilter.value = "";
    monthFilter.value = "";
    applyFilter();
}

searchInput.addEventListener('input', applyFilter);
yearFilter.addEventListener('change', applyFilter);
monthFilter.addEventListener('change', applyFilter);

function openLightbox(src){
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
}

function closeLightbox(){
    document.getElementById('lightbox').classList.add('hidden');
}
    </script>

</body>

</html>