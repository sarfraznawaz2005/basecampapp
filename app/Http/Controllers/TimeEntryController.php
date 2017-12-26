<?php

namespace App\Http\Controllers;

use App\DataTables\PendingTodosDataTable;
use App\Models\Todo;
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

        // todo : FIX
        // make sure end time is greater than start time
        ///////////////////////////////////////////////////
        /*
        $sTime = date('Y-m-d h:i', strtotime(request()->dated . ' ' . request()->time_start));
        $eTime = date('Y-m-d h:i', strtotime(request()->dated . ' ' . request()->time_end));

        if (strtotime($sTime) > strtotime($eTime)) {
            flash('Start Time cannot greater than End Time.', 'danger');
            return redirect()->back()->withInput();
        } elseif (strtotime($sTime) == strtotime($eTime)) {
            flash('Both times specified are same.', 'danger');
            return redirect()->back()->withInput();
        }
        */
        ///////////////////////////////////////////////////

        $todo->fill(request()->all());

        if (!$todo->save()) {
            return redirect()->back()->withInput()->withErrors($todo);
        }

        flash('Todo Saved Succesfully', 'success');

        return redirect()->back()->withInput();
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
        $todo->fill(request()->all());

        if (!$todo->save()) {
            return redirect()->back()->withInput()->withErrors($todo);
        }

        flash('Todo Updated Succesfully', 'success');

        return redirect()->back();
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
                $action = listingDeleteButton(route('delete_todo', [$object]), 'Time Entry');

                return tdCenter($action);
            })
            ->rawColumns(['total', 'action'])
            ->make(true);
    }

    public function destroy(Todo $todo)
    {
        if (!$todo->delete($todo)) {
            return redirect()->back()->withErrors(['Could not delete!']);
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
                        }

                        // so that we do not send post request too fast to BC
                        sleep(1);
                    }
                }
            }
        }

        return $posted;
    }
}
