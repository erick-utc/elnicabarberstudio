<?php

namespace App\Listeners;

use IlluminateAuthEventsLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BitacoraAcceso;
use Illuminate\Auth\Events\Logout;

class RegistrarBitacoraLogout
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
    public function handle(Logout $event)
{
    BitacoraAcceso::create([
        'user_id' => $event->user->id,
        'tipo' => 'logout',
        'ip' => request()->ip(),
        'navegador' => request()->header('User-Agent'),
    ]);
}
}
