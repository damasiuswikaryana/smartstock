<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOutMasterPhoto extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'stock_out_master_doc';

    public function stock_out_master(): BelongsTo
    {
        return $this->belongsTo(StockOutMaster::class, 'stock_out_m_id', 'id');
    }
}
