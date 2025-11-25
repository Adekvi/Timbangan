<?php

namespace App\Models\Update;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $guarded = [];
    protected $casts = [
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    public function updates()
    {
        return $this->hasMany(Device_update::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function firmware(){
    //     return $this->hasMany(Firmwares::class);
    // }
}
