<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shopping_Cart extends Model
{
    protected $table = "shopping_cart";
    protected $fillable = [
        'tour_id', 'persons', 'date','price',
    ];


    public function tour()
    {
        return $this->belongsTo('App\Models\Tour','tour_id');
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'shopping_cart');
    }
    public function order()
    {
        return $this->belongsToMany('App\Models\Order', 'shopping_cart');
    }
}
