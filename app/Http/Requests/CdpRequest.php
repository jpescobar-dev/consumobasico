<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CdpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'num_cdp' => 'required|string|max:255',
            'fecha_cdp' => 'required|date',
            'id_proceso' => 'required|integer',
            'cfinanciero_id' => 'required|integer',
            'requerimiento' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
            'ccosto' => 'required|exists:ccostos,ccosto',
            'moneda' => 'required|in:CLP,UF',
            'total_moneda_compra' => 'nullable|string|max:255',
            'paridad' => 'nullable|numeric',
            'monto_total_impto_incluido' => 'nullable|numeric',
            'st' => 'required|in:22,29,31',
            'catalogo_id' => 'required|integer',
            'denominacion' => 'nullable|string',
            'tipo_gasto1' => 'required|in:GO,INI',
            'proyecto_id' => 'required|integer',
            'pp' => 'required|integer',
            'tipo_gasto2' => 'required|in:TRANSITORIO,PERMANENTE',
            'cargado_cgu' => 'nullable|boolean',
            'comprometido_cgu' => 'required|boolean',
            'total_compromiso' => 'required|numeric',
            'num_compromiso' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'validez' => 'nullable|string|max:255',
        ];
    }
}
