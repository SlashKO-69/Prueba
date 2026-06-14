<?php

namespace App\Http\Controllers;

use App\Models\Flujo_Caja;

class Flujo_CajaController extends Controller
{
    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede ver el flujo de caja.');
        }
    }

    public function index()
    {
        $this->soloAdmin();
        $movimientos = Flujo_Caja::with(['cliente', 'empleado'])->orderBy('created_at', 'desc')->get();

        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('cantidad_dinero');
        $totalEgresos  = $movimientos->where('tipo', 'egreso')->sum('cantidad_dinero');
        $saldo         = $totalIngresos - $totalEgresos;

        return view('flujo_caja_archivos_blade.flujo_caja_index', compact('movimientos', 'totalIngresos', 'totalEgresos', 'saldo'));
    }
}