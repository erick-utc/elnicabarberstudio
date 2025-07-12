<?php

namespace App\Http\Controllers;

use App\Models\Horarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\RegistraBitacora;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller implements HasMiddleware
{
    use RegistraBitacora;

    public static function middleware():array {
        return [
            new Middleware('permission:ver horarios', only: ['index', 'search', 'calendar']),
            new Middleware('permission:editar horarios', only: ['edit', 'update']),
            new Middleware('permission:crear horarios', only: ['create', 'store']),
            new Middleware('permission:borrar horarios', only: ['delete'])
        ];
    }

    public function index() {
        //si es administrador
        // $horarios = Horarios::orderBy('created_at','desc')->paginate(10);
        $horarios = Horarios::with('user')->orderBy('created_at','desc')->paginate(10);
        $search = '';

        $this->registrarMovimiento('ver', 'ver horarios');
        return view('horarios.index', compact('horarios', 'search'));
    }

    public function create() {
        $usuarios = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero')
                            ->orWhere('name', 'recepcionista');
                    })->get();

        $this->registrarMovimiento('crear nuevo horario', 'crear horarios');
        return view('horarios.create', compact('usuarios'));
    }

    public function store(Request $request) {
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
        $horario = Horarios::create([
            'user_id'=> $request->user_id,
            'dias'=> $request->dias,
            'inicio'=> $request->inicio,
            'fin'=> $request->fin
        ]);

        $this->registrarMovimiento('guardar nuevo horario de: '.$horario->user->correo, 'crear horarios', $horario);
        
        return redirect()->route('horario.index')->with('success', 'Horario agregado correctamente');
    }

    public function edit($id) {
        $usuarios = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero')
                            ->orWhere('name', 'recepcionista');
                    })->get(); 
        $horario = Horarios::findOrFail($id);
        $hasDias = $horario->dias;

        $this->registrarMovimiento('editar horario de: '.$horario->user->correo, 'editar horarios', $horario);

        return view('horarios.edit', compact('horario', 'usuarios', 'hasDias'));
    }

    public function update($id, Request $request) {
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
        $horario = Horarios::findOrFail($id);

        $horario -> user_id = $request -> user_id;
        $horario->dias = $request->dias;
        $horario->inicio = $request->inicio;
        $horario->fin = $request->fin;

        $horario->save();

        $this->registrarMovimiento('acutalizar horario de: '.$horario->user->correo, 'editar horarios', $horario);

        return redirect()->route('horario.index')->with('success', 'Horario actualizado correctamente');
    }

    public function destroy($id) {
        $horario = Horarios::findOrFail($id);

        $horario->delete();

        $this->registrarMovimiento('borrar horario de: '.$horario->user->correo, 'borrar horarios', $horario);

        return redirect()->route('horario.index')->with('success', 'Horario borrado correctamente');
    }

    public function calendar(Request $request)
    {
        $userId = $request->get('user_id');

        $query = Horarios::with('user');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $horarios = $query->get();

        $events = [];



        foreach ($horarios as $horario) {
            foreach ($horario->dias as $day) {
                // Para demo: crea eventos semanales
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
                    'title' => $horario->user->nombre . ' ' . $horario->user->primerApellido .' ' . $horario->user->segundoApellido . ' (' . strtoupper($day) . ')',
                    'startRecur' => now()->startOfWeek()->format('Y-m-d'),
                    'endRecur' => now()->addWeeks(4)->endOfWeek()->format('Y-m-d'),
                    'daysOfWeek' => [$dayNumber], // 0=domingo ... 6=sábado
                    'startTime' => $horario->inicio,
                    'endTime' => $horario->fin,
                    'color' => '#3a87ad',
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

        $this->registrarMovimiento('ver calendario horarios', 'ver horarios');

        return view('horarios.calendar', compact('events', 'barberos', 'isAdmin'));
    }

    public function search(Request $request) {
        $search = $request->search;
        $horarios = Horarios::whereHas('user', function ($query) use ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        })->paginate();

        $this->registrarMovimiento('buscar horarios: '.$search, 'ver horarios');

        return view('horarios.index', compact('horarios', 'search'));
    }
}
