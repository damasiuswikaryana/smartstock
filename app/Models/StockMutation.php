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

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'pekerjaan_id', 'id');
    }

    public function gudangAsal(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'source_id', 'id');
    }

    public function gudangTarget(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'target_id', 'id');
    }
}
