<?php
namespace App\Http\Controllers;
use App\Models\operador;
use App\Models\Cliente;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class OperadorController extends Controller
{
    // Vista de login
    public function login()
    {
        return view('login_operador');
    }

    // Procesa el acceso según el rol
    public function authenticate(Request $request)
    {
        $rol = $request->input('rol');

    switch ($rol) {
        case 'administradora':
            $request->validate([
                'contraseña' => 'required'
            ]);

            $operador = Operador::where('rol', 'administradora')->first();

            if ($operador && Hash::check($request->contraseña, $operador->contraseña)) {
                return redirect()->back()->with([
                    'success' => 'Acceso concedido como administradora',
                    'rol' => $operador->rol,
                    'nombre' => $operador->nombre // aquí se pasa Annie
                ]);
            }

            return redirect()->back()->with('error', 'Contraseña incorrecta');

        case 'recepcionista':
            $operador = Operador::where('rol', 'recepcionista')->first();

            return redirect()->back()->with([
                'success' => 'Acceso concedido como recepcionista',
                'rol' => $operador->rol,
                'nombre' => $operador ? $operador->nombre : 'Recepcionista'
            ]);

        default:
            return redirect()->back()->with('error', 'Rol inválido');
    }
    }

    // Dashboard para administradora (clientes + personal)
    public function dashboardAdmin()
    {
        $clientes = Cliente::all();
        $personals = Personal::all();
        return view('panel_admin', compact('clientes', 'personals'));
    }

    // Dashboard para recepcionista (solo clientes)
    public function dashboardRecepcionista()
    {
        $clientes = Cliente::all();
        return view('panel_recepcionista', compact('clientes'));
    }
    
}
