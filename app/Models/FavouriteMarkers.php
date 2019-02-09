<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FavouriteMarkers extends Model
{
     use SoftDeletes;
    protected $table = "favourite_markers";

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }


}
