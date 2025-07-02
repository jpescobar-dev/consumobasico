@extends('layouts.theme.app')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Crear Cliente Medidor'])

<div class="container">
    <form action="{{ route('clientesmedidores.store') }}" method="POST">
        @include('clientesmedidores.partials.form')
       
        <a href="{{ route('clientesmedidores.index') }}" class="btn btn-outline-warning">Cancelar</a>   
         <button type="submit" class="btn btn-outline-primary">Guardar</button>     
    </form>
</div>
@endsection
