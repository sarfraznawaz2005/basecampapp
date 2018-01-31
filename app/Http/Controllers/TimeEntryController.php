<?php

namespace App\Http\Controllers;

use App\DataTables\PendingTodosDataTable;
use App\Facades\Data;
use App\Models\Todo;
use Exception;
use Illuminate\Support\Str;
use function parse_str;
use function request;
use function set_time_limit;
use function sleep;
use function str_slug;
use Yajra\Datatables\Facades\Datatables;

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

        // refresh hours if session lost
        if (! session('all_users_hours')) {
            HomeController::refreshData();
        }

        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        if (old('project_id')) {
            $todoLists = json_decode($this->todoLists(old('project_id')), true);
        } elseif (session('project_id')) {
            $todoLists = json_decode($this->todoLists(session('project_id')), true);
        }

        if (old('todolist_id')) {
            $todos = json_decode($this->todos(old('todolist_id')), true);
        } elseif (session('todolist_id')) {
            $todos = json_decode($this->todos(session('todolist_id')), true);
        }

        return $dataTable->render('pages.timeentry.timeentry',
            compact('projects', 'todoLists', 'todos')
        );
    }

    /**
     * Stores a to do.
     *
     * @param Todo $todo
     * @return TimeEntryController
     */
    public function store(Todo $todo)
    {
        return $this->saveEntry($todo);
    }

    public function edit(Todo $todo)
    {
        title('Edit Entry');

        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        $todoLists = json_decode($this->todoLists($todo->project_id), true);
        $todos = json_decode($this->todos($todo->todolist_id), true);

        return view('pages.timeentry.edit', compact('projects', 'todoLists', 'todos', 'todo'));
    }

    public function update(Todo $todo)
    {
        return $this->saveEntry($todo, true);
    }

    /**
     * @param Todo $todo
     * @param bool $isUpdate
     * @return $this
     */
    protected function saveEntry(Todo $todo, $isUpdate = false)
    {
        $rules = [
            'user_id' => 'required',
            'project_id' => 'required',
            'todolist_id' => 'required',
            'todo_id' => 'required',
            'dated' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'description' => 'required',
        ];

        if (!$isUpdate) {
            addRequestVar('user_id', user()->id);
        } else {
            unset($rules['user_id']);
        }

        // validate
        $this->validate(request(), $rules);

        ///////////////////////////////////////////////////
        // make sure end time is greater than start time
        $diff = getBCHoursDiff(request()->dated, request()->time_start, request()->time_end, true);

        if ($diff < 0) {
            flash('Start Time cannot greater than End Time.', 'danger');
            return redirect()->back()->withInput();
        }
        ///////////////////////////////////////////////////

        $todo->fill(request()->all());

        if (!$todo->save()) {
            return redirect()->back()->withInput()->withErrors($todo);
        }

        session(['project_id' => request()->project_id]);
        session(['todolist_id' => request()->todolist_id]);
        session(['todo_id' => request()->todo_id]);
        session(['description' => request()->description]);

        flash('Todo Saved Succesfully', 'success');

        if ($isUpdate) {
            return redirect()->back();
        }

        return redirect()->to(route('timeentry'));
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

    public function postedTodos()
    {
        $query = user()->postedTodos;

        if (!$query->count()) {
            return noDataTableResponse();
        }

        return Datatables::of($query)
            ->editColumn('project', function ($object) {
                if ($object->project) {
                    return $object->project->project_name;
                }

                return 'N/A';
            })
            ->editColumn('total', function ($object) {
                $text = getBCHoursDiff($object->dated, $object->time_start, $object->time_end);

                return tdLabel('success', $text);
            })
            ->editColumn('action', function ($object) {

                $action = listingViewButton(route('timeentry.view', [$object]));
                $action .= listingDeleteButton(route('delete_todo', [$object]), 'Time Entry');

                return tdCenter($action);
            })
            ->rawColumns(['total', 'action'])
            ->make(true);
    }

    public function destroy(Todo $todo)
    {
        try {
            if (!$todo->delete($todo)) {
                return redirect()->back()->withErrors(['Could not delete!']);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        flash('Todo Deleted Succesfully', 'success');

        return redirect()->back();
    }

    public function postTodos(Todo $todo)
    {
        set_time_limit(0);

        $posted = '';

        if (trim(request()->data)) {
            $todoIDs = [];

            parse_str(request()->data, $todoIDs);

            if (isset($todoIDs['selected_todos']) && $todoIDs['selected_todos']) {
                foreach ($todoIDs['selected_todos'] as $todoID) {

                    $todo = $todo->find($todoID);

                    if ($todo) {

                        $personId = user()->basecamp_api_user_id;
                        $hours = getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end);
                        $projectName = $todo->project->project_name;

                        // find out action endpoint to post to basecamp
                        $action = 'projects/' . $todo->project_id . '-' . str_slug($projectName) . '/time_entries.xml';


                        $xmlData = <<< XMLDATA
                        <time-entry>
                          <date>{$todo->dated}</date>
                          <description>{$todo->description}</description>
                          <hours>$hours</hours>
                          <person-id>$personId</person-id>
                          <todo-item-id>{$todo->todo_id}</todo-item-id>
                        </time-entry>
XMLDATA;

                        // send to basecamp
                        $responseHeader = postInfo($action, $xmlData);

                        // check to see if it was posted successfully to BC
                        if (Str::contains($responseHeader, 'Created')) {
                            // update to do status
                            $todo->status = 'posted';
                            $todo->save();

                            $posted = 'ok';
                        } else {
                            flash(
                                'Todo "' . $todo->description . '" with hours of ' . $hours . ' could not be posted.',
                                'danger'
                            );
                        }

                        // so that we do not send post request too fast to BC
                        sleep(1);
                    }
                }
            }
        }

        if ($posted === 'ok') {
            flash('Todos Posted Succesfully To Basecamp.', 'success');

            $monthHours = Data::getUserMonthlyHours(true);
            session(['month_hours' => $monthHours]);
        }

        return $posted;
    }


    public function show(Todo $todo)
    {
        title('Todo Details');

        $todolistName = getTodoListName($todo->todolist_id);
        $todoName = getTodoName($todo->todo_id);

        return view('pages.timeentry.details',
            compact('todo', 'todolistName', 'todoName')
        );
    }

}
