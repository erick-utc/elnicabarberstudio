<?php

namespace App\Exports;

use App\Models\Citas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use NumberFormatter;

class GananciasBarberosExport implements FromView
{
    public $fechaInicio, $fechaFin, $porcentaje;

    public function __construct($fechaInicio, $fechaFin, $porcentaje)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->porcentaje = $porcentaje;
    }

    public function view(): View
    {
        $formatter = new NumberFormatter('es_CR', NumberFormatter::CURRENCY);
        $resultados = Citas::with('barbero', 'paquete')
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
            })
            ->selectRaw('barbero_id, SUM(paquetes.precio * ?) as total_ganancia', [$this->porcentaje / 100])
            ->join('paquetes', 'citas.paquete_id', '=', 'paquetes.id')
            ->groupBy('barbero_id')
            ->with('barbero')
            ->get();

        foreach($resultados as $resultado) {
            $resultado->total_ganancia = $formatter->formatCurrency($resultado->total_ganancia, 'CRC');
        }

        return view('reportes.exportar_ganancias', [
            'resultados' => $resultados,
            'porcentaje' => $this->porcentaje
        ]);
    }
}
