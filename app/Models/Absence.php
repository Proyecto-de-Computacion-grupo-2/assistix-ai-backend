<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Absence extends Model
{
    use HasFactory;

    protected $table = "absence";
    protected $primaryKey = "id_absence";

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id_mundo_deportivo');
    }
}
