@extends('layouts.theme.app')

@section('title', 'Detalle DTE')
@section('title2', 'Editar Detalle')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Editar Detalle DTE'])

<div class="container mt-4">
    <form action="{{ route('dtes.detalle.update', $dte->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12 mb-3">
                <h5>DTE N° {{ $dte->NumeroDte }} - {{ $dte->NombreEmisor }}</h5>
            </div>

            @foreach ([
                'tipo' => 'Tipo',
                'numerocliente' => 'Número Cliente',
                'periodoconsumo' => 'Periodo Consumo',
                'lecturaanterior' => 'Lectura Anterior',
                'lecturaactual' => 'Lectura Actual',
                'consumo' => 'Consumo'
            ] as $name => $label)
                <div class="col-md-6 mb-3">
                    <label for="{{ $name }}">{{ $label }}</label>
                    <input type="{{ in_array($name, ['lecturaanterior', 'lecturaactual', 'consumo']) ? 'number' : 'text' }}"
                           class="form-control @error($name) is-invalid @enderror"
                           name="{{ $name }}" value="{{ old($name, $detalle->$name) }}">
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-warning">Actualizar Detalle</button>
            <a href="{{ route('dtes.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
