<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use NumberFormatter;
use App\Exports\GananciasBarberosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReporteGananciasMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index() {
        return view('reportes.index');
    }

    public function indexpaquetes() {
        $inicio = Carbon::now()->toDateString();
        $fin = Carbon::now()->toDateString();
        $resultados = [];
        return view('reportes.indexpaquetes', compact('resultados', 'inicio', 'fin'));
    }

    public function paquetesPorFecha(Request $request)
    {
        $inicio = Carbon::parse($request->inicio)->toDateString();
        $fin = Carbon::parse($request->fin)->toDateString();

        $resultados = Citas::with('paquete')
            ->when($inicio && $fin, function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha', [$inicio, $fin]);
            })
            ->selectRaw('paquete_id, COUNT(*) as total')
            ->groupBy('paquete_id')
            ->get();

            // dd($resultados);

        return view('reportes.indexpaquetes', compact('resultados', 'inicio', 'fin'));
    }

    public function indexganancias() {
        $inicio = Carbon::now()->toDateString();
        $fin = Carbon::now()->toDateString();
        $resultados = [];
        $porcentaje = 0;
        return view('reportes.indexganancias', compact('resultados', 'porcentaje', 'inicio', 'fin'));
    }

    public function gananciasBarberos(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $porcentaje = $request->porcentaje ?? 100;

        $fileName = 'ganancias_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = 'reportes/' . $fileName;


        $formatter = new NumberFormatter('es_CR', NumberFormatter::CURRENCY);

        $resultados = Citas::with('barbero', 'paquete')
            ->when($inicio && $fin, function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha', [$inicio, $fin]);
            })
            ->selectRaw('barbero_id, SUM(paquetes.precio * ?) as total_ganancia', [$porcentaje / 100])
            ->join('paquetes', 'citas.paquete_id', '=', 'paquetes.id')
            ->groupBy('barbero_id')
            ->with('barbero')
            ->get();

        foreach($resultados as $resultado) {
            $resultado->total_ganancia = $formatter->formatCurrency($resultado->total_ganancia, 'CRC');
        }

        Excel::store(new GananciasBarberosExport($inicio, $fin, $porcentaje), $filePath);

        $admin = Auth::user();

        Mail::to($admin->email)->send(new ReporteGananciasMail($filePath));

            // dd($resultados);

        return view('reportes.indexganancias', compact('resultados', 'porcentaje', 'inicio', 'fin'));
    }

    public function citasPorFecha(Request $request)
    {
        $tipo = $request->input('tipo', 'diario'); // diario, semanal, mensual
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $query = Citas::whereBetween('fecha', [$fechaInicio, $fechaFin]);

        $citas = $query->get()->groupBy(function ($cita) use ($tipo) {
            return match($tipo) {
                'semanal' => \Carbon\Carbon::parse($cita->fecha)->startOfWeek()->format('Y-m-d'),
                'mensual' => \Carbon\Carbon::parse($cita->fecha)->format('Y-m'),
                default => $cita->fecha,
            };
        });

        return view('reportes.citas', compact('citas', 'tipo', 'fechaInicio', 'fechaFin'));
    }

    public function clientesFrecuentes(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $frecuentes = Citas::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('cliente_id', DB::raw('COUNT(*) as total'))
            ->groupBy('cliente_id')
            ->orderByDesc('total')
            ->with('cliente')
            ->get();

        return view('reportes.clientes', compact('frecuentes', 'fechaInicio', 'fechaFin'));
    }

    public function usoHorarioBarberos(Request $request)
    {
        $fecha = $request->input('fecha', now()->toDateString());
        $barberos = User::role('barbero')->with(['horarios', 'citasComoBarbero' => function ($query) use ($fecha) {
            $query->where('fecha', $fecha);
        }])->get();

        $data = [];

        foreach ($barberos as $barbero) {
            $horarios = $barbero->horarios->filter(fn($h) => in_array(\Carbon\Carbon::parse($fecha)->format('l'), $h->dias));
            $horasDisponibles = 0;

            foreach ($horarios as $h) {
                $horasDisponibles += \Carbon\Carbon::parse($h->fin)->diffInMinutes($h->inicio);
            }

            $horasAgendadas = count($barbero->citasComoBarbero) * 30; // asumiendo 30 minutos por cita

            $porcentaje = $horasDisponibles > 0
                ? round(($horasAgendadas / $horasDisponibles) * 100, 2)
                : 0;

            $data[] = [
                'nombre' => $barbero->name.' '.$barbero->primerApellido.' '.$barbero->segundoApellido,
                'min_disponibles' => $horasDisponibles,
                'min_ocupados' => $horasAgendadas,
                'porcentaje' => $porcentaje,
            ];
        }

        return view('reportes.uso_horario', compact('data', 'fecha'));
    }
}


