<table>
    <thead>
        <tr>
            <th>Barbero</th>
            <th>Ganancia</th>
        </tr>
    </thead>
    <tbody>
        @foreach($resultados as $item)
            <tr>
                <td>{{ $item->barbero->name }}</td>
                <td>{{ $item->total_ganancia }}</td>
            </tr>
        @endforeach
    </tbody>
</table>