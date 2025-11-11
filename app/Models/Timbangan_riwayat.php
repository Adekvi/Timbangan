<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timbangan_riwayat extends Model
{
    protected $guarded = [];

    public function ordersheet()
    {
        return $this->belongsTo(Ordersheet::class, 'id_ordersheet', 'id');
    }
}
