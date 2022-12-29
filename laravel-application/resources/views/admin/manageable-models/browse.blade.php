<x-admin-layout>

    <x-slot name="title">{{ $model->getHumanName(true) }}</x-slot>

    <h2 class="text-4xl font-bold">{{ $model->getHumanName(true) }}</h2>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <table class="table w-full text-left">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th class="px-1 py-2">{{ Str::ucfirst($column) }}</th>
                @endforeach
                @foreach($model->browseActions() as $action)
                    <th class="px-1 py-2"></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach($columns as $column)
                        @php
                            $value = $row->$column;

                            if(is_string($value))
                            {
                                $value = Str::limit($value, 30, '...');
                            }
                            else
                            {
                                $value = '';// TODO: Implement other types of values
                            }
                        @endphp
                        <td class="px-1 py-2">{{ $value }}</td>
                    @endforeach
                    @foreach($row->browseActions() as $actionKey => $actionValue)
                        <td class="px-1 py-2">
                            <a href="{{ $routePrefix }}/{{ $actionValue }}" class="bg-teal-500 text-white active:bg-teal-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:bg-teal-700 hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">{{ $actionKey }}</a>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</x-admin-layout>
