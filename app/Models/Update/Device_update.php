<?php

namespace App\Models\Update;

use Illuminate\Database\Eloquent\Model;

class Device_update extends Model
{
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function firmware()
    {
        return $this->belongsTo(Firmwares::class);
    }
}
