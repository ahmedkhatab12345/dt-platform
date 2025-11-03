<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pillar;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StandardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read standards')->only('index');
        $this->middleware('permission:create standards')->only(['create', 'store']);
        $this->middleware('permission:update standards')->only(['edit', 'update']);
        $this->middleware('permission:delete standards')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Standard::with('pillar.perspective');

        if ($request->filled('pillar_id')) {
            $query->where('pillar_id', $request->pillar_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $standards = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('standards.index', compact('standards'));
    }

    public function create()
    {
        $pillars = Pillar::with('perspective')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('standards.create', compact('pillars'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'uuid'      => 'required|string|max:255|unique:standards,uuid',
            'pillar_id' => 'required|exists:pillars,id',
            'name'      => 'required|string|max:255',
            'criteria'  => 'nullable|string',
            'weight'    => 'nullable|numeric|min:0|max:100',
        ]);

        Standard::create($r->only('uuid', 'pillar_id', 'name', 'criteria', 'weight'));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'standards',
            'description' => 'تم إنشاء معيار جديد باسم ' . $r->name,
        ]);

        return redirect()->route('standards.index')->with('success','Created.');
    }

    public function edit(Standard $standard)
    {
        $pillars = Pillar::with('perspective')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('standards.edit', compact('standard','pillars'));
    }

    public function update(Request $r, Standard $standard)
    {
        $r->validate([
            'uuid'      => ['required','string','max:255', Rule::unique('standards','uuid')->ignore($standard->id)],
            'pillar_id' => 'required|exists:pillars,id',
            'name'      => 'required|string|max:255',
            'criteria'  => 'nullable|string',
            'weight'    => 'nullable|numeric|min:0|max:100',
        ]);

        $standard->update($r->only('uuid', 'pillar_id', 'name', 'criteria', 'weight'));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'standards',
            'description' => 'تم تعديل معيار باسم ' . $standard->name,
        ]);

        return redirect()->route('standards.index')->with('success','Updated.');
    }

    public function destroy(Standard $standard)
    {
        if ($standard->assignments()->count() > 0) {
            return redirect()->route('standards.index')
                ->with('error', 'This standard cannot be deleted because it is associated with assignments.');
        }

        $standard->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'standards',
            'description' => 'تم حذف معيار باسم ' . $standard->name,
        ]);
        return redirect()->route('standards.index')->with('success','Standard deleted successfully.');
    }
}
