@extends('layouts.app')

@section('title', 'Pengaturan Kartu Anggota')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Kartu Anggota</h1>
        <p class="text-gray-600">Kelola tampilan kartu anggota Koperasi Syariah</p>
    </div>

    <form method="POST" action="{{ route('admin.kartu-anggota.settings.update') }}" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Hidden fields for essential data -->
            <input type="hidden" name="nama_koperasi" value="{{ $settings->nama_koperasi ?? 'Koperasi Syariah' }}">
            <input type="hidden" name="nama_ketua" value="{{ $settings->nama_ketua ?? 'Nama Ketua' }}">
            <input type="hidden" name="jabatan_ketua" value="{{ $settings->jabatan_ketua ?? 'Ketua' }}">
            <input type="hidden" name="syarat_ketentuan" value="{{ $settings->syarat_ketentuan ?? 'Kartu ini berlaku sebagai identitas resmi anggota koperasi.' }}">

            <div class="lg:col-span-3">
                <!-- Card Preview -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Preview Kartu</h3>

                    <!-- Tab Navigation -->
                    <div class="flex mb-4 border-b">
                        <button id="frontTab" class="px-4 py-2 text-blue-600 border-b-2 border-blue-600 font-medium"
                                onclick="showFrontTab(event)" type="button">
                            Kartu Depan
                        </button>
                        <button id="backTab" class="px-4 py-2 text-gray-500 border-b-2 border-transparent font-medium"
                                onclick="showBackTab(event)" type="button">
                            Kartu Belakang
                        </button>
                    </div>

                    <!-- Card Preview Container -->
                    <div class="relative mx-auto" style="width: 340px; height: 214px;">
                        <!-- Front Card Preview -->
                        <div id="frontCardPreview" class="absolute inset-0 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg shadow-lg overflow-hidden"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            @include('admin.kartu.parts.preview-card-front')
                        </div>

                        <!-- Back Card Preview -->
                        <div id="backCardPreview" class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg shadow-lg overflow-hidden"
                             style="display: none; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                            @include('admin.kartu.parts.preview-card-back')
                        </div>
                    </div>

                    <!-- Background Settings -->
                    <div class="mt-6 bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Background Kartu</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Depan</label>
                                <div class="space-y-2">
                                    <select id="backgroundFrontSelect" class="w-full border rounded-lg px-3 py-2">
                                        <option value="gradient-blue" {{ ($settings->background_front ?? '') == 'gradient-blue' ? 'selected' : '' }}>Gradient Blue</option>
                                        <option value="gradient-green" {{ ($settings->background_front ?? '') == 'gradient-green' ? 'selected' : '' }}>Gradient Green</option>
                                        <option value="gradient-purple" {{ ($settings->background_front ?? '') == 'gradient-purple' ? 'selected' : '' }}>Gradient Purple</option>
                                        <option value="solid-color" {{ ($settings->background_front ?? '') == 'solid-color' ? 'selected' : '' }}>Solid Color</option>
                                        <option value="custom-image" {{ ($settings->background_front ?? '') == 'custom-image' ? 'selected' : '' }}>Custom Image</option>
                                    </select>
                                    <input type="file" id="backgroundFrontUpload" accept="image/*" class="hidden">
                                    <button type="button" onclick="document.getElementById('backgroundFrontUpload').click()"
                                            class="w-full bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                                        Upload Background Depan
                                    </button>
                                    <input type="hidden" name="background_front" value="{{ $settings->background_front ?? 'gradient-blue' }}">
                                    <input type="hidden" name="background_image_front" value="{{ $settings->background_image_front ?? '' }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Belakang</label>
                                <div class="space-y-2">
                                    <select id="backgroundBackSelect" class="w-full border rounded-lg px-3 py-2">
                                        <option value="gradient-blue" {{ ($settings->background_back ?? '') == 'gradient-blue' ? 'selected' : '' }}>Gradient Blue</option>
                                        <option value="gradient-green" {{ ($settings->background_back ?? '') == 'gradient-green' ? 'selected' : '' }}>Gradient Green</option>
                                        <option value="gradient-purple" {{ ($settings->background_back ?? '') == 'gradient-purple' ? 'selected' : '' }}>Gradient Purple</option>
                                        <option value="solid-color" {{ ($settings->background_back ?? '') == 'solid-color' ? 'selected' : '' }}>Solid Color</option>
                                        <option value="custom-image" {{ ($settings->background_back ?? '') == 'custom-image' ? 'selected' : '' }}>Custom Image</option>
                                    </select>
                                    <input type="file" id="backgroundBackUpload" accept="image/*" class="hidden">
                                    <button type="button" onclick="document.getElementById('backgroundBackUpload').click()"
                                            class="w-full bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                                        Upload Background Belakang
                                    </button>
                                    <input type="hidden" name="background_back" value="{{ $settings->background_back ?? 'gradient-blue' }}">
                                    <input type="hidden" name="background_image_back" value="{{ $settings->background_image_back ?? '' }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warna Font Depan</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" name="font_color_front" value="{{ $settings->font_color_front ?? '#ffffff' }}"
                                           class="h-10 w-20 border rounded cursor-pointer">
                                    <span class="text-sm text-gray-600">Pilih warna font untuk kartu depan</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warna Font Belakang</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" name="font_color_back" value="{{ $settings->font_color_back ?? '#ffffff' }}"
                                           class="h-10 w-20 border rounded cursor-pointer">
                                    <span class="text-sm text-gray-600">Pilih warna font untuk kartu belakang</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="submit" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg hover:bg-green-600 font-medium">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                        </button>
                        <a href="{{ route('admin.kartu-anggota.anggota-list') }}"
                           class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600 font-medium block text-center">
                            <i class="fas fa-users mr-2"></i> Lihat Daftar Anggota
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let currentSide = 'front';

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupTabNavigation();
    setupBackgroundHandlers();
    setupFontColorHandlers();
});

