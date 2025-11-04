<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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

    public function index(Request $request)
    {
        Gate::authorize('read projects');
    
        $user = auth()->user();
        $entities = GovernmentEntity::orderBy('name')->get();
    
        $projects = Project::query()
            ->with(['governmentEntity', 'standard', 'user'])
            ->when($user->category_id != 6, function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where('name', 'like', '%' . $term . '%');
            })
            ->when($request->filled('government_entity_id'), function ($q) use ($request) {
                $q->where('government_entity_id', $request->integer('government_entity_id'));
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());
    
        return view('projects.index', [
            'projects' => $projects,
            'entities' => $entities,
            'selectedGovernmentEntityId' => $request->integer('government_entity_id'),
            'selectedStatus' => $request->input('status'),
        ]);
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
            'government_entity_id' => 'required|exists:government_entities,id',
            'standard_id' => 'required|exists:standards,id',
            'overview' => 'nullable|string',
            'start_month' => 'required|integer|min:1|max:12',
            'start_year' => 'required|integer|min:2020|max:2030',
            'end_month' => 'required|integer|min:1|max:12',
            'end_year' => 'required|integer|min:2020|max:2030',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:planned,in_progress,completed',
            'department' => 'nullable|string|max:255',
            'indicators' => 'nullable|array',
            'indicators.*' => 'nullable|string|max:255',
            'final_deliverables' => 'nullable|array',
            'final_deliverables.*' => 'nullable|string|max:255',
            'activities' => 'nullable|array',
            'activities.*' => 'nullable|string|max:255',
        ]);
        
        $validated['start_date'] = "{$validated['start_year']}-{$validated['start_month']}-01";
        $validated['end_date'] = "{$validated['end_year']}-{$validated['end_month']}-01";
        
        unset($validated['start_year'], $validated['start_month'], $validated['end_year'], $validated['end_month']);
        $validated['created_by'] = auth()->id();
        
        Project::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'projects',
            'description' => 'تم إنشاء مشروع جديد باسم ' . $request->name,
        ]);
        
        return redirect()
            ->route('projects.create', ['government_entity_id' => $validated['government_entity_id']])
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
    
            'start_month' => 'nullable|integer|min:1|max:12',
            'start_year'  => 'nullable|integer|min:2020|max:2030',
            'end_month'   => 'nullable|integer|min:1|max:12',
            'end_year'    => 'nullable|integer|min:2020|max:2030',
    
            'budget' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:planned,in_progress,completed',
            'department' => 'nullable|string|max:255',
    
            'government_entity_id' => 'required|exists:government_entities,id',
            'standard_id' => 'required|exists:standards,id',
    
            'indicators' => 'nullable|array',
            'indicators.*' => 'nullable|string|max:255',
            'final_deliverables' => 'nullable|array',
            'final_deliverables.*' => 'nullable|string|max:255',
            'activities' => 'nullable|array',
            'activities.*' => 'nullable|string|max:255',
        ]);
    
        if (!empty($validated['start_year']) && !empty($validated['start_month'])) {
            $validated['start_date'] = "{$validated['start_year']}-{$validated['start_month']}-01";
        }
    
        if (!empty($validated['end_year']) && !empty($validated['end_month'])) {
            $validated['end_date'] = "{$validated['end_year']}-{$validated['end_month']}-01";
        }
    
        unset($validated['start_year'], $validated['start_month'], $validated['end_year'], $validated['end_month']);
    
        $validated['indicators'] = array_filter($validated['indicators'] ?? []);
        $validated['final_deliverables'] = array_filter($validated['final_deliverables'] ?? []);
        $validated['activities'] = array_filter($validated['activities'] ?? []);
    
        $project->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'projects',
            'description' => 'تم تعديل مشروع باسم ' . $project->name,
        ]);
    
        return redirect()->route('projects.index')
            ->with('success', 'تم تحديث المشروع بنجاح');
    }
    
    public function destroy(Project $project)
    {
        Gate::authorize('delete projects');

        $project->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'projects',
            'description' => 'تم حذف مشروع باسم ' . $project->name,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }
}
