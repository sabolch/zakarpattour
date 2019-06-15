<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouritesResource;
use App\Models\FavouriteMarkers;
use App\Models\FavouriteTours;
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

    }

    public function getFavorites(Request $request){
        $markers = FavouriteMarkers::where('user_id',$request->user()->id)->get(['marker_id']);
        $tours = FavouriteTours::where('user_id',$request->user()->id)->get(['tour_id']);

        return response()->json(['success'=>true,'data'=>['markers'=>$markers,'tours'=>$tours]],200);
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
        $request->user()->favouriteMarkers()->syncWithoutDetaching($validator->valid()['id']);

        $markers = FavouriteMarkers::where('user_id',$request->user()->id)->get(['marker_id']);

        return response()->json(['success'=>true,'data'=>$markers],201);
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
        $request->user()->favouriteTours()->syncWithoutDetaching($validator->valid()['id']);

        $tours = FavouriteTours::where('user_id',$request->user()->id)->get(['tour_id']);

        return response()->json(['success'=>true,'data'=>$tours],201);
    }
//    Remove marker from favourites
    public function deleteMarker(Request $request){
        $validator = \Validator::make($request->all(),[
            'item_id'=>'required|integer'
        ],[
            'required'=>'ID required',
            'integer'=>'ID must be a type of integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }
        $request->user()->favouriteMarkers()->detach($validator->valid()['item_id']);

        $markers = FavouriteMarkers::where('user_id',$request->user()->id)->get(['marker_id']);

        return response()->json(['success'=>true,'data'=>$markers],200);
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

        $tours = FavouriteTours::where('user_id',$request->user()->id)->get(['tour_id']);
        return response()->json(['success'=>true,'data'=>$tours],200);
    }

}
