<?php

namespace App\Http\Controllers\Admin\Auth;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\Auth\LoginController as AuthLoginController;
use Backpack\CRUD\app\Library\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LoginController extends AuthLoginController
{
  
        /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // $user=User::where($this->username(),$request->email)->where("user_type",4)->first();//inspector check
        
        // if(!$user) return $this->sendFailedLoginResponse($request);
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if (config('backpack.base.setup_email_verification_routes', false)) {
                return $this->logoutIfEmailNotVerified($request);
            }

            return $this->sendLoginResponse($request);
        }else if($this->attemptLoginUsername($request)){
if (config('backpack.base.setup_email_verification_routes', false)) {
                return $this->logoutIfEmailNotVerified($request);
            }

            return $this->sendLoginResponse($request);
		}

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
	/**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLoginUsername(Request $request)
    {
        return $this->guard()->attempt(
            ['username'=>$request->email,'password'=>$request->password], $request->filled('remember')
        );
    }

}
