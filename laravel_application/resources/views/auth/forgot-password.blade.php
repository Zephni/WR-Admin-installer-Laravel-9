<x-frontend-layout>

    <x-slot name="title">Forgot password</x-slot>

    <x-frontend.auth-container displayCompanyInfo="true">

        @if(session('status'))
            <x-frontend.alert type="success">{{ session('status') }}</x-frontend.alert>
        @endif

        @foreach($errors->all() as $message)
            <x-frontend.alert>{{ $message }}</x-frontend.alert>
        @endforeach

        <form action="{{ route('password.forgot') }}" method="POST">
            @csrf
            <h4 class="text-xl font-semibold mt-1 mb-4 pb-1">Forgot password</h4>
            <p class="mb-4">Send password reset request</p>
            <div class="mb-4">
                <x-frontend.input-text name="email" placeholder="Email"></x-frontend.input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <x-frontend.input-submit value="Send reset request" />
            </div>
        </form>

    </x-frontend.auth-container>
</x-frontend-layout>
