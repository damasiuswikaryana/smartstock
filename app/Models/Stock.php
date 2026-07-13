<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'stocks';

    public function item_varian(): BelongsTo
    {
        return $this->belongsTo(ItemVarian::class, 'item_varian_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'lokasi_id', 'id');
    }
}
