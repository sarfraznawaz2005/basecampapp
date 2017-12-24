<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class PostedTodosWidget extends AbstractWidget
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function run()
    {
        return view('widgets.posted_todos_widget', [
            'config' => $this->config,
        ]);
    }

    public function placeholder()
    {
        return "Loading...";
    }
}
