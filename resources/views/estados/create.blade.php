@extends('layouts.theme.app')
@section('title', 'Estados')
@section('title2','Nuevo')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Nuevo Estado
        </div>
        <div class="card-body">
            <form action="{{ route('estados.store') }}" method="POST">
                @csrf
                @include('estados.form')
                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                <a href="{{ route('cfinancieros.index') }}" class="btn btn-outline-warning">Volver</a>
            </form>
        </div>
    </div>
</div>




@endsection
