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
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 rounded-md px-2 py-1']) }}
    >{{ $value }}</textarea>
</div>
