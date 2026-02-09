<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="pb-0 md:pb-0"> {{-- Tambahkan padding top untuk menghindari overlap nav --}}
    @include('pages.guest.partialsGuest.navGuest')

    <!-- Layanan Gereja Section -->
    <section id="layanan" class="py-12 md:py-16">
        <div class="max-w-[1200px] mx-auto px-6 pb-6">

            <h2
                class="text-4xl md:text-3xl font-bold text-center mb-8 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Layanan Gereja
            </h2>

            <div class="flex flex-wrap justify-center gap-6 md:gap-8">

                <!-- Card Layanan -->
                <a href="{{ route('guest.layanan.baptisan') }}" class="group bg-yellow-50 border border-yellow-200 shadow-md rounded-2xl p-6 text-center w-full max-w-[220px] flex-shrink-0
                hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">✝️</div>
                    <h3 class="font-bold text-gray-800">Baptisan</h3>
                </a>



                <a href="{{ route('guest.layanan.pernikahan') }}" class="group bg-yellow-50 border border-yellow-200 shadow-md rounded-2xl p-6 text-center w-full max-w-[220px] flex-shrink-0
                hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">💍</div>
                    <h3 class="font-bold text-gray-800">Pernikahan</h3>
                </a>

                <a href="{{ route('guest.layanan.pindah') }}" class="group bg-yellow-50 border border-yellow-200 shadow-md rounded-2xl p-6 text-center w-full max-w-[220px] flex-shrink-0
                hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">📜</div>
                    <h3 class="font-bold text-gray-800">Pindah</h3>
                </a>

                <a href="{{ route('guest.layanan.sidi') }}" class="group bg-yellow-50 border border-yellow-200 shadow-md rounded-2xl p-6 text-center w-full max-w-[220px] flex-shrink-0
                hover:shadow-xl hover:-translate-y-2 transition duration-300">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">⛪</div>
                    <h3 class="font-bold text-gray-800">Sidi</h3>
                </a>

            </div>

        </div>
    </section>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>