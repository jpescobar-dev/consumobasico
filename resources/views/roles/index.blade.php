@extends('layouts.theme.app')

@section('title', 'Roles')
@section('title2', 'Listado de Roles')

@section('content')
@include('partials.alerts')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nuevo Rol
    </a>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->permissions as $perm)
                                <span class="badge badge-info">{{ $perm->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este rol?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
