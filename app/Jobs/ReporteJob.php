<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteExport;
use App\Mail\EnviarReporte;


class ReporteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fecha= now()->format('Y-m-d');
      
       $datos= DB::select("SELECT distinct acc.Creacion,p.Nombres,p.Cedula,o.Nombre,pis.Nombre,acc.CodigoTarjeta FROM accesos acc 
        INNER JOIN personas p ON acc.idPersona=p.idPersona
        INNER JOIN organizaciones o ON acc.idOrganizacion=o.idOrganizacion
        INNER JOIN pisos pis ON o.idPiso=pis.idPiso
        INNER JOIN edificios e ON pis.idEdificio=e.idEdificio
        INNER JOIN visitas v ON e.idEdificio=v.idEdificio
        WHERE cast(acc.Creacion AS DATE)='$fecha'");

        $export = new ReporteExport($datos);
        Excel::store($export, 'ReporteVisitas.xlsx'); 
        $path=storage_path();
        $rutaArchivo= $path.'\app\ReporteVisitas.xlsx';

  
        \Mail::to('centro.financiero21@gmail.com')->send(new EnviarReporte($rutaArchivo));
      

 

    
        return true;

    }
}
