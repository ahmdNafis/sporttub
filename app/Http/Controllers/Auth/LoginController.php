<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Facades\Support;
use Illuminate\Support\Str;
use Socialite;

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
    //protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo() {
        
        $role = Auth::user()->roles()->pluck('role_name')->toArray();
         
        if(in_array('Super Admin', $role))  return '/dashboard';
        else return route('profile_show', ['user_id' => Auth::id()]);
    }

    protected function authLogin() {
        $role = Auth::user()->roles()->pluck('role_name')->toArray();
         
        if(in_array('Super Admin', $role)) return redirect('/dashboard');
        else return redirect('/profile/'.Auth::id());
    }

    public function redirectToFacebookProvider() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderFacebookCallback(Request $request) {
       // $request->session()->put('state', Str::random(40));
        $social_account = Socialite::driver('facebook')->stateless()->user();
        $user = User::where('email', $social_account['email'])->first();
        $filtered = [];
        if(!$user) {
            foreach($social_account['user'] as $key => $val) {
                switch ($key) {
                    case 'id':
                        $filtered['social_id'] = $val;
                        break;
                    case 'name':
                        $name = explode(' ', $val);
                        $filtered['first_name'] = $name[0];
                        $filtered['last_name'] = implode(' ', array_slice($name, 1));
                        break;
                    case 'email':
                        $filtered[$key] = $val;
                        break;
                    case 'avatar_original':
                        $filtered['avatar_original'] = $val;
                        break;
                    case 'avatar':
                        $filtered['avatar'] = $val;
                        break;
                }
            }
            $new_user = User::create($filtered);
            if($new_user->roles()->attach(Role::find(2)) == null) {
                Auth::login($new_user);
                return $this->authLogin();
            }
        } else {
            Auth::login($user);
            return $this->authLogin();
        }
    }

    public function redirectToGoogleProvider() {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderGoogleCallback(Request $request) {
        $social_account = Socialite::driver('google')->stateless()->user();
        $user = User::where([
            ['email', $social_account->email],
            ['user_status', 1],
        ])->first();
        $filtered = [];
        if(!$user) {
            foreach($social_account as $key => $val) {
                switch ($key) {
                    case 'id':
                        $filtered['social_id'] = $val;
                        break;
                    case 'name':
                        $name = explode(' ', $val);
                        $filtered['first_name'] = $name[0];
                        $filtered['last_name'] = implode(' ', array_slice($name, 1));
                        break;
                    case 'email':
                        $filtered[$key] = $val;
                        break;
                    case 'avatar':
                        $filtered['avatar'] = $val;
                        break;
                    case 'avatar_original':
                        $filtered['avatar_original'] = $val;
                        break;
                }
            }
            $filtered['user_status'] = true;
            $new_user = User::create($filtered);
            if($new_user->roles()->attach(Role::find(2)) == null) {
                Auth::login($new_user);
                return $this->authLogin();
            }
        } else {
            Auth::login($user);
            return $this->authLogin();
        }
    }

    public function redirectToTwitterProvider() {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleProviderTwitterCallback() {
        $social_account = Socialite::driver('twitter')->user();
        $name = explode(' ', $social_account->name);
        $user = User::where([
            ['social_id', $social_account->id],
            ['user_status', 1],
            ])->orWhere([
            ['first_name', $name[0]],
            ['last_name', $name[1]],
            ['user_status', 1],
            ])->orWhere([
            ['nickname', $social_account->nickname],
            ['user_status', 1],
        ])->first();
        $filtered = [];
        if(!$user) {
            foreach($social_account as $key => $val) {
                switch ($key) {
                    case 'id':
                        $filtered['social_id'] = $val;
                        break;
                    case 'name': 
                        $filtered['first_name'] = $name[0];
                        $filtered['last_name'] = implode(' ', array_slice($name, 1));
                        break;
                    case 'email':
                        $filtered[$key] = $val ? $val : null;
                        break;
                    case 'avatar_original':
                        $filtered['avatar_original'] = $val;
                        break;
                    case 'avatar':
                        $filtered['avatar'] = $val;
                        break;
                }
            }
            $filtered['user_status'] = true;
            $new_user = User::create($filtered);
            if($new_user->roles()->attach(Role::find(2)) == null) {
                Auth::login($new_user);
                return $this->authLogin();
            }
        } else {
            Auth::login($user);
            return $this->authLogin();
        }
        
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }
}
