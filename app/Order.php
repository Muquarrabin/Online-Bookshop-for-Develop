<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }
    public function shipping()
    {
        return $this->belongsTo('App\ShippingAddress', 'shipping_id');
    }
    public function second_hand_acc()
    {
        return $this->hasOne(SecondHandAccount::class,'order_id','id');
    }
}
