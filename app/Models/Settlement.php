<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Settlement extends Model
{
    use Translatable;
    use Sluggable;
    use SoftDeletes;
    use SluggableScopeHelpers;

    protected $table = 'settlements';

    public $translatedAttributes = ['title', 'description'];
    //

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function pagination($search_query = null, $order_by, $per_page)
    {
        return Settlement::where('id', 'LIKE', '%%')
            ->when($search_query, function ($q) use ($search_query) {
                return $q->whereHas('translations', function ($q) use ($search_query) {
                    $q->where('title', 'LIKE', '%' . $search_query . '%');
                });
            })
            ->orderBy($order_by, 'desc')
            ->paginate($per_page, ['id', 'slug', 'lat', 'lng', 'views',
                'created_at', 'updated_at']);
    }

    public static function paginateTrashed($search_query = null, $order_by, $per_page)
    {
        return Settlement::onlyTrashed()
            ->when($search_query, function ($q) use ($search_query) {
                return $q->whereHas('translations', function ($q) use ($search_query) {
                    $q->where('title', 'LIKE', '%' . $search_query . '%');
                });
            })
            ->orderBy($order_by, 'desc')
            ->paginate($per_page, ['id', 'slug', 'views',
                'created_at', 'updated_at', 'deleted_at']);
    }
}
