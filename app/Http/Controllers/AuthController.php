<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BannedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Check IP Ban
        $ip = $request->ip();
        if (BannedIp::where('ip_address', $ip)->exists()) {
             return back()->withErrors(['username' => 'IP Address Anda telah di-banned permanen.']);
        }

        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials, (bool) ($validated['remember'] ?? false))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Check if blocked
            if ($user->is_blocked) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['username' => 'Akun Anda sedang di Blokir oleh Administrasi. Alasan: ' . ($user->block_reason ?? '-')]);
            }

            // Update IP Address
            $user->update(['ip_address' => $ip]);

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Check IP Ban
        $ip = $request->ip();
        if (BannedIp::where('ip_address', $ip)->exists()) {
             return back()->withErrors(['username' => 'IP Address Anda telah di-banned permanen.']);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'first_name' => 'required|string|max:80',
            'last_name' => 'nullable|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email',
            'password' => 'required|string|min:3',
            'phone' => 'required|string|max:30',
            'date_of_birth' => 'required|date',
            'profile_pic' => 'nullable|image|max:2048', // 2MB Max
            'terms' => 'accepted',
        ]);

        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            // PENYELESAIAN ERROR 500 VERCEL
            if (config('app.env') === 'local') {
                // Di lokal (XAMPP), simpan gambar seperti biasa
                $file = $request->file('profile_pic');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/profile_pics'), $filename);
                $profilePicPath = 'uploads/profile_pics/' . $filename;
            } else {
                // Di Vercel, lewati penyimpanan file lokal
                $profilePicPath = null;
            }
        }

        $user = User::create([
            'name' => trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? '')),
            'email' => $validated['email'] ?? null,
            'username' => $validated['username'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'role' => 'buyer',
            'password' => $validated['password'],
            'profile_pic' => $profilePicPath,
            'ip_address' => $ip,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}