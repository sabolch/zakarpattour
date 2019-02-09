<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarkerCategory extends Model
{
    use SoftDeletes;
    use \Dimsav\Translatable\Translatable;

    protected $table = 'marker_categories';
    public $translatedAttributes = ['name'];

    public function markers()
    {
        return $this->hasMany(Marker::class);
    }

    public static function pagination($search_query, $order_by, $per_page)
    {
        if ($search_query) {
            return MarkerCategory::select(['marker_categories.*', 't.name'])
                ->join('marker_category_translations as t', 'marker_categories.id', '=', 't.marker_category_id')
                ->groupBy('marker_categories.id')
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy('t.name', $order_by)
                ->paginate($per_page);
        }
        return MarkerCategory::select(['marker_categories.*', 't.name'])
            ->join('marker_category_translations as t', 'marker_categories.id', '=', 't.marker_category_id')
            ->groupBy('marker_categories.id')
            ->orderBy('t.name', $order_by)
            ->paginate($per_page);
    }

    public static function paginateTrashed($search_query, $order_by, $per_page)
    {
        if ($search_query) {
            return MarkerCategory::onlyTrashed()
                ->select(['marker_categories.*', 't.name'])
                ->join('marker_category_translations as t', 'marker_categories.id', '=', 't.marker_category_id')
                ->groupBy('marker_categories.id')
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy('t.name', $order_by)
                ->paginate($per_page);
        }
        return MarkerCategory::onlyTrashed()
            ->select(['marker_categories.*', 't.name'])
            ->join('marker_category_translations as t', 'marker_categories.id', '=', 't.marker_category_id')
            ->groupBy('marker_categories.id')
            ->orderBy('t.name', $order_by)
            ->paginate($per_page);
    }

}
