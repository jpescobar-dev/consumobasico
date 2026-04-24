<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCdpRequest;
use App\Http\Requests\UpdateCdpRequest;
use App\Models\Catalogo;
use App\Models\Ccosto;
use App\Models\Cdp;
use App\Models\CdpDocumento;
use App\Models\Cfinanciero;
use App\Models\Estado;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ParidadUf;
use Carbon\Carbon;


class CdpController extends Controller
{
    public function index()
    {
        $cdps = Cdp::with([
            'cfinanciero',
            'centroCosto',
            'catalogoRelacion',
            'proyecto',
            'estado',
            'documentos',
        ])
        ->orderByDesc('id')
        ->get();

        return view('cdps.index', compact('cdps'));
    }

    public function create()
    {
        $cfinancieros = Cfinanciero::orderBy('cfinanciero')->get();
        $ccostos = Ccosto::orderBy('ccosto')->get();
        $catalogos = Catalogo::orderBy('catalogo')->get();
        $proyectos = Proyecto::orderBy('proyecto')->get();

        $fechaHoy = Carbon::today()->format('Y-m-d');
        $paridadHoy = ParidadUf::whereDate('fecha', $fechaHoy)->value('valor');

        return view('cdps.create', compact(
            'cfinancieros',
            'ccostos',
            'catalogos',
            'proyectos',
            'fechaHoy',
            'paridadHoy'
        ));
    }

    public function store(StoreCdpRequest $request)
    {


    $data = $request->validated();

        $data['estado_id'] = 1;
        $data['id_proceso'] = $data['proceso_sgf'];
        unset($data['proceso_sgf']);
        
        if ($data['moneda'] === 'CLP') {
            $data['fecha_paridad'] = null;
            $data['paridad'] = 1;
        }

        if ($data['moneda'] === 'UF') {
            $fechaParidad = \Carbon\Carbon::parse($data['fecha_paridad'])->format('Y-m-d');

            $valorParidad = ParidadUf::whereDate('fecha', $fechaParidad)->value('valor');

            if (!$valorParidad) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'No existe paridad UF registrada para la fecha seleccionada.');
            }

            $data['fecha_paridad'] = $fechaParidad;
            $data['paridad'] = $valorParidad;
        }

        $montoCalculado = ((float) ($data['total_moneda_compra'] ?? 0)) * ((float) ($data['paridad'] ?? 0));
        $data['monto_total_impto_incluido'] = round($montoCalculado);
    

        $this->normalizarCampos($data);

        DB::beginTransaction();

        try {
            $cdp = Cdp::create($data);

            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $file) {
                    $path = $file->store('cdps/documentos', 'public');

                    $cdp->documentos()->create([
                        'nombre_original' => $file->getClientOriginalName(),
                        'archivo' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'peso' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('cdps.index')
                ->with('success', 'CDP creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el CDP: ' . $e->getMessage());
        }
    }

    public function show(Cdp $cdp)
    {
        $cdp->load([
            'cfinanciero',
            'centroCosto',
            'catalogoRelacion',
            'proyecto',
            'estado',
            'documentos',
        ]);

        return view('cdps.show', compact('cdp'));
    }

    public function edit(Cdp $cdp)
    {
        $cdp->load('documentos');

        $cfinancieros = Cfinanciero::orderBy('cfinanciero')->get();
        $ccostos = Ccosto::orderBy('ccosto')->get();
        $catalogos = Catalogo::orderBy('catalogo')->get();
        $proyectos = Proyecto::orderBy('proyecto')->get();
        $estados = Estado::orderBy('id')->get();

        return view('cdps.edit', compact(
            'cdp',
            'cfinancieros',
            'ccostos',
            'catalogos',
            'proyectos',
            'estados'
        ));
    }

    public function update(UpdateCdpRequest $request, Cdp $cdp)
    {
        $data = $request->validated();

        $this->normalizarCampos($data);

        DB::beginTransaction();

        try {
            $cdp->update($data);

            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $file) {
                    $path = $file->store('cdps/documentos', 'public');

                    $cdp->documentos()->create([
                        'nombre_original' => $file->getClientOriginalName(),
                        'archivo' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'peso' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('cdps.index')
                ->with('success', 'CDP actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el CDP: ' . $e->getMessage());
        }
    }

    public function destroy(Cdp $cdp)
    {
        DB::beginTransaction();

        try {
            $cdp->load('documentos');

            foreach ($cdp->documentos as $documento) {
                if ($documento->archivo && Storage::disk('public')->exists($documento->archivo)) {
                    Storage::disk('public')->delete($documento->archivo);
                }

                $documento->delete();
            }

            $cdp->delete();

            DB::commit();

            return redirect()
                ->route('cdps.index')
                ->with('success', 'CDP eliminado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al eliminar el CDP: ' . $e->getMessage());
        }
    }

    public function destroyDocumento(Cdp $cdp, CdpDocumento $documento)
    {
        if ((int) $documento->cdp_id !== (int) $cdp->id) {
            abort(404);
        }

        try {
            if ($documento->archivo && Storage::disk('public')->exists($documento->archivo)) {
                Storage::disk('public')->delete($documento->archivo);
            }

            $documento->delete();

            return redirect()
                ->back()
                ->with('success', 'Documento eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al eliminar el documento: ' . $e->getMessage());
        }
    }

    public function downloadDocumento(Cdp $cdp, CdpDocumento $documento)
    {
        if ((int) $documento->cdp_id !== (int) $cdp->id) {
            abort(404);
        }

        if (!$documento->archivo || !Storage::disk('public')->exists($documento->archivo)) {
            return redirect()
                ->back()
                ->with('error', 'El archivo no existe en el almacenamiento.');
        }

        return Storage::disk('public')->download(
            $documento->archivo,
            $documento->nombre_original
        );
    }

    private function normalizarCampos(array &$data): void
    {
        $data['cargado_cgu'] = (bool) ($data['cargado_cgu'] ?? false);
        $data['comprometido_cgu'] = (bool) ($data['comprometido_cgu'] ?? false);
        $data['pp'] = $data['pp'] ?? 100;
    }

    public function obtenerParidadUf(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'fecha' => ['required', 'date'],
        ]);

        $fecha = \Carbon\Carbon::parse($request->fecha)->format('Y-m-d');

        $paridad = ParidadUf::whereDate('fecha', $fecha)->first();

        if (!$paridad) {
            return response()->json([
                'ok' => false,
                'message' => 'No existe paridad UF para la fecha seleccionada.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'fecha' => $fecha,
            'valor' => $paridad->valor,
            'valor_formateado' => number_format((float) $paridad->valor, 2, ',', '.'),
        ]);
    }
}