<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Ticket Types for Event: {{ $event->name }}
            <a href="{{ route('events.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('events.ticket-types.store', $event->id) }}" method="POST">
                        @csrf
                        <div id="ticket-types-container">
                            <div class="ticket-type mb-4">
                                <input type="text" name="ticket_types[0][name]"
                                    class="w-full border-gray-300 rounded-lg mb-2" placeholder="Ticket Type Name"
                                    required>
                                @error('ticket_types.*.name')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <input type="number" name="ticket_types[0][price]"
                                    class="w-full border-gray-300 rounded-lg mb-2" placeholder="Price" required>
                                @error('ticket_types.*.price')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <input type="checkbox" name="ticket_types[0][complimentary]" class="mr-2"
                                    value="1"> Complimentary
                                @error('ticket_types.*.complimentary')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <input type="checkbox" name="ticket_types[0][active]" class="mr-2" value="1"
                                    checked> Active
                                @error('ticket_types.*.active')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <select name="ticket_types[0][user_id]" class="w-full border-gray-300 rounded-lg mb-2"
                                    required>
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('ticket_types.*.user_id')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <button type="button"
                                    class="remove-ticket-type bg-red-500 text-white font-bold py-2 px-3 rounded mt-2">Remove</button>
                            </div>
                        </div>
                        <button type="button" id="add-ticket-type"
                            class="bg-green-500 text-white font-bold py-2 px-3 rounded mt-4">Add Ticket Type</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save Ticket
                            Types</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ticket-type').addEventListener('click', function() {
            const container = document.getElementById('ticket-types-container');
            const ticketTypeCount = container.children.length;

            const ticketTypeHTML = `
                <div class="ticket-type mb-4">
                    <input type="text" name="ticket_types[${ticketTypeCount}][name]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Ticket Type Name" required>
                    <input type="number" name="ticket_types[${ticketTypeCount}][price]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Price" required>
                    <input type="checkbox" name="ticket_types[${ticketTypeCount}][complimentary]" class="mr-2" value="1"> Complimentary
                    <input type="checkbox" name="ticket_types[${ticketTypeCount}][active]" class="mr-2" value="1" checked> Active
                    <select name="ticket_types[${ticketTypeCount}][user_id]" class="w-full border-gray-300 rounded-lg mb-2" required>
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="remove-ticket-type bg-red-500 text-white font-bold py-2 px-3 rounded mt-2">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', ticketTypeHTML);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-ticket-type')) {
                e.target.closest('.ticket-type').remove();
            }
        });
    </script>
</x-app-layout>
