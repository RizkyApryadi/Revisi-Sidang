@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')

@push('style')
<style>
    /* Ensure modal titles and form labels are left-aligned and visually consistent */
    .modal .modal-title {
        text-align: left !important;
        width: auto;
        font-weight: 600;
    }

    .modal .modal-header {
        align-items: center;
    }

    /* More specific selectors to override any inherited centering */
    .modal .modal-body label,
    .modal .form-group>label,
    .modal .form-group label {
        display: block !important;
        text-align: left !important;
        font-weight: 600;
        margin-bottom: .35rem;
    }

    .modal .form-control,
    .modal .form-control-file,
    .modal textarea {
        margin-bottom: .5rem;
    }

    .modal .modal-body {
        padding-top: .5rem;
    }
</style>
@endpush

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>User Account</h4>

        <!-- Button Tambah User -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
            <i class="fas fa-plus"></i> Tambah User
        </button>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->pendeta?->nama_lengkap ?? $user->penatua?->nama_lengkap ?? $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <!-- BUTTON EDIT (MODAL) -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditUser-{{ $user->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>


                                <!-- Edit modal for this user (pre-filled from DB) -->
                                <div class="modal fade" id="modalEditUser-{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                                <span>&times;</span>

                                            </div>

                                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" value="{{ $user->id }}">

                                                <div class="modal-body">

                                                    <div class="form-group mb-3">
                                                        <label
                                                            style="text-align:left!important;display:block!important;">Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ (old('id') == $user->id) ? old('email') : $user->email }}">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label
                                                            style="text-align:left!important;display:block!important;">Role</label>
                                                        <select class="form-control" name="role">
                                                            <option value="penatua" {{ ((old('id')==$user->id ?
                                                                old('role') : $user->role) == 'penatua') ? 'selected' :
                                                                '' }}>Penatua</option>
                                                            <option value="pendeta" {{ ((old('id')==$user->id ?
                                                                old('role') : $user->role) == 'pendeta') ? 'selected' :
                                                                '' }}>Pendeta</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label
                                                            style="text-align:left!important;display:block!important;">Password
                                                            (opsional)</label>
                                                        <input type="password" name="password" class="form-control"
                                                            placeholder="Kosongkan jika tidak diubah">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label
                                                            style="text-align:left!important;display:block!important;">Konfirmasi
                                                            Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control">
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- DELETE -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- CREATE USER MODAL (placed inside content so it's available in DOM) -->
<div class="modal fade" id="modalCreateUser" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label>Sumber Akun</label>
                        <select id="sourceType" name="source_type" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="penatua">Penatua</option>
                            <option value="pendeta">Pendeta</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="selectPersonWrap" style="display:none;">
                        <label id="selectPersonLabel">Pilih</label>
                        <select id="selectPerson" name="person_id" class="form-control">
                            <!-- options populated by JS -->
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" id="personEmail" name="email" class="form-control" required>
                    </div>

                    <!-- hidden fields filled by JS -->
                    <input type="hidden" id="personName" name="name">
                    <input type="hidden" id="roleInput" name="role">
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
@if(old('id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // If validation failed on update, reopen the specific user's modal (Bootstrap 5 API)
        let id = @json(old('id'));
        const el = document.getElementById('modalEditUser-' + id);
        if (el) {
            const modal = new bootstrap.Modal(el);
            modal.show();
        }
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data passed from controller (arrays of objects with id, nama_lengkap, email)
        const penatuas = @json($penatuas ?? []);
        const pendetas = @json($pendetas ?? []);

        const sourceType = document.getElementById('sourceType');
        const selectPersonWrap = document.getElementById('selectPersonWrap');
        const selectPerson = document.getElementById('selectPerson');
        const selectPersonLabel = document.getElementById('selectPersonLabel');
        const personName = document.getElementById('personName');
        const personEmail = document.getElementById('personEmail');
        const roleInput = document.getElementById('roleInput');
        const modalEl = document.getElementById('modalCreateUser');

        function clearPersonSelect() {
            selectPerson.innerHTML = '';
        }

        function populateOptions(list, labelField) {
            clearPersonSelect();
            const emptyOpt = document.createElement('option');
            emptyOpt.value = '';
            emptyOpt.text = '-- Pilih --';
            selectPerson.appendChild(emptyOpt);
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.text = item[labelField] + (item.email ? ' <' + item.email + '>' : '');
                // store json on option for convenience
                opt.dataset.item = JSON.stringify(item);
                selectPerson.appendChild(opt);
            });
        }

        sourceType?.addEventListener('change', function (e) {
            const val = e.target.value;
            if (!val) {
                selectPersonWrap.style.display = 'none';
                clearPersonSelect();
                return;
            }

            if (val === 'penatua') {
                selectPersonLabel.textContent = 'Pilih Penatua';
                populateOptions(penatuas, 'nama_lengkap');
                selectPersonWrap.style.display = '';
                if (roleInput) roleInput.value = 'penatua';
            } else if (val === 'pendeta') {
                selectPersonLabel.textContent = 'Pilih Pendeta';
                populateOptions(pendetas, 'nama_lengkap');
                selectPersonWrap.style.display = '';
                if (roleInput) roleInput.value = 'pendeta';
            } else {
                selectPersonWrap.style.display = 'none';
            }
        });

        selectPerson?.addEventListener('change', function (e) {
            const opt = e.target.selectedOptions[0];
            if (!opt || !opt.dataset.item) return;
            let item = {};
            try { item = JSON.parse(opt.dataset.item); } catch (err) { }
            if (item) {
                if (item.nama_lengkap) personName.value = item.nama_lengkap;
                if (item.email) personEmail.value = item.email;
            }
        });

        // Reset modal fields when opened
        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', function () {
                sourceType.value = '';
                clearPersonSelect();
                selectPersonWrap.style.display = 'none';
                if (personName) personName.value = '';
                if (personEmail) personEmail.value = '';
                if (roleInput) roleInput.value = 'penatua';
            });
        }
    });
</script>
@endpush