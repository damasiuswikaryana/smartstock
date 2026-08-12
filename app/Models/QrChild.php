<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrChild extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'master_qr_child';

    public function master(): BelongsTo
    {
        return $this->belongsTo(Po::class, 'qr_id', 'id');
    }

    public function varian(): BelongsTo
    {
        return $this->belongsTo(ItemVarian::class, 'item_varian_id', 'id');
    }
}
