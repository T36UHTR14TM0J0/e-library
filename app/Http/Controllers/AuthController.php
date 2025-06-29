<?php

namespace App\Http\Controllers;

use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

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

        return redirect('/')->with('success', 'Berhasil keluar sistem');
    }



    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Handle forgot password request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email'             => 'required|email',
        ], [
            'email.required'    => 'Alamat email wajib diisi',
            'email.email'       => 'Format alamat email tidak valid',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        
        // Log activity
        $user = Auth::user();
        $this->logActivity(
            'Meminta kirim link lupa password : ' . $user->nama_lengkap,
            $user);

        return $status === Password::RESET_LINK_SENT
            ? back()->with("success","Berhasil mengirim link reset password")
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show password reset form
     */
    public function showResetPasswordForm($token)
    {
        $email = $_GET['email'];
        return view('auth.reset_password', ['token' => $token,'email' => $email]);
    }

    /**
     * Handle password reset request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'             => 'required',
            'email'             => 'required|email',
            'password'          => ['required', 'confirmed', Rules\Password::min(8)],
        ], [
            'token.required'    => 'Token reset password wajib ada',
            'email.required'    => 'Alamat email wajib diisi',
            'email.email'       => 'Format alamat email tidak valid',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed'=> 'Konfirmasi password tidak cocok',
            'password.min'      => 'Password minimal harus 8 karakter',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Log activity
        $user = Auth::user();
        $this->logActivity(
            'Melakukan perubahan password : ' . $user->nama_lengkap,
            $user);

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', "Berhasil membuat password baru")
            : back()->withErrors(['email' => [__($status)]]);
    }

}