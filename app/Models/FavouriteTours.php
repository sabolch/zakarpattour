<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavouriteTours extends Model
{
    use SoftDeletes;

    protected $table = "favourite_tours";


}
