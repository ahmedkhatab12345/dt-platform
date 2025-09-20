<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $tools = $query->latest()->paginate(10);

        return view('tools.index', compact('tools'));
    }
    
    public function create()
    {
        return view('tools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        Tool::create($request->all());

        return redirect()->route('tools.index')->with('success', 'Tool created successfully.');
    }

    public function edit(Tool $tool)
    {
        return view('tools.edit', compact('tool'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        $tool->update($request->all());

        return redirect()->route('tools.index')->with('success', 'Tool updated successfully.');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->assignments()->count() > 0) {
            return redirect()->route('tools.index')
                ->with('error', 'This tool cannot be deleted because it is associated with assignments.');
        }

        $tool->delete();
        return redirect()->route('tools.index')->with('success','Tool deleted successfully.');
    }
}
