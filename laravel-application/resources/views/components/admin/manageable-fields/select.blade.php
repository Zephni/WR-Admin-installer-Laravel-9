@props([
    'label'     => null,
    'name'      => $name ?? 'unset',
    'value'     => $value ?? '',
    'data'      => $data ?? [],
])

<div class="block w-full py-3">
    @if($label != null)
        <x-admin.manageable-fields.label :label="$label" :name="$name" />
    @endif

    <select
        id="mf_{{ $name }}"
        name="{{ $name }}"
        @if($data['readonly'] ?? false) readonly @endif
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 bg-gray-900 text-gray-300 rounded-md px-2 py-2']) }}>
        @foreach($data['options'] as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ $optionKey == $value ? 'selected' : '' }}>{{ $optionValue }}</option>
        @endforeach
    </select>

    @if($data['info'] ?? false)
        <x-admin.alert type="info" :message="$data['info']" />
    @endif
</div>
