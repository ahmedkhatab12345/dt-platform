<?php

namespace App\Http\Controllers\Innovation;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GovernmentEntity;
use App\Models\InnovationPartnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PartnershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('read partnerships');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        $partnerships = InnovationPartnership::query()
            ->with(['governmentEntity', 'user'])
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where(function ($qq) use ($term) {
                    $qq->where('agreement_title', 'like', "%{$term}%")
                       ->orWhere('details', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('government_entity_id'),
                fn ($q) => $q->where('government_entity_id', $request->integer('government_entity_id'))
            )
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('innovation.partnerships.index', [
            'partnerships' => $partnerships,
            'entities' => $entities,
            'selectedGovernmentEntityId' => $request->integer('government_entity_id'),
            'q' => $request->input('q'),
        ]);
    }

    public function create()
    {
        Gate::authorize('create partnerships');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.partnerships.create', compact('entities'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create partnerships');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'agreement_title' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $row = InnovationPartnership::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'innovation_partnerships',
            'description' => 'تم إنشاء شراكة/اتفاقية: ' . $row->agreement_title,
        ]);

        return redirect()->route('innovation.partnerships.index')
            ->with('success', 'تم إضافة الشراكة/الاتفاقية بنجاح');
    }

    public function edit(InnovationPartnership $partnership)
    {
        Gate::authorize('update partnerships');

        $user = auth()->user();
        if ($user->category_id != 6 && $partnership->created_by != $user->id) {
            abort(403);
        }

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.partnerships.edit', compact('partnership', 'entities'));
    }

    public function update(Request $request, InnovationPartnership $partnership)
    {
        Gate::authorize('update partnerships');

        $user = auth()->user();
        if ($user->category_id != 6 && $partnership->created_by != $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'agreement_title' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $partnership->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'innovation_partnerships',
            'description' => 'تم تعديل شراكة/اتفاقية: ' . $partnership->agreement_title,
        ]);

        return redirect()->route('innovation.partnerships.index')
            ->with('success', 'تم تحديث الشراكة/الاتفاقية بنجاح');
    }

    public function destroy(InnovationPartnership $partnership)
    {
        Gate::authorize('delete partnerships');

        $user = auth()->user();
        if ($user->category_id != 6 && $partnership->created_by != $user->id) {
            abort(403);
        }

        $title = $partnership->agreement_title;
        $partnership->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'innovation_partnerships',
            'description' => 'تم حذف شراكة/اتفاقية: ' . $title,
        ]);

        return redirect()->route('innovation.partnerships.index')
            ->with('success', 'تم حذف الشراكة/الاتفاقية بنجاح');
    }
}
