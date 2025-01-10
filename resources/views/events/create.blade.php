<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
            <a href="{{ url('events') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded float-right">Back</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Event Name</label>
                            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg" value="{{ old('name') }}">
                            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-gray-700">Date</label>
                            <input type="date" name="date" id="date" class="w-full border-gray-300 rounded-lg" value="{{ old('date') }}">
                            @error('date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="start_time" class="block text-gray-700">Start Time</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="w-full border-gray-300 rounded-lg" value="{{ old('start_time') }}">
                            @error('start_time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="end_time" class="block text-gray-700">End Time</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="w-full border-gray-300 rounded-lg" value="{{ old('end_time') }}">
                            @error('end_time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-gray-700">Location</label>
                            <input type="text" name="location" id="location" class="w-full border-gray-300 rounded-lg" value="{{ old('location') }}">
                            @error('location') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Image</label>
                            <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-lg">
                            @error('image') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Ticket Types</label>
                            <div id="ticket-types-container">
                                <div class="ticket-type mb-4">
                                    <input type="text" name="ticket_types[][name]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Ticket Type Name" required>
                                    <input type="number" name="ticket_types[][price]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Price" required>
                                    <input type="checkbox" name="ticket_types[][complimentary]" class="mr-2" value="1"> Complimentary
                                    <input type="checkbox" name="ticket_types[][active]" class="mr-2" value="1" checked> Active
                                    <select name="ticket_types[][user_id]" class="w-full border-gray-300 rounded-lg mb-2" required>
                                        <option value="">Select User</option>
                                        <!-- Assuming you have a list of users available in the blade file -->
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="remove-ticket-type bg-red-500 text-white font-bold py-2 px-3 rounded mt-2">Remove</button>
                                </div>
                            </div>
                            <button type="button" id="add-ticket-type" class="bg-green-500 text-white font-bold py-2 px-3 rounded mt-4">Add Ticket Type</button>
                            @error('ticket_types') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ticket-type').addEventListener('click', function () {
            const container = document.getElementById('ticket-types-container');
            const ticketTypeHTML = `
                <div class="ticket-type mb-4">
                    <input type="text" name="ticket_types[][name]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Ticket Type Name" required>
                    <input type="number" name="ticket_types[][price]" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Price" required>
                    <input type="checkbox" name="ticket_types[][complimentary]" class="mr-2" value="1"> Complimentary
                    <input type="checkbox" name="ticket_types[][active]" class="mr-2" value="1" checked> Active
                    <select name="ticket_types[][user_id]" class="w-full border-gray-300 rounded-lg mb-2" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="remove-ticket-type bg-red-500 text-white font-bold py-2 px-3 rounded mt-2">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', ticketTypeHTML);
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-ticket-type')) {
                e.target.closest('.ticket-type').remove();
            }
        });
    </script>
</x-app-layout>
