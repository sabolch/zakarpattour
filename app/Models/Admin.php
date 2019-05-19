<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $guard = 'admin';
    protected $table = 'admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $dates = ['deleted_at'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function pagination($search_query, $order_by, $per_page)
    {
        return Admin::withTrashed()
            ->when($search_query, function ($q) use ($search_query) {
                $q ->where('name', 'LIKE', '%' . $search_query . '%');
            })
            ->orderBy('created_at', $order_by)
            ->paginate($per_page,['id', 'name', 'email', 'telephone', 'created_at', 'updated_at','deleted_at']);
    }
}
