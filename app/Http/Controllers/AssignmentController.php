<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Standard;
use App\Models\Tool;
use App\Models\GovernmentEntity;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read assignments')->only('index');
        $this->middleware('permission:create assignments')->only(['create','store']);
        $this->middleware('permission:update assignments')->only(['edit','update']);
        $this->middleware('permission:delete assignments')->only('destroy');
    }

    public function index()
    {
        $assignments = Assignment::with(['standard','tool','governmentEntity'])->paginate(10);
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $standards = Standard::all();
        $tools = Tool::all();
        $entities = GovernmentEntity::all();

        return view('assignments.create', compact('standards','tools','entities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.standard_id' => 'required|exists:standards,id',
            'assignments.*.tool_id' => 'required|exists:tools,id',
            'assignments.*.government_entity_id' => 'required|exists:government_entities,id',
        ]);

        foreach ($request->assignments as $data) {
            Assignment::create($data);
        }

        return redirect()->route('assignments.index')->with('success','Assignments saved successfully.');
    }

    public function edit(Assignment $assignment)
    {
        $standards = Standard::all();
        $tools = Tool::all();
        $entities = GovernmentEntity::all();

        return view('assignments.edit', compact('assignment','standards','tools','entities'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'standard_id' => 'required|exists:standards,id',
            'tool_id' => 'required|exists:tools,id',
            'government_entity_id' => 'required|exists:government_entities,id',
        ]);

        $assignment->update($request->only('standard_id','tool_id','government_entity_id'));

        return redirect()->route('assignments.index')->with('success','Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('assignments.index')->with('success','Assignment deleted successfully.');
    }
}
