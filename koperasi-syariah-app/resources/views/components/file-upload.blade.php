@props([
    'name' => 'file',
    'label' => 'Pilih File',
    'accept' => '.jpg,.jpeg,.png,.pdf',
    'required' => false,
    'maxSize' => '2MB',
    'help' => null,
    'existingFile' => null,
    'existingLabel' => null
])

<div class="space-y-2">
    {{-- Label --}}
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Existing File Display --}}
    @if($existingFile)
        <div class="p-3 bg-green-50 border border-green-200 rounded-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    @php
                        $extension = strtolower(pathinfo($existingFile, PATHINFO_EXTENSION));
                        $iconClass = 'fas fa-file';
                        $iconColor = 'text-green-600';

                        switch($extension) {
                            case 'pdf':
                                $iconClass = 'fas fa-file-pdf';
                                break;
                            case 'doc':
                            case 'docx':
                                $iconClass = 'fas fa-file-word';
                                break;
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                                $iconClass = 'fas fa-file-image';
                                break;
                        }
                    @endphp
                    <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                    <span class="text-sm text-green-700">{{ $existingLabel ?? basename($existingFile) }}</span>
                </div>
                <a href="{{ asset('storage/' . $existingFile) }}" target="_blank"
                   class="text-green-600 hover:text-green-800 text-sm">
                    <i class="fas fa-eye mr-1"></i>Lihat
                </a>
            </div>
        </div>
        <p class="text-xs text-gray-500">Upload file baru untuk mengganti:</p>
    @endif

    {{-- File Input --}}
    <div class="relative">
        <input type="file"
               id="{{ $name }}"
               name="{{ $name }}"
               @if($required) required @endif
               accept="{{ $accept }}"
               class="hidden"
               onchange="handleFileUpload(this)">

        <label for="{{ $name }}"
               class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 hover:bg-gray-50 transition-colors">
            <div class="text-center">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-blue-600">Klik untuk upload</span>
                    atau drag & drop
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $help ?? 'Format: ' . str_replace(',', ', ', $accept) . '. Max: ' . $maxSize }}
                </p>
            </div>
        </label>

        {{-- Selected File Preview --}}
        <div id="{{ $name }}-preview" class="hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i id="{{ $name }}-icon" class="fas fa-file text-blue-600"></i>
                    <span id="{{ $name }}-name" class="text-sm text-blue-700"></span>
                </div>
                <button type="button" onclick="clearFileUpload('{{ $name }}')"
                        class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function handleFileUpload(input) {
    const preview = document.getElementById(input.id + '-preview');
    const fileName = document.getElementById(input.id + '-name');
    const fileIcon = document.getElementById(input.id + '-icon');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileNameDisplay = file.name;
        const extension = fileNameDisplay.split('.').pop().toLowerCase();

        // Update icon based on file type
        let iconClass = 'fas fa-file';
        switch(extension) {
            case 'pdf':
                iconClass = 'fas fa-file-pdf';
                break;
            case 'doc':
            case 'docx':
                iconClass = 'fas fa-file-word';
                break;
            case 'xls':
            case 'xlsx':
                iconClass = 'fas fa-file-excel';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                iconClass = 'fas fa-file-image';
                break;
        }

        fileIcon.className = iconClass + ' text-blue-600';
        fileName.textContent = fileNameDisplay;
        preview.classList.remove('hidden');
    } else {
        clearFileUpload(input.id);
    }
}

function clearFileUpload(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + '-preview');

    input.value = '';
    preview.classList.add('hidden');
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(function(input) {
        const label = input.nextElementSibling;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            label.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            label.addEventListener(eventName, function() {
                label.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            label.addEventListener(eventName, function() {
                label.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        label.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            input.files = files;
            handleFileUpload(input);
        }, false);
    });
});
</script>