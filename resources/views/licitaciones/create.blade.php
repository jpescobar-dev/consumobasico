@extends('layouts.theme.app')

@section('title', 'Licitaciones')
@section('title2', 'Nueva Licitacion')
@section('header_actions')
    <a href="{{ route('licitaciones.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
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
                    <h4>Crear Licitación</h4>
                </div>
                <div>
                    <a href="{{ route('licitaciones.index') }}" class="btn btn-outline-warning btn-sm">Volver</a>
                </div>
            </div>

            @include('partials.alerts')

            <div class="widget-content widget-content-area br-6 mt-2 mb-2">
                <form action="{{ route('licitaciones.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="numero_licitacion">Número Licitación</label>
                                <input type="text" name="numero_licitacion" id="numero_licitacion" class="form-control @error('numero_licitacion') is-invalid @enderror" value="{{ old('numero_licitacion') }}" required>
                                @error('numero_licitacion')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="estado">Estado</label>
                                <input type="text" name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado') }}">
                                @error('estado')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="tipo">Tipo</label>
                                <input type="text" name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo') }}">
                                @error('tipo')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="unidad_compra">Unidad de Compra</label>
                                <input type="text" name="unidad_compra" id="unidad_compra" class="form-control @error('unidad_compra') is-invalid @enderror" value="{{ old('unidad_compra') }}">
                                @error('unidad_compra')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="monto_total_estimado">Monto Total Estimado</label>
                                <input type="number" step="0.01" name="monto_total_estimado" id="monto_total_estimado" class="form-control @error('monto_total_estimado') is-invalid @enderror" value="{{ old('monto_total_estimado') }}">
                                @error('monto_total_estimado')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="numero_ofertas_recibidas">N° Ofertas Recibidas</label>
                                <input type="number" name="numero_ofertas_recibidas" id="numero_ofertas_recibidas" class="form-control @error('numero_ofertas_recibidas') is-invalid @enderror" value="{{ old('numero_ofertas_recibidas') }}">
                                @error('numero_ofertas_recibidas')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="fecha_publicacion">Fecha de Publicación</label>
                                <input type="datetime-local" name="fecha_publicacion" id="fecha_publicacion" class="form-control @error('fecha_publicacion') is-invalid @enderror" value="{{ old('fecha_publicacion') }}">
                                @error('fecha_publicacion')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="fecha_adjudicacion">Fecha de Adjudicación</label>
                                <input type="datetime-local" name="fecha_adjudicacion" id="fecha_adjudicacion" class="form-control @error('fecha_adjudicacion') is-invalid @enderror" value="{{ old('fecha_adjudicacion') }}">
                                @error('fecha_adjudicacion')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="descripcion">Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="form-group text-end">
                        <a href="{{ route('licitaciones.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
