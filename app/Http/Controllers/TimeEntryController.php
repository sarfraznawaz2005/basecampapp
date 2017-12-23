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

        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        return view('pages.timeentry.timeentry', compact('projects'));
    }

    /**
     * Returns todolists of given project
     *
     * @param $projectId
     * @return string
     */
    public function todoLists($projectId)
    {
        return json_encode(getProjectTodoLists($projectId));
    }

    /**
     * Returns todos of given todolist
     *
     * @param $todolistId
     * @return string
     */
    public function todos($todolistId)
    {
        return json_encode(getTodoListTodos($todolistId));
    }
}
