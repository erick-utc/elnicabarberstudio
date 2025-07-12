<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Traits\RegistraBitacora;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RolesController extends Controller implements HasMiddleware
{
    public static function middleware():array {
        return [
            new Middleware('permission:ver roles', only: ['index']),
            new Middleware('permission:editar roles', only: ['edit']),
            new Middleware('permission:crear roles', only: ['create']),
            new Middleware('permission:borrar roles', only: ['delete'])
        ];
    }
    use RegistraBitacora;

    public function index() {
        $roles = Role::orderBy('created_at','desc')->paginate(10);
        $search = '';

        $this->registrarMovimiento('ver', 'ver roles');

        return view('roles.index', compact('roles', 'search'));
    }

    public function create() {
        $permisos = Permission::orderBy('name', 'asc')->get();

        $this->registrarMovimiento('crear nuevo rol', 'crear roles');

        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:roles|min:3'
        ]);

        if($validator->passes()) {
            $role = Role::create(['name'=> $request->name]);

            if(!empty($request->permisos)) {
                foreach($request->permisos as $name) {
                    $role->givePermissionTo($name);
                }
            }

            $this->registrarMovimiento('guardar nuevo rol '.$role->name, 'crear roles', $role);
            
            return redirect()->route('role.index')->with('success', 'Role agregado correctamente');
        }else {
            return redirect()->route('role.create')->withInput()->withErrors($validator);
        }   
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        $permisos = Permission::orderBy('name', 'asc')->get();
        $hasPermisos = $role->permissions->pluck('name');
        
        $this->registrarMovimiento('editar rol', 'edit roles', $role);

        return view('roles.edit', compact('role', 'permisos', 'hasPermisos'));
    }

    public function update($id, Request $request) {
        $role = Role::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name'=> 'required|min:3|unique:roles,name,'.$id.',id'
        ]);

        if($validator->passes()) {
            $role->name = $request->name;
            $role->save();

            if(!empty($request->permisos)) {
                $role->syncPermissions($request->permisos);
            }else {
                $role->syncPermissions([]);
            }

            $this->registrarMovimiento('actualizar rol: '.$role->name, 'edit roles', $role);
            
            return redirect()->route('role.index')->with('success', 'Role actualizado correctamente');
        }else {
            return redirect()->route('role.edit', $id)->withInput()->withErrors($validator);
        }
    }

    public function destroy($id) {
        $role = Role::findOrFail($id);

        $role->delete();

        $this->registrarMovimiento('borrar rol: '.$role->name, 'borrar roles', $role);
       
        return redirect()->route('role.index')->with('success', 'Role borrado correctamente');
    }

    public function search(Request $request) {
        $roles = Role::where('name', 'like', '%'.$request->search.'%')->paginate(10);
        $search= $request->search;

        $this->registrarMovimiento('buscar roles: '.$search, 'ver roles');

        return view('roles.index', compact('roles', 'search'));
    }
}
