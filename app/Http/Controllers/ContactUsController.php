<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Snowfire\Beautymail\Beautymail;

class ContactUsController extends Controller
{
    public function __construct(Request $request)
    {
//        $this->middleware(['auth:admin'])->except('store','index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('per_page') ? Input::get('per_page') : 10;
        $order_by = Input::has('order') ? Input::get('order') : ['created_at','desc'];

        return  ContactUsResource::collection(ContactUs::pagination($search_query, $order_by,$per_page));
    }

    public function archive()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $per_page = Input::has('per_page') ? Input::get('per_page') : 10;
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
//         create https client for check the token
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
            'data'=> config('services.recaptcha.secret_key'),
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

    public function reply(Request $request){
//        try{
            $validator = Validator::make($request->all(),
                [
                    'to' => 'required|email',
                    'from' => 'required|email',
                    'toName' => 'required',
                    'subject' => 'required',
                    'question' => 'required',
                    'message' => 'required',

                ], [
                    'to.required' => 'To is required',
                    'to.email' => 'To must be a valid email',
                    'from.required' => 'From is required',
                    'from.email' => 'From must be a valid email',
                    'toName.required' => 'toName is required',
                    'subject.required' => 'Subject is required',
                    'question.required' => 'Question is required',
                    'message.required' => 'Message is required',
                ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $data = $validator->valid();

            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('emails.reply', ['data'=>$data['message'],'name'=>$data['toName'],'question'=>$data['question']], function ($message) use ($data) {
                $message
                    ->from($data['from'])
                    ->to($data['to'], $data['toName'])
                    ->subject( config('app.name') . ' - ' .  $data['subject'] );
            });
//
//            Mail::send('emails.reply', ['data'=>$data['message'],'name'=>$data['toName']], function ($message) use ($data) {
//                $message->from($data['from'], config('app.name'));
//                $message->to($data['to'])->subject($data['subject']);
//            });

            return response()->json(['success' => true], 200);

//        }catch (\Exception $e){
//            return response()->json(['success' => false], 400);
//        }

    }
}
