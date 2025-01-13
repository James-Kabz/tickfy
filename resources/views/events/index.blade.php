<x-app-layout>
    <x-slot name="header">
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-between ">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                {{ __('Events') }}
            </h2>
            <form action="{{ route('events.search') }}" method="GET" class="mb-3 lg:mb-0">
                <input type="text" name="find"
                    class="py-2 px-6 text-gray-900 font-bold border rounded-lg w-48 lg:w-64" placeholder="Search by Name">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-lg">Search</button>
            </form>
            @can('create event')
                <button class="">
                    <a href="{{ route('events.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Event</a>
                </button>
            @endcan
        </div>
    </x-slot>
    <style>
        .transition {
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
    </style>

    <!-- Events Section -->
    <main class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Upcoming Events</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        @if ($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}"
                                class="w-full h-48 object-cover">
                        @else
                            <img src="https://via.placeholder.com/300x200" alt="{{ $event->name }}"
                                class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $event->date }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                            </p>
                            <p class="text-gray-700 mt-3">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                            <div class="mt-4 flex justify-between">
                                <a href="{{ route('events.show', $event->id) }}"
                                    class=" bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                    Learn More
                                </a>
                                @can('view tickets')
                                    {{-- <div class="p-4 text-center"> --}}
                                        <a href="{{ route('tickets.show', $event->id) }}"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                            View Purchased Tickets
                                        </a>
                                    {{-- </div> --}}
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No events found.</p>
                @endforelse

            </div>
        </div>
    </main>
</x-app-layout>
