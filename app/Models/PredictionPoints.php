<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PredictionPoints extends Model
{
    use HasFactory;
    protected $table = 'prediction_points';
    protected $primaryKey = 'id_prediction_points';

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id_mundo_deportivo');
    }
}
