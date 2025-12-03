<?php

namespace App\Http\Controllers\Innovation;

use App\Http\Controllers\Controller;
use App\Models\InnovationAssessment;
use App\Models\GovernmentEntity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InnovationAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('read assessments');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        $assessments = InnovationAssessment::query()
            ->with(['governmentEntity', 'user'])
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where(function ($sub) use ($term) {
                    $sub->where('summary', 'like', "%{$term}%")
                        ->orWhere('strengths', 'like', "%{$term}%")
                        ->orWhere('weaknesses', 'like', "%{$term}%")
                        ->orWhere('recommendation', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('government_entity_id'),
                fn ($q) => $q->where('government_entity_id', $request->integer('government_entity_id'))
            )
            ->latest()
            ->paginate(10);

        return view('innovation.assessment.index', [
            'assessments' => $assessments,
            'entities' => $entities,
        ]);
    }

    public function show(InnovationAssessment $assessment)
    {
        Gate::authorize('read assessments');

        $user = auth()->user();
        if ($user->category_id != 6 && $assessment->created_by != $user->id) {
            abort(403);
        }

        return view('innovation.assessment.show', compact('assessment'));
    }

    public function create()
    {
        Gate::authorize('create assessments');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.assessment.create', compact('entities'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create assessments');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'summary' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'understanding_level' => 'required|in:high,medium,low',
        ]);

        $validated['created_by'] = auth()->id();

        $row = InnovationAssessment::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'innovation_assessments',
            'description' => 'تم إنشاء تقييم ابتكار للجهة: ' . $row->governmentEntity->name,
        ]);

        return redirect()->route('innovation.assessment.index')
            ->with('success', 'تم إضافة تقييم الابتكار بنجاح');
    }

    public function edit(InnovationAssessment $assessment)
    {
        Gate::authorize('update assessments');

        $user = auth()->user();
        if ($user->category_id != 6 && $assessment->created_by != $user->id) {
            abort(403);
        }

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.assessment.edit', compact('assessment', 'entities'));
    }

    public function update(Request $request, InnovationAssessment $assessment)
    {
        Gate::authorize('update assessments');

        $user = auth()->user();
        if ($user->category_id != 6 && $assessment->created_by != $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'summary' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'understanding_level' => 'required|in:high,medium,low',
        ]);

        $assessment->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'innovation_assessments',
            'description' => 'تم تعديل تقييم ابتكار للجهة: ' . $assessment->governmentEntity->name,
        ]);

        return redirect()->route('innovation.assessment.index')
            ->with('success', 'تم تحديث تقييم الابتكار بنجاح');
    }

    public function destroy(InnovationAssessment $assessment)
    {
        Gate::authorize('delete assessments');

        $user = auth()->user();
        if ($user->category_id != 6 && $assessment->created_by != $user->id) {
            abort(403);
        }

        $assessment->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'innovation_assessments',
            'description' => 'تم حذف تقييم الابتكار',
        ]);

        return redirect()->route('innovation.assessment.index')
            ->with('success', 'تم حذف تقييم الابتكار بنجاح');
    }
}
