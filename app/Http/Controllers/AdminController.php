<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
        return view('admin.index');
    }
    public function map()
    {
        return view('admin.map');
    }

    public function getUsers(){
        return datatables(Admin::all(['name','email','created_at','id']))->toJson();
    }

    public function manageusers(){
        $admins = Admin::all();
        $users = User::all();
        return view('admin.manageusers',[
            'admins' => $admins,
            'users' => $users,
        ]);
    }
    public function create(Request $request){
        $created = json_decode($request->input('object'), true);
        switch ($request->input('user')){
            case 'admin':
                $admin = new Admin();
                $admin->name = $created['name'];
                $admin->email = $created['email'];
                $admin->password = Hash::make($created['password']);
                $admin->created_at = date("Y-m-d H:m:s");
                $admin->updated_at = date("Y-m-d H:m:s");
                $admin->save();
                return json_encode($admin);
                break;
            case 'user':
                return  abort(404);
                break;
            default:
                return  abort(404);
                break;
        }
    }

    public function destroy(Request $request){
        switch ($request->input('user')){
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
    public function edit(Request $request){
        $changed = json_decode($request->input('object'), true);
        switch ($request->input('user')){
            case 'admin':
                $admin = Admin::findOrFail($changed['id']);
                $admin->name = $changed['name'];
                $admin->email = $changed['email'];
                $admin->password = strlen($changed['password']) > 0 ? Hash::make($changed['password']) : $admin->password;
                $admin->updated_at = date("Y-m-d H:m:s");
                $admin->save();
                break;
            case 'user':
                $user = User::findOrFail($changed['id']);
                $user->name = $changed['name'];
                $user->email = $changed['email'];
                $user->password = strlen($changed['password']) > 0 ? Hash::make($changed['password']) : $user->password;
                $user->updated_at = date("Y-m-d H:m:s");
                $user->save();
                break;
            default:
                return abort(404);
                break;
        }
    }
}
