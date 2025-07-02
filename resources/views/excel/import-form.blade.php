@extends('layouts.theme.app')

@section('content')
    @include('layouts.theme.partials.breadcrumb', ['breadcrumb' => 'Importar Excel'])

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h6>Cargar Informacion del Sistema Gestion Financiera</h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                            @csrf
                            <div class="form-group">
                                <label for="excel_file">Seleccionar archivo Excel:</label>
                                <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls" class="form-control" required>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-outline-primary">Previsualizar</button>
                                <button type="button" id="clear-form" class="btn btn-outline-warning">Limpiar Formulario</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const clearButton = document.getElementById('clear-form');
    const fileInput = document.getElementById('excel_file');
    const uploadForm = document.getElementById('upload-form');

    // Limpiar formulario
    if (clearButton && fileInput) {
        clearButton.addEventListener('click', function () {
            Swal.fire({
                title: '¿Limpiar formulario?',
                text: "Se eliminará el archivo seleccionado.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fileInput.value = '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Formulario limpio',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    }

    // Spinner al enviar
    if (uploadForm) {
        uploadForm.addEventListener('submit', function () {
            Swal.fire({
                title: 'Procesando...',
                html: 'Por favor espera...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }

    // Mensajes al volver
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Importación cancelada',
            text: '{{ session('info') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif
});
</script>
@endpush
