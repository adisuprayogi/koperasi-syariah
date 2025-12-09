@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <div class="text-center mb-6">
        <i class="fas fa-key text-4xl text-indigo-600 mb-2"></i>
        <h2 class="text-2xl font-bold text-gray-900">Ubah Password</h2>
        <p class="text-gray-600 mt-2">Silakan ubah password Anda untuk keamanan akun</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
            <div class="mt-1 relative">
                <input type="password" id="current_password" name="current_password" required
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Masukkan password saat ini">
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-lock text-gray-400"></i>
                </span>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
            <div class="mt-1 relative">
                <input type="password" id="password" name="password" required minlength="8"
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Minimal 8 karakter">
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-key text-gray-400"></i>
                </span>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
            <div class="mt-1 relative">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Ketik ulang password baru">
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-check text-gray-400"></i>
                </span>
            </div>
        </div>

        <div class="flex space-x-3">
            <button type="submit"
                    class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-2"></i>Simpan Password
            </button>
            <a href="{{ route('dashboard') }}"
               class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </form>

    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Tips Password Aman:
        </h3>
        <ul class="text-xs text-blue-700 space-y-1">
            <li>• Gunakan minimal 8 karakter</li>
            <li>• Kombinasikan huruf besar dan kecil</li>
            <li>• Tambahkan angka dan simbol</li>
            <li>• Hindari menggunakan informasi pribadi</li>
        </ul>
    </div>
</div>
@endsection