<x-frontend-layout>

    <x-slot name="title">Reset password</x-slot>

    <x-frontend.auth-container displayCompanyInfo="true">

        @if(session('status'))
            <x-frontend.alert type="success">{{ session('status') }}</x-frontend.alert>
        @endif

        @foreach($errors->all() as $message)
            <x-frontend.alert>{{ $message }}</x-frontend.alert>
        @endforeach

        <form action="{{ route('password.reset.request') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <h4 class="text-xl font-semibold mt-1 mb-4 pb-1">Reset password</h4>
            <p class="mb-4">Resetting password for:</p>
            <div class="mb-4">
                <x-frontend.input-text name="email" placeholder="Email" value="{{ $email }}"></x-frontend.input-text>
            </div>
            <div class="mb-4">
                <x-frontend.input-text type="password" name="password" placeholder="New password"></x-frontend.input-text>
            </div>
            <div class="mb-4">
                <x-frontend.input-text type="password" name="password_confirmation" placeholder="Confirm new password"></x-frontend.input-text>
            </div>
            <div class="text-center pt-1 mb-12 pb-1">
                <x-frontend.input-submit value="Reset password" />
            </div>
        </form>

    </x-frontend.auth-container>
</x-frontend-layout>
