<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GlobalRecommendation extends Model
{
    use HasFactory;

    protected $table = 'global_recommendation';
    protected $primaryKey = 'id_global_recommendation';

    public function player(): BelongsTo
    {
        return $this->BelongsTo(Player::class, 'id_mundo_deportivo','id_mundo_deportivo');
    }
}
