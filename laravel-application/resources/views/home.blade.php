<x-frontend-layout>
    <x-slot name="title">Home</x-slot>

    <h1 class="mt-8 text-4xl text-center font-light">Welcome to {{ env('APP_NAME') }}</h1>
    <hr class="my-6 border-slate-500">
    <p class="text-center text-2xl font-light">This is a fresh install, make sure to check DB in .env and migrate before continuing.</p>

    <section class="w-full max-w-3xl my-12 mx-auto pb-2 px-2 py-2 bg-fancy shadow-lg shadow-slate-500 rounded-lg border-slate-900 border-4">
        <table class="w-full">
            <thead class="text-left text-gray-100">
                <tr>
                    <th class="px-2 py-1 text-xl font-bold" colspan="2">.ENV</th>
                </tr>
            </thead>
            <tbody>
                {{-- FRESH INSTALL, SHOW IMPORTANT ENV VARS FOR DEVELOPER --}}
                @foreach ([
                    'APP' => [
                        'APP_NAME',
                        'APP_ENV',
                        'APP_DEBUG',
                        'APP_URL',
                    ],
                    'DB' => [
                        'DB_CONNECTION',
                        'DB_HOST',
                        'DB_PORT',
                        'DB_DATABASE',
                        'DB_USERNAME',
                    ],
                    'MAIL' => [
                        'MAIL_MAILER',
                        'MAIL_HOST',
                        'MAIL_PORT',
                        'MAIL_USERNAME',
                        'MAIL_ENCRYPTION',
                        'MAIL_FROM_ADDRESS',
                        'MAIL_FROM_NAME',
                    ],
                ] as $envKeyGroup => $envKeys)
                    <tr><td colspan="2" class="h-2"></td></tr>
                    <tr class="bg-slate-800 text-gray-100"><td colspan="2" class="px-2 py-1" style="border-bottom: 2px solid #c057a1;">{{ $envKeyGroup }}</td></tr>

                    @foreach($envKeys as $envKey)
                        <tr>
                            <td class="px-2 py-1 text-white">{{ $envKey }}</td>
                            <td class="px-2 py-1 text-white">{{ env($envKey) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </section>
</x-frontend.layout>
