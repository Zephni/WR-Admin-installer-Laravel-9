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

        {{-- AlpineJS --}}
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        {{-- Flowbite --}}
        <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
    </head>
    <body class="bg-gray-200 flex">
        {{-- NAVIGATION --}}
        <aside class="w-96 h-screen" aria-label="Sidebar">
            <div class="h-full overflow-y-auto py-4 px-3 bg-gray-800 rounded-br-lg">
                <a href="https://flowbite.com/" class="flex items-center pl-1.5 mb-5">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="mr-3 h-9" alt="Flowbite Logo" />
                    <p class="block w-full text-xl font-semibold whitespace-nowrap text-white">
                        {{ env('APP_NAME') }}<br />
                        <span class="block w-full text-sm font-semibold whitespace-nowrap text-slate-400">Administration</span>
                    </p>
                </a>
                <ul class="space-y-2">
                    @foreach(config('admin-navigation') as $navigationItem)
                        {{-- If pure HTML --}}
                        @if(isset($navigationItem['html']))
                            {!! $navigationItem['html'] !!}
                        {{-- If seperator --}}
                        @elseif(isset($navigationItem['seperator']) && $navigationItem['seperator'] === true)
                            <li class="relative border-t border-gray-700"></li>
                        {{-- If nav item does not have children --}}
                        @elseif(!isset($navigationItem['children']))
                            <li class="relative">
                                <a href="{{ route($navigationItem['route']) }}" class="group flex items-center p-2 text-base font-normal rounded-lg text-white hover:bg-gray-700 @if(request()->route()->getName() == $navigationItem['route']) bg-slate-700 @endif">
                                    @if(isset($navigationItem['icon']))
                                        <i class="{{ $navigationItem['icon'] }} mr-4 text-2xl text-gray-400 group-hover:text-white"></i>
                                    @endif
                                    <span>
                                        @if(request()->route()->getName() == $navigationItem['route'])
                                            <span class="absolute inset-y-0 -left-1 w-1 bg-blue-500 rounded-tr-sm rounded-br-sm" aria-hidden="true"></span>
                                        @endif
                                        {!! $navigationItem['title'] !!}
                                    </span>
                                </a>
                            </li>
                        {{-- If nav item has children --}}
                        @else
                            <li class="relative">
                                <div class="flex justify-between">
                                    <a href="{{ route($navigationItem['route']) }}" class="group flex flex-grow items-center p-2 text-base font-normal rounded-lg text-white hover:bg-gray-700">
                                        @if(isset($navigationItem['icon']))
                                            <i class="{{ $navigationItem['icon'] }} mr-4 text-2xl text-gray-400 group-hover:text-white"></i>
                                        @endif
                                        <span class="flex-1 text-left whitespace-nowrap text-white" sidebar-toggle-item>
                                            @if(request()->route()->getName() == $navigationItem['route'])
                                                <span class="absolute inset-y-0 -left-1 w-1 bg-blue-500 rounded-tr-sm rounded-br-sm" aria-hidden="true"></span>
                                            @endif
                                            {!! $navigationItem['title'] !!}
                                        </span>
                                    </a>
                                    <button type="button" class="flex items-center flex-shrink p-2 text-base font-normal rounded-lg transition duration-75 group text-white hover:bg-gray-700" aria-controls="nav-dropdown-{{ $loop->index }}" data-collapse-toggle="nav-dropdown-{{ $loop->index }}">
                                        <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                                <ul id="nav-dropdown-{{ $loop->index }}" class="hidden py-2 space-y-2">
                                    @foreach($navigationItem['children'] as $child)
                                        <li>
                                            <a href="{{ route($child['route']) }}" class="flex items-center p-2 pl-11 w-full text-base font-normal rounded-lg transition duration-75 group text-white hover:bg-gray-700">{{ $child['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="container mx-auto mt-4 px-4">
            {{ $slot }}
        </main>

        <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
    </body>
</html>
