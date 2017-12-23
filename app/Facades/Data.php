<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 12/23/2017
 * Time: 6:08 PM
 */

namespace App\Facades;

use App\Models\Project;
use Setting;

class Data
{
    public static function getUserMonthlyHours($forceRefresh = false)
    {
        $userId = user()->id;

        if (!Setting::get("hours.$userId") || $forceRefresh) {
            $totalHours = getTotalWorkedHoursThisMonth();

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
}