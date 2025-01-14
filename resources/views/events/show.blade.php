<x-app-layout>
    <x-slot name="header">
        <div class="p-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Events') }}
                <a href="{{ url('events') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">
                    Back
                </a>
            </h2>
        </div>
    </x-slot>

    <div class="container max-w-7xl mx-auto mt-6 px-4">
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-wrap md:flex-nowrap gap-6 bg-white shadow-md rounded-lg overflow-hidden p-6">
            <!-- Left: Event Details -->
            <div class="w-full md:w-1/2 flex flex-col">
                @if ($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="Event Banner"
                        class="w-full h-full object-cover rounded-lg shadow">
                @else
                    <div class="bg-gray-200 w-full h-64 flex items-center justify-center rounded-lg">
                        <p class="text-gray-500 text-lg font-semibold">No Image Available</p>
                    </div>
                @endif

                <p class="text-gray-500 text-3xl uppercase mt-4 mb-2">
                    {{ \Carbon\Carbon::parse($event->date)->format('d M') }}
                </p>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $event->name }}</h1>

                {{-- <p class="text-gray-600 text-lg mb-4">
                    By <span class="font-semibold">{{ $event->organizer_name }}</span>
                </p> --}}

                <h1 class="text-xl lg:text-2xlfont-bold text-gray-800 mb-2 underline">Location</h1>
                <p class="text-gray-700 text-lg flex items-center mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ $event->location }}
                </p>

                <div class="grid grid-cols-1">
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-2 underline">Dates</h1>
                    <p class="text-gray-700 text-lg flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y g:i A') }} -
                        {{ \Carbon\Carbon::parse($event->end_time)->format('F j, Y g:i A') }}
                    </p>
                </div>

                <div class="mt-4">
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-700 underline">Description</h2>
                    <p class="text-lg text-gray-600 whitespace-pre-line">
                        {{ $event->description }}
                    </p>
                </div>

                <p class="flex items-center space-x-2 mt-4">
                    <span class="font-bold">Status:</span>
                    @if ($event->ticket_status === 'Open')
                        <span class="px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                            Tickets Open
                        </span>
                    @elseif ($event->ticket_status === 'Closed')
                        <span class="px-3 py-1 text-sm font-semibold text-red-700 bg-red-100 rounded-full">
                            Tickets Closed
                        </span>
                    @elseif ($event->ticket_status === 'Ongoing')
                        <span class="px-3 py-1 text-sm font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                            Event Ongoing
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold text-gray-700 bg-gray-100 rounded-full">
                            Inactive
                        </span>
                    @endif
                </p>

                <!-- Actions -->
                <div class="flex flex-row space-x-5 mt-5">
                    @can('edit event')
                        <a href="{{ route('events.edit', ['event' => $event->id]) }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endcan

                    @can('delete event')
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>

            </div>

            <!-- Right: Ticket Booking Form -->
            @if ($event->ticket_status === 'Open')
                <div class="w-full md:w-1/2 bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4">Book Your Tickets</h2>
                    <form action="{{ route('payment.show', $event->id) }}" method="GET" id="bookingForm">
                        @csrf
                        <table class="table-auto w-full border border-gray-300 mb-4">
                            <thead>
                                <tr>
                                    <th class="text-left py-2 px-4 border-b">Ticket Type</th>
                                    <th class="text-center py-2 px-4 border-b">Price</th>
                                    <th class="text-center py-2 px-4 border-b">Quantity</th>
                                    <th class="text-right py-2 px-4 border-b">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->ticketTypes as $ticketType)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $ticketType->name }}</td>
                                        <td class="text-center py-2 px-4 border-b">
                                            {{ number_format($ticketType->price, 2) }}</td>
                                        <td class="text-center py-2 px-4 border-b">
                                            <input type="hidden" name="ticket_types[{{ $ticketType->id }}][price]"
                                                value="{{ $ticketType->price }}">
                                            <button type="button"
                                                class="quantity-decrease bg-gray-200 px-3 py-1 rounded-l">-</button>
                                            <input type="number" name="ticket_types[{{ $ticketType->id }}][quantity]"
                                                value="0" min="0"
                                                class="quantity-input w-12 text-center border rounded">
                                            <button type="button"
                                                class="quantity-increase bg-gray-200 px-3 py-1 rounded-r">+</button>
                                        </td>
                                        <td class="text-right py-2 px-4 border-b">
                                            <span class="ticket-total">0.00</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Total:</h3>
                            <h3 class="text-lg font-bold" id="grandTotal">KES 0.00</h3>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 w-full border-gray-300 rounded-lg" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                class="mt-1 w-full border-gray-300 rounded-lg" required>
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone
                                Number</label>
                            <input type="text" id="phone_number" name="phone_number"
                                class="mt-1 w-full border-gray-300 rounded-lg" required>
                        </div>

                        <input type="hidden" name="grand_total" id="grand_total_input" value="0">
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded w-full">
                            Book Now
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-6 bg-gray-100 p-4 text-center text-gray-600 rounded-lg">
                    Tickets are not available for this event.
                </div>
            @endif


        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const grandTotalElement = document.getElementById('grandTotal');

            function updateTotal() {
                let grandTotal = 0;

                quantityInputs.forEach(input => {
                    const row = input.closest('tr');

                    // Find the hidden price input inside the same row
                    const priceInput = row.querySelector('input[type="hidden"]');
                    const price = parseFloat(priceInput.value) || 0; // Ensure it's a valid number
                    const quantity = parseInt(input.value) || 0;
                    const ticketTotal = row.querySelector('.ticket-total');

                    // Debugging log
                    console.log('Price:', price, 'Quantity:', quantity);

                    if (isNaN(price) || isNaN(quantity)) {
                        ticketTotal.textContent = "0.00";
                        return; // Skip this row if the price or quantity is invalid
                    }

                    const rowTotal = price * quantity;
                    ticketTotal.textContent = rowTotal.toFixed(2);
                    grandTotal += rowTotal;
                });

                grandTotalElement.textContent = `KES ${grandTotal.toFixed(2)}`;
            }

            document.querySelectorAll('.quantity-increase').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    input.value = parseInt(input.value) + 1;
                    updateTotal();
                });
            });

            document.querySelectorAll('.quantity-decrease').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.nextElementSibling;
                    if (parseInt(input.value) > 0) {
                        input.value = parseInt(input.value) - 1;
                        updateTotal();
                    }
                });
            });

            quantityInputs.forEach(input => {
                input.addEventListener('input', updateTotal);
            });

            updateTotal();
        });
    </script>
</x-app-layout>
