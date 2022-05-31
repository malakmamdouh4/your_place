<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



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
    }



    public function showLoginForm()
    {
        return view("auth.login");
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('phone',$request->phone)->first();
        $credentials = $request->only('phone', 'password');

        if(!$user)
        {
            return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
        }
        elseif($user && $user->activate == 1 && $user->groupId == 1)
        {
            if (Auth::attempt($credentials) ) {

                return redirect()->route('home');
            }
    
            return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
        }
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
        
    }



    public function username()
    {
        return 'phone' ;
    }



}
