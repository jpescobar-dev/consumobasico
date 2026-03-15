@extends('layouts.theme.app')

@section('title', 'Ordenes de Compras')
@section('title2', 'Actualizar O.C')

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @include('layouts.theme.partials.breadcrumb')
                </div>
                <div>
                    <h4>Actualizar Orden de Compra</h4>
                </div>
                <div>
                    <a href="{{ route('ordenescompras.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
                  
                </div>
            </div>

            @include('partials.alerts')

            {{-- <div class="widget-content widget-content-area br-6 mt-2 mb-2"> --}}
            <div class="card shadow-sm  rounded p-4 mt-3">
    <form action="{{ route('ordenescompras.update', $ordencompra) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="orden_compra">Número OC</label>
                <input type="text" name="orden_compra" class="form-control" value="{{ $ordencompra->orden_compra }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $ordencompra->nombre) }}" required>
                @error('nombre')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="tipo">Tipo</label>
                <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror"
                       value="{{ old('tipo', $ordencompra->tipo) }}" required>
                @error('tipo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="estado">Estado</label>
                <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror"
                       value="{{ old('estado', $ordencompra->estado) }}">
                @error('estado')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="unidad_compra">Unidad de Compra</label>
                <input type="text" name="unidad_compra" class="form-control @error('unidad_compra') is-invalid @enderror"
                       value="{{ old('unidad_compra', $ordencompra->unidad_compra) }}">
                @error('unidad_compra')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="rutproveedor">Proveedor</label>
                <select name="rutproveedor" class="form-control @error('rutproveedor') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->rutproveedor }}"
                            {{ old('rutproveedor', $ordencompra->rutproveedor) == $proveedor->rutproveedor ? 'selected' : '' }}>
                            {{ $proveedor->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('rutproveedor')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_creacion">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control @error('fecha_creacion') is-invalid @enderror"
                       value="{{ old('fecha_creacion', $ordencompra->fecha_creacion) }}">
                @error('fecha_creacion')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_envio">Fecha de Envío</label>
                <input type="date" name="fecha_envio" class="form-control @error('fecha_envio') is-invalid @enderror"
                       value="{{ old('fecha_envio', $ordencompra->fecha_envio) }}">
                @error('fecha_envio')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            @foreach (['monto_neto', 'descuentos', 'cargos', 'iva', 'impuesto_especifico', 'total'] as $campo)
                <div class="col-md-4 mb-3">
                    <label for="{{ $campo }}">{{ ucwords(str_replace('_', ' ', $campo)) }}</label>
                    <input type="number" step="0.01" name="{{ $campo }}" class="form-control @error($campo) is-invalid @enderror"
                           value="{{ old($campo, $ordencompra->$campo) }}">
                    @error($campo)<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
            @endforeach
        </div>

        <div class="form-group text-end">
            <a href="{{ route('ordenescompras.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
            <button type="submit" class="btn btn-outline-primary btn-sm">Actualizar</button>
        </div>
    </form>
</div>
@endsection
