<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promocion;

class PromocionController extends Controller
{
    private function verificarSesion()
    {
        if (!session('empleado_ci')) {
            abort(403, 'Debes iniciar sesión primero.');
        }
    }

    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede gestionar promociones.');
        }
    }

    public function index()
    {
        $this->verificarSesion();
        $promociones = Promocion::all();
        return view('promocion_archivos_blade.promocion_index', compact('promociones'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'porcentaje_descuento' => 'required|numeric|min:1|max:100',
            'requisito'            => 'required|string|max:255',
        ]);

        Promocion::create($request->only(['porcentaje_descuento', 'requisito']));

        return redirect()->route('promociones.index')->with('success', 'Promoción creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'porcentaje_descuento' => 'required|numeric|min:1|max:100',
            'requisito'            => 'required|string|max:255',
        ]);

        $promocion = Promocion::findOrFail($id);
        $promocion->update($request->only(['porcentaje_descuento', 'requisito']));

        return redirect()->route('promociones.index')->with('success', 'Promoción actualizada correctamente.');
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        Promocion::destroy($id);
        return redirect()->route('promociones.index')->with('success', 'Promoción eliminada.');
    }
}