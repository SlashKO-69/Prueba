<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reunion;
use App\Models\Empleado;

class ReunionController extends Controller
{
    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede gestionar reuniones.');
        }
    }

    private function verificarSesion()
    {
        if (!session('empleado_ci')) {
            abort(403, 'Debes iniciar sesión primero.');
        }
    }

    public function index()
    {
        $this->soloAdmin();
        $reuniones = Reunion::orderBy('fecha_reunion', 'desc')->get();
        $empleados = Empleado::where('rol', 'empleado')->get();
        return view('reunion_archivos_blade.reunion_index', compact('reuniones', 'empleados'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'fecha_reunion' => 'required|date',
            'hora_reunion'  => 'required',
            'motivo'        => 'required|string|max:255',
        ]);

        // Inicializar asistencia solo con empleados (no admin)
        $empleados   = Empleado::where('rol', 'empleado')->get();
        $asistencia  = [];
        foreach ($empleados as $emp) {
            $asistencia[$emp->ci_empleado] = 'pendiente';
        }

        Reunion::create([
            'fecha_reunion' => $request->fecha_reunion,
            'hora_reunion'  => $request->hora_reunion,
            'motivo'        => $request->motivo,
            'asistencia'    => $asistencia,
        ]);

        return redirect()->route('reuniones.index')->with('success', 'Reunión creada correctamente.');
    }

    public function show($id)
    {
        $this->soloAdmin();
        $reunion   = Reunion::findOrFail($id);
        $empleados = Empleado::where('rol', 'empleado')->get();
        return view('reunion_archivos_blade.reunion_show', compact('reunion', 'empleados'));
    }

    public function actualizarAsistencia(Request $request, $id)
    {
        $this->soloAdmin();

        $reunion    = Reunion::findOrFail($id);
        $asistencia = $reunion->asistencia ?? [];

        $asistencia[$request->ci_empleado] = $request->estado;
        $reunion->update(['asistencia' => $asistencia]);

        return redirect()->route('reuniones.show', $id)->with('success', 'Asistencia actualizada.');
    }

    // Bandeja de entrada para empleados — ven sus reuniones pendientes
    public function bandeja()
    {
        $this->verificarSesion();
        $ci        = session('empleado_ci');
        $reuniones = Reunion::orderBy('fecha_reunion', 'desc')->get()
            ->filter(function ($r) use ($ci) {
                $asistencia = $r->asistencia ?? [];
                return isset($asistencia[$ci]);
            });

        return view('reunion_archivos_blade.reunion_bandeja', compact('reuniones'));
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        Reunion::destroy($id);
        return redirect()->route('reuniones.index')->with('success', 'Reunión eliminada.');
    }
}