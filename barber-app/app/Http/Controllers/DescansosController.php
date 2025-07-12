<?php

namespace App\Http\Controllers;

use App\Models\Descansos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\RegistraBitacora;
use Illuminate\Support\Facades\Auth;

class DescansosController extends Controller implements HasMiddleware
{
    use RegistraBitacora;

    public static function middleware():array {
        return [
            new Middleware('permission:ver descansos', only: ['index']),
            new Middleware('permission:editar descansos', only: ['edit']),
            new Middleware('permission:crear descansos', only: ['create']),
            new Middleware('permission:borrar descansos', only: ['delete'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $descansos = Descansos::with('user')->orderBy('created_at','desc')->paginate(10);
        $search = '';

        $this->registrarMovimiento('ver' , 'ver descansos');
        return view('descansos.index', compact('descansos', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero')
                            ->orWhere('name', 'recepcionista');
                    })->get();

        $this->registrarMovimiento('crear nuevo descanso', 'crear descansos');
        return view('descansos.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dias' => 'required|array',
            'inicio' => 'required|date_format:H:i',
            'fin' => 'required|date_format:H:i|after:inicio',
        ],[
            'dias.required' => 'Debe seleccionar al menos un dia de la semana',
            'inicio.required' => 'La Hora de Entrada es requerida',
            'fin.required' => 'La Hora de Salida es requerida',
            'fin.after' => 'La Hora de Salida debe ser despues de la Hora de Entrada'
        ]);

        $descanso = Descansos::create([
            'user_id'=> $request->user_id,
            'dias'=> $request->dias,
            'inicio'=> $request->inicio,
            'fin'=> $request->fin
        ]);


        $this->registrarMovimiento('guardar nuevo descanso de: '.$descanso->user->correo, 'crear descansos', $descanso);
        return redirect()->route('descanso.index')->with('success', 'Descanso agregado correctamente'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuarios = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero')
                            ->orWhere('name', 'recepcionista');
                    })->get();
        $descanso = Descansos::findOrFail($id);
        $hasDias = $descanso->dias;

        $this->registrarMovimiento('editar descanso de: '.$descanso->user->correo, 'editar descansos', $descanso);
        return view('descansos.edit', compact('descanso', 'usuarios', 'hasDias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
            'dias' => 'required|array',
            'inicio' => 'required|date_format:H:i',
            'fin' => 'required|date_format:H:i|after:inicio',
        ],[
            'user_id.required' => 'Debe seleccionar un barbero',
            'dias.required' => 'Debe seleccionar al menos un dia de la semana',
            'inicio.required' => 'La Hora de Entrada es requerida',
            'fin.required' => 'La Hora de Salida es requerida',
            'fin.after' => 'La Hora de Salida debe ser despues de la Hora de Entrada'
        ]);

        $descanso = Descansos::findOrFail($id);

        $descanso -> user_id = $request -> user_id;
        $descanso->dias = $request->dias;
        $descanso->inicio = $request->inicio;
        $descanso->fin = $request->fin;

        $descanso->save();

        $this->registrarMovimiento('actualizar descanso de: '.$descanso->user->correo, 'editar descansos', $descanso);

        return redirect()->route('descanso.index')->with('success', 'Descanso actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $descanso = Descansos::findOrFail($id);

        $descanso->delete();

        $this->registrarMovimiento('borrar descanso de: '.$descanso->user->correo, 'borrar descansos', $descanso);

        return redirect()->route('descanso.index')->with('success', 'Descanso borrado correctamente');
    }

    public function calendar(Request $request) {
        $userId = $request->get('user_id');

        $query = Descansos::with('user');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $descansos = $query->get();

        $events = [];

        foreach ($descansos as $descanso) {
            foreach ($descanso->dias as $day) {
                $dayMap = [
                    'domingo' => 0,
                    'lunes' => 1,
                    'martes' => 2,
                    'miércoles' => 3,
                    'miercoles' => 3, // sin tilde también
                    'jueves' => 4,
                    'viernes' => 5,
                    'sábado' => 6,
                    'sabado' => 6, // sin tilde también
                ];

                $dayKey = strtolower($day);
                $dayNumber = $dayMap[$dayKey] ?? null;

                $events[] = [
                    'title' => $descanso->user->nombre . ' ' . $descanso->user->primerApellido .' ' . $descanso->user->segundoApellido . ' (' . strtoupper($day) . ')',
                    'startRecur' => now()->startOfWeek()->format('Y-m-d'),
                    'endRecur' => now()->addWeeks(4)->endOfWeek()->format('Y-m-d'),
                    'daysOfWeek' => [$dayNumber], // 0=domingo ... 6=sábado
                    'startTime' => $descanso->inicio,
                    'endTime' => $descanso->fin,
                    'color' => '#c04441',
                ];
            }
        }

        $barberos = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero')
                            ->orWhere('name', 'recepcionista');
                    })->get();

        $user = Auth::user();
        $isAdmin = false;
        // dd($user->roles);
        if($user->roles) {
            foreach($user->roles as $role) {
                // dd($role->name);
                if($role->name == 'admin' || $role->name == 'recepcionista') {
                    $isAdmin = true;
                }
            }
        }

        $this->registrarMovimiento('ver calendario descansos', 'ver descansos');

        return view('descansos.calendar', compact('events', 'barberos', 'isAdmin'));
    }

    public function search(Request $request) {
        $search = $request->search;
        $descansos = Descansos::whereHas('user', function ($query) use ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        })->paginate();

        $this->registrarMovimiento('buscar descansos: '.$search, 'ver descansos');

        return view('descansos.index', compact('descansos', 'search'));
    }
}
