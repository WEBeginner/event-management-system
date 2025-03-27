<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
class AttendeeController extends Controller
{
    // Join an event
    public function store(Event $event)
    {
        if ($event->isFull()) {
            return back()->with('error', 'This event has reached capacity!');
        }

        Auth::user()->attendingEvents()->syncWithoutDetaching([
            $event->id => ['status' => 'registered']
        ]);

        return back()->with('success', 'You have successfully registered for this event!');
    }

    // Leave an event
    public function destroy(Event $event)
    {
        Auth::user()->attendingEvents()->detach($event->id);
        
        return back()->with('success', 'You have cancelled your registration.');
    }
}
