<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRecommendation extends Model
{
    use HasFactory;

    protected $table = 'user_recommendation';
    protected $primaryKey = 'id_user_recommendation';

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id_mundo_deportivo','id_mundo_deportivo');
    }

    public function league_users(): HasOne
    {
        return $this->hasOne(LeagueUser::class, 'id_user');
    }
}
