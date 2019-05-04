<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin'])->except('store');
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $order_by = Input::has('order') ? Input::get('order') : ['created_at','desc'];

        return  ContactUsResource::collection(ContactUs::pagination($search_query, $order_by,$per_page));
    }

    public function archive()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('limit') ? Input::get('limit') : 10;
        $order_by = Input::has('order') ? Input::get('order') : ['created_at','desc'];

        return  ContactUsResource::collection(ContactUs::archivePagination($search_query, $order_by,$per_page));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required',
                'recaptchaToken' => 'required',

            ], [
                'name.required' => 'Name is required',
                'email.required' => 'Latitude is required',
                'email.email' => 'Must be a valid email!',
                'subject.required' => 'Subject is required',
                'message.required' => 'Message is required',
                'recaptchaToken.required' => 'RecaptchaToken is required!',
            ]);

        $g_token = $request->input('recaptchaToken');
        // create https client for check the token
        $client = new Client();
        $response = $client->post(config('services.recaptcha.url'), [
            'form_params' => array(
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $g_token
            )
        ]);
        $verification = json_decode($response->getBody()->getContents());
        if (!$verification->success) {
            return response()->json(['errors' => ['recaptchaToken' => 'recaptchaToken is not valid!']], 400);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $validator->valid();

        $message = new ContactUs();

        $message->name = $data['name'];
        $message->email = $data['email'];
        $message->subject = $data['subject'];
        $message->message = $data['message'];

        $message->save();

        return response()->json([
            'success' => true,
//            'data'=> $message,
        ], 201);
    }


    /**
     * check the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|integer',

            ], [
                'id.required' => 'ID is required',
                'id.integer' => 'ID must be integer',
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $validator->valid();
        try {
            $message = ContactUs::findOrFail($data['id']);
            $message->checked = true;
            $message->save();

            return response()->json([
                'success' => true,
                'data' => $message,
            ], 202);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try{
            $message = ContactUs::findOrFail($request->input('id'));
            $message->delete();
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

    /**
     * Remove forever the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
