<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersheetPackageweight extends Model
{
    protected $guarded = [];

    public function package()
    {
        return $this->belongsTo(OrdersheetPackage::class, 'id_package');
    }
}
