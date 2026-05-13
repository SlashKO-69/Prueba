<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sueldo_o_Pago;
use App\Models\Empleado;
use Carbon\Carbon;

class SueldoController extends Controller
{
    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede gestionar sueldos.');
        }
    }

    public function index()
    {
        $this->soloAdmin();

        $empleados = Empleado::all();
        $sueldos   = Sueldo_o_Pago::with('empleado')->orderBy('fecha_pago', 'desc')->get();

        // Calcular días restantes al próximo pago (ciclo mensual desde fecha_pago)
        $sueldos->each(function ($s) {
            $ultimo = Carbon::parse($s->fecha_pago);
            $proximo = $ultimo->copy()->addMonth();
            $s->dias_para_cobro = Carbon::today()->diffInDays($proximo, false);
        });

        return view('sueldo_archivos_blade.sueldo_index', compact('empleados', 'sueldos'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'ci_empleado' => 'required|exists:empleados,ci_empleado',
            'monto'       => 'required|numeric|min:1',
        ]);

        // Verificar que no tenga un pago pendiente del mes actual
        $yaExiste = Sueldo_o_Pago::where('ci_empleado', $request->ci_empleado)
            ->whereMonth('fecha_pago', Carbon::now()->month)
            ->whereYear('fecha_pago', Carbon::now()->year)
            ->exists();

        if ($yaExiste) {
            return back()->with('error', 'Este empleado ya tiene un pago registrado este mes.');
        }

        Sueldo_o_Pago::create([
            'ci_empleado' => $request->ci_empleado,
            'monto'       => $request->monto,
            'fecha_pago'  => Carbon::today()->toDateString(),
            'estado'      => 'pendiente',
        ]);

        return redirect()->route('sueldos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function pagar($id)
    {
        $this->soloAdmin();

        $sueldo = Sueldo_o_Pago::findOrFail($id);
        $sueldo->update(['estado' => 'pagado']);

        return redirect()->route('sueldos.index')->with('success', 'Sueldo marcado como pagado.');
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        Sueldo_o_Pago::destroy($id);
        return redirect()->route('sueldos.index')->with('success', 'Registro eliminado.');
    }
}