<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        title('Dashboard');

        // total monthly hours so far
        $totalHours = Data::getUserMonthlyHours();

        // projectly hours
        $projects = Data::getUserProjectlyHours();
        $projects = collect($projects)->sortByDesc('hours');

        $allUsersHours = [];
        if (user()->isAdmin() && session('all_users')) {
            // ideally should be added and stored rather than being hard-coded
            $users = session('all_users');

            foreach ($users as $userId => $user) {
                $nameArray = explode(' ', $user);
                $name = $nameArray[0] . ' ' . $nameArray[1][0];

                $hours = Data::getUserMonthlyHours(false, $userId);
                $allUsersHours[] = [
                    'name' => $name,
                    'hours' => $hours,
                    'color' => substr(md5(rand()), 0, 6),
                ];

                // sort by max hours
                $allUsersHours = collect($allUsersHours)->sortByDesc('hours');
            }
        }

        return view('pages.dashboard.dashboard',
            compact('totalHours', 'projects', 'allUsersHours')
        );
    }

    public function refresh()
    {
        set_time_limit(0);

        // add all projects first
        $projects = getAllProjects();

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

        Data::getUserMonthlyHours(true);

        Data::getUserProjectlyHours(true);

        flash('Data Refreshed Successfully!', 'success');

        return redirect()->back();
    }
}
