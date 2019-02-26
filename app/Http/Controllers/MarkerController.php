<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;
use App\Http\Resources\MarkerResource;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;


class MarkerController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:admin')->except([]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $category = Input::has('category') ? Input::get('category') : '';
        $order_by = Input::has('order') ? Input::get('order') : ['created_at','desc'];

        return  MarkerResource::collection(Marker::pagination($search_query, $category,$order_by,$per_page));
    }

    public function trashed()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $category = Input::has('category') ? Input::get('category') : '';
        $order_by = Input::has('order') ? Input::get('order') : ['created_at','desc'];

        return  MarkerResource::collection(Marker::paginateTrashed($search_query, $category,$order_by,$per_page));

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),
            [
                'category_id'=>'required|integer',
                'lat'=>'required|numeric',
                'lng'=>'required|numeric',
                'title'    => 'required',
                'translation' =>'required',

            ],[
                'category_id.required'=>'Category ID is required',
                'category_id.integer'=>'Category ID must be integer',
                'lat.required'=>'Latitude is required',
                'lng.required'=>'Longitude is required',
                'lat.numeric'=>'Latitude must be numeric',
                'lng.numeric'=>'Longitude must be numeric',
                'title.required'=>'Title array is required!',
                'translation.required'=>'Translation is required!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();

        $marker = new Marker();
        $marker->marker_category_id = $data['category_id'];
        $marker->lat = $data['lat'];
        $marker->lng = $data['lng'];
        $marker->title = $data['title'];
        $marker->save();
        // Translate
        foreach ($data['translation'] as $array){
            foreach ($array as $locale => $obj){
                $marker->translateOrNew($locale)->title = $obj['title'];
                $marker->translateOrNew($locale)->description = $obj['description'];
            }
        }
        $marker->save();

        return response()->json([
            'success'=>true,
            'data'=> $marker,
        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try{
            $marker = Marker::findBySlugOrFail($slug)->with('category')->first();
            return response()->json([
                'success'=>true,
                'data'=> $marker
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'success'=>false,
                'error'=>'Data not found!'
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator =  Validator::make($request->all(),
            [
                'id'=>'required|integer',
                'category_id'=>'required|integer',
                'lat'=>'required|numeric',
                'lng'=>'required|numeric',
                'title'    => 'required',
                'translation' =>'required',

            ],[
                'id.required'=>'ID is required',
                'id.integer'=>'ID must be integer',
                'category_id.required'=>'Category ID is required',
                'category_id.integer'=>'Category ID must be integer',
                'lat.required'=>'Latitude is required',
                'lng.required'=>'Longitude is required',
                'lat.numeric'=>'Latitude must be numeric',
                'lng.numeric'=>'Longitude must be numeric',
                'title.required'=>'Title array is required!',
                'translation.required'=>'Translation is required!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();

        try{
            $marker = Marker::findOrFail($data['id']);
            $marker->marker_category_id = $data['category_id'];
            $marker->lat = $data['lat'];
            $marker->lng = $data['lng'];
            $marker->title = $data['title'];
            $marker->save();
            // Translate
            foreach ($data['translation'] as $array){
                foreach ($array as $locale => $obj){
                    $marker->translateOrNew($locale)->title = $obj['title'];
                    $marker->translateOrNew($locale)->description = $obj['description'];
                }
            }
            $marker->save();

            return response()->json([
                'success'=>true,
                'data'=> $marker,
            ],202);
        }catch (ModelNotFoundException $e){
            return response()->json(
                [
                    'success'=>false,
                    'error'=>'Data not found!'
                ],
                400
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreTrashed(Request $request)
    {
        try{
            $marker = Marker::onlyTrashed()->findOrFail($request->input('id'));
            $marker->deleted_at = null;
            $marker->save();
            return response()->json([
                'success'=>true,
                'data'=> $marker
            ],200);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'success'=>false,
                'error'=>'Data not found!'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $marker = Marker::findOrFail($request->input('id'));
            $marker->delete();
            return response()->json(
                ['success'=>true],
                200
            );
        }catch (ModelNotFoundException $e){
            return response()->json(
                [
                    'success'=>false,
                    'error'=>'Data not found!'
                ],
                400
            );
        }
    }
    public function destroyForever(Request $request)
    {
        try{
            $marker = Marker::onlyTrashed()->findOrFail($request->input('id'));
            $marker->forcedelete();

            return response()->json(
                ['success'=>true],
                200
            );
        }catch (ModelNotFoundException $e){
            return response()->json(
                [
                    'success'=>false,
                    'error'=>'Data not found!'
                ],
                400
            );
        }
    }
}
