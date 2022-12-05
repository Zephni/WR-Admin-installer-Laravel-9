<x-frontend-layout>
    <x-slot name="title">Login</x-slot>

    <x-frontend.auth-container>
        @if(session('status'))
            <x-alert type="success">{{ session('status') }}</x-alert>
        @endif
        @foreach($errors->all() as $message)
            <x-alert>{{ $message }}</x-alert>
        @endforeach
        <form action="{{ route('login-post') }}" method="POST">
            @csrf
            <p class="mb-4">Please login to your account</p>
            <div class="mb-4">
                <x-input-text name="email" placeholder="Email"></x-input-text>
            </div>
            <div class="mb-4">
                <x-input-text type="password" name="password" placeholder="Password"></x-input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <input
                    class="inline-block px-6 py-2.5 bg-fancy text-white font-medium text-xs leading-tight uppercase cursor-pointer rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out w-full mb-3"
                    type="submit"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light"
                    value="Log in" />
                <a class="text-gray-500" href="{{ route('forgot-password') }}">Forgot password?</a>
            </div>
            <div class="flex items-center justify-between pb-6">
                <p class="mb-0 mr-2 text-sm">Don't have an account?</p>
                <a
                    href="{{ route('register') }}"
                    class="inline-block px-6 py-2 border-2 border-primary-600 text-primary-600 font-medium text-xs leading-tight uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light">
                    Register an account
                </a>
            </div>
        </form>
    </x-frontend.auth-container>
</x-frontend-layout>
