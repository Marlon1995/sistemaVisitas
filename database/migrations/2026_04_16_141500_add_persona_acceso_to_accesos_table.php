<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonaAccesoToAccesosTable extends Migration
{
    public function up()
    {
        Schema::table('Accesos', function (Blueprint $table) {
            $table->string('PersonaPermitioAcceso')->nullable()->after('CodigoTarjeta');
        });
    }

    public function down()
    {
        Schema::table('Accesos', function (Blueprint $table) {
            $table->dropColumn('PersonaPermitioAcceso');
        });
    }
}
