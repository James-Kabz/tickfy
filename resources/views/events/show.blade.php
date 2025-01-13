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

        <!-- Event Section -->
        <div class="flex flex-wrap md:flex-nowrap gap-6 bg-white shadow-md rounded-lg overflow-hidden p-6">
            <!-- Left: Event Image -->
            <div class="w-full md:w-1/2">
                @if ($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="Event Banner"
                        class="w-full h-full object-cover rounded-lg shadow">
                @else
                    <div class="bg-gray-200 w-full h-64 flex items-center justify-center rounded-lg">
                        <p class="text-gray-500 text-lg font-semibold">No Image Available</p>
                    </div>
                @endif
            </div>


            <!-- Right: Event Details -->
            <div class="w-full md:w-1/2 flex flex-col justify-between">
                <!-- Event Date -->
                <p class="text-gray-500 text-3xl uppercase mb-2">
                    {{ \Carbon\Carbon::parse($event->date)->format('d M') }}
                </p>

                <!-- Event Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $event->name }}</h1>

                <!-- Organizer -->
                <p class="text-gray-600 text-lg mb-4">
                    By <span class="font-semibold">{{ $event->organizer_name }}</span>
                </p>

                <!-- Location -->
                <p class="text-gray-700 text-lg flex items-center mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ $event->location }}
                </p>

                <!-- Date and Time -->
                <p class="text-gray-700 text-lg flex items-center">
                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                    {{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y g:i A') }} -
                    {{ \Carbon\Carbon::parse($event->end_time)->format('F j, Y g:i A') }}
                </p>
                <!-- Event Description -->
                <div class="mt-20 mb-6">
                    <h2 class="text-3xl font-bold text-gray-700">Description</h2>
                    <p class=" text-lg text-gray-600 whitespace-pre-line">
                        {{ $event->description }}
                    </p>
                </div>

                <p class="flex items-center space-x-2">
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

                @if ($event->ticketTypes->isNotEmpty())
                    <ul>
                        @foreach ($event->ticketTypes as $ticketType)
                            <li>
                                {{ $ticketType->name }} - Kshs{{ $ticketType->price }}
                                @if ($ticketType->complimentary)
                                    (Complimentary)
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-red-500">No ticket types available for this event.</p>
                @endif

                <!-- Actions -->
                <div class="flex flex-row space-x-5 ">
                    @can('edit event')
                        <a href="{{ route('events.edit', $event->id) }}"
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
        </div>
    </div>
</x-app-layout>
