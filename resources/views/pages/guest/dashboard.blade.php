<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="pb-0 md:pb-0">
    @include('pages.guest.partialsGuest.navGuest')

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[85vh] flex items-center justify-center text-white">

        <!-- Background HD Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('img/hd-back.jpg') }}" class="w-full h-full object-cover object-center"
                alt="Background Ibadah HD">
        </div>

        <!-- Overlay Gelap Transparan -->
        <div class="absolute inset-0 bg-black/55"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-6 max-w-4xl">

            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
                GOD GIVES US POWER
            </h1>

            <!-- Description -->
            <p class="text-base md:text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Kiranya setiap pelayanan dan firman
                Tuhan menjadi kekuatan dan pengharapan bagi setiap jemaat.
            </p>

            <a href="#berita"
                class="px-8 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold shadow-xl transition hover:scale-105">
                Lihat Berita
            </a>

        </div>

    </section>


    <section id="jadwal" class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-[1100px] mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-extrabold text-center text-gray-800 mb-3">Jadwal Ibadah</h2>
            <p class="text-center text-gray-600 mb-6">Informasi jadwal ibadah mingguan dan khusus. Klik tiap item untuk
                melihat detail.</p>

            <div class="bg-white rounded-xl shadow divide-y overflow-hidden">

                @php
                $displayCount = 5;
                $first = (isset($ibadahs) && $ibadahs->count()) ? $ibadahs->first() : null;

                // Determine newest item by tanggal + waktu/jam (fallback to created_at if missing)
                $newest = null;
                if (isset($ibadahs) && $ibadahs->count()) {
                $newest = $ibadahs->sortByDesc(function($item) {
                $date = data_get($item, 'tanggal') ?? null;
                $time = data_get($item, 'waktu') ?? data_get($item, 'jam') ?? null;
                if ($date) {
                try {
                return \Carbon\Carbon::parse(trim($date . ' ' . ($time ?? '00:00:00')))->timestamp;
                } catch (\Exception $e) {
                return 0;
                }
                }
                // fallback to created_at if no tanggal
                return data_get($item, 'created_at') ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0;
                })->first();
                }
                @endphp

                {{-- Real first item (if available) --}}
                @if($first)
                <a href="{{ route('guest.jadwal.show', $first->id) }}" class="group block">
                    <div class="flex items-center justify-between p-5 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-cyan-500 rounded-lg flex items-center justify-center">
                                ⏰
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-lg">{{ $first->nama_minggu ?? $first->nama ?? 'Ibadah'
                                        }}</h3>
                                    @if($newest && data_get($first,'id') == data_get($newest,'id'))
                                    <span
                                        class="inline-block text-xs bg-gradient-to-r from-pink-500 to-purple-600 text-white px-2 py-0.5 rounded-full uppercase">Terbaru</span>
                                    @endif
                                </div>
                                @php
                                $tanggal = data_get($first, 'tanggal') ?? data_get($first, 'warta.tanggal') ?? null;
                                @endphp
                                <p class="text-sm text-gray-500">{{ $tanggal ?
                                    \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-lg font-semibold">{{ data_get($first, 'hari') ?? (data_get($first,
                                'tanggal') ? \Carbon\Carbon::parse(data_get($first,
                                'tanggal'))->locale('id')->translatedFormat('l') : '-') }}</span>
                            <span class="text-gray-600">/ {{ $first->waktu ?
                                \Carbon\Carbon::parse($first->waktu)->format('H.i') : ($first->jam ?
                                \Carbon\Carbon::parse($first->jam)->format('H.i') : '-') }} WIB</span>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Show next items (if any) up to $displayCount, else show placeholders --}}
                @php
                $remaining = $displayCount - ($first ? 1 : 0);
                $others = (isset($ibadahs) && $ibadahs->count()) ? $ibadahs->slice(1, $remaining) : collect();
                $placeholders = $remaining - $others->count();
                @endphp

                @foreach($others as $item)
                <a href="{{ route('guest.jadwal.show', $item->id) }}" class="group block border-t">
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">⏰</div>
                            <div>
                                <h3 class="font-medium text-lg">{{ $item->nama_minggu ?? $item->nama ?? 'Ibadah' }}</h3>
                                <p class="text-sm text-gray-500">{{ data_get($item, 'tanggal') ? \Carbon\Carbon::parse(data_get($item, 'tanggal'))->locale('id')->translatedFormat('j F Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-lg font-semibold">{{ data_get($item, 'hari') ?? (data_get($item,'tanggal') ? \Carbon\Carbon::parse(data_get($item,'tanggal'))->locale('id')->translatedFormat('l') : '-') }}</span>
                            <span class="text-gray-600">/ {{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H.i') : ($item->jam ? \Carbon\Carbon::parse($item->jam)->format('H.i') : '-') }} WIB</span>
                        </div>
                    </div>
                </a>
                @endforeach

                @for($i = 0; $i < $placeholders; $i++)
                <div class="p-5 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg"></div>
                        <div>
                            <h3 class="font-medium text-lg invisible">&nbsp;</h3>
                            <p class="text-sm invisible">&nbsp;</p>
                        </div>
                    </div>

                    <div class="invisible">&nbsp;</div>
                </div>
                @endfor

        </div>
        </div>
    </section>


    <!-- Berita Section -->
    <section id="berita" class="relative text-white py-12 md:py-16">

        <!-- Background image + gradient overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('img/worship.jpg') }}" alt="Worship background" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>

        <div class="relative z-10 max-w-[1400px] mx-auto px-8">
            <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-10">
                Berita Gereja
            </h2>

            @if(isset($beritas) && $beritas->count())
            @php
            $featured = $beritas->first();
            $others = $beritas->slice(1);
            @endphp

            <!-- Featured (latest) -->
            <div class="mb-8">
                <a href="{{ route('guest.berita.show', $featured->id) }}" class="group block">
                    <div class="grid md:grid-cols-2 bg-white rounded-2xl overflow-hidden shadow-xl">

                        <!-- FOTO FEATURED (ADA SPACE HALUS) -->
                        <div class="p-3 bg-gray-100">
                            <div class="aspect-[16/9] overflow-hidden rounded-xl">
                                <img src="{{ asset('storage/'.$featured->foto) }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition">
                            </div>
                        </div>

                        <div class="p-8 text-gray-800">
                            <span class="inline-block mb-3 bg-gradient-to-r from-pink-500 to-purple-600
                            text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                Berita Terbaru
                            </span>

                            <h3 class="text-3xl font-extrabold text-indigo-700 mb-4">
                                {{ $featured->judul }}
                            </h3>

                            <p class="text-gray-500 mb-4">
                                {{ \Carbon\Carbon::parse($featured->tanggal)->format('j F Y') }}
                            </p>

                            <p class="text-gray-700 text-lg leading-relaxed">
                                {{ \Illuminate\Support\Str::limit(strip_tags($featured->ringkasan), 220) }}
                            </p>
                        </div>

                    </div>
                </a>
            </div>

            <!-- Smaller news grid -->
            @if($others->count())
            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
                @foreach($others as $b)
                <a href="{{ route('guest.berita.show', $b->id) }}" class="group block">

                    <div class="bg-white rounded-2xl shadow-lg p-6 text-gray-800 hover:shadow-2xl transition">

                        <!-- FOTO KECIL (ADA SPACE HALUS) -->
                        <div class="p-2 bg-gray-100 rounded-xl mb-4">
                            <div class="aspect-[16/9] overflow-hidden rounded-lg">
                                <img src="{{ asset('storage/'.$b->foto) }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition">
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold text-indigo-700 mb-2">
                            {{ $b->judul }}
                        </h3>

                        <p class="text-gray-500 text-sm mb-2">
                            {{ \Carbon\Carbon::parse($b->tanggal)->format('j F Y') }}
                        </p>

                        <p class="text-gray-700 text-sm">
                            {{ \Illuminate\Support\Str::limit(strip_tags($b->ringkasan), 100) }}
                        </p>

                    </div>
                </a>
                @endforeach
            </div>
            @endif

            @else
            <div class="text-center text-white">Belum ada berita.</div>
            @endif
        </div>
    </section>


    <!-- Galeri Section -->
    <section id="galeri" class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-[1400px] mx-auto px-6 pb-6">

            <h2
                class="text-4xl md:text-3xl font-bold text-center mb-8 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Galeri Gereja
            </h2>

            @if(isset($galeris) && $galeris->count())
            @php
            $latestGaleri = $galeris->sortByDesc(function($item){
            return data_get($item,'tanggal') ? \Carbon\Carbon::parse($item->tanggal)->timestamp :
            (data_get($item,'created_at') ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0);
            })->first();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($galeris->take(6) as $g)
                <a href="{{ route('guest.galeri.show', $g->id) }}" class="group block">
                    <div class="overflow-hidden rounded-xl bg-white">
                        <div class="relative h-56 w-full bg-gray-100">
                            <img src="{{ $g->foto_path ? asset('storage/' . $g->foto_path) : asset('images/galeri-placeholder.jpg') }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @if($latestGaleri && data_get($g,'id') == data_get($latestGaleri,'id'))
                            <div
                                class="absolute left-3 top-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                Terbaru</div>
                            @endif

                            <div
                                class="absolute left-0 right-0 bottom-0 bg-black/40 text-white px-3 py-2 backdrop-blur-sm">
                                <h3 class="text-sm font-semibold truncate">{{ $g->judul ?? 'Galeri' }}</h3>
                                <p class="text-xs text-white/90">{{ $g->tanggal ?
                                    \Carbon\Carbon::parse($g->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="col-span-full text-center text-gray-600">Belum ada foto galeri.</div>
            @endif

            @if(isset($galeris) && $galeris->count())
            <div class="mt-10 flex justify-center">
                <a href="{{ route('guest.galeri') }}" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold shadow-md 
                hover:bg-indigo-700 hover:shadow-lg transition">
                    Lihat Selengkapnya
                </a>
            </div>
            @endif

        </div>
    </section>



    @include('pages.guest.partialsGuest.footer')


    <script>
        function updateCountdown() {
        const newYear = new Date("Jan 1, 2026 00:00:00").getTime();
        const now = new Date().getTime();
        const diff = newYear - now;

        if (diff < 0) {
            document.getElementById("countdown").innerHTML =
                "<h3 class='text-3xl font-bold text-indigo-600'>Selamat Tahun Baru 2026 🎆</h3>";
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((diff / (1000 * 60)) % 60);
        const seconds = Math.floor((diff / 1000) % 60);

        document.getElementById("days").innerText = days;
        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
    </script>

</body>

</html>