<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        if (Auth::check()){
            $previous_session = Auth::User()->session_id;
            if ($previous_session) {
                Session::getHandler()->destroy($previous_session);
                Auth::logout();
                redirect('/');
            }
        }
    }

    public function login(Request $request){

        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
//            $role1 = Auth::user()->hasRole('Administrator|SysOp|USER_OPERATOR_SIAC|USER_OPERATOR_ADMIN|ENLACE|USER_ARCHIVO_CAP|USER_ARCHIVO_ADMIN');
//            $role2 = Auth::user()->hasRole('CIUDADANO|DELEGADO');
//
//            if ($role1) {
//                return redirect()->route('home');
//            } elseif($role2) {
//                return redirect()->route('home-ciudadano');
//            } else {
                return redirect()->route('home');
//            }
        }else{
            return redirect()->route('login')
                ->with('error','Username, email ó password incorrecto');
        }

    }



}
