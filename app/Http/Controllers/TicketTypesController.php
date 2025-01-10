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

    public function create()
    {
        $events = Event::all();
        $users = User::all();
        return view('ticket-types.create', ['events' => $events], ['users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'complimentary' => 'required|boolean',
            'active' => 'required|boolean',
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        TicketType::create($data);

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
