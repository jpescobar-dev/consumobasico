<?php

namespace App\Http\Controllers;

use App\Models\Ccosto;
use App\Models\Cfinanciero;
use Illuminate\Http\Request;

class CcostoController extends Controller
{
    public function index()
    {
        $ccostos = Ccosto::with('centrofinanciero')->get();
        return view('ccostos.index', compact('ccostos'));
    }

    public function create()
    {
        $cfinancieros = Cfinanciero::all();
        return view('ccostos.create', compact('cfinancieros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ccosto' => 'required|unique:ccostos,ccosto|max:10',
            'nombre' => 'required|string|max:50',
            'cfinanciero' => 'required|exists:cfinancieros,cfinanciero',
        ]);

        Ccosto::create($request->all());
        return redirect()->route('ccostos.index')->with('success', 'Centro de Costo creado exitosamente.');
    }

    public function show(Ccosto $ccosto)
    {
        return view('ccostos.show', compact('ccosto'));
    }

    public function edit(Ccosto $ccosto)
    {
        $cfinancieros = Cfinanciero::all();
        return view('ccostos.edit', compact('ccosto', 'cfinancieros'));
    }

    public function update(Request $request, Ccosto $ccosto)
        {
            $request->validate([
                'nombre' => 'required|string|max:50',
                'cfinanciero' => 'required|exists:cfinancieros,cfinanciero',
            ]);

            $ccosto->update($request->only('nombre', 'cfinanciero'));

            return redirect()->route('ccostos.index')->with('success', 'Centro de Costo actualizado exitosamente.');
        }   

    public function destroy(Ccosto $ccosto)
    {
        $ccosto->delete();
        return redirect()->route('ccostos.index')->with('success', 'Centro de Costo eliminado exitosamente.');
    }
}
