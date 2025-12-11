@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-secondary-50 opacity-50"></div>

    <div class="relative max-w-md w-full">
        <!-- Change Password Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-6 text-center">
                <div class="mx-auto flex items-center justify-center mb-3">
                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-white bg-opacity-20 backdrop-blur-sm">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white">
                    Ubah Password
                </h2>
                <p class="mt-1 text-sm text-primary-100">
                    Perbarui keamanan akun Anda dengan password baru
                </p>
            </div>

            <!-- Form Content -->
            <div class="px-8 py-8">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-4 rounded-lg mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mt-0.5 mr-3"></i>
                            <div>
                                <p class="font-medium text-sm">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="current_password" name="current_password" required
                                   class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200"
                                   placeholder="Masukkan password saat ini">
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required minlength="8"
                                   class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200"
                                   placeholder="Minimal 8 karakter">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400">
                                <span id="password-strength" class="hidden"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-check text-gray-400"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200"
                                   placeholder="Ketik ulang password baru">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button type="submit"
                                class="group relative flex-1 flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02]">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-save text-primary-300 group-hover:text-white transition-colors duration-200"></i>
                            </span>
                            Simpan Password
                        </button>
                        <a href="{{ route('dashboard') }}"
                           class="group relative flex-1 flex justify-center py-3 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 text-center transition-all duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-arrow-left text-gray-400 group-hover:text-primary-600 transition-colors duration-200"></i>
                            </span>
                            Batal
                        </a>
                    </div>
                </form>

                <!-- Security Tips -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-blue-600 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-900 mb-3">Tips Password Aman:</h3>
                            <ul class="text-xs text-blue-700 space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                    <span>Minimal 8 karakter dengan kombinasi huruf besar, angka, dan simbol</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                    <span>Gunakan informasi yang tidak mudah ditebak (nama, tanggal lahir, dll)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                    <span>Hindari menggunakan password yang sama dengan akun lain</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                    <span>Ubah password secara berkala (minimal 3 bulan sekali)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="text-center mt-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script>
    // Password strength checker
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('password-strength');
        const confirmInput = document.getElementById('password_confirmation');

        if (passwordInput && strengthIndicator) {
            passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let strengthText = '';
                    let strengthColor = '';

                    if (password.length >= 8) strength++;
                    if (password.match(/[a-z]/)) strength++;
                    if (password.match(/[A-Z]/)) strength++;
                    if (password.match(/[0-9]/)) strength++;
                    if (password.match(/[^a-zA-Z0-9]/)) strength++;

                    if (strength <= 2) {
                        strengthText = 'Lemah';
                        strengthColor = 'text-red-500';
                    } else if (strength <= 3) {
                        strengthText = 'Sedang';
                        strengthColor = 'text-yellow-500';
                    } else {
                        strengthText = 'Kuat';
                        strengthColor = 'text-green-500';
                    }

                    strengthIndicator.textContent = strengthText;
                    strengthIndicator.className = strengthColor;
                    strengthIndicator.classList.remove('hidden');
                });

                passwordInput.addEventListener('blur', function() {
                    if (this.value.length === 0) {
                        strengthIndicator.classList.add('hidden');
                    }
                });
            }

        // Password confirmation validation
        if (confirmInput && passwordInput) {
            confirmInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak cocok!');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    });
</script>
@endsection