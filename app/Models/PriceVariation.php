<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceVariation extends Model
{
    use HasFactory;

    protected $table = 'price_variation';
    protected $primaryKey = 'id_price_variation';

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'id_mundo_deportivo');
    }
}
