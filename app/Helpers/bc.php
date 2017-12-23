<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 1:54 PM
 */

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

    if (isset($response['head']['title'])) {
        return '';
    }

    return $response;
}

function companyName()
{
    return user()->basecamp_org;
}

function apiKey()
{
    return user()->basecamp_api_key;
}

function bcUserId()
{
    return user()->basecamp_api_user_id;
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

function getWorkedHoursData()
{
    $userId = bcUserId();
    $sDate = date('Y-m-1');
    $eDate = date('Y-m-d');

    $query = "report?&subject_id=$userId&from=$sDate&to=$eDate&commit=Create+report";

    $data = getInfo('time_entries', $query);

    return $data;
}

function getTotalWorkedHoursThisMonth()
{
    $hours = 0;

    $data = getWorkedHoursData();

    if (isset($data['time-entry'])) {
        foreach ($data['time-entry'] as $timeEntryXML) {
            $array = (array)$timeEntryXML;
            $hours += $array['hours'];
        }

        $hours = number_format($hours, 2);
    }

    return $hours;
}

function getTotalWorkedHoursThisMonthAllProjects()
{
    $finalData = [];
    $projectsData = [];

    $data = getWorkedHoursData();

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

    return $data['name'];
}

function getTodoListName($id)
{
    $data = getInfo("todo_lists/$id");

    return $data['name'];
}

function getTodoName($id)
{
    $data = getInfo("todo_items/$id");

    return $data['name'];
}

function getAllProjects()
{
    $finalData = [];

    $data = getInfo("projects");

    if (isset($data['project'])) {
        foreach ($data['project'] as $xml) {
            $array = (array)$xml;

            if (isset($array['id'])) {
                $finalData[$array['id']] = ucfirst($array['name']);
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

