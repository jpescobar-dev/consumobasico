<?php

namespace App\Http\Controllers;

use App\Models\Detalledte;
use Illuminate\Http\Request;

class DetalledteController extends Controller
{
    public function index()
    {
        $detalles = Detalledte::with(['dte', 'cliente', 'contrato', 'ordenCompra', 'proyecto'])->get();
        return view('detalledtes.index', compact('detalles'));
    }

    public function create()
    {
        return view('detalledtes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dte_id' => 'required|exists:dtes,id|unique:detalle_consumos_basicos,dte_id',  
            'tipo' => 'required|in:Contrato,Orden Compra,Consumo Basico',
            'cliente_id' => 'required|exists:clientesmedidores,id',
            'periodoconsumo' => 'required|string|max:20',
            'lecturaanterior' => 'nullable|integer',
            'lecturaactual' => 'nullable|integer',
            'consumo' => 'nullable|integer',
            'contrato_id' => 'nullable|exists:contratos,id',
            'ordencompra_id' => 'nullable|exists:ordencompras,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
        ]);

        Detalledte::create($validated);

        return redirect()->route('detalledtes.index')->with('success', 'Detalle creado correctamente.');
    }

    public function show($id)
    {
        $detalle = Detalledte::with(['dte', 'cliente', 'contrato', 'ordenCompra', 'proyecto'])->findOrFail($id);
        return view('detalledtes.show', compact('detalle'));
    }

    public function edit($id)
    {
        $detalle = Detalledte::findOrFail($id);
        return view('detalledtes.edit', compact('detalle'));
    }

    public function update(Request $request, $id)
    {
        $detalle = Detalledte::findOrFail($id);

        $validated = $request->validate([
            'dte_id' => 'required|exists:dtes,id',
            'tipo' => 'required|in:Contrato,Orden Compra,Consumo Basico',
            'cliente_id' => 'required|exists:clientesmedidores,id',
            'periodoconsumo' => 'required|string|max:20',
            'lecturaanterior' => 'nullable|integer',
            'lecturaactual' => 'nullable|integer',
            'consumo' => 'nullable|integer',
            'contrato_id' => 'nullable|exists:contratos,id',
            'ordencompra_id' => 'nullable|exists:ordencompras,id',
            'proyecto_id' => 'nullable|exists:proyectos,id',
        ]);

        $detalle->update($validated);

        return redirect()->route('detalledtes.index')->with('success', 'Detalle actualizado correctamente.');
    }

    public function destroy($id)
    {
        $detalle = Detalledte::findOrFail($id);
        $detalle->delete();

        return redirect()->route('detalledtes.index')->with('success', 'Detalle eliminado correctamente.');
    }
}
