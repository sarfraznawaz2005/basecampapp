<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectsDataTable;
use App\Models\Project;
use function collect;

class ProjectController extends Controller
{
    public function show($projectId, Project $project, ProjectsDataTable $dataTable)
    {
        $project = $project->where('project_id', $projectId)->first();

        title($project->project_name . ' - Hours This Month');

        $dataTable->projectId = $projectId;

        return $dataTable->render('pages.project.project');
    }
}
