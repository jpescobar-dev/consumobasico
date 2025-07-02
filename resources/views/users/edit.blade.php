@extends('layouts.theme.app')

@section('title', 'Editar Usuario')
@section('title2', 'Modificar Registro')

@section('content')
@include('partials.alerts')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Roles</label><br>
            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                           id="role_{{ $role->id }}"
                           {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                    <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
