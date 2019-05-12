<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Http\Resources\TourResource;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
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
        if(Input::has('locale')){
            Session::put('locale',Input::get('locale'));
        }

        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $category = Input::has('category') ? json_decode(Input::get('category')) : '';
        $price = Input::has('price') ? json_decode(Input::get('price')) : [10,15000];
        $sights = Input::has('sights') ? json_decode(Input::get('sights')) : '';
        $order_by = Input::has('order') ? Input::get('order') : 'created_at';

        $start_date = Input::has('start_date') ? Input::get('start_date') : date('Y-m-d');
        $end_date = Input::has('end_date') ? Input::get('end_date') : date('Y-m-d', strtotime('+7 months'));

        return  TourResource::collection(Tour::pagination(true, $search_query,  $category, $sights,$price,
            $order_by, $start_date, $end_date,$per_page));
    }

    public function trashed()
    {
        if(Input::has('locale')){
            Session::put('locale',Input::get('locale'));
        }

        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $category = Input::has('category') ? json_decode(Input::get('category')) : '';
        $price = Input::has('price') ? json_decode(Input::get('price')) : [10,15000];
        $sights = Input::has('sights') ? json_decode(Input::get('sights')) : '';
        $order_by = Input::has('order') ? Input::get('order') : 'created_at';

        return  TourResource::collection(Tour::paginateTrashed( $search_query,  $category, $sights,$price, $order_by, $per_page));

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
                'category'=>'required|integer',
                'translations' =>'required',
                'markers' =>'required',
                'start_date' =>'required|date',
                'end_date' =>'required|date',
                'duration' =>'required|integer',
                'price' =>'required|numeric',

            ],[
                'category.required'=>'Category ID is required',
                'category.integer'=>'Category ID must be integer',
                'start_date.required'=>'Start Date is required',
                'end_date.required'=>'End Date is required',
                'duration.required'=>'Duration is required',
                'duration.integer'=>'Duration must be integer',
                'price.required'=>'Price is required',
                'price.numeric'=>'Price must be numeric',
                'translations.required'=>'Translation is required!',
                'markers.required'=>'Markers is required!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();

        $tour = new Tour();
        $tour->marker_category_id = $data['category'];
        $tour->lat = $data['lat'];
        $tour->lng = $data['lng'];
        $tour->title = $data['translations'][0]['title'];
        $tour->description = $data['translations'][0]['description'];
        $tour->save();

        // Translate
        foreach ($data['translations'] as $array) {
            $tour->translateOrNew($array['locale'])->title = $array['title'];
            $tour->translateOrNew($array['locale'])->description = $array['description'];
        }
        $tour->save();
//
        return response()->json([
            'success'=>true,
            'data'=> $tour,
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
            $tour = Tour::whereSlug($slug)->with('category')->first();
            return response()->json([
                'success'=>true,
                'data'=> $tour
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
                'category'=>'required|integer',
                'translations' =>'required',
                'markers' =>'required',
                'start_date' =>'required|date',
                'end_date' =>'required|date',
                'duration' =>'required|integer',
                'price' =>'required|numeric',

            ],[
                'category.required'=>'Category ID is required',
                'category.integer'=>'Category ID must be integer',
                'start_date.required'=>'Start Date is required',
                'end_date.required'=>'End Date is required',
                'duration.required'=>'Duration is required',
                'duration.integer'=>'Duration must be integer',
                'price.required'=>'Price is required',
                'price.numeric'=>'Price must be numeric',
                'translations.required'=>'Translation is required!',
                'markers.required'=>'Markers is required!'
            ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();

        try{
            $tour = Tour::findOrFail($data['id']);
            $tour->marker_category_id = $data['category'];
            $tour->lat = $data['lat'];
            $tour->lng = $data['lng'];
            $tour->title = $data['title'];
            $tour->save();
            // Translate
            foreach ($data['translations'] as $array) {
                $tour->translateOrNew($array['locale'])->title = $array['title'];
                $tour->translateOrNew($array['locale'])->description = $array['description'];
            }
            $tour->save();

            return response()->json([
                'success'=>true,
                'data'=> $tour,
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
            $tour = Tour::onlyTrashed()->findOrFail($request->input('id'));
            $tour->deleted_at = null;
            $tour->save();
            return response()->json([
                'success'=>true,
                'data'=> $tour
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
            $tour = Tour::findOrFail($request->input('id'));
            $tour->delete();
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
            $tour = Tour::onlyTrashed()->findOrFail($request->input('id'));
            $tour->forcedelete();

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
