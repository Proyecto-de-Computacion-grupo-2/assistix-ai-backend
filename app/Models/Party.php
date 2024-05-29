<?php

namespace App\Models;

use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
    use HasFactory;

    protected $table = "party";
    protected $primaryKey = "id";

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'id_mundo_deportivo');
    }
}

/*
 * Si tengo una relación 1->N
 * A -> B
 * En model A defino un HasMany para indicar que se relaciona hacia una N.
 * En model B defino un BelongsTo para indicar que se relaciona desde un 1.
 *
 * En Controller A defino las funciones que saquen los datos de la tabla A y B que pertenecen al ID de A.
 * En Controller B defino las funciones que saquen los datos de la tabla B que corresponden con datos especificos únicamente a la tabla B.
 *
 * Tengo que definir las rutas API.
 * En API defino de controller A con el controlador de A y la función en A, y defino B con el controlador de B y la función en B.
*/
