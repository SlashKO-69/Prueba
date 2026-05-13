<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Solo admin puede ver la lista de empleados
        if (session('empleado_rol') !== 'admin') {
            return redirect()->route('clientes.index');
        }

        $empleados = Empleado::all();
        return view('empleado_archivos_blade.empleado_index', compact('empleados'));
    }

    public function create()
    {
        return view('empleado_archivos_blade.empleado_create');
    }

    public function store(Request $request)
    {
        $rules = [
            'ci_empleado' => 'required|unique:empleados,ci_empleado',
            'nombre'      => 'required|string|max:100',
            'apaterno'    => 'required|string|max:100',
            'amaterno'    => 'nullable|string|max:100',
            'celular'     => 'nullable|string|max:20',
            'rol'         => 'required|in:admin,empleado',
            'password'    => 'required|string|min:6|confirmed',
        ];

        $request->validate($rules);
        $data = $request->except('password_confirmation');
        Empleado::create($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function show($ci_empleado)
    {
        $empleado = Empleado::findOrFail($ci_empleado);
        return view('empleado_archivos_blade.empleado_show', compact('empleado'));
    }

    public function edit($ci_empleado)
    {
        $empleado = Empleado::findOrFail($ci_empleado);
        return view('empleado_archivos_blade.empleado_edit', compact('empleado'));
    }

    public function update(Request $request, $ci_empleado)
    {
        $empleado = Empleado::findOrFail($ci_empleado);

        $rules = [
            'nombre'   => 'required|string|max:100',
            'apaterno' => 'required|string|max:100',
            'amaterno' => 'nullable|string|max:100',
            'celular'  => 'nullable|string|max:20',
            'rol'      => 'required|in:admin,empleado',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        $request->validate($rules);
        $data = $request->except(['password', 'password_confirmation', '_method', '_token']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $empleado->update($data);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($ci_empleado)
    {
        Empleado::destroy($ci_empleado);
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }

    public function loginForm()
    {
        return view('empleado_archivos_blade.empleado_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ci_empleado' => 'required',
            'password'    => 'required',
        ]);

        $empleado = Empleado::where('ci_empleado', $request->ci_empleado)->first();

        if (!$empleado) {
            return back()->withErrors(['ci_empleado' => 'No se encontró ningún empleado con ese CI.']);
        }

        if (!Hash::check($request->password, $empleado->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        session([
            'empleado_ci'     => $empleado->ci_empleado,
            'empleado_rol'    => $empleado->rol,
            'empleado_nombre' => $empleado->nombre,
        ]);

        // Admin → lista de empleados | Empleado → lista de clientes
        if ($empleado->rol === 'admin') {
            return redirect()->route('empleados.index')->with('success', '¡Bienvenido ' . $empleado->nombre . '!');
        } else {
            return redirect()->route('clientes.index')->with('success', '¡Bienvenido ' . $empleado->nombre . '!');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('empleados.loginForm');
    }
}