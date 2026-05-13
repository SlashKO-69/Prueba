<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Inscripcion;
use Carbon\Carbon;

class ClienteController extends Controller
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
        $clientes = Cliente::with('inscripciones')->get();

        $clientes->each(function ($cliente) {
            $ultima = $cliente->inscripciones->sortByDesc('fecha_vencimiento')->first();
            if ($ultima) {
                $cliente->dias_restantes  = Carbon::today()->diffInDays(Carbon::parse($ultima->fecha_vencimiento), false);
                $cliente->fecha_vencimiento = $ultima->fecha_vencimiento;
                $cliente->monto           = $ultima->monto;
            } else {
                $cliente->dias_restantes  = null;
                $cliente->fecha_vencimiento = null;
                $cliente->monto           = null;
            }
        });

        return view('cliente_archivos_blade.cliente_index', compact('clientes'));
    }

    public function create()
    {
        $this->verificarSesion();
        return view('cliente_archivos_blade.cliente_create');
    }

    public function store(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'Ci'       => 'required|unique:clientes,Ci',
            'nombre'   => 'required|string|max:100',
            'apaterno' => 'required|string|max:100',
            'amaterno' => 'nullable|string|max:100',
            'meses'    => 'required|integer|min:1|max:24',
            'monto'    => 'required|numeric|min:1',
        ]);

        $cliente = Cliente::create($request->only(['Ci', 'nombre', 'apaterno', 'amaterno']));

        Inscripcion::create([
            'ci_cliente'        => $cliente->Ci,
            'fecha_inscripcion' => Carbon::today()->toDateString(),
            'fecha_vencimiento' => Carbon::today()->addMonths((int) $request->meses)->toDateString(),
            'monto'             => $request->monto,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente inscrito correctamente.');
    }

    public function show($ci)
    {
        $this->verificarSesion();
        $cliente       = Cliente::with('inscripciones')->findOrFail($ci);
        $inscripciones = $cliente->inscripciones->sortByDesc('fecha_vencimiento');
        return view('cliente_archivos_blade.cliente_show', compact('cliente', 'inscripciones'));
    }

    public function edit($ci)
    {
        $this->verificarSesion();
        $cliente = Cliente::findOrFail($ci);
        return view('cliente_archivos_blade.cliente_edit', compact('cliente'));
    }

    public function update(Request $request, $ci)
    {
        $this->verificarSesion();
        $cliente = Cliente::findOrFail($ci);

        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apaterno' => 'required|string|max:100',
            'amaterno' => 'nullable|string|max:100',
        ]);

        $cliente->update($request->only(['nombre', 'apaterno', 'amaterno']));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($ci)
    {
        $this->verificarSesion();
        Cliente::destroy($ci);
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    public function reinscribir(Request $request, $ci)
    {
        $this->verificarSesion();

        $request->validate([
            'meses' => 'required|integer|min:1|max:24',
            'monto' => 'required|numeric|min:1',
        ]);

        $cliente = Cliente::findOrFail($ci);

        $ultima = Inscripcion::where('ci_cliente', $ci)->orderBy('fecha_vencimiento', 'desc')->first();
        $inicio = $ultima && Carbon::parse($ultima->fecha_vencimiento)->isFuture()
            ? Carbon::parse($ultima->fecha_vencimiento)
            : Carbon::today();

        Inscripcion::create([
            'ci_cliente'        => $ci,
            'fecha_inscripcion' => Carbon::today()->toDateString(),
            'fecha_vencimiento' => $inicio->addMonths((int) $request->meses)->toDateString(),
            'monto'             => $request->monto,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente reinscrito correctamente.');
    }
}