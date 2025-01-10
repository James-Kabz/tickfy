<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Ticket Type
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('ticket-types.update', $ticketType->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" id="name" name="name" value="{{ $ticketType->name }}"
                            class="w-full border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Price</label>
                        <input type="number" id="price" name="price" value="{{ $ticketType->price }}"
                            class="w-full border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="complimentary" class="block text-gray-700">Complimentary</label>
                        <select id="complimentary" name="complimentary" 
                            class="w-full border-gray-300 rounded-lg" required>
                            <option value="1" {{ $ticketType->complimentary ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$ticketType->complimentary ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="active" class="block text-gray-700">Active</label>
                        <select id="active" name="active" 
                            class="w-full border-gray-300 rounded-lg" required>
                            <option value="1" {{ $ticketType->active ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$ticketType->active ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700">User</label>
                        <select id="user_id" name="user_id" 
                            class="w-full border-gray-300 rounded-lg" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ $ticketType->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="event_id" class="block text-gray-700">Event</label>
                        <select id="event_id" name="event_id" 
                            class="w-full border-gray-300 rounded-lg" required>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" 
                                    {{ $ticketType->event_id == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
