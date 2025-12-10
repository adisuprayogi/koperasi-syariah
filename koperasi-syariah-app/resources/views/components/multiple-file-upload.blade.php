@props([
    'name' => 'files',
    'label' => 'Upload Multiple Files',
    'accept' => '.jpg,.jpeg,.png,.pdf',
    'maxFiles' => 5,
    'maxSize' => '2MB',
    'help' => null,
    'existingFiles' => []
])

<div class="space-y-3">
    {{-- Label --}}
    <label class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    {{-- Help Text --}}
    @if($help)
        <p class="text-xs text-gray-500">{{ $help }}</p>
    @endif

    {{-- Upload Area --}}
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
        <div class="text-center">
            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
            <label for="{{ $name }}-input" class="cursor-pointer">
                <span class="text-blue-600 hover:text-blue-800 font-medium">Pilih file</span>
                <span class="text-gray-600"> atau drag & drop</span>
            </label>
            <input type="file"
                   id="{{ $name }}-input"
                   name="{{ $name }}[]"
                   accept="{{ $accept }}"
                   multiple
                   class="hidden"
                   onchange="handleMultipleFileUpload(this, {{ $maxFiles }})">
            <p class="text-xs text-gray-500 mt-1">
                Maksimal {{ $maxFiles }} file, format: {{ str_replace(',', ', ', $accept) }}, max: {{ $maxSize }} per file
            </p>
        </div>
    </div>

    {{-- Existing Files --}}
    @if(count($existingFiles) > 0)
        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-700">File yang tersimpan:</p>
            @foreach($existingFiles as $index => $file)
                @php
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
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
                <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded">
                    <div class="flex items-center space-x-2">
                        <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                        <span class="text-sm text-green-700">{{ basename($file) }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ asset('storage/' . $file) }}" target="_blank"
                           class="text-green-600 hover:text-green-800 text-xs">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- File Preview List --}}
    <div id="{{ $name }}-preview" class="space-y-2 hidden">
        <p class="text-sm font-medium text-gray-700">File yang akan diupload:</p>
        <div id="{{ $name }}-list" class="space-y-2">
            {{-- Files will be added here dynamically --}}
        </div>
    </div>
</div>

<script>
function handleMultipleFileUpload(input, maxFiles) {
    const preview = document.getElementById(input.id.replace('-input', '-preview'));
    const list = document.getElementById(input.id.replace('-input', '-list'));

    if (input.files && input.files.length > 0) {
        // Check if exceeds max files
        if (input.files.length > maxFiles) {
            alert('Maksimal hanya ' + maxFiles + ' file yang dapat diupload.');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }

        preview.classList.remove('hidden');
        list.innerHTML = '';

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const fileId = input.id + '-' + i;

            // File icon based on type
            let iconClass = 'fas fa-file';
            let iconColor = 'text-blue-600';
            const extension = file.name.split('.').pop().toLowerCase();

            switch(extension) {
                case 'pdf':
                    iconClass = 'fas fa-file-pdf';
                    iconColor = 'text-red-500';
                    break;
                case 'doc':
                case 'docx':
                    iconClass = 'fas fa-file-word';
                    iconColor = 'text-blue-500';
                    break;
                case 'xls':
                case 'xlsx':
                    iconClass = 'fas fa-file-excel';
                    iconColor = 'text-green-500';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    iconClass = 'fas fa-file-image';
                    iconColor = 'text-purple-500';
                    break;
            }

            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-blue-50 border border-blue-200 rounded';
            fileItem.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="${iconClass} ${iconColor}"></i>
                    <div>
                        <span class="text-sm font-medium text-blue-700">${file.name}</span>
                        <span class="text-xs text-blue-600 ml-2">(${formatFileSize(file.size)})</span>
                    </div>
                </div>
                <button type="button" onclick="removeFileFromList('${input.id}', ${i})"
                        class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(fileItem);
        }
    } else {
        preview.classList.add('hidden');
    }
}

function removeFileFromList(inputId, index) {
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();

    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }

    input.files = dt.files;
    handleMultipleFileUpload(input, 5); // Re-render with updated files
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadInputs = document.querySelectorAll('input[type="file"][multiple]');

    uploadInputs.forEach(function(input) {
        const container = input.closest('.border-dashed');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            container.addEventListener(eventName, function() {
                container.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, function() {
                container.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        container.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length <= parseInt(input.getAttribute('onchange').match(/maxFiles\)[^0-9]*([0-9]+)/)[1])) {
                input.files = files;
                handleMultipleFileUpload(input, 5);
            } else {
                alert('Maksimal file yang dapat diupload adalah ' + input.getAttribute('onchange').match(/maxFiles\)[^0-9]*([0-9]+)/)[1]);
            }
        }, false);
    });
});
</script>