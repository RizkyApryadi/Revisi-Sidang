<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penatua;
use App\Models\Pendeta;
// removed Jemaat import; not needed for user creation here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['nullable', 'in:penatua,pendeta'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'source_type' => ['nullable', 'in:penatua,pendeta'],
            'person_id' => ['nullable', 'integer'],
        ]);

        // Determine role: if a source_type is selected, role is derived from it.
        $role = $validated['role'] ?? null;
        if (!empty($validated['source_type'])) {
            $role = $validated['source_type'];
        }

        // Compute a name if not provided: use related jemaat name when available
        $name = $validated['name'] ?? null;
        if (empty($name) && !empty($validated['source_type']) && !empty($validated['person_id'])) {
            if ($validated['source_type'] === 'penatua') {
                $penatua = Penatua::with('jemaat')->find($validated['person_id']);
                if ($penatua) {
                    $name = $penatua->jemaat?->nama ?? ('Penatua ' . $penatua->id);
                }
            }

            if ($validated['source_type'] === 'pendeta') {
                $pendeta = Pendeta::with('jemaat')->find($validated['person_id']);
                if ($pendeta) {
                    $name = $pendeta->jemaat?->nama ?? ('Pendeta ' . $pendeta->id);
                }
            }
        }

        // fallback name
        if (empty($name)) {
            $name = explode('@', $validated['email'])[0];
        }

        $user = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'role' => $role ?? 'penatua',
            'password' => Hash::make($validated['password']),
        ]);

        // Attach user to the selected Penatua or Pendeta when provided
        if (!empty($validated['source_type']) && !empty($validated['person_id'])) {
            if ($validated['source_type'] === 'penatua') {
                $penatua = Penatua::find($validated['person_id']);
                if ($penatua) {
                    $penatua->user_id = $user->id;
                    $penatua->save();
                }
            }

            if ($validated['source_type'] === 'pendeta') {
                $pendeta = Pendeta::find($validated['person_id']);
                if ($pendeta) {
                    $pendeta->user_id = $user->id;
                    $pendeta->save();
                }
            }
        }

        return redirect()->route('admin.user')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display a listing of users for admin.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->get();
        // Prepare lightweight lists (id, nama_lengkap, email) for the create-user modal
        $penatuas = Penatua::with(['jemaat', 'user'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nama_lengkap' => $p->jemaat?->nama ?? null,
                'email' => $p->user?->email ?? null,
            ];
        })->values();

        $pendetas = Pendeta::with(['jemaat', 'user'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nama_lengkap' => $p->jemaat?->nama ?? null,
                'email' => $p->user?->email ?? null,
            ];
        })->values();

        return view('pages.admin.MasterData.user.index', compact('users', 'penatuas', 'pendetas'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:penatua,pendeta'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Only update fields allowed from this modal (email, role, password)
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $oldRole = $user->getOriginal('role');

        $user->save();

        // If role changed, move the detail record from the old table to the new one
        if ($oldRole !== $user->role) {
            // moving from penatua -> pendeta
            if ($oldRole === 'penatua' && $user->role === 'pendeta') {
                $old = Penatua::where('user_id', $user->id)->first();
                if ($old) {
                    // create pendeta with matching fields
                    Pendeta::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $old->nama_lengkap,
                        'jenis_kelamin' => $old->jenis_kelamin,
                        'tempat_lahir' => $old->tempat_lahir,
                        'tanggal_lahir' => $old->tanggal_lahir,
                        'alamat' => $old->alamat,
                        'no_hp' => $old->no_hp,
                        'foto' => $old->foto,
                    ]);

                    // delete old penatua record
                    $old->delete();
                }
            }

            // moving from pendeta -> penatua
            if ($oldRole === 'pendeta' && $user->role === 'penatua') {
                $old = Pendeta::where('user_id', $user->id)->first();
                if ($old) {
                    // create penatua with matching fields; wijk_id is unknown so leave null
                    Penatua::create([
                        'user_id' => $user->id,
                        'wijk_id' => null,
                        'nama_lengkap' => $old->nama_lengkap,
                        'jenis_kelamin' => $old->jenis_kelamin,
                        'tempat_lahir' => $old->tempat_lahir,
                        'tanggal_lahir' => $old->tanggal_lahir,
                        'alamat' => $old->alamat,
                        'no_hp' => $old->no_hp,
                        'foto' => $old->foto,
                    ]);

                    // delete old pendeta record
                    $old->delete();
                }
            }
        }

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // delete related foto files if exist
        if ($user->penatua && $user->penatua->foto) {
            Storage::disk('public')->delete($user->penatua->foto);
        }

        if ($user->pendeta && $user->pendeta->foto) {
            Storage::disk('public')->delete($user->pendeta->foto);
        }

        $user->delete();

        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus.');
    }
}
