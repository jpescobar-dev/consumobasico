<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista previa del Excel</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Vista previa de datos importados</h1>


    
    @php
        $camposExtras = ['tipodocumento', 'numerodocumento', 'formaingreso', 'rutproveedor', 'nombreproveedor', 'url'];
        $allColumns = collect($rows)
            ->flatMap(fn($row) => array_keys($row))
            ->merge($camposExtras)
            ->unique()
            ->values()
            ->all();
    @endphp
   
        
    <table>
        <thead>
            <tr>
                @foreach ($allColumns as $col)
                    <th>{{ ucfirst($col) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($allColumns as $col)
                        <td>{{ $row[$col] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
