<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    protected $table="shipping_charges";
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
