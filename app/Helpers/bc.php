<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 1:54 PM
 *
 * Some functions to get data from basecamp classic.
 * Docs: https://github.com/basecamp/basecamp-classic-api
 *
 */

use Carbon\Carbon;

/**
 * Get's info from basecamp
 * @param $action
 * @param $queryString
 * @return array|mixed|SimpleXMLElement
 */
function getInfo($action, $queryString = '')
{
    if (!credentialsOk()) {
        return '';
    }

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action . '/' . $queryString;

    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($session, CURLOPT_HTTPGET, 1);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml', 'Content-Type: application/xml']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_USERPWD, apiKey() . ":X");
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($session);
    curl_close($session);

    @$response = simplexml_load_string($response);
    $response = (array)$response;

    //$array = json_decode(json_encode($response), 1);

    if (isset($response['head']['title'])) {
        return '';
    }

    return $response;
}

function postInfo($action, $xmlData)
{
    if (!credentialsOk()) {
        return '';
    }

    @unlink('headers');

    $url = 'https://' . companyName() . '.basecamphq.com/' . $action;

    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($session, CURLOPT_HTTPHEADER, ['Accept: application/xml', 'Content-Type: application/xml']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_USERPWD, apiKey() . ":X");
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($session, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($session, CURLOPT_HEADERFUNCTION, "HandleHeaderLine");

    curl_exec($session);
    curl_close($session);

    $response = file_get_contents('headers');

    return $response;
}

function HandleHeaderLine($curl, $header_line)
{
    file_put_contents('headers', $header_line);

    return $header_line;
}

function companyName()
{
    return user()->basecamp_org;
}

function apiKey()
{
    return user()->basecamp_api_key;
}

function bcUserId($bcUserId = 0)
{
    return $bcUserId ?: user()->basecamp_api_user_id;
}

function credentialsOk()
{
    if (!trim(companyName()) || !trim(apiKey()) || !trim(bcUserId())) {
        return false;
    }

    return true;
}

##############################################################
## DATA FUNCTIONS
##############################################################

function getWorkedHoursData($bcUserId = 0)
{
    $userId = bcUserId($bcUserId);
    $sDate = date('Y-m-1');
    $eDate = date('Y-m-d');

    $query = "report?&subject_id=$userId&from=$sDate&to=$eDate&commit=Create+report";

    $data = getInfo('time_entries', $query);

    return $data;
}

function getTotalWorkedHoursThisMonth($bcUserId = 0)
{
    $hours = 0;

    $data = getWorkedHoursData($bcUserId);

    if (isset($data['time-entry'])) {
        foreach ($data['time-entry'] as $timeEntryXML) {
            $array = (array)$timeEntryXML;

            if (isset($array['hours'])) {
                $hours += $array['hours'];
            }
        }

        $hours = number_format($hours, 2);
    }

    return $hours;
}

function getTotalWorkedHoursThisMonthAllProjects($bcUserId = 0)
{
    $finalData = [];
    $projectsData = [];

    $data = getWorkedHoursData($bcUserId);

    if (isset($data['time-entry'])) {
        foreach ($data['time-entry'] as $timeEntryXML) {
            $array = (array)$timeEntryXML;
            $projectsData[] = $array;
        }
    }

    $projectsData = collect($projectsData)->groupBy('project-id');

    foreach ($projectsData as $projectId => $array) {

        $totalHours = $array->sum('hours');

        $finalData[] = [
            'project_id' => $projectId,
            'project_name' => getProjectName($projectId),
            'hours' => number_format($totalHours, 2),
        ];
    }

    $finalData = collect($finalData)->sortByDesc('hours')->toArray();

    return $finalData;
}

function getProjectName($id)
{
    $data = getInfo("projects/$id");

    return isset($data['name']) ? $data['name'] : '';
}

function getPersonName($id)
{
    $data = getInfo("people/$id");

    return isset($data['first-name']) ? $data['first-name'] : '';
}

function getTodoListName($id)
{
    $data = getInfo("todo_lists/$id");

    return isset($data['name']) ? $data['name'] : '';
}

function getTodoName($id)
{
    $data = getInfo("todo_items/$id");

    return isset($data['content']) ? $data['content'] : '';
}

function getAllProjects()
{
    $finalData = [];

    $data = getInfo("projects");

    if (isset($data['project'])) {

        $project = (array)$data['project'];

        if (isset($project[0])) {
            foreach ($data['project'] as $xml) {
                $array = (array)$xml;

                if (isset($array['id']) && isset($array['company'])) {
                    $finalData[$array['id']] = ucfirst($array['name']);
                }
            }
        } else {
            // in case of single entry/project
            if (isset($project['id']) && isset($project['company'])) {
                $finalData[$project['id']] = ucfirst($project['name']);
            }
        }

    }

    asort($finalData);

    return $finalData;
}

function getProjectTodoLists($projectId)
{
    $finalData = [];

    $data = getInfo("projects/$projectId/todo_lists");

    if (isset($data['todo-list'])) {
        foreach ($data['todo-list'] as $xml) {
            $array = (array)$xml;

            if (isset($array['id'])) {
                $finalData[$array['id']] = ucfirst($array['name']);
            }
        }
    }

    asort($finalData);

    return $finalData;
}

function getTodoListTodos($todolistId)
{
    $finalData = [];

    $data = getInfo("todo_lists/$todolistId/todo_items");

    if (isset($data['todo-item'])) {
        foreach ($data['todo-item'] as $xml) {
            $array = (array)$xml;

            if (isset($array['id'])) {
                $finalData[$array['id']] = ucfirst($array['content']);
            }
        }
    }

    asort($finalData);

    return $finalData;
}

function getBCHoursDiff($date, $startTime, $endTime, $returnNegative = false)
{
    $sTime = Carbon::parse($date . ' ' . $startTime);
    $eTime = Carbon::parse($date . ' ' . $endTime);

    $diffInMinutes = $sTime->diffInMinutes($eTime, false);

    if ($diffInMinutes < 0 && !$returnNegative) {
        return number_format(0, 2);
    }

    return number_format($diffInMinutes / 60, 2);
}

