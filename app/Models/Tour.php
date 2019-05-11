<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Tour extends Model
{
    use \Dimsav\Translatable\Translatable;
    use Sluggable;
    use SoftDeletes;
    use SluggableScopeHelpers;

    protected $table = 'tours';

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

    public function favourites(){
        return $this->belongsToMany('App\User','favourite_tours');
    }

    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id', 'id');
    }

    public static function pagination($active = true, $search_query = null, $category, $order_by, $per_page)
    {
        if($search_query){
            return Tour::when($category, function ($q) use ($category) {
                return $q->whereIn('tour_category_id', $category);})
                ->select(['tours.*','t.title'])
                ->join('tour_translations as t', 'tours.id', '=', 't.tours_id')
                ->groupBy('tours.id')
                ->where('title','LIKE', '%'.$search_query.'%')
                ->with('category')
                ->orderBy( $order_by[0], $order_by[1])
                ->paginate($per_page);
        }
        return Tour::when($category, function ($q) use ($category) {
            return $q->whereIn('tour_category_id', $category);})
            ->select(['tours.*','t.title'])
            ->join('tour_translations as t', 'tours.id', '=', 't.tours_id')
            ->groupBy('tours.id')
            ->with('category')
            ->orderBy( $order_by[0], $order_by[1])
            ->paginate($per_page);
    }

    public static function paginateTrashed($search_query = null, $category, $order_by, $per_page)
    {
        if($search_query){
            return Tour::onlyTrashed()
                ->select(['tours.*','t.title'])
                ->join('tour_translations as t', 'tours.id', '=', 't.tours_id')
                ->groupBy('tours.id')
                ->whereIn('tour_category_id',$category)
                ->where('title','LIKE', '%'.$search_query.'%')
                ->with('category')
                ->orderBy( $order_by[0], $order_by[1])
                ->paginate($per_page);
        }
        return Tour::onlyTrashed()
            ->when($category, function ($q) use ($category) {
                return $q->whereIn('tour_category_id', $category);})
            ->select(['tours.*','t.title'])
            ->join('tour_translations as t', 'tours.id', '=', 't.tours_id')
            ->groupBy('tours.id')
            ->with('category')
            ->orderBy( $order_by[0], $order_by[1])
            ->paginate($per_page);
    }
}
