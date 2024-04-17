<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory;
    protected $table = "player";
    protected $primaryKey = "id_mundo_deportivo";

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'id_mundo_deportivo');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(PredictionPoints::class, 'id_mundo_deportivo');
    }

    public function price_variations(): HasMany
    {
        return $this->hasMany(PriceVariation::class, 'id_mundo_deportivo');
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'id_mundo_deportivo', 'id_mundo_deportivo');
    }

}
