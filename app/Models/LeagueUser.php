<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
