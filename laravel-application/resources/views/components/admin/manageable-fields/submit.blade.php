@props([
    'type'      => 'submit',
    'value'     => $value ?? 'Submit',
    'icon'      => $icon ?? null,
])

<div class="flex justify-center w-full py-3">
    <button
        id="mf_submit"
        type="{{ $type }}"
        {{ $attributes->merge(['class' => 'text-center !bg-teal-700 hover:!bg-teal-600 active:bg-teal-600 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 hover:cursor-pointer']) }}>
        <span class="flex items-center m-auto gap-2 mx-auto px-16">
            @if($icon)<i class="text-lg {!! $icon !!}"></i>@endif
            <span class="text-lg font-light relative" style="top: -1px;">{!! $value !!}</span>
        </span>
    </button>
</div>
