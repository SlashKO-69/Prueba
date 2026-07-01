<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Cliente;
use App\Models\Promocion;
use Carbon\Carbon;

class InscripcionController extends Controller
{
    private function verificarSesion()
    {
        if (!session('empleado_ci')) {
            abort(403, 'Debes iniciar sesiÃ³n primero.');
        }
    }

    public function index(Request $request)
    {
        $this->verificarSesion();

        $buscar    = $request->input('buscar', '');
        $estado    = $request->input('estado', '');
        $ordenar   = $request->input('ordenar', 'id');
        $direccion = $request->input('direccion', 'asc');

        $columnasPermitidas = ['id', 'cliente', 'ci_cliente', 'fecha_inscripcion', 'fecha_vencimiento', 'dias_restantes', 'monto', 'estado'];
        if (!in_array($ordenar, $columnasPermitidas)) {
            $ordenar = 'id';
        }
        $direccion = strtolower($direccion) === 'desc' ? 'desc' : 'asc';

        $inscripciones = Inscripcion::with('cliente')->get();

        $inscripciones->each(function ($ins) {
            $ins->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($ins->fecha_vencimiento), false);
        });

        if ($buscar) {
            $inscripciones = $inscripciones->filter(function ($ins) use ($buscar) {
                $busqueda = strtolower($buscar);
                return str_contains(strtolower($ins->cliente->nombre ?? ''), $busqueda)
                    || str_contains(strtolower($ins->cliente->apaterno ?? ''), $busqueda)
                    || str_contains(strtolower($ins->cliente->amaterno ?? ''), $busqueda)
                    || str_contains(strtolower($ins->ci_cliente), $busqueda);
            });
        }

        if ($estado) {
            $inscripciones = $inscripciones->filter(function ($ins) use ($estado) {
                $dias = $ins->dias_restantes;
                return match($estado) {
                    'activo'      => !is_null($dias) && $dias > 5,
                    'por-vencer'  => !is_null($dias) && $dias >= 0 && $dias <= 5,
                    'vencido'     => !is_null($dias) && $dias < 0,
                    default       => true,
                };
            });
        }

        $inscripciones = $inscripciones->sortBy(function ($ins) use ($ordenar) {
            $valor = match($ordenar) {
                'id'                  => $ins->id,
                'cliente'             => strtolower(trim(($ins->cliente->nombre ?? '') . ' ' . ($ins->cliente->apaterno ?? ''))),
                'ci_cliente'          => $ins->ci_cliente,
                'fecha_inscripcion'   => $ins->fecha_inscripcion ?? '0000-00-00',
                'fecha_vencimiento'   => $ins->fecha_vencimiento ?? '0000-00-00',
                'dias_restantes'      => $ins->dias_restantes ?? 999999,
                'monto'               => $ins->monto ?? 0,
                'estado'              => $ins->dias_restantes,
                default               => $ins->id,
            };
            return is_string($valor) ? strtolower($valor) : $valor;
        }, SORT_REGULAR, $direccion === 'desc');

        $inscripciones = $inscripciones->values();

