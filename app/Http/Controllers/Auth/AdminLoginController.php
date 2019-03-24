<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Validators\Validator;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        Config::set('jwt.user', 'App\Models\Admin');
        Config::set('auth.providers.users.model', Admin::class);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
//        $g_token = $request->input('recaptchaToken');

//        // if google captcha is not empty
//        if(strlen($g_token) > 0) {
//            // create https client for check the token
//            $client = new Client();
//            $response = $client->post("https://www.google.com/recaptcha/api/siteverify", [
//                'form_params' => array(
//                    'secret' => '6LfeIXYUAAAAADT7qhrqb5jNZVBh1qTcJ4uau2Oj',
//                    'response' => $g_token
//                )
//            ]);
//            $verification = json_decode($response->getBody()->getContents());
//            if ($verification->success) {


                $token = $this->guard()->attempt($this->credentials($request));

                if ($token) {
                    $this->guard()->setToken($token);
                    return true;
                }
//
//                return false;
//            }
//            return false;
//        }
        return true;

    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $token = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
        ];
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
    }
}
