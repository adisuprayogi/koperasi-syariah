<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Temporary route untuk buat user pengurus
Route::get('/create-pengurus', function() {
    // Cek apakah sudah ada
    $existing = User::where('email', 'pengurus@koperasi.local')->first();
    if ($existing) {
        return 'User pengurus sudah ada! Email: pengurus@koperasi.local, Password: password123';
    }

    $user = User::create([
        'name' => 'Pengurus Koperasi',
        'email' => 'pengurus@koperasi.local',
        'password' => Hash::make('password123'),
        'role' => 'pengurus',
        'email_verified_at' => now()
    ]);

    return 'User pengurus berhasil dibuat!<br>Email: pengurus@koperasi.local<br>Password: password123<br><a href="/login">Klik Login</a>';
});