@props([
    'link' => $link ?? true,
    'href' => $href ?? '#',
    'text' => $text ?? 'Button',
    'type' => $type ?? 'primary', // primary, secondary, danger, warning, success
    'confirm' => $confirm ?? '0',
])

@php
    $typeStyles = [
        'primary' => 'bg-teal-700 hover:bg-teal-600 active:bg-teal-600',
        'secondary' => 'bg-gray-700 hover:bg-gray-600 active:bg-gray-600',
        'danger' => 'bg-red-700 hover:bg-red-600 active:bg-red-600',
        'warning' => 'bg-yellow-700 hover:bg-yellow-600 active:bg-yellow-600',
        'success' => 'bg-green-700 hover:bg-green-600 active:bg-green-600',
    ];

    $typeStyle = $typeStyles[$type] ?? $typeStyles['primary'];
@endphp

@if($link)
    <a
        href="{{ $href }}"
        title="{{ strip_tags($text) }}"
        @if($confirm != '0')
            onclick="return confirm('{{ $confirm }}')"
        @endif
        {{ $attributes->merge(['class' => $typeStyle.' text-white font-bold uppercase text-xs px-4 py-1 rounded shadow hover:shadow-md outline-none focus:outline-none ease-linear transition-all duration-150']) }}
    >
        <span class="font-light relative" style="top: -1px;">{!! $text !!}</span>
    </a>
@else
    {{-- TODO: Make button version --}}
@endif
