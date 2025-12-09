<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Check if login is email or username/nomor anggota
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Create credentials array
        $authCredentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($authCredentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();

            // Check if first login
            if ($user->first_login) {
                // Update first_login to false
                $user->first_login = false;
                $user->save();

                // Redirect to change password page
                return redirect()->route('password.change')
                    ->with('info', 'Ini adalah login pertama Anda. Silakan ubah password Anda.');
            }

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'pengurus':
                    return redirect()->route('pengurus.dashboard');
                case 'anggota':
                    return redirect()->route('anggota.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'login' => 'Email/Username atau password salah.',
        ])->onlyInput('login');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Handle change password request
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route($this->getDashboardRoute())
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Get dashboard route based on user role
     */
    private function getDashboardRoute()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return 'admin.dashboard';
            case 'pengurus':
                return 'pengurus.dashboard';
            case 'anggota':
                return 'anggota.dashboard';
            default:
                return 'home';
        }
    }
}
