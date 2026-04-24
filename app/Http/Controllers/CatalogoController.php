<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Item;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        $catalogos = Catalogo::with('itemRelacion')->get();
        return view('catalogos.index', compact('catalogos'));
    }

    public function create()
    {
        $items = Item::all();
        return view('catalogos.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catalogo' => 'required|string|max:10|unique:catalogos,catalogo',
            'descripcion' => 'required|string|max:100',
            'estado' => 'required|in:Activo,Inactivo',
            'item' => 'required|exists:items,item',
        ]);

        Catalogo::create($request->all());

        return redirect()->route('catalogos.index')->with('success', 'Catálogo creado exitosamente.');
    }

    public function show(Catalogo $catalogo)
    {
        return view('catalogos.show', compact('catalogo'));
    }

    public function edit(Catalogo $catalogo)
    {
        $items = Item::all();
        return view('catalogos.edit', compact('catalogo', 'items'));
    }

    public function update(Request $request, Catalogo $catalogo)
    {
        $request->validate([
            'descripcion' => 'required|string|max:100',
            'estado' => 'required|in:Activo,Inactivo',
            'item' => 'required|exists:items,item',
        ]);

        $catalogo->update($request->only('descripcion', 'estado', 'item'));

        return redirect()->route('catalogos.index')->with('success', 'Catálogo actualizado exitosamente.');
    }

    public function destroy(Catalogo $catalogo)
    {
        $catalogo->delete();

        return redirect()->route('catalogos.index')->with('success', 'Catálogo eliminado exitosamente.');
    }
}
