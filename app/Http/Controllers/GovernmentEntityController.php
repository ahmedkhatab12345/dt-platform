<?php

namespace App\Http\Controllers;

use App\Enums\GovernmentEntityClassification;
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
        $query = GovernmentEntity::query();

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
        ]);

        GovernmentEntity::create($request->only('uuid','name','classification'));

        return redirect()->route('government_entities.index')->with('success','Entity created successfully.');
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
            'uuid' => ['required','string','max:255', Rule::unique('government_entities','uuid')->ignore($government_entity->id)],
            'name' => 'required|string|max:255',
            'classification' => 'required|string',
        ]);

        $government_entity->update($request->only('uuid','name','classification'));

        return redirect()->route('government_entities.index')->with('success','Entity updated successfully.');
    }

    public function destroy(GovernmentEntity $government_entity)
    {
        if ($government_entity->assignments()->count() > 0) {
            return redirect()->route('government_entities.index')
                ->with('error', 'This government entity cannot be deleted because it is associated with assignments.');
        }

        $government_entity->delete();

        return redirect()->route('government_entities.index')
            ->with('success','Government entity deleted successfully.');
    }
}
