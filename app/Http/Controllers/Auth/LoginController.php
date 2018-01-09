<?php

namespace App\Http\Controllers\Auth;

use anlutro\LaravelSettings\SettingStore;
use App\Facades\Data;
use App\Http\Controllers\Controller;
use App\Models\Project;
use function flash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use function session;
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
     * @param SettingStore $settingStore
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request, SettingStore $settingStore)
    {
        set_time_limit(0);

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            $this->setup($settingStore);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function setup($settingStore)
    {
        $allUsersHours = [];

        // set daily hours required
        if (! $settingStore->get('daily_hours')) {
            $settingStore->set('daily_hours', request()->daily_hours);
            $settingStore->save();
        }

        // save original user info in session
        if (user()->isAdmin()) {
            session(['ouid' => user()->id]);
        }

        // refresh monthly hours for all users
        if (user()->isAdmin()) {

            $excludedUserIds = [
                12026432
            ];

            $users = Data::getAllUsers($excludedUserIds);

            session(['all_users' => $users]);

            if (user()->isAdmin() && $users) {
                foreach ($users as $userId => $user) {
                    $nameArray = explode(' ', $user);
                    $name = $nameArray[0] . ' ' . $nameArray[1][0];

                    $hours = Data::getUserMonthlyHours(true, $userId);

                    $allUsersHours[] = [
                        'name' => $name,
                        'hours' => $hours,
                        'color' => substr(md5(rand()), 0, 6),
                    ];

                    // sort by max hours
                    $allUsersHours = collect($allUsersHours)->sortByDesc('hours');
                }
            }
        }

        session(['all_users_hours' => $allUsersHours]);

        // refresh data on login - order is important
        if (Data::checkConnection(user()->basecamp_api_user_id)) {
            Data::addUserProjects();
            $monthHours = Data::getUserMonthlyHours(true);
            Data::getUserProjectlyHours(true);

            session(['month_hours' => $monthHours]);
        }
    }

}
