@props([
    'label'     => null,
    'name'      => $name ?? 'unset',
    'value'     => $value ?? '',
    'options'   => $options ?? [],
])

<div class="block w-full py-3">
    @if($label != null)
        <x-admin.manageable-fields.label :label="$label" :name="$name" />
    @endif

    <textarea
        id="mf_{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 bg-gray-900 text-gray-300 rounded-md px-2 py-2']) }}
    >{{ $value }}</textarea>
</div>
