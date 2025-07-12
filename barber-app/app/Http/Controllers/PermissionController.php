<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\RegistraBitacora;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware():array {
        return [
            new Middleware('permission:ver permisos', only: ['index']),
            new Middleware('permission:editar permisos', only: ['edit']),
            new Middleware('permission:crear permisos', only: ['create']),
            new Middleware('permission:borrar permisos', only: ['delete'])
        ];
    }

    use RegistraBitacora;
    //this method will show permissions page
    public function index() {
        $permisos = Permission::orderBy('created_at','desc')->paginate(10);
        $search = '';
        $this->registrarMovimiento('ver', 'ver permisos');
        return view('permissions.index', compact('permisos','search'));
    }

    public function create() {
        $this->registrarMovimiento('crear nuevo permiso', 'crear permisos');
        return view('permissions.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:permissions|min:3'
        ]);

        if($validator->passes()) {
            $permiso = Permission::create(['name'=> $request->name]);
            $this->registrarMovimiento('guardar nuevo permiso: '.$permiso->name, 'crear permisos', $permiso);
            
            return redirect()->route('permission.index')->with('success', 'Permiso agregado correctamente');
        }else {
            return redirect()->route('permission.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id) {
        $permiso = Permission::findOrFail($id);

        $this->registrarMovimiento('editar permiso', 'editar permisos', $permiso);
        return view('permissions.edit', compact('permiso'));
    }

    public function update($id, Request $request) {
        $permiso = Permission::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name'=> 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);

        if($validator->passes()) {
            $permiso->name = $request->name;
            $permiso->save();
            
            $this->registrarMovimiento('actualizar permiso: '.$permiso->name, 'editar permisos', $permiso);
            return redirect()->route('permission.index')->with('success', 'Permiso actualizado correctamente');
        }else {
            return redirect()->route('permission.edit',$id)->withInput()->withErrors($validator);
        }
    }

    public function destroy($id) {
            $permiso = Permission::findOrFail($id);

            $permiso->delete();
            $this->registrarMovimiento('borrar permiso: '.$permiso->name, 'borrar permisos', $permiso);
            return redirect()->route('permission.index')->with('success', 'Permiso borrado correctamente');
    }

    public function search(Request $request){
        // dd($request->search);
        $permisos = Permission::where('name', 'like', '%'.$request->search.'%')->paginate(10);
        $search = $request->search;

        $this->registrarMovimiento('buscar permisos: '.$search, 'ver permisos');

        return view('permissions.index', compact('permisos', 'search'));
    }
}
