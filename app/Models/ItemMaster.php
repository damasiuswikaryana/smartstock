<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemMaster extends Model
{
    use SoftDeletes;
    protected $table    = "item_master";
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function varian(): HasMany
    {
        return $this->hasMany(ItemVarian::class, 'item_master_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
