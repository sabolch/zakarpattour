<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tymon\JWTAuth\Validators\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $g_token = $request->input('recaptchaToken');

        // if google captcha is not empty
        if(strlen($g_token) > 0) {
            // create https client for check the token
            $client = new Client();
            $response = $client->post(config('services.recaptcha.url'), [
                'form_params' => array(
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $g_token
                )
            ]);
            $verification = json_decode($response->getBody()->getContents());
            if ($verification->success) {


                $token = $this->guard()->attempt($this->credentials($request));

                if ($token) {
                    $this->guard()->setToken($token);
                    return true;
                }

                return false;
            }
            return false;
        }
        return false;

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
        $this->guard()->logout();
    }
}
