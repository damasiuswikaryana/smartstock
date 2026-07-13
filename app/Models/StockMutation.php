<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'stock_mutations';

    public function item_varian(): BelongsTo
    {
        return $this->belongsTo(ItemVarian::class, 'item_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }
}
