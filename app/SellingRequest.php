<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingRequest extends Model
{
    use SoftDeletes;
    protected $table="book_selling_requests";
    protected $guarded = [];

    //relations
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function image()
    {
        return $this->belongsTo('App\Image');
    }
    public function book()
    {
        return $this->hasOne(Book::class,'selling_request_id','id');
    }
    public function second_hand_acc()
    {
        return $this->hasOne(SecondHandAccount::class,'selling_request_id','id');
    }


    /*
     * Image Accessor
     */
    public function getImageUrlAttribute($value)
    {
        return asset('/').'assets/img/'.$this->image->file;
    }
    public function getDefaultImgAttribute($value)
    {
        return asset('/').'assets/img/'.'user-placeholder.png';
    }
}
