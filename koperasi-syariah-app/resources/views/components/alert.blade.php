@props(['type' => 'info', 'dismissible' => false])

@php
    $typeClasses = [
        'success' => 'bg-primary-50 border-primary-200 text-primary-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-secondary-50 border-secondary-200 text-secondary-800'
    ];

    $iconClasses = [
        'success' => 'fas-check-circle text-primary-400',
        'error' => 'fas-exclamation-circle text-red-400',
        'warning' => 'fas-exclamation-triangle text-yellow-400',
        'info' => 'fas-info-circle text-secondary-400'
    ];
@endphp

<div class="rounded-md border p-4 {{ $typeClasses[$type] ?? $typeClasses['info'] }} {{ $dismissible ? 'relative' : '' }}">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $iconClasses[$type] ?? $iconClasses['info'] }}"></i>
        </div>
        <div class="ml-3 flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex rounded-md p-1.5 hover:bg-opacity-20 {{ $typeClasses[$type] ?? $typeClasses['info'] }}">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>