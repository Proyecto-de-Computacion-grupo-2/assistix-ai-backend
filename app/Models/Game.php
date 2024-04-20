<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    protected $table = 'game';
    protected $primaryKey = 'id_game';

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id_mundo_deportivo');
    }
}
