@extends('layouts.theme.app')

@section('title', 'Ver Catálogo')
@section('title2', 'Detalle')

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div class="form-group mb-3">
        <label class="fw-bold">Código del Catálogo:</label>
        <div>{{ $catalogo->catalogo }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Nombre:</label>
        <div>{{ $catalogo->nombre }}</div>
    </div>
    <div class="form-group mb-3">
        <label class="fw-bold">Descripción:</label>
        <div>{{ $catalogo->descripcion }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Estado:</label>
        <div>{{ $catalogo->estado }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Ítem Asociado:</label>
        <div>
            {{ $catalogo->item->item ?? 'N/A' }} - {{ $catalogo->item->nombre ?? 'Sin asignar' }}
        </div>
    </div>

    <div class="form-group text-end mt-4">
        <a href="{{ route('catalogos.index') }}" class="btn btn-secondary btn-sm">Volver</a>
        <a href="{{ route('catalogos.edit', $catalogo) }}" class="btn btn-warning btn-sm">Editar</a>
    </div>
</div>
@endsection
