<?php

namespace App\Http\Controllers;

use App\Enums\GovernmentEntityClassification;
use App\Models\ActivityLog;
use App\Models\GovernmentEntity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GovernmentEntityController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read government_entities')->only('index');
        $this->middleware('permission:create government_entities')->only(['create', 'store']);
        $this->middleware('permission:update government_entities')->only(['edit', 'update']);
        $this->middleware('permission:delete government_entities')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = GovernmentEntity::with(['strategy', 'goals']);

        $user = auth()->user();

        if ($user->category_id != 6) {
            $query->where('created_by', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $entities = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('government_entities.index', compact('entities'));
    }

    public function create()
    {
        $classifications = GovernmentEntityClassification::cases();
        return view('government_entities.create', compact('classifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string|max:255|unique:government_entities,uuid',
            'name' => 'required|string|max:255',
            'classification' => 'required|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'goals' => 'nullable|array',
            'goals.*' => 'nullable|string|max:500',
        ]);

        $validated = $request->only('uuid', 'name', 'classification');
        $validated['created_by'] = auth()->id();

        $entity = GovernmentEntity::create($validated);

        $entity->strategy()->create([
            'mission' => $request->mission,
            'vision' => $request->vision,
        ]);

        if ($request->filled('goals')) {
            foreach ($request->goals as $goal) {
                if ($goal) {
                    $entity->goals()->create(['goal' => $goal]);
                }
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'government_entities',
            'description' => 'تم إنشاء جهة حكومية جديدة باسم ' . $request->name,
        ]);

        return redirect()->route('government_entities.index')
            ->with('success', 'Entity created successfully.');
    }

    public function edit(GovernmentEntity $government_entity)
    {
        $classifications = GovernmentEntityClassification::cases();
        return view('government_entities.edit', [
            'entity' => $government_entity,
            'classifications' => $classifications
        ]);
    }

    public function update(Request $request, GovernmentEntity $government_entity)
    {
        $request->validate([
            'uuid' => ['required', 'string', 'max:255', Rule::unique('government_entities', 'uuid')->ignore($government_entity->id)],
            'name' => 'required|string|max:255',
            'classification' => 'required|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'goals' => 'nullable|array',
            'goals.*' => 'nullable|string|max:500',
        ]);

        $government_entity->update($request->only('uuid', 'name', 'classification'));

        $government_entity->strategy()->updateOrCreate(
            ['government_entity_id' => $government_entity->id],
            [
                'mission' => $request->mission,
                'vision' => $request->vision,
            ]
        );

        $government_entity->goals()->delete();
        if ($request->filled('goals')) {
            foreach ($request->goals as $goal) {
                if ($goal) {
                    $government_entity->goals()->create(['goal' => $goal]);
                }
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'government_entities',
            'description' => 'تم تعديل الجهة الحكومية باسم ' . $government_entity->name,
        ]);

        return redirect()->route('government_entities.index')
            ->with('success', 'Entity updated successfully.');
    }

    public function show(GovernmentEntity $government_entity)
    {
        return view('government_entities.show', compact('government_entity'));
    }

    public function destroy(GovernmentEntity $government_entity)
    {
        if ($government_entity->assignments()->count() > 0) {
            return redirect()->route('government_entities.index')
                ->with('error', 'This government entity cannot be deleted because it is associated with assignments.');
        }


        $government_entity->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'government_entities',
            'description' => 'تم حذف الجهة الحكومية باسم ' . $government_entity->name,
        ]);

        return redirect()->route('government_entities.index')
            ->with('success', 'Government entity deleted successfully.');
    }
}
