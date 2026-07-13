<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
     use SoftDeletes;
     protected $guarded = ['id'];
     protected $dates = ['deleted_at'];
     protected $table = 'bank_accounts';
     
     public function bank(): BelongsTo
     {
         return $this->belongsTo(Bank::class, 'id_bank', 'id');
     }
}
