<?php

namespace App\Http\Controllers;

use App\Facades\Data;
use function redirect;

class HomeController extends Controller
{
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

        return view('pages.dashboard.dashboard',
            compact('totalHours', 'projects')
        );
    }

    public function refresh()
    {
        Data::getUserMonthlyHours(true);

        Data::getUserProjectlyHours(true);

        flash('Data Refreshed Successfully!', 'success');

        return redirect()->back();
    }
}
