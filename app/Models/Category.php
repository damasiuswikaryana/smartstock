<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Fractal\Resource\Item;

class Category extends Model
{
     use SoftDeletes;
     protected $guarded = ['id'];
     protected $dates = ['deleted_at'];

     public function items(): HasMany
     {
          return $this->hasMany(ItemMaster::class, 'category_id', 'id');
     }
}
