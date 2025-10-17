<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        // Se asume que la relación 'rol' está definida en el modelo User
        $usuarios = User::with('rol')
            ->orderBy('id')
            ->get();

        return view('usuarios.table', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // Se asume que el modelo 'Rol' usa la tabla 'rol'
        $roles = Rol::orderBy('nombre_rol')->get(['id_rol', 'nombre_rol']);
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'tipo_documento' => 'nullable|string|max:20',
            'numero_identificacion' => 'nullable|string|max:50',
            'genero' => 'nullable|string|max:20',
            // VALIDACIÓN CORRECTA (ya estaba bien): apunta a la tabla 'rol'
            'id_rol' => 'nullable|integer|exists:rol,id_rol',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'tipo_documento' => $request->tipo_documento,
            'numero_identificacion' => $request->numero_identificacion,
            'genero' => $request->genero,
            'id_rol' => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado correctamente.');
    }

    /**
     * Muestra un usuario específico.
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Muestra el formulario de edición de un usuario.
     */
    public function edit(User $usuario)
    {
        $roles = Rol::orderBy('nombre_rol')->get(['id_rol', 'nombre_rol']);
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Actualiza un usuario en la base de datos.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'tipo_documento' => 'nullable|string|max:20',
            'numero_identificacion' => 'nullable|string|max:50',
            'genero' => 'nullable|string|max:20',
            // ¡CORRECCIÓN APLICADA AQUÍ! Se cambió 'roles' a 'rol' para coincidir con tu DB.
            'id_rol' => 'nullable|integer|exists:rol,id_rol', 
        ]);

        $usuario->update($request->only([
            'nombre',
            'apellidos',
            'email',
            'telefono',
            'tipo_documento',
            'numero_identificacion',
            'genero',
            'id_rol',
        ]));

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $usuario)
    {
        try {
            $usuario->delete();
            return back()->with('ok', 'Usuario eliminado correctamente.');
        } catch (\Throwable $e) {
            // Este error generalmente ocurre por restricciones de clave foránea.
            return back()->withErrors('No se puede eliminar: tiene registros relacionados.');
        }
    }
}