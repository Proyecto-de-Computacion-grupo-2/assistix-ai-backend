<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeagueUser extends Model
{
    use HasFactory;
    protected $table = 'league_user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    public function players()
    {
        return $this->hasMany(Player::class, 'id_mundo_deportivo');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(UserRecommendation::class, 'id_mundo_deportivo');
    }
}
