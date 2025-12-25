@extends('layouts.app')

@section('title', 'Daftar Anggota - Kartu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Anggota</h1>
        <p class="text-gray-600 mt-2">Pilih anggota untuk preview dan cetak kartu</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.kartu-anggota.settings') }}"
                       class="text-blue-500 hover:text-blue-700">
                        ← Kembali ke Pengaturan
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="text" id="search" placeholder="Cari anggota..."
                           class="border rounded-lg px-3 py-2 w-64">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Foto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nomor Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($anggotas as $index => $anggota)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($anggota->foto)
                                <img src="{{ asset('storage/'.$anggota->foto) }}"
                                     alt="{{ $anggota->nama_lengkap }}"
                                     class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-gray-500 text-xs">{{ substr($anggota->nama_lengkap, 0, 1) }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $anggota->nomor_anggota }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $anggota->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $anggota->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $anggota->alamat_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $anggota->telepon }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                       {{ $anggota->status_keanggotaan == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $anggota->status_keanggotaan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.kartu-anggota.preview', $anggota->id) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900">
                                    Preview
                                </a>
                                <a href="{{ route('admin.kartu-anggota.html', $anggota->id) }}"
                                   target="_blank"
                                   class="text-green-600 hover:text-green-900">
                                    Cetak
                                </a>
                                <a href="{{ route('admin.kartu-anggota.download', $anggota->id) }}"
                                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
                                    Download PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data anggota
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anggotas->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $anggotas->links('pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('search').addEventListener('input', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const nama = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        const nomor = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const alamat = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

        if (nama.includes(searchValue) || nomor.includes(searchValue) || alamat.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush