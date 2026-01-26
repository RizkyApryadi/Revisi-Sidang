@extends('layouts.main')
@section('title', 'Data Jemaat')

@section('content')

@push('styles')
<style>
    /* Ensure modal titles and form labels are left-aligned and visually consistent */
    .modal .modal-title { text-align: left !important; width: auto; font-weight: 600; }
    .modal .modal-header { align-items: center; }
    /* More specific selectors to override any inherited centering */
    .modal .modal-body label,
    .modal .form-group > label,
    .modal .form-group label {
        display: block !important;
        text-align: left !important;
        font-weight: 600;
        margin-bottom: .35rem;
    }
    .modal .form-control, .modal .form-control-file, .modal textarea { margin-bottom: .5rem; }
    .modal .modal-body { padding-top: .5rem; }
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
                                <button
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditUser-{{ $user->id }}"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                
                                <!-- Edit modal for this user (pre-filled from DB) -->
                                <div class="modal fade" id="modalEditUser-{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    <span>&times;</span>
                                                
                                            </div>

                                            <form action="{{ url('admin/user/'.$user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" value="{{ $user->id }}">

                                                <div class="modal-body">

                                                    <div class="form-group mb-3">
                                                        <label style="text-align:left!important;display:block!important;">Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ (old('id') == $user->id) ? old('email') : $user->email }}">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label style="text-align:left!important;display:block!important;">Role</label>
                                                        <select class="form-control" name="role">
                                                            <option value="penatua" {{ ((old('id') == $user->id ? old('role') : $user->role) == 'penatua') ? 'selected' : '' }}>Penatua</option>
                                                            <option value="pendeta" {{ ((old('id') == $user->id ? old('role') : $user->role) == 'pendeta') ? 'selected' : '' }}>Pendeta</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label style="text-align:left!important;display:block!important;">Password (opsional)</label>
                                                        <input type="password" name="password" class="form-control"
                                                            placeholder="Kosongkan jika tidak diubah">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label style="text-align:left!important;display:block!important;">Konfirmasi Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- DELETE -->
                                <form action="{{ url('admin/user/'.$user->id) }}" method="POST"
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

<!-- ================= MODAL CREATE USER ================= -->
<div class="modal fade" id="modalCreateUser" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

                <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role">
                            <option value="">Pilih Role</option>
                            <option value="penatua">Penatua</option>
                            <option value="pendeta">Pendeta</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- =================================================== -->


<!-- global edit modal removed; using per-user modals rendered per row -->

@endsection

@push('scripts')
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
@endpush
