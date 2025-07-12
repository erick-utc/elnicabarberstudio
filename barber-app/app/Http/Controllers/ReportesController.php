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

}


