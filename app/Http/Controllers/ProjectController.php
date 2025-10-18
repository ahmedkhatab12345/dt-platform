<?php

namespace App\Http\Controllers;

use App\Models\GovernmentEntity;
use App\Models\Project;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Gate::authorize('read projects');

        $projects = Project::latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        Gate::authorize('create projects');
        $entities = GovernmentEntity::all();
        $standards = Standard::all();
        return view('projects.create', compact('entities','standards'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create projects');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
            'department' => 'required|string|max:255',

            // الحقول الجديدة
            'government_entity_id' => 'required|exists:government_entities,id',
            'standard_id' => 'required|exists:standards,id',

            'indicators' => 'nullable|array',
            'indicators.*' => 'string|max:255',
            'final_deliverables' => 'nullable|array',
            'final_deliverables.*' => 'string|max:255',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:255',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    public function show(Project $project)
    {
        Gate::authorize('read projects');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        Gate::authorize('update projects');
        $entities = GovernmentEntity::all();
        $standards = Standard::all();
        return view('projects.edit', compact('project','entities','standards'));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update projects');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
            'department' => 'required|string|max:255',

            // الحقول الجديدة
            'government_entity_id' => 'required|exists:government_entities,id',
            'standard_id' => 'required|exists:standards,id',

            'indicators' => 'nullable|array',
            'indicators.*' => 'string|max:255',
            'final_deliverables' => 'nullable|array',
            'final_deliverables.*' => 'string|max:255',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:255',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم تحديث المشروع بنجاح');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete projects');

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }
}
