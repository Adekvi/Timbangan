<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersheetPackage extends Model
{
    protected $guarded = [];

    public function weights()
    {
        return $this->hasMany(OrdersheetPackageWeight::class, 'id_package');
    }
}
