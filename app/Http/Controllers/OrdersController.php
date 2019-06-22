<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => array('index', 'status', 'destroy')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $validator = Validator::make($request->all(),
            [
                'status' => 'required',
            ], [
                'status.required' => 'status is required',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $validator->valid();

        return OrderResource::collection(Order::where('status', $data['status'])->items()->paginate($per_page));


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        try {
            $per_page = Input::has('limit') ? Input::get('limit') : 10;
            $validator = Validator::make($request->all(),
                [
                    'status' => 'required',

                ], [
                    'status.required' => 'status is required',

                ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $data = $validator->valid();

            return OrderResource::collection(Order::selectData($data['status'], $request->user()->id, $per_page));
        } catch (\Exception $e) {
            return response()->json(['success' => false], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tours' => 'required',
                'total_price' => 'required',

            ], [
                'total_price.required' => 'total_price is required',
                'tours.required' => 'tours is required',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $validator->valid();
        try {
            $order = new Order();
            $order->status = 'processing';
            $order->user_id = $request->user()->id;
            $order->total_price = $data['total_price'];
            $order->save();


            foreach ($data['tours'] as $item) {
                $order->items()->attach([$item['tour_id'] => [
                    'price' => $item['price'],
                    'persons' => $item['persons'],
                    'date' => $item['date']
                ]]);

            }
        } catch (\Exception $e) {
            return response()->json(['success' => false], 400);
        }
        return response()->json(['success' => true], 201);
    }

    public
    function status(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'status' => 'required',

            ], [
                'id.required' => 'Item ID is required',
                'status.required' => 'status is required',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $validator->valid();

        try {
            $order = Order::findOrFail($data['id'])->update(['status' => $data['status']]);
            return response()->json(
                ['success' => true],
                200
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Data not found!'
                ],
                400
            );
        }
        return response()->json([
            'success' => true,
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(
                ['success' => true],
                200
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Data not found!'
                ],
                400
            );
        }
    }

}
