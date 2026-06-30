<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Inscripcion;
use App\Models\Promocion;
use Carbon\Carbon;

class ClienteController extends Controller
{
    private function verificarSesion()
    {
        if (!session('empleado_ci')) {
            abort(403, 'Debes iniciar sesión primero.');
        }
    }

    public function index(Request $request)
    {
        $this->verificarSesion();

        $buscar   = $request->input('buscar', '');
        $estado   = $request->input('estado', '');
        $ordenar  = $request->input('ordenar', 'Ci');
        $direccion = $request->input('direccion', 'asc');

        $columnasPermitidas = ['Ci', 'nombre', 'apaterno', 'amaterno', 'fecha_vencimiento', 'dias_restantes', 'estado'];
        if (!in_array($ordenar, $columnasPermitidas)) {
            $ordenar = 'Ci';
        }
        $direccion = strtolower($direccion) === 'desc' ? 'desc' : 'asc';

        $clientes = Cliente::with('inscripciones')->get();

        $clientes->each(function ($cliente) {
            $ultima = $cliente->inscripciones->sortByDesc('fecha_vencimiento')->first();
            if ($ultima) {
                $cliente->dias_restantes    = Carbon::today()->diffInDays(Carbon::parse($ultima->fecha_vencimiento), false);
                $cliente->fecha_vencimiento = $ultima->fecha_vencimiento;
                $cliente->monto             = $ultima->monto;
            } else {
                $cliente->dias_restantes    = null;
                $cliente->fecha_vencimiento = null;
                $cliente->monto             = null;
            }
        });

        if ($buscar) {
            $clientes = $clientes->filter(function ($cliente) use ($buscar) {
                $busqueda = strtolower($buscar);
                return str_contains(strtolower($cliente->nombre), $busqueda)
                    || str_contains(strtolower($cliente->apaterno), $busqueda)
                    || str_contains(strtolower($cliente->amaterno ?? ''), $busqueda)
                    || str_contains(strtolower($cliente->Ci), $busqueda);
            });
        }

        if ($estado) {
            $clientes = $clientes->filter(function ($cliente) use ($estado) {
                $dias = $cliente->dias_restantes;
                return match($estado) {
                    'activo'      => !is_null($dias) && $dias > 5,
                    'por-vencer'  => !is_null($dias) && $dias >= 0 && $dias <= 5,
                    'vencido'     => !is_null($dias) && $dias < 0,
                    default       => true,
                };
            });
        }

        $clientes = $clientes->sortBy(function ($cliente) use ($ordenar) {
            return match($ordenar) {
                'Ci'                  => $cliente->Ci,
                'nombre'              => $cliente->nombre,
                'apaterno'            => $cliente->apaterno,
                'amaterno'            => $cliente->amaterno ?? '',
                'fecha_vencimiento'   => $cliente->fecha_vencimiento ?? '0000-00-00',
                'dias_restantes'      => $cliente->dias_restantes ?? 999999,
                'estado'              => $cliente->dias_restantes,
                default               => $cliente->Ci,
            };
        }, SORT_REGULAR, $direccion === 'desc');

        $clientes = $clientes->values();

        $promociones = Promocion::all();
        return view('cliente_archivos_blade.cliente_index', compact('clientes', 'promociones', 'buscar', 'estado', 'ordenar', 'direccion'));
    }

    public function create()
    {
        $this->verificarSesion();
        $promociones = Promocion::all();
        return view('cliente_archivos_blade.cliente_create', compact('promociones'));
    }

    public function store(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'Ci'                   => 'required|unique:clientes,Ci',
            'nombre'               => 'required|string|max:100',
            'apaterno'             => 'required|string|max:100',
            'amaterno'             => 'nullable|string|max:100',
            'meses'                => 'required|integer|min:1|max:24',
            'monto'                => 'required|numeric|min:1',
            'id_promocion'         => 'nullable|exists:promocions,id_promocion',
        ]);

        $cliente = Cliente::create($request->only(['Ci', 'nombre', 'apaterno', 'amaterno']));

        $inscripcion = Inscripcion::create([
            'ci_cliente'        => $cliente->Ci,
            'id_promocion'      => $request->id_promocion ?: null,
            'fecha_inscripcion' => Carbon::today()->toDateString(),
            'fecha_vencimiento' => Carbon::today()->addMonths((int) $request->meses)->toDateString(),
            'monto'             => $request->monto,
        ]);

        // Registro automático en flujo de caja
        \App\Models\Flujo_Caja::create([
            'asunto'          => 'Inscripción de cliente',
            'cantidad_dinero' => $request->monto,
            'glosa'           => $cliente->nombre . ' ' . $cliente->apaterno . ' — CI: ' . $cliente->Ci,
            'tipo'            => 'ingreso',
            'ci_cliente'      => $cliente->Ci,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente inscrito correctamente.');
    }

    public function show($ci)
    {
        $this->verificarSesion();
        $cliente       = Cliente::with('inscripciones.promocion')->findOrFail($ci);
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
            'meses'        => 'required|integer|min:1|max:24',
            'monto'        => 'required|numeric|min:1',
            'id_promocion' => 'nullable|exists:promocions,id_promocion',
        ]);

        $cliente = Cliente::findOrFail($ci);

        $ultima = Inscripcion::where('ci_cliente', $ci)->orderBy('fecha_vencimiento', 'desc')->first();
        $inicio = $ultima && Carbon::parse($ultima->fecha_vencimiento)->isFuture()
            ? Carbon::parse($ultima->fecha_vencimiento)
            : Carbon::today();

        Inscripcion::create([
            'ci_cliente'        => $ci,
            'id_promocion'      => $request->id_promocion ?: null,
            'fecha_inscripcion' => Carbon::today()->toDateString(),
            'fecha_vencimiento' => $inicio->addMonths((int) $request->meses)->toDateString(),
            'monto'             => $request->monto,
        ]);

        // Registro automático en flujo de caja
        \App\Models\Flujo_Caja::create([
            'asunto'          => 'Inscripción de cliente',
            'cantidad_dinero' => $request->monto,
            'glosa'           => $cliente->nombre . ' ' . $cliente->apaterno . ' — CI: ' . $ci . ' (reinscripción)',
            'tipo'            => 'ingreso',
            'ci_cliente'      => $ci,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente reinscrito correctamente.');
    }
}