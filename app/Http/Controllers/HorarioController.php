<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Empleado;

class HorarioController extends Controller
{
    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede gestionar horarios.');
        }
    }

    public function index()
    {
        $this->soloAdmin();
        $horarios  = Horario::with('empleado')->orderBy('fecha', 'desc')->get();
        $empleados = Empleado::where('rol', 'empleado')->get();
        return view('horario_archivos_blade.horario_index', compact('horarios', 'empleados'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'ci_empleado'  => 'required|exists:empleados,ci_empleado',
            'fecha'        => 'required|date',
            'hora_entrada' => 'required',
            'hora_salida'  => 'required',
            'turno'        => 'required|in:mañana,tarde,noche',
        ]);

        Horario::create($request->only(['ci_empleado', 'fecha', 'hora_entrada', 'hora_salida', 'turno']));

        return redirect()->route('horarios.index')->with('success', 'Horario registrado correctamente.');
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        Horario::destroy($id);
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado.');
    }
}