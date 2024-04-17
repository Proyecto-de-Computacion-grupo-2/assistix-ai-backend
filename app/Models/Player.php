<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = "player";
    protected $primaryKey = "id_mundo_deportivo";

    public function games()
    {
        return $this->hasMany(Game::class, 'id_mundo_deportivo', 'id_mundo_deportivo');
    }
}
