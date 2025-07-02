@extends('layouts.theme.app')

@section('title', 'Detalle DTE')
@section('title2', 'Crear Detalle')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Crear Detalle DTE'])

<div class="container mt-4">
    <form action="{{ route('dtes.detalle.store', $dte->id) }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12 mb-3">
                <h5 class="text-primary">
                    DTE N° {{ $dte->NumeroDte }} — {{ $dte->NombreEmisor }}
                </h5>
                <p><strong>Periodo:</strong> {{ $dte->Periodo }} | <strong>Fecha:</strong> {{ $dte->Fecha }}</p>
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
                    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                    <input
                        type="{{ in_array($name, ['lecturaanterior', 'lecturaactual', 'consumo']) ? 'number' : 'text' }}"
                        class="form-control @error($name) is-invalid @enderror"
                        name="{{ $name }}"
                        value="{{ old($name) }}"
                        required
                    >
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-4">
             <a href="{{ route('consultas.electricidad.index') }}" class="btn btn-outline-warning">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-save"></i> Guardar Detalle
            </button>
           
        </div>
    </form>
</div>
@endsection
