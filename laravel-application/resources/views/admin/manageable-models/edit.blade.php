<x-admin-layout>

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <h2 class="text-4xl font-light text-gray-300">{{ $pageTitle }}</h2>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="w-full">
        <form action="{{ $submitRoute }}" method="post" class="border-b bg-gray-800 border-gray-700 rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <x-admin.manageable-fields.input name="table" type="hidden" value="{{ $model->getTable() }}" />

            <x-admin.manageable-fields.input name="on_success_redirect" type="hidden" value="{{ $onSuccessRedirect }}" />

            <x-admin.manageable-fields.input name="id" type="hidden" value="{{ $model->id }}" />

            @foreach($fields as $field)
                @if(is_string($field))
                    {!! $field !!}
                @elseif(is_a($field, \App\Classes\ManageableFields\ManageableField::class))
                    {{ $field->render() }}
                @endif
            @endforeach

            <x-admin.manageable-fields.submit value="{{ $submitText }}" icon="bi bi-pencil-square" />
        </form>
    </div>

</x-admin-layout>
