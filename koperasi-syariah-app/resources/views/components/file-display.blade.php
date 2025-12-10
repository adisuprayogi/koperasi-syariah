@props(['file' => null, 'label' => 'Document', 'showDelete' => false, 'deleteRoute' => null, 'pengajuanId' => null, 'field' => null])

@if($file)
    <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
        <div class="flex items-center space-x-3">
            {{-- File Icon based on extension --}}
            @php
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $iconClass = 'fas fa-file';
                $iconColor = 'text-gray-500';

                switch($extension) {
                    case 'pdf':
                        $iconClass = 'fas fa-file-pdf';
                        $iconColor = 'text-red-500';
                        break;
                    case 'doc':
                    case 'docx':
                        $iconClass = 'fas fa-file-word';
                        $iconColor = 'text-blue-500';
                        break;
                    case 'xls':
                    case 'xlsx':
                        $iconClass = 'fas fa-file-excel';
                        $iconColor = 'text-green-500';
                        break;
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        $iconClass = 'fas fa-file-image';
                        $iconColor = 'text-purple-500';
                        break;
                    case 'txt':
                        $iconClass = 'fas fa-file-alt';
                        $iconColor = 'text-gray-500';
                        break;
                }
            @endphp

            <i class="{{ $iconClass }} {{ $iconColor }} text-lg"></i>

            <div>
                <p class="text-sm font-medium text-gray-900">{{ $label }}</p>
                <p class="text-xs text-gray-500">{{ basename($file) }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            {{-- Download Button --}}
            <a href="{{ asset('storage/' . $file) }}"
               target="_blank"
               class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors"
               title="Download {{ $label }}">
                <i class="fas fa-download mr-1"></i>
                Download
            </a>

            {{-- Preview Button for Images and PDFs --}}
            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                <button type="button"
                        onclick="previewFile('{{ asset('storage/' . $file) }}', '{{ $label }}', '{{ $extension }}')"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors"
                        title="Preview {{ $label }}">
                    <i class="fas fa-eye mr-1"></i>
                    Preview
                </button>
            @endif

            {{-- Delete Button --}}
            @if($showDelete && $deleteRoute)
                <form action="{{ $deleteRoute }}" method="POST" class="inline" onsubmit="return confirm('Hapus file ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors"
                            title="Delete {{ $label }}">
                        <i class="fas fa-trash mr-1"></i>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
@else
    <div class="p-3 border border-dashed border-gray-300 rounded-lg text-center text-gray-500">
        <i class="fas fa-file-upload text-2xl mb-2"></i>
        <p class="text-sm">{{ $label }} tidak tersedia</p>
    </div>
@endif

{{-- Preview Modal --}}
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="previewTitle" class="text-lg font-medium text-gray-900">Preview Dokumen</h3>
            <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div id="previewContent" class="max-h-96 overflow-auto">
            {{-- Content will be loaded here --}}
        </div>

        <div class="mt-4 flex justify-end space-x-3">
            <button type="button" onclick="closePreview()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                Tutup
            </button>
            <a id="previewDownload" href="#" target="_blank"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>Download
            </a>
        </div>
    </div>
</div>

<script>
function previewFile(url, title, type) {
    const modal = document.getElementById('previewModal');
    const titleElement = document.getElementById('previewTitle');
    const contentElement = document.getElementById('previewContent');
    const downloadElement = document.getElementById('previewDownload');

    titleElement.textContent = 'Preview: ' + title;
    downloadElement.href = url;

    if (type === 'pdf') {
        contentElement.innerHTML = `
            <div class="w-full h-96">
                <iframe src="${url}" class="w-full h-full border-0" title="${title}"></iframe>
            </div>
        `;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
        contentElement.innerHTML = `
            <div class="text-center">
                <img src="${url}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
            </div>
        `;
    } else {
        contentElement.innerHTML = `
            <div class="text-center text-gray-500">
                <i class="fas fa-file text-6xl mb-4"></i>
                <p>Preview tidak tersedia untuk tipe file ini</p>
                <p class="text-sm mt-2">Silakan download file untuk melihat isinya</p>
            </div>
        `;
    }

    modal.classList.remove('hidden');
}

function closePreview() {
    const modal = document.getElementById('previewModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>