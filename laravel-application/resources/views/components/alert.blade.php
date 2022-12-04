@props([
    'type'    => 'error',
    'title'   => $title ?? '',
])

@php
    $alertID = 'alert-'.str_replace(' ', '-', strtolower(preg_replace( '/[\W]/', '', strip_tags($slot))));
@endphp

<div x-show="show" x-transition.duration.400ms id="{{ $alertID }}" class="p-2 mb-4 text-sm rounded-lg text-red-700 bg-red-100 relative" role="alert" x-data="{ show: true }">
    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-1 relative -top-0.5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2.033 16.01c.564-1.789 1.632-3.932 1.821-4.474.273-.787-.211-1.136-1.74.209l-.34-.64c1.744-1.897 5.335-2.326 4.113.613-.763 1.835-1.309 3.074-1.621 4.03-.455 1.393.694.828 1.819-.211.153.25.203.331.356.619-2.498 2.378-5.271 2.588-4.408-.146zm4.742-8.169c-.532.453-1.32.443-1.761-.022-.441-.465-.367-1.208.164-1.661.532-.453 1.32-.442 1.761.022.439.466.367 1.209-.164 1.661z"/></svg>
    <button type="button" @click="show = false" class="absolute right-3 top-4 ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg hover:ring-2 ring-red-300 inline-flex" data-dismiss-target="#{{ $alertID }}" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>

    <span class="">
        @if($title)
            <span class="font-medium">{{ $title }}</span>
        @endif

        {{ $slot }}
    </span>
</div>
