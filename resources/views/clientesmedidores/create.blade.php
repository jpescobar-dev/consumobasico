@extends('layouts.theme.app')

@section('title', 'Clientes Medidores')
@section('title2', 'Crear')

@section('content')

<div class="container">
    <form action="{{ route('clientesmedidores.store') }}" method="POST">
        @include('clientesmedidores.partials.form')
       
        <a href="{{ route('clientesmedidores.index') }}" class="btn btn-outline-warning">Cancelar</a>   
         <button type="submit" class="btn btn-outline-primary">Guardar</button>     
    </form>
</div>
@endsection
