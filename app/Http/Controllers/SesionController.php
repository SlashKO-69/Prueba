<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sesion;
use App\Models\DetalleSesion;
use App\Models\Cliente;
use App\Models\Empleado;
use Carbon\Carbon;

class SesionController extends Controller
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
        $sesiones = Sesion::with('cliente')->orderBy('Inicio', 'desc')->get();

        $clientesActivos = Cliente::with('inscripciones')
            ->get()
            ->filter(function ($cliente) {
                $ultima = $cliente->inscripciones->sortByDesc('fecha_vencimiento')->first();
                return $ultima && Carbon::parse($ultima->fecha_vencimiento)->isFuture();
            });

        $empleados = Empleado::all();

        return view('sesion_archivos_blade.sesion_index', compact('sesiones', 'clientesActivos', 'empleados'));
    }

    public function entrada(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'ci_cliente' => 'required|exists:clientes,Ci',
        ]);

        $sesionAbierta = Sesion::where('ci_cliente', $request->ci_cliente)
            ->whereNull('Final')
            ->first();

        if ($sesionAbierta) {
            return redirect()->route('sesiones.index')->with('error', 'Este cliente ya tiene una sesión abierta.');
        }

        Sesion::create([
            'ci_cliente' => $request->ci_cliente,
            'Inicio'     => Carbon::now(),
            'Final'      => null,
        ]);

        return redirect()->route('sesiones.index')->with('success', 'Entrada registrada correctamente.');
    }

    public function salida(Request $request, $id)
    {
        $this->verificarSesion();

        $request->validate([
            'ci_empleado' => 'nullable|exists:empleados,ci_empleado',
            'Detalles'    => 'required|string|max:500',
        ]);

        $sesion = Sesion::findOrFail($id);
        $sesion->update(['Final' => Carbon::now()]);

        DetalleSesion::create([
            'id_sesion'   => $id,
            'ci_empleado' => $request->ci_empleado,
            'Detalles'    => $request->Detalles,
        ]);

        return redirect()->route('sesiones.index')->with('success', 'Salida y detalle registrados correctamente.');
    }

    public function show($id)
    {
        $this->verificarSesion();
        $sesion  = Sesion::with('cliente')->findOrFail($id);
        $detalle = DetalleSesion::with('empleado')->where('id_sesion', $id)->first();
        return view('sesion_archivos_blade.sesion_show', compact('sesion', 'detalle'));
    }

    public function agregarDetalle(Request $request, $id)
    {
        $this->verificarSesion();

        $request->validate([
            'ci_empleado' => 'nullable|exists:empleados,ci_empleado',
            'Detalles'    => 'required|string|max:500',
        ]);

        DetalleSesion::create([
            'id_sesion'   => $id,
            'ci_empleado' => $request->ci_empleado,
            'Detalles'    => $request->Detalles,
        ]);

        return redirect()->route('sesiones.show', $id)->with('success', 'Detalle agregado correctamente.');
    }

    public function destroy($id)
    {
        $this->verificarSesion();
        Sesion::destroy($id);
        return redirect()->route('sesiones.index')->with('success', 'Sesión eliminada.');
    }
}