<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
     
    public function stockMutationsAsSource()
    {
        return $this->hasMany(StockMutation::class, 'source_id')->where('source_type', 'outlet');
    }
    
    public function stockMutationsAsTarget()
    {
        return $this->hasMany(StockMutation::class, 'target_id')->where('target_type', 'outlet');
    }
}
