@extends('layouts.theme.app')

@section('title', 'Crear Centro Financiero')
@section('title2', 'Nuevo Registro')

@section('content')

@include('partials.alerts')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <form action="{{ route('cfinancieros.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="cfinanciero">Código Centro Financiero</label>
            <input type="text" name="cfinanciero" id="cfinanciero" class="form-control @error('cfinanciero') is-invalid @enderror" value="{{ old('cfinanciero') }}" maxlength="4" required>
            @error('cfinanciero')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" maxlength="50" required>
            @error('nombre')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>        
        

        <div class="form-group text-end">
            <a href="{{ route('cfinancieros.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
            <button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
        </div>
    </form>
</div>
@endsection