        return view('inscripcion_archivos_blade.inscripcion_index', compact('inscripciones', 'buscar', 'estado', 'ordenar', 'direccion'));
    }

    public function create()
    {
        $this->verificarSesion();
        return view('inscripcion_archivos_blade.inscripcion_create');
    }

    public function store(Request $request)
    {
        $this->verificarSesion();

        $request->validate([
            'meses' => 'required|integer|min:1|max:24',
            'monto' => 'required|numeric|min:1',
        ]);

        $fechaInscripcion = Carbon::today();
        $fechaVencimiento = Carbon::today()->addMonths((int) $request->meses);

        Inscripcion::create([
            'fecha_inscripcion' => $fechaInscripcion->toDateString(),
            'fecha_vencimiento' => $fechaVencimiento->toDateString(),
            'monto'             => $request->monto,
        ]);

        return redirect()->route('inscripciones.index')->with('success', 'InscripciÃ³n registrada correctamente.');
    }

    public function show($id)
    {
        $this->verificarSesion();
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($inscripcion->fecha_vencimiento), false);
        return view('inscripcion_archivos_blade.inscripcion_show', compact('inscripcion'));
    }

    public function destroy($id)
    {
        $this->verificarSesion();
        Inscripcion::destroy($id);
        return redirect()->route('inscripciones.index')->with('success', 'InscripciÃ³n eliminada.');
    }
    public function exportarExcel(Request $request)
    {
        $this->verificarSesion();

        $buscar    = $request->input('buscar', '');
        $estado    = $request->input('estado', '');
        $ordenar   = $request->input('ordenar', 'id');
        $direccion = $request->input('direccion', 'asc');

        $columnasPermitidas = ['id', 'cliente', 'ci_cliente', 'fecha_inscripcion', 'fecha_vencimiento', 'dias_restantes', 'monto', 'estado'];
        if (!in_array($ordenar, $columnasPermitidas)) {
            $ordenar = 'id';
        }
        $direccion = strtolower($direccion) === 'desc' ? 'desc' : 'asc';

        $inscripciones = Inscripcion::with('cliente')->get();

        $inscripciones->each(function ($ins) {
            $ins->dias_restantes = Carbon::today()->diffInDays(Carbon::parse($ins->fecha_vencimiento), false);
        });

        if ($buscar) {
            $inscripciones = $inscripciones->filter(function ($ins) use ($buscar) {
                $busqueda = strtolower($buscar);
                return str_contains(strtolower($ins->cliente->nombre ?? ''), $busqueda)
                    || str_contains(strtolower($ins->cliente->apaterno ?? ''), $busqueda)
                    || str_contains(strtolower($ins->cliente->amaterno ?? ''), $busqueda)
                    || str_contains(strtolower($ins->ci_cliente), $busqueda);
            });
        }

        if ($estado) {
            $inscripciones = $inscripciones->filter(function ($ins) use ($estado) {
                $dias = $ins->dias_restantes;
                return match($estado) {
                    'activo'      => !is_null($dias) && $dias > 5,
                    'por-vencer'  => !is_null($dias) && $dias >= 0 && $dias <= 5,
                    'vencido'     => !is_null($dias) && $dias < 0,
                    default       => true,
                };
            });
        }

        $inscripciones = $inscripciones->sortBy(function ($ins) use ($ordenar) {
            $valor = match($ordenar) {
                'id'                  => $ins->id,
                'cliente'             => strtolower(trim(($ins->cliente->nombre ?? '') . ' ' . ($ins->cliente->apaterno ?? ''))),
                'ci_cliente'          => $ins->ci_cliente,
                'fecha_inscripcion'   => $ins->fecha_inscripcion ?? '0000-00-00',
                'fecha_vencimiento'   => $ins->fecha_vencimiento ?? '0000-00-00',
                'dias_restantes'      => $ins->dias_restantes ?? 999999,
                'monto'               => $ins->monto ?? 0,
                'estado'              => $ins->dias_restantes,
                default               => $ins->id,
            };
            return is_string($valor) ? strtolower($valor) : $valor;
        }, SORT_REGULAR, $direccion === 'desc');

        $inscripciones = $inscripciones->values();

        $filename = 'inscripciones_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($inscripciones) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['#', 'Cliente', 'CI', 'Inscripción', 'Vencimiento', 'Días restantes', 'Monto (Bs.)', 'Estado'], ';');

            foreach ($inscripciones as $ins) {
                $dias = $ins->dias_restantes;
                $estado = is_null($dias)
                    ? 'Sin inscripción'
                    : ($dias < 0 ? 'Vencido' : ($dias <= 5 ? 'Por vencer' : 'Activo'));

                fputcsv($file, [
                    $ins->id,
                    trim(($ins->cliente->nombre ?? '') . ' ' . ($ins->cliente->apaterno ?? '')),
                    $ins->ci_cliente,
                    $ins->fecha_inscripcion ? Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') : '',
                    $ins->fecha_vencimiento ? Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') : '',
                    $dias,
                    $ins->monto,
                    $estado,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
