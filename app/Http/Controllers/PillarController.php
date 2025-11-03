<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Perspective;
use App\Models\Pillar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PillarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read pillars')->only('index');
        $this->middleware('permission:create pillars')->only(['create', 'store']);
        $this->middleware('permission:update pillars')->only(['edit', 'update']);
        $this->middleware('permission:delete pillars')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Pillar::with('perspective');

        if ($request->filled('perspective_id')) {
            $query->where('perspective_id', $request->perspective_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $pillars = $query->orderBy('created_at','desc')->paginate(10);

        return view('pillars.index', compact('pillars'));
    }

    public function create()
    {
        $perspectives = Perspective::orderBy('created_at','desc')->get();
        return view('pillars.create', compact('perspectives'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'uuid' => 'required|string|max:255|unique:pillars,uuid',
            'perspective_id' => 'required|exists:perspectives,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Pillar::create($r->only('uuid','perspective_id','name','description'));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'pillars',
            'description' => 'تم إنشاء محور جديد باسم ' . $r->name,
        ]);


        return redirect()->route('pillars.index')->with('success','Created.');
    }

    public function edit(Pillar $pillar)
    {
        $perspectives = Perspective::orderBy('created_at','desc')->get();
        return view('pillars.edit', compact('pillar','perspectives'));
    }

    public function update(Request $r, Pillar $pillar)
    {
        $r->validate([
            'uuid' => ['required','string','max:255', Rule::unique('pillars','uuid')->ignore($pillar->id)],
            'perspective_id' => 'required|exists:perspectives,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $pillar->update($r->only('uuid','perspective_id','name','description'));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'pillars',
            'description' => 'تم تعديل  محور باسم ' . $pillar->name,
        ]);

        return redirect()->route('pillars.index')->with('success','Updated.');
    }

    public function destroy(Pillar $pillar)
    {
        if ($pillar->standards()->count() > 0) {
            return redirect()->route('pillars.index')
                ->with('error', 'This pillar cannot be deleted because it is associated with standards.');
        }

        $pillar->delete();
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'pillars',
            'description' => 'تم حذف محور باسم ' . $pillar->name,
        ]);
        return redirect()->route('pillars.index')->with('success','Pillar deleted successfully.');
    }
}
