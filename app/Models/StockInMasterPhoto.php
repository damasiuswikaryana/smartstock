<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInMasterPhoto extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'stock_in_master_doc';

    public function stock_in_master(): BelongsTo
    {
        return $this->belongsTo(StockInMaster::class, 'stock_in_m_id', 'id');
    }
}
