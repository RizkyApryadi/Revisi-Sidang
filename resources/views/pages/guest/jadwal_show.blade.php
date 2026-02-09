<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Jadwal </title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
    @include('pages.guest.partialsGuest.navGuest')

    <main class="container mx-auto py-10 px-4">
        <div class="max-w-5xl mx-auto bg-white rounded-lg shadow p-6">
            <div class="flex gap-6">
                <!-- Left buttons column -->
                <div class="w-40 flex-shrink-0">
                    <div class="flex flex-col space-y-3">
                        <button id="btn-jadwal"
                            class="js-switch-btn text-left px-3 py-2 rounded bg-indigo-600 text-white font-semibold">Jadwal</button>
                        <button id="btn-warta"
                            class="js-switch-btn text-left px-3 py-2 rounded bg-white text-gray-700 border">Warta</button>
                    </div>
                </div>

                <!-- Right content column -->
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 id="title-main" class="text-2xl font-bold text-indigo-700">{{
                                optional($ibadah->warta)->nama_minggu ?? 'Ibadah' }}</h2>
                            <p id="subtitle-main" class="text-sm text-gray-500">{{ optional($ibadah->warta)->tanggal ?
                                \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->translatedFormat('j F Y') :
                                '-' }} • {{ $ibadah->waktu ?? '-' }} WIB</p>
                        </div>
                        <a href="{{ route('guest.jadwal') }}" class="text-sm text-indigo-600">Kembali ke Jadwal</a>
                    </div>

                    <!-- Jadwal panel (default) -->
                    <div id="panel-jadwal">

                        @if(!empty($ibadah->pdf_text))
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-2">Teks PDF</h3>
                            <div class="prose max-w-none text-gray-800">
                                {!! nl2br(e($ibadah->pdf_text)) !!}
                            </div>
                        </div>
                        @endif

                        @if(!empty($ibadah->tema))
                        <div class="mb-4">
                            <h3 class="font-semibold">Tema</h3>
                            <p class="text-gray-700">{{ $ibadah->tema }}</p>
                        </div>
                        @endif

                        @if(!empty($ibadah->keterangan))
                        <div class="mb-4">
                            <h3 class="font-semibold">Keterangan</h3>
                            <p class="text-gray-700">{!! nl2br(e($ibadah->keterangan)) !!}</p>
                        </div>
                        @endif

                        <div class="mb-4">
                            <h3 class="font-semibold">Pendeta</h3>
                            <p class="text-gray-700">{{ optional($ibadah->pendeta)->nama_lengkap ?? '-' }}</p>
                        </div>

                        @if(!empty($ibadah->lokasi) || !empty($ibadah->keterangan_tambahan))
                        <div class="mb-4">
                            @if(!empty($ibadah->lokasi))
                            <div class="text-sm text-gray-600"><strong>Lokasi:</strong> {{ $ibadah->lokasi }}</div>
                            @endif
                            @if(!empty($ibadah->keterangan_tambahan))
                            <div class="text-sm text-gray-600"><strong>Catatan:</strong> {{ $ibadah->keterangan_tambahan
                                }}</div>
                            @endif
                        </div>
                        @endif
                            {{-- Pelayan Ibadah table (above PDF/file viewer) --}}
                            @php
                            $pelayanRaw = $ibadah->pelayan_ibadah ?? $ibadah->pelayans ?? $ibadah->pelayan ?? null;
                            @endphp

                            {{-- pelayan payload (debug removed) --}}

                            {{-- Fast path: if controller attached a collection of pelayan rows, render directly --}}
                            @if(!empty($pelayanRaw) && (is_array($pelayanRaw) || (is_object($pelayanRaw) && method_exists($pelayanRaw, 'count') && $pelayanRaw->count() > 0)))
                            <div class="mb-4">
                                <h3 class="font-semibold">Pelayan Ibadah</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 border">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Peran</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($pelayanRaw as $p)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ $p->jenis_pelayanan ?? $p->jenis ?? '-' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-800">{{ $p->petugas ?? $p->nama ?? (is_string($p) ? $p : json_encode($p)) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            {{-- Fallback parsing for strings/arrays --}}
                            @php
                            $pelayanArr = [];
                            if(is_string($pelayanRaw) && trim($pelayanRaw) !== ''){
                                $json = json_decode($pelayanRaw, true);
                                if(json_last_error() === JSON_ERROR_NONE && is_array($json)){
                                    $pelayanArr = $json;
                                } else {
                                    $pelayanArr = array_filter(array_map('trim', preg_split('/\r\n|\n|,|;/u', $pelayanRaw)));
                                }
                            } elseif(is_array($pelayanRaw)){
                                $pelayanArr = $pelayanRaw;
                            }
                            @endphp

                            @if(!empty($pelayanArr))
                            <div class="mb-4">
                                <h3 class="font-semibold">Pelayan Ibadah</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 border">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Peran</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($pelayanArr as $p)
                                            @php
                                            $role = null; $name = null;
                                            if(is_array($p)){
                                                $role = $p['jenis_pelayanan'] ?? $p['jenis'] ?? $p['peran'] ?? $p['role'] ?? null;
                                                $name = $p['petugas'] ?? $p['nama'] ?? $p['name'] ?? $p['label'] ?? null;
                                            } else {
                                                $parts = preg_split('/\s*[:\-\|]\s*/u', trim($p), 2);
                                                if(count($parts) === 2){ $role = $parts[0]; $name = $parts[1]; }
                                                else { $role = '-'; $name = $parts[0] ?? (string)$p; }
                                            }
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ $role ?? '-' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-800">{{ $name ?? (is_string($p) ? $p : json_encode($p)) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            @endif

                            @if(!empty($ibadah->file))
                        <div class="mb-6">
                            <div class="text-sm text-gray-600 mb-2">File:</div>
                            <div id="pdf-container" class="w-full rounded overflow-hidden border" style="height:auto;">
                                <iframe id="iframe-pdf" src="{{ route('guest.jadwal.file', $ibadah->id) }}"
                                    class="w-full h-full" style="height:100%;" frameborder="0"></iframe>
                            </div>
                        </div>
                        @else
                        <div class="mb-6 text-gray-600">Tidak ada file jadwal tersedia.</div>
                        @endif

                    </div>

                    <!-- Warta panel (hidden by default) -->
                    <div id="panel-warta" class="hidden">
                        @php
                        $warta = optional($ibadah->warta);
                        $wartaContent = $warta->pengumuman ?? $warta->deskripsi ?? $warta->isi ?? $warta->ringkasan ??
                        $warta->konten ?? $warta->body ?? null;
                        @endphp

                        @if($warta)
                        <div id="warta-container" class="mb-3 rounded overflow-hidden border" style="height:auto;">
                            <div id="warta-scroll" class="p-4 h-full" style="height:100%; overflow:auto;">
                                <div class="mb-3">
                                    <h3 class="font-semibold text-lg">Warta: {{ $warta->nama_minggu }}</h3>
                                    <p class="text-sm text-gray-500">Tanggal: {{ $warta->tanggal ?
                                        \Carbon\Carbon::parse($warta->tanggal)->translatedFormat('j F Y') : '-' }}</p>
                                </div>

                                @if(!empty($wartaContent))
                                @php
                                $content = $wartaContent;
                                $isHtml = preg_match('/<[^>]+>/', $content);
                                    @endphp

                                    <div class="text-gray-800">
                                        @if($isHtml)
                                        {!! $content !!}
                                        @else
                                        @php
                                        $paras = preg_split('/\r\n\r\n|\n\n|\r\r/', $content);
                                        @endphp
                                        @foreach($paras as $p)
                                        <p class="mb-3">{!! nl2br(e(trim($p))) !!}</p>
                                        @endforeach
                                        @endif
                                    </div>
                                    @else
                                    <div class="text-gray-600">Tidak ada isi warta untuk ditampilkan.</div>
                                    @endif

                            </div>
                        </div>
                        @else
                        <div class="text-gray-600">Tidak ada warta terkait.</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('pages.guest.partialsGuest.footer')

    <script>
        (function(){
        const btnJadwal = document.getElementById('btn-jadwal');
        const btnWarta = document.getElementById('btn-warta');
        const panelJadwal = document.getElementById('panel-jadwal');
        const panelWarta = document.getElementById('panel-warta');
        const titleMain = document.getElementById('title-main');
        const subtitleMain = document.getElementById('subtitle-main');

        const jadwalTitle = {!! json_encode(optional($ibadah->warta)->nama_minggu ?? 'Ibadah') !!};
        const jadwalSubtitle = {!! json_encode((optional($ibadah->warta)->tanggal ? \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->translatedFormat('j F Y') : '-') . ' • ' . ($ibadah->waktu ?? '-') . ' WIB') !!};

        const wartaTitle = {!! json_encode(optional($ibadah->warta)->nama_minggu ?? 'Warta') !!};
        const wartaSubtitle = {!! json_encode(optional($ibadah->warta)->tanggal ? \Carbon\Carbon::parse(optional($ibadah->warta)->tanggal)->translatedFormat('j F Y') : '-') !!};

        function setActive(btn){
            document.querySelectorAll('.js-switch-btn').forEach(b=>{
                b.classList.remove('bg-indigo-600','text-white','border');
                b.classList.add('bg-white','text-gray-700','border');
            });
            btn.classList.remove('bg-white','text-gray-700','border');
            btn.classList.add('bg-indigo-600','text-white');
        }

        btnJadwal.addEventListener('click', function(){
            setActive(btnJadwal);
            panelJadwal.classList.remove('hidden');
            panelWarta.classList.add('hidden');
            titleMain.innerText = jadwalTitle;
            subtitleMain.innerText = jadwalSubtitle;
        });

        btnWarta.addEventListener('click', function(){
            setActive(btnWarta);
            panelJadwal.classList.add('hidden');
            panelWarta.classList.remove('hidden');
            titleMain.innerText = wartaTitle;
            subtitleMain.innerText = wartaSubtitle;
        });

        // adjust PDF and Warta container heights to fit viewport responsively
        function adjustPdfHeight(){
            const pdfContainer = document.getElementById('pdf-container');
            if(!pdfContainer) return;
            const rect = pdfContainer.getBoundingClientRect();
            const top = rect.top;
            const margin = 12; // bottom margin
            // responsive minimums
            const w = window.innerWidth;
            let minHeight;
            if(w < 640) minHeight = 320; // small devices
            else if(w < 1024) minHeight = 520; // tablets
            else minHeight = 740; // desktop

            const available = window.innerHeight - top - margin;
            const h = Math.max(minHeight, available);
            pdfContainer.style.height = h + 'px';
            // sync warta container height so only warta content scrolls
            const wartaContainer = document.getElementById('warta-container');
            if(wartaContainer){
                wartaContainer.style.height = h + 'px';
            }
        }

        // recalc after toggles
        btnJadwal.addEventListener('click', function(){
            setTimeout(adjustPdfHeight, 50);
        });

        btnWarta.addEventListener('click', function(){
            setTimeout(adjustPdfHeight, 50);
        });

        // scroll container into view (leave small offset for header)
        function scrollToContainer(id){
            const el = document.getElementById(id);
            if(!el) return;
            const rect = el.getBoundingClientRect();
            const offset = 80; // space from top for header/title
            const target = window.scrollY + rect.top - offset;
            window.scrollTo({ top: Math.max(0, target), behavior: 'smooth' });
        }

        // scroll when switching panels
        btnJadwal.addEventListener('click', function(){
            setTimeout(()=>scrollToContainer('pdf-container'), 120);
        });

        btnWarta.addEventListener('click', function(){
            setTimeout(()=>scrollToContainer('warta-container'), 120);
        });

        // initialize
        setActive(btnJadwal);
        adjustPdfHeight();

        // keep it responsive
        window.addEventListener('resize', adjustPdfHeight);
    })();
    </script>

</body>

</html>