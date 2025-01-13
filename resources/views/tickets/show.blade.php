<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets for Event: ' . $event->name) }}
            <a href="{{ url()->previous() }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">
                Back
            </a>

        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 px-4">
        <!-- Event Details -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-800">Event Details</h3>
                <hr class="my-4">
                <p><strong>Name:</strong> {{ $event->name }}</p>
                <p><strong>Location:</strong> {{ $event->location }}</p>
                <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y g:i A') }}
                </p>
                @if ($event->image)
                    <div class="mt-4">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image"
                            class="w-96 rounded-xl mx-auto">
                    </div>
                @endif
            </div>
        </div>

        <!-- Tickets Purchased -->
        <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-800">Purchased Tickets</h3>
                <hr class="my-4">

                @if ($tickets->isEmpty())
                    <p class="text-gray-600">No tickets have been purchased for this event yet.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-gray-800">Ticket Type</th>
                                <th class="px-4 py-2 text-left text-gray-800">Name</th>
                                <th class="px-4 py-2 text-left text-gray-800">Phone Number</th>
                                <th class="px-4 py-2 text-left text-gray-800">Email</th>
                                <th class="px-4 py-2 text-left text-gray-800">Quantity</th>
                                <th class="px-4 py-2 text-left text-gray-800">Transaction ID</th>
                                <th class="px-4 py-2 text-left text-gray-800">Scanned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td class="px-4 py-2 border-t">{{ $ticket->ticketType->name }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->name }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->phone_number }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->email }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->quantity }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->transaction_id }}</td>
                                    <td class="px-4 py-2 border-t">{{ $ticket->scanned ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
