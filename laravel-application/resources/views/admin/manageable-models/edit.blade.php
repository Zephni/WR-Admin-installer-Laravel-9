<x-admin-layout>

    <x-slot name="title">Editing {{ strtolower($model->getHumanName(false)) }} #{{ $model->id }}</x-slot>

    <h2 class="text-3xl font-bold text-gray-300">Editing {{ strtolower($model->getHumanName(false)) }} #{{ $model->id }}</h2>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="w-full">
        <form action="{{ route('admin.manageable-models.edit.submit', ['table' => $model->getTable(), 'id' => $model->id]) }}" method="post" class="border-b bg-gray-800 border-gray-700 rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <x-admin.manageable-fields.input name="table" type="hidden" value="{{ $model->getTable() }}" />

            <x-admin.manageable-fields.input name="id" type="hidden" value="{{ $model->id }}" />

            @foreach($fields as $field)
                @if(is_string($field))
                    {!! $field !!}
                @elseif(is_a($field, \App\Classes\ManageableFields\ManageableField::class))
                    {{ $field->render() }}
                @endif
            @endforeach

            <x-admin.manageable-fields.submit value="Save" />
        </form>
    </div>

</x-admin-layout>
