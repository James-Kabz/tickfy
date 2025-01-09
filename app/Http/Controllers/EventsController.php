<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('events.index', ['events' => $events]);
    }

    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('events.show', ['event' => $event]);
    }

    public function create(Event $job)
    {
        $events = Event::all();

        return view('events.create', ['events' => $events]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required',
            'image' => 'required|nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $event = new Event($data);
        $event->user_id = Auth::id();
        $event->save();


        // notify user about a new event
        // $event->user->notify(new \App\Notifications\EventCreated($event));
        
        return redirect('events')->with('success', 'Event created successfully');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request, $id)
    {

        $event = Event::findOrFail($id);
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        } else {
            $data['image'] = $event->image;
        }


        $event->update($data);
        return redirect('events')->with('success', 'Event updated successfully');
    }


    public function destroy($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->delete();
        return redirect('events')->with('success', 'Event deleted successfully');
    }
}
