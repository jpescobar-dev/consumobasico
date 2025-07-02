@extends('layouts.theme.app')

@section('title', 'Ver Usuario')
@section('title2', 'Detalle del Usuario')

@section('content')
<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <div class="form-group mb-3">
        <label class="fw-bold">Nombre:</label>
        <div>{{ $user->name }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Correo Electrónico:</label>
        <div>{{ $user->email }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Roles Asignados:</label>
        <div>
            @forelse($user->getRoleNames() as $role)
                <span class="badge badge-primary">{{ $role }}</span>
            @empty
                <span class="text-muted">Sin roles asignados</span>
            @endforelse
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="fw-bold">Creado el:</label>
        {{-- <div>{{ $user->created_at->format('d/m/Y H:i') }}</div> --}}
        <div>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</div>
    </div>

    <div class="form-group text-end mt-4">
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Volver al listado</a>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Editar</a>
    </div>
</div>
@endsection
