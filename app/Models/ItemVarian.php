<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemVarian extends Model
{
    use SoftDeletes;
    protected $table    = "item_varian";
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function itemMaster(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id', 'id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_varian_id');
    }
}
