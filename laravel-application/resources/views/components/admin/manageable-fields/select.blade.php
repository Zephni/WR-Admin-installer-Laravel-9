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

    <select
        id="mf_{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 bg-gray-900 text-gray-300 rounded-md px-2 py-2']) }}>
        @foreach($options['options'] as $key => $value)
            <option value="{{ $key }}" {{ $key == $value ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>

    @if($options['info'] ?? false)
        <x-admin.alert type="info" :message="$options['info']" />
    @endif
</div>
