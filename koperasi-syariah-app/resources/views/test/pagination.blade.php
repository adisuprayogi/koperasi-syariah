@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Pagination Test</h1>

    <div class="mb-4">
        <p>Paginator Class: {{ get_class($anggota) }}</p>
        <p>Total: {{ $anggota->total() }}</p>
        <p>Last Page: {{ $anggota->lastPage() }}</p>
        <p>Current Page: {{ $anggota->currentPage() }}</p>
        <p>Has Pages: {{ $anggota->hasPages() ? 'Yes' : 'No' }}</p>
    </div>

    <div class="bg-white rounded-lg p-4 mb-4">
        <h2 class="font-semibold mb-2">Data:</h2>
        @foreach($anggota as $item)
            <div class="py-1">{{ $loop->index + 1 }}. {{ $item->nama_lengkap }}</div>
        @endforeach
    </div>

    <div class="bg-gray-100 rounded-lg p-4">
        <h2 class="font-semibold mb-2">Pagination:</h2>
        {{ $anggota->links() }}
    </div>
</div>
@endsection