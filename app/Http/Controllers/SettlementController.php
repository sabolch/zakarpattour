<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettlementResource;
use App\Models\Marker;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Input::has('locale')) {
            Session::put('locale', Input::get('locale'));
        }

        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $order_by = Input::has('order') ? Input::get('order') : 'created_at';

        return SettlementResource::collection(Settlement::pagination($search_query, $order_by, $per_page));
    }

    public function trashed()
    {
        if (Input::has('locale')) {
            Session::put('locale', Input::get('locale'));
        }

        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $order_by = Input::has('order') ? Input::get('order') : 'created_at';

        return SettlementResource::collection(Settlement::paginateTrashed($search_query, $order_by, $per_page));
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),
            [
                'lat'=>'required|numeric',
                'lng'=>'required|numeric',
                'translations' =>'required',

            ],[
                'lat.required'=>'Latitude is required',
                'lng.required'=>'Longitude is required',
                'lat.numeric'=>'Latitude must be numeric',
                'lng.numeric'=>'Longitude must be numeric',
                'translations.required'=>'Translation is required!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();

        //Translate check
        foreach ($data['translations'] as $array) {
            if(empty($array['title']) || empty($array['description'])){
                return response()->json(['errors'=>['translations'=>'Empty data in translations']],400);
            }
        }

        $settlement = new Settlement();
        $settlement->lat = $data['lat'];
        $settlement->lng = $data['lng'];
        $settlement->title = $data['translations'][0]['title'];
        $settlement->description = $data['translations'][0]['description'];
        $settlement->save();

        // Translate
        foreach ($data['translations'] as $array) {
            $settlement->translateOrNew($array['locale'])->title = $array['title'];
            $settlement->translateOrNew($array['locale'])->description = $array['description'];
        }
        $settlement->save();
//
        return response()->json([
            'success'=>true,
            'data'=> $settlement,
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
            $settlement = Settlement::findBySlugOrFail($slug);

            $settlement->increment('views');

            return response()->json([
                'success'=>true,
                'data'=> $settlement
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
                'id'=>'required|numeric',
                'lat'=>'required|numeric',
                'lng'=>'required|numeric',
                'translations' =>'required',

            ],[
                'id.required'=>'Item ID is required',
                'lat.required'=>'Latitude is required',
                'lng.required'=>'Longitude is required',
                'lat.numeric'=>'Latitude must be numeric',
                'lng.numeric'=>'Longitude must be numeric',
                'translations.required'=>'Translation is required!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],400);
        }

        $data = $validator->valid();



        try{
            $settlement = Settlement::findOrFail($data['id']);
            $settlement->lat = $data['lat'];
            $settlement->lng = $data['lng'];
            $settlement->title = $data['translations'][0]['title'];
            $settlement->description = $data['translations'][0]['description'];
            $settlement->save();

            // Translate
            foreach ($data['translations'] as $array) {
                $settlement->translateOrNew($array['locale'])->title = $array['title'];
                $settlement->translateOrNew($array['locale'])->description = $array['description'];
            }
            $settlement->save();

            return response()->json([
                'success'=>true,
                'data'=> $settlement,
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $id = $request->input('id');
            $settlement = Settlement::findOrFail($id);
            $settlement->delete();

             Marker::where('settlement_id',$id)
                 ->update(['settlement_id' => 0]);

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

    public function restore(Request $request)
    {
        try{
            $settlement = Settlement::onlyTrashed()->findOrFail($request->input('id'));
            $settlement->deleted_at = null;
            $settlement->save();

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
