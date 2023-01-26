@props([
    'label'     => null,
    'name'      => $name ?? 'unset',
])

<label for="mf_{{ $name }}" class="block text-gray-300 text-sm font-bold mb-2">{{ $label }}</label>
