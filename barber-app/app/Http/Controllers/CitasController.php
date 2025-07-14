<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paquetes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\RegistraBitacora;
use Illuminate\Support\Facades\Mail;
use App\Mail\CitaNotificadaMail;
use App\Enums\EstadoCita;

class CitasController extends Controller implements HasMiddleware
{
    use RegistraBitacora;

    public static function middleware():array {
        return [
            new Middleware('permission:ver citas', only: ['index']),
            new Middleware('permission:editar citas', only: ['edit']),
            new Middleware('permission:crear citas', only: ['create']),
            new Middleware('permission:borrar citas', only: ['delete'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        if($isAdmin) {
            $citas = Citas::with(['cliente', 'barbero', 'paquete'])->paginate(10);
        }else {
            $citas = Citas::where('cliente_id', $user->id)
                    ->orWhere('barbero_id', $user->id)
                    ->with('paquete')
                    ->paginate(10);
        }
        
       
        $search = '';
        $this->registrarMovimiento('ver' , 'ver citas');
        return view('citas.index', compact('citas', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $barberos = User::where('roles', 'barbero')->get();
        // $clientes = User::where('roles', 'cliente')->get();
        $barberos = User::role('barbero')->get();
        $clientes = User::role('cliente')->get();
        $paquetes = Paquetes::all();
        $dia = ucfirst(Carbon::now()->locale('es')->isoFormat('dddd')); // día actual
        $fecha = Carbon::now()->toDateString();
        $horas = [];
        $inicio = Carbon::parse('09:00');
        $fin = Carbon::parse('19:00');
        $id = null;
        $isClient = false;

        if($request->has('id')) {
            $id = $request->query('id');
        }

        if($request->has('isclient')) {
            $isClient = $request->query('isclient');
        }

        array_push($horas, $inicio);

        $aux = $inicio->copy();

        while($aux < $fin) {
            $aux->addMinutes(30);
            array_push($horas, $aux->copy());
        }

        // dd($barberos);

        $this->registrarMovimiento('crear nueva cita' , 'crear cita');

        return view('citas.create', compact('barberos', 'clientes', 'paquetes' ,'dia', 'fecha', 'horas', 'id', 'isClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $now_plus_30min = Carbon::now()->addMinutes(30)->format('H:i');
        $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'barbero_id' => 'required|exists:users,id',
            'paquete_id' => 'required|exists:paquetes,id',
            'fecha' => 'required',
            'hora' => 'required|date_format:H:i|after:now_plus_30min',
        ],[
            'client_id.required' => 'Seleccione un cliente',
            'barbero_id.required' => 'Seleccionae un barbero',
            'paquete_id.required' => 'Seleccione un paquete',
            'fecha.required' => 'Seleccione una fecha',
            'hora.required' => 'Seleccione la hora',
            'hora.after' => 'Solo puede crear citas despues de la próxima media hora'
        ]);


        $dia = ucfirst(Carbon::parse($request->fecha)->locale('es')->isoFormat('dddd')); // día actual
        $barbero = User::findOrFail($request->barbero_id);
        $hora = $request->hora;
        $isClient = false;


        if($request->has('isclient')) {
            $isClient = $request->query('isclient');
        }

        $existeCita = Citas::where('barbero_id', $request->barbero_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->exists();
        if ($existeCita) {
            return back()->withErrors(['hora' => 'Ya existe una cita agendada para ese barbero a esa hora.']);
        }

        $dentroHorario = $barbero->horarios->first(function ($horario) use ($dia, $hora, $request) {
            
            return in_array($dia, $horario->dias) &&
                $hora >= $horario->inicio &&
                $hora <  $horario->fin;
        });

        

        if (!$dentroHorario) {
            return back()->withErrors(['fecha' => 'La hora no está dentro del horario del barbero para ese día.']);
        }

        $enDescanso = $barbero->descansos->first(function ($descanso) use ($dia, $hora) {
            return in_array($dia, $descanso->dias) &&
                $hora >= $descanso->inicio &&
                $hora <  $descanso->fin;
        });

        if ($enDescanso) {
            return back()->withErrors(['fecha' => 'La hora está dentro de un descanso del barbero.']);
        }

        // dd($request->all());
        $cita = new Citas();
        $cita->fill($request->all());

        $cita->dia = $dia;

        $cita->save();

        Mail::to($cita->cliente->email)->send(new CitaNotificadaMail($cita, EstadoCita::Creada->value));
        Mail::to($cita->barbero->email)->send(new CitaNotificadaMail($cita, EstadoCita::Creada->value));

        // Citas::create($request->all());

        // dd($isClient);

        $this->registrarMovimiento('guardar nueva cita de: '.$cita->cliente->correo.'y barbero: '.$cita->barbero->correo, 'crear citas', $cita);

        if($isClient) {
            return redirect()->route('dashboard');
        }else {
            return redirect()->route('cita.index')->with('success', 'Cita creada correctamente');
        }
        
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
        $cita = Citas::findOrFail($id);
        $barberos = User::role('barbero')->get();
        $clientes = User::role('cliente')->get();
        $paquetes = Paquetes::all();
        $horas = [];
        $inicio = Carbon::parse('09:00');
        $fin = Carbon::parse('19:00');

        array_push($horas, $inicio);

        $aux = $inicio->copy();

        while($aux < $fin) {
            $aux->addMinutes(30);
            array_push($horas, $aux->copy());
        }

        // dd($horas);
        $this->registrarMovimiento('editar cita de: '.$cita->cliente->correo, 'editar citas', $cita);

        return view('citas.edit', compact('barberos', 'clientes', 'paquetes' , 'horas', 'cita'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $now_plus_30min = Carbon::now()->addMinutes(30)->format('H:i');
        $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'barbero_id' => 'required|exists:users,id',
            'paquete_id' => 'required|exists:paquetes,id',
            'fecha' => 'required',
            'hora' => 'required|date_format:H:i|after:now_plus_30min',
        ],[
            'client_id.required' => 'Seleccione un cliente',
            'barbero_id.required' => 'Seleccionae un barbero',
            'paquete_id.required' => 'Seleccione un paquete',
            'fecha.required' => 'Seleccione una fecha',
            'hora.required' => 'Seleccione la hora',
            'hora.after' => 'Solo puede crear citas despues de la próxima media hora'
        ]);
        $cita = Citas::findOrFail($id);
        $dia = ucfirst(Carbon::parse($request->fecha)->locale('es')->isoFormat('dddd')); // día actual
        $barbero = User::findOrFail($request->barbero_id);
        $hora = $request->hora;
        $isClient = false;


        if($request->has('isclient')) {
            $isClient = $request->query('isclient');
        }

        $existeCita = Citas::where('barbero_id', $request->barbero_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->exists();
        if ($existeCita) {
            return back()->withErrors(['hora' => 'Ya existe una cita agendada para ese barbero a esa hora.']);
        }
        

        $dentroHorario = $barbero->horarios->first(function ($horario) use ($dia, $hora, $request) {
            
            return in_array($dia, $horario->dias) &&
                $hora >= $horario->inicio &&
                $hora <  $horario->fin;
        });

        

        if (!$dentroHorario) {
            return back()->withErrors(['fecha' => 'La hora no está dentro del horario del barbero para ese día.']);
        }

        $enDescanso = $barbero->descansos->first(function ($descanso) use ($dia, $hora) {
            return in_array($dia, $descanso->dias) &&
                $hora >= $descanso->inicio &&
                $hora <  $descanso->fin;
        });

        if ($enDescanso) {
            return back()->withErrors(['fecha' => 'La hora está dentro de un descanso del barbero.']);
        }

        // dd($request->all());
        $cita->fill($request->all());

        $cita->dia = $dia;

        $cita->save();

        Mail::to($cita->cliente->email)->send(new CitaNotificadaMail($cita, EstadoCita::Editada->value));
        Mail::to($cita->barbero->email)->send(new CitaNotificadaMail($cita, EstadoCita::Editada->value));

        $this->registrarMovimiento('actualizar cita de cliente: '.$cita->cliente->correo.'y barbero:'.$cita->barbero->correo, 'editar descansos', $cita);

        // Citas::create($request->all());

        if($isClient) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('cita.index')->with('success', 'Cita actualizada correctamente');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cita = Citas::findOrFail($id);

        $cita->delete();

        $this->registrarMovimiento('borrar cita de cliente: '.$cita->cliente->correo.'y barbero: '.$cita->barbero->correo, 'borrar citas', $cita);

        Mail::to($cita->cliente->email)->send(new CitaNotificadaMail($cita, EstadoCita::Borrada->value));

        return redirect()->route('cita.index')->with('success', 'Cita borrada correctamente');
    }

    public function calendar(Request $request)
    {
        
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
        $userId = $request->get('user_id');
        $query = Citas::with('barbero');
        $barberos = User::whereHas('roles', function($query) {
                        $query->where('name', 'barbero');
                    })->get();
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

        if($isAdmin) {
            if ($userId) {
                $query->where('barbero_id', $userId);
            }
            $citas = $query->get();
        }else {
            $citas = Citas::where('cliente_id', $user->id)
                    ->orWhere('barbero_id', $user->id)
                    ->with('paquete')
                    ->get();
        }

        $events = $citas->map(function ($cita) {

            return [
                'id' => $cita->cita_id,
                'title' => 'barbero: '.$cita->barbero->name.' -cliente: '.$cita->cliente->name.' -paquete: '.$cita->paquete->nombre,
                'start' => $cita->fecha.'T'.$cita->hora,
                'end' => $cita->fecha.'T'.Carbon::parse($cita->hora)->addMinutes(30)->format('H:i'),
                // 'startTime' => $cita->hora,
                // 'endTime' => Carbon::parse($cita->hora)->copy()->addMinutes(30)->format('H:i'),
                // 'daysOfWeek' => [$dayNumber], // 0=domingo ... 6=sábado
                'color' => '#000000',
            ];
        });

        $userString = $isAdmin ? 'administrador' : 'usuario normal';
        $this->registrarMovimiento('ver calendario citas como: '.$userString, 'ver citas');

        return view('citas.calendar', compact('events', 'barberos', 'isAdmin'));
    }

    public function search(Request $request) {
        $search = $request->search;
        $citas = Citas::whereHas('user', function ($query) use ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        })->paginate();

        $this->registrarMovimiento('buscar citas: '.$search, 'ver citas');

        return view('citas.index', compact('citas', 'search'));
    }

    public function horasNoDisponibles(Request $request)
    {
        $barberoId = $request->query('barbero_id');
        $fecha = $request->query('fecha') ?? Carbon::now()->toDateString();

        if (!$barberoId) {
            return response()->json(['error' => 'Barbero ID requerido'], 400);
        }

        $barbero = User::with('horarios', 'descansos')->findOrFail($barberoId);
        $dia = ucfirst(Carbon::parse($fecha)->locale('es')->isoFormat('dddd'));
        $dia = CitasController::quitarAcentos(ucfirst(Carbon::parse($fecha)->locale('es')->isoFormat('dddd')));

        $inicio = Carbon::parse('09:00');
        $fin = Carbon::parse('19:00');

        $horas_posibles = [];
        $aux = $inicio->copy();
        while ($aux < $fin) {
            $horas_posibles[] = $aux->format('H:i');
            $aux->addMinutes(30);
        }

        // Obtener citas ya agendadas para ese día y barbero
        $horas_citas = Citas::where('barbero_id', $barberoId)
            ->where('fecha', $fecha)
            ->pluck('hora')
            ->toArray();

        // Horarios válidos del barbero para ese día
        $horariosDia = $barbero->horarios->filter(function ($horario) use ($dia) {
            return in_array($dia, $horario->dias);
        });

        // Descansos del barbero para ese día
        $descansosDia = $barbero->descansos->filter(function ($descanso) use ($dia) {
            return in_array($dia, $descanso->dias);
        });

        $horas_no_disponibles = [];

        foreach ($horas_posibles as $hora) {
            $enHorario = $horariosDia->first(function ($horario) use ($hora) {
                return $hora >= $horario->inicio && $hora < $horario->fin;
            });

            $enDescanso = $descansosDia->first(function ($descanso) use ($hora) {
                return $hora >= $descanso->inicio && $hora < $descanso->fin;
            });

            if (!$enHorario || $enDescanso || in_array($hora, $horas_citas)) {
                $horas_no_disponibles[] = $hora;
            }
        }

        return response()->json([
            'horas_no_disponibles' => $horas_no_disponibles,
            'horas_posibles' => $horas_posibles,
        ]);
    }

    public function quitarAcentos($cadena) {
        return strtr($cadena, [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'N'
        ]);
    }
}
