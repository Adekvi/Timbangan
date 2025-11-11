<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VAllOrdersheetPlusCari extends Model
{
    protected $guarded = [];

    public function order(){
        return $this->hasMany(Ordersheet::class, 'id_order', 'id');
    }
}
