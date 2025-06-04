<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccesosModelo extends Model
{
    protected $table = 'Accesos';
    protected $primaryKey = 'idAcceso';
    protected $fillable = ['idPersona','idUusario', 'idOrganizacion' ,'CodigoTarjeta', 'idVisita', 'Creacion', 'Modificacion'];
    public $timestamps = false;

    public static function getExcerpt($str, $startPos = 0, $maxLength = 50)
    {
        if (strlen($str) > $maxLength) {
            $excerpt = substr($str, $startPos, $maxLength - 6);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt = substr($excerpt, 0, $lastSpace);
            $excerpt .= ' [...]';
        } else {
            $excerpt = $str;
        }

        return $excerpt;
    }
}
