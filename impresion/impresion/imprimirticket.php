<?php
require __DIR__ . '/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

date_default_timezone_set('America/Guayaquil'); // Establecer la zona horaria

header("Content-Type: application/json");

try {
    // Obtener datos del POST (por ejemplo, JSON enviado desde el servidor)
    $input = file_get_contents("php://input");
    $datos = json_decode($input);

    if (!$datos || empty($datos->nombre_impresora)) {
        throw new Exception("Datos incompletos o nombre de impresora no enviado.");
    }

    $nombre_impresora = $datos->nombre_impresora;

    // Conectar a la impresora compartida
    $connector = new WindowsPrintConnector($nombre_impresora);
    $printer = new Printer($connector);

    // Imprimir contenido
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Atendido por: " . $datos->nombre_encargado . "\n");
    $printer->text("Ticket: " . $datos->idAcceso . "\n");
    $printer->text("Persona: " . $datos->Nombres . "\n");
    $printer->text("Cedula: " . $datos->Cedula . "\n");
    $printer->text("Edificio: " . $datos->nombre_edificio . "\n");
    $printer->text("Tarjeta: " . $datos->CodigoTarjeta . "\n");
    $printer->text("Piso: " . $datos->nombre_piso . "\n");
    $printer->text("Organización: " . $datos->nombre_organizacion . "\n");
    $printer->text(date("Y-m-d H:i:s") . "\n"); // Hora en zona horaria de Guayaquil

    $printer->feed(3);
    $printer->cut();
    $printer->close();

    echo json_encode(["status" => "success", "message" => "Ticket impreso correctamente."]);

} catch (\Exception $e) {
    error_log('Error al imprimir ticket: ' . $e->getMessage());
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
