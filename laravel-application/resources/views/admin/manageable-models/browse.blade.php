<x-admin-layout>

    <x-slot name="title">{{ $model->getHumanName(true) }}</x-slot>

    <h2 class="text-4xl font-bold">{{ $model->getHumanName(true) }}</h2>

    <table class="w-full text-left">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
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
                                $value = Str::limit($value, 50);
                            }
                            else
                            {
                                $value = '';
                            }
                        @endphp
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</x-admin-layout>
