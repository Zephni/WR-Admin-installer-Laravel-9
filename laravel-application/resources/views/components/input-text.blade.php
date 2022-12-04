{{-- Component passed values --}}
@props([
    'type' => $type ?? 'text', // password, email, etc.
    'name' => $name,
    'value' => $value ?? '',
    'placeholder' => $placeholder ?? '',
])

<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
