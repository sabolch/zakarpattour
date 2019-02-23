<?php

namespace App\Http\Controllers\Settings;

use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'telephone' => 'required|numeric',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        User::where('id', $user->id)->update($request->only('name', 'email', 'telephone'));
        return tap($user)->update($request->only('email'));
    }
}
