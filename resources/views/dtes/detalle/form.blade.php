@extends('layouts.theme.app')

@section('title', 'Detalle DTE')
@section('title2', 'Crear Detalle')

@section('content')
@include('layouts.theme.partials.breadcrumb', ['titulo' => 'Crear Detalle DTE'])
<div class="widget-content widget-content-area br-6 mt-2 mb-2">

    <form action="{{ route('dtes.detalle.store', $dte->id) }}" method="POST">
        @csrf
        <div class="row">
            <!-- Columna 1: PDF -->
            <div class="col-md-8 mb-2">      
                <iframe src="{{ $dte->Url }}" width="100%" height="600px" frameborder="0"></iframe>
            </div>

            <!-- Columnas 2 y 3: Formulario -->
            <div class="col-md-4 mb-6">
                <div class="row">
                    <div class="col-12 mb-6">
                        <h5 class="text-primary">
                            DTE N° {{ $dte->NumeroDte }} — {{ $dte->NombreEmisor }}
                        </h5>
                        <p>
                            <strong>Periodo:</strong> {{ $dte->Periodo }} |
                            <strong>Fecha:</strong> {{ $dte->Fecha }}
                        </p>
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

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-save"></i> Guardar Detalle
                        </button>
                        <a href="{{ route('consultas.electricidad.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


@endsection
