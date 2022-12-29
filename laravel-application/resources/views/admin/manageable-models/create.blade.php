<x-admin-layout>

    <x-slot name="title">Creating new {{ strtolower($model->getHumanName(false)) }}</x-slot>

    <h2 class="text-4xl font-bold">Creating new {{ strtolower($model->getHumanName(false)) }}</h2>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="w-full">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @foreach($fields as $field)
                {{ $field->render() }}
            @endforeach
        </form>
    </div>

</x-admin-layout>
