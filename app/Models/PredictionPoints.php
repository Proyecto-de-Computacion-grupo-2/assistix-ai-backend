<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictionPoints extends Model
{
    use HasFactory;
    protected $table = 'prediction_points';
    protected $primaryKey = 'id_prediction_points';
}
