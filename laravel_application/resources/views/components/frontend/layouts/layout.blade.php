<!DOCTYPE html>
<html lang="en">
    <head>
        {{-- Meta data --}}
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }} - {{ env('APP_NAME') }}</title>

        {{-- Styles / JS --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-200">
        {{-- Header --}}
        <section class="py-6 bg-slate-800 text-white shadow-md shadow-orange-400">
            <div class="container flex justify-end md:justify-between mx-auto px-6">
                <p>
                    <a href="{{ route('home') }}">{{ env('APP_NAME') }}</a>
                </p>
                @auth
                    <p class="font-light">
                        Logged in as <strong>{{ auth()->user()->name }}</strong>
                        @if(auth()->user()->getPermission('admin') == true)
                            <span class="px-2">|</span>
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @endif
                        <span class="px-2">|</span>
                        <a href="{{ route('logout') }}">Logout</a>
                    </p>
                @else
                    <p class="font-light">
                        <a href="{{ route('login') }}">Login</a>
                        <span class="px-2">|</span>
                        <a href="{{ route('register') }}">Register</a>
                    </p>
                @endauth
            </div>
        </section>

        {{-- MAIN --}}
        <main class="container mx-auto mt-4 px-4">
            {{ $slot }}
        </main>
    </body>
</html>
