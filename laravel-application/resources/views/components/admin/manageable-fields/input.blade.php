@props([
    'label'     => null,
    'name'      => $name ?? 'unset',
    'type'      => $type ?? 'text',
    'value'     => $value ?? '',
    'data'      => $data ?? [],
])

@php
    $appendedClasses = '';
    if($attributes->has('readonly')){
        $appendedClasses .= ' bg-gray-800 text-gray-600 cursor-not-allowed ';
    }
@endphp

@if($type != 'hidden' && $label != null)
<div class="block w-full py-3">
@endif
    @if($label != null)
        <x-admin.manageable-fields.label :label="$label" :name="$name" />
    @endif

    <input
        id="mf_{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
        @if($data['readonly'] ?? false) readonly @endif
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 bg-gray-900 text-gray-300 rounded-md px-2 py-2'.$appendedClasses]) }}
    />

    @if($data['info'] ?? false)
        <x-admin.alert type="info-basic" :message="$data['info']" />
    @endif
@if($type != 'hidden'&& $label != null)
</div>
@endif
