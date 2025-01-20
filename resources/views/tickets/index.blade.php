<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="container max-w-7xl mx-auto mt-6 px-4">
        <h3 class="text-xl font-semibold mb-4">Events List</h3>
        <table class="table-auto w-full border border-gray-300 mb-4">
            <thead>
                <tr>
                    <th class="text-left py-2 px-4 border-b">Event Name</th>
                    <th class="text-center py-2 px-4 border-b">Date</th>
                    <th class="text-center py-2 px-4 border-b">Tickets Sold</th>
                    <th class="text-center py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $event->name }}</td>
                        <td class="text-center py-2 px-4 border-b">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
                        <td class="text-center py-2 px-4 border-b">{{ $event->tickets->count() }}</td>
                        <td class="text-center py-2 px-4 border-b">
                            <a href="{{ route('events.show', $event->id) }}" class="text-blue-500">View Tickets</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
