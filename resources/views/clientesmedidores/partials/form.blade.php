@csrf

<div class="mb-3">
    <label for="numerocliente" class="form-label">Número Cliente</label>
    <input type="text" name="numerocliente" id="numerocliente" class="form-control" value="{{ old('numerocliente', $clientesmedidor->numerocliente ?? '') }}" required {{ isset($clientesmedidor) ? 'readonly' : '' }}>
</div>

<div class="mb-3">
    <label for="medidor" class="form-label">Medidor</label>
    <input type="text" name="medidor" id="medidor" class="form-control" value="{{ old('medidor', $clientesmedidor->medidor ?? '') }}">
</div>

<div class="mb-3">
    <label for="rutproveedor" class="form-label">Proveedor</label>
    <select name="rutproveedor" id="rutproveedor" class="form-select" required>
        <option value="">Seleccione...</option>
        @foreach ($proveedores as $rut => $nombre)
            <option value="{{ $rut }}" {{ (old('rutproveedor', $clientesmedidor->rutproveedor ?? '') == $rut) ? 'selected' : '' }}>
                {{ $nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="ccosto" class="form-label">Centro de Costo</label>
    <select name="ccosto" id="ccosto" class="form-select" required>
        <option value="">Seleccione...</option>
        @foreach ($ccostos as $codigo => $nombre)
            <option value="{{ $codigo }}" {{ (old('ccosto', $clientesmedidor->ccosto ?? '') == $codigo) ? 'selected' : '' }}>
                {{ $nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <input type="text" name="tipo" id="tipo" class="form-control" value="{{ old('tipo', $clientesmedidor->tipo ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="tarifa" class="form-label">Tarifa</label>
    <select name="tarifa" id="tarifa" class="form-select" required>
        <option value="Normal" {{ old('tarifa', $clientesmedidor->tarifa ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
        <option value="Calefaccion" {{ old('tarifa', $clientesmedidor->tarifa ?? '') == 'Calefaccion' ? 'selected' : '' }}>Calefacción</option>
    </select>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="vigente" id="vigente" class="form-check-input" value="1"
        {{ old('vigente', $clientesmedidor->vigente ?? true) ? 'checked' : '' }}>
    <label for="vigente" class="form-check-label">Vigente</label>
</div>
