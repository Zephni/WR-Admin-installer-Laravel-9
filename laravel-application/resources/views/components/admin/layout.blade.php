<!DOCTYPE html>
<html lang="en">
    <head>
        {{-- Meta data --}}
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }} [Admin] - {{ env('APP_NAME') }}</title>

        {{-- Styles / JS --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-200">
        {{-- Header --}}
        <section class="flex py-6 bg-slate-800 text-white shadow-md shadow-orange-400">
            <div class="container mx-auto flex justify-between px-6">
                <p>
                    <x-svg.icon-manage class="mr-2 w-8 h-8 fill-white" />
                    <a href="{{ route('admin.dashboard') }}">[ADMINISTRATION]</a> -
                    <a href="{{ route('home') }}">{{ env('APP_NAME') }}</a>
                </p>
                @auth
                    <p class="font-light">
                        Logged in as <strong>{{ auth()->user()->name }}</strong>
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
