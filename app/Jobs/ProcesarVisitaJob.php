<?php

namespace App\Jobs;

use App\Mail\EnviarEmail;
use App\AuditoriasModelo;
use App\PermisosModelo;
use App\ImpresorasModelo;
use App\Services\TelegramServices;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesarVisitaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datos;
    protected $personas;
    protected $visitas;
    protected $usuario_id;
    protected $guardo;
     protected $deseaImprimir;

    public $tries = 3;

    public function __construct($datos, $personas, $visitas, $usuario_id, $guardo, $deseaImprimir)
    {
        $this->datos = $datos;
        $this->personas = $personas;
        $this->visitas = $visitas;
        $this->usuario_id = $usuario_id;
        $this->guardo = $guardo;
         $this->deseaImprimir = $deseaImprimir;
    }

    public function handle()
    {
        $this->datos = json_decode($this->datos);

            if ($this->deseaImprimir=="true" ) {
               


            try {
                $impresora = ImpresorasModelo::join('Users', 'Impresoras.idImpresora', '=', 'Users.idImpresora')
                    ->where('Users.id', $this->usuario_id)
                    ->select(['Impresoras.Nombre', 'Users.name as encargado'])
                    ->first();

                if (!$impresora) {
                    Log::warning("No se encontró impresora para el usuario ID: {$this->usuario_id}");
                    return;
                }
                list($url, $nombre_impresora) = explode('/', $impresora->Nombre);
                $this->datos->nombre_impresora = $nombre_impresora;
                $this->datos->nombre_encargado = $impresora->encargado;
            $this->imprimir($url,$this->datos); // Llama a la función de impresión
       
            } catch (\Exception $ex) {
                Log::error('Error al imprimir: ' . $ex->getMessage());
            }
        }

       try {
    Mail::to($this->datos->email)->send(new EnviarEmail($this->datos));
    Log::info("Correo enviado correctamente a {$this->datos->email}");
} catch (\Exception $e) {
    Log::error("Error al enviar correo a {$this->datos->email}: " . $e->getMessage());
}

        // Enviar mensaje a Telegram
        $telegram = new TelegramServices();
        $fecha = now()->format('Y-m-d H:i:s');
		$organizacion = $this->datos->nombre_organizacion ?: 'Centro financiero';

$message = <<<TEXT
La persona {$this->personas->Nombres}
Cédula: {$this->personas->Cedula}
Sexo: {$this->personas->Sexo}
Ha visitado la organización: {$organizacion}
Código Tarjeta: {$this->visitas->CodigoTarjeta}
Ubicada en el edificio: {$organizacion}
Piso: {$this->datos->nombre_piso}
Ticket número: {$this->datos->idAcceso}
Fecha y Hora: {$fecha}
TEXT;
        $telegram->sendMessage($message);

        // Registrar en auditoría
        AuditoriasModelo::create([
            'idUSuario' => $this->usuario_id,
            'Accion' => "La persona: {$this->personas->Nombres} - {$this->personas->Cedula} ha visitado la organización {$this->datos->nombre_organizacion}"
        ]);
    }

private function imprimir($url, $datos)
{
    try {
        // Solo decodificar si viene como string
        if (is_string($datos)) {
            $datos = json_decode($datos);
        }

        // Separar IP y nombre de impresora si vienen juntos
        if (strpos($url, '/') !== false) {
            list($ip, $nombre_impresora) = explode('/', $url);
            $url = $ip;
            $datos->nombre_impresora = $nombre_impresora;
        }

        $clienteUrl = 'http://' . $url . '/impresion/imprimirticket.php';

        $payload = json_encode([
            "nombre_impresora" => $datos->nombre_impresora ?? '',
            "nombre_encargado" => $datos->nombre_encargado ?? '',
            "idAcceso" => $datos->idAcceso ?? '',
            "Nombres" => $datos->Nombres ?? '',
            "Cedula" => $datos->Cedula ?? '',
            "nombre_edificio" => $datos->nombre_edificio ?? '',
            "CodigoTarjeta" => $datos->CodigoTarjeta ?? '',
            "nombre_piso" => $datos->nombre_piso ?? '',
            "nombre_organizacion" => $datos->nombre_organizacion ?? ''
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $clienteUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            \Log::error('cURL error: ' . curl_error($ch));
        } else {
            \Log::info('Respuesta cURL: ' . $response);
        }

        curl_close($ch);
    } catch (\Exception $e) {
        \Log::error('Error al imprimir con cURL: ' . $e->getMessage());
    }
}



}
