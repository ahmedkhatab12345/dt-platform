<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\GovernmentEntity;
use Illuminate\Http\Request;
use App\Exports\PlannedProjectsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function plannedProjects(Request $request)
    {
        $entities = GovernmentEntity::orderBy('name')->get();

        $projects = Project::query()
            ->with('governmentEntity')
            ->where('status', 'planned')
            // ->whereNotNull('budget')
            // ->where('budget', '>', 0)
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where('name', 'like', "%{$term}%");
            })
            ->when($request->filled('government_entity_id'), function ($q) use ($request) {
                $q->where('government_entity_id', $request->government_entity_id);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('reports.projects_planned', compact('projects', 'entities'));
    }

    public function plannedProjectsExport()
    {
        return Excel::download(new PlannedProjectsExport, 'planned_projects.xlsx');
    }
}
