<?php

namespace App\Listeners;

use App\Events\EmpresaSolicitudRegistrada;
use App\Mail\NuevaSolicitudEmpresaMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoNuevaSolicitudEmpresa
{
    public function handle(EmpresaSolicitudRegistrada $event): void
    {
        try {
            $admins = User::administradores()->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NuevaSolicitudEmpresaMail($event->empresa, $event->user));
            }
        } catch (\Throwable $e) {
            Log::error('Fallo al enviar correo de nueva solicitud de empresa a administradores', [
                'exception' => $e,
                'id_empresa' => $event->empresa->id_empresa,
            ]);
        }
    }
}
