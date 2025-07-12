<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\DescansosController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PaquetesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\BitacoraAccesosController;
use App\Http\Controllers\BitacoraMovimientosController;
use App\Http\Controllers\BitacorasController;
use App\Http\Controllers\ReportesController;
use App\Models\Paquetes;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

Route::get('/', function () {
    $paquetes = Paquetes::orderBy('created_at', 'asc')->get();
    $imagenes = [
        'images/WhatsApp Image 2025-06-15 at 14.56.34 (2).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.56.34.jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.42.44.jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.42.43 (1).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.43.13 (1).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.56.34 (1).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.42.44 (1).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.56.34 (3).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.42.52 (1).jpeg',
        'images/WhatsApp Image 2025-06-15 at 14.43.01 (1).jpeg',
    ];
    return view('welcome', compact('paquetes', 'imagenes'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $paquetes = Paquetes::orderBy('created_at', 'asc')->get();
        $imagenes = [
            'images/WhatsApp Image 2025-06-15 at 14.56.34 (2).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.56.34.jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.42.44.jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.42.43 (1).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.43.13 (1).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.56.34 (1).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.42.44 (1).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.56.34 (3).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.42.52 (1).jpeg',
            'images/WhatsApp Image 2025-06-15 at 14.43.01 (1).jpeg',
        ];
        return view('dashboard', compact('paquetes', 'imagenes'));
    })->name('dashboard');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/permissions/search', [PermissionController::class, 'search'])->name('permission.search');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permission.store');
    Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permissions/{permiso}', [PermissionController::class, 'destroy'])->name('permission.destroy');
    

    Route::get('/roles', [RolesController::class, 'index'])->name('role.index');
     Route::post('/roles/search', [RolesController::class, 'search'])->name('role.search');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('role.create');
    Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('role.edit');
    Route::post('/roles', [RolesController::class, 'store'])->name('role.store');
    Route::post('/roles/{id}', [RolesController::class, 'update'])->name('role.update');
    Route::delete('/roles/{role}', [RolesController::class, 'destroy'])->name('role.destroy');
   

    Route::get('/horarios', [HorarioController::class, 'index'])->name('horario.index');
    Route::post('/horarios/search', [HorarioController::class, 'search'])->name('horario.search');
    Route::get('/horarios/create', [HorarioController::class, 'create'])->name('horario.create');
    Route::get('/horarios/{id}/edit', [HorarioController::class, 'edit'])->name('horario.edit');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horario.store');
    Route::post('/horarios/{id}', [HorarioController::class, 'update'])->name('horario.update');
    Route::delete('/horarios/{role}', [HorarioController::class, 'destroy'])->name('horario.destroy');
    Route::get('/horarios/calendar', [HorarioController::class, 'calendar'])->name('horario.calendar');
    

    Route::get('/descansos', [DescansosController::class, 'index'])->name('descanso.index');
    Route::post('/descansos/search', [DescansosController::class, 'search'])->name('descanso.search');
    Route::get('/descansos/create', [DescansosController::class, 'create'])->name('descanso.create');
    Route::get('/descansos/{id}/edit', [DescansosController::class, 'edit'])->name('descanso.edit');
    Route::post('/descansos', [DescansosController::class, 'store'])->name('descanso.store');
    Route::post('/descansos/{id}', [DescansosController::class, 'update'])->name('descanso.update');
    Route::delete('/descansos/{role}', [DescansosController::class, 'destroy'])->name('descanso.destroy');
    Route::get('/descansos/calendar', [DescansosController::class, 'calendar'])->name('descanso.calendar');
    

    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuario.index');
    Route::get('/usuarios/create', [UsuariosController::class, 'create'])->name('usuario.create');
    Route::post('/usuarios/search', [UsuariosController::class, 'search'])->name('usuario.search');
    Route::post('/usuarios/{id}/estado', [UsuariosController::class, 'cambiarEstado'])->name('usuarios.estado');
    Route::get('/usuarios/{id}/edit', [UsuariosController::class, 'edit'])->name('usuario.edit');
    Route::get('/usuarios/{id}/edit/roles', [UsuariosController::class, 'editroles'])->name('usuario.editroles');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuario.store');
    Route::post('/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuario.update');
    Route::post('/usuarios/{id}/roles', [UsuariosController::class, 'updateroles'])->name('usuario.updateroles');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');

    // Route::get('/horarios/calendar', [UsuariosController::class, 'calendar'])->name('usuario.calendar');
   

    Route::get('/paquetes', [PaquetesController::class, 'index'])->name('paquete.index');
    Route::post('/paquetes/search', [PaquetesController::class, 'search'])->name('paquete.search');
    Route::get('/paquetes/create', [PaquetesController::class, 'create'])->name('paquete.create');
    Route::get('/paquetes/{id}/edit', [PaquetesController::class, 'edit'])->name('paquete.edit');
    Route::post('/paquetes', [PaquetesController::class, 'store'])->name('paquete.store');
    Route::post('/paquetes/{id}', [PaquetesController::class, 'update'])->name('paquete.update');
    Route::delete('/paquetes/{role}', [PaquetesController::class, 'destroy'])->name('paquete.destroy');
    

    Route::get('/citas', [CitasController::class, 'index'])->name('cita.index');
    Route::post('/citas/search', [CitasController::class, 'search'])->name('citas.search');
    Route::get('/citas/create', [CitasController::class, 'create'])->name('cita.create');
    Route::get('/citas/{id}/edit', [CitasController::class, 'edit'])->name('cita.edit');
    Route::post('/citas', [CitasController::class, 'store'])->name('cita.store');
    Route::post('/citas/{id}', [CitasController::class, 'update'])->name('cita.update');
    Route::delete('/citas/{id}', [CitasController::class, 'destroy'])->name('cita.destroy');
    Route::get('/citas/calendar', [CitasController::class, 'calendar'])->name('cita.calendar');
    
    Route::get('/bitacoras', [BitacorasController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacoras/accesos', [BitacoraAccesosController::class, 'index'])->name('bitacora.accesos');
    Route::get('/bitacoras/movimientos', [BitacoraMovimientosController::class, 'index'])->name('bitacora.movimientos');

    Route::get('/reportes', [ReportesController::class, 'index'])->name('reporte.index');
    Route::get('/reportes/paquetes', [ReportesController::class, 'indexpaquetes'])->name('reporte.indexpaquetes');
    Route::get('/reportes/ganacias', [ReportesController::class, 'indexganancias'])->name('reporte.indexganancias');
    Route::post('/reportes/paquetes', [ReportesController::class, 'paquetesPorFecha'])->name('reporte.paquetesPorFecha');
    Route::post('/reportes/ganancias', [ReportesController::class, 'gananciasBarberos'])->name('reporte.gananciasBarberos');

    Route::get('/barbero/horas-disponibles', [CitasController::class, 'horasNoDisponibles'])->name('barbero.horas');

    Route::get('/acerca-de', function() {
        return view('acerca.index');
    })->name('acerca.index');
    Route::get('/ayuda', function() {
        return view('ayuda.index');
    })->name('ayuda.index');
});
