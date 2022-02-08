<?php

namespace App\Http\Controllers\Auth;
use Auth;
use App\models\User;
use Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request; 

use stdClass;
use Mail;
 

use App\models\LoginHistory;

class AuthController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */
      
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|confirmed|min:6',
					'captcha'  => 'required|confirmed|same:input_captcha'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);
    }
     
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath() {
 
        // Logic that determines where to send the user 
        if (\Auth::user()->hasRole('ADMIN')) {			
			$loginHistoryObj = new LoginHistory();
			$loginHistoryObj->userid = \Auth::user()->id;
			$loginHistoryObj->save();			
            return '/IBD/uc-patient-list';
        }else if (\Auth::user()->hasRole('DOCTOR')) {			
			$loginHistoryObj = new LoginHistory();
			$loginHistoryObj->userid = \Auth::user()->id;
			$loginHistoryObj->save();			
            return '/IBD/uc-patient-list';
        } 
        return '/login';
    }
    
    public function authenticated($request, $user) {
        if ($user->status != 'A') {
            Auth::logout();
            return redirect('login')->withErrors([
                        $this->loginUsername() => 'Your ' . $this->loginUsername() . ' is not active. Please contact Administrator.'
            ]);
        } else {
            return redirect()->intended($this->redirectPath());
        }
    }
	 
}
