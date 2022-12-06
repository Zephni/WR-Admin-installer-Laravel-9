<x-frontend-layout>
    <x-slot name="title">Register</x-slot>

    <x-frontend.auth-container>
        @foreach($errors->all() as $message)
            <x-alert>{{ $message }}</x-alert>
        @endforeach
        <form action="{{ route('register-post') }}" method="POST">
            @csrf
            <p class="mb-4">Register a new account</p>
            <div class="mb-4">
                <x-input-text name="name" placeholder="Full name"></x-input-text>
            </div>
            <div class="mb-4">
                <x-input-text name="email" placeholder="Email"></x-input-text>
            </div>
            <div class="mb-4">
                <x-input-text type="password" name="password" placeholder="Password"></x-input-text>
            </div>
            <div class="mb-4">
                <x-input-text type="password" name="password_confirmation" placeholder="Confirm password"></x-input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <input
                    class="inline-block px-6 py-2.5 bg-fancy text-white font-medium text-xs leading-tight uppercase cursor-pointer rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out w-full mb-3"
                    type="submit"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light"
                    value="Register" />
                <a class="text-gray-500" href="{{ route('password.forgot') }}">Forgot password?</a>
            </div>
        </form>
    </x-frontend.auth-container>
</x-frontend-layout>
