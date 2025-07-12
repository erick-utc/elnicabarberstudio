<?php

namespace App\Listeners;

use IlluminateAuthEventsLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BitacoraAcceso;
use Illuminate\Auth\Events\Login;

class RegistrarBitacoraLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
{
    BitacoraAcceso::create([
        'user_id' => $event->user->id,
        'tipo' => 'login',
        'ip' => request()->ip(),
        'navegador' => request()->header('User-Agent'),
    ]);
}
}
