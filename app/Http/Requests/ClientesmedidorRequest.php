<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientesmedidorRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->route('clientesmedidor')?->numerocliente;

        return [
            'numerocliente' => 'required|string|max:20' . ($id ? '' : '|unique:clientesmedidores,numerocliente'),
            'medidor'       => 'nullable|string|max:20',
            'rutproveedor'  => 'required|exists:proveedores,rutproveedor',
            'ccosto'        => 'required|exists:ccostos,ccosto',
            'tipo'          => 'required|string|max:50',
            'tarifa'        => 'required|in:Normal,Calefaccion',
            'vigente'       => 'nullable|boolean',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
