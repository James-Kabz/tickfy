<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Event;
use Illuminate\Support\Str;

class TicketsController extends Controller
{

    /**
     * Display a list of all events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all events
        $events = Event::all();
        return view('tickets.index', ['events' => $events]);
    }

    /**
     * Show all tickets purchased for a specific event.
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        // Fetch the tickets for this event
        $tickets = $event->tickets; // Assuming `tickets` is a defined relationship in your `Event` model

        // Pass the event and tickets to the view
        return view(
            'tickets.show',
            [
                'event' => $event,
                'tickets' => $tickets
            ]
        );
    }

    public function view($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $eventName = $ticket->event->name;
        return view('ticket.confirmation', compact('ticket', 'eventName'));
    }

    /**
     * Store a newly created ticket booking in storage.
     */
    public function store(Request $request, $eventId)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'ticket_types' => 'required|array',
            'ticket_types.*.quantity' => 'required|integer|min:0',
        ]);

        // Fetch the event
        $event = Event::findOrFail($eventId);

        // Check if tickets are still open
        if ($event->ticket_status !== 'Open') {
            return redirect()->back()->with('error', 'Tickets are no longer available for this event.');
        }

        // Loop through ticket types and save tickets
        foreach ($request->ticket_types as $typeId => $ticketData) {
            $ticketType = TicketType::find($typeId);

            if ($ticketType && $ticketData['quantity'] > 0) {
                // Generate a unique transaction ID for the booking
                $transactionId = Str::uuid();

                // Save tickets for each ticket type
                for ($i = 0; $i < $ticketData['quantity']; $i++) {
                    Ticket::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'price' => $ticketType->price,
                        'ticket_type_id' => $ticketType->id,
                        'transaction_id' => $transactionId,
                        'quantity' => 1, // Each ticket is counted individually
                        'scanned' => false,
                        'event_id' => $event->id,
                    ]);
                }
            }
        }

        // Redirect to the payment.show route
        return redirect()->route('payment.show', ['event' => $eventId])->with('success', 'Your tickets have been successfully booked!');
    }

}
