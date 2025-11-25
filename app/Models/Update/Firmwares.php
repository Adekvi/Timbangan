<?php

namespace App\Models\Update;

use Illuminate\Database\Eloquent\Model;

class Firmwares extends Model
{
    protected $guarded = [];

    public function updates()
    {
        return $this->hasMany(Device_update::class);
    }

    // public function device(){
    //     return $this->belongsTo(Device::class);
    // }
}
