<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function welcome()
    {
        $events = Event::orderBy('created_at', 'asc')->take(6)->get(); // Fetch the next 6 upcoming events
        return view('welcome', ['events' => $events]);
    }

    public function index()
    {
        $events = Event::with('ticketTypes')->orderBy('created_at', 'desc')->get();
        return view('events.index', ['events' => $events]);
    }

    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('events.show', ['event' => $event]);
    }

    public function create()
    {
        $events = Event::all();
        $users = User::all();
        return view(
            'events.create',
            [
                'events' => $events,
                'users' => $users
            ]
        );
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
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        // Create the event
        $event = Event::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'image' => $data['image'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('events.ticket-types.create', $event->id)
            ->with('success', 'Event created successfully. Now, add ticket types.');
    }

    public function edit($event)
    {
        $event = Event::findOrFail($event);
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
