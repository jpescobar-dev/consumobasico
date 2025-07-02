@extends('layouts.theme.app')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Detalle Cliente Medidor'])

<div class="container">
    <table class="table table-bordered">
        <tr><th>Número Cliente</th><td>{{ $clientesmedidor->numerocliente }}</td></tr>
        <tr><th>Medidor</th><td>{{ $clientesmedidor->medidor }}</td></tr>
        <tr><th>Proveedor</th><td>{{ $clientesmedidor->rutproveedor }}</td></tr>
        <tr><th>Centro Costo</th><td>{{ $clientesmedidor->ccosto }}</td></tr>
        <tr><th>Tipo</th><td>{{ $clientesmedidor->tipo }}</td></tr>
        <tr><th>Tarifa</th><td>{{ $clientesmedidor->tarifa }}</td></tr>
        <tr><th>Vigente</th><td>{{ $clientesmedidor->vigente ? 'Sí' : 'No' }}</td></tr>
        <tr><th>Creado</th><td>{{ $clientesmedidor->created_at }}</td></tr>
        <tr><th>Actualizado</th><td>{{ $clientesmedidor->updated_at }}</td></tr>
    </table>
    <a href="{{ route('clientesmedidores.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
