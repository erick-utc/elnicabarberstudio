<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\RegistraBitacora;
use App\Actions\Fortify\CreateNewUser;

class UsuariosController extends Controller implements HasMiddleware
{
    use RegistraBitacora;

    public static function middleware():array {
        return [
            new Middleware('permission:ver usuarios', only: ['index']),
            new Middleware('permission:editar usuarios', only: ['edit']),
            new Middleware('permission:crear usuarios', only: ['create']),
            new Middleware('permission:borrar usuarios', only: ['delete'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        $search = '';
        $this->registrarMovimiento('ver', 'ver usuarios');
        return view('usuarios.index',compact('usuarios', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->registrarMovimiento('crear nuevo usuario', 'crear usuarios');
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new CreateNewUser;
        $user->create($request->all());

        return redirect()->route('usuario.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id) {
        $usuario = User::findOrFail($id);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(string $id) {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editroles(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::orderBy('name', 'asc')->get();
        $hasRoles = $usuario->roles->pluck('name');

        $this->registrarMovimiento('editar roles de usuario: '.$usuario->correo, 'editar usuarios', $usuario);

        return view('usuarios.editroles', compact('usuario', 'roles', 'hasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateroles(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        $usuario->syncRoles($request->role);

        $this->registrarMovimiento('actualizar usuario: '.$usuario->correo, 'editar usuarios', $usuario);

        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);

        $usuario->delete();
        $this->registrarMovimiento('borrar usuario: '.$usuario->email, 'borrar usuarios', $usuario);
        return redirect()->route('usuario.index')->with('success', 'Permiso borrado correctamente');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->desabilitado = !$usuario->desabilitado;
        $usuario->save();

        $this->registrarMovimiento('desabilitar usuario: '.$usuario->email, 'editar usuarios', $usuario);

        return response()->json([
            'success' => true,
            'nuevo_estado' => $usuario->desabilitado ? 'activo' : 'inactivo'
        ]);
    }

    public function search(Request $request){
        // dd($request->search);
        $usuarios = User::where('name', 'like', '%'.$request->search.'%')->paginate(10);
        $search = $request->search;

        $this->registrarMovimiento('buscar usuario: '.$search, 'ver usuario');

        return view('usuarios.index', compact('usuarios', 'search'));
    }
}
