<?php

namespace App\Http\Controllers;
use App\Models\personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    // Mostrar todos los registros de personal
    public function index()
    {
        $personals = Personal::all();
        return view('personals.index', compact('personals'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('personals.create');
    }

    // Guardar nuevo personal con fecha de contratación
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:personals,ci',
            'cargo' => 'required|string|max:100',
            'fecha_contratacion' => 'required|date',
            'fecha_pago' => 'required|date',
        ]);

        Personal::create($request->all());

        return redirect()->route('personals.index')
                         ->with('success', 'Personal registrado correctamente con fecha de contratación');
    }

    // Mostrar un registro específico
    public function show($id)
    {
        $personal = Personal::findOrFail($id);
        return view('personals.show', compact('personal'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $personal = Personal::findOrFail($id);
        return view('personals.edit', compact('personal'));
    }

    // Actualizar datos del personal
    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:personals,ci,' . $personal->id,
            'cargo' => 'required|string|max:100',
            'fecha_contratacion' => 'required|date',
            'fecha_pago' => 'required|date',
        ]);

        $personal->update($request->all());

        return redirect()->route('personals.index')
                         ->with('success', 'Datos del personal actualizados correctamente');
    }

    // Eliminar registro de personal
    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);
        $personal->delete();

        return redirect()->route('personals.index')
                         ->with('success', 'Personal eliminado correctamente');
    }
}
