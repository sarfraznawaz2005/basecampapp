<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 1/15/2017
 * Time: 3:36 PM
 */

/**
 * Enable or disable query log.
 *
 * @param bool $enable
 */
function queryLog($enable = true)
{
    if ($enable) {
        \DB::connection()->enableQueryLog();
    } else {
        \DB::connection()->disableQueryLog();
    }
}

/**
 * @return mixed
 * This function return last executed query in plain sql
 */
function getLastQuery()
{
    $query = \DB::getQueryLog();
    $lastQuery = end($query);

    return $lastQuery;
}

/**
 * displays message on console and also appends in log
 *
 * @param $message
 * @param bool $log
 */
function out($message, $log = true)
{
    echo $message . PHP_EOL;

    if ($log) {
        Log::info($message);
    }
}

/**
 * Returns instance of logged in user.
 *
 * @return \Illuminate\Contracts\Auth\Authenticatable|\App\User\Models\User
 */
function user()
{
    return auth()->user();
}

/**
 * Removes given field value from request
 *
 * @param $field
 */
function removeRequestVar($field)
{
    if (is_array($field)) {
        foreach ($field as $item) {
            request()->request->remove($item);
        }
    } else {
        request()->request->remove($field);
    }
}

/**
 * Adds a new value to request
 *
 * @param $name
 * @param $value
 */
function addRequestVar($name, $value)
{
    request()->request->add([$name => $value]);
}

/**
 * Removes given field value from request if it's empty
 *
 * @param $field
 */
function removeRequestVarIfEmpty($field)
{
    if (is_array($field)) {
        foreach ($field as $item) {
            if (trim(request()->$item) === '') {
                removeRequestVar($item);
            }
        }
    } else {
        if (trim(request()->$field) === '') {
            removeRequestVar($field);
        }
    }
}

/**
 * if DataTable ajax frontend gets empty serverside response, this will avoid the error
 *
 * @return string
 */
function noDataTableResponse()
{
    return json_encode([
        "sEcho" => 1,
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => []
    ]);
}

function title($title = '')
{
    if (trim($title)) {
        request()->session()->flash('page.title', $title);
    }

    if (request()->session()->has('page.title')) {
        return request()->session()->get('page.title');
    }

    return config('app.name');
}


