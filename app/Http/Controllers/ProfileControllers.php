<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileControllers extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            
            $path = $request->file('foto')->store('profile-photos', 'public');
            $validated['foto'] = $path;
        } elseif ($request->has('remove_foto')) {
            // Hapus foto jika opsi remove_foto dicentang
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = null;
        } else {
            unset($validated['foto']);
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}