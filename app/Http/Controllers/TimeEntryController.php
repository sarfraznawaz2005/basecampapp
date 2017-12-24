<?php

namespace App\Http\Controllers;

use function addRequestVar;
use App\DataTables\PendingTodosDataTable;
use App\Models\Todo;
use function redirect;
use function request;

class TimeEntryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param PendingTodosDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(PendingTodosDataTable $dataTable)
    {
        title('Time Entry');

        $todoLists = [];
        $todos = [];

        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        if (old('project_id')) {
            $todoLists = json_decode($this->todoLists(old('project_id')), true);
        }

        if (old('todolist_id')) {
            $todos = json_decode($this->todos(old('todolist_id')), true);
        }

        return $dataTable->render('pages.timeentry.timeentry',
            compact('projects', 'todoLists', 'todos')
        );
    }

    /**
     * Stores a to do.
     *
     * @param Todo $todo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Todo $todo)
    {
        addRequestVar('user_id', user()->id);

        $this->validate(request(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'todolist_id' => 'required',
            'todo_id' => 'required',
            'dated' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'description' => 'required',
        ]);

        // make sure end time is greater than start time
        ///////////////////////////////////////////////////
        $sTime = date('Y-m-d h:i', strtotime(request()->dated . ' ' . request()->time_start));
        $eTime = date('Y-m-d h:i', strtotime(request()->dated . ' ' . request()->time_end));

        if (strtotime($sTime) > strtotime($eTime)) {
            flash('Start Time cannot greater than End Time.', 'danger');
            return redirect()->back()->withInput();
        } elseif (strtotime($sTime) == strtotime($eTime)) {
            flash('Both times specified are same.', 'danger');
            return redirect()->back()->withInput();
        }
        ///////////////////////////////////////////////////

        $todo->fill(request()->all());

        if (!$todo->save()) {
            return redirect()->back()->withInput()->withErrors($todo);
        }

        flash('Todo Saved Succesfully', 'success');

        return redirect()->back()->withInput();
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
