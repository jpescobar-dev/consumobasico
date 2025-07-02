@extends('layouts.theme.app')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Editar Cliente Medidor'])

<div class="container">
    <form action="{{ route('clientesmedidores.update', $clientesmedidor->numerocliente) }}" method="POST">
        @csrf
        @method('PUT')
        @include('clientesmedidores.partials.form')
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('clientesmedidores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
