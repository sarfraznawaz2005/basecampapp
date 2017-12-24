<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;

class PostedTodosDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('project', function ($object) {
                if ($object->project) {
                    return $object->project->project_name;
                }

                return 'N/A';
            })
            ->editColumn('total', function ($object) {
                return '10';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $query = user()->postedTodos;

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
            //->addAction(['width' => '80px'])
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
            'order' => [[0, 'desc']],
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