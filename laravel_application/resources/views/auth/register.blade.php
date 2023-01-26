<x-frontend-layout>

    <x-slot name="title">Register</x-slot>

    <x-frontend.auth-container>

        @foreach($errors->all() as $message)
            <x-frontend.alert>{{ $message }}</x-frontend.alert>
        @endforeach

        <form action="{{ route('register-post') }}" method="POST">
            @csrf
            <p class="mb-4">Register a new account</p>
            <div class="mb-4">
                <x-frontend.input-text name="name" placeholder="Full name"></x-frontend.input-text>
            </div>
            <div class="mb-4">
                <x-frontend.input-text name="email" placeholder="Email"></x-frontend.input-text>
            </div>
            <div class="mb-4">
                <x-frontend.input-text type="password" name="password" placeholder="Password"></x-frontend.input-text>
            </div>
            <div class="mb-4">
                <x-frontend.input-text type="password" name="password_confirmation" placeholder="Confirm password"></x-frontend.input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <x-frontend.input-submit value="Register" />

                <a class="text-gray-500" href="{{ route('password.forgot') }}">Forgot password?</a>
            </div>
        </form>

    </x-frontend.auth-container>
</x-frontend-layout>
