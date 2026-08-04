<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Po extends Model
{
    use SoftDeletes;
    protected $guarded  = ['id'];
    protected $dates    = ['deleted_at'];
    protected $table    = 'master_po';

    public function child(): HasMany
    {
        return $this->hasMany(PoChild::class, 'po_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }

    public function directorBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id', 'id');
    }
}
