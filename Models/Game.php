<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Game extends Model
{
    use HasFactory;

    protected $table = 'game';
    protected $primaryKey = 'id_game';

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'id_mundo_deportivo','id_mundo_deportivo');
    }
}
