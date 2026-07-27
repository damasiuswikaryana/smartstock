<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function childs()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id', 'id');
    }
}
