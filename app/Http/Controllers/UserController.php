<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penatua;
use App\Models\Pendeta;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:penatua,pendeta'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display a listing of users for admin.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->get();
        return view('pages.admin.MasterData.user.index', compact('users'));
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
