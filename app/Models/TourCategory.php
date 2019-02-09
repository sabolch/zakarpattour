<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourCategory extends Model
{
    use SoftDeletes;
    use \Dimsav\Translatable\Translatable;

    protected $table = 'tour_categories';
    public $translatedAttributes = ['name'];

    public function tours()
    {
        return $this->hasMany(Marker::class);
    }

    public static function pagination($search_query, $order_by, $per_page)
    {
        if ($search_query) {
            return TourCategory::select(['tour_categories.*', 't.name'])
                ->join('tour_category_translations as t', 'tour_categories.id', '=', 't.tour_category_id')
                ->groupBy('tour_categories.id')
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy('t.name', $order_by)
                ->paginate($per_page);
        }
        return TourCategory::select(['tour_categories.*', 't.name'])
            ->join('tour_category_translations as t', 'tour_categories.id', '=', 't.tour_category_id')
            ->groupBy('tour_categories.id')
            ->orderBy('t.name', $order_by)
            ->paginate($per_page);
    }

    public static function paginateTrashed($search_query, $order_by, $per_page)
    {
        if ($search_query) {
            return TourCategory::onlyTrashed()
                ->select(['tour_categories.*', 't.name'])
                ->join('tour_category_translations as t', 'tour_categories.id', '=', 't.tour_category_id')
                ->groupBy('tour_categories.id')
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy('t.name', $order_by)
                ->paginate($per_page);
        }
        return TourCategory::onlyTrashed()
            ->select(['tour_categories.*', 't.name'])
            ->join('tour_category_translations as t', 'tour_categories.id', '=', 't.tour_category_id')
            ->groupBy('tour_categories.id')
            ->orderBy('t.name', $order_by)
            ->paginate($per_page);
    }
}
