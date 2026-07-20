<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransferMasterPhoto extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'stock_transfer_master_doc';

    public function stock_transfer_master(): BelongsTo
    {
        return $this->belongsTo(StockTransferMaster::class, 'stock_transfer_m_id', 'id');
    }
}
