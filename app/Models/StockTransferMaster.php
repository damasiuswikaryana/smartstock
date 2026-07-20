<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransferMaster extends Model
{
    use SoftDeletes;
    protected $guarded  = ['id'];
    protected $dates    = ['deleted_at'];
    protected $table    = 'stock_transfer_master';

    public function child(): HasMany
    {
        return $this->hasMany(StockTransferChild::class, 'transfer_master_id', 'id');
    }

    public function documentation(): HasMany
    {
        return $this->hasMany(StockTransferMasterPhoto::class, 'stock_transfer_m_id', 'id');
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'pekerjaan_id', 'id');
    }

    public function gudangAsal(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'werehouse_source_id', 'id');
    }

    public function gudangTarget(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'werehouse_target_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
