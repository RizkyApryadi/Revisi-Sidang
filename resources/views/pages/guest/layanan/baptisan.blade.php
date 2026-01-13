<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKBP Soposurung - Galeri</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

    <body class="pt-24 bg-gray-50 text-gray-900"> {{-- pad top to avoid fixed nav overlap --}}
    @include('pages.guest.partialsGuest.navGuest')

    <div class="max-w-5xl mx-auto px-4">

        {{-- HEADER --}}
        <div class="bg-indigo-700 text-white p-2 mb-3 flex justify-between items-center rounded">
            <strong>HURIA KRISTEN BATAK PROTESTAN</strong>
            <span>{{ now()->translatedFormat('l, d M Y') }}</span>
        </div>

        {{-- FORM UTAMA --}}
        <div class="bg-white border border-green-500 rounded-md shadow-sm">
            <div class="bg-green-600 text-white text-center py-2 rounded-t-md font-semibold">
                Formulir Pendaftaran Baptisan Kudus
            </div>

            <div class="p-4">

                <p class="mb-4">Kami yang bertanda tangan dibawah ini :</p>

                {{-- DATA ORANG TUA --}}
                <div class="flex flex-wrap mb-2 items-center">
                    <label class="w-full md:w-1/6 font-medium">Nama Ayah</label>
                    <div class="w-full md:w-5/6">
                        <input type="text" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="flex flex-wrap mb-2 items-center">
                    <label class="w-full md:w-1/6 font-medium">Nama Ibu</label>
                    <div class="w-full md:w-5/6">
                        <input type="text" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="flex flex-wrap mb-2 items-center">
                    <label class="w-full md:w-1/6 font-medium">HP</label>
                    <div class="w-full md:w-5/6">
                        <input type="text" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="flex flex-wrap mb-2 items-center">
                    <label class="w-full md:w-1/6 font-medium">Email</label>
                    <div class="w-full md:w-5/6">
                        <input type="email" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="flex flex-wrap mb-2 items-center">
                    <label class="w-full md:w-1/6 font-medium">Wijk</label>
                    <div class="w-full md:w-5/6">
                        <select class="block w-full border border-gray-300 rounded-md p-2">
                            <option>-</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap mb-3 items-start">
                    <label class="w-full md:w-1/6 font-medium">Alamat</label>
                    <div class="w-full md:w-5/6">
                        <textarea class="w-full border border-gray-300 rounded-md p-2"></textarea>
                    </div>
                </div>

                {{-- TANGGAL KEBAKTIAN --}}
                <div class="flex flex-wrap mb-3 items-center">
                    <label class="w-full md:w-1/6 font-medium">Tgl. Kebaktian</label>
                    <div class="w-full md:w-1/3">
                        <input type="date" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                {{-- DATA ANAK --}}
                <div id="children-section">
                    <div class="bg-blue-100 border border-blue-200 text-blue-800 p-3 rounded-md mb-3">
                        Data Anak yang akan menerima Sakramen Baptisan Kudus
                    </div>

                    <div id="children-list">
                        @if(old('children'))
                            @foreach(old('children') as $idx => $child)
                                <div class="child-block border border-gray-200 p-3 mb-3 rounded" data-child-index="{{ $idx }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <strong>{{ $loop->index + 1 }}.</strong>
                                        <button type="button" class="remove-child-btn text-sm text-red-600 hover:underline">Hapus</button>
                                    </div>

                                    <div class="flex flex-wrap mb-2 items-center">
                                        <label class="w-full md:w-1/6 font-medium">Nama Anak</label>
                                        <div class="w-full md:w-5/6">
                                            <input data-field="name" name="children[{{ $idx }}][name]" value="{{ $child['name'] ?? '' }}" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap mb-2 items-center">
                                        <label class="w-full md:w-1/6 font-medium">Tgl. Lahir</label>
                                        <div class="w-full md:w-1/3">
                                            <input data-field="dob" name="children[{{ $idx }}][dob]" value="{{ $child['dob'] ?? '' }}" type="date" class="w-full border border-gray-300 rounded-md p-2">
                                        </div>

                                        <label class="w-full md:w-1/6 font-medium">Jenis Kelamin</label>
                                        <div class="w-full md:w-1/3">
                                            <select data-field="gender" name="children[{{ $idx }}][gender]" class="block w-full border border-gray-300 rounded-md p-2">
                                                <option value="Laki-laki" {{ (isset($child['gender']) && $child['gender'] == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ (isset($child['gender']) && $child['gender'] == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap">
                                        <label class="w-full md:w-1/6 font-medium">Tempat Lahir</label>
                                        <div class="w-full md:w-5/6">
                                            <input data-field="place" name="children[{{ $idx }}][place]" value="{{ $child['place'] ?? '' }}" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="child-block border border-gray-200 p-3 mb-3 rounded" data-child-index="0">
                                <div class="flex justify-between items-center mb-2">
                                    <strong>1.</strong>
                                    <button type="button" class="remove-child-btn text-sm text-red-600 hover:underline">Hapus</button>
                                </div>

                                <div class="flex flex-wrap mb-2 items-center">
                                    <label class="w-full md:w-1/6 font-medium">Nama Anak</label>
                                    <div class="w-full md:w-5/6">
                                        <input data-field="name" name="children[0][name]" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                    </div>
                                </div>

                                <div class="flex flex-wrap mb-2 items-center">
                                    <label class="w-full md:w-1/6 font-medium">Tgl. Lahir</label>
                                    <div class="w-full md:w-1/3">
                                        <input data-field="dob" name="children[0][dob]" type="date" class="w-full border border-gray-300 rounded-md p-2">
                                    </div>

                                    <label class="w-full md:w-1/6 font-medium">Jenis Kelamin</label>
                                    <div class="w-full md:w-1/3">
                                        <select data-field="gender" name="children[0][gender]" class="block w-full border border-gray-300 rounded-md p-2">
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex flex-wrap">
                                    <label class="w-full md:w-1/6 font-medium">Tempat Lahir</label>
                                    <div class="w-full md:w-5/6">
                                        <input data-field="place" name="children[0][place]" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-2">
                        <button type="button" id="add-child-btn" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Anak</button>
                    </div>

                    <template id="child-template">
                        <div class="child-block border border-gray-200 p-3 mb-3 rounded" data-child-index="__INDEX__">
                            <div class="flex justify-between items-center mb-2">
                                <strong>__NUM__.</strong>
                                <button type="button" class="remove-child-btn text-sm text-red-600 hover:underline">Hapus</button>
                            </div>

                            <div class="flex flex-wrap mb-2 items-center">
                                <label class="w-full md:w-1/6 font-medium">Nama Anak</label>
                                <div class="w-full md:w-5/6">
                                    <input data-field="name" name="children[__INDEX__][name]" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                </div>
                            </div>

                            <div class="flex flex-wrap mb-2 items-center">
                                <label class="w-full md:w-1/6 font-medium">Tgl. Lahir</label>
                                <div class="w-full md:w-1/3">
                                    <input data-field="dob" name="children[__INDEX__][dob]" type="date" class="w-full border border-gray-300 rounded-md p-2">
                                </div>

                                <label class="w-full md:w-1/6 font-medium">Jenis Kelamin</label>
                                <div class="w-full md:w-1/3">
                                    <select data-field="gender" name="children[__INDEX__][gender]" class="block w-full border border-gray-300 rounded-md p-2">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-wrap">
                                <label class="w-full md:w-1/6 font-medium">Tempat Lahir</label>
                                <div class="w-full md:w-5/6">
                                    <input data-field="place" name="children[__INDEX__][place]" type="text" class="w-full border border-gray-300 rounded-md p-2">
                                </div>
                            </div>
                        </div>
                    </template>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const list = document.getElementById('children-list');
                            const addBtn = document.getElementById('add-child-btn');
                            const template = document.getElementById('child-template').innerHTML;

                            function reindex() {
                                const blocks = list.querySelectorAll('.child-block');
                                blocks.forEach((block, i) => {
                                    block.setAttribute('data-child-index', i);
                                    // update displayed number
                                    const strong = block.querySelector('strong');
                                    if (strong) strong.textContent = (i + 1) + '.';
                                    // update input names
                                    block.querySelectorAll('[data-field]').forEach(input => {
                                        const field = input.getAttribute('data-field');
                                        input.name = `children[${i}][${field}]`;
                                    });
                                });
                                updateRemoveButtons();
                            }

                            function updateRemoveButtons() {
                                const blocks = list.querySelectorAll('.child-block');
                                const removeBtns = list.querySelectorAll('.remove-child-btn');
                                removeBtns.forEach(btn => btn.disabled = blocks.length <= 1);
                            }

                            function removeHandler(e) {
                                const block = e.currentTarget.closest('.child-block');
                                if (!block) return;
                                block.remove();
                                reindex();
                            }

                            // attach handlers to existing remove buttons
                            list.querySelectorAll('.remove-child-btn').forEach(b => b.addEventListener('click', removeHandler));

                            addBtn.addEventListener('click', function () {
                                const idx = list.querySelectorAll('.child-block').length;
                                let html = template.replace(/__INDEX__/g, idx).replace(/__NUM__/g, idx + 1);
                                const temp = document.createElement('div');
                                temp.innerHTML = html;
                                const node = temp.firstElementChild;
                                list.appendChild(node);
                                // attach remove handler for new node
                                node.querySelectorAll('.remove-child-btn').forEach(b => b.addEventListener('click', removeHandler));
                                reindex();
                            });

                            updateRemoveButtons();
                        });
                    </script>
                </div>

            {{-- KELENGKAPAN --}}
            <div class="bg-blue-100 border border-blue-200 text-blue-800 p-3 rounded-md mt-2">
                Kelengkapan Administrasi
            </div>

            <div class="flex flex-wrap mb-2 items-center">
                <label class="w-full md:w-1/4 font-medium">Akta Pernikahan</label>
                <div class="w-full md:w-3/4">
                    <input type="file" class="w-full">
                </div>
            </div>

            <div class="flex flex-wrap mb-2 items-center">
                <label class="w-full md:w-1/4 font-medium">Surat Pengantar Sintua</label>
                <div class="w-full md:w-3/4">
                    <input type="file" class="w-full">
                </div>
            </div>

            <div class="flex flex-wrap mb-4 items-center">
                <label class="w-full md:w-1/4 font-medium">Surat Pengantar Jemaat HKBP Lain</label>
                <div class="w-full md:w-3/4">
                    <input type="file" class="w-full">
                </div>
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-end gap-3 mt-4">
                <a href="#" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">KEMBALI</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">KIRIM</button>
            </div>

        </div>
    </div>

    @include('pages.guest.partialsGuest.footer')

</body>

</html>




</div>