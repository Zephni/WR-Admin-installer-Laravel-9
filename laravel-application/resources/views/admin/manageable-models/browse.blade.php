<x-admin-layout>

    <x-slot name="title">{{ $model->getHumanName(true) }}</x-slot>

    <div class="relative text-4xl font-bold text-gray-300">
        {{ $model->getHumanName(true) }}
        <a href="{{ route('admin.manageable-models.create', ['table' => $model->getTable()]) }}" class="absolute right-0 bg-teal-700 hover:bg-teal-600 active:bg-teal-600 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">Create new</a>
    </div>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="overflow-x-auto relative">
        <table class="w-full text-sm text-left text-gray-200">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    @foreach($columns as $column)
                        <th class="px-2 py-2 w-auto">{{ Str::of($column)->replace('_', ' ')->title() }}</th>
                    @endforeach
                    @foreach($model->browseActions() as $action)
                        <th class="px-2 py-2 w-16"></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="border-b bg-gray-800 border-gray-700">
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
                            <td class="px-2 py-3">{{ $value }}</td>
                        @endforeach
                        @foreach($row->browseActions() as $actionKey => $actionValue)
                            <td class="px-2 py-2">
                                <x-admin.button
                                    href="{{ $actionValue['href'] ?? '#' }}"
                                    text="{{ $actionValue['text'] ?? 'Button' }}"
                                    type="{{ $actionValue['type'] ?? 'primary' }}"
                                    confirm="{{ $actionValue['confirm'] ?? '0' }}" />
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-admin-layout>
