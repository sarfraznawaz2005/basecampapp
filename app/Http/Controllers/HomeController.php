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
        title(config('app.name'));

        // total monthly hours so far
        $totalHours = Data::getUserMonthlyHours();

        // projectly hours
        $projects = Data::getUserProjectlyHours();
        $projects = collect($projects)->sortByDesc('hours');

        return view('pages.dashboard.dashboard',
            compact('totalHours', 'projects')
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
