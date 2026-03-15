@extends('layouts.theme.app')

@section('title', 'Ordenes de Compras')
@section('title2', 'Vista Detalle')

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">

    <div class="form-group mb-2">
        <label class="fw-bold">Número OC:</label>
        <div>{{ $ordencompra->orden_compra }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Nombre:</label>
        <div>{{ $ordencompra->nombre }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Tipo:</label>
        <div>{{ $ordencompra->tipo }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Estado:</label>
        <div>{{ $ordencompra->estado ?? '-' }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Unidad de Compra:</label>
        <div>{{ $ordencompra->unidad_compra }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Proveedor:</label>
        <div>{{ $ordencompra->proveedor->nombre ?? '-' }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Fecha de Creación:</label>
        <div>{{ $ordencompra->fecha_creacion ?? '-' }}</div>
    </div>

    <div class="form-group mb-2">
        <label class="fw-bold">Fecha de Envío:</label>
        <div>{{ $ordencompra->fecha_envio ?? '-' }}</div>
    </div>

    @php
        $monetarios = [
            'Monto Neto' => $ordencompra->monto_neto,
            'Descuentos' => $ordencompra->descuentos,
            'Cargos' => $ordencompra->cargos,
            'IVA' => $ordencompra->iva,
            'Impuesto Específico' => $ordencompra->impuesto_especifico,
            'Total' => $ordencompra->total
        ];
    @endphp

    @foreach ($monetarios as $label => $valor)
        <div class="form-group mb-2">
            <label class="fw-bold">{{ $label }}:</label>
            <div>{{ $valor !== null ? '$' . number_format($valor, 0, ',', '.') : '-' }}</div>
        </div>
    @endforeach

    <div class="form-group text-end mt-4">
        <a href="{{ route('ordenescompras.index') }}" class="btn btn-secondary btn-sm">Volver</a>
        <a href="{{ route('ordenescompras.edit', $ordencompra) }}" class="btn btn-warning btn-sm">Editar</a>
    </div>

</div>
@endsection
