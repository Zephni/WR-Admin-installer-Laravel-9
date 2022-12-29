@props([
    'type'      => 'submit',
    'value'     => $value ?? 'Submit'
])

<div class="block w-full py-3">
    <input
        id="mf_submit"
        type="{{ $type }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'w-full border border-gray-500 rounded-md px-2 py-1 text-white font-bold bg-blue-700 hover:bg-blue-600 focus:shadow-outline focus:outline-none hover:cursor-pointer']) }}
    />
</div>
