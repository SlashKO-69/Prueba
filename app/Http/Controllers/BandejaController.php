<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Informe;
use App\Models\Reunion;
use App\Models\Aparato;
use Carbon\Carbon;

class BandejaController extends Controller
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

        if (session('empleado_rol') === 'admin') {
            $informes  = Informe::with(['empleado', 'aparato'])->orderBy('created_at', 'desc')->get();
            $reuniones = collect();
            $aparatos  = collect();
        } else {
            $ci        = session('empleado_ci');
            $reuniones = Reunion::orderBy('fecha_reunion', 'desc')->get()
                ->filter(function ($r) use ($ci) {
                    return isset(($r->asistencia ?? [])[$ci]);
                });
            $informes = collect();
            $aparatos = Aparato::all();
        }

        return view('bandeja', compact('informes', 'reuniones', 'aparatos'));
    }

    public function reportar(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'id_aparato'     => 'required|exists:aparatos,id_aparato',
            'detalle'        => 'required|string|max:500',
        ]);

        $aparato = Aparato::findOrFail($request->id_aparato);

        Informe::create([
            'id_aparato'     => $request->id_aparato,
            'nombre_maquina' => $aparato->nombre_aparato,
            'ci_empleado'    => session('empleado_ci'),
            'detalle'        => $request->detalle,
            'fecha_informe'  => Carbon::today()->toDateString(),
            'leido'          => 0,
        ]);

        return redirect()->route('bandeja.index')->with('success', 'Reporte enviado al administrador.');
    }

    public function marcarLeido($id)
    {
        if (session('empleado_rol') !== 'admin') abort(403);
        Informe::findOrFail($id)->update(['leido' => 1]);
        return redirect()->route('bandeja.index')->with('success', 'Reporte marcado como leído.');
    }
}