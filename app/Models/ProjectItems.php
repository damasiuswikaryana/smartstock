<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectItems extends Model
{
    protected $guarded  = ['id'];
    protected $table    = 'pekerjaan_items';

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'pekerjaan_id', 'id');
    }

    public function itemMaster(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id', 'id');
    }
}
