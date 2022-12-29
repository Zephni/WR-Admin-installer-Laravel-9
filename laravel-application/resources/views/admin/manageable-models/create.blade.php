<x-admin-layout>

    <x-slot name="title">Creating new {{ strtolower($model->getHumanName(false)) }}</x-slot>

    <h2 class="text-4xl font-bold">Creating new {{ strtolower($model->getHumanName(false)) }}</h2>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="w-full">
        <form action="{{ route('admin.manageable-models.create.submit', ['table' => $model->getTable()]) }}" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <x-admin.manageable-fields.input name="table" type="hidden" value="{{ $model->getTable() }}" />

            @foreach($fields as $field)
                @if(is_string($field))
                    {!! $field !!}
                @elseif(is_a($field, \App\Classes\ManageableFields\ManageableField::class))
                    {{ $field->render() }}
                @endif
            @endforeach

            <x-admin.manageable-fields.submit value="Create" />
        </form>
    </div>

</x-admin-layout>
