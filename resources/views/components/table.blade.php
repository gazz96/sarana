
<div class="table-responsive scrollbar bg-white">
    <table class="table fs--1 mb-0 border-top border-200">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th class="{{ $column['class'] ?? '' }}">
                        @if ($column['sortable'] ?? false)
                            <a href="?sort={{ $column['name'] }}&sortBy={{ request('sortBy') == 'DESC' ? 'ASC' : 'DESC' }}">
                        @endif
                        {{ $column['label'] ?? '' }}
                        @if ($column['sortable'] ?? false)
                            </a>
                        @endif

                    </th>
                @endforeach
            </tr>

        </thead>
        <tbody>
            @foreach ($models as $model)
                <tr>
                    @foreach ($columns as $column)

                        <td class="{{ $column['class'] ?? '' }}">

                            @if (isset($column['callback']) && is_callable($column['callback']))
                                {!! $column['callback']($model) !!}
                                @continue
                            @endif

                            @if(isset($model[$column['name'] ?? '']))
                                {!! $model[$column['name']] !!}
                                @continue
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        
            <tfoot>
                <tr>
                    @foreach ($columns as $column)
                        @if ($column['searchable'] ?? false)
                            <td>
                                <form action="" method="GET">
                                    <input type="text" name="{{ $column['name'] }}" class="form-control form-control-sm"
                                    placeholder="Search ..." value="{{ request($column['name']) }}">
                                </form>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            </tfoot>
    </table>
    {{ $models->links() }}
</div>