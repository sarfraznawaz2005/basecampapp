<?php

namespace App\DataTables;

use App\Models\User;
use function tdLabel;
use Yajra\Datatables\Services\DataTable;
use function tdCenter;

class UsersDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('pending_hours', function ($object) {
                return tdLabel('warning', $object->pendingTodosHours());
            })
            ->editColumn('posted_hours', function ($object) {
                return tdLabel('warning', $object->postedTodosHours());
            })
            ->editColumn('is_admin', function ($object) {
                $text = $object->is_admin === 1 ? 'Yes' : 'No';
                $type = $object->is_admin === 1 ? 'success' : 'default';

                return tdLabel($type, $text);
            })
            ->editColumn('action', function ($object) {
                $action = '';

                if ($object->id !== user()->id) {
                    $action = $this->loginAsButton(route('user.loginas', $object));
                }

                return tdCenter($action);
            })
            ->rawColumns(['pending_hours', 'posted_hours', 'is_admin', 'action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $query = User::all();

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->addAction(['width' => '1px'])
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'order' => [4, 'desc'],
            'dom' => 'Bfrtipr',
            'pageLength' => 25,
            'autoWidth' => true,
            'responsive' => true,
            'bLengthChange' => false,
            'processing' => true,
            'buttons' => [
                'create',
                'export',
                'print',
                'reset',
                'reload',
            ],
        ];
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name',
            'email',
            'is_admin',
            'pending_hours',
            'posted_hours',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_' . time();
    }

    protected function loginAsButton($link)
    {
        $title = 'Login as User';
        $type = 'primary';

        $html = <<< HTML
        <a data-placement="top" data-tooltip data-original-title="$title" title="$title" class="edit_btn" href="$link">
            <b class="btn btn-$type btn-sm glyphicon glyphicon-user"></b>
        </a>
HTML;

        return $html;
    }
}