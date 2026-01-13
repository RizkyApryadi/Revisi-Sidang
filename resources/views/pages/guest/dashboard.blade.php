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

    <!-- Hero Section -->
    <section id="home"
        class="min-h-[90vh] flex items-center justify-center relative overflow-hidden bg-[linear-gradient(135deg,#1e3c72_0%,#2a5298_40%,#6dd5fa_100%)]">

        <!-- Border Box Wrapper -->
        <div class="w-full flex justify-center px-4">
            <div class="w-full max-w-[1200px] border border-white/30 rounded-2xl 
                px-6 md:px-10 lg:px-14 py-10 md:py-14 bg-white/0 backdrop-blur-sm">

                <!-- Content Wrapper -->
                <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">

                    <!-- Image -->
                    <div class="flex justify-center md:justify-start w-full md:w-[45%]">
                        <img src="{{ asset('img/Jesus.png') }}" class="max-w-[270px] md:max-w-[360px] lg:max-w-[400px] 
                           object-contain
                           mt-4 md:mt-8 lg:mt-10
                           drop-shadow-[0_0_40px_rgba(255,255,255,0.7)]
                           transition-transform duration-500 hover:scale-105">
                    </div>

                    <!-- Text -->
                    <div class="w-full md:w-[55%] text-center md:text-left text-white">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow mb-6">
                            “Aku adalah Jalan,<br> Kebenaran, dan Hidup”
                        </h1>
                        <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 leading-relaxed">
                            Tidak ada seorang pun datang kepada Bapa,<br>
                            kalau tidak melalui Aku.
                        </p>
                        <p class="text-base md:text-lg italic text-white/85">
                            — Yohanes 14:6
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Countdown Section -->
    <section class="relative bg-gradient-to-r from-yellow-100 via-yellow-50 to-yellow-100 py-10">
        <div class="max-w-[850px] mx-auto text-center px-3">

            <h2 class="text-xl md:text-2xl font-bold mb-6
        bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Hitung Mundur Menuju Tahun 2026 🎉
            </h2>

            <!-- Countdown Wrapper -->
            <div id="countdown" class="grid grid-cols-4 gap-3 justify-center">

                <!-- Card Item -->
                <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                    <span id="days" class="text-xl md:text-2xl font-extrabold text-indigo-700">0</span>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-600 mt-1">Hari</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                    <span id="hours" class="text-xl md:text-2xl font-extrabold text-indigo-700">0</span>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-600 mt-1">Jam</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                    <span id="minutes" class="text-xl md:text-2xl font-extrabold text-indigo-700">0</span>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-600 mt-1">Menit</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200">
                    <span id="seconds" class="text-xl md:text-2xl font-extrabold text-indigo-700">0</span>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-600 mt-1">Detik</p>
                </div>

            </div>

            <p class="mt-6 text-sm md:text-base text-gray-700 italic font-medium">
                “Masa depan itu dimulai dengan langkah hari ini.”
            </p>

        </div>
    </section>

    <!-- Jadwal Ibadah Section -->
    <section id="about" class="bg-slate-50 py-12 md:py-16 relative">
        <div class="max-w-[1400px] mx-auto px-8 pb-6">

            <!-- Title -->
            <h2 class="text-4xl md:text-3xl font-bold text-center mb-8 bg-clip-text text-transparent 
        bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500" style="scroll-margin-top:5rem;">
                Jadwal Ibadah Mingguan
            </h2>

            <!-- Grid (3 terbaru saja) -->
            <div id="dashboardJadwalGrid" class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                @if(isset($ibadahs) && $ibadahs->count())
                @foreach($ibadahs->take(3) as $index => $ibadah)
                <div class="relative bg-white rounded-2xl shadow-md p-6 border border-indigo-100 
                hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <!-- NEW Badge -->
                    @if($index === 0)
                    <span
                        class="absolute top-3 right-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md animate-pulse uppercase tracking-wide">
                        NEW
                    </span>
                    @endif

                    <h3 class="text-xl font-semibold text-indigo-700 mb-2">
                        {{ $ibadah->jenis ?? 'Ibadah' }}
                    </h3>

                    <p class="text-gray-600 font-medium">
                        {{ $ibadah->tanggal ? \Carbon\Carbon::parse($ibadah->tanggal)->translatedFormat('j F Y') : '-'
                        }}
                        • {{ $ibadah->waktu ?? '-' }} WIB
                    </p>

                    @if(!empty($ibadah->tema))
                    <p class="text-gray-600 mt-2 leading-relaxed">
                        <strong class="text-indigo-600">Topik:</strong>
                        {{ \Illuminate\Support\Str::limit(strip_tags($ibadah->tema), 100) }}
                    </p>
                    @elseif(!empty($ibadah->keterangan))
                    @php $k = strip_tags($ibadah->keterangan); @endphp
                    <p class="text-gray-600 mt-2 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($k, 120, '...') }}
                    </p>
                    @endif

                </div>
                @endforeach
                @else
                <div class="col-span-full text-center text-gray-600">Belum ada jadwal ibadah.</div>
                @endif
            </div>

            <!-- View all button -->
            @if(isset($ibadahs) && $ibadahs->count())
            <div class="mt-10 flex justify-center">
                <a href="{{ route('guest.jadwal') }}" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold shadow-md 
                hover:bg-indigo-700 hover:shadow-lg transition">
                    Lihat Semua Jadwal
                </a>
            </div>
            @endif

        </div>
    </section>




    <!-- Kegiatan Gereja Section (hero gradient background) -->
    <section id="kegiatan" class="bg-[linear-gradient(135deg,#1e3c72_0%,#2a5298_40%,#6dd5fa_100%)] text-white">
        <div class="max-w-[1400px] mx-auto px-8 py-14 md:py-20">
            <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-12 text-white tracking-wide drop-shadow-md">
                Kegiatan Gereja
            </h2>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-12">
                @if(isset($kegiatans) && $kegiatans->count())
                @foreach($kegiatans->take(3) as $index => $kegiatan)
                <div
                    class="relative bg-white rounded-3xl shadow-lg p-8 border border-white/20 hover:shadow-2xl hover:-translate-y-1 transition duration-300">

                    <!-- NEW hanya untuk 1 yang paling terbaru -->
                    @if($index === 0)
                    <span
                        class="absolute top-3 right-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md animate-pulse uppercase">
                        NEW
                    </span>
                    @endif

                    <h3 class="text-2xl font-bold text-indigo-700 mb-3">
                        {{ $kegiatan->judul ?? 'Kegiatan Gereja' }}
                    </h3>

                    <p class="text-gray-700 font-medium mb-1">
                        {{ $kegiatan->tanggal ? \Carbon\Carbon::parse($kegiatan->tanggal)->format('j F Y') : '-' }}
                    </p>

                    @if(!empty($kegiatan->deskripsi))
                    <p class="text-gray-600 mt-1 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit(strip_tags($kegiatan->deskripsi), 130, '...') }}
                    </p>
                    @else
                    <p class="text-gray-600 mt-1 italic">Tidak ada deskripsi.</p>
                    @endif

                </div>
                @endforeach
                @else
                <div class="col-span-full text-center text-white/90 text-lg">
                    Belum ada kegiatan.
                </div>
                @endif
            </div>

            @if(isset($kegiatans) && $kegiatans->count())
            <div class="mt-12 flex justify-center">
                <a href="{{ route('guest.kegiatan') }}"
                    class="px-6 py-3 rounded-xl bg-white text-indigo-700 font-bold hover:bg-white/90 transition">
                    Lihat Selengkapnya
                </a>
            </div>
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

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

                @if(isset($galeris) && $galeris->count())
                @foreach($galeris as $g)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-400">
                    <div class="w-full h-48 overflow-hidden">
                        <img src="{{ $g->foto_path ? asset('storage/' . $g->foto_path) : asset('images/galeri-placeholder.jpg') }}"
                            class="w-full h-48 object-cover hover:scale-110 transition-all duration-500">
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ $g->judul ?? 'Galeri' }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $g->tanggal ? \Carbon\Carbon::parse($g->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ \Illuminate\Support\Str::limit(strip_tags($g->deskripsi ?? ''), 120, '...') }}
                        </p>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-span-full text-center text-gray-600">Belum ada foto galeri.</div>
                @endif

            </div>

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



    <!-- Larger spacer to avoid bottom clipping on small screens -->
    <div class="h-28 md:h-40"></div>

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