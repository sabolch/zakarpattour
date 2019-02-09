<?php

namespace App\Http\Controllers;

use App\Points;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(["main","language"]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function main(){
        return view('welcome');
    }
    public function language(Request $request){
        Session::put('locale',$request->input('language'));
    }
}
