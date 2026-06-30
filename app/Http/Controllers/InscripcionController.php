<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Cliente;
use App\Models\Promocion;
use Carbon\Carbon;

class InscripcionController extends Controller
{
    private function verificarSesion()
    {
        if (!session('empleado_ci')) {
            abort(403, 'Debes iniciar sesión primero.');
        }
    }

    public function index()
    {
        $this->verificarSesion();
        $inscripciones = Inscripcion::with('cliente')->orderBy('fecha_vencimiento', 'asc')->get();

        $inscripciones->each(function ($ins) {
            $ins->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($ins->fecha_vencimiento), false);
        });

        return view('inscripcion_archivos_blade.inscripcion_index', compact('inscripciones'));
    }

    public function create()
    {
        $this->verificarSesion();
        $promociones = Promocion::all();
        return view('inscripcion_archivos_blade.inscripcion_create', compact('promociones'));
    }

    public function store(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'ci_cliente'    => 'required|exists:clientes,Ci',
            'id_promocion'  => 'nullable|exists:promocions,id_promocion',
            'meses'         => 'required|integer|min:1|max:24',
            'monto'         => 'required|numeric|min:1',
        ]);

        $fechaInscripcion = Carbon::today();
        $fechaVencimiento = Carbon::today()->addMonths((int) $request->meses);

        Inscripcion::create([
            'ci_cliente'        => $request->ci_cliente,
            'id_promocion'      => $request->id_promocion ?: null,
            'fecha_inscripcion' => $fechaInscripcion->toDateString(),
            'fecha_vencimiento' => $fechaVencimiento->toDateString(),
            'monto'             => $request->monto,
        ]);

        return redirect()->route('inscripciones.index')->with('success', 'Inscripción registrada correctamente.');
    }

    public function show($id)
    {
        $this->verificarSesion();
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($inscripcion->fecha_vencimiento), false);
        return view('inscripcion_archivos_blade.inscripcion_show', compact('inscripcion'));
    }

    public function destroy($id)
    {
        $this->verificarSesion();
        Inscripcion::destroy($id);
        return redirect()->route('inscripciones.index')->with('success', 'Inscripción eliminada.');
    }
}