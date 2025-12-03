<?php

namespace App\Http\Controllers\Innovation;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GovernmentEntity;
use App\Models\InnovationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('read events');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        $events = InnovationEvent::query()
            ->with(['governmentEntity', 'user'])
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->q);
                $q->where(function ($qq) use ($term) {
                    $qq->where('event_title', 'like', "%{$term}%")
                       ->orWhere('details', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('government_entity_id'),
                fn ($q) => $q->where('government_entity_id', $request->integer('government_entity_id'))
            )
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('innovation.events.index', [
            'events' => $events,
            'entities' => $entities,
            'selectedGovernmentEntityId' => $request->integer('government_entity_id'),
            'q' => $request->input('q'),
        ]);
    }

    public function create()
    {
        Gate::authorize('create events');

        $user = auth()->user();

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.events.create', compact('entities'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create events');

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'event_title' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $row = InnovationEvent::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => 'innovation_events',
            'description' => 'تم إنشاء فعالية/مبادرة: ' . $row->event_title,
        ]);

        return redirect()->route('innovation.events.index')
            ->with('success', 'تم إضافة الفعالية/المبادرة بنجاح');
    }

    public function edit(InnovationEvent $event)
    {
        Gate::authorize('update events');

        $user = auth()->user();
        if ($user->category_id != 6 && $event->created_by != $user->id) {
            abort(403);
        }

        $entities = GovernmentEntity::query()
            ->when($user->category_id != 6, fn ($q) => $q->where('created_by', $user->id))
            ->orderBy('name')
            ->get();

        return view('innovation.events.edit', compact('event', 'entities'));
    }

    public function update(Request $request, InnovationEvent $event)
    {
        Gate::authorize('update events');

        $user = auth()->user();
        if ($user->category_id != 6 && $event->created_by != $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'government_entity_id' => 'required|exists:government_entities,id',
            'event_title' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $event->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => 'innovation_events',
            'description' => 'تم تعديل فعالية/مبادرة: ' . $event->event_title,
        ]);

        return redirect()->route('innovation.events.index')
            ->with('success', 'تم تحديث الفعالية/المبادرة بنجاح');
    }

    public function destroy(InnovationEvent $event)
    {
        Gate::authorize('delete events');

        $user = auth()->user();
        if ($user->category_id != 6 && $event->created_by != $user->id) {
            abort(403);
        }

        $title = $event->event_title;
        $event->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model' => 'innovation_events',
            'description' => 'تم حذف فعالية/مبادرة: ' . $title,
        ]);

        return redirect()->route('innovation.events.index')
            ->with('success', 'تم حذف الفعالية/المبادرة بنجاح');
    }
}
