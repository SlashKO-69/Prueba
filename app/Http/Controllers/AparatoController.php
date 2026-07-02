<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aparato;

class AparatoController extends Controller
{
    private function soloAdmin()
    {
        if (session('empleado_rol') !== 'admin') {
            abort(403, 'Solo el administrador puede gestionar aparatos.');
        }
    }

    public function index()
    {
        $this->soloAdmin();
        $aparatos = Aparato::all();
        return view('aparato_archivos_blade.aparato_index', compact('aparatos'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'nombre_aparato' => 'required|string|max:100',
            'tipo_aparato'   => 'required|string|max:100',
            'estado_aparato' => 'required|in:funcionando,en mantenimiento,fuera de servicio',
        ]);

        Aparato::create($request->only(['nombre_aparato', 'tipo_aparato', 'estado_aparato']));

        return redirect()->route('aparatos.index')->with('success', 'Aparato registrado correctamente.');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'estado_aparato' => 'required|in:funcionando,en mantenimiento,fuera de servicio',
        ]);

        Aparato::findOrFail($id)->update(['estado_aparato' => $request->estado_aparato]);

        return redirect()->route('aparatos.index')->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        Aparato::destroy($id);
        return redirect()->route('aparatos.index')->with('success', 'Aparato eliminado correctamente.');
    }
}