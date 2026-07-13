<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInChild extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $table = 'stock_in_child';

    public function varian(): BelongsTo
    {
        return $this->belongsTo(ItemVarian::class, 'item_varian_id', 'id');
    }
}
