<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PisosModelo extends Model
{
    protected $table = 'Pisos';
    protected $primaryKey = 'idPiso';
    protected $fillable = ['Nombre', 'Estado', 'idEdificio', 'Creacion', 'Modificacion'];
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
