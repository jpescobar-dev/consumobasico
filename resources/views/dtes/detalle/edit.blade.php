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
            <!-- Columna 1: PDF -->
            <div class="col-md-4 mb-4">
                <h6 class="text-muted">Documento PDF</h6>
                <iframe src="{{ $dte->Url }}" width="100%" height="600px" frameborder="0"></iframe>
            </div>

            <!-- Columnas 2 y 3: Formulario -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 mb-3">
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
                                value="{{ old($name, $detalle->$name) }}"
                                required
                            >
                            @error($name)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-save"></i> Actualizar Detalle
                        </button>
                        <a href="{{ route('consultas.electricidad.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
