<?php

namespace App\Models;

use App\Models\Update\Device;
use Illuminate\Database\Eloquent\Model;

class OrdersheetPackageweight extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function device(){
        return $this->belongsTo(Device::class, 'id_device');
    }

    public function package()
    {
        return $this->belongsTo(OrdersheetPackage::class, 'id_package');
    }
}
