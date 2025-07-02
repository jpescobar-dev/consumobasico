<?php
namespace App\Http\Controllers;

use App\Models\ClienteMedidor;
use App\Models\Proveedor;
use App\Models\Ccosto;
use Illuminate\Http\Request;
use App\Http\Requests\ClientesmedidorRequest;

class ClienteMedidorController extends Controller
{
    public function index()
    {
        $clientesmedidores = ClienteMedidor::all();
        return view('clientesmedidores.index', compact('clientesmedidores'));
    }

    public function create()
    {
        $proveedores = Proveedor::pluck('nombre', 'rutproveedor');
        $ccostos = Ccosto::pluck('nombre', 'ccosto');
        return view('clientesmedidores.create', compact('proveedores', 'ccostos'));
    }

    public function store(ClientesmedidorRequest $request)
    {
        ClienteMedidor::create($request->validated());
        return redirect()->route('clientesmedidores.index')->with('success', 'Cliente medidor creado correctamente.');
    }

    public function show(ClienteMedidor $clientesmedidor)
    {
        return view('clientesmedidores.show', compact('clientesmedidor'));
    }

    public function edit(ClienteMedidor $clientesmedidor)
    {
        $proveedores = Proveedor::pluck('nombre', 'rutproveedor');
        $ccostos = Ccosto::pluck('nombre', 'ccosto');
        return view('clientesmedidores.edit', compact('clientesmedidor', 'proveedores', 'ccostos'));
    }

    public function update(ClientesmedidorRequest $request, ClienteMedidor $clientesmedidor)
    {
        $clientesmedidor->update($request->validated());
        return redirect()->route('clientesmedidores.index')->with('success', 'Cliente medidor actualizado correctamente.');
    }

    public function destroy(ClienteMedidor $clientesmedidor)
    {
        $clientesmedidor->delete();
        return redirect()->route('clientesmedidores.index')->with('success', 'Cliente medidor eliminado correctamente.');
    }
}
