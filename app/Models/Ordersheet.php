<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordersheet extends Model
{
    protected $guarded = [];

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
