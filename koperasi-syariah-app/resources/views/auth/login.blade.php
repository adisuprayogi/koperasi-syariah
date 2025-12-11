@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-secondary-50 opacity-50"></div>

    <div class="relative max-w-md w-full">
        <!-- Login Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Header Section with Logo -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-8 text-center">
                @php
                    $koperasi = \App\Models\Koperasi::first();
                @endphp

                <div class="mx-auto flex items-center justify-center mb-4">
                    @if($koperasi && $koperasi->logo)
                        <img class="h-20 w-auto rounded-lg"
                             src="{{ asset('storage/' . $koperasi->logo) }}"
                             alt="{{ $koperasi->nama_koperasi }}">
                    @else
                        <div class="h-20 w-20 flex items-center justify-center rounded-full bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class="fas fa-mosque text-white text-3xl"></i>
                        </div>
                    @endif
                </div>

                @if($koperasi)
                    <h2 class="text-2xl font-bold text-white">
                        {{ $koperasi->nama_koperasi }}
                    </h2>
                    <p class="mt-2 text-sm text-primary-100">
                        Aplikasi Koperasi Syariah
                    </p>
                @else
                    <h2 class="text-2xl font-bold text-white">
                        Koperasi Syariah
                    </h2>
                    <p class="mt-2 text-sm text-primary-100">
                        Aplikasi Koperasi Syariah
                    </p>
                @endif
            </div>

            <!-- Login Form -->
            <div class="px-8 py-8">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input type="hidden" name="remember" value="true">

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                                <div>
                                    <p class="font-medium text-sm">Mohon perhatikan kesalahan berikut:</p>
                                    <ul class="list-disc list-inside text-sm mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Email/Username Field -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                            Email atau Nomor Anggota
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="login" name="login" type="text" required
                                   value="{{ old('login') }}"
                                   class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200"
                                   placeholder="Masukkan email atau nomor anggota" autocomplete="username" autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200"
                                   placeholder="Masukkan password" autocomplete="current-password">
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02]">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-primary-300 group-hover:text-white transition-colors duration-200"></i>
                            </span>
                            Masuk ke Akun
                        </button>
                    </div>

                    <!-- Register Info -->
                    <div class="text-center text-sm text-gray-600 pt-4 border-t border-gray-100">
                        <p>Belum memiliki akun? Hubungi Administrator untuk registrasi.</p>
                    </div>
                </form>

                <!-- Login Guide -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Panduan Login:</h3>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-100">
                            <span class="inline-flex items-center justify-center w-16 h-8 bg-red-500 text-white text-xs font-bold rounded-full mr-3">Admin</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Administrator</p>
                                <p class="text-xs text-gray-600">Login menggunakan email</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="inline-flex items-center justify-center w-16 h-8 bg-blue-500 text-white text-xs font-bold rounded-full mr-3">Pengurus</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pengurus Koperasi</p>
                                <p class="text-xs text-gray-600">Login menggunakan email</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="inline-flex items-center justify-center w-16 h-8 bg-green-500 text-white text-xs font-bold rounded-full mr-3">Anggota</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Anggota Koperasi</p>
                                <p class="text-xs text-gray-600">Login menggunakan nomor anggota</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
    // Auto-focus on login field
    document.addEventListener('DOMContentLoaded', function() {
        const loginField = document.getElementById('login');
        if (loginField) {
            loginField.focus();
        }
    });
</script>
@endsection
