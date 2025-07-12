<?php

namespace App\Http\Controllers;

use App\Models\Paquetes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\RegistraBitacora;

class PaquetesController extends Controller implements HasMiddleware
{
    use RegistraBitacora;

    public static function middleware():array {
        return [
            new Middleware('permission:ver paquetes', only: ['index']),
            new Middleware('permission:editar paquetes', only: ['edit']),
            new Middleware('permission:crear paquetes', only: ['create']),
            new Middleware('permission:borrar paquetes', only: ['delete'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paquetes = Paquetes::orderBy('created_at', 'asc')->paginate(10);
        $search = '';
        $this->registrarMovimiento('ver', 'ver paquetes');
        return view('paquetes.index', compact('paquetes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->registrarMovimiento('crear nuevo paquete', 'crear paquetes');
        return view('paquetes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(),[
        //     'name'=> 'required|unique:paquetes|min:3',
        //     // 'precio'=> 'required|min:3',
        // ]);

        // if($validator->passes()) {
            $paquete = new Paquetes();
            $paquete->fill($request->all());
            $paquete->save();

            $this->registrarMovimiento('guardar nuevo paquete: '.$paquete->nombre, 'crear paquetes', $paquete);
            
            return redirect()->route('paquete.index')->with('success', 'Paquete agregado correctamente');
        // }else {
            // return redirect()->route('paquete.create')->withInput()->withErrors($validator);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paquete = Paquetes::findOrFail($id);

        $this->registrarMovimiento('editar paquete: '.$paquete->nombre, 'editar paquetes', $paquete);

        return view('paquetes.edit', compact('paquete'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paquete = Paquetes::findOrFail($id);

        $paquete->update($request->all());

        $this->registrarMovimiento('actualizar paquete: '.$paquete->nombre, 'editar paquetes', $paquete);

        return redirect()->route('paquete.index')->with('success', 'Paquete actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paquete = Paquetes::findOrFail($id);

        $paquete->delete();

        $this->registrarMovimiento('borrar paquete: '.$paquete->nombre, 'editar paquetes', $paquete);

        return redirect()->route('paquete.index')->with('success', 'Paquete borrado correctamente');
    }

    public function search(Request $request) {
        // dd($request->search);
        $paquetes = Paquetes::where('nombre', 'like', '%'.$request->search.'%')->paginate(10);
        $search = $request->search;

        $this->registrarMovimiento('buscar paquetes: '.$search, 'ver paquetes');

        return view('paquetes.index', compact('paquetes', 'search'));
    }
}
