<?php

namespace App\Http\Controllers\Innovation;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GovernmentEntity;
use App\Models\InnovationFramework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FrameworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('read frameworks');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        $frameworks = InnovationFramework::query()
            ->with(['governmentEntity', 'user'])
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where(function ($qq) use ($term) {
                    $qq->where('framework_name', 'like', "%{$term}%")
                       ->orWhere('details', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('government_entity_id'),
                fn ($q) => $q->where('government_entity_id', $request->integer('government_entity_id'))
            )
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('innovation.frameworks.index', [
            'frameworks' => $frameworks,
            'entities' => $entities,
            'selectedGovernmentEntityId' => $request->integer('government_entity_id'),
            'q' => $request->input('q'),
        ]);
    }

    public function create()
    {
        Gate::authorize('create frameworks');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.frameworks.create', compact('entities'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create frameworks');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'framework_name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $row = InnovationFramework::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'innovation_frameworks',
            'description' => 'تم إنشاء إطار/منهجية: ' . $row->framework_name,
        ]);

        return redirect()->route('innovation.frameworks.index')
            ->with('success', 'تم إضافة الإطار/المنهجية بنجاح');
    }

    public function edit(InnovationFramework $framework)
    {
        Gate::authorize('update frameworks');

        $user = auth()->user();
        if ($user->category_id != 6 && $framework->created_by != $user->id) {
            abort(403);
        }

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.frameworks.edit', compact('framework', 'entities'));
    }

    public function update(Request $request, InnovationFramework $framework)
    {
        Gate::authorize('update frameworks');

        $user = auth()->user();
        if ($user->category_id != 6 && $framework->created_by != $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'framework_name' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $framework->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'innovation_frameworks',
            'description' => 'تم تعديل إطار/منهجية: ' . $framework->framework_name,
        ]);

        return redirect()->route('innovation.frameworks.index')
            ->with('success', 'تم تحديث الإطار/المنهجية بنجاح');
    }

    public function destroy(InnovationFramework $framework)
    {
        Gate::authorize('delete frameworks');

        $user = auth()->user();
        if ($user->category_id != 6 && $framework->created_by != $user->id) {
            abort(403);
        }

        $title = $framework->framework_name;
        $framework->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'innovation_frameworks',
            'description' => 'تم حذف إطار/منهجية: ' . $title,
        ]);

        return redirect()->route('innovation.frameworks.index')
            ->with('success', 'تم حذف الإطار/المنهجية بنجاح');
    }
}
