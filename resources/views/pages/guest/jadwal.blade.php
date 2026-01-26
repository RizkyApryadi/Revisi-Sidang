<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="pb-24 md:pb-32">
    @include('pages.guest.partialsGuest.navGuest')

    <!-- Jadwal Ibadah Section (copied from dashboard) -->
    <section id="about" class="bg-slate-50 pt-20 pb-12 md:pt-24 md:pb-16 relative">
        <div class="max-w-[1400px] mx-auto px-8 pb-6">

            <!-- Title -->
            <h2
                class="text-2xl md:text-3xl font-bold text-center mb-8 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500"
                style="scroll-margin-top:6rem;">
                Jadwal Ibadah Mingguan
            </h2>

            <!-- Grid Jadwal -->
            <div id="jadwalGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

                @if(isset($ibadahs) && $ibadahs->count())
                @foreach($ibadahs as $ibadah)
                <div
                    class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100 hover:shadow-xl transition duration-300">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-2">{{ optional($ibadah->warta)->nama_minggu ?? 'Ibadah' }}</h3>
                    <p class="text-gray-600">{{ optional($ibadah->warta)->tanggal ? \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->format('j F Y') : '-' }} - {{ $ibadah->waktu ?? '-' }} WIB</p>
                    @if(!empty($ibadah->tema))
                    <p class="text-gray-600">TOPIK MINGGU: {{ \Illuminate\Support\Str::limit(strip_tags($ibadah->tema),
                        100) }}</p>
                    @elseif(!empty($ibadah->keterangan))
                    @php $k = strip_tags($ibadah->keterangan); @endphp
                    <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($k, 120, '...') }}</p>
                    @endif
                </div>
                @endforeach
                @else
                <div class="col-span-full text-center text-gray-600">Belum ada jadwal ibadah.</div>
                @endif

            </div>

            <!-- Pagination -->
            @if(isset($ibadahs) && $ibadahs->hasPages())
            <div id="dashboardJadwalPagerWrap" class="mt-8 flex flex-col items-center gap-3">

                <!-- Custom Pagination Info -->
                <div class="pagination-info text-center text-sm text-gray-600">
                    Showing {{ $ibadahs->firstItem() }} to {{ $ibadahs->lastItem() }} of {{ $ibadahs->total() }} results
                </div>

                <!-- Pagination Numbers + Prev/Next -->
                <div id="dashboardJadwalPagination" class="flex justify-center mt-2">
                    {{ $ibadahs->links('pagination::tailwind') }}
                </div>

            </div>
            @endif

            <script>
                // Client-side pagination: intercept pagination clicks and replace the grid + pager
                (function () {
                    const gridContainer = document.getElementById('jadwalGrid');
                    const pagerContainer = document.getElementById('jadwalPagination');

                    if (!pagerContainer) return;

                    // Delegate click handler to the pager container
                    pagerContainer.addEventListener('click', function (e) {
                        const a = e.target.closest('a');
                        if (!a) return;
                        const href = a.getAttribute('href');
                        if (!href || href === '#') return;

                        e.preventDefault();

                        fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newGrid = doc.querySelector('#jadwalGrid');
                                const newPager = doc.querySelector('#jadwalPagination');
                                if (newGrid && gridContainer) {
                                    gridContainer.innerHTML = newGrid.innerHTML;
                                }
                                if (newPager && pagerContainer) {
                                    pagerContainer.innerHTML = newPager.innerHTML;
                                }
                                // Smooth scroll to grid
                                gridContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            })
                            .catch(err => console.error('Failed to load page:', err));
                    });
                })();
            </script>
        </div>
    </section>

    <!-- Spacer -->
    <div class="h-12 md:h-20"></div>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>