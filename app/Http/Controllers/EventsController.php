<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
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
            'ticket_types.*.name' => 'required|string',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.complimentary' => 'required|boolean',
            'ticket_types.*.active' => 'required|boolean',
            'ticket_types.*.user_id' => 'required|exists:users,id',
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

        // Create ticket types dynamically
        if ($request->has('ticket_types')) {
            foreach ($request->ticket_types as $ticketType) {
                $event->ticketTypes()->create(array_merge($ticketType, ['event_id' => $event->id]));
            }
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully');
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
