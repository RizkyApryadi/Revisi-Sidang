<footer class="bg-gray-900 text-white">
	<div class="max-w-[1200px] mx-auto px-6 py-10">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

			<!-- Brand -->
			<div>
				<a href="/" class="flex items-center gap-3 mb-3">
					<img src="{{ asset('img/logo.png') }}" alt="HKBP Soposurung" class="w-12 h-12 object-contain">
					<div>
						<h4 class="font-bold text-lg">HKBP Soposurung</h4>
						<p class="text-sm text-gray-300">Situs resmi jemaat HKBP Soposurung</p>
					</div>
				</a>

				<p class="text-sm text-gray-400 mt-3">Alamat: Jl. Contoh No.123, Soposurung, Sumatera</p>
				<p class="text-sm text-gray-400">Kontak: <a href="tel:+62123456789" class="underline">+62 12 3456 789</a></p>
			</div>

			<!-- Quick Links -->
			<div>
				<h5 class="font-semibold mb-3">Menu</h5>
				<ul class="space-y-2 text-gray-300">
					<li><a href="#jadwal" class="hover:text-white">Jadwal Ibadah</a></li>
					<li><a href="#berita" class="hover:text-white">Berita</a></li>
					<li><a href="#galeri" class="hover:text-white">Galeri</a></li>
					<li><a href="{{ route('pages.guest.dashboard') }}" class="hover:text-white">Beranda</a></li>
				</ul>
			</div>

			<!-- Social / Info -->
			<div>
				<h5 class="font-semibold mb-3">Ikuti Kami</h5>
				<div class="flex items-center gap-3 mb-4">
					<a href="#" class="p-2 rounded bg-white/5 hover:bg-white/10">
						<!-- Facebook SVG -->
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12H20l-1.5 2.9h-2.5v7A10 10 0 0022 12z"/></svg>
					</a>
					<a href="#" class="p-2 rounded bg-white/5 hover:bg-white/10">
						<!-- Instagram SVG -->
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 6.3A4.7 4.7 0 1016.7 13 4.7 4.7 0 0012 8.3zM18.5 6a1.1 1.1 0 11-1.1-1.1A1.1 1.1 0 0118.5 6z"/></svg>
					</a>
					<a href="#" class="p-2 rounded bg-white/5 hover:bg-white/10">
						<!-- YouTube SVG -->
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a2.9 2.9 0 00-2-2C19.6 3.6 12 3.6 12 3.6s-7.6 0-9.5.6a2.9 2.9 0 00-2 2A29.8 29.8 0 000 12a29.8 29.8 0 00.5 5.8 2.9 2.9 0 002 2c1.9.6 9.5.6 9.5.6s7.6 0 9.5-.6a2.9 2.9 0 002-2A29.8 29.8 0 0024 12a29.8 29.8 0 00-.5-5.8zM9.8 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg>
					</a>
				</div>

				<p class="text-sm text-gray-400">Email: <a href="mailto:info@hkbpsoposurung.or.id" class="underline">info@hkbpsoposurung.or.id</a></p>
			</div>

		</div>
	</div>

	<div class="border-t border-white/10">
		<div class="max-w-[1200px] mx-auto px-6 py-4 flex flex-col md:flex-row items-center justify-between text-sm text-gray-400">
			<div>© {{ date('Y') }} HKBP Soposurung. Semua hak dilindungi.</div>
		
		</div>
	</div>
</footer>