function setupTabNavigation() {
    // Fungsi akan dipanggil via onclick
}

function showFrontTab() {
    event.preventDefault();

    const frontTab = document.getElementById('frontTab');
    const backTab = document.getElementById('backTab');
    const frontCard = document.getElementById('frontCardPreview');
    const backCard = document.getElementById('backCardPreview');

    currentSide = 'front';
    frontCard.style.display = 'block';
    backCard.style.display = 'none';

    frontTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    frontTab.classList.remove('text-gray-500');
    backTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    backTab.classList.add('text-gray-500');

    // Apply the correct background for front card
    const frontSelect = document.getElementById('backgroundFrontSelect');
    if (frontSelect) {
        updateBackground('front', frontSelect.value);
    }

    return false;
}

function showBackTab() {
    event.preventDefault();

    const frontTab = document.getElementById('frontTab');
    const backTab = document.getElementById('backTab');
    const frontCard = document.getElementById('frontCardPreview');
    const backCard = document.getElementById('backCardPreview');

    currentSide = 'back';
    frontCard.style.display = 'none';
    backCard.style.display = 'block';

    backTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    backTab.classList.remove('text-gray-500');
    frontTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    frontTab.classList.add('text-gray-500');

    // Apply the correct background for back card
    const backSelect = document.getElementById('backgroundBackSelect');
    if (backSelect) {
        updateBackground('back', backSelect.value);
    }

    return false;
}

function setupBackgroundHandlers() {
    const frontSelect = document.getElementById('backgroundFrontSelect');
    const backSelect = document.getElementById('backgroundBackSelect');
    const frontUpload = document.getElementById('backgroundFrontUpload');
    const backUpload = document.getElementById('backgroundBackUpload');

    frontSelect.addEventListener('change', function() {
        updateBackground('front', this.value);
    });

    backSelect.addEventListener('change', function() {
        updateBackground('back', this.value);
    });

    frontUpload.addEventListener('change', function(e) {
        handleBackgroundUpload(e, 'front');
    });

    backUpload.addEventListener('change', function(e) {
        handleBackgroundUpload(e, 'back');
    });
}

function setupFontColorHandlers() {
    const frontColorPicker = document.querySelector('input[name="font_color_front"]');
    const backColorPicker = document.querySelector('input[name="font_color_back"]');

    if (frontColorPicker) {
        frontColorPicker.addEventListener('input', function() {
            updateFontColor('front', this.value);
        });
    }

    if (backColorPicker) {
        backColorPicker.addEventListener('input', function() {
            updateFontColor('back', this.value);
        });
    }
}

function updateFontColor(side, color) {
    // Update text elements in front card
    if (side === 'front') {
        const frontCard = document.getElementById('frontCardPreview');
        if (frontCard) {
            const textElements = frontCard.querySelectorAll('.card-text-front');
            textElements.forEach(element => {
                element.style.color = color;
            });
        }
    }

    // Update text elements in back card
    if (side === 'back') {
        const backCard = document.getElementById('backCardPreview');
        if (backCard) {
            const textElements = backCard.querySelectorAll('.card-text-back');
            textElements.forEach(element => {
                element.style.color = color;
            });
        }
    }
}

function updateBackground(side, style) {
    const card = side === 'front' ?
        document.getElementById('frontCardPreview') :
        document.getElementById('backCardPreview');

    const imageUrl = side === 'front' ?
        '{{ $settings->background_image_front ? asset("storage/".$settings->background_image_front) : "" }}' :
        '{{ $settings->background_image_back ? asset("storage/".$settings->background_image_back) : "" }}';

    switch(style) {
        case 'gradient-blue':
            card.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            break;
        case 'gradient-green':
            card.style.background = 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)';
            break;
        case 'gradient-purple':
            card.style.background = 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)';
            break;
        case 'solid-color':
            const color = side === 'front' ?
                '{{ $settings->primary_color_front ?? "#1e40af" }}' :
                '{{ $settings->primary_color_back ?? "#1e40af" }}';
            card.style.backgroundColor = color;
            break;
        case 'custom-image':
            if (imageUrl) {
                card.style.backgroundImage = `url('${imageUrl}')`;
                card.style.backgroundSize = 'cover';
                card.style.backgroundPosition = 'center';
            }
            break;
    }
}

function handleBackgroundUpload(event, side) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    const fieldName = side === 'front' ? 'background_front' : 'background_back';
    formData.append(fieldName, file);

    const url = side === 'front' ?
        '{{ route("admin.kartu-anggota.upload-background-front") }}' :
        '{{ route("admin.kartu-anggota.upload-background-back") }}';

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Upload failed');
    });
}
</script>
@endpush