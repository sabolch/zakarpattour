<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouritesResource;
use App\Models\FavouriteMarkers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FavouritesController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(['auth','verified']);
        $this->user = auth()->user();
    }

    public function markers(){
        $pre_page = Input::has('limit') ? Input::get('limit') : 10;
        return FavouritesResource::collection(
            User::with('favouriteMarkers')->paginate($pre_page)
        );
    }
    public function tours(){
        $pre_page = Input::has('limit') ? Input::get('limit') : 10;
        return FavouritesResource::collection(
            User::with('favouriteTours')->paginate($pre_page)
    );
    }

//    Add Marker to favourites
    public function createMarker(Request $request){
        $validator = \Validator::make($request->all(),[
            'id'=>'required|integer'
        ],[
            'required'=>'ID required',
            'integer'=>'ID must be a type of integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $this->user->favouriteMarkers()->sync($validator->valid()['id']);
        return response()->json(['success'=>true],201);
    }
//    Add Tour to favourites
    public function createTour(Request $request){
        $validator = \Validator::make($request->all(),[
            'id'=>'required|integer'
        ],[
            'required'=>'ID required',
            'integer'=>'ID must be a type of integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $this->user->favouriteTours()->sync($validator->valid()['id']);
        return response()->json(['success'=>true],201);
    }
//    Remove marker from favourites
    public function deleteMarker(Request $request){
        $validator = \Validator::make($request->all(),[
            'id'=>'required|integer'
        ],[
            'required'=>'ID required',
            'integer'=>'ID must be a type of integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $this->user->favouriteMarkers()->detach($validator->valid()['id']);
        return response()->json(['success'=>true],200);
    }
//    Remove tour from favourites
    public function deleteTour(Request $request){
        $validator = \Validator::make($request->all(),[
            'id'=>'required|integer'
        ],[
            'required'=>'ID required',
            'integer'=>'ID must be a type of integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $this->user->favouriteTours()->detach($validator->valid()['id']);
        return response()->json(['success'=>true],200);
    }

}
