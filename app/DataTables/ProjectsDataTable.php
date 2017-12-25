<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;

class ProjectsDataTable extends DataTable
{
    public $projectId = 0;

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('hours', function ($array) {
                return tdLabel('success', number_format($array['hours'], 2));
            })
            ->rawColumns(['hours'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $projectsData = [];

        $data = getWorkedHoursData();

        if (isset($data['time-entry'])) {
            foreach ($data['time-entry'] as $timeEntryXML) {
                $array = (array)$timeEntryXML;
                $projectsData[] = $array;
            }
        }

        $projectsData = collect($projectsData)->where('project-id', $this->projectId);

        return $this->applyScopes($projectsData);
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
            'date',
            'description',
            'hours',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'projects_' . time();
    }
}