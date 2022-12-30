@props([
    'type'      => 'submit',
    'value'     => $value ?? 'Submit'
])

<div class="block w-full py-3">
    <input
        id="mf_submit"
        type="{{ $type }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'w-full !bg-teal-700 hover:!bg-teal-600 active:bg-teal-600 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 hover:cursor-pointer']) }}
    />
</div>
