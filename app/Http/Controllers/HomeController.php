<?php

namespace App\Http\Controllers;

use App\Facades\Data;
use App\Models\Project;
use function collect;
use function config;
use function redirect;
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
        if (user()->isAdmin() || user()->basecamp_api_user_id === '11816315') {
            // ideally should be added and stored rather than being hard-coded
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
                $hours = Data::getUserMonthlyHours(false, $userId);
                $allUsersHours[] = [
                    'name' => $user,
                    'hours' => $hours,
                    'color' => substr(md5(rand()), 0, 6),
                ];
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
