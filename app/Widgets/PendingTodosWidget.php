<?php

namespace App\Widgets;

use App\DataTables\PendingTodosDataTable;
use Arrilot\Widgets\AbstractWidget;

class PendingTodosWidget extends AbstractWidget
{
    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = false;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     * @param PendingTodosDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function run(PendingTodosDataTable $dataTable)
    {
        return $dataTable->render('widgets.pending_todos_widget', [
            'config' => $this->config,
        ]);
    }

    public function placeholder()
    {
        return "Loading...";
    }
}
