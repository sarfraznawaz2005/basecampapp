<?php

namespace App\Http\Controllers;

class TimeEntryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        title('Time Entry');

        return view('pages.timeentry.timeentry');
    }
}
