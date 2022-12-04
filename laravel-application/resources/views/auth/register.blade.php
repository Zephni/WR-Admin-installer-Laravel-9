<x-frontend-layout>
    <x-slot name="title">Register</x-slot>

    <x-auth-view-container>
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
                    class="inline-block px-6 py-2.5 text-white font-medium text-xs leading-tight uppercase cursor-pointer rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out w-full mb-3"
                    type="submit"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light"
                    style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);"
                    value="Register" />
                <a class="text-gray-500" href="#!">Forgot password?</a>
            </div>
            <div class="flex items-center justify-between pb-6">
                <p class="mb-0 mr-2">Don't have an account?</p>
                <button
                    type="button"
                    class="inline-block px-6 py-2 border-2 border-red-600 text-red-600 font-medium text-xs leading-tight uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light">
                    Danger
                </button>
            </div>
        </form>
    </x-auth-view-container>
</x-frontend-layout>
