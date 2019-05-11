<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouritesResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FavouritesController extends Controller
{


    public function __construct(Request $request)
    {

    }

    public function markers(Request $request){
        $pre_page = Input::has('limit') ? Input::get('limit') : 10;
        return FavouritesResource::collection(
            $request->user()->favouriteMarkers()->paginate($pre_page)
        );
//        return response()->json(['success'=>true,'data'=>$request->user()->favouriteMarkers()->paginate($pre_page)],200);
    }

    public function tours(Request $request){
        $pre_page = Input::has('limit') ? Input::get('limit') : 10;
        return FavouritesResource::collection(
            $request->user()->favouriteTours()->paginate($pre_page)
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
        $request->user()->favouriteMarkers()->attach($validator->valid()['id']);
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

        $request->user()->favouriteTours()->attach($validator->valid()['id']);
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
        $request->user()->favouriteMarkers()->detach($validator->valid()['id']);
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
        $request->user()->favouriteTours()->detach($validator->valid()['id']);
        return response()->json(['success'=>true],200);
    }

}
