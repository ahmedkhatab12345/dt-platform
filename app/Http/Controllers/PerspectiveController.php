<?php

namespace App\Http\Controllers;

use App\Models\Perspective;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerspectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read perspectives')->only('index');
        $this->middleware('permission:create perspectives')->only(['create', 'store']);
        $this->middleware('permission:update perspectives')->only(['edit', 'update']);
        $this->middleware('permission:delete perspectives')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Perspective::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $perspectives = $query->orderBy('created_at','desc')->paginate(10);

        return view('perspectives.index', compact('perspectives'));
    }

    public function create()
    {
        return view('perspectives.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'uuid' => 'required|string|max:255|unique:perspectives,uuid',
            'name' => 'required|string|max:255|unique:perspectives,name',
            'description' => 'nullable|string',
        ]);

        Perspective::create($r->only('uuid','name','description'));

        return redirect()->route('perspectives.index')->with('success','Created.');
    }

    public function edit(Perspective $perspective)
    {
        return view('perspectives.edit', compact('perspective'));
    }

    public function update(Request $r, Perspective $perspective)
    {
        $r->validate([
            'uuid' => ['required','string','max:255', Rule::unique('perspectives','uuid')->ignore($perspective->id)],
            'name' => ['required','string','max:255', Rule::unique('perspectives','name')->ignore($perspective->id)],
            'description' => 'nullable|string',
        ]);

        $perspective->update($r->only('uuid','name','description'));

        return redirect()->route('perspectives.index')->with('success','Updated.');
    }

    public function destroy(Perspective $perspective)
    {
        if ($perspective->pillars()->count() > 0) {
            return redirect()->route('perspectives.index')
                ->with('error', 'This perspective cannot be deleted because it is associated with pillars.');
        }

        $perspective->delete();
        return redirect()->route('perspectives.index')->with('success','Perspective deleted successfully.');
    }
}
