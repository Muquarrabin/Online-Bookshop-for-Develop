<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondHandAccount extends Model
{
    use SoftDeletes;
    protected $table="second_hand_acc";
    protected $guarded = [];

    //relations

    public function selling_request()
    {
        return $this->belongsTo(SellingRequest::class,'selling_request_id','id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
