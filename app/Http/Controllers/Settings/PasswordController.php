<?php

namespace App\Http\Controllers\Settings;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);
    }
    public function destroy(Request $request){
        User::destroy($request->user()->id);
        return response()->json(['success'=>true],200);
    }
}
