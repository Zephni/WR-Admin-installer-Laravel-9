@props([
    'value' => 'Submit',
])

<input
    class="inline-block px-6 py-2.5 bg-fancy text-white font-medium text-xs leading-tight uppercase cursor-pointer rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out w-full mb-3"
    type="submit"
    data-mdb-ripple="true"
    data-mdb-ripple-color="light"
    value="{{ $value }}" />
