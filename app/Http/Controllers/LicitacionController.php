<?php

namespace App\Http\Controllers;

use App\Models\Licitacion;
use Illuminate\Http\Request;

class LicitacionController extends Controller
{
    public function index()
    {
        $licitaciones = Licitacion::all();
        return view('licitaciones.index', compact('licitaciones'));
    }



    public function create()
    {
        return view('licitaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_licitacion' => 'required|string|max:50|unique:licitaciones,numero_licitacion',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'unidad_compra' => 'nullable|string|max:100',
            'monto_total_estimado' => 'nullable|numeric|min:0',
            'numero_ofertas_recibidas' => 'nullable|integer|min:0',
            'fecha_publicacion' => 'nullable|date',
            'fecha_adjudicacion' => 'nullable|date',
        ]);

        Licitacion::create($request->all());

        return redirect()->route('licitaciones.index')->with('success', 'Licitación creada exitosamente.');
    }

    public function show(Licitacion $licitacion)
    {
        return view('licitaciones.show', compact('licitacion'));
    }

    public function edit(Licitacion $licitacion)
    {
        return view('licitaciones.edit', compact('licitacion'));
    }

    public function update(Request $request, Licitacion $licitacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'unidad_compra' => 'nullable|string|max:100',
            'monto_total_estimado' => 'nullable|numeric|min:0',
            'numero_ofertas_recibidas' => 'nullable|integer|min:0',
            'fecha_publicacion' => 'nullable|date',
            'fecha_adjudicacion' => 'nullable|date',
        ]);

        $licitacion->update($request->only([
            'nombre',
            'descripcion',
            'estado',
            'tipo',
            'unidad_compra',
            'monto_total_estimado',
            'numero_ofertas_recibidas',
            'fecha_publicacion',
            'fecha_adjudicacion',
        ]));

        return redirect()->route('licitaciones.index')->with('success', 'Licitación actualizada exitosamente.');
    }

    public function destroy(Licitacion $licitacion)
    {
        $licitacion->delete();
        return redirect()->route('licitaciones.index')->with('success', 'Licitación eliminada exitosamente.');
    }

    
}
