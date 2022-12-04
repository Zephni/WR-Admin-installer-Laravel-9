<x-frontend-layout>
    <x-slot name="title">Home</x-slot>

    <h1 class="mt-8 text-4xl text-center font-light">Welcome to {{ env('APP_NAME') }}</h1>
    <hr class="my-6 border-slate-500">
    <p class="text-center text-2xl font-light">This is a fresh install, make sure to check DB in .env and migrate before continuing.</p>

    <section class="my-12 mx-auto pb-2 px-2 py-2 bg-slate-300 shadow-lg shadow-slate-500 rounded-lg w-full max-w-3xl border-slate-900 border-4" style="background: linear-gradient(to right,#ee7724,#d8363a,#dd3675,#b44593);">
        <table class="w-full">
            <thead class="text-left text-gray-100">
                <tr>
                    <th class="px-2 py-1 text-xl font-bold" colspan="2">.ENV</th>
                </tr>
            </thead>
            <tbody class="">
                @php $keyBuffer = null; @endphp
                @foreach ([
                        'APP_NAME',
                        'APP_ENV',
                        'APP_DEBUG',
                        'APP_URL',
                        'DB_CONNECTION',
                        'DB_HOST',
                        'DB_PORT',
                        'DB_DATABASE',
                        'DB_USERNAME',
                        'DB_PASSWORD',
                        'MAIL_MAILER',
                        'MAIL_HOST',
                        'MAIL_PORT',
                        'MAIL_USERNAME',
                        'MAIL_PASSWORD',
                        'MAIL_ENCRYPTION',
                        'MAIL_FROM_ADDRESS',
                        'MAIL_FROM_NAME'
                    ] as $key)
                    @php
                        $keySection = explode('_', $key)[0];
                        if($keySection !== $keyBuffer) {
                            $keyBuffer = $keySection;
                            echo '<tr><td colspan="2" class="h-2"></td></tr>';
                            echo '<tr class="bg-slate-800 text-gray-100"><td colspan="2" class="px-2 py-1" style="border-bottom: 2px solid #c057a1;">' . $keySection . '</td></tr>';
                        }
                    @endphp
                    <tr>
                        <td class="px-2 py-1 text-white">{{ $key }}</td>
                        <td class="px-2 py-1 text-white">{{ env($key) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

</x-frontend-layout>
