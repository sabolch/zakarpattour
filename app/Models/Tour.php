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

    protected $casts = [
        'directions' => 'object',
        'available_dates'=>'array'
    ];

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

    public function markers(){
        return $this->belongsToMany('App\Models\Marker','marker_tours');
    }
    public function markers_paginate()
    {
        return $this->belongsToMany('App\Models\Marker','marker_tours');
    }

    public function favourites(){
        return $this->belongsToMany('App\User','favourite_tours');
    }

    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id', 'id');
    }

    // not clean code version :(
    public static function pagination($active = true,
                                      $search_query = null,
                                      $category,
                                      $sights,
                                      $settlements,
                                      $price,
                                      $duration,
                                      $order_by,
                                      $start_date,
                                      $end_date,
                                      $per_page)
    {

        return Tour::whereBetween('price',$price)
            ->where('active',$active)
            ->whereBetween('duration',$duration)
//            ->whereBetween('start_date', array($start_date, $end_date))
            ->whereBetween('end_date', array($start_date, $end_date))
            // category
            ->when($category, function ($q) use ($category) {
                return $q->whereIn('tour_category_id', $category);})
            // sights
            ->when(($sights || $settlements), function ($q) use ($sights,$settlements) {
                return $q->whereHas('markers', function($q) use ($sights,$settlements) {
                    // sights
                    if($sights) {
                       $q->whereIn('marker_id', $sights);
                   }
                    // settlements
                   if($settlements) {
                       $q->whereIn('settlement_id',$settlements);
                   }
                });
            })
            // search query
            ->when($search_query, function ($q) use ($search_query) {
                return $q->whereHas('translations', function($q) use ($search_query) {
                    $q->where('title','LIKE', '%'.$search_query.'%');
                });
            })
            ->with('markers')
            ->with('category')
            ->orderBy( $order_by,'desc')
            ->paginate($per_page,['id','slug','tour_category_id','start_date','end_date','duration','person','price','views',
                'created_at','updated_at']);
    }

    public static function paginateTrashed($search_query = null, $category,$sights,$price, $order_by, $per_page)
    {
        return Tour::onlyTrashed()
             ->whereBetween('price',$price)
            // category
            ->when($category, function ($q) use ($category) {
                return $q->whereIn('tour_category_id', $category);})
            // sights
            ->when($sights, function ($q) use ($sights) {
                return $q->whereHas('markers', function($q) use ($sights) {
                    $q->whereIn('marker_id',$sights);
                });
            })
            // search query
            ->when($search_query, function ($q) use ($search_query) {
                return $q->whereHas('translations', function($q) use ($search_query) {
                    $q->where('title','LIKE', '%'.$search_query.'%');
                });
            })
            ->with('category')
            ->orderBy( $order_by,'asc')
            ->paginate($per_page,['id','slug','tour_category_id','start_date','end_date','duration','person','price','views',
                'created_at','updated_at']);

    }
}
