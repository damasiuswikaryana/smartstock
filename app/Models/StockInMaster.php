<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $table = 'stock_in_master';

    public function child(): HasMany
    {
        return $this->hasMany(StockInChild::class, 'in_master_id', 'id');
    }

    public function documentation(): HasMany
    {
        return $this->hasMany(StockInMasterPhoto::class, 'stock_in_m_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'pekerjaan_id', 'id');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'werehouse_id', 'id');
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
