<x-mail::message>
# Notificación de Cita

La cita ha sido **{{ $tipo }}**.

- Cliente: {{ $cita->cliente->name.' '.$cita->cliente->primerApellido.' '.$cita->cliente->segundoApellido }}
- Barbero: {{ $cita->barbero->name.' '.$cita->barbero->primerApellido.' '.$cita->barbero->segundoApellido }}
- Paquete: {{ $cita->paquete->nombre.' - '.$cita->paquete->descripcion }}
- Fecha: {{ $cita->fecha }}
- Hora: {{ $cita->hora }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
