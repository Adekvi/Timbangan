<?php

namespace App\Models;

use App\Models\Update\Device;
use Illuminate\Database\Eloquent\Model;

class Ordersheet extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function device(){
        return $this->belongsTo(Device::class, 'id_device');
    }

    // Relasi ke view (data asal)
    public function vall()
    {
        return $this->belongsTo(VAllOrdersheetPlusCari::class, 'id_order', 'id');
    }

    // Relasi ke tabel timbangan (riwayat timbang)
    public function timbangans()
    {
        return $this->hasMany(Timbangan_riwayat::class, 'id_ordersheet', 'id');
    }
}
