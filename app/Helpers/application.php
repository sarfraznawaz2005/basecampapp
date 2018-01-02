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
 * @return \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User
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

/**
 * make listing edit button
 *
 * @param $link
 * @param string $title
 * @return string
 */
function listingEditButton($link, $title = 'Edit')
{
    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="$title" class="edit_btn" href="$link">
        <b class="btn btn-primary btn-sm glyphicon glyphicon-pencil"></b>
    </a>
HTML;

    return $html;
}

/**
 * make listing view button
 *
 * @param $link
 * @param string $title
 * @return string
 */
function listingViewButton($link, $title = 'View')
{
    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="$title" class="edit_btn" href="$link">
        <b class="btn btn-success btn-sm glyphicon glyphicon-eye-open"></b>
    </a>
HTML;

    return $html;
}

/**
 * make listing delete button
 *
 * @param $link
 * @param string $title
 * @param bool $showTip
 * @param bool $icon
 * @return string
 */
function listingDeleteButton($link, $title = 'this', $showTip = true, $icon = true)
{
    $tooltipClass = $showTip ? 'data-tooltip' : '';
    $csrf_field = csrf_field();
    $method_field = method_field('DELETE');
    $text = $icon ? '<b class="btn btn-danger btn-sm glyphicon glyphicon-trash"></b>' : 'Delete';
    $btnClass = $icon ? '' : 'btn btn-danger btn-sm';

    $html = <<< HTML
    <form action="$link" method="POST" style="display: inline;">
        $csrf_field
        $method_field

        <a data-placement="top" $tooltipClass data-original-title="Delete" 
        class="delete_btn confirm-delete $btnClass"
        data-label="$title"
        href="javascript:void(0);">
            $text
        </a>
    </form>
HTML;

    return $html;
}

/**
 * Centers content on dataTable.
 *
 * @param $data
 * @return string
 */
function tdCenter($data, $width = 'auto')
{
    return "<span class='tdcenter' style='width: $width; text-align: center; display: inline-block;'>$data</span>";
}

function tdBold($data)
{
    return "<span style='font-weight: bold;'>$data</span>";
}

function tdCheckBox($id, $column, $target, $checked = false)
{
    $checked = $checked ? 'checked' : '';

    return "<input data-id='$id' data-column='$column' data-target='$target' class='dt_chk' type='checkbox' $checked>";
}

/**
 * Returns label style for column of dataTable
 *
 * @param $type
 * @param $text
 * @param string $width
 * @return string
 */
function tdLabel($type, $text, $width = '60px')
{
    return "<label class='label label-$type tdcenter' style='width: $width;  line-height: 15px; margin-top: 7px; display: inline-block;'>$text</label>";
}


/**
 * Returns html table row
 *
 * Usage: tr($model->fieldName);
 *
 * @param $value
 * @param string $title
 * @param bool $strong
 * @param string $default
 * @return string
 */
function tr($value, $title = '', $strong = false, $default = '')
{
    if (!trim($value)) {
        $value = $default;
    }

    $tr = '<tr>';

    if (!$title) {
        $title = ucwords(str_replace(['_', '-'], ['', ''], $value));
    }

    if ($strong) {
        $tr .= '<td><strong>' . $title . '</strong></td>';
    } else {
        $tr .= '<td>' . $title . '</td>';
    }

    $tr .= '<td>' . $value . '</td>';
    $tr .= '</tr>';

    return $tr;
}

function getWorkingDaysCount($allMonth = false)
{
    $workdays = [];
    $month = date('n'); // Month ID, 1 through to 12.
    $year = date('Y'); // Year in 4 digit 2009 format.
    $startDate = new DateTime(date('Y-m-1'));

    if ($allMonth) {
        $days = date('t');
        $datetime2 = new DateTime(date("Y-m-$days"));
        $interval = $startDate->diff($datetime2);
        $day_count = $interval->days; // days from 1st of month to today
    } else {
        $day_count = date('d');
    }

    //loop through all days
    for ($i = 1; $i <= $day_count; $i++) {
        $date = $year . '/' . $month . '/' . $i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

        //if not a weekend add day to array
        if ($day_name != 'Sun' && $day_name != 'Sat') {
            $workdays[] = $i;
        }
    }

    return count($workdays);
}