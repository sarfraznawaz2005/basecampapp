<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Data;
use App\Http\Controllers\Controller;
use App\Models\Project;
use function flash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use function title;

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
    protected $redirectTo = '/home';

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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        title('Login');

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            $this->setup();

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function setup()
    {
        // save original user info in session
        if (user()->isAdmin()) {
            session(['ouid' => user()->id]);
        }

        // refresh data on login - order is important
        if (Data::checkConnection(user()->basecamp_api_user_id)) {
            Data::addUserProjects();
            Data::getUserMonthlyHours(true);
            Data::getUserProjectlyHours(true);
        }

        // refresh monthly hours for all users
        if (user()->basecamp_api_user_id === '11816315') {
            $users = [
                11816315 => 'Sarfraz',
                10971177 => 'Abdullah',
                1833053 => 'Faisal',
                11997273 => 'Shireen',
                11618976 => 'Shoaib',
                11685472 => 'Naveed',
                12026288 => 'Osama Alvi',
                12253292 => 'BinZia',
                12221928 => 'Imran',
                12153923 => 'Kafeel',
                12292572 => 'Majid',
            ];

            foreach ($users as $userId => $user) {
                Data::getUserMonthlyHours(true, $userId);
            }
        }
    }

}
