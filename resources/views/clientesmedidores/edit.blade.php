@extends('layouts.theme.app')

@section('title', 'Clientes Medidores')
@section('title2', 'Editar')

@section('content')

    <div class="container">
        <form action="{{ route('clientesmedidores.update', $clientemedidor) }}" method="POST">
            @csrf
            @method('PUT')

            @include('clientesmedidores.partials.form')

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('clientesmedidores.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
