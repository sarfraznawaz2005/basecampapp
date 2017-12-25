<?php

namespace App\DataTables;

use function tdCenter;
use function tdLabel;
use Yajra\Datatables\Services\DataTable;

class PendingTodosDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('check', function ($object) {
                return '<input type="checkbox" class="chk_post" name="selected_todos[]" value="' . $object->id . '">';
            })
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
            ->rawColumns(['check', 'total', 'action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $query = user()->pendingTodos;

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
            'order' => [[1, 'desc']],
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
            [
                'data' => 'check',
                'name' => 'check',
                'title' => '<input type="checkbox" id="checkAll">',
                'orderable' => false,
                'searchable' => false,
            ],
            'dated',
            'project',
            'description',
            'time_start',
            'time_end',
            'total',
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
}