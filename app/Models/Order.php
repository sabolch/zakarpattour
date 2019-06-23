<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
    use SoftDeletes;
    protected $table = "orders";
    protected $fillable = [
        'status', 'user_id', 'total_price',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\Shopping_Cart', 'shopping_cart', 'order_id','tour_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Shopping_Cart', 'order_id');
    }

    public static function selectData($status, $user_id, $limit)
    {
        return Order::where('status', $status)
            ->where('user_id', $user_id)
            ->with(['orders'=>function ($query){
                $query->with(['tour' => function($q){
                    $q->with(['translations' => function($x){
                        $x->select(['tour_id','locale','title']);
                    }])->select(['id','slug']);
                }])->select(['order_id','persons','price','date','tour_id']);
            }])->paginate($limit);
    }

    public static function paginating($status, $limit)
    {
        return Order::where('status', $status)
            ->with(['orders'=>function ($query){
                $query->with(['tour' => function($q){
                    $q->with(['translations' => function($x){
                        $x->select(['tour_id','locale','title']);
                    }])->select(['id','slug']);
                }])->select(['order_id','persons','price','date','tour_id']);
            }])->paginate($limit);
    }
}
