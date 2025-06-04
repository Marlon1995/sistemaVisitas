<?php /*
$headers = array('Content-Type' => 'application/json',
    'Accept' => 'application/json',
    'project_id'=>'173974305427',
    'Authorization'=>'key=AIzaSyCTJJ5MIdSucrvKVcsnekmbD7bQatpvm4g'
);
$body = json_encode(array('operation'=>'create',
        'notification_key_name'=> 'app-Chris',
        'registration_ids'=>array('dyTYXtnx0k8:APA91bHbFpa6RPPe0Ccbu6ArTbgdodxhvXrvoMkrwu0zbvjoFMTIRqVaLuXbwI2BlZJiFXS95DsIfMo-FBG-07BQWVifvOMACPxjeKWbVi21GzkmenyPs4DaHcWGDLSN7sBaQCHceSog')
    )
);
$options = ['headers'=>$headers,'body'=> $body];
$client = new Client();
$res = $client->post('https://fcm.googleapis.com/fcm/notification', $options);
return Response::json($res->getBody()->getContents());

require __DIR__ . '/ticket/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Producto{
    public function __construct($nombre, $precio, $cantidad){
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
    }
}
$productos = array(
    new Producto("Papas fritas", 10, 1),
    new Producto("Pringles", 22, 2),
    new Producto("Galletas saladas con un sabor muy salado y un precio excelente", 10, 1.5),
);

$nombre_impresora = "POS-58";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);
try{
    $logo = EscposImage::load("logo.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){}
$printer->text("Yo voy en el encabezado" . "\n");
$printer->text("Otra linea" . "\n");
$printer->text(date("Y-m-d H:i:s") . "\n");
$total = 0;
foreach ($productos as $producto) {
    $total += $producto->cantidad * $producto->precio;
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text($producto->cantidad . "x" . $producto->nombre . "\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text(' $' . $producto->precio . "\n");
}
$printer->text("--------\n");
$printer->text("TOTAL: $". $total ."\n");
$printer->text("Muchas gracias por su compra\nparzibyte.me");

$printer->feed(3);
$printer->cut();
$printer->pulse();
$printer->close();
 */

?>


