<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    use HasFactory;
    protected $table = 'user_recommendation';
    protected $primaryKey = 'id_user_recommendation';

}
