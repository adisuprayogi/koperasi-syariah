@props(['icon' => 'fas-inbox', 'title' => 'Tidak ada data', 'description' => 'Data yang Anda cari belum tersedia.'])

<div class="text-center py-12">
    <div class="mx-auto h-24 w-24 text-gray-400">
        <i class="{{ $icon }} text-6xl"></i>
    </div>
    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $title }}</h3>
    <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @if (isset($action))
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>