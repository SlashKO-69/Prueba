<?php

namespace App\Http\Controllers;
use App\Models\cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos_clientes = cliente::all();
        return view('clientes.index', compact('datos_clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string|max:255',
        'apaterno' => 'required|string|max:255',
        'ci' => 'required|string|unique:clientes,ci',
        'fecha_inscripcion' => 'required|date',
        'fecha_vencimiento' => 'required|date|after:fecha_inscripcion',
    ]);

    cliente::create($request->all());
    return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente existente.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'nullable|string|max:255',
            'ci' => 'required|string|unique:clientes,ci,' . $cliente->id,
            'fecha_inscripcion' => 'required|date',
            'fecha_vencimiento' => 'required|date|after:fecha_inscripcion',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado correctamente');
    }

    /**
     * Eliminar un cliente.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado correctamente');
    }
}
