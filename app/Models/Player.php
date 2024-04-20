<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    protected $table = "player";
    protected $primaryKey = "id_mundo_deportivo";

    public function price_variations(): HasMany
    {
        return $this->hasMany(PriceVariation::class, 'id_mundo_deportivo');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(PredictionPoints::class, 'id_mundo_deportivo');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'id_mundo_deportivo');
    }

    public function global_recommendations(): HasMany
    {
        return $this->hasMany(GlobalRecommendation::class, 'id_mundo_deportivo');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'id_mundo_deportivo');
    }

    public function league_user(): HasOne
    {
        return $this->hasOne(LeagueUser::class, 'id_mundo_deportivo');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(UserRecommendation::class, 'id_mundo_deportivo');
    }

}
