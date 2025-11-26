<?php

namespace App\Models;

use App\Models\Update\Device;
use Illuminate\Database\Eloquent\Model;

class Timbangan_riwayat extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function device(){
        return $this->belongsTo(Device::class, 'id_device');
    }

    public function ordersheet()
    {
        return $this->belongsTo(Ordersheet::class, 'id_ordersheet', 'id');
    }
}
