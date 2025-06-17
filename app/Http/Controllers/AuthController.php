<?php

namespace App\Http\Controllers;

use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    use LogsActivity; 
    // Show login form
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard'); // atau route home/beranda Anda
        }
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

       

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();    
            // Membuat log
            $this->logActivity(
                'Login sistem :' . $user->nama_lengkap,
                $user,
                $request->all()
            );
        
            
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Email atau password salah');
    }

  

    // Handle logout
   public function logout(Request $request)
{
        // Dapatkan user sebelum logout
        $user = Auth::user();
        
        // Log activity
        $this->logActivity(
            'Logout sistem: ' . $user->nama_lengkap,
            $user);
        
        // Proses logout
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil keluar sistem');
    }
}