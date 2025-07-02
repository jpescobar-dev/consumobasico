@extends('layouts.theme.app')

@section('title', 'Crear Catálogo')
@section('title2', 'Nuevo Registro')

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    @include('partials.alerts')

    <form action="{{ route('catalogos.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="catalogo">Código del Catálogo</label>
            <input type="text" name="catalogo" id="catalogo"
                   class="form-control @error('catalogo') is-invalid @enderror"
                   value="{{ old('catalogo') }}" maxlength="10" required>
            @error('catalogo')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion"
                   class="form-control @error('descripcion') is-invalid @enderror"
                   value="{{ old('descripcion') }}" maxlength="100" required>
            @error('descripcion')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select name="estado" id="estado"
                    class="form-control @error('estado') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estado')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="item">Ítem Asociado</label>
            <select name="item" id="item"
                    class="form-control @error('item') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($items as $i)
                    <option value="{{ $i->item }}" {{ old('item') == $i->item ? 'selected' : '' }}>
                        {{ $i->item }} - {{ $i->nombre }}
                    </option>
                @endforeach
            </select>
            @error('item')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group text-end">
            <a href="{{ route('catalogos.index') }}" class="btn btn-outline-warning btn-sm">Cancelar</a>
            <button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
        </div>
    </form>
</div>
@endsection
