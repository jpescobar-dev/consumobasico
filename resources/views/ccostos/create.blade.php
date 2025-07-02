@extends('layouts.theme.app')

@section('title', 'Crear Centro de Costo')
@section('title2', 'Nuevo Registro')

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    @include('partials.alerts')

    <form action="{{ route('ccostos.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="ccosto">Código Centro de Costo</label>
            <input type="text" name="ccosto" id="ccosto" 
                   class="form-control @error('ccosto') is-invalid @enderror" 
                   value="{{ old('ccosto') }}" maxlength="10" required>
            @error('ccosto')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" 
                   class="form-control @error('nombre') is-invalid @enderror" 
                   value="{{ old('nombre') }}" maxlength="150" required>
            @error('nombre')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="cfinanciero">Centro Financiero</label>
            <select name="cfinanciero" id="cfinanciero" class="form-control @error('cfinanciero') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($cfinancieros as $cf)
                    <option value="{{ $cf->cfinanciero }}" {{ old('cfinanciero') == $cf->cfinanciero ? 'selected' : '' }}>
                        {{ $cf->cfinanciero }} - {{ $cf->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cfinanciero')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group text-end">
            <a href="{{ route('ccostos.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
            <button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
        </div>
    </form>
</div>
@endsection
