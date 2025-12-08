<?php

namespace App\Http\Controllers\Innovation;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GovernmentEntity;
use App\Models\InnovationPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlatformsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('read platforms');

        $entities = GovernmentEntity::orderBy('name')->get();

        $platforms = InnovationPlatform::query()
            ->with(['governmentEntity', 'user'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where(function ($qq) use ($term) {
                    $qq->where('platform_name', 'like', "%{$term}%")
                    ->orWhere('platform_function', 'like', "%{$term}%")
                    ->orWhere('platform_url', 'like', "%{$term}%");
                });
            })
            ->when(
                $request->filled('government_entity_id'),
                fn ($q) => $q->where('government_entity_id', $request->integer('government_entity_id'))
            )
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('innovation.platforms.index', [
            'platforms' => $platforms,
            'entities' => $entities,
            'selectedGovernmentEntityId' => $request->integer('government_entity_id'),
            'q' => $request->input('q'),
        ]);
    }

    public function create()
    {
        Gate::authorize('create platforms');

        $entities = GovernmentEntity::orderBy('name')->get();

        return view('innovation.platforms.create', compact('entities'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create platforms');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'platform_name' => 'required|string|max:255',
            'platform_function' => 'nullable|string|max:255',
            'platform_url' => 'nullable|string|max:2048',
            'details' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $platform = InnovationPlatform::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'innovation_platforms',
            'description' => 'تم إنشاء منصة: ' . $platform->platform_name,
        ]);

        return redirect()->route('innovation.platforms.index')
            ->with('success', 'تم إضافة المنصة بنجاح');
    }

    public function edit(InnovationPlatform $platform)
    {
        Gate::authorize('update platforms');
    
        $entities = GovernmentEntity::orderBy('name')->get();
    
        return view('innovation.platforms.edit', compact('platform', 'entities'));
    }    

    public function update(Request $request, InnovationPlatform $platform)
    {
        Gate::authorize('update platforms');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'platform_name' => 'required|string|max:255',
            'platform_function' => 'nullable|string|max:255',
            'platform_url' => 'nullable|string|max:2048',
            'details' => 'nullable|string',
        ]);

        $platform->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'innovation_platforms',
            'description' => 'تم تعديل منصة: ' . $platform->platform_name,
        ]);

        return redirect()->route('innovation.platforms.index')
            ->with('success', 'تم تحديث المنصة بنجاح');
    }

    public function destroy(InnovationPlatform $platform)
    {
        Gate::authorize('delete platforms');

        $name = $platform->platform_name;
        $platform->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'innovation_platforms',
            'description' => 'تم حذف منصة: ' . $name,
        ]);

        return redirect()->route('innovation.platforms.index')
            ->with('success', 'تم حذف المنصة بنجاح');
    }
}
