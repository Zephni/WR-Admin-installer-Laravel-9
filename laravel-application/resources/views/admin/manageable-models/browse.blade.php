<x-admin-layout>

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="relative text-3xl font-bold text-gray-300">{{ $pageTitle }}</div>

    <hr class="my-4 h-px bg-gray-500 border-0">

    <div class="w-full mb-6 flex justify-between">
        <span>
            @if(request()->get('search')) Filtered @else Total @endif
            {{ Str::lower($model->getHumanName()) }}: <b>{{ $rows->total() }}</b>
        </span>
        <x-admin.button
            href="{{ route('admin.manageable-models.create', ['table' => $model->getTable()]) }}"
            text="Create new"
            type="primary" />
    </div>

    <div class="flex justify-end mb-6">
        <form action="" method="get">
            <div class="">
                <input type="text" name="search" value="{{ request()->get('search') }}" class="w-64 px-2 py-1 rounded border border-gray-500 text-gray-200 bg-gray-800 focus:outline-none focus:border-teal-500" placeholder="Search...">
                <button type="submit" class="ml-2 px-2 py-1 rounded border border-gray-500 text-gray-200 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:border-teal-500">Search</button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto relative">
        <table class="w-full text-sm text-left text-gray-200">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    @foreach($columns as $column)
                        <th class="px-2 py-2 w-auto">{{ Str::of($column)->replace('_', ' ')->title() }}</th>
                    @endforeach
                    @php $browseKeys = []; @endphp
                    @foreach($model->browseActions() as $key => $action)
                        @php $browseKeys[] = $key; @endphp
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
                                else if($value instanceof \Carbon\Carbon)
                                {
                                    $value = $value->format('Y-m-d H:ia');
                                }
                                else
                                {
                                    $value = '';// TODO: Implement other types of values
                                }
                            @endphp
                            <td class="px-2 py-3">{{ $value }}</td>
                        @endforeach
                        @php
                            $rowBrowseActions = $row->browseActions();
                            $rowBrowseActions = array_merge(array_fill_keys($browseKeys, false), $rowBrowseActions);
                        @endphp
                        @foreach($rowBrowseActions as $key => $action)
                            <td class="px-2 py-2">
                                @if($action != false)
                                    <x-admin.button
                                        href="{{ $action['href'] ?? '#' }}"
                                        text="{{ $action['text'] ?? 'Button' }}"
                                        type="{{ $action['type'] ?? 'primary' }}"
                                        confirm="{{ $action['confirm'] ?? '0' }}" />
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($rows) === 0)
            <div class="w-full mt-10 flex justify-center items-center">
                <div class="text-1xl font-bold text-gray-500">
                    No {{ Str::lower($model->getHumanName()) }} found,
                    <a href="{{ route('admin.manageable-models.create', ['table' => $model->getTable()]) }}" class="text-teal-500 hover:text-teal-400">create new</a>.
                </div>
            </div>
        @else
            <div class="flex items-center flex-col mt-10">
                {{ $rows->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
