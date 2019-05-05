<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use SoftDeletes;
    protected $table = "contact_us";

    public static function pagination($search_query = null, $order_by, $per_page)
    {
        if ($search_query) {
            return ContactUs::where('checked', false)
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy($order_by[0], $order_by[1])
                ->paginate($per_page);
        }
        return ContactUs::where('checked', false)
            ->orderBy($order_by[0], $order_by[1])
            ->paginate($per_page);
    }

    public static function archivePagination($search_query = null, $order_by, $per_page)
    {
        if ($search_query) {
            return ContactUs::where('checked', true)
                ->where('name', 'LIKE', '%' . $search_query . '%')
                ->orderBy($order_by[0], $order_by[1])
                ->paginate($per_page);
        }
        return ContactUs::where('checked', true)
            ->orderBy($order_by[0], $order_by[1])
            ->paginate($per_page);
    }

}
