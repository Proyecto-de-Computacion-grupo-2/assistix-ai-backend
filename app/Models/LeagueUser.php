<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class LeagueUser extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'league_user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'id_mundo_deportivo');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(UserRecommendation::class, 'id_user');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
