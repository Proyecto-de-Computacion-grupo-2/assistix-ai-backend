<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalRecommendation extends Model
{
    use HasFactory;
    protected $table = 'global_recommendation';
    protected $primaryKey = 'id_global_recommendation';
}
