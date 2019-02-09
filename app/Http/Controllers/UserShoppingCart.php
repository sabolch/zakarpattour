<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserShoppingCart extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
        $this->user = auth()->user();
    }

    public function index(){
        $pre_page = Input::has('limit') ? Input::get('limit') : 10;

        return \App\Http\Resources\UserShoppingCart::collection(
            $this->user->shoppingcart()->paginate($pre_page)
        );
    }
    public function add(Request $request){
        // add
    }
    public function delete(Request $request){

    }
}
