<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\Request;

class TicketTypesController extends Controller
{

    public function index()
    {
        $ticketTypes = TicketType::paginate(10);
        // $ticketTypes = TicketType::where('active', 1)->orderBy('name')->paginate(10);
        return view('ticket-types.index', ['ticketTypes' => $ticketTypes]);
    }

    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $users = User::all();
        return view('ticket-types.create', ['event' => $event], ['users' => $users]);
    }

    public function store(Request $request,$eventId)
    {
        $event = Event::findOrFail($eventId);

        $data = $request->validate([
            'ticket_types.*.name' => 'required|string',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.complimentary' => '
            |boolean',
            'ticket_types.*.active' => 'required|boolean',
            'ticket_types.*.user_id' => 'required|exists:users,id',
        ]);

        foreach ($data['ticket_types'] as $ticketType) {
            $event->ticketTypes()->create($ticketType);
        }


        return redirect('ticket-types')->with('success', 'Ticket type created successfully.');
    }

    public function edit(TicketType $ticketType)
    {
        $events = Event::all();
        $users = User::all();
        return view(
            'ticket-types.edit',
            [
                'events' => $events,
                'ticketType' => $ticketType,
                'users' => $users
            ]
        );
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'complimentary' => 'required|boolean',
            'active' => 'required|boolean',
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ticketType->update($data);

        return redirect('ticket-types')->with('success', 'Ticket Type updated successfully');
    }

    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();

        return redirect('ticket-types')->with('success', 'Ticket Type deleted successfully');
    }
}
