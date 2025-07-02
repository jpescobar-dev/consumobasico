<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Excel</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        input[type="file"] { margin: 10px 0; }
        button { padding: 5px 10px; }
        .success { color: green; font-weight: bold; }
        .errors { color: red; }
    </style>
</head>
<body>
    <h1>Subir archivo Excel</h1>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="excel_file">Seleccionar archivo Excel:</label>
        <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
        <button type="submit">Subir</button>
    </form>
</body>
</html>
