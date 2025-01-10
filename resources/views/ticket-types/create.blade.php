<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Ticket Type
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('ticket-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" id="name" name="name" 
                            class="w-full border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Price</label>
                        <input type="number" id="price" name="price" 
                            class="w-full border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="complimentary" class="block text-gray-700">Complimentary</label>
                        <select id="complimentary" name="complimentary" 
                            class="w-full border-gray-300 rounded-lg" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="active" class="block text-gray-700">Active</label>
                        <select id="active" name="active" 
                            class="w-full border-gray-300 rounded-lg" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700">User</label>
                        <select id="user_id" name="user_id" 
                            class="w-full border-gray-300 rounded-lg" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="event_id" class="block text-gray-700">Event</label>
                        <select id="event_id" name="event_id" 
                            class="w-full border-gray-300 rounded-lg" required>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                        Create
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
