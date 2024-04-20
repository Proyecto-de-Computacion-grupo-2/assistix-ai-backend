<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PriceVariation extends Model
{
    use HasFactory;

    protected $table = 'price_variation';
    protected $primaryKey = 'id_price_variation';

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id_mundo_deportivo');
    }
}
