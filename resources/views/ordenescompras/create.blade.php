@extends('layouts.theme.app')

@section('title', 'Ordenes de Compras')
@section('title2', 'Nueva O.C')

@section('header_actions')
    <a href="{{ route('ordenescompras.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
@endsection

@section('content')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @include('layouts.theme.partials.breadcrumb')
                </div>
                <div>
                    <h4>Crear Orden de Compra</h4>
                </div>
                <div>
                    <a href="{{ route('ordenescompras.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
                </div>
            </div>

            @include('partials.alerts')

            <div class="widget-content widget-content-area br-6 mt-2 mb-2">

    <form action="{{ route('ordenescompras.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="orden_compra">Número OC</label>
                <input type="text" name="orden_compra" class="form-control @error('orden_compra') is-invalid @enderror"
                       value="{{ old('orden_compra') }}" required maxlength="50">
                @error('orden_compra')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre') }}" required maxlength="255">
                @error('nombre')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="tipo">Tipo</label>
                <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror"
                       value="{{ old('tipo') }}" required>
                @error('tipo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="estado">Estado</label>
                <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror"
                       value="{{ old('estado') }}">
                @error('estado')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="unidad_compra">Unidad de Compra</label>
                <input type="text" name="unidad_compra" class="form-control @error('unidad_compra') is-invalid @enderror"
                       value="{{ old('unidad_compra', '2182') }}">
                @error('unidad_compra')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="rutproveedor">Proveedor</label>
                <select name="rutproveedor" class="form-control @error('rutproveedor') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->rutproveedor }}" {{ old('rutproveedor') == $proveedor->rutproveedor ? 'selected' : '' }}>
                            {{ $proveedor->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('rutproveedor')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_creacion">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control @error('fecha_creacion') is-invalid @enderror"
                       value="{{ old('fecha_creacion') }}">
                @error('fecha_creacion')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_envio">Fecha de Envío</label>
                <input type="date" name="fecha_envio" class="form-control @error('fecha_envio') is-invalid @enderror"
                       value="{{ old('fecha_envio') }}">
                @error('fecha_envio')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="monto_neto">Monto Neto</label>
                <input type="number" name="monto_neto" step="0.01"
                       class="form-control @error('monto_neto') is-invalid @enderror"
                       value="{{ old('monto_neto') }}">
                @error('monto_neto')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="descuentos">Descuentos</label>
                <input type="number" name="descuentos" step="0.01"
                       class="form-control @error('descuentos') is-invalid @enderror"
                       value="{{ old('descuentos') }}">
                @error('descuentos')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="cargos">Cargos</label>
                <input type="number" name="cargos" step="0.01"
                       class="form-control @error('cargos') is-invalid @enderror"
                       value="{{ old('cargos') }}">
                @error('cargos')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="iva">IVA</label>
                <input type="number" name="iva" step="0.01"
                       class="form-control @error('iva') is-invalid @enderror"
                       value="{{ old('iva') }}">
                @error('iva')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="impuesto_especifico">Impuesto Específico</label>
                <input type="number" name="impuesto_especifico" step="0.01"
                       class="form-control @error('impuesto_especifico') is-invalid @enderror"
                       value="{{ old('impuesto_especifico') }}">
                @error('impuesto_especifico')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="total">Total</label>
                <input type="number" name="total" step="0.01"
                       class="form-control @error('total') is-invalid @enderror"
                       value="{{ old('total') }}">
                @error('total')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>
        </div>
       
         <div class="form-group text-end">
                        <a href="{{ route('ordenescompras.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
                    </div>
    </form>
</div>
@endsection
