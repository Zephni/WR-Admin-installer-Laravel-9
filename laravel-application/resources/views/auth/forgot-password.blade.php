<x-frontend-layout>
    <x-slot name="title">Forgot password</x-slot>

    <x-frontend.auth-container displayCompanyInfo="true">
        @if(session('status'))
            <x-alert type="success">{{ session('status') }}</x-alert>
        @endif
        @foreach($errors->all() as $message)
            <x-alert>{{ $message }}</x-alert>
        @endforeach
        <form action="{{ route('password.forgot') }}" method="POST">
            @csrf
            <h4 class="text-xl font-semibold mt-1 mb-4 pb-1">Forgot password</h4>
            <p class="mb-4">Send password reset request.</p>
            <div class="mb-4">
                <x-input-text name="email" placeholder="Email"></x-input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <input
                    class="inline-block px-6 py-2.5 bg-fancy text-white font-medium text-xs leading-tight uppercase cursor-pointer rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out w-full mb-3"
                    type="submit"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light"
                    value="Send reset request" />
            </div>
        </form>
    </x-frontend.auth-container>
</x-frontend-layout>
