<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    // Show all events
    public function index(Request $request)
    {
        $events = Event::with(['categories', 'user'])
            ->when($request->search, function($query) use ($request) {
                return $query->where('title', 'like', '%'.$request->search.'%');
            })
            ->when($request->category, function($query) use ($request) {
                return $query->whereHas('categories', function($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            })
            ->upcoming()
            ->paginate(10);

        $categories = Category::all();
        
        return view('events.index', compact('events', 'categories'));
    }

    // Show single event
    public function show(Event $event)
    {
        $event->load(['categories', 'user', 'attendees']);
        $isAttending = auth()->check() && $event->attendees->contains(auth()->id());
        
        // Track viewed event in session
        session()->push('recent_events', $event->id);
        
        return view('events.show', compact('event', 'isAttending'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    // Store new event
    public function store(StoreEventRequest $request)
    {
        $event = auth()->user()->events()->create($request->validated());
        
        if ($request->has('categories')) {
            $event->categories()->attach($request->categories);
        }
        
        return redirect()->route('events.show', $event)
                         ->with('success', 'Event created successfully!');
    }

    // Show edit form
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        
        $categories = Category::all();
        $selectedCategories = $event->categories->pluck('id')->toArray();
        
        return view('events.edit', compact('event', 'categories', 'selectedCategories'));
    }

    // Update event
    public function update(UpdateEventRequest $request, Event $event)
    {
        Gate::authorize('update', $event);
        
        $event->update($request->validated());
        
        $event->categories()->sync($request->categories ?? []);
        
        return redirect()->route('events.show', $event)
                         ->with('success', 'Event updated successfully!');
    }

    // Delete event
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);
        
        $event->delete();
        
        return redirect()->route('events.index')
                         ->with('success', 'Event deleted successfully!');
    }

    
}
