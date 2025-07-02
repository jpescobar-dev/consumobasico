@extends('layouts.theme.app')

@section('content')
{{-- @include('layouts.theme.partials.breadcrumb', ['breadcrumb' => 'Vista Previa de Excel']) --}}

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h4>Documentos válidos para guardar</h4>
                </div>

                <div class="card-body table-responsive">
                    @if ($validos->isEmpty())
                        <div class="alert alert-warning">No hay documentos válidos.</div>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    @foreach ($headers as $col)
                                        <th>{{ ucfirst($col) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($validos as $row)
                                    <tr>
                                        @foreach ($headers as $col)
                                            <td>{{ $row[$col] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>

            @if ($rechazados->isNotEmpty())
            <div class="card mt-4">
                <div class="card-header bg-warning">
                    <h4>Documentos rechazados (errores)</h4>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-danger">
                        <thead>
                            <tr>
                                @foreach ($headers as $col)
                                    <th>{{ ucfirst($col) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rechazados as $row)
                                <tr>
                                    @foreach ($headers as $col)
                                        <td>{{ $row[$col] ?? '' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="mt-4">
                <form action="{{ route('excel.store') }}" method="POST" id="confirm-form" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirmar Guardado</button>
                </form>

                <form action="{{ route('excel.cancel') }}" method="POST" id="cancel-form" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancelar</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($rechazados->isNotEmpty())
        Swal.fire({
            icon: 'warning',
            title: '¡Atención!',
            text: 'Se encontraron documentos con errores. Revísalos antes de guardar.',
            timer: 5000,
            showConfirmButton: true
        });
    @endif
});
</script>
@endpush

