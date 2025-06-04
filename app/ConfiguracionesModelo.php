<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionesModelo extends Model
{
    protected $table = 'Configuraciones';
    protected $primaryKey = 'idConfiguracion';
    protected $fillable = ['Nombre','Data1', 'Data2', 'Data3', 'Data4', 'Data5','Data6', 'Data7', 'Data8', 'Data9', 'Data10','Creacion'];
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
