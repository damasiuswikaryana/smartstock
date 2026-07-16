<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use SoftDeletes;
    protected $guarded  = ['id'];
    protected $dates    = ['deleted_at'];
    protected $table    = 'pekerjaan';

    public function items(): HasMany
    {
        return $this->hasMany(ProjectItems::class, 'pekerjaan_id', 'id');
    }

    public function entitas(): BelongsTo
    {
        return $this->belongsTo(Entitas::class, 'entitas_id', 'id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'werehouse_id', 'id');
    }
}
