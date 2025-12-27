@if ($paginator->hasPages())
    <nav class="flex items-center justify-between" aria-label="Pagination">
        <div class="flex-1 flex items-center justify-between">
            <!-- Mobile Previous Button -->
            <div class="flex items-center">
                <span class="relative z-0 inline-flex shadow-sm rounded-md -space-x-px">
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-primary-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                </span>
            </div>

            <!-- Desktop Pagination Numbers -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        dari
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        data
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex shadow-sm -space-x-px rounded-md" aria-label="Pagination">
                        <!-- Previous -->
                        @if ($paginator->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}"
                               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-primary-50 transition-colors">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @foreach ($elements as $element)
                            @if (is_string($element))
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50 text-sm font-medium text-gray-700">
                                    {{ $element }}
                                </span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page"
                                              class="relative inline-flex items-center px-4 py-2 border border-primary-500 bg-primary-600 text-sm font-medium text-white">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-primary-50 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        <!-- Next -->
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}"
                               class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-primary-50 transition-colors">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Mobile Next Button -->
            <div class="flex items-center sm:hidden">
                <span class="relative z-0 inline-flex shadow-sm rounded-md -space-x-px">
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-primary-50 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
