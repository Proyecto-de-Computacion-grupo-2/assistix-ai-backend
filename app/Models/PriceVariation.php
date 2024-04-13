<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceVariation extends Model
{
    use HasFactory;
    protected $table = 'price_variation';
    protected $primaryKey = 'id_price_variation';

}
