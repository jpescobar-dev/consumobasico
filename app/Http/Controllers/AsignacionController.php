<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Item;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with('item')->get();    
        return view('asignaciones.index', compact('asignaciones'));
    }

    public function create()
    {
        $items = Item::all();
        return view('asignaciones.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asignacion' => 'required|string|unique:asignaciones,asignacion',
            'item' => 'required|exists:items,item',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Asignacion::create($request->all());

        return redirect()->route('asignaciones.index')->with('success', 'Asignación creada correctamente.');
    }

    public function show(Asignacion $asignacion)
    {
        return view('asignaciones.show', compact('asignacion'));
    }

    public function edit(Asignacion $asignacion)
    {
        $items = Item::all();
        return view('asignaciones.edit', compact('asignacion', 'items'));
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        $request->validate([
            'item' => 'required|exists:items,item',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $asignacion->update($request->only(['item', 'nombre', 'descripcion']));

        return redirect()->route('asignaciones.index')->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();
        return redirect()->route('asignaciones.index')->with('success', 'Asignación eliminada correctamente.');
    }
}
