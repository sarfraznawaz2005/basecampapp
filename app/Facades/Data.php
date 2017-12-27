<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 6:08 PM
 */

namespace App\Facades;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Setting;

class Data
{
    public static function getUserMonthlyHours($forceRefresh = false, $userId = 0)
    {
        $userId = $userId ?: user()->basecamp_api_user_id;

        if (!Setting::get("hours.$userId") || $forceRefresh) {
            $totalHours = getTotalWorkedHoursThisMonth($userId);

            Setting::set("hours.$userId", $totalHours);
            Setting::save();
        } else {
            $totalHours = Setting::get("hours.$userId");
        }

        return $totalHours;
    }

    public static function getUserProjectlyHours($forceRefresh = false)
    {
        $projects = user()->projects;

        if (!$projects->count() || $forceRefresh) {
            $projects = getTotalWorkedHoursThisMonthAllProjects();

            // reset hours
            DB::statement("update projects set hours = '0' where user_id = " . user()->id);

            foreach ($projects as $project) {

                $projectInstance = Project::firstOrNew([
                    'user_id' => user()->id,
                    'project_id' => $project['project_id'],
                ]);

                $project['user_id'] = user()->id;

                $projectInstance->fill($project);
                $projectInstance->save();
            }
        }

        return $projects;
    }

    public static function addUserProjects()
    {
        $projects = getAllProjects();

        foreach ($projects as $projectId => $name) {

            $projectInstance = Project::firstOrNew([
                'user_id' => user()->id,
                'project_id' => $projectId,
            ]);

            $projectInstance->user_id = user()->id;
            $projectInstance->project_id = $projectId;
            $projectInstance->project_name = $name;

            $projectInstance->save();
        }
    }

    public static function getAllUsers()
    {
        $finalData = [];

        $data = getInfo("people");

        if (isset($data['person'])) {
            foreach ($data['person'] as $xml) {
                $array = (array)$xml;

                // consider only company employees
                if ($array['company-id'] !== env('ETEAM_BC_COMPANY_ID')) {
                    continue;
                }

                if (isset($array['first-name'])) {
                    $finalData[$array['id']] = ucfirst($array['first-name']) . ' ' . ucfirst($array['last-name']);
                }
            }
        }

        asort($finalData);

        return $finalData;
    }

    public static function checkConnection($bcUserId)
    {
        $name = getPersonName($bcUserId);

        if (!$name) {
            flash('Could not connect to BaseCamp with your provided details, make sure they are correct!', 'warning');
            return false;
        }

        return true;
    }
}