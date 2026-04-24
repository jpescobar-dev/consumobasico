@extends('layouts.theme.app')

@section('title', 'CDPs')
@section('title2', 'Crear')

@section('content')


@include('partials.alerts')

<div class="widget-content widget-content-area br-6 mt-2 mb-2">
    <form action="{{ route('cdps.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="estado_id" value="1">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="num_cdp">N° CDP</label>
                    <input type="text" name="num_cdp" id="num_cdp"
                        class="form-control @error('num_cdp') is-invalid @enderror"
                        value="{{ old('num_cdp') }}" maxlength="50" required>
                    @error('num_cdp')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="fecha_cdp">Fecha CDP</label>
                    <input type="date" name="fecha_cdp" id="fecha_cdp"
                        class="form-control @error('fecha_cdp') is-invalid @enderror"
                        value="{{ old('fecha_cdp') }}" required>
                    @error('fecha_cdp')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="proceso_sgf">Proceso SGF</label>
                    <input type="number" name="proceso_sgf" id="proceso_sgf"
                        class="form-control @error('proceso_sgf') is-invalid @enderror"
                        value="{{ old('proceso_sgf') }}" min="1" required>
                    @error('proceso_sgf')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="cfinanciero_id">Centro Financiero</label>
                    <select name="cfinanciero_id" id="cfinanciero_id"
                        class="form-control @error('cfinanciero_id') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach ($cfinancieros as $cfinanciero)
                            <option value="{{ $cfinanciero->cfinanciero }}"
                                {{ old('cfinanciero_id') == $cfinanciero->cfinanciero ? 'selected' : '' }}>
                                {{ $cfinanciero->cfinanciero }} - {{ $cfinanciero->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cfinanciero_id')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="ccosto">Centro de Costo</label>
                    <select name="ccosto" id="ccosto"
                        class="form-control @error('ccosto') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach ($ccostos as $ccosto)
                            <option value="{{ $ccosto->ccosto }}"
                                {{ old('ccosto') == $ccosto->ccosto ? 'selected' : '' }}>
                                {{ $ccosto->ccosto }} - {{ $ccosto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('ccosto')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="requerimiento">Requerimiento</label>
                    <input type="text" name="requerimiento" id="requerimiento"
                        class="form-control @error('requerimiento') is-invalid @enderror"
                        value="{{ old('requerimiento') }}" maxlength="255">
                    @error('requerimiento')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="moneda">Moneda</label>
                    <select name="moneda" id="moneda"
                        class="form-control @error('moneda') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="CLP" {{ old('moneda') == 'CLP' ? 'selected' : '' }}>CLP</option>
                        <option value="UF" {{ old('moneda') == 'UF' ? 'selected' : '' }}>UF</option>
                    </select>
                    @error('moneda')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="total_moneda_compra">Total Moneda Compra</label>
                    <input type="number" step="0.0001" min="0" name="total_moneda_compra" id="total_moneda_compra"
                        class="form-control @error('total_moneda_compra') is-invalid @enderror"
                        value="{{ old('total_moneda_compra') }}">
                    @error('total_moneda_compra')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3" id="grupo_fecha_paridad">
                <div class="form-group mb-3">
                    <label for="fecha_paridad">Fecha Paridad</label>
                    <input type="date" name="fecha_paridad" id="fecha_paridad"
                        class="form-control @error('fecha_paridad') is-invalid @enderror"
                        value="{{ old('fecha_paridad', $fechaHoy) }}">
                    @error('fecha_paridad')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="paridad_mostrada">Paridad</label>
                    <input type="text" id="paridad_mostrada" class="form-control" value="" readonly>
                    <input type="hidden" name="paridad" id="paridad" value="{{ old('paridad') }}">
                    @error('paridad')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small id="paridad_help" class="text-muted"></small>
                </div>
            </div>

            <div class="col-md-2">
            <div class="form-group mb-3">
                <label for="monto_total_impto_incluido_mostrado">Monto Total</label>
                <input type="text" id="monto_total_impto_incluido_mostrado"
                    class="form-control"
                    value=""
                    readonly>

                <input type="hidden"
                    name="monto_total_impto_incluido"
                    id="monto_total_impto_incluido"
                    value="{{ old('monto_total_impto_incluido') }}">

                @error('monto_total_impto_incluido')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror

                <small id="monto_total_help" class="text-muted"></small>
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="st">ST</label>
                    <select name="st" id="st" class="form-control @error('st') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="22" {{ old('st') == '22' ? 'selected' : '' }}>22</option>
                        <option value="29" {{ old('st') == '29' ? 'selected' : '' }}>29</option>
                        <option value="31" {{ old('st') == '31' ? 'selected' : '' }}>31</option>
                    </select>
                    @error('st')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="catalogo">Catálogo</label>
                    <select name="catalogo" id="catalogo"
                        class="form-control @error('catalogo') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach ($catalogos as $catalogo)
                            <option value="{{ $catalogo->catalogo }}"
                                data-descripcion="{{ $catalogo->descripcion }}"
                                {{ old('catalogo') == $catalogo->catalogo ? 'selected' : '' }}>
                                {{ $catalogo->catalogo }} - {{ $catalogo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('catalogo')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="denominacion">Denominación</label>
                    <input type="text" name="denominacion" id="denominacion"
                        class="form-control @error('denominacion') is-invalid @enderror"
                        value="{{ old('denominacion') }}">
                    @error('denominacion')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="tipo_gasto1">Tipo Gasto 1</label>
                    <select name="tipo_gasto1" id="tipo_gasto1"
                        class="form-control @error('tipo_gasto1') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="GO" {{ old('tipo_gasto1') == 'GO' ? 'selected' : '' }}>GO</option>
                        <option value="INI" {{ old('tipo_gasto1') == 'INI' ? 'selected' : '' }}>INI</option>
                    </select>
                    @error('tipo_gasto1')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="proyecto_id">Proyecto</label>
                    <select name="proyecto_id" id="proyecto_id"
                        class="form-control @error('proyecto_id') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}"
                                {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->proyecto }} - {{ $proyecto->codigo }}
                            </option>
                        @endforeach
                    </select>
                    @error('proyecto_id')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="pp">PP</label>
                    <input type="number" name="pp" id="pp"
                        class="form-control @error('pp') is-invalid @enderror"
                        value="{{ old('pp', 100) }}" min="0" max="100">
                    @error('pp')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="tipo_gasto2">Tipo Gasto 2</label>
                    <select name="tipo_gasto2" id="tipo_gasto2"
                        class="form-control @error('tipo_gasto2') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="TRANSITORIO" {{ old('tipo_gasto2') == 'TRANSITORIO' ? 'selected' : '' }}>TRANSITORIO</option>
                        <option value="PERMANENTE" {{ old('tipo_gasto2') == 'PERMANENTE' ? 'selected' : '' }}>PERMANENTE</option>
                    </select>
                    @error('tipo_gasto2')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="total_compromiso">Total Compromiso</label>
                    <input type="number" step="0.01" min="0" name="total_compromiso" id="total_compromiso"
                        class="form-control @error('total_compromiso') is-invalid @enderror"
                        value="{{ old('total_compromiso') }}">
                    @error('total_compromiso')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="num_compromiso">N° Compromiso</label>
                    <input type="number" min="0" name="num_compromiso" id="num_compromiso"
                        class="form-control @error('num_compromiso') is-invalid @enderror"
                        value="{{ old('num_compromiso') }}">
                    @error('num_compromiso')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="validez">Validez</label>
                    <input type="text" name="validez" id="validez"
                        class="form-control @error('validez') is-invalid @enderror"
                        value="{{ old('validez') }}" maxlength="255">
                    @error('validez')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="documentos">Documentos</label>
                    <input type="file" name="documentos[]" id="documentos"
                        class="form-control @error('documentos') is-invalid @enderror @error('documentos.*') is-invalid @enderror"
                        multiple>
                    @error('documentos')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                    @error('documentos.*')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="text-muted">Puede subir varios archivos. Máximo 10 MB por archivo.</small>
                    <div id="lista-documentos" class="mt-2 small text-muted"></div>
                </div>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-3">
                <div class="form-check mb-3">
                    <input type="checkbox" name="cargado_cgu" id="cargado_cgu" class="form-check-input"
                        value="1" {{ old('cargado_cgu') ? 'checked' : '' }}>
                    <label class="form-check-label" for="cargado_cgu">Cargado CGU</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-check mb-3">
                    <input type="checkbox" name="comprometido_cgu" id="comprometido_cgu" class="form-check-input"
                        value="1" {{ old('comprometido_cgu') ? 'checked' : '' }}>
                    <label class="form-check-label" for="comprometido_cgu">Comprometido CGU</label>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" id="observaciones" rows="3"
                class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
            @error('observaciones')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="form-group text-end">
            <a href="{{ route('cdps.index') }}" class="btn btn-secondary btn-sm">Cancelar</a>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moneda = document.getElementById('moneda');
        const grupoFechaParidad = document.getElementById('grupo_fecha_paridad');
        const fechaParidad = document.getElementById('fecha_paridad');
        const paridad = document.getElementById('paridad');
        const paridadMostrada = document.getElementById('paridad_mostrada');
        const paridadHelp = document.getElementById('paridad_help');

        const totalMonedaCompra = document.getElementById('total_moneda_compra');
        const montoTotal = document.getElementById('monto_total_impto_incluido');
        const montoTotalMostrado = document.getElementById('monto_total_impto_incluido_mostrado');
        const montoTotalHelp = document.getElementById('monto_total_help');

        const catalogo = document.getElementById('catalogo');
        const denominacion = document.getElementById('denominacion');
        const documentos = document.getElementById('documentos');
        const listaDocumentos = document.getElementById('lista-documentos');

        const fechaHoy = @json($fechaHoy);
        const urlParidadUf = @json(route('cdps.paridad-uf'));

        function formatearNumero(valor, decimales = 2) {
            const numero = Number(valor);

            if (Number.isNaN(numero)) {
                return '';
            }

            return numero.toLocaleString('es-CL', {
                minimumFractionDigits: decimales,
                maximumFractionDigits: decimales
            });
        }

        function limpiarMontoTotal() {
            montoTotal.value = '';
            montoTotalMostrado.value = '';
            montoTotalHelp.textContent = '';
        }

        function calcularMontoTotal() {
            const total = parseFloat(totalMonedaCompra.value || 0);
            const valorParidad = parseFloat(paridad.value || 0);

            if (!moneda.value || !totalMonedaCompra.value || !paridad.value) {
                limpiarMontoTotal();
                return;
            }

            const calculado = total * valorParidad;

            // Se guarda entero, porque el campo en BD está definido sin decimales
            const montoRedondeado = Math.round(calculado);

            montoTotal.value = montoRedondeado;
            montoTotalMostrado.value = formatearNumero(montoRedondeado, 0);
            montoTotalHelp.textContent = `${formatearNumero(total, 4)} × ${formatearNumero(valorParidad, 2)}`;
        }

        async function consultarParidadUf(fecha) {
            paridad.value = '';
            paridadMostrada.value = '';
            paridadHelp.textContent = '';
            limpiarMontoTotal();

            if (!fecha) {
                return;
            }

            paridadMostrada.value = 'Consultando...';

            try {
                const response = await fetch(`${urlParidadUf}?fecha=${encodeURIComponent(fecha)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    paridad.value = '';
                    paridadMostrada.value = '';
                    paridadHelp.textContent = data.message ?? 'No existe paridad para la fecha seleccionada.';
                    limpiarMontoTotal();
                    return;
                }

                paridad.value = data.valor;
                paridadMostrada.value = data.valor_formateado ?? formatearNumero(data.valor, 2);
                paridadHelp.textContent = `Paridad UF para ${data.fecha}`;

                calcularMontoTotal();
            } catch (error) {
                paridad.value = '';
                paridadMostrada.value = '';
                paridadHelp.textContent = 'No fue posible consultar la paridad.';
                limpiarMontoTotal();
            }
        }

        async function toggleCamposMoneda() {
            if (moneda.value === 'CLP') {
                grupoFechaParidad.style.display = 'none';
                fechaParidad.value = '';
                paridad.value = 1;
                paridadMostrada.value = '1,00';
                paridadHelp.textContent = 'Paridad fija para CLP';
                calcularMontoTotal();
                return;
            }

            if (moneda.value === 'UF') {
                grupoFechaParidad.style.display = '';

                if (!fechaParidad.value) {
                    fechaParidad.value = fechaHoy;
                }

                await consultarParidadUf(fechaParidad.value);
                return;
            }

            grupoFechaParidad.style.display = 'none';
            fechaParidad.value = '';
            paridad.value = '';
            paridadMostrada.value = '';
            paridadHelp.textContent = '';
            limpiarMontoTotal();
        }

        function cargarDenominacionDesdeCatalogo() {
            const selected = catalogo.options[catalogo.selectedIndex];
            if (!selected) return;

            const descripcion = selected.getAttribute('data-descripcion') || '';

            if (!denominacion.value || denominacion.dataset.autoload === '1') {
                denominacion.value = descripcion;
                denominacion.dataset.autoload = '1';
            }
        }

        function listarDocumentos() {
            listaDocumentos.innerHTML = '';

            if (!documentos.files.length) {
                return;
            }

            const ul = document.createElement('ul');
            ul.className = 'mb-0 ps-3';

            Array.from(documentos.files).forEach(file => {
                const li = document.createElement('li');
                li.textContent = file.name;
                ul.appendChild(li);
            });

            listaDocumentos.appendChild(ul);
        }

        moneda.addEventListener('change', toggleCamposMoneda);

        fechaParidad.addEventListener('change', async function () {
            if (moneda.value === 'UF') {
                await consultarParidadUf(fechaParidad.value);
            }
        });

        totalMonedaCompra.addEventListener('input', calcularMontoTotal);

        catalogo.addEventListener('change', cargarDenominacionDesdeCatalogo);
        documentos.addEventListener('change', listarDocumentos);

        denominacion.addEventListener('input', function () {
            denominacion.dataset.autoload = '0';
        });

        toggleCamposMoneda();

        @if(old('catalogo') && !old('denominacion'))
            cargarDenominacionDesdeCatalogo();
        @endif

        @if(old('monto_total_impto_incluido'))
            montoTotalMostrado.value = formatearNumero(@json(old('monto_total_impto_incluido')), 0);
        @endif
    });
</script>
@endsection
@endsection