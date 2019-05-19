<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\FavouriteMarkers;
use App\Models\FavouriteTours;
use App\Models\Marker;
use App\Models\Settlement;
use App\Models\Tour;
use App\User;
use App\Models\Admin;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users_count = User::count();
        $favorites_count = FavouriteMarkers::count() + FavouriteTours::count();
        $settlements = Settlement::count();
        $sights = Marker::count() ;
        $tours = Tour::count() ;


        return response()->json([
            'success' => true,
            'data' => [
                'users' =>$users_count,
                'fovorites' =>$favorites_count,
                'settlements' =>$settlements,
                'sights' =>$sights,
                'tours' =>$tours,
            ],
        ], 200);
    }
//
//    public function map()
//    {
//        return view('admin.map');
//    }

    public function getUsers()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $order_by = Input::has('order') ? 'desc' : 'asc';
        $per_page = Input::has('per_page') ? (int)Input::get('per_page') : 5;

        return UsersResource::collection(User::pagination($search_query, $order_by, $per_page));
    }

    public function getAdmins()
    {
        $search_query = Input::has('q') ? Input::get('q') : false;
        $order_by = Input::has('order') ? 'desc' : 'asc';
        $per_page = Input::has('per_page') ? (int)Input::get('per_page') : 5;

        return UsersResource::collection(Admin::pagination($search_query, $order_by, $per_page));
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:50',
                'email' => 'required|email',
                'telephone' => 'required',
                'password' => 'required|min:6'
            ], [
                'name.required' => 'name is required!',
                'email.required' => 'email is required!',
                'email.email' => 'email must be valid email!',
                'telephone.required' => 'telephone is required!',
                'password.required' => 'password is required!',
                'password.min' => 'password min 6 characters!',
                'name.max' => 'name max size 50 character!'
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $validator->valid();

        $admin = new Admin();
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->telephone = $data['telephone'];
        $admin->password = Hash::make($data['password']);
        $admin->created_at = date("Y-m-d H:m:s");
        $admin->updated_at = date("Y-m-d H:m:s");
        $admin->save();

        return response()->json([
            'success' => true,
            'data' => $admin,
        ], 201);
    }

    public function destroy(Request $request)
    {
        switch ($request->input('user')) {
            case 'admin':
                return Admin::destroy($request->input('id'));
                break;
            case 'user':
                return User::destroy($request->input('id'));
                break;
            default:
                return abort(404);
                break;
        }
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'name' => 'required|max:50',
                'email' => 'required|email',
                'telephone' => 'required',
            ], [
                'id.required' => 'ID is required!',
                'name.required' => 'name is required!',
                'email.required' => 'email is required!',
                'email.email' => 'email must be valid email!',
                'telephone.required' => 'telephone is required!',
                'name.max' => 'name max size 50 character!',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $validator->valid();

        try {
            $admin = Admin::withTrashed()->findOrFail((int)$data['id']);
            $admin->name = $data['name'];
            $admin->email = $data['email'];
            $admin->telephone = $data['telephone'];
            if (strlen(trim($data['password'])) > 0) {
                $admin->password = Hash::make($data['password']);
            }
            $admin->updated_at = date("Y-m-d H:m:s");
            $admin->save();

            return response()->json([
                'success' => true,
                'data' => $admin,
            ], 202);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => $e->getMessage()
                ],
                200
            );
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'type' => 'required',

            ], [
                'id.required' => 'ID is required!',
                'type.required' => 'type is required!',

            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $validator->valid();
        try {
            switch ($data['type']) {
                case 'admin':
                    $user = Admin::findOrFail($data['id']);
                    break;
                case 'user':
                    $user = User::findOrFail($data['id']);
                    break;
                default:
                    return response()->json(
                        [
                            'success' => false,
                            'error' => 'Incorrect user type!'
                        ],
                        400
                    );
                    break;
            }
            $user->delete();
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


    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'type' => 'required',

            ], [
                'id.required' => 'ID is required!',
                'type.required' => 'type is required!',

            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $validator->valid();
        try {
            switch ($data['type']) {
                case 'admin':
                    $user = Admin::withTrashed()->findOrFail((int)$data['id']);
                    break;
                case 'user':
                    $user = User::findOrFail((int)$data['id']);
                    break;
                default:
                    return response()->json(
                        [
                            'success' => false,
                            'error' => 'Incorrect user type!'
                        ],
                        400
                    );
                    break;
            }
            $user->deleted_at = null;
            $user->save();
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
