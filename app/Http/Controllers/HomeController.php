<?php

namespace App\Http\Controllers;

use anlutro\LaravelSettings\SettingStore;
use App\Facades\Data;
use App\Models\Project;
use function collect;
use function config;
use function explode;
use function redirect;
use function session;
use function set_time_limit;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param SettingStore $settingStore
     * @return \Illuminate\Http\Response
     */
    public function index(SettingStore $settingStore)
    {
        title('Dashboard - ' . date('d F Y')
            . ' (Workday ' . getWorkingDaysCount()
            . ' of '
            . (getWorkingDaysCount(true)) . ')'
        );

        // projectly hours
        $projects = Data::getUserProjectlyHours();
        $projects = collect($projects)->sortByDesc('hours');

        // refresh hours if session lost
        if (! session('all_users_hours')) {
            $this->refreshData();
        }

        $allUsersHours = [];
        if (user()->isAdmin() && session('all_users_hours')) {
            $allUsersHours = session('all_users_hours');
        }

        $monthHours = session('month_hours');

        if (!session('month_hours')) {
            $monthHours = Data::getUserMonthlyHours();
            session(['month_hours' => $monthHours]);
        }

        return view('pages.dashboard.dashboard',
            compact('projects', 'allUsersHours', 'monthHours')
        );
    }

    public function refresh()
    {
        $this->refreshData();

        flash('Data Refreshed Successfully!', 'success');

        return redirect()->back();
    }

    public static function refreshData()
    {
        set_time_limit(0);

        $excludedUserIds = [
            12026432
        ];

        $allUsersHours = [];

        // refresh all users hours
        $users = Data::getAllUsers($excludedUserIds);
        
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

            session(['all_users_hours' => $allUsersHours]);
        }

        // add all projects first
        $projects = getAllProjects();
        //dd($projects);

        foreach ($projects as $projectId => $name) {

            $projectInstance = Project::firstOrNew([
                'user_id' => user()->id,
                'project_id' => $projectId,
            ]);

            $projectInstance->user_id = user()->id;
            $projectInstance->project_id = $projectId;
            $projectInstance->project_name = $name;

            $projectInstance->save();
        }

        $monthHours = Data::getUserMonthlyHours(true);
        session(['month_hours' => $monthHours]);

        Data::getUserProjectlyHours(true);
    }
}
