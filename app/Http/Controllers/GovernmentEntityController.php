<?php

namespace App\Http\Controllers;

use App\Enums\GovernmentEntityClassification;
use App\Models\GovernmentEntity;
use Illuminate\Http\Request;

class GovernmentEntityController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read government_entities')->only('index');
        $this->middleware('permission:create government_entities')->only(['create', 'store']);
        $this->middleware('permission:update government_entities')->only(['edit', 'update']);
        $this->middleware('permission:delete government_entities')->only('destroy');
    }

    public function index()
    {
        $entities = GovernmentEntity::latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'classification' => 'required|string',
        ]);

        GovernmentEntity::create($request->only('name','classification'));

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
            'name' => 'required|string|max:255',
            'classification' => 'required|string',
        ]);

        $government_entity->update($request->only('name','classification'));

        return redirect()->route('government_entities.index')->with('success','Entity updated successfully.');
    }

    public function destroy(GovernmentEntity $entity)
    {
        if ($entity->assignments()->count() > 0) {
            return redirect()->route('government_entities.index')
                ->with('error', 'This government entity cannot be deleted because it is associated with assignments.');
        }

        $entity->delete();

        return redirect()->route('government_entities.index')
            ->with('success','Government entity deleted successfully.');
    }
}
