<x-app-layout>
    <div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <div class="flex items-center mb-6">
            <div class="flex-1 border-t-2 border-red-600"></div>
            <div class="w-8 h-8 bg-red-600 text-white font-bold text-center leading-8 rounded-full ml-2">1</div>
            <span class="ml-4 text-gray-600">Pick an event</span>
            <div class="flex-1 border-t-2 border-red-600 ml-6"></div>
            <div class="w-8 h-8 bg-red-600 text-white font-bold text-center leading-8 rounded-full ml-2">2</div>
            <span class="ml-4 font-bold text-red-600">Make Payment</span>
            <div class="flex-1 border-t-2 border-gray-300 ml-6"></div>
            <div class="w-8 h-8 bg-gray-300 text-gray-600 font-bold text-center leading-8 rounded-full ml-2">3</div>
            <span class="ml-4 text-gray-600">Confirmation</span>
        </div>

        <h2 class="text-2xl font-bold mb-4">Payment for {{ $event->name }}</h2>

        @if (session('ticketDetails'))
            <table class="table-auto w-full border border-gray-300 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-2 px-4">Name</th>
                        <th class="text-left py-2 px-4">Phone Number</th>
                        <th class="text-left py-2 px-4">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">{{ session('ticketDetails.name', 'N/A') }}</td>
                        <td class="py-2 px-4 border-b">{{ session('ticketDetails.phone_number', 'N/A') }}</td>
                        <td class="py-2 px-4 border-b">{{ session('ticketDetails.email', 'N/A') }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No ticket details found.</p>
        @endif

        <table class="table-auto w-full border border-gray-300 mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left py-2 px-4">Ticket Type</th>
                    <th class="text-center py-2 px-4">Quantity</th>
                    <th class="text-center py-2 px-4">Price</th>
                    <th class="text-right py-2 px-4">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ticketDetails as $ticketTypeId => $details)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $event->ticketTypes->find($ticketTypeId)->name }}</td>
                        <td class="text-center py-2 px-4 border-b">{{ $details['quantity'] }}</td>
                        <td class="text-center py-2 px-4 border-b">KES {{ number_format($details['price'], 2) }}</td>
                        <td class="text-right py-2 px-4 border-b">KES {{ number_format($details['quantity'] * $details['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="text-lg font-bold text-right mb-4">Total Amount: KES {{ number_format($grandTotal, 2) }}</h3>

        <form action="{{ route('payment.initiatePayment') }}" method="POST" class="border-t pt-4">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Provide your Mpesa [KE] Mobile Number</label>
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden mb-4">
                <span class="bg-gray-100 px-4 py-2 text-gray-600">+254</span>
                <input type="text" id="phone" name="phone" class="flex-1 px-4 py-2 focus:outline-none" placeholder="740XXXXXX" required>
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Proceed
            </button>
        </form>
    </div>
</x-app-layout>
