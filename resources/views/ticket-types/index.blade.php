<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket Types
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">All Ticket Types</h3>
                    <!-- Button to Create Ticket Type -->
                    <a href="{{ route('ticket-types.create') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Create Ticket Type
                    </a>
                </div>

                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="py-2 px-4 text-left text-gray-700">#</th>
                            <th class="py-2 px-4 text-left text-gray-700">Name</th>
                            <th class="py-2 px-4 text-left text-gray-700">Price</th>
                            <th class="py-2 px-4 text-left text-gray-700">Complimentary</th>
                            <th class="py-2 px-4 text-left text-gray-700">Active</th>
                            <th class="py-2 px-4 text-left text-gray-700">Event</th>
                            <th class="py-2 px-4 text-left text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketTypes as $ticketType)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4">{{ $ticketType->name }}</td>
                                <td class="py-2 px-4">{{ $ticketType->price }}</td>
                                <td class="py-2 px-4">
                                    {{ $ticketType->complimentary ? 'Yes' : 'No' }}
                                </td>
                                <td class="py-2 px-4">
                                    {{ $ticketType->active ? 'Yes' : 'No' }}
                                </td>
                                <td class="py-2 px-4">{{ $ticketType->event->name ?? 'N/A' }}</td>
                                <td class="py-2 px-4 flex space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('ticket-types.edit', $ticketType->id) }}"
                                       class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                        Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('ticket-types.destroy', $ticketType->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                                                onclick="return confirm('Are you sure you want to delete this ticket type?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $ticketTypes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
