@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            @php
                $koperasi = \App\Models\Koperasi::first();
            @endphp
            <div class="mx-auto flex items-center justify-center">
                @if($koperasi && $koperasi->logo)
                    <img class="h-20 w-auto"
                         src="{{ asset('storage/' . $koperasi->logo) }}"
                         alt="{{ $koperasi->nama_koperasi }}">
                @else
                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-indigo-100">
                        <i class="fas fa-mosque text-indigo-600 text-xl"></i>
                    </div>
                @endif
            </div>
            @if($koperasi)
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ $koperasi->nama_koperasi }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Aplikasi Koperasi Syariah
                </p>
            @else
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Masuk ke Aplikasi Koperasi Syariah
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Atau
                    <a href="{{ route('home') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        kembali ke beranda
                    </a>
                </p>
            @endif
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <input type="hidden" name="remember" value="true">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="login" class="sr-only">Email atau Username</label>
                    <input id="login" name="login" type="text" required
                           value="{{ old('login') }}"
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Email atau Username" autocomplete="username" autofocus>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Password" autocomplete="current-password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Lupa password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Masuk
                </button>
            </div>

            <div class="text-center text-sm text-gray-600">
                <p>Belum memiliki akun? Hubungi Administrator untuk registrasi.</p>
            </div>
        </form>

        <div class="mt-6 border-t border-gray-200 pt-6">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Informasi Login:</h3>
            <div class="space-y-2 text-xs text-gray-600">
                <div class="flex items-center">
                    <span class="inline-block w-12 h-6 bg-red-100 text-red-800 rounded-full text-center mr-2">Admin</span>
                    <span>Manajer sistem (login dengan email)</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-12 h-6 bg-blue-100 text-blue-800 rounded-full text-center mr-2">Pengurus</span>
                    <span>Pengurus koperasi (login dengan email)</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-12 h-6 bg-green-100 text-green-800 rounded-full text-center mr-2">Anggota</span>
                    <span>Anggota koperasi (login dengan nomor anggota)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
