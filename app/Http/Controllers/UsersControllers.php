<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersControllers extends Controller
{
    public function index()
    {
        $users = User::query()
            ->when(request('username'), function($query) {
                $query->where('username', 'like', '%'.request('username').'%');
            })
            ->when(request('npm'), function($query) {
                $query->where('npm', 'like', '%'.request('npm').'%');
            })
            ->when(request('nidn'), function($query) {
                $query->where('nidn', 'like', '%'.request('nidn').'%');
            })
            ->when(request('role'), function($query) {
                $query->where('role', request('role'));
            }, function($query) {
                if (!auth()->user()->isAdmin()) {
                    $query->where('role', '!=', 'admin');
                }
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_direction', 'desc'))
            ->paginate(request('per_page', 10));

        return view('pengaturan.user.index', compact('users'));
    }

    public function create(){
        $prodis = Prodi::all();
        return view('pengaturan.user.create', compact('prodis'));
    }

    public function store(UsersRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile-photos', 'public');
            $validated['foto'] = $path;
        }


        // Membuat pengguna baru
        User::create([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'username'      => $validated['username'],
            'password'      => Hash::make($validated['password']),
            'role'          => $validated['role'],
            'npm'           => $validated['npm'] ?? null,
            'nidn'          => $validated['nidn'] ?? null,
            'prodi_id'      => $validated['prodi_id'] ?? null,
            'foto'          => $validated['foto'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    // Menampilkan detail pengguna
    public function show(User $user)
    {
        return view('pengaturan.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $prodis = Prodi::all(); // atau query lain sesuai kebutuhan
        return view('pengaturan.user.edit', compact('user', 'prodis'));
    }

    // Memperbarui pengguna
    public function update(UsersRequest $request, User $user)
    {
        // Data sudah divalidasi oleh UsersRequest
        $validated = $request->validated();

        // Handle password update (jika diisi)
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // Hapus dari array jika tidak diubah
        }

    

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            
            $path = $request->file('foto')->store('profile-photos', 'public');
            $validated['foto'] = $path;
        }  else {
            // Pertahankan foto yang ada jika tidak ada perubahan
            unset($validated['foto']);
        }

        // Update data pengguna
        $user->update([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'username'      => $validated['username'],
            'role'          => $validated['role'],
            'npm'           => $validated['npm'] ?? null,
            'nidn'          => $validated['nidn'] ?? null,
            'prodi_id'      => $validated['prodi_id'] ?? null,
            // Password dan foto sudah dihandle di atas
        ] + $validated); // Gabungkan dengan data yang mungkin sudah diubah

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }


    public function destroy(User $user)
    {
        $user->delete(); // Hapus pengguna
        return redirect()->route('users.index')->with('success', 'Data user berhasil dihapus.');
    }
}
